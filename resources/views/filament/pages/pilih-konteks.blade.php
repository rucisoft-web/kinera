<x-filament-panels::page>
<div class="arah">
    @if ($companies->isEmpty())
        <div class="arah-card">
            <h3>Belum ada instansi</h3>
            @if (auth()->user()?->isSuperAdmin())
                <p class="arah-muted">Tambahkan instansi pertama, lalu buat perioda (misalnya Tahun 2026) di tab <b>Perioda</b> pada halaman instansi tersebut. Setelah itu kembali ke halaman ini untuk memilihnya, dan isi datanya lewat <b>Import Data</b>.</p>
                <a class="arah-btn arah-btn-primary" href="{{ \App\Filament\Resources\Companies\CompanyResource::getUrl('create') }}">Tambah instansi</a>
            @else
                <p class="arah-muted">Akun Anda belum terdaftar di instansi mana pun. Hubungi Super Admin.</p>
            @endif
        </div>
    @endif
    @foreach ($companies as $c)
        <div class="arah-card" style="margin-bottom:1rem">
            @unless ($single)<h3>{{ $c->code }} — {{ $c->name }}</h3>@else<h3>{{ $c->name }}</h3>@endunless
            <div class="arah-muted" style="margin-bottom:.8rem">{{ collect([$c->industry, $c->city])->filter()->join(' · ') }}</div>
            @if ($c->periods->isEmpty())
                <p class="arah-muted">Instansi ini belum punya perioda.
                    @if (auth()->user()?->isSuperAdmin())
                        <a href="{{ \App\Filament\Resources\Companies\CompanyResource::getUrl('edit', ['record' => $c]) }}" style="text-decoration:underline">Buat perioda</a> di tab Perioda.
                    @endif
                </p>
            @endif
            <div class="arah-grid arah-grid-3">
                @foreach ($c->periods as $p)
                    <div class="arah-card" style="{{ $p->id === $currentPeriod ? 'border:2px solid var(--k-taum)' : '' }}">
                        <h3>{{ $p->name }} <span class="arah-badge {{ $p->status === 'berjalan' ? 'arah-hijau' : ($p->status === 'perencanaan' ? 'arah-kuning' : 'arah-abu') }}">{{ $p->status }}</span></h3>
                        <div class="arah-muted">{{ $p->start_date->translatedFormat('d M Y') }} – {{ $p->end_date->translatedFormat('d M Y') }}</div>
                        <div style="font-size:.8rem; margin:.5rem 0">{{ $p->notes }}</div>
                        @if ($p->id === $currentPeriod)
                            <span class="arah-badge arah-biru">Sedang aktif</span>
                        @else
                            <button type="button" class="arah-btn arah-btn-primary" wire:click="pilih({{ $p->id }})">Gunakan perioda ini</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</x-filament-panels::page>
