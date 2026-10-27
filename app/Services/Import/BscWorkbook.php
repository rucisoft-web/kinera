<?php

namespace App\Services\Import;

use App\Models\Kpi;
use App\Models\StrategicInitiative;
use App\Support\Terms;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Format workbook Excel untuk mengisi data satu perusahaan/perioda.
 * Dipakai bersama oleh BscTemplate (unduh template) dan BscImporter (upload).
 */
class BscWorkbook
{
    public const MONTH_COLUMNS = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];

    /** Urutan sheet = urutan proses import. Kolom bertanda * wajib diisi. */
    public const SHEETS = [
        'Jati Diri' => [
            'columns' => ['bagian*', 'isi*'],
            'help' => 'Satu baris per bagian: visi, misi, tujuan, tagline, janji_merek, proposisi_nilai, budaya, sejarah.',
        ],
        'Nilai Inti' => [
            'columns' => ['nama*', 'nama_lokal', 'deskripsi', 'perilaku'],
            'help' => 'Nama nilai inti menjadi kunci; upload ulang memperbarui deskripsi.',
        ],
        'Tema' => [
            'columns' => ['kode*', 'nama*', 'deskripsi', 'warna'],
            'help' => 'Tema strategis (opsional). Warna dalam format #RRGGBB.',
        ],
        'Sasaran' => [
            'columns' => ['kode*', 'perspektif*', 'nama_sasaran*', 'kode_tema', 'deskripsi', 'bobot', 'email_pemilik'],
            'help' => 'Perspektif: Keuangan, Kepuasan Pelanggan, Proses Internal, atau SDM & Pembelajaran. Bobot kosong = 1.',
        ],
        'Relasi Sasaran' => [
            'columns' => ['kode_penyebab*', 'kode_akibat*', 'kekuatan', 'catatan'],
            'help' => 'Hubungan sebab-akibat untuk Peta Strategi. Kekuatan 1 (lemah) s.d. 3 (kuat), kosong = 2.',
        ],
        'KPI' => [
            'columns' => ['kode*', 'kode_sasaran*', 'nama_kpi*', 'satuan*', 'polaritas', 'frekuensi', 'agregasi', 'bobot', 'baseline',
                'target_tahunan', 'desimal', 'kpi_keuangan', 'kpi_kritis', 'sumber_data', 'email_pemilik', 'deskripsi'],
            'help' => 'Polaritas: naik (makin besar makin baik) atau turun. Frekuensi: bulanan, mingguan, kuartalan, semesteran, tahunan. '
                .'Agregasi YTD: jumlah, rata-rata, atau terakhir. kpi_keuangan/kpi_kritis: ya/tidak.',
        ],
        'Target' => [
            'columns' => ['kode_kpi*', 'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des', 'catatan'],
            'help' => 'Target per bulan. KPI tahunan cukup diisi di kolom des. Sel kosong dilewati.',
        ],
        'Realisasi' => [
            'columns' => ['kode_kpi*', 'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des', 'catatan', 'bukti'],
            'help' => 'Realisasi per bulan. Masuk sebagai draf lalu mengikuti alur verifikasi dan persetujuan, '
                .'kecuali pengunggah memilih "setujui langsung". Sel kosong dilewati.',
        ],
        'Inisiatif' => [
            'columns' => ['kode*', 'nama_inisiatif*', 'kode_sasaran', 'kode_tema', 'email_pic', 'mulai', 'selesai', 'status', 'dampak',
                'urgensi', 'risiko', 'tingkat_anggaran', 'anggaran', 'deskripsi', 'hasil_diharapkan'],
            'help' => 'kode_sasaran boleh lebih dari satu, pisahkan dengan koma. Tanggal YYYY-MM-DD. Dampak/urgensi/risiko/tingkat_anggaran 1-5.',
        ],
    ];

    public const ATMAN_FIELDS = [
        'visi' => 'vision', 'misi' => 'mission', 'tujuan' => 'purpose', 'tagline' => 'tagline',
        'janji_merek' => 'brand_promise', 'proposisi_nilai' => 'value_proposition', 'budaya' => 'culture_statement', 'sejarah' => 'history',
    ];

    /** Kunci sheet yang tahan beda penulisan: "Relasi Sasaran" -> "relasisasaran". */
    public static function sheetKey(string $name): string
    {
        return preg_replace('/[^a-z0-9]/', '', Str::lower(Str::ascii($name)));
    }

    /** Nama sheet resmi dari nama sheet di file, atau null bila tidak dikenali. */
    public static function canonicalSheet(string $name): ?string
    {
        $key = static::sheetKey($name);
        $aliases = ['jatidiri' => 'Jati Diri', 'identitas' => 'Jati Diri', 'nilaiinti' => 'Nilai Inti', 'nilai' => 'Nilai Inti',
            'tema' => 'Tema', 'temastrategis' => 'Tema', 'sasaran' => 'Sasaran', 'sasaranstrategis' => 'Sasaran',
            'relasisasaran' => 'Relasi Sasaran', 'relasi' => 'Relasi Sasaran', 'petastrategi' => 'Relasi Sasaran',
            'kpi' => 'KPI', 'indikator' => 'KPI', 'target' => 'Target', 'targetkpi' => 'Target',
            'realisasi' => 'Realisasi', 'realisasikpi' => 'Realisasi', 'inisiatif' => 'Inisiatif', 'inisiatifstrategis' => 'Inisiatif'];

        return $aliases[$key] ?? null;
    }

    /** "Nama KPI *" -> "nama_kpi" */
    public static function headerKey(mixed $header): string
    {
        $h = Str::lower(Str::ascii(trim((string) $header)));
        $h = preg_replace('/\(.*?\)|\*/', '', $h);

        return trim(preg_replace('/[^a-z0-9]+/', '_', $h), '_');
    }

    public static function isBlank(mixed $v): bool
    {
        return $v === null || (is_string($v) && trim($v) === '');
    }

    public static function text(mixed $v): ?string
    {
        if ($v instanceof DateTimeInterface) {
            return $v->format('Y-m-d');
        }
        if (static::isBlank($v)) {
            return null;
        }
        if (is_float($v) && floor($v) === $v) {
            $v = (int) $v;
        }

        return trim((string) $v);
    }

    /** Angka dari sel Excel atau teks format Indonesia: "11.098.665", "6,62", "Rp 1.234,5", "12%". */
    public static function number(mixed $v): ?float
    {
        if (is_int($v) || is_float($v)) {
            return (float) $v;
        }
        if (static::isBlank($v)) {
            return null;
        }
        $s = preg_replace('/[^0-9,.\-]/', '', (string) $v);
        if ($s === '' || $s === '-') {
            throw new ImportRowException('Angka tidak valid: "'.$v.'"');
        }
        if (str_contains($s, '.') && str_contains($s, ',')) {
            $s = str_replace(',', '.', str_replace('.', '', $s));
        } elseif (str_contains($s, ',')) {
            $s = str_replace(',', '.', $s);
        } elseif (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $s)) {
            $s = str_replace('.', '', $s);
        }
        if (! is_numeric($s)) {
            throw new ImportRowException('Angka tidak valid: "'.$v.'"');
        }

        return (float) $s;
    }

    public static function bool(mixed $v): bool
    {
        return in_array(Str::lower((string) static::text($v)), ['ya', 'y', 'yes', 'true', '1', 'x', 'v'], true);
    }

    public static function perspective(mixed $v): string
    {
        $s = static::sheetKey((string) static::text($v));
        foreach (Terms::PERSPECTIVES as $key) {
            if ($s === $key || $s === static::sheetKey(Terms::get($key))) {
                return $key;
            }
        }
        $map = [
            'keuangan' => ['keuangan', 'financial', 'finansial', 'artha'],
            'pelanggan' => ['pelanggan', 'customer', 'kama', 'konsumen'],
            'proses' => ['proses', 'internal', 'process', 'dharma'],
            'sdm' => ['sdm', 'pembelajaran', 'learning', 'pertumbuhan', 'swadharma', 'manusia'],
        ];
        foreach ($map as $key => $needles) {
            foreach ($needles as $n) {
                if ($s !== '' && str_contains($s, $n)) {
                    return $key;
                }
            }
        }
        throw new ImportRowException('Perspektif tidak dikenali: "'.$v.'". Pakai '.implode(', ', Terms::perspectives()).'.');
    }

    public static function direction(mixed $v): string
    {
        $s = static::sheetKey((string) static::text($v));

        return match (true) {
            $s === '' => 'higher',
            in_array($s, ['naik', 'tinggi', 'higher', 'up', 'makinbesarmakinbaik', 'makintinggimakinbaik', 'maksimal'], true) => 'higher',
            in_array($s, ['turun', 'rendah', 'lower', 'down', 'makinkecilmakinbaik', 'makinrendahmakinbaik', 'minimal'], true) => 'lower',
            default => throw new ImportRowException('Polaritas tidak dikenali: "'.$v.'". Pakai naik atau turun.'),
        };
    }

    public static function frequency(mixed $v): string
    {
        $s = static::sheetKey((string) static::text($v));
        if ($s === '') {
            return 'bulanan';
        }
        $aliases = ['triwulan' => 'kuartalan', 'triwulanan' => 'kuartalan', 'kuartal' => 'kuartalan', 'semester' => 'semesteran',
            'tahun' => 'tahunan', 'bulan' => 'bulanan', 'minggu' => 'mingguan', 'monthly' => 'bulanan', 'yearly' => 'tahunan', 'annual' => 'tahunan'];
        $s = $aliases[$s] ?? $s;
        if (! array_key_exists($s, Kpi::FREQUENCIES)) {
            throw new ImportRowException('Frekuensi tidak dikenali: "'.$v.'". Pakai '.implode(', ', array_keys(Kpi::FREQUENCIES)).'.');
        }

        return $s;
    }

    public static function aggregation(mixed $v): string
    {
        $s = static::sheetKey((string) static::text($v));

        return match (true) {
            $s === '', in_array($s, ['jumlah', 'sum', 'akumulasi', 'total'], true) => 'sum',
            in_array($s, ['ratarata', 'rata', 'average', 'avg'], true) => 'average',
            in_array($s, ['terakhir', 'last', 'akhir', 'posisiakhir'], true) => 'last',
            default => throw new ImportRowException('Agregasi tidak dikenali: "'.$v.'". Pakai jumlah, rata-rata, atau terakhir.'),
        };
    }

    public static function initiativeStatus(mixed $v): string
    {
        $s = Str::lower((string) static::text($v));
        if ($s === '') {
            return 'direncanakan';
        }
        foreach (StrategicInitiative::STATUSES as $key => $label) {
            if ($s === $key || $s === Str::lower($label)) {
                return $key;
            }
        }
        throw new ImportRowException('Status inisiatif tidak dikenali: "'.$v.'". Pakai '.implode(', ', array_keys(StrategicInitiative::STATUSES)).'.');
    }

    public static function scale(mixed $v, int $default = 3): int
    {
        $n = static::number($v);
        if ($n === null) {
            return $default;
        }
        if ($n < 1 || $n > 5) {
            throw new ImportRowException('Nilai skala harus 1 sampai 5, bukan '.$v.'.');
        }

        return (int) round($n);
    }

    public static function date(mixed $v): ?string
    {
        if ($v instanceof DateTimeInterface) {
            return $v->format('Y-m-d');
        }
        $s = static::text($v);
        if ($s === null) {
            return null;
        }
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'] as $f) {
            $d = \DateTime::createFromFormat('!'.$f, $s);
            if ($d && $d->format($f) === $s) {
                return $d->format('Y-m-d');
            }
        }
        if (is_numeric($s) && (float) $s > 20000) { // nomor seri tanggal Excel
            return Carbon::create(1899, 12, 30)->addDays((int) $s)->format('Y-m-d');
        }
        throw new ImportRowException('Tanggal tidak valid: "'.$s.'". Pakai format YYYY-MM-DD.');
    }

    /** Bulan 1-12 dari angka, nama bulan, "2026-03", atau tanggal. */
    public static function month(mixed $v): int
    {
        if ($v instanceof DateTimeInterface) {
            return (int) $v->format('n');
        }
        $s = Str::lower((string) static::text($v));
        if (preg_match('/^\d{4}-(\d{1,2})/', $s, $m)) {
            $s = $m[1];
        }
        if (is_numeric($s) && (int) $s >= 1 && (int) $s <= 12) {
            return (int) $s;
        }
        foreach (Terms::months() as $n => $name) {
            if ($s !== '' && (str_starts_with(Str::lower($name), $s) || $s === static::MONTH_COLUMNS[$n - 1])) {
                return $n;
            }
        }
        throw new ImportRowException('Bulan tidak valid: "'.$v.'".');
    }

    /** @return array<int,string> */
    public static function codes(mixed $v): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[,;]/', (string) static::text($v)))));
    }
}
