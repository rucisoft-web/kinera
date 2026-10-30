@php
    use App\Filament\Pages\BantuanPengisian;
    use App\Support\Bantuan;
@endphp
<x-filament-panels::page>
<div class="arah modul" x-data="{ tab: (location.hash || '#pengantar').slice(1) }">
    <div class="arah-card bantuan-hero">
        <span class="kenali-eyebrow">Strategic Leadership Lab · Mengelola Renstra Perguruan Tinggi dengan Balanced Scorecard</span>
        <h2>Modul Ajar Balanced Scorecard</h2>
        <p>Modul ini mengikuti rundown workshop dua hari bersama PSMPT UIN Sunan Kalijaga. Setiap sesi berisi tujuan pembelajaran, konsep kunci, studi kasus dari data demo UIN Sunan Kalijaga, lembar kerja yang dikerjakan di akun latihan kelompok, pertanyaan diskusi, dan kuis singkat.</p>
        <div class="kenali-actions">
            <a href="{{ \App\Filament\Pages\WorkshopKinera::getUrl() }}" class="arah-btn">Rundown workshop</a>
            <a href="{{ BantuanPengisian::getUrl() }}" class="arah-btn">Bantuan pengisian</a>
            <button type="button" class="arah-btn" onclick="window.print()">Cetak / simpan PDF</button>
        </div>
    </div>

    <nav class="panduan-tabs modul-tabs" role="tablist">
        <button type="button" class="panduan-tab" :class="tab === 'pengantar' && 'is-active'" @click="tab = 'pengantar'; history.replaceState(null, '', '#pengantar')">Pengantar</button>
        @foreach ($sessions as $s)
            <button type="button" class="panduan-tab" :class="tab === 'sesi-{{ $s['no'] }}' && 'is-active'" @click="tab = 'sesi-{{ $s['no'] }}'; history.replaceState(null, '', '#sesi-{{ $s['no'] }}')">{{ is_numeric($s['no']) && $s['no'] < 9 ? 'Sesi '.$s['no'] : 'Penutup' }}</button>
        @endforeach
    </nav>

    <section class="modul-sesi" x-show="tab === 'pengantar'" x-cloak>
        <div class="bantuan-two">
            <div class="arah-card">
                <b>Cara memakai modul</b>
                <ul>
                    <li>Fasilitator membuka sesi sesuai rundown dan memaparkan konsep kunci.</li>
                    <li>Peserta membuka studi kasus di demo UIN Sunan Kalijaga (cukup sebagai Tamu).</li>
                    <li>Lembar kerja dikerjakan per kelompok di <b>akun latihan</b> (lat01, lat02, …) agar data demo utama tidak berubah.</li>
                    <li>Tutup sesi dengan diskusi dan kuis. Jawaban kuis langsung menampilkan pembahasan.</li>
                </ul>
            </div>
            <div class="arah-card">
                <b>Akun latihan kelompok</b>
                <ul>
                    <li>Admin menyiapkannya dengan <code>php artisan kinera:latihan 8 --password=…</code> (8 kelompok).</li>
                    <li>Setiap kelompok mendapat instansi sendiri (LAT01, LAT02, …) berisi salinan demo, dengan akun per peran: <code>lat01.perencanaan</code>, <code>lat01.rektor</code>, <code>lat01.wr2</code>, <code>lat01.ti</code>, <code>lat01.dewas</code>, dan lainnya, semua <code>@contoh.local</code>.</li>
                    <li>Tambahkan <code>--kosong</code> untuk instansi kosong bila kelompok ingin menyusun strategi dari nol.</li>
                    <li>Jalankan perintah yang sama untuk mereset, dan <code>--hapus</code> setelah workshop.</li>
                </ul>
            </div>
        </div>
        <div class="bantuan-table-wrap" style="margin-top:1rem">
            <table class="bantuan-table">
                <thead><tr><th>Sesi</th><th>Waktu</th><th>Judul</th><th>Bentuk</th></tr></thead>
                <tbody>@foreach ($sessions as $s)<tr><td>{{ $s['hari'] }} · {{ is_numeric($s['no']) && $s['no'] < 9 ? 'Sesi '.$s['no'] : 'Penutup' }}</td><td>{{ $s['waktu'] }}</td><td><b>{{ $s['judul'] }}</b></td><td>{{ $s['jenis'] }}</td></tr>@endforeach</tbody>
            </table>
        </div>
    </section>

    @foreach ($sessions as $s)
        <section class="modul-sesi" x-show="tab === 'sesi-{{ $s['no'] }}'" x-cloak>
            <div class="arah-card modul-head">
                <span class="ws-session-no">{{ is_numeric($s['no']) && $s['no'] < 9 ? $s['no'] : '★' }}</span>
                <div>
                    <small>{{ $s['hari'] }} · {{ $s['waktu'] }} · {{ $s['durasi'] }} · {{ $s['jenis'] }}</small>
                    <h3>{{ $s['judul'] }}</h3>
                </div>
            </div>

            <div class="bantuan-sec">
                <h3><span>1</span> Tujuan pembelajaran</h3>
                <p>Setelah sesi ini peserta mampu:</p>
                <ul class="modul-list">@foreach ($s['tujuan'] as $t)<li>{{ $t }}</li>@endforeach</ul>
            </div>

            <div class="bantuan-sec">
                <h3><span>2</span> Konsep kunci</h3>
                <div class="arah-grid arah-grid-2">
                    @foreach ($s['konsep'] as [$k, $v])<div class="arah-card modul-konsep"><b>{{ $k }}</b><span>{{ $v }}</span></div>@endforeach
                </div>
            </div>

            <div class="bantuan-sec">
                <h3><span>3</span> Studi kasus: demo UIN Sunan Kalijaga</h3>
                <p>{{ $s['kasus'] }}</p>
                <div class="ws-links">@foreach ($s['layar'] as [$label, $path])<a href="{{ url($path) }}" target="_blank" rel="noopener" class="arah-btn">{{ $label }} →</a>@endforeach</div>
            </div>

            <div class="bantuan-sec">
                <h3><span>4</span> Lembar kerja kelompok</h3>
                <ol class="bantuan-steps">@foreach ($s['lembar'] as $l)<li><span>{{ $l }}</span></li>@endforeach</ol>
                @php($help = collect($s['bantuan'])->filter(fn ($k) => Bantuan::exists($k)))
                @if ($help->isNotEmpty())
                    <p class="modul-help">Bantuan pengisian: @foreach ($help as $k)<a href="{{ BantuanPengisian::urlFor($k) }}" target="_blank" rel="noopener">{{ Bantuan::registry()[$k][0] }}</a>@if (! $loop->last), @endif @endforeach</p>
                @endif
            </div>

            <div class="bantuan-sec">
                <h3><span>5</span> Pertanyaan diskusi</h3>
                <ul class="modul-list">@foreach ($s['diskusi'] as $d)<li>{{ $d }}</li>@endforeach</ul>
            </div>

            <div class="bantuan-sec">
                <h3><span>6</span> Kuis</h3>
                <div class="modul-kuis">
                    @foreach ($s['kuis'] as $qi => [$q, $opts, $ans, $why])
                        <div class="arah-card modul-q" x-data="{ pick: null }">
                            <b>{{ $qi + 1 }}. {{ $q }}</b>
                            <div class="modul-opts">
                                @foreach ($opts as $oi => $o)
                                    <button type="button" @click="pick = {{ $oi }}" :disabled="pick !== null"
                                        :class="pick !== null && ({{ $oi }} === {{ $ans }} ? 'is-right' : (pick === {{ $oi }} ? 'is-wrong' : ''))">{{ chr(65 + $oi) }}. {{ $o }}</button>
                                @endforeach
                            </div>
                            <div class="modul-why" x-show="pick !== null" x-cloak>
                                <span x-text="pick === {{ $ans }} ? 'Benar.' : 'Kurang tepat. Jawaban: {{ chr(65 + $ans) }}.'"></span> {{ $why }}
                                <button type="button" class="modul-reset" @click="pick = null">Ulangi</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
</div>
</x-filament-panels::page>
