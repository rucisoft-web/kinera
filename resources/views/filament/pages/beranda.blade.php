@php
    use App\Support\Fmt;
    use App\Support\Terms;
    $t = $tasks;
    $badge = fn ($s) => match ($s) { 'hijau' => 'arah-hijau', 'kuning' => 'arah-kuning', 'merah' => 'arah-merah', default => 'arah-abu' };
    $hour = now()->hour;
    $greeting = match (true) { $hour < 11 => 'Selamat pagi', $hour < 15 => 'Selamat siang', $hour < 18 => 'Selamat sore', default => 'Selamat malam' };
@endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-card">
        <div class="arah-hero">
            <div>
                <h2>{{ $greeting }}, {{ $user->name }}</h2>
                <div class="arah-muted">{{ $user->job_title }} · {{ $roles ?: 'tanpa peran' }}</div>
                <div class="arah-muted" style="margin-top:.25rem">{{ $company?->name }} — {{ $period?->name }} ({{ $period?->status }}){{ $monthName ? ' · realisasi terakhir yang diharapkan: '.$monthName : '' }}</div>
            </div>
            @if ($exec)
                <div style="display:flex; gap:1.5rem; flex-wrap:wrap">
                    <div class="arah-stat"><span class="label">Skor strategis</span><span class="value">{{ Fmt::num($exec['overall']['score'], 1) }}</span><span class="arah-badge {{ $badge($exec['overall']['status']) }}">{{ $exec['overall']['status'] ?? 'belum ada' }}</span></div>
                    <div class="arah-stat"><span class="label">KPI merah</span><span class="value" style="color:var(--k-merah)">{{ $exec['kpiStats']['merah'] }}</span><span class="sub">dari {{ $exec['kpiStats']['total'] }} KPI</span></div>
                    <div class="arah-stat"><span class="label">Inisiatif terlambat</span><span class="value">{{ $exec['lateInitiatives']->count() }}</span><span class="sub">dari {{ $exec['initiativeCount'] }} aktif</span></div>
                    <div class="arah-stat"><span class="label">Temuan terbuka</span><span class="value">{{ $exec['openFindings'] }}</span><span class="sub">belum ditutup</span></div>
                </div>
            @endif
        </div>
        <div class="arah-kamasan" style="margin-top:1rem"></div>
        @if ($exec)
            <div class="arah-grid arah-grid-4">
                @foreach ($exec['perspectives'] as $key => $p)
                    <div>
                        <div style="display:flex; justify-content:space-between; font-size:.8rem; margin-bottom:.3rem">
                            <span><span class="arah-dot" style="background:{{ $p['color'] }}"></span> {{ $p['label'] }}</span>
                            <strong>{{ Fmt::num($p['score'], 1) }}</strong>
                        </div>
                        <div class="arah-bar"><span style="width:{{ min(100, ($p['score'] ?? 0) / 1.2) }}%; background:{{ Fmt::statusHex($p['status']) }}"></span><i class="tick" style="left:{{ 100 / 1.2 }}%"></i></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <h3 style="margin:1.4rem 0 .6rem; font-size:1rem; font-weight:700">Tugas saya</h3>
    <div class="arah-grid arah-grid-2">
        @if ($user->hasPermission('kpi.input_actual'))
        <div class="arah-card">
            <h3>Realisasi KPI belum diisi <small>{{ $t['month'] ? Terms::months()[$t['month']] : '' }} · batas tanggal {{ config('dhastram.cycle.input_day') }}</small></h3>
            @forelse ($t['missing']->take(10) as $k)
                <ul class="arah-list"><li>
                    <span><a href="{{ \App\Filament\Resources\KpiActuals\KpiActualResource::getUrl('create', ['kpi' => $k->id, 'month' => $t['month']]) }}">{{ $k->short_code }}</a> {{ $k->name }}</span>
                    <span class="arah-muted">{{ $k->owner?->name }}</span>
                </li></ul>
            @empty
                <div class="arah-empty">Semua realisasi bulan ini sudah terisi. Terima kasih.</div>
            @endforelse
            @if ($t['missing']->count() > 10)<div class="arah-muted">+{{ $t['missing']->count() - 10 }} KPI lainnya</div>@endif
            @if ($t['returned']->isNotEmpty())
                <div style="margin-top:.6rem"><strong style="font-size:.8rem; color:var(--k-merah)">Dikembalikan untuk diperbaiki</strong></div>
                <ul class="arah-list">
                @foreach ($t['returned'] as $a)
                    <li><span><a href="{{ \App\Filament\Resources\KpiActuals\KpiActualResource::getUrl('edit', ['record' => $a]) }}">{{ $a->kpi->short_code }}</a> {{ $a->kpi->name }} — {{ Terms::monthShort($a->month) }}</span><span class="arah-muted" title="{{ $a->return_note }}">{{ \Illuminate\Support\Str::limit($a->return_note, 50) }}</span></li>
                @endforeach
                </ul>
            @endif
        </div>
        @endif

        @if ($user->hasPermission('kpi.verify_finance') || $user->hasPermission('kpi.approve_actual'))
        <div class="arah-card">
            <h3>Persetujuan realisasi <small>verifikasi tgl {{ config('dhastram.cycle.verify_day') }} · persetujuan tgl {{ config('dhastram.cycle.approve_day') }}</small></h3>
            <ul class="arah-list">
                @if ($user->hasPermission('kpi.verify_finance'))
                <li><span>KPI keuangan menunggu verifikasi</span><a href="{{ \App\Filament\Resources\KpiActuals\KpiActualResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'draf']]]) }}">{{ $t['toVerify']->count() }} baris</a></li>
                @endif
                @if ($user->hasPermission('kpi.approve_actual'))
                <li><span>Realisasi siap disetujui Pimpinan</span><a href="{{ \App\Filament\Resources\KpiActuals\KpiActualResource::getUrl('index') }}">{{ $t['toApprove']->count() }} baris</a></li>
                @endif
            </ul>
            <div class="arah-muted" style="margin-top:.4rem">Realisasi bertanda PROYEKSI belum berasal dari dokumen sumber; cocokkan dengan laporan sebelum disetujui.</div>
        </div>
        @endif

        <div class="arah-card">
            <h3>Aktivitas inisiatif jatuh tempo <small>14 hari ke depan & terlambat</small></h3>
            @forelse ($t['activities'] as $a)
                <ul class="arah-list"><li>
                    <span><a href="{{ \App\Filament\Resources\InitiativeActivities\InitiativeActivityResource::getUrl('edit', ['record' => $a]) }}">{{ $a->name }}</a><br><span class="arah-muted">{{ $a->initiative?->short_code }} · {{ \Illuminate\Support\Str::limit($a->initiative?->title, 45) }}</span></span>
                    <span style="text-align:right"><span class="arah-badge {{ $a->status === 'terlambat' ? 'arah-merah' : 'arah-biru' }}">{{ $a->due_date?->translatedFormat('d M') }}</span><br><span class="arah-muted">{{ Fmt::num($a->progress) }}%</span></span>
                </li></ul>
            @empty
                <div class="arah-empty">Tidak ada aktivitas yang jatuh tempo.</div>
            @endforelse
        </div>

        <div class="arah-card">
            <h3>Tindakan korektif & milestone</h3>
            @forelse ($t['actions'] as $c)
                <ul class="arah-list"><li>
                    <span><a href="{{ \App\Filament\Resources\CorrectiveActions\CorrectiveActionResource::getUrl('edit', ['record' => $c]) }}">{{ $c->title }}</a><br><span class="arah-muted">{{ $c->finding?->short_code }} · {{ \Illuminate\Support\Str::limit($c->finding?->title, 45) }}</span></span>
                    <span class="arah-badge {{ $c->is_overdue ? 'arah-merah' : 'arah-abu' }}">{{ $c->due_date?->translatedFormat('d M') ?? '-' }}</span>
                </li></ul>
            @empty
                <div class="arah-empty">Tidak ada tindakan korektif terbuka.</div>
            @endforelse
            @if ($t['milestones']->isNotEmpty())
                <div style="margin-top:.6rem"><strong style="font-size:.8rem">Milestone 30 hari</strong></div>
                <ul class="arah-list">
                @foreach ($t['milestones'] as $m)
                    <li><span>{{ $m->name }} <span class="arah-muted">({{ $m->initiative?->short_code }})</span></span><span class="arah-badge {{ $m->due_date->isPast() ? 'arah-merah' : 'arah-kuning' }}">{{ $m->due_date->translatedFormat('d M') }}</span></li>
                @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
</x-filament-panels::page>
