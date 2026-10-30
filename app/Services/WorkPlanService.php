<?php

namespace App\Services;

use App\Models\AtmanProfile;
use App\Models\Finding;
use App\Models\Kpi;
use App\Models\StrategicInitiative;
use App\Models\StrategicIssue;
use App\Models\StrategicObjective;
use App\Models\TowsStrategy;
use App\Models\User;
use App\Models\WorkPlanDraft;
use App\Services\Ai\ClaudeWriter;
use App\Support\BscContext;
use App\Support\Terms;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Throwable;
use ZipArchive;

/**
 * Menyusun Draf Rencana Kerja tahunan: fakta (sasaran, IKU, target, inisiatif, anggaran) diambil dari Kinera,
 * narasi dan rincian kegiatan ditulis Claude dengan memperhatikan lampiran. Tanpa kunci API dipakai mode contoh.
 */
class WorkPlanService
{
    public const MAX_FILES = 5;

    public const MAX_KB = 10240;

    public const MIMES = ['pdf', 'docx', 'txt', 'md', 'csv'];

    public function __construct(private BscContext $ctx, private RenstraDraftService $renstra, private ClaudeWriter $claude) {}

    public function aiAvailable(): bool
    {
        return $this->claude->available();
    }

    /** @return array<int> tahun yang dapat disusun: tahun periode aktif dan tahun berikutnya */
    public function years(): array
    {
        $y = $this->ctx->year();

        return [$y, $y + 1];
    }

    /** Fakta dari isian Kinera yang menjadi bahan dan angka resmi dokumen. */
    public function facts(int $year): array
    {
        $py = $this->ctx->year();
        $profile = AtmanProfile::first();

        $objectives = StrategicObjective::with(['owner', 'kpis' => fn ($q) => $q->where('is_active', true)->orderBy('code')])
            ->orderByRaw("CASE perspective WHEN 'pelanggan' THEN 1 WHEN 'keuangan' THEN 2 WHEN 'proses' THEN 3 ELSE 4 END")
            ->orderBy('sort')->orderBy('code')->get()
            ->map(fn (StrategicObjective $o) => [
                'kode' => $o->code,
                'sasaran' => $o->title,
                'perspektif' => Terms::perspective($o->perspective),
                'penanggung_jawab' => $o->owner?->job_title ?: $o->owner?->name,
                'iku' => $o->kpis->map(fn (Kpi $k) => [
                    'kode' => $k->code,
                    'nama' => $k->name,
                    'satuan' => $k->unit,
                    'desimal' => (int) $k->decimals,
                    'arah' => $k->direction === 'lower' ? 'semakin kecil semakin baik' : 'semakin besar semakin baik',
                    'baseline' => $k->baseline === null ? null : (float) $k->baseline,
                    'target' => $this->renstra->projectTarget($k, $year, $py),
                    'target_usulan' => $year !== $py,
                ])->values()->all(),
            ])->values()->all();

        $initiatives = StrategicInitiative::with(['owner', 'objectives', 'budgets', 'activities' => fn ($q) => $q->orderBy('start_date')])
            ->orderBy('code')->get()
            ->map(function (StrategicInitiative $i) use ($year, $py) {
                $base = (float) ($i->budgets->sum('planned_amount') ?: $i->budget_planned ?: 0);

                return [
                    'kode' => $i->code,
                    'nama' => $i->title,
                    'uraian' => $i->description,
                    'sasaran' => $i->objectives->pluck('code')->values()->all(),
                    'penanggung_jawab' => $i->owner?->job_title ?: $i->owner?->name,
                    'mulai' => $i->start_date?->toDateString(),
                    'selesai' => $i->end_date?->toDateString(),
                    'status' => $i->status,
                    'progres' => (int) $i->progress,
                    'hasil_diharapkan' => $i->expected_outcome,
                    'pagu' => $base > 0 ? round($base * (1 + RenstraDraftService::BUDGET_GROWTH) ** ($year - $py), $year === $py ? 0 : -6) : null,
                    'pagu_indikatif' => $year !== $py,
                    'sumber_dana' => $i->budgets->pluck('funding_source')->filter()->unique()->implode(', ') ?: null,
                    'kegiatan' => $i->activities->map(fn ($a) => [
                        'nama' => $a->name,
                        'mulai' => $a->start_date?->toDateString(),
                        'tenggat' => $a->due_date?->toDateString(),
                        'status' => $a->status,
                        'progres' => (int) $a->progress,
                    ])->values()->all(),
                ];
            })->values()->all();

        return [
            'instansi' => $this->ctx->company()?->name,
            'pimpinan' => $this->ctx->company()?->leader_title ?: 'Pimpinan',
            'tahun_rencana' => $year,
            'tahun_data' => $py,
            'visi' => $profile?->vision,
            'misi' => $profile?->mission,
            'tujuan' => $profile?->purpose,
            'arah_strategi' => TowsStrategy::orderByDesc('priority')->get()->map(fn ($t) => $t->title.($t->description ? ': '.$t->description : ''))->values()->all(),
            'isu_strategis' => StrategicIssue::orderByDesc('urgency')->orderByDesc('impact')->get()->map(fn ($s) => $s->title.($s->description ? ': '.$s->description : ''))->values()->all(),
            'temuan_terbuka' => Finding::where('status', '!=', 'ditutup')->orderBy('code')->get()->map(fn ($f) => trim($f->title.' ('.$f->severity.')'))->values()->all(),
            'sasaran' => $objectives,
            'inisiatif' => $initiatives,
        ];
    }

    /**
     * @param  array<int,UploadedFile>  $files
     */
    public function generate(int $year, ?string $instructions, array $files, ?User $user): WorkPlanDraft
    {
        $year = in_array($year, $this->years(), true) ? $year : $this->ctx->year();
        $facts = $this->facts($year);
        [$blocks, $meta] = $this->attachments($files);

        $draft = new WorkPlanDraft([
            'year' => $year,
            'title' => 'Rencana Kerja '.$facts['instansi'].' Tahun '.$year,
            'instructions' => $instructions ?: null,
            'attachments' => $meta,
            'created_by' => $user?->id,
        ]);

        if (! $this->aiAvailable()) {
            $draft->fill(['mode' => 'contoh', 'status' => 'selesai', 'content' => ['facts' => $facts, 'ai' => $this->sample($facts)]])->save();

            return $draft;
        }

        $text = "Data isian Kinera (JSON):\n".json_encode($facts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            ."\n\nSusun Rencana Kerja tahun {$year} sesuai skema."
            .($instructions ? "\n\nArahan tambahan dari penyusun:\n".$instructions : '');

        try {
            $r = $this->claude->write($this->systemPrompt(), [...$blocks, ['type' => 'text', 'text' => $text]], $this->schema());
            $draft->fill([
                'mode' => 'ai', 'status' => 'selesai', 'model' => $r['model'],
                'input_tokens' => $r['input_tokens'], 'output_tokens' => $r['output_tokens'],
                'content' => ['facts' => $facts, 'ai' => $r['data']],
            ])->save();
        } catch (Throwable $e) {
            report($e);
            $draft->fill(['mode' => 'ai', 'status' => 'gagal', 'model' => $this->claude->model(), 'error' => mb_substr($e->getMessage(), 0, 1000)])->save();
        }

        return $draft;
    }

    /**
     * Lampiran menjadi blok dokumen: PDF dikirim utuh, DOCX diambil teksnya, TXT/MD/CSV sebagai teks.
     *
     * @param  array<int,UploadedFile>  $files
     * @return array{0: array<int,array>, 1: array<int,array>}
     */
    public function attachments(array $files): array
    {
        $blocks = [];
        $meta = [];
        foreach (array_slice($files, 0, self::MAX_FILES) as $file) {
            $name = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            $path = $file->getRealPath();
            if ($ext === 'pdf') {
                $blocks[] = ['type' => 'document', 'title' => $name, 'source' => ['type' => 'base64', 'mediaType' => 'application/pdf', 'data' => base64_encode((string) file_get_contents($path))]];
            } else {
                $text = $ext === 'docx' ? $this->docxText($path) : (string) file_get_contents($path);
                $text = trim(mb_convert_encoding($text, 'UTF-8', 'UTF-8'));
                if ($text === '') {
                    continue;
                }
                $blocks[] = ['type' => 'document', 'title' => $name, 'source' => ['type' => 'text', 'mediaType' => 'text/plain', 'data' => $text]];
            }
            $meta[] = ['name' => $name, 'size' => (int) $file->getSize()];
        }

        return [$blocks, $meta];
    }

    public function docxText(string $path): string
    {
        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            return '';
        }
        $xml = (string) $zip->getFromName('word/document.xml');
        $zip->close();
        $xml = preg_replace(['/<w:tab\/>/', '/<\/w:p>/', '/<w:br\/>/'], ["\t", "\n", "\n"], $xml);

        return html_entity_decode(strip_tags((string) $xml), ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    public function systemPrompt(): string
    {
        return <<<'TXT'
        Anda adalah penyusun dokumen perencanaan instansi pemerintah di Indonesia. Tugas Anda menyusun Draf Rencana Kerja tahunan dalam bahasa Indonesia baku yang lugas, siap disunting pimpinan.

        Ketentuan:
        - Data isian Kinera adalah sumber resmi. Sasaran, kode IKU, target, dan pagu tidak boleh diubah atau dikarang; rujuk dengan kode (misalnya F1, F1.1, atau kode inisiatif). Angka akan dicetak langsung dari Kinera.
        - Setiap inisiatif Kinera menjadi satu program; isi kode_inisiatif dengan kodenya. Program baru dari lampiran atau arahan boleh ditambahkan dengan kode_inisiatif kosong.
        - Rinci kegiatan yang konkret, keluaran yang terukur, IKU yang didukung, triwulan pelaksanaan (1–4), dan penanggung jawab berupa jabatan. Kegiatan yang sudah tercatat di Kinera dipertahankan dan dapat dilengkapi.
        - Manfaatkan lampiran (misalnya RKAT, arahan pimpinan, hasil evaluasi) untuk latar belakang, arah kebijakan, program, dan risiko. Sebutkan lampiran yang dipakai di sumber_rujukan.
        - Jangan menyebut nama orang; gunakan jabatan. Jangan mengarang nomor regulasi; tuliskan hanya yang ada di data atau lampiran.
        - Narasi ringkas: setiap paragraf 2–5 kalimat.
        TXT;
    }

    public function schema(): array
    {
        $str = ['type' => 'string'];
        $strs = ['type' => 'array', 'items' => $str];
        $obj = fn (array $props) => ['type' => 'object', 'properties' => $props, 'required' => array_keys($props), 'additionalProperties' => false];

        return $obj([
            'ringkasan' => $str,
            'latar_belakang' => $str,
            'maksud_tujuan' => $str,
            'dasar_hukum' => $strs,
            'arah_kebijakan' => $strs,
            'program' => ['type' => 'array', 'items' => $obj([
                'kode_inisiatif' => $str,
                'nama' => $str,
                'uraian' => $str,
                'sasaran_kode' => $strs,
                'kegiatan' => ['type' => 'array', 'items' => $obj([
                    'nama' => $str,
                    'keluaran' => $str,
                    'iku_kode' => $strs,
                    'triwulan' => ['type' => 'array', 'items' => ['type' => 'integer', 'enum' => [1, 2, 3, 4]]],
                    'penanggung_jawab' => $str,
                ])],
            ])],
            'risiko' => ['type' => 'array', 'items' => $obj(['risiko' => $str, 'mitigasi' => $str])],
            'pemantauan' => $str,
            'penutup' => $str,
            'sumber_rujukan' => $strs,
        ]);
    }

    /** Isi contoh tanpa AI: dirangkai dari data Kinera dengan kalimat baku. */
    public function sample(array $f): array
    {
        $year = $f['tahun_rencana'];
        $kpiByObjective = collect($f['sasaran'])->mapWithKeys(fn ($o) => [$o['kode'] => collect($o['iku'])->pluck('kode')->all()]);
        $quarters = function (?string $start, ?string $end) use ($year): array {
            $s = $start ? Carbon::parse($start) : null;
            $e = $end ? Carbon::parse($end) : null;
            if (! $s || ! $e || $e->lt($s)) {
                return [1, 2, 3, 4];
            }
            // Kegiatan dari tahun lain dipetakan ke triwulan yang sama pada tahun rencana.
            $shift = $e->year === $s->year ? $year - $s->year : 0;
            [$s, $e] = [$s->copy()->addYears($shift), $e->copy()->addYears($shift)];
            if ($e->year < $year || $s->year > $year) {
                return [1, 2, 3, 4];
            }
            $from = $s->year < $year ? 1 : $s->quarter;
            $to = $e->year > $year ? 4 : $e->quarter;

            return range($from, max($from, $to));
        };

        $programs = collect($f['inisiatif'])->map(fn ($i) => [
            'kode_inisiatif' => $i['kode'],
            'nama' => $i['nama'],
            'uraian' => trim(($i['uraian'] ?? '').' '.($i['hasil_diharapkan'] ? 'Hasil yang diharapkan: '.$i['hasil_diharapkan'] : '')),
            'sasaran_kode' => $i['sasaran'],
            'kegiatan' => collect($i['kegiatan'])->map(fn ($a) => [
                'nama' => $a['nama'],
                'keluaran' => 'Laporan pelaksanaan dan bukti dukung kegiatan',
                'iku_kode' => collect($i['sasaran'])->flatMap(fn ($o) => array_slice($kpiByObjective[$o] ?? [], 0, 1))->values()->all(),
                'triwulan' => $quarters($a['mulai'], $a['tenggat']),
                'penanggung_jawab' => $i['penanggung_jawab'] ?? '-',
            ])->whenEmpty(fn ($c) => $c->push([
                'nama' => 'Pelaksanaan '.$i['nama'],
                'keluaran' => $i['hasil_diharapkan'] ?: 'Laporan pelaksanaan program',
                'iku_kode' => collect($i['sasaran'])->flatMap(fn ($o) => array_slice($kpiByObjective[$o] ?? [], 0, 1))->values()->all(),
                'triwulan' => $quarters($i['mulai'], $i['selesai']),
                'penanggung_jawab' => $i['penanggung_jawab'] ?? '-',
            ]))->values()->all(),
        ])->values()->all();

        $nKpi = collect($f['sasaran'])->sum(fn ($o) => count($o['iku']));

        return [
            'ringkasan' => "Rencana Kerja Tahun {$year} menjabarkan ".count($f['sasaran'])." sasaran strategis dan {$nKpi} indikator kinerja utama ke dalam ".count($programs).' program beserta kegiatan, jadwal, dan penanggung jawabnya.',
            'latar_belakang' => "Rencana Kerja ini disusun sebagai penjabaran tahunan Rencana Strategis {$f['instansi']} dan menjadi acuan penyusunan anggaran serta Perjanjian Kinerja tahun {$year}. ".($f['isu_strategis'] ? 'Isu utama yang perlu dijawab antara lain: '.implode('; ', array_map(fn ($s) => explode(':', $s)[0], array_slice($f['isu_strategis'], 0, 3))).'.' : ''),
            'maksud_tujuan' => 'Rencana Kerja dimaksudkan sebagai pedoman pelaksanaan program dan kegiatan, serta alat untuk memantau pencapaian target kinerja secara berkala.',
            'dasar_hukum' => [],
            'arah_kebijakan' => array_map(fn ($s) => explode(':', $s)[0], $f['arah_strategi']),
            'program' => $programs,
            'risiko' => collect($f['temuan_terbuka'])->take(3)->map(fn ($t) => ['risiko' => 'Temuan belum tuntas: '.$t, 'mitigasi' => 'Tindak lanjut dipantau pada rapat evaluasi kinerja bulanan.'])
                ->push(['risiko' => 'Keterlambatan pelaksanaan kegiatan', 'mitigasi' => 'Pemantauan progres inisiatif setiap bulan di Kinera dan eskalasi ke pimpinan bila terlambat.'])->values()->all(),
            'pemantauan' => 'Capaian IKU dilaporkan sesuai frekuensi pengukuran, progres program diperbarui setiap bulan di Kinera, dan hasilnya dibahas dalam rapat evaluasi kinerja triwulanan.',
            'penutup' => "Rencana Kerja ini menjadi komitmen bersama seluruh unit kerja untuk mencapai target kinerja tahun {$year}.",
            'sumber_rujukan' => ['Isian Kinera (mode contoh tanpa AI)'],
        ];
    }
}
