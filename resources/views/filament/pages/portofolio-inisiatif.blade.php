@php use App\Support\Fmt; @endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-card">
        <div class="arah-muted">Nilai = dampak × 2 + urgensi (3–15, tinggi ≥ {{ \App\Services\InitiativePortfolioService::VALUE_THRESHOLD }}) · Upaya = risiko + tingkat anggaran (2–10, tinggi &gt; {{ \App\Services\InitiativePortfolioService::EFFORT_THRESHOLD }}). Garis tipis pada bar = waktu berjalan.</div>
    </div>
    <div style="display:grid; grid-template-columns: 1.6rem 1fr; gap:.5rem; margin-top:1rem">
        <div style="writing-mode:vertical-rl; transform:rotate(180deg); text-align:center; font-size:.75rem; color:var(--k-tinta-2); font-weight:700">NILAI →</div>
        <div>
            <div class="arah-matrix">
                @foreach (['quick_win', 'prioritas', 'pengisi', 'tinjau_ulang'] as $q)
                    @php $meta = $quadrants[$q]; $items = $rows->where('quadrant', $q); @endphp
                    <div class="arah-quad" style="background: {{ $meta['color'] }}12; border-color: {{ $meta['color'] }}55">
                        <h4 style="color: {{ $meta['color'] }}">{{ $meta['label'] }} <span class="arah-muted">({{ $items->count() }})</span></h4>
                        <div class="hint">{{ $meta['hint'] }}</div>
                        @forelse ($items as $r)
                            @php $i = $r['initiative']; @endphp
                            <div class="arah-ini">
                                <div style="display:flex; justify-content:space-between; gap:.5rem">
                                    <a class="t" style="color:var(--k-tinta); text-decoration:none" href="{{ \App\Filament\Resources\StrategicInitiatives\StrategicInitiativeResource::getUrl('view', ['record' => $i]) }}">{{ $i->short_code }} · {{ $i->title }}</a>
                                    @if ($r['late'])<span class="arah-badge arah-merah">terlambat</span>@endif
                                </div>
                                <div style="display:flex; align-items:center; gap:.5rem; margin-top:.35rem">
                                    <div class="arah-bar" style="flex:1"><span style="width:{{ $i->progress }}%; background:{{ $meta['color'] }}"></span>@if($r['elapsed'] !== null)<i class="tick" style="left:{{ $r['elapsed'] }}%"></i>@endif</div>
                                    <strong style="font-size:.75rem">{{ Fmt::num($i->progress) }}%</strong>
                                </div>
                                <div class="m">
                                    <span>PIC: {{ $i->owner?->name }}</span>
                                    <span>Nilai {{ $r['value'] }} · Upaya {{ $r['effort'] }}</span>
                                    <span>Anggaran {{ Fmt::rp($r['planned']) }} · serapan {{ Fmt::pct($r['absorption'], 0) }}</span>
                                    @if ($r['next_milestone'])<span>▸ {{ $r['next_milestone']->name }} ({{ $r['next_milestone']->due_date->format('d/m/Y') }})</span>@endif
                                </div>
                            </div>
                        @empty
                            <div class="arah-empty">Tidak ada inisiatif.</div>
                        @endforelse
                    </div>
                @endforeach
            </div>
            <div style="text-align:center; font-size:.75rem; color:var(--k-tinta-2); font-weight:700; margin-top:.4rem">← UPAYA RENDAH · UPAYA TINGGI →</div>
        </div>
    </div>
</div>
</x-filament-panels::page>
