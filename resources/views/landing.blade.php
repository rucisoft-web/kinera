@php
    $img = fn (string $n) => asset('images/kenali/'.$n.'.jpg').'?v=3';
    $guest = fn (array $q = []) => route('arah.guest', $q);
    $year = collect($cases)->pluck('year')->filter()->max() ?? now()->year;
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kinera · Kontrak Kinerja Pemimpin BLU yang terpantau setiap triwulan</title>
    <meta name="description" content="Kinera: Balanced Scorecard dan Kontrak Kinerja Pemimpin BLU sesuai PER-6/PB/2025 untuk satker Badan Layanan Umum. Coba data demo tanpa akun.">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v=1">
</head>
<body>
<header class="l-top">
    <div class="l-wrap l-top-in">
        <a href="{{ url('/') }}" class="l-brand"><picture><source srcset="{{ asset('images/kinera-logo-dark.svg') }}" media="(prefers-color-scheme: dark)"><img src="{{ asset('images/kinera-logo.svg') }}" alt="Kinera" class="l-logo"></picture></a>
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
                <span class="l-eyebrow">Untuk Pemimpin BLU, Pejabat Pengelola, dan Dewan Pengawas</span>
                <h1>Kontrak Kinerja BLU yang terpantau, terbukti, dan tepat waktu.</h1>
                <p class="l-lead">Kinera memadukan <b>Balanced Scorecard</b> dengan <b>Kontrak Kinerja Pemimpin BLU</b> menurut PER-6/PB/2025: Indikator Kinerja aspek layanan & keuangan, target trajektori triwulanan, Definisi Operasional, laporan capaian, uji syarat insentif, dan versi perubahan kontrak, semua dalam satu tempat.</p>
                <div class="l-actions">
                    <a href="#data-contoh" class="l-btn l-btn-primary">Coba data demo {{ $year }}</a>
                    <a href="#panduan" class="l-btn">Baca panduannya</a>
                </div>
                <p class="l-note">Tanpa akun. Mode Tamu hanya melihat, data contoh tidak bisa diubah. Data demo hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak.</p>
            </div>
            <figure class="l-shot">
                <span class="l-tag">Data demo</span>
                <img src="{{ $img('pusat-kendali') }}" alt="Pusat Kendali Kinera dengan data contoh" loading="eager">
            </figure>
        </div>
    </section>

    {{-- ===== Kenapa perlu ===== --}}
    <section class="l-section" id="kenapa">
        <div class="l-wrap">
            <span class="l-eyebrow">Kenapa perlu Kinera</span>
            <h2>Kontrak Kinerja ditandatangani setahun sekali, tetapi dinilai setiap triwulan.</h2>
            <p class="l-lead">PER-6/PB/2025 menuntut laporan capaian paling lambat 15 hari kerja setelah setiap periode trajektori, lengkap dengan hasil verifikasi internal, perhitungan, dan dokumen pendukung. Kinera menjawab empat hambatan klasik pelaksanaan strategi (Kaplan &amp; Norton) dalam bahasa BLU.</p>
            <div class="l-grid l-grid-4">
                @foreach ([
                    ['95%', 'pegawai tidak memahami strategi organisasinya', 'Hambatan visi', 'Peta Strategi satu halaman yang bisa dibaca semua orang, dari Dewan Pengawas sampai unit kerja.'],
                    ['75%', 'manajer tidak memiliki insentif yang terkait dengan strategi', 'Hambatan manusia', 'Setiap Indikator Kinerja punya penanggung jawab, target trajektori, dan capaian maksimum; syarat insentif Pasal 14 diuji otomatis.'],
                    ['60%', 'organisasi tidak mengaitkan anggaran dengan strategi', 'Hambatan sumber daya', 'Setiap inisiatif terhubung ke sasaran, lengkap dengan anggaran BLU dan serapannya.'],
                    ['85%', 'tim eksekutif membahas strategi kurang dari 1 jam per bulan', 'Hambatan manajemen', 'Pusat Kendali dan ritme rapat bulanan membuat strategi selalu masuk agenda.'],
                ] as [$num, $fact, $label, $answer])
                    <div class="l-card l-barrier">
                        <div class="l-num">{{ $num }}</div>
                        <p>{{ $fact }}</p>
                        <span class="l-chip">{{ $label }}</span>
                        <p class="l-answer"><b>Jawaban Kinera:</b> {{ $answer }}</p>
                    </div>
                @endforeach
            </div>
            <div class="l-grid l-grid-3 l-gap-top">
                @foreach ([
                    ['Satu sumber kebenaran', 'Realisasi diinput unit, diverifikasi Keuangan/SPI, disetujui Pemimpin BLU. Dokumen pendukung dan Definisi Operasional tersimpan bersama angkanya.'],
                    ['Peringatan dini', 'Indikator dengan capaian di bawah 65%, target PNBP yang belum tercapai, dan tenggat laporan triwulan muncul sebelum terlambat.'],
                    ['Siap DJPb & Pembina Teknis', 'Cetak Kontrak Kinerja (Lampiran I), Berita Acara (Lampiran II), perhitungan capaian (Lampiran VI), dan surat pernyataan (Lampiran IV).'],
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
                    ['3', 'Peta Strategi & Kontrak', 'Sasaran di empat perspektif, Indikator Kinerja aspek layanan & keuangan, trajektori, Definisi Operasional, dan Kontrak Kinerja.'],
                    ['4', 'Pelaksanaan', 'Inisiatif, tonggak, aktivitas, anggaran dan serapan, serta realisasi per trajektori.'],
                    ['5', 'Capaian & Insentif', 'Laporan capaian triwulanan, uji syarat insentif, perubahan Kontrak Kinerja, temuan, dan tindakan korektif.'],
                ] as [$n, $t, $d])
                    <div class="l-card l-phase"><span class="l-phase-n">{{ $n }}</span><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
            <div class="l-shots">
                @foreach ([['peta-strategi', 'Peta Strategi'], ['portofolio', 'Portofolio inisiatif'], ['laporan', 'Laporan untuk pimpinan']] as [$f, $cap])
                    <figure class="l-shot l-shot-sm">
                        <span class="l-tag">Data demo</span>
                        <img src="{{ $img($f) }}" alt="{{ $cap }} di Kinera" loading="lazy">
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
            <p class="l-lead">Masuk sebagai Tamu pada perioda {{ $year }} (proyeksi). Contoh juga berisi perioda tahun sebelumnya yang sudah ditutup, lengkap dengan Kontrak Kinerja, adendum, dan perhitungan capaiannya.</p>
            <div class="l-demo-warn" role="note"><span class="l-tag l-tag-inline">Data demo</span><span>Hanya data demo untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada Laporan Keuangan BLU Audited TA 2025, Perjanjian Kinerja 2025, dan RSB 2020–2024 UIN Sunan Kalijaga; rincian triwulanan, Kontrak Kinerja, realisasi indikator layanan, dan seluruh data {{ $year }} (proyeksi) adalah simulasi. Bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan.</span></div>
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
                            <span class="l-stats">{{ $c['objectives'] }} sasaran · {{ $c['kpis'] }} Indikator Kinerja · {{ $c['initiatives'] }} inisiatif</span>
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
            <span class="l-eyebrow">Panduan Kinera</span>
            <h2>Siapa mengerjakan apa, dan kapan.</h2>
            <p class="l-lead">Kinera berjalan dengan ritme yang sederhana: bulanan untuk pemantauan internal, triwulanan untuk laporan capaian Kontrak Kinerja. Setiap peran hanya melihat menu yang menjadi tugasnya.</p>
            <ol class="l-cycle">
                @foreach ([
                    [$cycle['reminder_day'] ?? 3, 'Pengingat', 'Sistem mengingatkan PIC yang belum mengisi realisasi.'],
                    [$cycle['input_day'] ?? 5, 'Input realisasi', 'Unit kerja mengisi realisasi Indikator Kinerja, dokumen pendukung, dan progres inisiatif.'],
                    [$cycle['verify_day'] ?? 7, 'Verifikasi', 'Keuangan/SPI memverifikasi angka (hasil verifikasi internal, Pasal 10).'],
                    [$cycle['approve_day'] ?? 8, 'Persetujuan', 'Pemimpin BLU menyetujui; capaian dan status terkunci.'],
                    [$cycle['meeting_day'] ?? 10, 'Rapat tinjauan', 'Bahas yang merah dan di bawah 65%, lalu kirim laporan capaian ke DJPb & Pembina Teknis.'],
                ] as [$day, $t, $d])
                    <li><span class="l-day">Tgl {{ $day }}</span><b>{{ $t }}</b><span>{{ $d }}</span></li>
                @endforeach
            </ol>
            <div class="l-grid l-grid-3 l-gap-top">
                @foreach ([
                    ['Dewan Pengawas', ['Buka Beranda dan Peta Strategi untuk gambaran satu halaman.', 'Pantau Capaian & Insentif setiap triwulan.', 'Pantau temuan penting sampai ditutup.']],
                    ['Pemimpin BLU', ['Pantau Pusat Kendali & Capaian: IK di bawah 65%, tenggat laporan.', 'Setujui realisasi yang sudah diverifikasi.', 'Tanda tangani Kontrak Kinerja, perubahan, dan surat pernyataan.']],
                    ['Perencanaan', ['Isi lingkungan strategis dan SWOT–TOWS.', 'Susun Indikator Kinerja, trajektori, dan Definisi Operasional.', 'Susun usulan Kontrak Kinerja dan perubahannya.']],
                    ['Keuangan & SPI', ['Verifikasi realisasi IK aspek keuangan (PNBP, EBITDA).', 'Catat anggaran dan serapan setiap inisiatif.', 'Kembalikan angka yang tidak sesuai beserta catatannya.']],
                    ['Unit Kerja & Fakultas', ['Lihat tugas di Beranda setiap awal bulan.', 'Input realisasi IK beserta dokumen pendukung.', 'Kerjakan tindakan korektif yang ditugaskan.']],
                    ['Admin', ['Buat satker BLU dan perioda.', 'Undang pengguna dan atur peran per satker.', 'Periksa jejak audit bila diperlukan.']],
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
                <a href="{{ $guest(['ke' => 'kenali']) }}" class="l-btn">Kenali Kinera</a>
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
                    <h2>Kenali Kinera dalam satu hari.</h2>
                    <p>Workshop untuk Pemimpin BLU, Pejabat Pengelola, Dewan Pengawas, dan tim perencanaan: dari Peta Strategi sampai Kontrak Kinerja dan laporan capaian, memakai studi kasus di atas.</p>
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
                ['Apakah data demo ini data resmi UIN Sunan Kalijaga?', 'Bukan. Data demo hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Sebagian angka 2025 mengacu pada LK BLU Audited 2025, Perjanjian Kinerja 2025, dan RSB 2020–2024; Kontrak Kinerja, rincian triwulanan, dan data 2026 adalah simulasi. Nama pejabat sengaja tidak dipakai.'],
                ['Apa yang bisa dilakukan sebagai Tamu?', 'Melihat semua menu, Kontrak Kinerja, dan laporan, serta berpindah perioda. Tamu tidak bisa mengubah atau menghapus data.'],
                ['Apakah cocok untuk BLU rumah sakit atau BLU lainnya?', 'Bisa. Aspek layanan, keuangan, dan kebijakan/penugasan berlaku untuk semua rumpun BLU. Satu instalasi menampung banyak satker dengan hak akses per satker dan per peran, termasuk Dewan Pengawas.'],
                ['Bagaimana memulai dengan data sendiri?', 'Admin membuat satker BLU dan perioda, lalu Perencanaan mengisi atau mengimpor sasaran, Indikator Kinerja, dan target dari Excel, kemudian menyusun Kontrak Kinerja. Ikuti Panduan Implementasi.'],
                ['Di mana Kinera dipasang?', 'Di server atau hosting milik satker sendiri (misalnya cPanel), sehingga data tetap berada di bawah kendali BLU.'],
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
        <p><b>Kinera</b> · Rucisoft</p>
        <p class="l-foot-warn"><b>DATA DEMO:</b> hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Sebagian angka 2025 mengacu pada dokumen publik UIN Sunan Kalijaga (LK BLU Audited 2025, Perjanjian Kinerja 2025, RSB 2020–2024); selebihnya simulasi dan proyeksi. Nama satker dipakai sebagai studi kasus, bukan kerja sama atau dukungan, dan bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan.</p>
    </div>
</footer>
</body>
</html>
