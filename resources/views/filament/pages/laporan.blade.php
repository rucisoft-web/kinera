@php
    use App\Support\Fmt;
    use App\Support\Terms;
    $badge = fn ($s) => match ($s) { 'hijau' => 'arah-hijau', 'kuning' => 'arah-kuning', 'merah' => 'arah-merah', default => 'arah-abu' };
@endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-card" style="display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; justify-content:space-between">
        <div style="display:flex; flex-wrap:wrap; gap:.4rem">
            @foreach ($reports as $key => $label)
                <button type="button" wire:click="setReport('{{ $key }}')" class="arah-btn {{ $current === $key ? 'arah-btn-primary' : '' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div style="display:flex; gap:.4rem">
            @if ($canExport)
                <a class="arah-btn arah-btn-primary" href="{{ route('arah.report.export', ['report' => $current]) }}">⤓ Unduh Excel</a>
            @else
                <button class="arah-btn" disabled title="Butuh izin report.export">⤓ Unduh Excel</button>
            @endif
            <button type="button" class="arah-btn" onclick="window.print()">🖶 Cetak</button>
        </div>
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>{{ $reports[$current] }}</h3>
        <div class="arah-scroll">
        @if ($current === 'scorecard')
            <table class="arah-table">
                <thead><tr><th>KPI</th><th>Satuan</th>@foreach (range(1, 12) as $m)<th class="num">{{ Terms::monthShort($m) }}</th>@endforeach<th class="num">YTD</th><th class="num">Skor</th></tr></thead>
                <tbody>
                @php $lastP = null; @endphp
                @foreach ($data as $row)
                    @if ($lastP !== $row['perspective'])
                        <tr><td colspan="16" style="background:var(--k-kertas-2); font-weight:700">{{ $row['perspective'] }}</td></tr>
                        @php $lastP = $row['perspective']; @endphp
                    @endif
                    <tr>
                        <td style="min-width:16rem"><strong>{{ $row['kpi']->short_code }}</strong> {{ $row['kpi']->name }}<br><span class="arah-muted">{{ $row['kpi']->owner?->name }} · {{ $row['kpi']->direction === 'lower' ? '↓ makin kecil makin baik' : '↑ makin besar makin baik' }}</span></td>
                        <td>{{ $row['kpi']->unit }}</td>
                        @foreach ($row['monthly'] as $m => $mv)
                            <td class="num" title="Target {{ Fmt::num($mv['target'], $row['kpi']->decimals) }}">
                                @if ($mv['actual'] !== null)
                                    <span class="arah-dot" style="background:{{ Fmt::statusHex($mv['status']) }}"></span> {{ Fmt::num($mv['actual'], $row['kpi']->decimals) }}
                                @else
                                    <span class="arah-muted">{{ Fmt::num($mv['target'], $row['kpi']->decimals) }}</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="num">{{ Fmt::num($row['ytd']['actual'], $row['kpi']->decimals) }}<br><span class="arah-muted">/ {{ Fmt::num($row['ytd']['target'], $row['kpi']->decimals) }}</span></td>
                        <td class="num"><span class="arah-badge {{ $badge($row['ytd']['status']) }}">{{ Fmt::num($row['ytd']['score'], 1) }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="arah-muted" style="margin-top:.5rem">Angka abu-abu = target (belum ada realisasi). Titik warna = status bulan tersebut.</div>
        @elseif ($current === 'objectives')
            <div class="arah-grid arah-grid-5" style="margin-bottom:1rem">
                <div class="arah-stat"><span class="label">Skor strategis</span><span class="value">{{ Fmt::num($data['overall']['score'], 1) }}</span><span class="arah-badge {{ $badge($data['overall']['status']) }}">{{ $data['overall']['status'] }}</span></div>
                @foreach ($data['perspectives'] as $p)
                    <div class="arah-stat"><span class="label">{{ $p['label'] }}</span><span class="value">{{ Fmt::num($p['score'], 1) }}</span><span class="arah-badge {{ $badge($p['status']) }}">{{ $p['status'] }}</span></div>
                @endforeach
            </div>
            <table class="arah-table">
                <thead><tr><th>Sasaran</th><th>Perspektif</th><th>Penanggung jawab</th><th class="num">Bobot</th><th class="num">KPI</th><th class="num">Skor</th></tr></thead>
                <tbody>
                @foreach ($data['objectives'] as $o)
                    <tr><td><strong>{{ $o['objective']->short_code }}</strong> {{ $o['objective']->title }}</td><td>{{ Terms::perspective($o['objective']->perspective) }}</td><td>{{ $o['objective']->owner?->name }}</td>
                        <td class="num">{{ Fmt::num($o['objective']->weight) }}</td><td class="num">{{ $o['kpis']->count() }}</td><td class="num"><span class="arah-badge {{ $badge($o['status']) }}">{{ Fmt::num($o['score'], 1) }}</span></td></tr>
                @endforeach
                </tbody>
            </table>
        @elseif ($current === 'initiatives')
            <table class="arah-table">
                <thead><tr><th>Inisiatif</th><th>PIC</th><th>Kuadran</th><th class="num">Progres</th><th class="num">Waktu</th><th class="num">Anggaran</th><th class="num">Realisasi</th><th>Milestone berikutnya</th></tr></thead>
                <tbody>
                @foreach ($data as $r)
                    @php $i = $r['initiative']; @endphp
                    <tr><td><strong>{{ $i->short_code }}</strong> {{ $i->title }} @if($r['late'])<span class="arah-badge arah-merah">terlambat</span>@endif<br><span class="arah-muted">{{ $i->start_date?->format('d/m/Y') }} – {{ $i->end_date?->format('d/m/Y') }} · {{ \App\Models\StrategicInitiative::STATUSES[$i->status] ?? $i->status }}</span></td>
                        <td>{{ $i->owner?->name }}</td>
                        <td><span class="arah-badge" style="background:{{ \App\Services\InitiativePortfolioService::QUADRANTS[$r['quadrant']]['color'] }}22; color:{{ \App\Services\InitiativePortfolioService::QUADRANTS[$r['quadrant']]['color'] }}">{{ \App\Services\InitiativePortfolioService::QUADRANTS[$r['quadrant']]['label'] }}</span></td>
                        <td class="num">{{ Fmt::num($i->progress) }}%</td><td class="num">{{ Fmt::num($r['elapsed']) }}%</td>
                        <td class="num">{{ Fmt::rp($r['planned']) }}</td><td class="num">{{ Fmt::rp($r['realized']) }}<br><span class="arah-muted">{{ Fmt::pct($r['absorption'], 0) }}</span></td>
                        <td>{{ $r['next_milestone']?->name ?? '-' }}<br><span class="arah-muted">{{ $r['next_milestone']?->due_date?->format('d/m/Y') }}</span></td></tr>
                @endforeach
                </tbody>
            </table>
        @else
            <table class="arah-table">
                <thead><tr><th>Temuan</th><th>Tingkat</th><th class="num">Nilai</th><th>Status</th><th>Tindakan korektif</th></tr></thead>
                <tbody>
                @foreach ($data as $f)
                    <tr><td><strong>{{ $f->short_code }}</strong> {{ $f->title }}<br><span class="arah-muted">{{ \App\Models\Finding::SOURCES[$f->source] ?? $f->source }} · {{ $f->raised_at?->format('d/m/Y') }} · {{ $f->raiser?->name }}</span></td>
                        <td><span class="arah-badge {{ in_array($f->severity, ['tinggi', 'kritis']) ? 'arah-merah' : 'arah-kuning' }}">{{ $f->severity }}</span></td>
                        <td class="num">{{ $f->amount ? Fmt::rp($f->amount) : '-' }}</td>
                        <td><span class="arah-badge {{ $f->status === 'ditutup' ? 'arah-hijau' : ($f->status === 'siap_ditutup' ? 'arah-biru' : 'arah-kuning') }}">{{ \App\Models\Finding::STATUSES[$f->status] ?? $f->status }}</span></td>
                        <td>@forelse ($f->correctiveActions as $a)<div style="margin-bottom:.25rem">{{ $a->status === 'selesai' ? '✔' : '•' }} {{ $a->title }} <span class="arah-muted">— {{ $a->owner?->name }}, {{ $a->due_date?->format('d/m') }}</span></div>@empty<span class="arah-muted">Belum ada tindakan korektif</span>@endforelse</td></tr>
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
    </div>
</div>
</x-filament-panels::page>
