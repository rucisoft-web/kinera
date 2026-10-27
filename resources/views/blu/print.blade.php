@php
    use App\Models\Kpi;
    use App\Support\Fmt;
    use App\Support\Terbilang;
    $year = $period?->year;
    $ind = collect($contract->indicators ?? []);
    $leaderTitle = $company?->leader_title ?: 'Pemimpin BLU';
    $satker = $company?->name ?? '-';
    $ministry = $company?->ministry ?: '…………';
    $signDate = $contract->dg_signed_at ?? $contract->leader_signed_at;
    $baDate = $contract->discussed_at;
    $isChange = $contract->version > 0;
    $tw = fn ($i, $q) => Fmt::num($i['targets'][$q] ?? null, $i['decimals'] ?? 0).(($i['unit'] ?? '') === '%' && ($i['targets'][$q] ?? null) !== null ? '%' : '');
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title }} — {{ $satker }} {{ $year }}</title>
<style>
    @page { size: A4; margin: 18mm 16mm; }
    body { font-family: "Times New Roman", Georgia, serif; color: #111; font-size: 11.5pt; line-height: 1.45; margin: 0; background: #f2f2f2; }
    .sheet { background: #fff; max-width: 190mm; margin: 12px auto; padding: 16mm 14mm; position: relative; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
    h1, h2, h3 { text-align: center; margin: .15rem 0; font-size: 12.5pt; }
    h1 { font-size: 14pt; }
    .center { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin: .6rem 0; font-size: 10.5pt; }
    th, td { border: 1px solid #333; padding: 3px 5px; vertical-align: top; }
    th { background: #eee; }
    td.num, th.num { text-align: right; white-space: nowrap; }
    .noborder td { border: none; padding: 1px 4px; }
    .sign { display: grid; grid-template-columns: repeat(var(--cols, 2), 1fr); gap: 1rem; margin-top: 1.4rem; text-align: center; }
    .sign .space { height: 70px; }
    .muted { color: #555; font-size: 9.5pt; }
    .demo { border: 2px solid #b42318; color: #b42318; padding: 6px 10px; font: 600 9.5pt/1.35 Arial, sans-serif; margin-bottom: 10px; }
    .wm { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; font: 700 72pt Arial, sans-serif; color: rgba(180,35,24,.07); transform: rotate(-30deg); }
    .toolbar { max-width: 190mm; margin: 12px auto 0; font: 10pt Arial, sans-serif; display: flex; gap: .5rem; }
    .toolbar button { padding: 6px 12px; cursor: pointer; }
    @media print { body { background: #fff; } .sheet { box-shadow: none; margin: 0; padding: 0; max-width: none; } .toolbar { display: none; } }
</style>
</head>
<body>
<div class="toolbar"><button onclick="window.print()">🖶 Cetak / simpan PDF</button><button onclick="window.close()">Tutup</button><span class="muted" style="align-self:center">{{ $title }}</span></div>
<div class="sheet">
    @if ($demo)
        <div class="wm">DATA DEMO</div>
        <div class="demo">{{ $demo }}</div>
    @endif

@if ($doc === 'kontrak')
    <h1>{{ $isChange ? 'PERUBAHAN KONTRAK KINERJA' : 'KONTRAK KINERJA' }}</h1>
    <h2>ANTARA</h2>
    <h2>DIREKTUR JENDERAL PERBENDAHARAAN<br>KEMENTERIAN KEUANGAN REPUBLIK INDONESIA</h2>
    <h2>DENGAN</h2>
    <h2>{{ mb_strtoupper($leaderTitle) }} BADAN LAYANAN UMUM {{ mb_strtoupper($satker) }}<br>{{ mb_strtoupper($ministry) }}</h2>
    <h2>TAHUN {{ $year }}{{ $isChange ? ' — PERUBAHAN KE-'.$contract->version : '' }}</h2>
    <p class="center">NOMOR: {{ $contract->number ?: '……………………' }}</p>
    @php $t = $signDate ? Terbilang::date($signDate) : null; @endphp
    <p>Pada hari ini, {{ $t['hari'] ?? '……' }}, tanggal {{ $t['tanggal'] ?? '……' }}, bulan {{ $t['bulan'] ?? '……' }}, tahun {{ $t['tahun'] ?? '……' }}, bertempat di {{ $contract->signed_place ?: 'Jakarta' }}, kami yang bertanda tangan di bawah ini:</p>
    <table class="noborder">
        <tr><td style="width:1.5rem">1.</td><td style="width:5rem">Nama</td><td>: {{ $company?->dg_name ?: '……………………' }}</td></tr>
        <tr><td></td><td>Jabatan</td><td>: Direktur Jenderal Perbendaharaan, Kementerian Keuangan RI<br>&nbsp; Dalam hal ini bertindak untuk dan atas nama Menteri Keuangan RI<br>&nbsp; Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong></td></tr>
        <tr><td>2.</td><td>Nama</td><td>: {{ $company?->leader_name ?: '……………………' }}</td></tr>
        <tr><td></td><td>Jabatan</td><td>: {{ $leaderTitle }} {{ $satker }}, {{ $ministry }} RI<br>&nbsp; Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></td></tr>
    </table>
    <p>Bersepakat bahwa dalam rangka mewujudkan Badan Layanan Umum yang efektif, transparan, akuntabel, dan berorientasi pada hasil, diperlukan Kontrak Kinerja Tahun {{ $year }}, dengan ketentuan:</p>
    @if ($isChange)
        <p>Kontrak Kinerja ini mengubah Kontrak Kinerja Tahun {{ $year }} sebelumnya berdasarkan {{ \App\Models\PerformanceContract::BASES[$contract->basis] ?? 'ketentuan PER-6/PB/2025' }}{{ $contract->reason ? ', dengan pertimbangan: '.$contract->reason : '' }}</p>
    @endif
    <p>PIHAK KEDUA menyatakan kesanggupan untuk mencapai target pada Indikator Kinerja Terpilih sebagai berikut:</p>
    <table>
        <thead><tr><th rowspan="2">No.</th><th rowspan="2">Indikator Kinerja</th><th colspan="4">Target Kinerja {{ $year }}</th></tr>
            <tr><th>Triwulan I</th><th>Triwulan II</th><th>Triwulan III</th><th>Triwulan IV</th></tr></thead>
        <tbody>
        @foreach ($ind as $n => $i)
            <tr><td class="center">{{ $n + 1 }}.</td><td>{{ $i['name'] }}{{ ($i['unit'] ?? '') !== '%' ? ' ('.$i['unit'].')' : '' }}</td>
                @foreach ([3, 6, 9, 12] as $q)<td class="num">{{ ($i['targets'][$q] ?? null) === null ? '-' : $tw($i, $q) }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table>
    <p>PIHAK KEDUA bersedia menerima konsekuensi atas capaian target kinerja tersebut. Reviu dan evaluasi atas capaian kinerja dilaksanakan oleh PIHAK PERTAMA untuk selanjutnya dipergunakan sebagai dasar pertimbangan pemberian remunerasi.</p>
    <p style="text-align:right">{{ $contract->signed_place ?: 'Jakarta' }}, {{ $signDate ? $signDate->translatedFormat('d F Y') : '……………………' }}</p>
    <div class="sign">
        <div>Direktorat Jenderal Perbendaharaan<br>Direktur Jenderal,<div class="space"></div><u>{{ $company?->dg_name ?: '……………………' }}</u><br>NIP {{ $company?->dg_nip ?: '……………………' }}</div>
        <div>Badan Layanan Umum {{ $satker }}<br>{{ $leaderTitle }},<div class="space"></div><u>{{ $company?->leader_name ?: '……………………' }}</u><br>NIP {{ $company?->leader_nip ?: '……………………' }}</div>
    </div>

@elseif ($doc === 'berita-acara')
    <h1>BERITA ACARA HASIL PEMBAHASAN<br>USULAN {{ $isChange ? 'PERUBAHAN ' : '' }}KONTRAK KINERJA</h1>
    <h2>PEMIMPIN BADAN LAYANAN UMUM {{ mb_strtoupper($satker) }}</h2>
    <p class="center">Nomor: {{ $contract->ba_number ?: 'BA-……………………' }}</p>
    @php $t = $baDate ? Terbilang::date($baDate) : null; @endphp
    <p>Pada hari ini {{ $t['hari'] ?? '……' }}, tanggal {{ $t['tanggal'] ?? '……' }} bulan {{ $t['bulan'] ?? '……' }} tahun {{ $t['tahun'] ?? '……' }}, telah dilaksanakan pembahasan usulan kontrak kinerja Pemimpin Badan Layanan Umum {{ $satker }} untuk dijadikan dasar penyusunan kontrak kinerja bagi Pemimpin Badan Layanan Umum.</p>
    <p>Rincian indikator kinerja, trajektori, dan target yang disepakati adalah sebagai berikut:</p>
    <table>
        <thead><tr><th rowspan="2">No.</th><th rowspan="2">Indikator Kinerja</th><th colspan="4">Target Kinerja {{ $year }}</th></tr>
            <tr><th>Triwulan I</th><th>Triwulan II</th><th>Triwulan III</th><th>Triwulan IV</th></tr></thead>
        <tbody>
        @foreach ($ind as $n => $i)
            <tr><td class="center">{{ $n + 1 }}.</td><td>{{ $i['name'] }}{{ ($i['unit'] ?? '') !== '%' ? ' ('.$i['unit'].')' : '' }}</td>
                @foreach ([3, 6, 9, 12] as $q)<td class="num">{{ ($i['targets'][$q] ?? null) === null ? '-' : $tw($i, $q) }}</td>@endforeach</tr>
        @endforeach
        </tbody>
    </table>
    <div class="sign" style="--cols:3">
        <div>{{ $company?->technical_supervisor ?: 'Kementerian/Lembaga' }}<br>(Pembina Teknis)<div class="space"></div>(……………………)</div>
        <div>Direktorat PPK BLU<div class="space"></div><br>(……………………)</div>
        <div>Pemimpin BLU<br>{{ $satker }}<div class="space"></div>({{ $company?->leader_name ?: '……………………' }})</div>
    </div>

@elseif ($doc === 'definisi-operasional')
    <h1>DEFINISI OPERASIONAL INDIKATOR KINERJA</h1>
    <h2>KONTRAK KINERJA PEMIMPIN BLU {{ mb_strtoupper($satker) }} TAHUN {{ $year }}</h2>
    <p class="muted center">Disusun Pemimpin BLU bersama Pembina Teknis (aspek layanan) dan Direktorat Jenderal Perbendaharaan (aspek keuangan) — PER-6/PB/2025 Pasal 5 ayat (9).</p>
    @foreach ($ind as $n => $i)
        <table>
            <tr><th style="width:28%; text-align:left">{{ $n + 1 }}. Indikator Kinerja</th><td><strong>{{ $i['name'] }}</strong></td></tr>
            <tr><th style="text-align:left">Aspek</th><td>{{ Kpi::ASPECTS[$i['aspect']] ?? $i['aspect'] }}{{ ($i['mandatory'] ?? false) ? ' · wajib dicapai' : '' }}</td></tr>
            <tr><th style="text-align:left">Definisi / batasan</th><td>{{ $i['definition'] ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Formula</th><td>{{ $i['formula'] ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Satuan · polaritas</th><td>{{ $i['unit'] }} · {{ ($i['direction'] ?? 'higher') === 'lower' ? 'makin kecil makin baik' : 'makin besar makin baik' }}</td></tr>
            <tr><th style="text-align:left">Trajektori · target tahunan</th><td>{{ Kpi::FREQUENCIES[$i['frequency']] ?? $i['frequency'] }} · {{ Fmt::num($i['annual'] ?? null, $i['decimals'] ?? 0) }} · capaian maksimum {{ Fmt::num($i['max'] ?? null, 0) }}%</td></tr>
            <tr><th style="text-align:left">Sumber data</th><td>{{ $i['source'] ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Pelaksanaan pengambilan data capaian</th><td>{{ $i['collection'] ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Metode pengujian</th><td>{{ $i['test'] ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Dokumen pendukung</th><td>{{ $i['documents'] ?: '—' }}</td></tr>
        </table>
    @endforeach

@elseif ($doc === 'capaian')
    <h1>Hasil Perhitungan Capaian Indikator Kinerja</h1>
    <h2>Pemimpin BLU {{ $satker }}</h2>
    <h2>Tahun {{ $year }}{{ $upto < 12 ? ' (s.d. '.\App\Services\BluContractService::QUARTERS[$upto].')' : '' }}</h2>
    <p class="muted center">Dasar: {{ $contract->label }}{{ $contract->number ? ' Nomor '.$contract->number : '' }} · format lampiran Lampiran VI PER-6/PB/2025</p>
    <table>
        <thead><tr><th>No.</th><th>Indikator Kinerja</th><th class="num">Target</th><th class="num">Realisasi</th><th class="num">Capaian Kinerja per Indikator</th><th class="num">Capaian Kinerja Maksimum</th></tr></thead>
        <tbody>
        @foreach ($a['rows'] as $n => $r)
            <tr><td class="center">{{ $n + 1 }}.</td><td>{{ $r['kpi']->name }} ({{ $r['kpi']->unit }}){{ $r['below_floor'] ? ' *' : '' }}</td>
                <td class="num">{{ Fmt::num($r['target'], $r['kpi']->decimals) }}</td><td class="num">{{ Fmt::num($r['actual'], $r['kpi']->decimals) }}</td>
                <td class="num">{{ Fmt::pct($r['raw'], 2) }}</td><td class="num">{{ Fmt::pct($r['recognized'], 2) }}</td></tr>
        @endforeach
        <tr><td colspan="5" style="text-align:right"><strong>Total nilai capaian kinerja</strong></td><td class="num"><strong>{{ Fmt::num($a['total'], 2) }}</strong></td></tr>
        <tr><td colspan="5" style="text-align:right"><strong>Rata-rata nilai capaian kinerja</strong></td><td class="num"><strong>{{ Fmt::pct($a['average'], 2) }}</strong></td></tr>
        </tbody>
    </table>
    <p>Capaian Kinerja yang diusulkan: <strong>{{ Fmt::pct($a['average'], 2) }}</strong></p>
    @if (count($a['below_floor']))<p class="muted">* Capaian di bawah {{ config('dhastram.score_floor', 65) }}% dari target (Pasal 14 ayat 4 huruf c).</p>@endif
    <p class="muted">Capaian per indikator = realisasi ÷ target × 100% (indikator makin kecil makin baik: target ÷ realisasi × 100%). Capaian maksimum mengikuti KMK remunerasi BLU{{ $company?->remuneration_decree ? ' ('.$company->remuneration_decree.')' : '' }}; indikator yang targetnya diturunkan lewat perubahan kontrak dihitung paling tinggi 100%.</p>

@elseif ($doc === 'pernyataan')
    <h3>{{ mb_strtoupper($ministry) }}</h3>
    <h3>{{ mb_strtoupper($company?->technical_supervisor ?: 'UNIT ESELON I') }}</h3>
    <h3>{{ mb_strtoupper($satker) }}</h3>
    <p class="center muted">{{ $company?->address }} {{ $company?->city }}</p>
    <hr>
    <h1>SURAT PERNYATAAN KEBENARAN PERHITUNGAN KELEBIHAN CAPAIAN KINERJA<br>PEMIMPIN BADAN LAYANAN UMUM</h1>
    <p class="center">NOMOR: ……………………</p>
    <p>Dalam rangka memenuhi kelengkapan usulan persetujuan pemberian insentif kinerja atas kelebihan capaian kinerja Pemimpin Badan Layanan Umum, kami yang bertanda tangan di bawah ini:</p>
    <table class="noborder">
        <tr><td style="width:6rem">Nama</td><td>: {{ $company?->leader_name ?: '……………………' }}</td></tr>
        <tr><td>NIP</td><td>: {{ $company?->leader_nip ?: '……………………' }}</td></tr>
        <tr><td>Jabatan</td><td>: {{ $leaderTitle }} {{ $satker }}</td></tr>
    </table>
    <p>dengan ini menyatakan kebenaran atas hasil perhitungan kelebihan capaian kinerja periode {{ $year }} (rata-rata nilai capaian kinerja {{ Fmt::pct($a['average'], 2) }} sebagaimana terlampir) dan bersedia jika sewaktu-waktu dilakukan pengujian oleh pembina keuangan dan/atau pembina teknis BLU.</p>
    <p>Apabila dikemudian hari terbukti pernyataan ini tidak benar dan menimbulkan kerugian Negara, maka bersedia untuk mempertanggungjawabkan sesuai dengan peraturan perundangan.</p>
    <p>Demikian surat pernyataan ini dibuat dengan sebenarnya.</p>
    <div class="sign"><div></div><div>{{ $company?->city ?: '……………' }}, ……………………<br>{{ $leaderTitle }} {{ $satker }}<div class="space"></div><u>{{ $company?->leader_name ?: '……………………' }}</u><br>NIP {{ $company?->leader_nip ?: '……………………' }}</div></div>
@endif

    <p class="muted" style="margin-top:1.5rem; border-top:1px solid #ccc; padding-top:.4rem">Dicetak dari {{ config('dhastram.brand_name') }} · {{ now()->translatedFormat('d F Y H:i') }} · Format mengacu PER-6/PB/2025. Penyampaian resmi tetap melalui Sistem Aplikasi BLU terintegrasi DJPb.</p>
</div>
</body>
</html>
