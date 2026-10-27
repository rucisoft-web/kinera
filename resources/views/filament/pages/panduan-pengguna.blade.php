@php
    $src = fn (string $ref) => asset('images/'.(str_starts_with($ref, 'k:') ? 'kenali/' : 'panduan/').substr($ref, 2).'.jpg').'?v=2';
@endphp
<x-filament-panels::page>
<div class="arah panduan" x-data="{ tab: (location.hash || '#semua').slice(1) }" x-init="if (! @js(array_keys($roles)).includes(tab)) tab = 'semua'">
    <p class="kenali-lead">Pilih peran Anda. Setiap panduan berisi tugas utama, ritme kerja, dan langkah-langkah dengan contoh tampilan dari data demo. Klik gambar untuk memperbesar.</p>

    <nav class="panduan-tabs" role="tablist">
        @foreach ($roles as $key => $role)
            <button type="button" role="tab" class="panduan-tab" :class="tab === '{{ $key }}' && 'is-active'" :aria-selected="tab === '{{ $key }}'"
                @click="tab = '{{ $key }}'; history.replaceState(null, '', '#{{ $key }}')">{{ $role['label'] }}</button>
        @endforeach
    </nav>

    @foreach ($roles as $key => $role)
        <section x-show="tab === '{{ $key }}'" x-cloak class="panduan-role">
            <div class="arah-card panduan-intro">
                <div>
                    <h2>{{ $role['label'] }}</h2>
                    <p>{{ $role['who'] }}</p>
                </div>
                @if ($role['rhythm'])
                    <div class="panduan-rhythm">
                        <b>Ritme kerja</b>
                        <ul>@foreach ($role['rhythm'] as $r)<li>{{ $r }}</li>@endforeach</ul>
                    </div>
                @endif
            </div>

            <ol class="panduan-steps">
                @foreach ($role['steps'] as $i => [$title, $text, $image])
                    <li class="arah-card panduan-step {{ $image ? 'has-image' : '' }}">
                        <div class="panduan-step-text">
                            <div class="panduan-step-no">{{ $i + 1 }}</div>
                            <div>
                                <h3>{{ $title }}</h3>
                                <p>{{ $text }}</p>
                            </div>
                        </div>
                        @if ($image)
                            <a class="kenali-shot panduan-shot" href="{{ $src($image) }}" target="_blank" rel="noopener">
                                <img src="{{ $src($image) }}" alt="{{ $title }}" loading="lazy">
                            </a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </section>
    @endforeach

    <div class="arah-muted panduan-foot">Tangkapan layar diambil dari data demo UIN Sunan Kalijaga Yogyakarta (hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak; bukan data resmi). Menu yang terlihat bisa berbeda sesuai hak akses peran Anda.</div>
</div>
</x-filament-panels::page>
