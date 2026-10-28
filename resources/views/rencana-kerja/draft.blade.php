@php
    use App\Support\Fmt;
    $year = $draft->year;
    $name = $f['instansi'] ?? $company?->name;
    $leader = $f['pimpinan'] ?? 'Pimpinan';
    $kpis = collect($f['sasaran'] ?? [])->flatMap(fn ($o) => collect($o['iku'])->map(fn ($k) => $k + ['sasaran_kode' => $o['kode']]))->keyBy('kode');
    $inits = collect($f['inisiatif'] ?? [])->keyBy('kode');
    $programs = collect($ai['program'] ?? []);
    $usulan = ($f['tahun_rencana'] ?? $year) !== ($f['tahun_data'] ?? $year);
    $tw = ['I', 'II', 'III', 'IV'];
    $isAi = $draft->mode === 'ai';
    $totalPagu = $programs->sum(fn ($p) => $inits[$p['kode_inisiatif']]['pagu'] ?? 0);
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $draft->title }}</title>
<style>
    @page { size: A4; margin: 20mm 18mm; }
    body { font-family: "Times New Roman", Georgia, serif; color: #111; font-size: 11.5pt; line-height: 1.5; margin: 0; background: {{ $word ? '#fff' : '#eef0f2' }}; }
    .sheet { background: #fff; max-width: 190mm; margin: 12px auto; padding: 16mm 14mm; position: relative; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
    h1 { font-size: 14pt; text-align: center; margin: 0 0 .8rem; text-transform: uppercase; }
    h1 small { font-size: 12.5pt; }
    h2 { font-size: 12pt; margin: 1.1rem 0 .4rem; }
    h3 { font-size: 11.5pt; margin: .9rem 0 .3rem; font-style: italic; }
    p { text-align: justify; margin: .35rem 0; }
    table { width: 100%; border-collapse: collapse; margin: .5rem 0 .8rem; font-size: 9.5pt; line-height: 1.3; }
    th, td { border: 1px solid #444; padding: 3px 5px; vertical-align: top; }
    th { background: #e6ece9; }
    td.num, th.num { text-align: right; white-space: nowrap; }
    td.c, th.c { text-align: center; }
    td.prop { font-style: italic; color: #444; }
    tr.grp td { background: #f3f5f4; font-weight: bold; }
    .pb { page-break-before: always; }
    .cover { text-align: center; padding-top: 30mm; min-height: 230mm; position: relative; }
    .cover .draft { display: inline-block; border: 2px solid #8a1c1c; color: #8a1c1c; font: 700 11pt Arial, sans-serif; letter-spacing: .3em; padding: 4px 14px; }
    .cover .t1 { font-size: 20pt; font-weight: bold; margin: 18mm 0 4mm; letter-spacing: .04em; }
    .cover .t2 { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
    .cover .t3 { font-size: 14pt; margin-top: 4mm; }
    .cover .foot { margin-top: 60mm; font-size: 10pt; color: #333; }
    .note { border-left: 3px solid #b7791f; background: #fdf8ee; padding: 6px 10px; font: 9.5pt/1.45 Arial, sans-serif; margin: .6rem 0; }
    .muted { color: #555; font-size: 9.5pt; }
    .toc td { border: none; padding: 2px 4px; font-size: 11pt; }
    .toc td.l { padding-left: 1.4rem; }
    ol, ul { margin: .3rem 0 .5rem 1.3rem; padding: 0; }
    li { margin: .15rem 0; text-align: justify; }
    .demo { border: 2px solid #b42318; color: #b42318; padding: 6px 10px; font: 600 9.5pt/1.35 Arial, sans-serif; margin-bottom: 10px; text-align: left; }
    .wm { position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; font: 700 80pt Arial, sans-serif; color: rgba(180,35,24,.06); transform: rotate(-30deg); z-index: 0; }
    .toolbar { max-width: 190mm; margin: 12px auto 0; font: 10pt Arial, sans-serif; display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }
    .toolbar button, .toolbar a { padding: 6px 12px; cursor: pointer; font: inherit; border: 1px solid #999; background: #fff; color: #111; text-decoration: none; border-radius: 4px; }
    .persp { font-weight: bold; color: #fff; padding: 2px 6px; }
    @media print { body { background: #fff; } .sheet { box-shadow: none; margin: 0; padding: 0; max-width: none; } .toolbar { display: none; } }
</style>
</head>
<body>
@unless ($word)
<div class="toolbar">
    <button onclick="window.print()">🖶 Cetak / simpan PDF</button>
    <a href="{{ request()->fullUrlWithQuery(['format' => 'word']) }}">⬇ Unduh Word</a>
    <button onclick="window.close()">Tutup</button>
    <span class="muted">{{ $draft->title }} · {{ $isAi ? 'disusun dengan Claude' : 'mode contoh tanpa AI' }}</span>
</div>
@endunless
<div class="sheet">
@if ($demo && ! $word)
    <div class="wm">DATA DEMO</div>
@endif

<div class="cover">
    @if ($demo)<div class="demo">{{ $demo }}</div>@endif
    <span class="draft">DRAF</span>
    <div class="t1">RENCANA KERJA</div>
    <div class="t2">{{ $name }}</div>
    <div class="t3">TAHUN {{ $year }}</div>
    <div class="foot">
        @if ($isAi)
            Draf disusun dengan bantuan Claude dari isian Kinera{{ count($draft->attachments ?? []) ? ' dan '.count($draft->attachments).' lampiran' : '' }}<br>
        @else
            Draf disusun otomatis dari isian Kinera (mode contoh tanpa AI)<br>
        @endif
        pada {{ $draft->created_at->locale('id')->translatedFormat('j F Y') }}. Narasi perlu ditelaah dan ditetapkan oleh {{ $leader }}.
    </div>
</div>

<div class="pb"></div>
<h1>Daftar Isi</h1>
<table class="toc">
    <tr><td>BAB I</td><td>PENDAHULUAN</td></tr>
    <tr><td>BAB II</td><td>SASARAN DAN TARGET KINERJA TAHUN {{ $year }}</td></tr>
    <tr><td>BAB III</td><td>PROGRAM DAN KEGIATAN</td></tr>
    <tr><td>BAB IV</td><td>JADWAL PELAKSANAAN</td></tr>
    <tr><td>BAB V</td><td>RENCANA ANGGARAN</td></tr>
    <tr><td>BAB VI</td><td>RISIKO DAN PEMANTAUAN</td></tr>
    <tr><td>BAB VII</td><td>PENUTUP</td></tr>
    <tr><td>LAMPIRAN</td><td>Catatan Penyusunan Draf</td></tr>
</table>

<div class="pb"></div>
<h1>Bab I<br><small>Pendahuluan</small></h1>
@if (filled($ai['ringkasan'] ?? null))<div class="note"><strong>Ringkasan.</strong> {{ $ai['ringkasan'] }}</div>@endif
<h2>1.1 Latar Belakang</h2>
@foreach (preg_split('/\n\s*\n/', (string) ($ai['latar_belakang'] ?? '')) as $para)<p>{{ $para }}</p>@endforeach
<h2>1.2 Maksud dan Tujuan</h2>
<p>{{ $ai['maksud_tujuan'] ?? '' }}</p>
@if (filled($f['visi'] ?? null))<p>Rencana Kerja ini mendukung visi “{{ $f['visi'] }}”.</p>@endif
@php $sec = 2; @endphp
@if (! empty($ai['dasar_hukum']))
<h2>1.{{ ++$sec }} Dasar Hukum</h2>
<ol>@foreach ($ai['dasar_hukum'] as $d)<li>{{ $d }}</li>@endforeach</ol>
@endif
@if (! empty($ai['arah_kebijakan']))
<h2>1.{{ ++$sec }} Arah Kebijakan Tahun {{ $year }}</h2>
<ol>@foreach ($ai['arah_kebijakan'] as $a)<li>{{ $a }}</li>@endforeach</ol>
@endif

<div class="pb"></div>
<h1>Bab II<br><small>Sasaran dan Target Kinerja Tahun {{ $year }}</small></h1>
<p>Sasaran, indikator, baseline, dan target berikut diambil langsung dari isian Kinera.@if ($usulan) Target tahun {{ $year }} (dicetak miring) adalah <strong>usulan otomatis</strong> dari tren target {{ $f['tahun_data'] }} terhadap baseline dan perlu ditetapkan dalam Perjanjian Kinerja.@endif</p>
<table>
    <thead><tr><th style="width:22%">Sasaran strategis</th><th style="width:7%">Kode</th><th>Indikator kinerja utama</th><th class="num" style="width:12%">Baseline</th><th class="num" style="width:12%">Target {{ $year }}</th></tr></thead>
    <tbody>
    @foreach (collect($f['sasaran'] ?? [])->groupBy('perspektif') as $persp => $objs)
        <tr class="grp"><td colspan="5">Perspektif {{ $persp }}</td></tr>
        @foreach ($objs as $o)
            @foreach ($o['iku'] as $n => $k)
                <tr>@if ($n === 0)<td rowspan="{{ count($o['iku']) }}">{{ $o['kode'] }} {{ $o['sasaran'] }}</td>@endif
                    <td>{{ $k['kode'] }}</td><td>{{ $k['nama'] }}</td>
                    <td class="num">{{ Fmt::kpiValue($k['baseline'], $k['satuan'], $k['desimal']) }}</td>
                    <td class="num {{ $k['target_usulan'] ? 'prop' : '' }}">{{ Fmt::kpiValue($k['target'], $k['satuan'], $k['desimal']) }}</td></tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>

<div class="pb"></div>
<h1>Bab III<br><small>Program dan Kegiatan</small></h1>
<p>Target kinerja dicapai melalui {{ $programs->count() }} program berikut.</p>
@foreach ($programs as $pi => $p)
    @php $init = $inits[$p['kode_inisiatif']] ?? null; @endphp
    <h2>3.{{ $pi + 1 }} {{ $p['nama'] }}</h2>
    <p class="muted">{{ $init ? 'Kode '.$init['kode'].' · ' : 'Program baru (belum tercatat di Kinera) · ' }}Sasaran: {{ implode(', ', $p['sasaran_kode'] ?? []) ?: '-' }}{{ $init ? ' · Penanggung jawab: '.($init['penanggung_jawab'] ?? '-') : '' }} · Pagu: {{ $init && $init['pagu'] ? Fmt::rp($init['pagu']).($init['pagu_indikatif'] ? ' (indikatif)' : '') : 'belum ditetapkan' }}</p>
    @if (filled($p['uraian'] ?? null))<p>{{ $p['uraian'] }}</p>@endif
    <table>
        <thead><tr><th style="width:4%">No</th><th>Kegiatan</th><th style="width:28%">Keluaran</th><th style="width:12%">IKU</th><th style="width:20%">Penanggung jawab</th></tr></thead>
        <tbody>
        @foreach ($p['kegiatan'] ?? [] as $n => $k)
            <tr><td class="c">{{ $n + 1 }}</td><td>{{ $k['nama'] }}</td><td>{{ $k['keluaran'] }}</td>
                <td>{{ collect($k['iku_kode'] ?? [])->filter(fn ($c) => isset($kpis[$c]))->implode(', ') ?: '-' }}</td><td>{{ $k['penanggung_jawab'] }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endforeach

<div class="pb"></div>
<h1>Bab IV<br><small>Jadwal Pelaksanaan</small></h1>
<table>
    <thead><tr><th style="width:9%">Program</th><th>Kegiatan</th>@foreach ($tw as $t)<th class="c" style="width:7%">TW {{ $t }}</th>@endforeach</tr></thead>
    <tbody>
    @foreach ($programs as $pi => $p)
        @foreach ($p['kegiatan'] ?? [] as $n => $k)
            <tr>@if ($n === 0)<td rowspan="{{ count($p['kegiatan']) }}">3.{{ $pi + 1 }}</td>@endif<td>{{ $k['nama'] }}</td>
                @foreach ([1, 2, 3, 4] as $q)<td class="c" style="{{ in_array($q, $k['triwulan'] ?? [], true) ? 'background:#0f6b5c;color:#fff' : '' }}">{{ in_array($q, $k['triwulan'] ?? [], true) ? '■' : '' }}</td>@endforeach</tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<h1 class="pb" style="padding-top:.5rem">Bab V<br><small>Rencana Anggaran</small></h1>
<p>Pagu program bersumber dari rencana anggaran inisiatif di Kinera{{ $usulan ? '; untuk tahun '.$year.' pagu bersifat indikatif (pagu '.$f['tahun_data'].' dengan asumsi kenaikan '.(int) (\App\Services\RenstraDraftService::BUDGET_GROWTH * 100).'%) dan perlu diselaraskan dengan rencana kerja dan anggaran' : '' }}.</p>
<table>
    <thead><tr><th style="width:5%">No</th><th>Program</th><th style="width:18%">Sumber dana</th><th class="num" style="width:22%">Pagu {{ $year }}</th></tr></thead>
    <tbody>
    @foreach ($programs as $pi => $p)
        @php $init = $inits[$p['kode_inisiatif']] ?? null; @endphp
        <tr><td class="c">3.{{ $pi + 1 }}</td><td>{{ $p['nama'] }}</td><td>{{ $init['sumber_dana'] ?? '-' }}</td>
            <td class="num {{ $usulan ? 'prop' : '' }}">{{ $init && $init['pagu'] ? Fmt::rp($init['pagu']) : 'belum ditetapkan' }}</td></tr>
    @endforeach
    <tr class="grp"><td colspan="3">Jumlah</td><td class="num">{{ Fmt::rp($totalPagu) }}</td></tr>
    </tbody>
</table>

<h1 class="pb" style="padding-top:.5rem">Bab VI<br><small>Risiko dan Pemantauan</small></h1>
<h2>6.1 Risiko dan Mitigasi</h2>
<table>
    <thead><tr><th style="width:5%">No</th><th style="width:45%">Risiko</th><th>Mitigasi</th></tr></thead>
    <tbody>
    @foreach ($ai['risiko'] ?? [] as $n => $r)
        <tr><td class="c">{{ $n + 1 }}</td><td>{{ $r['risiko'] }}</td><td>{{ $r['mitigasi'] }}</td></tr>
    @endforeach
    </tbody>
</table>
<h2>6.2 Pemantauan dan Evaluasi</h2>
<p>{{ $ai['pemantauan'] ?? '' }}</p>

<h1 style="padding-top:1rem">Bab VII<br><small>Penutup</small></h1>
<p>{{ $ai['penutup'] ?? '' }}</p>
<table class="toc" style="margin-top:2rem"><tr><td style="width:55%"></td><td class="c">………………, ……………… {{ $year }}<br>{{ $leader }} {{ $name }}<br><br><br><br>……………………………………</td></tr></table>

<div class="pb"></div>
<h1>Lampiran<br><small>Catatan Penyusunan Draf</small></h1>
<div class="note">
    <p><strong>Cara penyusunan:</strong> {{ $isAi ? 'narasi, rincian kegiatan, jadwal, dan risiko ditulis oleh Claude ('.$draft->model.') berdasarkan isian Kinera dan lampiran; sasaran, IKU, target, dan pagu dicetak langsung dari Kinera.' : 'mode contoh tanpa AI; seluruh isi dirangkai dari isian Kinera dengan kalimat baku. Pasang kunci API Anthropic untuk mengaktifkan penyusunan dengan Claude.' }}</p>
    @if (! empty($draft->attachments))<p><strong>Lampiran yang dipakai:</strong> {{ collect($draft->attachments)->pluck('name')->implode(', ') }}</p>@endif
    @if ($draft->instructions)<p><strong>Arahan penyusun:</strong> {{ $draft->instructions }}</p>@endif
    @if (! empty($ai['sumber_rujukan']))<p><strong>Rujukan yang disebut:</strong> {{ implode('; ', $ai['sumber_rujukan']) }}</p>@endif
    <p><strong>Wajib ditelaah:</strong> seluruh narasi, kesesuaian kegiatan dengan anggaran, dan target {{ $usulan ? 'serta pagu ' : '' }}yang bertanda usulan/indikatif. Hasil AI dapat keliru; dokumen ini belum berlaku sebelum ditetapkan.</p>
</div>
</div>
</body>
</html>
