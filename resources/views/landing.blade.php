@php
    $img = fn (string $n) => asset('images/kenali/'.$n.'.jpg').'?v=1';
    $guest = fn (array $q = []) => route('arah.guest', $q);
    $year = collect($cases)->pluck('year')->filter()->max() ?? now()->year;
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ruci BSC · Strategi yang dijalankan setiap bulan</title>
    <meta name="description" content="Ruci BSC: perangkat manajemen strategi berbasis Balanced Scorecard untuk Dewan Komisaris, Direksi, dan pemilik usaha. Coba data demo tanpa akun.">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v=1">
</head>
<body>
<header class="l-top">
    <div class="l-wrap l-top-in">
        <a href="{{ url('/') }}" class="l-brand"><picture><source srcset="{{ asset('images/rucisoft-logo-dark.svg') }}" media="(prefers-color-scheme: dark)"><img src="{{ asset('images/rucisoft-logo.svg') }}" alt="Rucisoft BSC Professional" class="l-logo"></picture></a>
        <nav class="l-nav" aria-label="Navigasi utama">
            <a href="#kenapa">Kenapa perlu</a>
            <a href="#fitur">Fitur</a>
            <a href="#data-contoh">Data demo</a>
            <a href="#panduan">Panduan</a>
            <a href="#tanya">Tanya jawab</a>
        </nav>
        <a href="{{ url('/admin/login') }}" class="l-btn l-btn-sm">Masuk</a>
    </div>
</header>

<main>
    {{-- ===== Pembuka ===== --}}
    <section class="l-hero">
        <div class="l-wrap l-hero-in">
            <div>
                <span class="l-eyebrow">Untuk Dewan Komisaris, Direksi, dan Pemilik Usaha</span>
                <h1>Strategi yang dipahami semua orang, dijalankan setiap bulan.</h1>
                <p class="l-lead">Ruci BSC adalah perangkat manajemen strategi berbasis <b>Balanced Scorecard</b>. Dari analisis lingkungan dan SWOT–TOWS sampai Peta Strategi, KPI, target bulanan, inisiatif, anggaran, rapat tinjauan, dan tindak lanjutnya, semua dalam satu tempat.</p>
                <div class="l-actions">
                    <a href="#data-contoh" class="l-btn l-btn-primary">Coba data demo {{ $year }}</a>
                    <a href="#panduan" class="l-btn">Baca panduannya</a>
                </div>
                <p class="l-note">Tanpa akun. Mode Tamu hanya melihat, data contoh tidak bisa diubah.</p>
            </div>
            <figure class="l-shot">
                <span class="l-tag">Data demo</span>
                <img src="{{ $img('pusat-kendali') }}" alt="Pusat Kendali Ruci BSC dengan data contoh" loading="eager">
            </figure>
        </div>
    </section>

    {{-- ===== Kenapa perlu ===== --}}
    <section class="l-section" id="kenapa">
        <div class="l-wrap">
            <span class="l-eyebrow">Kenapa perlu Ruci BSC</span>
            <h2>Strategi yang baik sering berhenti di dokumen rencana.</h2>
            <p class="l-lead">Kaplan &amp; Norton, perancang Balanced Scorecard, menemukan empat hambatan klasik pelaksanaan strategi. Ruci BSC dirancang untuk menjawab keempatnya.</p>
            <div class="l-grid l-grid-4">
                @foreach ([
                    ['95%', 'karyawan tidak memahami strategi perusahaannya', 'Hambatan visi', 'Peta Strategi satu halaman yang bisa dibaca semua orang, dari Komisaris sampai kepala unit.'],
                    ['75%', 'manajer tidak memiliki insentif yang terkait dengan strategi', 'Hambatan manusia', 'Setiap KPI punya pemilik, target bulanan, dan skor yang terlihat jelas.'],
                    ['60%', 'organisasi tidak mengaitkan anggaran dengan strategi', 'Hambatan sumber daya', 'Setiap inisiatif terhubung ke sasaran, lengkap dengan anggaran dan serapannya.'],
                    ['85%', 'tim eksekutif membahas strategi kurang dari 1 jam per bulan', 'Hambatan manajemen', 'Pusat Kendali dan ritme rapat bulanan membuat strategi selalu masuk agenda.'],
                ] as [$num, $fact, $label, $answer])
                    <div class="l-card l-barrier">
                        <div class="l-num">{{ $num }}</div>
                        <p>{{ $fact }}</p>
                        <span class="l-chip">{{ $label }}</span>
                        <p class="l-answer"><b>Jawaban Ruci BSC:</b> {{ $answer }}</p>
                    </div>
                @endforeach
            </div>
            <div class="l-grid l-grid-3 l-gap-top">
                @foreach ([
                    ['Satu sumber kebenaran', 'Angka realisasi diinput unit, diverifikasi Keuangan, disetujui Direksi. Tidak ada lagi rekap Excel yang berbeda-beda versi.'],
                    ['Peringatan dini', 'KPI kritis yang merah, data yang terlambat, dan temuan yang belum ditindaklanjuti muncul di Pusat Kendali sebelum rapat.'],
                    ['Siap tata kelola', 'Jejak audit setiap perubahan, hak akses per peran, dan laporan untuk Komisaris yang bisa diekspor kapan saja.'],
                ] as [$t, $d])
                    <div class="l-card"><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Fitur ===== --}}
    <section class="l-section l-alt" id="fitur">
        <div class="l-wrap">
            <span class="l-eyebrow">Kerangka lengkap, bukan sekadar dasbor</span>
            <h2>Lima fase dari analisis sampai pembelajaran.</h2>
            <div class="l-grid l-grid-5">
                @foreach ([
                    ['1', 'Analisis', 'Lingkungan strategis: PESTEL, lima kekuatan industri, pasar, pesaing, regulasi, dan pemangku kepentingan.'],
                    ['2', 'Perumusan', 'SWOT–TOWS, isu strategis, visi–misi–nilai, dan pilihan strategi.'],
                    ['3', 'Peta Strategi', 'Sasaran di empat perspektif dengan hubungan sebab-akibat, KPI, dan target bulanan.'],
                    ['4', 'Pelaksanaan', 'Inisiatif, tonggak, aktivitas, anggaran dan serapan, serta input realisasi bulanan.'],
                    ['5', 'Tinjauan', 'Rapat tinjauan, temuan, tindakan korektif, penilaian kematangan, dan pelembagaan.'],
                ] as [$n, $t, $d])
                    <div class="l-card l-phase"><span class="l-phase-n">{{ $n }}</span><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
            <div class="l-shots">
                @foreach ([['peta-strategi', 'Peta Strategi'], ['portofolio', 'Portofolio inisiatif'], ['laporan', 'Laporan untuk pimpinan']] as [$f, $cap])
                    <figure class="l-shot l-shot-sm">
                        <span class="l-tag">Data demo</span>
                        <img src="{{ $img($f) }}" alt="{{ $cap }} di Ruci BSC" loading="lazy">
                        <figcaption>{{ $cap }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Data demo tahun berjalan ===== --}}
    <section class="l-section" id="data-contoh">
        <div class="l-wrap">
            <span class="l-eyebrow">Coba sendiri, tanpa akun</span>
            <h2>Data demo tahun berjalan {{ $year }}</h2>
            <p class="l-lead">Pilih satu perusahaan untuk langsung masuk sebagai Tamu pada perioda {{ $year }}. Setiap contoh juga berisi perioda tahun sebelumnya yang sudah ditutup. Perusahaan lain bisa dipilih lewat kotak konteks di kiri atas setelah masuk.</p>
            <div class="l-demo-warn" role="note"><span class="l-tag l-tag-inline">Data demo</span><span>Seluruh data disusun untuk pembelajaran dan secara garis besar mengacu pada laporan tahunan masing-masing perusahaan. Target, rincian bulanan, data {{ $year }}, dan sebagian indikator operasional adalah simulasi. Bukan data resmi perusahaan dan tidak untuk keputusan investasi.</span></div>
            @if ($cases === [])
                <p class="l-card">Data contoh belum dipasang. Jalankan <code>php artisan db:seed --class=DemoSeeder</code> di server.</p>
            @else
                <div class="l-grid l-grid-3">
                    @foreach ($cases as $c)
                        <a class="l-card l-case" href="{{ $guest(['perusahaan' => $c['code']]) }}">
                            <span class="l-case-head"><span class="l-code">{{ $c['code'] }}</span><span class="l-year">{{ $c['year'] }} · berjalan</span></span>
                            <b>{{ $c['name'] }}</b>
                            <small>{{ $c['sector'] }}</small>
                            <p>{{ $c['lesson'] }}</p>
                            <span class="l-stats">{{ $c['objectives'] }} sasaran · {{ $c['kpis'] }} KPI · {{ $c['initiatives'] }} inisiatif</span>
                            <span class="l-go">Masuk sebagai Tamu →</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===== Panduan ===== --}}
    <section class="l-section l-alt" id="panduan">
        <div class="l-wrap">
            <span class="l-eyebrow">Panduan Ruci BSC</span>
            <h2>Siapa mengerjakan apa, dan kapan.</h2>
            <p class="l-lead">Ruci BSC berjalan dengan ritme bulanan yang sederhana. Setiap peran hanya melihat menu yang menjadi tugasnya.</p>
            <ol class="l-cycle">
                @foreach ([
                    [$cycle['reminder_day'] ?? 3, 'Pengingat', 'Sistem mengingatkan PIC yang belum mengisi realisasi.'],
                    [$cycle['input_day'] ?? 5, 'Input realisasi', 'Kepala unit & PIC mengisi angka KPI dan progres inisiatif.'],
                    [$cycle['verify_day'] ?? 7, 'Verifikasi', 'Keuangan memeriksa angka dan serapan anggaran.'],
                    [$cycle['approve_day'] ?? 8, 'Persetujuan', 'Direksi menyetujui; skor dan status terkunci.'],
                    [$cycle['meeting_day'] ?? 10, 'Rapat tinjauan', 'Bahas yang merah, catat temuan, tetapkan tindakan korektif.'],
                ] as [$day, $t, $d])
                    <li><span class="l-day">Tgl {{ $day }}</span><b>{{ $t }}</b><span>{{ $d }}</span></li>
                @endforeach
            </ol>
            <div class="l-grid l-grid-3 l-gap-top">
                @foreach ([
                    ['Dewan Komisaris', ['Buka Beranda dan Peta Strategi untuk gambaran satu halaman.', 'Baca Laporan bulanan dan catatan rapat tinjauan.', 'Pantau temuan penting sampai ditutup.']],
                    ['Direksi', ['Pantau Pusat Kendali: KPI kritis, data terlambat, inisiatif berisiko.', 'Setujui realisasi bulanan yang sudah diverifikasi.', 'Pimpin rapat tinjauan dan tetapkan tindakan korektif.']],
                    ['Tim Strategi', ['Isi lingkungan strategis dan SWOT–TOWS.', 'Susun sasaran, Peta Strategi, kamus KPI, dan target.', 'Impor data dari Excel bila sudah punya rencana kerja.']],
                    ['Keuangan', ['Verifikasi angka realisasi KPI keuangan.', 'Catat anggaran dan serapan setiap inisiatif.', 'Kembalikan angka yang tidak sesuai beserta catatannya.']],
                    ['Kepala Unit & PIC', ['Lihat tugas di Beranda setiap awal bulan.', 'Input realisasi KPI dan aktivitas inisiatif.', 'Kerjakan tindakan korektif yang ditugaskan.']],
                    ['Admin', ['Buat perusahaan dan perioda analisis.', 'Undang pengguna dan atur peran per perusahaan.', 'Periksa jejak audit bila diperlukan.']],
                ] as [$role, $steps])
                    <div class="l-card l-role">
                        <h3>{{ $role }}</h3>
                        <ol>@foreach ($steps as $s)<li>{{ $s }}</li>@endforeach</ol>
                    </div>
                @endforeach
            </div>
            <div class="l-actions l-gap-top">
                <a href="{{ $guest(['ke' => 'panduan']) }}" class="l-btn l-btn-primary">Buka Panduan Pengguna lengkap</a>
                <a href="{{ $guest(['ke' => 'implementasi']) }}" class="l-btn">Panduan Implementasi</a>
                <a href="{{ $guest(['ke' => 'kenali']) }}" class="l-btn">Kenali Ruci BSC</a>
            </div>
            <p class="l-note">Panduan lengkap dengan tangkapan layar terbuka di dalam aplikasi sebagai Tamu.</p>
        </div>
    </section>

    {{-- ===== Workshop ===== --}}
    <section class="l-section">
        <div class="l-wrap">
            <div class="l-cta">
                <div>
                    <span class="l-eyebrow">Workshop</span>
                    <h2>Kenali Ruci BSC dalam satu hari.</h2>
                    <p>Workshop untuk Komisaris, Direksi, dan tim strategi: dari Peta Strategi sampai rapat tinjauan, memakai studi kasus perusahaan di atas.</p>
                </div>
                <div class="l-actions">
                    @if ($workshopUrl)
                        <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="l-btn l-btn-primary">Daftar Workshop</a>
                    @endif
                    <a href="{{ $guest(['ke' => 'workshop']) }}" class="l-btn">Lihat materi workshop</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Tanya jawab ===== --}}
    <section class="l-section l-alt" id="tanya">
        <div class="l-wrap l-narrow">
            <h2>Tanya jawab</h2>
            @foreach ([
                ['Apakah data demo ini data resmi perusahaan?', 'Bukan. Data demo disusun untuk pembelajaran dan secara garis besar mengacu pada laporan tahunan perusahaan terkait. Target, rincian bulanan, dan data tahun berjalan adalah simulasi.'],
                ['Apa yang bisa dilakukan sebagai Tamu?', 'Melihat semua menu dan laporan, berpindah perusahaan dan perioda. Tamu tidak bisa mengubah atau menghapus data.'],
                ['Apakah bisa dipakai untuk BUMN dan grup usaha?', 'Bisa. Satu instalasi menampung banyak perusahaan dengan hak akses per perusahaan dan per peran, termasuk peran Dewan Komisaris.'],
                ['Bagaimana memulai dengan data sendiri?', 'Admin membuat perusahaan dan perioda, lalu Tim Strategi mengisi atau mengimpor sasaran, KPI, dan target dari Excel. Ikuti Panduan Implementasi.'],
                ['Di mana Ruci BSC dipasang?', 'Di server atau hosting milik Anda sendiri (misalnya cPanel), sehingga data tetap berada di bawah kendali perusahaan.'],
            ] as [$q, $a])
                <details class="l-faq"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
            @endforeach
        </div>
    </section>

    <section class="l-section l-final">
        <div class="l-wrap l-narrow">
            <h2>Lihat sendiri dalam lima menit.</h2>
            <div class="l-actions l-center">
                <a href="#data-contoh" class="l-btn l-btn-primary">Coba sebagai Tamu</a>
                <a href="{{ url('/admin/login') }}" class="l-btn">Masuk dengan akun</a>
            </div>
        </div>
    </section>
</main>

<footer class="l-foot">
    <div class="l-wrap">
        <p><b>Ruci BSC</b> · Rucisoft</p>
        <p class="l-foot-warn"><b>DATA DEMO:</b> seluruh data perusahaan contoh di situs ini disusun untuk pembelajaran dan secara garis besar mengacu pada laporan tahunan masing-masing perusahaan; selebihnya simulasi. Nama perusahaan dipakai sebagai studi kasus, bukan kerja sama atau dukungan.</p>
    </div>
</footer>
</body>
</html>
