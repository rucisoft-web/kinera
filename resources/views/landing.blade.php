@php
    $img = fn (string $n) => asset('images/kenali/'.$n.'.jpg').'?v=3';
    $guide = fn (string $n) => asset('images/panduan/'.$n.'.jpg').'?v=3';
    $guest = fn (array $q = []) => route('arah.guest', $q);
    $year = collect($cases)->pluck('year')->filter()->max() ?? now()->year;
    $wa = fn (string $text) => 'https://wa.me/'.$whatsapp.'?text='.rawurlencode($text);
    $brosur = asset('files/brosur-kinera-workshop-2-hari.pdf');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kinera · Pengukuran Kinerja Instansi Pemerintah</title>
    <meta name="description" content="Kinera: pengukuran kinerja berbasis Balanced Scorecard untuk instansi pemerintah dan perguruan tinggi negeri: Renstra, Perjanjian Kinerja, IKU, SAKIP/LKjIP, sampai Kontrak Kinerja BLU. Coba data demo tanpa akun.">
    <meta property="og:title" content="Kinera · Pengukuran Kinerja Instansi Pemerintah">
    <meta property="og:description" content="Dari Renstra dan Perjanjian Kinerja sampai laporan capaian, dalam satu sistem.">
    <meta property="og:image" content="{{ $img('pusat-kendali') }}">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v=2">
</head>
<body>
<header class="l-top">
    <div class="l-wrap l-top-in">
        <a href="{{ url('/') }}" class="l-brand"><picture><source srcset="{{ asset('images/kinera-logo-dark.svg') }}" media="(prefers-color-scheme: dark)"><img src="{{ asset('images/kinera-logo.svg') }}" alt="Kinera" class="l-logo"></picture></a>
        <nav class="l-nav" aria-label="Navigasi utama">
            <a href="#masalah">Kenapa perlu</a>
            <a href="#solusi">Fitur</a>
            <a href="#data-contoh">Data demo</a>
            <a href="#workshop">Workshop</a>
            <a href="#panduan">Panduan</a>
            <a href="#tanya">Tanya jawab</a>
            <a href="https://rucisoft.id" target="_blank" rel="noopener">Rucisoft.id</a>
        </nav>
        <a href="{{ url('/admin/login') }}" class="l-btn l-btn-sm">Masuk</a>
    </div>
</header>

<main>
    {{-- ===== Pembuka ===== --}}
    <section class="l-hero">
        <div class="l-wrap l-hero-in">
            <div>
                <span class="l-eyebrow">Untuk pimpinan instansi pemerintah dan perguruan tinggi negeri</span>
                <h1>Kinerja instansi yang terukur, terbukti, dan tepat waktu.</h1>
                <p class="l-lead">Kinera memakai <b>Balanced Scorecard</b> untuk mengukur kinerja kementerian/lembaga, pemerintah daerah, dan perguruan tinggi negeri: Peta Strategi, Indikator Kinerja Utama, Perjanjian Kinerja, target triwulanan, Definisi Operasional, realisasi berbukti, dan laporan capaian untuk SAKIP/LKjIP. Bagi instansi berpola BLU tersedia modul <b>Kontrak Kinerja Pemimpin BLU</b> (PER-6/PB/2025).</p>
                <div class="l-actions">
                    <a href="#data-contoh" class="l-btn l-btn-primary">Coba data demo {{ $year }}</a>
                    <a href="#solusi" class="l-btn">Lihat fiturnya</a>
                </div>
                <p class="l-note">Tanpa akun. Mode Tamu hanya melihat, data contoh tidak bisa diubah. Data demo hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak.</p>
            </div>
            <figure class="l-shot">
                <span class="l-tag">Data demo</span>
                <img src="{{ $img('pusat-kendali') }}" alt="Pusat Kendali Kinera dengan data contoh" loading="eager">
            </figure>
        </div>
    </section>

    {{-- ===== Mitra ===== --}}
    <section class="l-mitra" aria-label="Mitra program">
        <div class="l-wrap l-mitra-in">
            <img src="{{ asset('images/mitra/uin-sunan-kalijaga.png') }}" alt="Logo UIN Sunan Kalijaga Yogyakarta" class="l-mitra-logo">
            <div>
                <span class="l-mitra-label">Bekerja sama dengan</span>
                <b class="l-mitra-name">Pusat Studi Manajemen Perguruan Tinggi</b>
                <span class="l-mitra-sub">UIN Sunan Kalijaga Yogyakarta · workshop dan pendampingan implementasi</span>
            </div>
            <a href="#workshop" class="l-btn l-mitra-btn">Workshop 2 hari →</a>
        </div>
    </section>

    {{-- ===== 1. Masalah ===== --}}
    <section class="l-section" id="masalah">
        <div class="l-wrap">
            <span class="l-step-head"><span class="l-step-no">1</span>Masalah yang sering dihadapi</span>
            <h2>Rencana strategis disahkan, lalu jarang dibuka lagi.</h2>
            <p class="l-lead">Instansi pemerintah dituntut menunjukkan hasil, bukan sekadar serapan anggaran. Gejala berikut mudah dikenali di banyak kementerian, pemerintah daerah, dan kampus negeri.</p>
            <div class="l-grid l-grid-3">
                @foreach ([
                    ['Renstra, Renja, dan kegiatan unit berjalan sendiri-sendiri', 'Target di dokumen perencanaan tidak terhubung dengan program unit kerja, sehingga kegiatan sibuk tetapi sasaran tidak bergerak.'],
                    ['Capaian Perjanjian Kinerja baru diketahui menjelang LKjIP', 'Perjanjian Kinerja ditandatangani awal tahun, lalu jarang dibuka lagi. Saat masalah terlihat, waktu memperbaikinya sudah sempit.'],
                    ['Data tersebar di banyak unit dan banyak versi', 'Setiap bidang memegang spreadsheet sendiri. Rekap triwulanan menyita waktu dan angkanya kerap berbeda antara laporan untuk pimpinan, kementerian, dan evaluator.'],
                    ['Anggaran tidak terbaca terhadap hasil', 'Serapan anggaran dilaporkan rapi, tetapi tidak terlihat sasaran mana yang dibiayai dan hasil apa yang diperoleh.'],
                    ['Target terlalu rendah atau definisinya kabur', 'Capaian ratusan persen atau angka yang tidak bisa diuji membuat evaluasi SAKIP kehilangan makna.'],
                    ['Rekomendasi pengawas tidak tuntas ditindaklanjuti', 'Temuan APIP, auditor, dan evaluator tercatat di notulen, tetapi tidak ada yang memantau penyelesaiannya.'],
                ] as [$t, $d])
                    <div class="l-card l-problem"><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== 2. Penyebab ===== --}}
    <section class="l-section l-alt" id="kenapa">
        <div class="l-wrap">
            <span class="l-step-head"><span class="l-step-no">2</span>Mengapa masalah ini terjadi</span>
            <h2>Akar masalahnya ada pada cara strategi diterjemahkan dan dipantau.</h2>
            <p class="l-lead">Kaplan &amp; Norton, perancang Balanced Scorecard, menyebutnya empat hambatan klasik pelaksanaan strategi. Di instansi pemerintah, keempatnya tampak sebagai berikut.</p>
            <div class="l-grid l-grid-4">
                @foreach ([
                    ['95%', 'pegawai tidak memahami strategi organisasinya', 'Hambatan visi', 'Sasaran strategis belum diturunkan menjadi indikator, pemilik, dan target triwulanan di setiap unit.'],
                    ['75%', 'manajer tidak memiliki insentif yang terkait dengan strategi', 'Hambatan manusia', 'Tidak ada definisi operasional baku: rumus, sumber data, dan cara uji tiap indikator berbeda antarunit.'],
                    ['60%', 'organisasi tidak mengaitkan anggaran dengan strategi', 'Hambatan sumber daya', 'Program dan anggaran disusun terpisah dari sasaran, sehingga serapan tidak bisa dibaca terhadap capaian.'],
                    ['85%', 'tim eksekutif membahas strategi kurang dari 1 jam per bulan', 'Hambatan manajemen', 'Siklus pelaporan tanpa penanggung jawab dan tenggat, dan laporan disusun ulang untuk setiap pihak.'],
                ] as [$num, $fact, $label, $cause])
                    <div class="l-card l-barrier">
                        <div class="l-num">{{ $num }}</div>
                        <p>{{ $fact }}</p>
                        <span class="l-chip">{{ $label }}</span>
                        <p class="l-answer">{{ $cause }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== 3. Solusi bergambar ===== --}}
    <section class="l-section" id="solusi">
        <div class="l-wrap">
            <span class="l-step-head"><span class="l-step-no">3</span>Solusi Kinera</span>
            <h2>Satu sistem dari strategi hingga laporan kinerja.</h2>
            <p class="l-lead">Setiap penyebab di atas dijawab dengan fitur yang dipakai sehari-hari oleh unit kerja, perencanaan, keuangan, APIP, dan pimpinan. Semua gambar di bawah diambil dari aplikasi dengan data demo.</p>

            @foreach ([
                ['peta-strategi', 'Hambatan visi', 'Peta Strategi satu halaman', 'Empat perspektif sektor publik (keuangan, pemangku kepentingan, proses layanan internal, pembelajaran) dengan hubungan sebab-akibat yang mudah dibaca dari pimpinan sampai unit kerja.', ['Sasaran strategis berwarna sesuai skor terkini', 'Tema strategis dan inisiatif terhubung ke sasaran', 'Hubungan sebab-akibat antarsasaran terlihat jelas']],
                ['kontrak-kinerja', 'Perjanjian & Kontrak Kinerja', 'Indikator dengan Definisi Operasional yang baku', 'Setiap Indikator Kinerja punya rumus, sumber data, cara uji, dokumen pendukung, bobot, dan trajektori triwulanan. Instansi BLU dapat menyusun Kontrak Kinerja Pemimpin BLU sesuai PER-6/PB/2025 dari indikator yang sama.', ['Target dan perubahannya tercatat berurutan', 'Capaian dibatasi sesuai capaian maksimum', 'Modul BLU: Kontrak, Berita Acara, lampiran, dan surat pernyataan']],
                ['capaian-insentif', 'Hambatan manusia', 'Capaian terpantau sejak triwulan pertama', 'Capaian setiap sasaran dan perspektif dihitung otomatis. Indikator yang tertinggal dan tenggat laporan yang mendekat ditandai sejak dini, bukan menjelang LKjIP.', ['Status hijau, kuning, merah per indikator', 'Pengingat tenggat laporan', 'Modul BLU: uji syarat insentif Pasal 14 PER-6/PB/2025']],
                ['alur-kerja', 'Hambatan manajemen', 'Satu alur realisasi untuk semua unit', 'Unit kerja mengisi realisasi beserta dokumen pendukung, perencanaan dan APIP memverifikasi, pimpinan menyetujui. Pengingat terkirim otomatis sesuai jadwal.', ['Riwayat persetujuan dan jejak audit', 'Realisasi yang dikembalikan disertai catatan', 'Impor data awal dari Excel']],
                ['portofolio', 'Hambatan sumber daya', 'Inisiatif dan anggaran terhubung ke sasaran', 'Setiap program strategis punya sasaran, penanggung jawab, milestone, anggaran, dan serapan. Pimpinan melihat mana yang terlambat dan mana yang boros.', ['Milestone dan aktivitas per inisiatif', 'Anggaran dan serapan per sumber dana', 'Temuan dan tindakan korektif sampai ditutup']],
                ['laporan', 'Satu sumber data', 'Laporan untuk setiap pihak dari data yang sama', 'Laporan untuk pimpinan, kementerian, evaluator SAKIP, dan pengawas dicetak dari angka yang sudah diverifikasi, tanpa menyusun ulang.', ['Laporan capaian triwulanan', 'Tinjauan kinerja dan notulen rapat', 'Unduh Excel atau cetak langsung']],
            ] as $i => [$file, $tag, $title, $text, $points])
                <div class="l-feature {{ $i % 2 ? 'l-feature-rev' : '' }}">
                    <figure class="l-shot">
                        <span class="l-tag">Data demo</span>
                        <img src="{{ $img($file) }}" alt="{{ $title }} di Kinera" loading="lazy">
                    </figure>
                    <div class="l-feature-text">
                        <span class="l-chip l-chip-blue">{{ $tag }}</span>
                        <h3>{{ $title }}</h3>
                        <p>{{ $text }}</p>
                        <ul class="l-ticks">@foreach ($points as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                    </div>
                </div>
            @endforeach

            <div class="l-mobile">
                <div class="l-feature-text">
                    <span class="l-chip l-chip-blue">Untuk pimpinan</span>
                    <h3>Pusat Kendali di layar ponsel</h3>
                    <p>Skor strategis, status Indikator Kinerja, serapan anggaran, inisiatif terlambat, dan temuan terbuka dalam satu layar. Pimpinan bisa memantau dari mana saja tanpa menunggu bahan rapat.</p>
                    <ul class="l-ticks"><li>Tampilan menyesuaikan ponsel dan tablet</li><li>Hak akses per peran dan per instansi</li><li>Autentikasi dua faktor untuk pimpinan</li></ul>
                </div>
                <figure class="l-phone">
                    <img src="{{ $img('hp-pusat-kendali') }}" alt="Pusat Kendali Kinera di layar ponsel" loading="lazy">
                </figure>
            </div>
        </div>
    </section>

    {{-- ===== 4. Cara memperoleh ===== --}}
    <section class="l-section l-alt" id="mulai">
        <div class="l-wrap">
            <span class="l-step-head"><span class="l-step-no">4</span>Cara memperoleh Kinera</span>
            <h2>Mulai dari tahap mana pun yang sesuai dengan kesiapan instansi Anda.</h2>
            <p class="l-lead">Rucisoft tidak meminta Anda langsung membeli. Pahami dulu masalahnya, coba data demonya, ikuti workshop, buktikan lewat pilot, lalu putuskan dengan proposal yang jelas.</p>
            <div class="l-grid l-grid-6">
                @foreach ([
                    ['01', 'Pahami', 'Baca penjelasan Kinera untuk pimpinan, lengkap dengan diagram dan contoh.', $guest(['ke' => 'kenali']), 'Kenali Kinera'],
                    ['02', 'Coba', 'Jelajahi data demo perguruan tinggi negeri sebagai Tamu, tanpa akun.', '#data-contoh', 'Coba data demo'],
                    ['03', 'Workshop', 'Strategic Leadership Lab, dua hari bersama PSMPT UIN Sunan Kalijaga: dari Peta Strategi sampai laporan kinerja.', '#workshop', 'Lihat rundown'],
                    ['04', 'Pastikan', 'Demo 30–45 menit bersama tim pimpinan untuk memetakan kebutuhan instansi.', $wa('Halo Rucisoft, kami ingin menjadwalkan demo Kinera.'), 'Jadwalkan demo'],
                    ['05', 'Buktikan', 'Pilot terbatas di satu atau dua unit dengan ukuran keberhasilan yang disepakati.', $wa('Halo Rucisoft, kami ingin membahas pilot Kinera.'), 'Ajukan pilot'],
                    ['06', 'Terapkan', 'Proposal transparan: implementasi, pelatihan per peran, pendampingan, dan pemasangan di server instansi.', 'mailto:'.$email.'?subject='.rawurlencode('Proposal Kinera'), 'Minta proposal'],
                ] as [$n, $t, $d, $href, $cta])
                    <a class="l-card l-way" href="{{ $href }}" @if (str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif>
                        <span class="l-way-n">{{ $n }}</span>
                        <h3>{{ $t }}</h3>
                        <p>{{ $d }}</p>
                        <span class="l-go">{{ $cta }} →</span>
                    </a>
                @endforeach
            </div>
            <div class="l-actions l-gap-top">
                <a href="{{ $wa('Halo Rucisoft, saya tertarik dengan Kinera.') }}" target="_blank" rel="noopener" class="l-btn l-btn-primary">Tanya via WhatsApp</a>
                <a href="{{ $brosur }}" class="l-btn" download>Unduh brosur (PDF)</a>
                <a href="mailto:{{ $email }}" class="l-btn">{{ $email }}</a>
            </div>
        </div>
    </section>

    {{-- ===== Lima fase ===== --}}
    <section class="l-section" id="fitur">
        <div class="l-wrap">
            <span class="l-eyebrow">Kerangka lengkap, bukan sekadar dasbor</span>
            <h2>Lima fase dari analisis sampai pembelajaran.</h2>
            <div class="l-grid l-grid-5">
                @foreach ([
                    ['1', 'Analisis', 'Lingkungan strategis: PESTEL, pasar, pesaing, regulasi, dan pemangku kepentingan.'],
                    ['2', 'Perumusan', 'SWOT–TOWS, isu strategis, visi–misi–nilai, dan pilihan strategi.'],
                    ['3', 'Peta Strategi & Indikator', 'Sasaran di empat perspektif, Indikator Kinerja Utama, trajektori, Definisi Operasional, dan Perjanjian Kinerja.'],
                    ['4', 'Pelaksanaan', 'Inisiatif, tonggak, aktivitas, anggaran dan serapan, serta realisasi per trajektori.'],
                    ['5', 'Capaian & Perbaikan', 'Laporan capaian triwulanan, bahan LKjIP, perubahan target, temuan, dan tindakan korektif.'],
                ] as [$n, $t, $d])
                    <div class="l-card l-phase"><span class="l-phase-n">{{ $n }}</span><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Data demo tahun berjalan ===== --}}
    <section class="l-section l-alt" id="data-contoh">
        <div class="l-wrap">
            <span class="l-eyebrow">Coba sendiri, tanpa akun</span>
            <h2>Data demo tahun berjalan {{ $year }}</h2>
            <p class="l-lead">Masuk sebagai Tamu pada perioda {{ $year }} (proyeksi). Contoh juga berisi perioda tahun sebelumnya yang sudah ditutup, lengkap dengan capaian indikator. Kampus contoh UIN Sunan Kalijaga Yogyakarta disusun dari Renstra 2025–2029 dan berpola BLU, sehingga peta strategi, indikator, inisiatif, Kontrak Kinerja beserta adendumnya, hingga draf Renstra dan Rencana Kerja ikut tampil.</p>
            <div class="l-demo-warn" role="note"><span class="l-tag l-tag-inline">Data demo</span><span>Hanya data demo untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada dokumen publik masing-masing kampus (laporan keuangan, Perjanjian Kinerja, LKjIP/Laporan Kinerja, dan Renstra); rincian triwulanan, Kontrak Kinerja, sebagian realisasi indikator, dan seluruh data {{ $year }} (proyeksi) adalah simulasi. Bukan data resmi kampus tersebut maupun kementerian terkait.</span></div>
            @if ($cases === [])
                <p class="l-card">Data contoh belum dipasang. Jalankan <code>php artisan kinera:demo</code> di server.</p>
            @else
                <div class="l-demo">
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
                    <figure class="l-shot l-shot-sm">
                        <img src="{{ $img('beranda') }}" alt="Beranda Kinera untuk pimpinan dengan data demo" loading="lazy">
                        <figcaption>Beranda pimpinan: tugas, skor, dan yang perlu disetujui bulan ini.</figcaption>
                    </figure>
                </div>
            @endif
        </div>
    </section>

    {{-- ===== Panduan ===== --}}
    <section class="l-section" id="panduan">
        <div class="l-wrap">
            <span class="l-eyebrow">Panduan Kinera</span>
            <h2>Siapa mengerjakan apa, dan kapan.</h2>
            <p class="l-lead">Kinera berjalan dengan ritme yang sederhana: bulanan untuk pemantauan internal, triwulanan untuk laporan capaian Perjanjian Kinerja (dan Kontrak Kinerja bagi BLU). Setiap peran hanya melihat menu yang menjadi tugasnya.</p>
            <ol class="l-cycle">
                @foreach ([
                    [$cycle['reminder_day'] ?? 3, 'Pengingat', 'Sistem mengingatkan PIC yang belum mengisi realisasi.'],
                    [$cycle['input_day'] ?? 5, 'Input realisasi', 'Unit kerja mengisi realisasi Indikator Kinerja, dokumen pendukung, dan progres inisiatif.'],
                    [$cycle['verify_day'] ?? 7, 'Verifikasi', 'Perencanaan, keuangan, atau APIP memverifikasi angka beserta buktinya.'],
                    [$cycle['approve_day'] ?? 8, 'Persetujuan', 'Pimpinan menyetujui; capaian dan status terkunci.'],
                    [$cycle['meeting_day'] ?? 10, 'Rapat tinjauan', 'Bahas indikator merah dan tindak lanjutnya, lalu kirim laporan capaian ke pihak yang berwenang.'],
                ] as [$day, $t, $d])
                    <li><span class="l-day">Tgl {{ $day }}</span><b>{{ $t }}</b><span>{{ $d }}</span></li>
                @endforeach
            </ol>
            <div class="l-shots">
                @foreach ([['kepala-input', 'Unit kerja mengisi realisasi'], ['keu-verifikasi', 'Verifikator memeriksa angka'], ['dir-setujui', 'Pimpinan menyetujui']] as [$f, $cap])
                    <figure class="l-shot l-shot-sm">
                        <img src="{{ $guide($f) }}" alt="{{ $cap }} di Kinera" loading="lazy">
                        <figcaption>{{ $cap }}</figcaption>
                    </figure>
                @endforeach
            </div>
            <div class="l-grid l-grid-3 l-gap-top">
                @foreach ([
                    ['Pengawas (APIP / Dewan Pengawas)', ['Buka Beranda dan Peta Strategi untuk gambaran satu halaman.', 'Pantau capaian setiap triwulan.', 'Pantau temuan penting sampai ditutup.']],
                    ['Pimpinan instansi', ['Pantau Pusat Kendali: indikator merah dan tenggat laporan.', 'Setujui realisasi yang sudah diverifikasi.', 'Tetapkan Perjanjian Kinerja dan perubahan targetnya.']],
                    ['Perencanaan', ['Isi lingkungan strategis dan SWOT–TOWS.', 'Susun Indikator Kinerja, trajektori, dan Definisi Operasional.', 'Siapkan bahan Perjanjian Kinerja, LKjIP, dan (bagi BLU) Kontrak Kinerja.']],
                    ['Keuangan & APIP', ['Verifikasi realisasi indikator keuangan dan anggaran.', 'Catat anggaran dan serapan setiap inisiatif.', 'Kembalikan angka yang tidak sesuai beserta catatannya.']],
                    ['Unit Kerja', ['Lihat tugas di Beranda setiap awal bulan.', 'Input realisasi IK beserta dokumen pendukung.', 'Kerjakan tindakan korektif yang ditugaskan.']],
                    ['Admin', ['Buat instansi dan perioda.', 'Undang pengguna dan atur peran per instansi.', 'Periksa jejak audit bila diperlukan.']],
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

    {{-- ===== Workshop 2 hari ===== --}}
    <section class="l-section l-alt" id="workshop">
        <div class="l-wrap">
            <div class="l-cta">
                <div class="l-cta-head">
                    <div class="l-cta-badge"><img src="{{ asset('images/mitra/uin-sunan-kalijaga.png') }}" alt="Logo UIN Sunan Kalijaga Yogyakarta"></div>
                    <div>
                        <span class="l-eyebrow">Workshop 2 hari bersama PSMPT UIN Sunan Kalijaga</span>
                        <h2>Strategic Leadership Lab</h2>
                        <p class="l-cta-sub">Mengelola Renstra Perguruan Tinggi dengan Balanced Scorecard.</p>
                        <p>Untuk pimpinan instansi pemerintah dan perguruan tinggi negeri, tim perencanaan dan keuangan, serta APIP/SPI. 08.00–16.30 WIB.</p>
                    </div>
                </div>
                <div class="l-rundown">
                    @foreach ([
                        ['Hari 1', 'Balanced Scorecard dan Peta Strategi instansi', [['08.00', 'Registrasi peserta'], ['08.30', 'Pembukaan dan sambutan Ketua PSMPT UIN Sunan Kalijaga'], ['09.00', 'Balanced Scorecard untuk sektor publik: empat perspektif'], ['10.30', 'Anatomi scorecard: sasaran, ukuran, target, dan inisiatif'], ['13.00', 'Diagnosis kinerja dan penyusunan sasaran strategis'], ['15.00', 'Menyusun Peta Strategi dan Indikator Kinerja per kelompok']]],
                        ['Hari 2', 'Pengukuran kinerja dan implementasi dengan Kinera', [['08.00', 'Definisi operasional, target, dan trajektori triwulanan'], ['10.30', 'Simulasi penetapan dan perubahan target (termasuk Kontrak Kinerja BLU)'], ['13.00', 'Praktik aplikasi Kinera: alur realisasi, capaian, dan laporan'], ['14.30', 'Inisiatif strategis, temuan, dan tindak lanjut pengawas'], ['15.30', 'Rencana aksi 90 hari, penutupan, dan penyerahan sertifikat']]],
                    ] as [$day, $theme, $rows])
                        <div class="l-day-card">
                            <div class="l-day-title"><span>{{ $day }}</span>{{ $theme }}</div>
                            <ul>@foreach ($rows as [$t, $s])<li><b>{{ $t }}</b><span>{{ $s }}</span></li>@endforeach</ul>
                        </div>
                    @endforeach
                </div>
                <div class="l-actions">
                    <a href="{{ $wa('Halo Rucisoft, saya ingin mendaftar Workshop Kinera 2 hari.') }}" target="_blank" rel="noopener" class="l-btn l-btn-primary">Daftar via WhatsApp</a>
                    <a href="{{ $brosur }}" class="l-btn" download>Unduh brosur (PDF)</a>
                </div>
                <p class="l-cta-note">Hasil yang dibawa pulang: draf Peta Strategi, daftar indikator dan Definisi Operasional, scorecard pimpinan beserta targetnya (format Kontrak Kinerja bagi BLU), rencana aksi 90 hari, sertifikat, dan akses ke kinera.rucisoft.id selama 3 bulan. Hubungi: <b>Jarot Wahyudi</b> · HP <b>0812-2783-3795</b>.</p>
            </div>
        </div>
    </section>

    {{-- ===== Tanya jawab ===== --}}
    <section class="l-section" id="tanya">
        <div class="l-wrap l-narrow">
            <h2>Tanya jawab</h2>
            @foreach ([
                ['Apakah data demo ini data resmi kampus tersebut?', 'Bukan. Data demo hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Sebagian angka 2025 mengacu pada dokumen publik kampus (laporan keuangan, Perjanjian Kinerja, LKjIP, Renstra); Kontrak Kinerja, rincian triwulanan, dan data 2026 adalah simulasi. Nama pejabat sengaja tidak dipakai.'],
                ['Apa peran Pusat Studi Manajemen Perguruan Tinggi UIN Sunan Kalijaga?', 'PSMPT menjadi mitra Rucisoft dalam penyelenggaraan workshop dan pendampingan implementasi Balanced Scorecard di instansi pemerintah dan perguruan tinggi negeri.'],
                ['Apa yang bisa dilakukan sebagai Tamu?', 'Melihat semua menu, indikator, Kontrak Kinerja, dan laporan, serta berpindah perioda. Tamu tidak bisa mengubah atau menghapus data.'],
                ['Apakah hanya untuk perguruan tinggi atau BLU?', 'Tidak. Kinera untuk instansi pemerintah pada umumnya: kementerian/lembaga, pemerintah daerah dan OPD, perguruan tinggi negeri, serta rumah sakit pemerintah. Modul Kontrak Kinerja Pemimpin BLU dipakai hanya oleh instansi berpola BLU. Satu instalasi menampung banyak instansi dengan hak akses per instansi dan per peran.'],
                ['Bagaimana memulai dengan data sendiri?', 'Admin membuat instansi dan perioda, lalu Perencanaan mengisi atau mengimpor sasaran, Indikator Kinerja, dan target dari Excel, kemudian menyusun Perjanjian Kinerja (dan Kontrak Kinerja bagi BLU). Ikuti Panduan Implementasi.'],
                ['Di mana Kinera dipasang?', 'Di server atau hosting milik instansi sendiri (misalnya cPanel), sehingga data tetap berada di bawah kendali instansi.'],
            ] as [$q, $a])
                <details class="l-faq"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
            @endforeach
        </div>
    </section>

    <section class="l-section l-final l-alt">
        <div class="l-wrap l-narrow">
            <h2>Lihat sendiri dalam lima menit.</h2>
            <div class="l-actions l-center">
                <a href="#data-contoh" class="l-btn l-btn-primary">Coba sebagai Tamu</a>
                <a href="{{ $wa('Halo Rucisoft, saya tertarik dengan Kinera.') }}" target="_blank" rel="noopener" class="l-btn">Tanya via WhatsApp</a>
                <a href="{{ url('/admin/login') }}" class="l-btn">Masuk dengan akun</a>
            </div>
        </div>
    </section>
</main>

<footer class="l-foot">
    <div class="l-wrap">
        <img src="{{ asset('images/kinera-logo.svg') }}" alt="Kinera" class="l-foot-logo">
        <p><b>Kinera</b> · <a href="https://rucisoft.id" target="_blank" rel="noopener">Rucisoft</a> · bekerja sama dengan Pusat Studi Manajemen Perguruan Tinggi UIN Sunan Kalijaga</p>
        <p><a href="mailto:{{ $email }}">{{ $email }}</a> · <a href="{{ $wa('Halo Rucisoft, saya tertarik dengan Kinera.') }}" target="_blank" rel="noopener">WhatsApp {{ preg_replace('/^62(\d{3})(\d{4})(\d+)$/', '0$1-$2-$3', $whatsapp) }}</a></p>
        <p class="l-foot-warn"><b>DATA DEMO:</b> hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Sebagian angka 2025 mengacu pada dokumen publik kampus contoh (Renstra 2025–2029 dan Laporan Keuangan BLU 2025 UIN Sunan Kalijaga); selebihnya simulasi dan proyeksi, dan bukan data resmi kampus tersebut, Kementerian Agama, maupun Kementerian Keuangan.</p>
    </div>
</footer>
</body>
</html>
