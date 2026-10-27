@php
    use App\Models\Kpi;
    use App\Services\BluContractService;
    use App\Support\Fmt;
    $badge = fn ($s) => match ($s) { 'hijau' => 'arah-hijau', 'kuning' => 'arah-kuning', 'merah' => 'arah-merah', default => 'arah-abu' };
    $check = ['ok' => ['✓ Terpenuhi', 'arah-hijau'], 'gagal' => ['✗ Tidak terpenuhi', 'arah-merah'], 'belum' => ['… Belum dapat dinilai', 'arah-abu']];
    $dl = ['tepat waktu' => 'arah-hijau', 'terlambat' => 'arah-merah', 'lewat tenggat' => 'arah-merah', 'segera' => 'arah-kuning', 'akan datang' => 'arah-abu', 'tidak ada perubahan' => 'arah-abu', 'tidak diajukan' => 'arah-abu'];
    $floor = config('dhastram.score_floor', 65);
    $contract = $a['contract'];
    $groups = collect($a['rows'])->groupBy(fn ($r) => $r['kpi']->aspect);
@endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-card" style="display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; justify-content:space-between">
        <div style="display:flex; flex-wrap:wrap; gap:.4rem; align-items:center">
            <span class="arah-muted">Capaian s.d.</span>
            @foreach (BluContractService::QUARTERS as $q => $label)
                <button type="button" wire:click="setUpto({{ $q }})" class="arah-btn {{ $upto === $q ? 'arah-btn-primary' : '' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div style="display:flex; gap:.4rem; flex-wrap:wrap">
            @if ($contract)
                <a class="arah-btn" target="_blank" href="{{ route('blu.print', ['doc' => 'capaian', 'contract' => $contract->id, 's' => $upto]) }}">🖶 Perhitungan capaian (Lampiran VI)</a>
                <a class="arah-btn" target="_blank" href="{{ route('blu.print', ['doc' => 'kontrak', 'contract' => $contract->id]) }}">🖶 Kontrak Kinerja (Lampiran I)</a>
                <a class="arah-btn" href="{{ \App\Filament\Resources\PerformanceContracts\PerformanceContractResource::getUrl('view', ['record' => $contract]) }}">Buka {{ $contract->label }}</a>
            @else
                <a class="arah-btn arah-btn-primary" href="{{ \App\Filament\Resources\PerformanceContracts\PerformanceContractResource::getUrl('create') }}">Susun Kontrak Kinerja</a>
            @endif
        </div>
    </div>

    <div class="arah-grid arah-grid-5" style="margin-top:1rem">
        <div class="arah-stat arah-card"><span class="label">Rata-rata capaian diakui</span><span class="value">{{ Fmt::pct($a['average'], 2) }}</span><span class="sub">{{ $a['count'] }} indikator · s.d. {{ BluContractService::QUARTERS[$upto] }}</span></div>
        <div class="arah-stat arah-card"><span class="label">Aspek layanan</span><span class="value">{{ Fmt::pct($a['by_aspect']['layanan']['average'] ?? null, 2) }}</span><span class="sub">{{ $a['by_aspect']['layanan']['count'] ?? 0 }} indikator</span></div>
        <div class="arah-stat arah-card"><span class="label">Aspek keuangan</span><span class="value">{{ Fmt::pct($a['by_aspect']['keuangan']['average'] ?? null, 2) }}</span><span class="sub">{{ $a['by_aspect']['keuangan']['count'] ?? 0 }} indikator</span></div>
        <div class="arah-stat arah-card"><span class="label">Capaian &lt; {{ $floor }}%</span><span class="value" style="color:{{ count($a['below_floor']) ? 'var(--k-merah)' : 'inherit' }}">{{ count($a['below_floor']) }}</span><span class="sub">menggugurkan syarat insentif</span></div>
        <div class="arah-stat arah-card"><span class="label">Wajib belum tercapai</span><span class="value" style="color:{{ count($a['mandatory_unmet']) ? 'var(--k-merah)' : 'inherit' }}">{{ count($a['mandatory_unmet']) }}</span><span class="sub">indikator mandatory &lt; 100%</span></div>
    </div>

    @if (count($a['below_floor']) || count($a['mandatory_unmet']))
        <div class="arah-card" style="margin-top:1rem; border-left:4px solid var(--k-merah)">
            <strong>Peringatan syarat insentif.</strong>
            @if (count($a['below_floor'])) Capaian di bawah {{ $floor }}%: {{ collect($a['below_floor'])->map(fn ($r) => $r['kpi']->code.' ('.Fmt::pct($r['recognized'], 1).')')->join(', ') }}. @endif
            @if (count($a['mandatory_unmet'])) Indikator wajib belum 100%: {{ collect($a['mandatory_unmet'])->map(fn ($r) => $r['kpi']->code)->join(', ') }}. @endif
            Bila kondisi ini bertahan sampai akhir tahun, permohonan insentif kelebihan capaian tidak memenuhi Pasal 14 ayat (4) PER-6/PB/2025.
        </div>
    @endif

    <div class="arah-card" style="margin-top:1rem">
        <h3>Perhitungan capaian Indikator Kinerja <small>format lampiran surat persetujuan (Lampiran VI) · {{ $contract ? $contract->label.' — '.(\App\Models\PerformanceContract::STATUSES[$contract->status] ?? $contract->status) : 'belum ada Kontrak Kinerja' }}</small></h3>
        <div class="arah-scroll">
        <table class="arah-table">
            <thead><tr><th>Indikator Kinerja</th><th class="num">Target s.d. {{ BluContractService::QUARTERS[$upto] }}</th><th class="num">Realisasi</th><th class="num">Capaian</th><th class="num">Maks.</th><th class="num">Capaian diakui</th><th>Catatan</th></tr></thead>
            <tbody>
            @foreach ($groups as $aspect => $rows)
                <tr><td colspan="7" style="background:var(--k-kertas-2); font-weight:700">{{ Kpi::ASPECTS[$aspect] ?? $aspect }} <span class="arah-muted">· rata-rata {{ Fmt::pct($a['by_aspect'][$aspect]['average'] ?? null, 2) }}</span></td></tr>
                @foreach ($rows as $r)
                    @php $k = $r['kpi']; @endphp
                    <tr>
                        <td style="min-width:18rem"><strong>{{ $k->code }}</strong> {{ $k->name }} @if ($k->is_mandatory)<span class="arah-badge arah-biru">wajib</span>@endif
                            <br><span class="arah-muted">{{ $k->unit }} · {{ $k->direction === 'lower' ? '↓' : '↑' }} · {{ $k->owner?->name }}</span></td>
                        <td class="num">{{ Fmt::num($r['target'], $k->decimals) }}</td>
                        <td class="num">{{ Fmt::num($r['actual'], $k->decimals) }}</td>
                        <td class="num">{{ Fmt::pct($r['raw'], 2) }}</td>
                        <td class="num">{{ Fmt::num($r['cap'], 0) }}%</td>
                        <td class="num"><span class="arah-badge {{ $badge($r['status']) }}">{{ Fmt::pct($r['recognized'], 2) }}</span></td>
                        <td style="font-size:.78rem">
                            @if ($r['below_floor'])<span class="arah-badge arah-merah">&lt; {{ $floor }}%</span>@endif
                            @if ($r['mandatory_unmet'])<span class="arah-badge arah-merah">wajib belum tercapai</span>@endif
                            @if ($r['lowered'])<span class="arah-badge arah-kuning" title="Pasal 19 ayat (8)">target diturunkan · maks 100%</span>@endif
                            @if ($r['pending'])<span class="arah-badge arah-abu">{{ $r['pending'] }} realisasi belum disetujui</span>@endif
                            @if ($r['actual'] === null)<span class="arah-muted">belum ada realisasi{{ $k->frequency === 'tahunan' ? ' (trajektori tahunan)' : '' }}</span>@endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr style="font-weight:700"><td colspan="5" style="text-align:right">Total nilai capaian kinerja</td><td class="num">{{ Fmt::num($a['total'], 2) }}</td><td></td></tr>
            <tr style="font-weight:700"><td colspan="5" style="text-align:right">Rata-rata nilai capaian kinerja</td><td class="num">{{ Fmt::pct($a['average'], 2) }}</td><td></td></tr>
            </tbody>
        </table>
        </div>
        <div class="arah-muted" style="margin-top:.5rem">Capaian = realisasi ÷ target × 100 (indikator ↓: target ÷ realisasi × 100). Capaian diakui dibatasi capaian maksimum per indikator (KMK remunerasi BLU); indikator yang targetnya diturunkan lewat perubahan Kontrak Kinerja dihitung paling tinggi 100% (Pasal 19 ayat 8).</div>
    </div>

    <div class="arah-grid arah-grid-2" style="margin-top:1rem">
        <div class="arah-card">
            <h3>Syarat permohonan insentif kelebihan capaian <small>Pasal 14 ayat (4) · dinilai atas capaian setahun</small></h3>
            <table class="arah-table">
                <tbody>
                @foreach ($checks as $c)
                    <tr>
                        <td style="width:2.2rem"><strong>{{ strlen($c['key']) === 1 ? $c['key'].'.' : '' }}</strong></td>
                        <td>{{ $c['label'] }}<br><span class="arah-muted">{{ $c['ref'] }} · {{ $c['note'] }}</span></td>
                        <td style="white-space:nowrap"><span class="arah-badge {{ $check[$c['status']][1] }}">{{ $check[$c['status']][0] }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="arah-muted" style="margin-top:.5rem">Permohonan diajukan setelah LK audited terbit, paling lambat 30 Juni tahun berikutnya, bersama surat pernyataan kebenaran perhitungan (Lampiran IV).</div>
        </div>
        <div class="arah-card">
            <h3>Tenggat siklus Kontrak Kinerja {{ $period?->year }} <small>PER-6/PB/2025</small></h3>
            <table class="arah-table">
                <thead><tr><th>Tenggat</th><th>Kegiatan</th><th>Status</th></tr></thead>
                <tbody>
                @foreach ($deadlines as $d)
                    <tr>
                        <td style="white-space:nowrap">{{ $d['due']->translatedFormat('d M Y') }}</td>
                        <td>{{ $d['title'] }}<br><span class="arah-muted">{{ $d['ref'] }}{{ $d['done'] ? ' · selesai '.$d['done']->translatedFormat('d M Y') : '' }}</span></td>
                        <td><span class="arah-badge {{ $dl[$d['status']] ?? 'arah-abu' }}">{{ $d['status'] }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="arah-muted" style="margin-top:.5rem">Hari kerja dihitung Senin–Jumat; libur nasional belum diperhitungkan. Keterlambatan usulan/penyampaian kontrak menjadi pertimbangan penolakan insentif (Pasal 6 ayat 4, Pasal 8 ayat 5).</div>
        </div>
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>Capaian per trajektori triwulan <small>kumulatif s.d. akhir triwulan</small></h3>
        <div class="arah-scroll">
        <table class="arah-table">
            <thead><tr><th>Indikator Kinerja</th>@foreach (BluContractService::QUARTERS as $label)<th class="num">{{ $label }}</th>@endforeach</tr></thead>
            <tbody>
            @foreach ($quarterly as $row)
                <tr>
                    <td style="min-width:16rem"><strong>{{ $row['kpi']->code }}</strong> {{ $row['kpi']->name }}</td>
                    @foreach ($row['q'] as $q => $v)
                        <td class="num">
                            @if ($v['score'] !== null)
                                <span class="arah-badge {{ $badge(app(\App\Services\KpiScoringService::class)->status($v['score'])) }}">{{ Fmt::num($v['score'], 1) }}%</span><br>
                                <span class="arah-muted">{{ Fmt::num($v['actual'], $row['kpi']->decimals) }} / {{ Fmt::num($v['target'], $row['kpi']->decimals) }}</span>
                            @else
                                <span class="arah-muted">{{ $v['target'] === null ? '—' : 'target '.Fmt::num($v['target'], $row['kpi']->decimals) }}</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
</x-filament-panels::page>
