@php
    use App\Support\Fmt;
    $m = $map;
    $wrap = function (string $text, int $width = 29): array {
        $lines = explode("\n", wordwrap($text, $width, "\n", true));
        return array_slice($lines, 0, 3);
    };
@endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-card">
        <div style="display:flex; flex-wrap:wrap; gap:1rem; font-size:.75rem; margin-bottom:.5rem">
            <span><span class="arah-dot" style="background:#2E7D4F"></span> Hijau ≥ 100</span>
            <span><span class="arah-dot" style="background:#C88A12"></span> Kuning ≥ 80</span>
            <span><span class="arah-dot" style="background:#D32F2F"></span> Merah &lt; 80</span>
            <span><span class="arah-dot" style="background:#94A3B8"></span> Belum ada realisasi</span>
            <span class="arah-muted">Panah = hubungan sebab-akibat (tebal = kuat). Klik kartu untuk detail sasaran.</span>
        </div>
        <div class="arah-scroll">
        <svg viewBox="0 0 {{ $m['width'] }} {{ $m['height'] }}" class="arah-map" style="min-width:900px" role="img" aria-label="Peta strategi">
            <defs>
                <marker id="arrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                    <path d="M 0 0 L 10 5 L 0 10 z" fill="#5B6B82"/>
                </marker>
            </defs>
            @foreach ($m['lanes'] as $i => $lane)
                <rect x="0" y="{{ $lane['y'] + 4 }}" width="{{ $m['width'] }}" height="162" rx="12" fill="{{ $lane['color'] }}" opacity="{{ $i % 2 ? .05 : .09 }}"/>
                <rect x="0" y="{{ $lane['y'] + 4 }}" width="6" height="162" rx="3" fill="{{ $lane['color'] }}"/>
                <text x="18" y="{{ $lane['y'] + 88 }}" class="lane-label" fill="{{ $lane['color'] }}">{{ $lane['label'] }}</text>
            @endforeach
            @foreach ($m['arrows'] as $a)
                @php $midY = ($a['sy'] + $a['ty']) / 2; @endphp
                <path d="M {{ $a['sx'] }} {{ $a['sy'] }} C {{ $a['sx'] }} {{ $midY }}, {{ $a['tx'] }} {{ $midY }}, {{ $a['tx'] }} {{ $a['ty'] }}" fill="none" stroke="#5B6B82" stroke-opacity=".55" stroke-width="{{ 0.8 + $a['strength'] * 0.6 }}" marker-end="url(#arrow)"/>
            @endforeach
            @foreach ($m['lanes'] as $lane)
                @foreach ($lane['cards'] as $c)
                    @php $col = Fmt::statusHex($c['status']); @endphp
                    <a href="{{ \App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource::getUrl('view', ['record' => $c['objective']]) }}">
                        <rect x="{{ $c['x'] }}" y="{{ $c['y'] }}" width="{{ $m['cardW'] }}" height="{{ $m['cardH'] }}" rx="10" fill="var(--k-kartu)" stroke="{{ $col }}" stroke-width="2"/>
                        <rect x="{{ $c['x'] }}" y="{{ $c['y'] }}" width="{{ $m['cardW'] }}" height="6" rx="3" fill="{{ $col }}"/>
                        <text x="{{ $c['x'] + 10 }}" y="{{ $c['y'] + 22 }}" class="card-code">{{ $c['objective']->short_code }}</text>
                        <text x="{{ $c['x'] + $m['cardW'] - 10 }}" y="{{ $c['y'] + 22 }}" text-anchor="end" style="font-size:12px; font-weight:700" fill="{{ $col }}">{{ $c['score'] !== null ? Fmt::num($c['score'], 1) : '–' }}</text>
                        @foreach ($wrap($c['objective']->title) as $li => $line)
                            <text x="{{ $c['x'] + 10 }}" y="{{ $c['y'] + 40 + $li * 14 }}" class="card-title">{{ $line }}</text>
                        @endforeach
                        <text x="{{ $c['x'] + 10 }}" y="{{ $c['y'] + $m['cardH'] - 8 }}" style="font-size:9.5px; fill:var(--k-tinta-2)">{{ $c['kpis']->count() }} KPI · {{ \Illuminate\Support\Str::limit($c['objective']->owner?->name, 22) }}</text>
                    </a>
                @endforeach
            @endforeach
        </svg>
        </div>
    </div>
</div>
</x-filament-panels::page>
