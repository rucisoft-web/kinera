<x-filament-panels::page>
<div class="arah bantuan">
    <aside class="bantuan-toc arah-card">
        <b>Topik bantuan</b>
        @foreach ($groups as $group => $items)
            <div class="bantuan-toc-group">
                <small>{{ $group }}</small>
                <ul>
                    @foreach ($items as $key => $title)
                        <li><a href="{{ \App\Filament\Pages\BantuanPengisian::urlFor($key) }}" @class(['is-active' => ($topic['key'] ?? null) === $key])>{{ $title }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </aside>

    <div class="bantuan-main">
        @if (! $topic)
            <div class="arah-card bantuan-hero">
                <span class="kenali-eyebrow">Bantuan pengisian</span>
                <h2>Teori, cara berpikir, dan contoh untuk setiap layar isian</h2>
                <p>Setiap layar isian Kinera punya tombol <b>Bantuan</b> di kanan atas. Tombol itu membuka halaman ini langsung pada topik yang sesuai: teori singkat, pertanyaan pemandu untuk menyusun isian, penjelasan tiap kolom, contoh di perguruan tinggi, dan tangkapan layar isian dari data demo.</p>
            </div>
            @foreach ($groups as $group => $items)
                <section class="kenali-section">
                    <h2>{{ $group }}</h2>
                    <div class="arah-grid arah-grid-3">
                        @foreach ($items as $key => $title)
                            @php($t = \App\Support\Bantuan::topic($key))
                            <a class="arah-card bantuan-card" href="{{ \App\Filament\Pages\BantuanPengisian::urlFor($key) }}">
                                <b>{{ $title }}</b>
                                <span>{{ $t['ringkas'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @else
            <div class="arah-card bantuan-hero">
                <span class="kenali-eyebrow">{{ $topic['group'] }}</span>
                <h2>{{ $topic['title'] }}</h2>
                <p>{{ $topic['ringkas'] }}</p>
                <div class="kenali-actions">
                    @if ($screenUrl)<a href="{{ $screenUrl }}" class="arah-btn arah-btn-primary">Buka layar {{ $topic['title'] }}</a>@endif
                    <button type="button" class="arah-btn" onclick="window.print()">Cetak / simpan PDF</button>
                </div>
            </div>

            <section class="bantuan-sec">
                <h3><span>1</span> Teori singkat</h3>
                @foreach ($topic['teori'] as $p)<p>{!! $p !!}</p>@endforeach
            </section>

            <section class="bantuan-sec">
                <h3><span>2</span> Cara berpikir sebelum mengisi</h3>
                <ol class="bantuan-steps">
                    @foreach ($topic['berpikir'] as [$q, $a])
                        <li><b>{{ $q }}</b><span>{!! $a !!}</span></li>
                    @endforeach
                </ol>
            </section>

            <section class="bantuan-sec">
                <h3><span>3</span> Panduan mengisi kolom</h3>
                <div class="bantuan-table-wrap">
                    <table class="bantuan-table">
                        <thead><tr><th style="width:28%">Kolom</th><th>Cara mengisi</th></tr></thead>
                        <tbody>
                            @foreach ($topic['kolom'] as [$k, $v])<tr><td><b>{{ $k }}</b></td><td>{!! $v !!}</td></tr>@endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bantuan-sec">
                <h3><span>4</span> Contoh isian di perguruan tinggi</h3>
                @if (! empty($topic['contoh_pengantar']))<p>{!! $topic['contoh_pengantar'] !!}</p>@endif
                <div class="bantuan-table-wrap">
                    <table class="bantuan-table bantuan-contoh">
                        <thead><tr>@foreach ($topic['contoh']['kolom'] as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
                        <tbody>
                            @foreach ($topic['contoh']['baris'] as $row)<tr>@foreach ($row as $cell)<td>{!! $cell !!}</td>@endforeach</tr>@endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            @if ($image)
                <section class="bantuan-sec">
                    <h3><span>5</span> Tangkapan layar isian</h3>
                    <p>Contoh formulir yang sudah terisi dari data demo UIN Sunan Kalijaga. Klik gambar untuk memperbesar.</p>
                    <a href="{{ $image }}" target="_blank" rel="noopener" class="bantuan-shot"><img src="{{ $image }}" alt="Contoh isian {{ $topic['title'] }}" loading="lazy"></a>
                </section>
            @endif

            <section class="bantuan-sec bantuan-two">
                <div class="arah-card bantuan-warn">
                    <b>Kesalahan yang sering terjadi</b>
                    <ul>@foreach ($topic['hindari'] as $h)<li>{!! $h !!}</li>@endforeach</ul>
                </div>
                <div class="arah-card bantuan-next">
                    <b>Setelah layar ini</b>
                    <ul>
                        @foreach ($topic['kaitan'] as $k => $why)
                            @if (\App\Support\Bantuan::exists($k))
                                <li><a href="{{ \App\Filament\Pages\BantuanPengisian::urlFor($k) }}">{{ \App\Support\Bantuan::registry()[$k][0] }}</a> · {{ $why }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif
    </div>
</div>
</x-filament-panels::page>
