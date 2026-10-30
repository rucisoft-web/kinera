@php
    use App\Models\PestelAnalysis;
    use App\Models\SwotItem;
    use App\Support\Fmt;
    use App\Support\Terms;
    $name = $company->name;
    $range = $start.'–'.$end;
    $leader = $company->leader_title ?: 'Pimpinan';
    $missions = collect(preg_split('/\r?\n/', (string) $profile?->mission))->map(fn ($m) => trim(preg_replace('/^\d+[.)]\s*/', '', $m)))->filter()->values();
    $towsLabels = ['SO' => 'Strategi S–O (kekuatan untuk meraih peluang)', 'WO' => 'Strategi W–O (memperbaiki kelemahan dengan peluang)', 'ST' => 'Strategi S–T (kekuatan untuk menghadapi ancaman)', 'WT' => 'Strategi W–T (meminimalkan kelemahan dan ancaman)'];
    $owner = fn ($u) => $u?->job_title ?: ($u?->name ?? '-');
    $dateFmt = fn ($d) => $d ? $d->locale('id')->translatedFormat('M Y') : '-';
    $sec = 0;
    $totalKpi = $kpiRows->count();
    $totalBase = $funding->sum('base');
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Draf Renstra {{ $name }} {{ $range }}</title>
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
    <span class="muted">Draf Renstra {{ $range }} · disusun otomatis dari isian Kinera</span>
</div>
@endunless
<div class="sheet">
@if ($demo && ! $word)
    <div class="wm">DATA DEMO</div>
@endif

{{-- ===== SAMPUL ===== --}}
<div class="cover">
    @if ($demo)<div class="demo">{{ $demo }}</div>@endif
    <span class="draft">DRAF</span>
    <div class="t1">RENCANA STRATEGIS</div>
    <div class="t2">{{ $name }}</div>
    <div class="t3">TAHUN {{ $range }}</div>
    <div class="foot">
        Draf disusun otomatis oleh Kinera dari isian periode {{ $period?->name ?? $periodYear }}<br>
        pada {{ now()->locale('id')->translatedFormat('j F Y') }}.<br>
        Narasi, target tahun berikutnya, dan pagu indikatif perlu ditelaah dan ditetapkan oleh {{ $leader }}.
    </div>
</div>

{{-- ===== DAFTAR ISI ===== --}}
<div class="pb"></div>
<h1>Daftar Isi</h1>
<table class="toc">
    <tr><td>BAB I</td><td>PENDAHULUAN</td></tr>
    <tr><td></td><td class="l">1.1 Latar Belakang · 1.2 Landasan Hukum · 1.3 Lingkungan Strategis · 1.4 Analisis SWOT · 1.5 Isu Strategis · 1.6 Pemangku Kepentingan</td></tr>
    <tr><td>BAB II</td><td>VISI, MISI, TUJUAN, DAN NILAI</td></tr>
    <tr><td>BAB III</td><td>ARAH KEBIJAKAN DAN STRATEGI</td></tr>
    <tr><td></td><td class="l">3.1 Strategi Hasil Analisis TOWS · 3.2 Tema dan Pilihan Strategis · 3.3 Peta Strategi</td></tr>
    <tr><td>BAB IV</td><td>SASARAN STRATEGIS DAN INDIKATOR KINERJA UTAMA</td></tr>
    <tr><td>BAB V</td><td>PROGRAM STRATEGIS DAN KERANGKA PENDANAAN</td></tr>
    <tr><td>BAB VI</td><td>PENUTUP</td></tr>
    <tr><td>LAMPIRAN</td><td>1. Matriks Kinerja {{ $range }} · 2. Definisi Operasional Indikator · 3. Catatan Penyusunan Draf</td></tr>
</table>

{{-- ===== BAB I ===== --}}
<div class="pb"></div>
<h1>Bab I<br><small>Pendahuluan</small></h1>
<h2>1.1 Latar Belakang</h2>
@if ($profile?->history)<p>{{ $profile->history }}</p>@endif
<p>Rencana Strategis (Renstra) {{ $name }} Tahun {{ $range }} merupakan dokumen perencanaan lima tahunan yang memuat visi, misi, tujuan, sasaran strategis, indikator kinerja utama, program, serta kerangka pendanaan. Renstra ini disusun dengan pendekatan <em>Balanced Scorecard</em> dalam empat perspektif, yaitu {{ collect($perspectives)->values()->map(fn ($p) => mb_strtolower($p))->join(', ', ', dan ') }}, sehingga setiap sasaran memiliki ukuran keberhasilan, penanggung jawab, dan program yang jelas.</p>
<p>Penyusunan memanfaatkan hasil analisis lingkungan strategis ({{ $pestel->flatten()->count() }} faktor PESTEL, {{ $swot->flatten()->count() }} butir SWOT), {{ $issues->count() }} isu strategis, {{ $objectives->count() }} sasaran strategis dengan {{ $totalKpi }} indikator kinerja utama, serta {{ $initiatives->count() }} program/inisiatif strategis yang tercatat di Kinera.</p>

<h2>1.2 Landasan Hukum</h2>
@if ($regulations->isEmpty())
    <p class="muted">Belum ada regulasi yang dicatat di menu Regulasi.</p>
@else
<ol>
    @foreach ($regulations as $r)
        <li>{{ $r->reference_no ? $r->reference_no.' tentang ' : '' }}{{ $r->title }}{{ $r->issuer ? ' ('.$r->issuer.')' : '' }}.</li>
    @endforeach
</ol>
@endif

<h2>1.3 Lingkungan Strategis</h2>
<p>Analisis PESTEL memetakan faktor makro yang berpengaruh terhadap {{ $name }} selama periode Renstra beserta respons yang direncanakan.</p>
<table>
    <thead><tr><th style="width:14%">Faktor</th><th>Kondisi dan dampak</th><th style="width:10%" class="c">Sifat</th><th style="width:30%">Respons</th></tr></thead>
    <tbody>
    @forelse ($pestel as $factor => $items)
        @foreach ($items as $p)
            <tr><td>{{ PestelAnalysis::FACTORS[$factor] ?? ucfirst($factor) }}</td><td><strong>{{ $p->title }}.</strong> {{ $p->description }}</td><td class="c">{{ ucfirst($p->impact_type ?? '-') }}</td><td>{{ $p->response ?: '-' }}</td></tr>
        @endforeach
    @empty
        <tr><td colspan="4" class="muted">Belum ada isian PESTEL.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>1.4 Analisis SWOT</h2>
<table>
    <thead><tr><th style="width:50%">Kekuatan (S) · skor {{ Fmt::num($swotScore['S'] ?? 0, 2) }}</th><th>Kelemahan (W) · skor {{ Fmt::num($swotScore['W'] ?? 0, 2) }}</th></tr></thead>
    <tbody><tr>
        @foreach (['S', 'W'] as $t)<td><ul>@forelse ($swot[$t] ?? [] as $s)<li>{{ $s->title }}</li>@empty<li class="muted">-</li>@endforelse</ul></td>@endforeach
    </tr></tbody>
    <thead><tr><th>Peluang (O) · skor {{ Fmt::num($swotScore['O'] ?? 0, 2) }}</th><th>Ancaman (T) · skor {{ Fmt::num($swotScore['T'] ?? 0, 2) }}</th></tr></thead>
    <tbody><tr>
        @foreach (['O', 'T'] as $t)<td><ul>@forelse ($swot[$t] ?? [] as $s)<li>{{ $s->title }}</li>@empty<li class="muted">-</li>@endforelse</ul></td>@endforeach
    </tr></tbody>
</table>
@php
    $internal = ($swotScore['S'] ?? 0) - ($swotScore['W'] ?? 0);
    $external = ($swotScore['O'] ?? 0) - ($swotScore['T'] ?? 0);
    $quad = $internal >= 0 ? ($external >= 0 ? 'I (agresif: memanfaatkan kekuatan untuk meraih peluang)' : 'II (diversifikasi: kekuatan untuk menghadapi ancaman)') : ($external >= 0 ? 'III (turn-around: membenahi kelemahan untuk meraih peluang)' : 'IV (defensif)');
@endphp
<p>Selisih skor internal (S − W) sebesar {{ Fmt::num($internal, 2) }} dan eksternal (O − T) sebesar {{ Fmt::num($external, 2) }} menempatkan {{ $name }} pada kuadran {{ $quad }}. Posisi ini menjadi dasar pemilihan strategi pada Bab III.</p>

<h2>1.5 Isu Strategis</h2>
<table>
    <thead><tr><th style="width:4%">No</th><th>Isu strategis</th><th style="width:15%">Perspektif</th><th style="width:11%" class="c">Urgensi / Dampak</th></tr></thead>
    <tbody>
    @forelse ($issues as $n => $i)
        <tr><td class="c">{{ $n + 1 }}</td><td><strong>{{ $i->title }}.</strong> {{ $i->description }}</td><td>{{ Terms::perspective($i->perspective) }}</td><td class="c">{{ $i->urgency }} / {{ $i->impact }}</td></tr>
    @empty
        <tr><td colspan="4" class="muted">Belum ada isu strategis.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>1.6 Pemangku Kepentingan</h2>
<table>
    <thead><tr><th style="width:24%">Pemangku kepentingan</th><th>Harapan</th><th style="width:30%">Strategi pelibatan</th></tr></thead>
    <tbody>
    @forelse ($stakeholders as $s)
        <tr><td>{{ $s->name }}<br><span class="muted">pengaruh {{ $s->influence }} · kepentingan {{ $s->interest }}</span></td><td>{{ $s->expectations ?: '-' }}</td><td>{{ $s->engagement_strategy ?: '-' }}</td></tr>
    @empty
        <tr><td colspan="3" class="muted">Belum ada pemangku kepentingan.</td></tr>
    @endforelse
    </tbody>
</table>

{{-- ===== BAB II ===== --}}
<div class="pb"></div>
<h1>Bab II<br><small>Visi, Misi, Tujuan, dan Nilai</small></h1>
<h2>2.1 Visi</h2>
<p style="text-align:center"><strong>“{{ $profile?->vision ?: '(visi belum diisi di menu Jati Diri)' }}”</strong></p>
<h2>2.2 Misi</h2>
@if ($missions->isEmpty())<p class="muted">Misi belum diisi.</p>@else
<ol>@foreach ($missions as $m)<li>{{ $m }}</li>@endforeach</ol>
@endif
<h2>2.3 Tujuan</h2>
@php $goals = collect(preg_split('/\r?\n|(?<=\.)\s+(?=\d+\.\s)/', (string) $profile?->purpose))->map(fn ($g) => trim(preg_replace('/^\d+[.)]\s*/', '', $g)))->filter()->values(); @endphp
@if ($goals->count() > 1)<ol>@foreach ($goals as $g)<li>{{ $g }}</li>@endforeach</ol>@else<p>{{ $goals->first() ?: 'Tujuan belum diisi di menu Jati Diri.' }}</p>@endif
<p>Tujuan tersebut dijabarkan menjadi {{ $objectives->count() }} sasaran strategis pada Bab IV, yang tersebar pada perspektif:
    {{ $byPerspective->map(fn ($o, $p) => Terms::perspective($p).' ('.$o->count().')')->values()->join(', ', ', dan ') }}.</p>
<h2>2.4 Nilai-Nilai Inti</h2>
@if ($profile?->culture_statement)<p>{{ $profile->culture_statement }}</p>@endif
<table>
    <thead><tr><th style="width:20%">Nilai</th><th>Makna</th><th style="width:38%">Perilaku utama</th></tr></thead>
    <tbody>
    @forelse ($values as $v)
        <tr><td><strong>{{ $v->name }}</strong>{{ $v->local_name && $v->local_name !== $v->name ? ' ('.$v->local_name.')' : '' }}</td><td>{{ $v->description }}</td>
            <td><ul>@foreach (preg_split('/\r?\n/', (string) $v->behaviors) as $b)@if (trim($b) !== '')<li>{{ trim($b) }}</li>@endif @endforeach</ul></td></tr>
    @empty
        <tr><td colspan="3" class="muted">Belum ada nilai inti.</td></tr>
    @endforelse
    </tbody>
</table>
@if ($capabilities->isNotEmpty())
<h2>2.5 Kapabilitas Kunci</h2>
<table>
    <thead><tr><th style="width:28%">Kapabilitas</th><th class="c" style="width:12%">Level kini → target</th><th>Langkah menutup kesenjangan</th></tr></thead>
    <tbody>
    @foreach ($capabilities as $c)
        <tr><td>{{ $c->name }}{{ $c->is_core ? ' ★' : '' }}</td><td class="c">{{ $c->current_level }} → {{ $c->target_level }}</td><td>{{ $c->gap_action ?: '-' }}</td></tr>
    @endforeach
    </tbody>
</table>
<p class="muted">★ kapabilitas inti (pembeda). Skala 1–5.</p>
@endif

{{-- ===== BAB III ===== --}}
<div class="pb"></div>
<h1>Bab III<br><small>Arah Kebijakan dan Strategi</small></h1>
<h2>3.1 Strategi Hasil Analisis TOWS</h2>
<p>Strategi dirumuskan dengan memadukan faktor internal dan eksternal pada Bab I. Strategi yang dipilih menjadi arah kebijakan selama {{ $range }}:</p>
@foreach ($tows->groupBy('type') as $type => $items)
    <h3>{{ $towsLabels[$type] ?? $type }}</h3>
    <ol>@foreach ($items as $t)<li><strong>{{ $t->title }}.</strong> {{ $t->description }}</li>@endforeach</ol>
@endforeach
@if ($tows->isEmpty())<p class="muted">Belum ada strategi TOWS.</p>@endif

<h2>3.2 Tema dan Pilihan Strategis</h2>
@foreach ($themes as $th)
    <h3>{{ $th->code }}. {{ $th->name }}</h3>
    <p>{{ $th->description }}</p>
    @if ($th->choices->isNotEmpty())
        <ul>@foreach ($th->choices as $ch)<li>{{ $ch->title }}{{ $ch->rationale ? ' — '.$ch->rationale : '' }}</li>@endforeach</ul>
    @endif
@endforeach

<h2>3.3 Peta Strategi</h2>
<p>Peta strategi menunjukkan hubungan sebab-akibat antarsasaran: penguatan {{ mb_strtolower(Terms::perspective('sdm')) }} mendorong perbaikan {{ mb_strtolower(Terms::perspective('proses')) }}, yang selanjutnya meningkatkan hasil pada {{ mb_strtolower(Terms::perspective('pelanggan')) }} dan {{ mb_strtolower(Terms::perspective('keuangan')) }}.</p>
<table>
    <tbody>
    @foreach ($perspectives as $key => $label)
        <tr><td style="width:22%;background:{{ Terms::perspectiveColor($key) }}" class="persp">{{ $label }}</td>
            <td>@foreach ($byPerspective[$key] ?? [] as $o)<div>{{ $o->code }} · {{ $o->title }}</div>@endforeach</td></tr>
    @endforeach
    </tbody>
</table>
@if ($relations->isNotEmpty())
<p>Hubungan utama antarsasaran:</p>
<ul>@foreach ($relations as $r)<li>{{ $r->source?->code }} {{ $r->source?->title }} → {{ $r->target?->code }} {{ $r->target?->title }}</li>@endforeach</ul>
@endif

{{-- ===== BAB IV ===== --}}
<div class="pb"></div>
<h1>Bab IV<br><small>Sasaran Strategis dan Indikator Kinerja Utama</small></h1>
<p>Bagian ini menetapkan sasaran strategis, indikator kinerja utama (IKU), kondisi awal (<em>baseline</em>), dan target tahunan {{ $range }}. Target tahun {{ $periodYear }} bersumber dari Perjanjian Kinerja yang berlaku; target tahun lainnya (dicetak miring) adalah <strong>usulan otomatis</strong> hasil proyeksi tren dan perlu ditetapkan.</p>
@foreach ($perspectives as $key => $label)
    @continue(! isset($byPerspective[$key]))
    <h2>4.{{ ++$sec }} Perspektif {{ $label }}</h2>
    @foreach ($byPerspective[$key] as $o)
        <h3>Sasaran {{ $o->code }}: {{ $o->title }}</h3>
        @if ($o->description)<p>{{ $o->description }} Penanggung jawab: {{ $owner($o->owner) }}.</p>@endif
        @if ($o->kpis->isNotEmpty())
        <table>
            <thead><tr><th style="width:7%">Kode</th><th>Indikator kinerja utama</th><th class="num" style="width:9%">Baseline</th>
                @foreach ($years as $y)<th class="num" style="width:9%">{{ $y }}</th>@endforeach</tr></thead>
            <tbody>
            @foreach ($kpiRows->where('objective.id', $o->id) as $row)
                @php $k = $row['kpi']; @endphp
                <tr><td>{{ $k->code }}</td><td>{{ $k->name }}{{ $k->direction === 'lower' ? ' ↓' : '' }}</td><td class="num">{{ Fmt::kpiValue($k->baseline, $k->unit, (int) $k->decimals) }}</td>
                    @foreach ($years as $y)<td class="num {{ $y === $periodYear ? '' : 'prop' }}">{{ Fmt::kpiValue($row['targets'][$y], $k->unit, (int) $k->decimals) }}</td>@endforeach</tr>
            @endforeach
            </tbody>
        </table>
        @endif
    @endforeach
@endforeach
<p class="muted">↓ indikator dengan arah “semakin kecil semakin baik”.</p>

{{-- ===== BAB V ===== --}}
<div class="pb"></div>
<h1>Bab V<br><small>Program Strategis dan Kerangka Pendanaan</small></h1>
<h2>5.1 Program dan Inisiatif Strategis</h2>
<p>Sasaran strategis dicapai melalui {{ $initiatives->count() }} program/inisiatif strategis berikut.</p>
<table>
    <thead><tr><th style="width:9%">Kode</th><th>Program / inisiatif</th><th style="width:16%">Sasaran didukung</th><th style="width:18%">Penanggung jawab</th><th style="width:13%">Waktu</th></tr></thead>
    <tbody>
    @forelse ($initiatives as $i)
        <tr><td>{{ $i->code }}</td><td><strong>{{ $i->title }}</strong>@if ($i->expected_outcome)<br><span class="muted">Hasil: {{ $i->expected_outcome }}</span>@endif</td>
            <td>{{ $i->objectives->pluck('code')->join(', ') ?: '-' }}</td><td>{{ $owner($i->owner) }}</td><td>{{ $dateFmt($i->start_date) }} – {{ $dateFmt($i->end_date) }}</td></tr>
    @empty
        <tr><td colspan="5" class="muted">Belum ada inisiatif strategis.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>5.2 Kerangka Pendanaan Indikatif</h2>
<p>Pagu tahun {{ $periodYear }} bersumber dari rencana anggaran inisiatif sebesar {{ Fmt::rp($totalBase) }}. Pagu tahun lain (dicetak miring) adalah <strong>indikatif</strong> dengan asumsi perubahan {{ (int) (\App\Services\RenstraDraftService::BUDGET_GROWTH * 100) }}% per tahun, dibulatkan ke juta rupiah, dan perlu diselaraskan dengan rencana kerja dan anggaran.</p>
<table>
    <thead><tr><th style="width:8%">Kode</th><th>Program</th><th style="width:11%">Sumber dana</th>@foreach ($years as $y)<th class="num">{{ $y }}<br><span class="muted">(Rp juta)</span></th>@endforeach</tr></thead>
    <tbody>
    @foreach ($funding as $f)
        <tr><td>{{ $f['initiative']->code }}</td><td>{{ $f['initiative']->title }}</td><td>{{ $f['sources'] }}</td>
            @foreach ($years as $y)<td class="num {{ $y === $periodYear ? '' : 'prop' }}">{{ $f['years'][$y] === null ? '-' : Fmt::num($f['years'][$y] / 1e6) }}</td>@endforeach</tr>
    @endforeach
    <tr class="grp"><td colspan="3">Jumlah</td>@foreach ($years as $y)<td class="num">{{ Fmt::num($fundingTotals[$y] / 1e6) }}</td>@endforeach</tr>
    </tbody>
</table>

{{-- ===== BAB VI ===== --}}
<h1 class="pb" style="padding-top:.5rem">Bab VI<br><small>Penutup</small></h1>
<p>Renstra {{ $name }} Tahun {{ $range }} menjadi acuan penyusunan rencana kerja tahunan, Perjanjian Kinerja, dan laporan kinerja. Pencapaian sasaran dipantau melalui pengukuran IKU secara berkala, rapat evaluasi kinerja, dan tindak lanjut temuan, sehingga perbaikan dapat dilakukan sebelum akhir tahun.</p>
<p>Renstra ini dapat ditinjau kembali apabila terjadi perubahan kebijakan, regulasi, atau kondisi lingkungan strategis yang signifikan.</p>
<table class="toc" style="margin-top:2rem"><tr><td style="width:55%"></td><td class="c">………………, ……………… {{ $start }}<br>{{ $leader }} {{ $name }}<br><br><br><br>……………………………………</td></tr></table>

{{-- ===== LAMPIRAN ===== --}}
<div class="pb"></div>
<h1>Lampiran 1<br><small>Matriks Kinerja {{ $range }}</small></h1>
<table>
    <thead><tr><th style="width:15%">Sasaran strategis</th><th>Indikator</th><th style="width:8%">Satuan</th><th class="num">Baseline</th>@foreach ($years as $y)<th class="num">{{ $y }}</th>@endforeach<th style="width:14%">Penanggung jawab</th></tr></thead>
    <tbody>
    @foreach ($perspectives as $key => $label)
        @continue(! isset($byPerspective[$key]))
        <tr class="grp"><td colspan="{{ 5 + count($years) }}">Perspektif {{ $label }}</td></tr>
        @foreach ($byPerspective[$key] as $o)
            @foreach ($kpiRows->where('objective.id', $o->id)->values() as $n => $row)
                @php $k = $row['kpi']; @endphp
                <tr>@if ($n === 0)<td rowspan="{{ $o->kpis->count() }}">{{ $o->code }} {{ $o->title }}</td>@endif
                    <td>{{ $k->code }} {{ $k->name }}</td><td>{{ $k->unit }}</td><td class="num">{{ Fmt::num($k->baseline, (int) $k->decimals) }}</td>
                    @foreach ($years as $y)<td class="num {{ $y === $periodYear ? '' : 'prop' }}">{{ Fmt::num($row['targets'][$y], (int) $k->decimals) }}</td>@endforeach
                    <td>{{ $owner($k->owner) }}</td></tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>

<div class="pb"></div>
<h1>Lampiran 2<br><small>Definisi Operasional Indikator</small></h1>
<table>
    <thead><tr><th style="width:7%">Kode</th><th style="width:22%">Indikator</th><th>Definisi / formula</th><th style="width:18%">Sumber data</th><th style="width:11%">Frekuensi</th></tr></thead>
    <tbody>
    @foreach ($kpiRows as $row)
        @php $k = $row['kpi']; @endphp
        <tr><td>{{ $k->code }}</td><td>{{ $k->name }}</td>
            <td>{{ $k->operational_definition ?: ($k->description && ! str_starts_with($k->description, 'DATA DEMO') ? $k->description : '') }}@if ($k->formula)<br><em>Formula:</em> {{ $k->formula }}@endif</td>
            <td>{{ $k->data_source ?: '-' }}</td><td>{{ \App\Models\Kpi::FREQUENCIES[$k->frequency] ?? $k->frequency }}</td></tr>
    @endforeach
    </tbody>
</table>

<div class="pb"></div>
<h1>Lampiran 3<br><small>Catatan Penyusunan Draf</small></h1>
<div class="note">
    <p><strong>Yang diambil langsung dari isian Kinera:</strong> regulasi, PESTEL, SWOT, isu strategis, pemangku kepentingan, visi, misi, tujuan, nilai inti, kapabilitas, strategi TOWS, tema dan pilihan strategis, peta strategi, sasaran, IKU beserta baseline dan target {{ $periodYear }}, definisi operasional, serta program dan anggaran {{ $periodYear }}.</p>
    <p><strong>Yang diusulkan otomatis dan wajib ditelaah:</strong> target IKU selain tahun {{ $periodYear }} (proyeksi: target {{ $periodYear }} ditambah selisih target terhadap baseline setiap tahun, maksimum 100% untuk satuan persen), pagu indikatif selain tahun {{ $periodYear }}, serta kalimat pengantar setiap bab.</p>
    <p><strong>Yang perlu dilengkapi penyusun:</strong> uraian capaian Renstra periode sebelumnya, analisis kondisi umum yang lebih rinci, nomor dan tanggal penetapan, serta tanda tangan pimpinan.</p>
    <p>Unduh versi Word dari tombol di bagian atas halaman untuk menyunting narasi sebelum ditetapkan.</p>
</div>
</div>
</body>
</html>
