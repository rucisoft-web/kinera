@php
    use App\Support\Fmt;
    use App\Support\Terms;
    $badge = fn ($s) => match ($s) { 'hijau' => 'arah-hijau', 'kuning' => 'arah-kuning', 'merah' => 'arah-merah', default => 'arah-abu' };
    $score = $overall['score'] ?? 0;
    $angle = min(120, $score) / 120 * 270;
    $rad = fn ($deg) => deg2rad($deg - 225);
    $arc = function ($from, $to, $r = 60) use ($rad) {
        $x1 = 75 + $r * cos($rad($from)); $y1 = 75 + $r * sin($rad($from));
        $x2 = 75 + $r * cos($rad($to)); $y2 = 75 + $r * sin($rad($to));
        $large = ($to - $from) > 180 ? 1 : 0;
        return "M $x1 $y1 A $r $r 0 $large 1 $x2 $y2";
    };
    // grafik tren
    $W = 640; $H = 220; $pad = 34;
    $months = array_keys($trend);
    $n = max(1, count($months) - 1);
    $yOf = fn ($v) => $H - $pad - (max(0, min(120, $v ?? 0)) / 120) * ($H - 2 * $pad);
    $xOf = fn ($i) => $pad + $i * (($W - 2 * $pad) / $n);
    $series = ['overall' => ['Skor strategis', '#0F2A4A']] + collect(Terms::perspectives())->mapWithKeys(fn ($l, $k) => [$k => [$l, Terms::perspectiveColor($k)]])->all();
@endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-grid arah-grid-3">
        <div class="arah-card" style="display:flex; align-items:center; gap:1rem">
            <svg viewBox="0 0 150 150" class="arah-gauge" role="img" aria-label="Skor strategis {{ $score }}">
                <path d="{{ $arc(0, 270) }}" stroke="var(--k-kertas-2)" stroke-width="14" fill="none" stroke-linecap="round"/>
                <path d="{{ $arc(0, 180) }}" stroke="rgba(168,67,42,.15)" stroke-width="14" fill="none"/>
                @if ($angle > 0.5)
                <path d="{{ $arc(0, max(0.6, $angle)) }}" stroke="{{ Fmt::statusHex($overall['status']) }}" stroke-width="14" fill="none" stroke-linecap="round"/>
                @endif
                <text x="75" y="78" text-anchor="middle" style="font-size:28px; font-weight:700; fill:var(--k-tinta)">{{ Fmt::num($score, 1) }}</text>
                <text x="75" y="98" text-anchor="middle" style="font-size:10px; fill:var(--k-tinta-2)">dari 100 (maks 120)</text>
            </svg>
            <div class="arah-stat">
                <span class="label">Skor strategis {{ $lastMonth ? 's.d. '.Terms::months()[$lastMonth] : '' }}</span>
                <span class="arah-badge {{ $badge($overall['status']) }}" style="font-size:.85rem">{{ strtoupper($overall['status'] ?? 'belum ada data') }}</span>
                <span class="sub">Hijau ≥ {{ config('dhastram.score_green') }} · Kuning ≥ {{ config('dhastram.score_yellow') }} · Merah &lt; {{ config('dhastram.score_yellow') }}</span>
                <span class="sub">Bobot: Keuangan {{ config('dhastram.perspective_weights.keuangan') }}%, Pelanggan {{ config('dhastram.perspective_weights.pelanggan') }}%, Proses {{ config('dhastram.perspective_weights.proses') }}%, SDM {{ config('dhastram.perspective_weights.sdm') }}%</span>
            </div>
        </div>
        <div class="arah-card">
            <h3>Status KPI <small>{{ $kpiStats['total'] }} KPI aktif</small></h3>
            @foreach (['hijau' => 'Tercapai', 'kuning' => 'Perlu perhatian', 'merah' => 'Kritis', 'kosong' => 'Belum ada realisasi'] as $k => $l)
                <div style="display:flex; align-items:center; gap:.6rem; margin-bottom:.45rem; font-size:.82rem">
                    <span style="width:8.5rem">{{ $l }}</span>
                    <div class="arah-bar" style="flex:1"><span style="width:{{ $kpiStats['total'] ? $kpiStats[$k] / $kpiStats['total'] * 100 : 0 }}%; background:{{ Fmt::statusHex($k === 'kosong' ? null : $k) }}"></span></div>
                    <strong style="width:2rem; text-align:right">{{ $kpiStats[$k] }}</strong>
                </div>
            @endforeach
        </div>
        <div class="arah-card">
            <h3>Perbaikan</h3>
            <div class="arah-grid arah-grid-2">
                <div class="arah-stat"><span class="label">Inisiatif aktif</span><span class="value">{{ $initiativeCount }}</span></div>
                <div class="arah-stat"><span class="label">Terlambat</span><span class="value" style="color:var(--k-merah)">{{ $lateInitiatives->count() }}</span></div>
                <div class="arah-stat"><span class="label">Temuan terbuka</span><span class="value">{{ $openFindings }}</span></div>
                <div class="arah-stat"><span class="label">Sasaran merah/kuning</span><span class="value">{{ $redYellow->count() }}</span></div>
            </div>
        </div>
    </div>

    <div class="arah-grid arah-grid-4" style="margin-top:1rem">
        @foreach ($perspectives as $key => $p)
            <div class="arah-card" style="border-top:4px solid {{ $p['color'] }}">
                <div class="arah-stat">
                    <span class="label">{{ $p['label'] }}</span>
                    <span class="value">{{ Fmt::num($p['score'], 1) }}</span>
                    <span><span class="arah-badge {{ $badge($p['status']) }}">{{ $p['status'] ?? '-' }}</span> <span class="sub">{{ $p['objectives'] }} sasaran</span></span>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($trend) > 1)
    <div class="arah-card" style="margin-top:1rem">
        <h3>Tren skor kumulatif per bulan</h3>
        <svg viewBox="0 0 {{ $W }} {{ $H }}" style="width:100%; height:auto" role="img" aria-label="Tren skor">
            @foreach ([0, 40, 80, 100, 120] as $g)
                <line x1="{{ $pad }}" x2="{{ $W - $pad }}" y1="{{ $yOf($g) }}" y2="{{ $yOf($g) }}" stroke="{{ $g == 100 ? '#2E7D4F' : ($g == 80 ? '#C88A12' : 'var(--k-garis)') }}" stroke-dasharray="{{ in_array($g, [80, 100]) ? '4 4' : '0' }}" stroke-width="1"/>
                <text x="{{ $pad - 6 }}" y="{{ $yOf($g) + 3 }}" text-anchor="end" style="font-size:9px; fill:var(--k-tinta-2)">{{ $g }}</text>
            @endforeach
            @foreach ($months as $i => $m)
                <text x="{{ $xOf($i) }}" y="{{ $H - 12 }}" text-anchor="middle" style="font-size:10px; fill:var(--k-tinta-2)">{{ Terms::monthShort($m) }}</text>
            @endforeach
            @foreach ($series as $key => [$label, $color])
                @php $pts = collect($months)->map(fn ($m, $i) => $xOf($i).','.$yOf($trend[$m][$key] ?? null))->join(' '); @endphp
                <polyline points="{{ $pts }}" fill="none" stroke="{{ $color }}" stroke-width="{{ $key === 'overall' ? 3 : 1.6 }}" stroke-linejoin="round" @if($key !== 'overall') opacity=".75" @endif/>
                @php $lm = end($months); $li = count($months) - 1; @endphp
                <circle cx="{{ $xOf($li) }}" cy="{{ $yOf($trend[$lm][$key] ?? null) }}" r="3" fill="{{ $color }}"/>
            @endforeach
        </svg>
        <div style="display:flex; flex-wrap:wrap; gap:1rem; font-size:.75rem">
            @foreach ($series as $key => [$label, $color])<span><span class="arah-dot" style="background:{{ $color }}"></span> {{ $label }}</span>@endforeach
        </div>
    </div>
    @endif

    <div class="arah-grid arah-grid-2" style="margin-top:1rem">
        <div class="arah-card">
            <h3>Sasaran merah & kuning</h3>
            <table class="arah-table">
                <thead><tr><th>Sasaran</th><th>Perspektif</th><th class="num">Skor</th></tr></thead>
                <tbody>
                @forelse ($redYellow as $o)
                    <tr>
                        <td><a href="{{ \App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource::getUrl('view', ['record' => $o['objective']]) }}">{{ $o['objective']->short_code }}</a> {{ $o['objective']->title }}<br><span class="arah-muted">PJ: {{ $o['objective']->owner?->name }}</span></td>
                        <td><span class="arah-dot" style="background:{{ Terms::perspectiveColor($o['objective']->perspective) }}"></span> {{ Terms::perspective($o['objective']->perspective) }}</td>
                        <td class="num"><span class="arah-badge {{ $badge($o['status']) }}">{{ Fmt::num($o['score'], 1) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="arah-empty">Semua sasaran hijau.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="arah-card">
            <h3>Inisiatif terlambat <small>progres tertinggal &gt; 10 poin dari waktu berjalan</small></h3>
            <table class="arah-table">
                <thead><tr><th>Inisiatif</th><th class="num">Progres</th><th class="num">Waktu</th></tr></thead>
                <tbody>
                @forelse ($lateInitiatives as $r)
                    <tr>
                        <td><a href="{{ \App\Filament\Resources\StrategicInitiatives\StrategicInitiativeResource::getUrl('view', ['record' => $r['initiative']]) }}">{{ $r['initiative']->short_code }}</a> {{ $r['initiative']->title }}<br><span class="arah-muted">PIC: {{ $r['initiative']->owner?->name }}</span></td>
                        <td class="num">{{ Fmt::num($r['initiative']->progress, 0) }}%</td>
                        <td class="num">{{ Fmt::num($r['elapsed'], 0) }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="arah-empty">Tidak ada inisiatif terlambat.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>KPI kritis & merah <small>capaian kumulatif (YTD)</small></h3>
        <div class="arah-scroll">
        <table class="arah-table">
            <thead><tr><th>KPI</th><th>Pemilik</th><th class="num">Target YTD</th><th class="num">Realisasi YTD</th><th class="num">Skor</th><th>Bulan</th></tr></thead>
            <tbody>
            @foreach ($criticalKpis as $r)
                <tr>
                    <td><a href="{{ \App\Filament\Resources\Kpis\KpiResource::getUrl('view', ['record' => $r['kpi']]) }}">{{ $r['kpi']->short_code }}</a> {{ $r['kpi']->name }} @if($r['kpi']->is_critical)<span class="arah-badge arah-biru">kritis</span>@endif<br><span class="arah-muted">{{ $r['objective']->title }}</span></td>
                    <td>{{ $r['kpi']->owner?->name }}</td>
                    <td class="num">{{ Fmt::kpiValue($r['target'], $r['kpi']->unit, $r['kpi']->decimals) }}</td>
                    <td class="num">{{ Fmt::kpiValue($r['actual'], $r['kpi']->unit, $r['kpi']->decimals) }}</td>
                    <td class="num"><span class="arah-badge {{ $badge($r['status']) }}">{{ Fmt::num($r['score'], 1) }}</span></td>
                    <td>{{ $r['last_month'] ? Terms::monthShort($r['last_month']) : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
</x-filament-panels::page>
