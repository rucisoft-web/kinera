@php
    $img = fn (string $n) => asset('images/kenali/'.$n.'.jpg').'?v=2';
    $p = $perspectives;

    // Diagram peta strategi: 4 lapis perspektif, panah sebab-akibat dari bawah ke atas
    $lanes = ['keuangan', 'pelanggan', 'proses', 'sdm'];
    $cy = fn (int $lane) => 10 + $lane * 100 + 46;
    $nodes = [
        'f1' => [0, 390, 'Pendapatan tumbuh'], 'f2' => [0, 690, 'Laba & arus kas sehat'],
        'c1' => [1, 390, 'Pelanggan puas & setia'], 'c2' => [1, 690, 'Pangsa pasar naik'],
        'p1' => [2, 290, 'Mutu layanan andal'], 'p2' => [2, 540, 'Inovasi produk/layanan'], 'p3' => [2, 790, 'Operasi efisien'],
        'l1' => [3, 290, 'Talenta kunci siap'], 'l2' => [3, 540, 'Sistem informasi terpadu'], 'l3' => [3, 790, 'Budaya kinerja'],
    ];
    $links = [['l1', 'p1'], ['l2', 'p2'], ['l2', 'p3'], ['l3', 'p3'], ['p1', 'c1'], ['p2', 'c2'], ['p3', 'f2'], ['c1', 'f1'], ['c2', 'f1']];

    // Diagram siklus bulanan: 5 simpul melingkar
    $R = 130; $cx0 = 210; $cy0 = 200;
    $pt = fn (float $deg, float $r) => [round($cx0 + $r * cos(deg2rad($deg)), 1), round($cy0 + $r * sin(deg2rad($deg)), 1)];
    $angles = [-90, -18, 54, 126, 198];
@endphp
<x-filament-panels::page>
<div class="arah kenali">

    {{-- ===== Pembuka ===== --}}
    <section class="kenali-hero">
        <div class="kenali-hero-text">
            <span class="kenali-eyebrow">Untuk Pemimpin BLU, Pejabat Pengelola, dan Dewan Pengawas</span>
            <h1>Strategi yang dipahami semua orang, dijalankan setiap bulan.</h1>
            <p>Ruci BSC adalah perangkat manajemen strategi berbasis <b>Balanced Scorecard</b>. Ruci BSC membantu pimpinan <b>mengomunikasikan strategi</b> ke seluruh organisasi dan <b>menerjemahkannya menjadi rencana aksi operasional</b>: sasaran, IK, target bulanan, inisiatif, anggaran, dan penanggung jawab yang dipantau dalam satu tempat.</p>
            <div class="kenali-actions">
                @if ($workshopUrl)
                    <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop Ruci BSC</a>
                @endif
                <a href="#lihat-langsung" class="arah-btn kenali-btn-lg">Lihat tampilannya</a>
            </div>
        </div>
        <a class="kenali-shot kenali-hero-shot" href="{{ $img('peta-strategi') }}" target="_blank" rel="noopener">
            <img src="{{ $img('peta-strategi') }}" alt="Peta Strategi UIN Sunan Kalijaga (data demo) di Ruci BSC BLU" loading="eager">
        </a>
    </section>

    {{-- ===== Masalah ===== --}}
    <section class="kenali-section">
        <h2>Mengapa strategi yang baik sering tidak sampai ke lapangan?</h2>
        <p class="kenali-lead">Kaplan &amp; Norton, perancang Balanced Scorecard, menemukan empat hambatan klasik pelaksanaan strategi. Ruci BSC dirancang untuk menjawab keempatnya.</p>
        <div class="arah-grid arah-grid-4">
            @foreach ([
                ['95%', 'pegawai tidak memahami strategi perusahaannya', 'Hambatan visi', 'Peta Strategi satu halaman yang bisa dibaca semua orang, dari Dewan Pengawas sampai kepala unit.'],
                ['75%', 'manajer tidak memiliki insentif yang terkait dengan strategi', 'Hambatan manusia', 'Setiap IK punya pemilik, target bulanan, dan skor yang terlihat jelas.'],
                ['60%', 'organisasi tidak mengaitkan anggaran dengan strategi', 'Hambatan sumber daya', 'Setiap inisiatif terhubung ke sasaran, lengkap dengan anggaran dan serapannya.'],
                ['85%', 'tim eksekutif membahas strategi kurang dari 1 jam per bulan', 'Hambatan manajemen', 'Pusat Kendali dan ritme rapat bulanan membuat strategi selalu masuk agenda.'],
            ] as [$num, $fact, $label, $answer])
                <div class="arah-card kenali-barrier">
                    <div class="kenali-num">{{ $num }}</div>
                    <div class="kenali-fact">{{ $fact }}</div>
                    <span class="arah-badge arah-merah">{{ $label }}</span>
                    <div class="kenali-answer"><b>Jawaban Ruci BSC:</b> {{ $answer }}</div>
                </div>
            @endforeach
        </div>
        <div class="arah-muted kenali-source">Sumber: Robert S. Kaplan &amp; David P. Norton, <i>The Strategy-Focused Organization</i> (Harvard Business School Press, 2001).</div>
    </section>

    {{-- ===== Diagram 1: strategi ke aksi ===== --}}
    <section class="kenali-section">
        <h2>Dari strategi menjadi rencana aksi</h2>
        <p class="kenali-lead">Ruci BSC menurunkan arah besar satker BLU, selangkah demi selangkah, sampai menjadi pekerjaan nyata yang punya tanggal, anggaran, dan penanggung jawab.</p>
        <div class="kenali-cascade">
            @foreach ([
                ['Visi & Misi', 'Arah besar satker BLU', 'Jati Diri'],
                ['Tema strategis', '3–5 fokus utama', 'Tema Strategis'],
                ['Sasaran strategis', 'Dalam 4 perspektif BSC', 'Peta Strategi'],
                ['IK & target', 'Ukuran, pemilik, target bulanan', 'IK'],
                ['Inisiatif', 'Proyek, anggaran, PIC', 'Portofolio Inisiatif'],
                ['Aktivitas & milestone', 'Rencana aksi bertanggal', 'Aktivitas Inisiatif'],
            ] as $i => [$t, $d, $where])
                <div class="kenali-step">
                    <div class="kenali-step-no">{{ $i + 1 }}</div>
                    <div class="kenali-step-t">{{ $t }}</div>
                    <div class="kenali-step-d">{{ $d }}</div>
                    <div class="kenali-step-w">menu {{ $where }}</div>
                </div>
            @endforeach
        </div>
        <div class="kenali-loop">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M4 12a8 8 0 0 1 14-5.3M20 12a8 8 0 0 1-14 5.3M18 3v4h-4M6 21v-4h4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <div><b>Umpan balik setiap bulan:</b> realisasi IK masuk, skor hijau/kuning/merah dihitung otomatis, rapat tinjauan memutuskan <b>temuan</b> dan <b>tindakan korektif</b>, lalu inisiatif disesuaikan. Strategi tidak berhenti di dokumen, tetapi terus dikoreksi.</div>
        </div>
    </section>

    {{-- ===== Diagram 2: peta strategi ===== --}}
    <section class="kenali-section">
        <h2>Peta Strategi: seluruh strategi dalam satu halaman</h2>
        <div class="arah-grid arah-grid-3">
            <div class="arah-card arah-span-2">
                <svg class="kenali-svg" viewBox="0 0 900 420" role="img" aria-label="Contoh peta strategi empat perspektif">
                    <defs>
                        <marker id="kn-arrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0 0L10 5L0 10z" class="kn-arrowhead"/></marker>
                    </defs>
                    @foreach ($lanes as $i => $k)
                        <rect x="0" y="{{ 10 + $i * 100 }}" width="900" height="92" rx="10" fill="{{ $p[$k]['color'] }}" fill-opacity=".08"/>
                        <rect x="0" y="{{ 10 + $i * 100 }}" width="5" height="92" rx="2" fill="{{ $p[$k]['color'] }}"/>
                        <text x="18" y="{{ 10 + $i * 100 + 50 }}" class="kn-lane" fill="{{ $p[$k]['color'] }}">{{ $p[$k]['label'] }}</text>
                    @endforeach
                    @foreach ($links as [$from, $to])
                        @php [$la, $xa] = $nodes[$from]; [$lb, $xb] = $nodes[$to]; @endphp
                        <path d="M{{ $xa }} {{ $cy($la) - 23 }} C {{ $xa }} {{ $cy($la) - 60 }}, {{ $xb }} {{ $cy($lb) + 60 }}, {{ $xb }} {{ $cy($lb) + 25 }}" class="kn-link" marker-end="url(#kn-arrow)"/>
                    @endforeach
                    <path d="M{{ $nodes['f1'][1] + 100 }} {{ $cy(0) }} L{{ $nodes['f2'][1] - 104 }} {{ $cy(0) }}" class="kn-link" marker-end="url(#kn-arrow)"/>
                    @foreach ($nodes as [$lane, $x, $label])
                        <rect x="{{ $x - 98 }}" y="{{ $cy($lane) - 23 }}" width="196" height="46" rx="8" class="kn-card" stroke="{{ $p[$lanes[$lane]]['color'] }}"/>
                        <text x="{{ $x }}" y="{{ $cy($lane) + 4 }}" text-anchor="middle" class="kn-card-t">{{ $label }}</text>
                    @endforeach
                </svg>
            </div>
            <div class="kenali-explain">
                <p>Peta Strategi menunjukkan <b>hubungan sebab-akibat</b>: SDM dan sistem yang kuat memperbaiki proses, proses yang baik memuaskan pelanggan, dan pelanggan yang setia menghasilkan kinerja keuangan.</p>
                <ul>
                    <li><span class="arah-dot" style="background: {{ $p['keuangan']['color'] }}"></span><b>{{ $p['keuangan']['label'] }}</b>: hasil yang diharapkan pemangku kepentingan.</li>
                    <li><span class="arah-dot" style="background: {{ $p['pelanggan']['color'] }}"></span><b>{{ $p['pelanggan']['label'] }}</b>: nilai yang dijanjikan kepada pelanggan.</li>
                    <li><span class="arah-dot" style="background: {{ $p['proses']['color'] }}"></span><b>{{ $p['proses']['label'] }}</b>: proses yang harus unggul.</li>
                    <li><span class="arah-dot" style="background: {{ $p['sdm']['color'] }}"></span><b>{{ $p['sdm']['label'] }}</b>: orang, sistem, dan budaya penggerak.</li>
                </ul>
                <p>Di Ruci BSC, setiap kotak berwarna sesuai skor terbarunya. Rapat cukup dimulai dari satu gambar: <b>sasaran mana yang merah, dan mengapa</b>.</p>
            </div>
        </div>
    </section>

    {{-- ===== Diagram 3: siklus bulanan ===== --}}
    <section class="kenali-section">
        <h2>Ritme bulanan yang menjaga strategi tetap hidup</h2>
        <div class="arah-grid arah-grid-2">
            <div class="arah-card kenali-center">
                <svg class="kenali-svg kenali-cycle" viewBox="0 0 420 400" role="img" aria-label="Siklus bulanan Ruci BSC">
                    <defs>
                        <marker id="kn-arrow2" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto"><path d="M0 0L10 5L0 10z" class="kn-arrowhead"/></marker>
                    </defs>
                    @foreach ($angles as $i => $a)
                        @php
                            $b = $angles[($i + 1) % 5] + ($i === 4 ? 360 : 0);
                            [$x1, $y1] = $pt($a + 19, $R); [$x2, $y2] = $pt($b - 19, $R);
                        @endphp
                        <path d="M{{ $x1 }} {{ $y1 }} A {{ $R }} {{ $R }} 0 0 1 {{ $x2 }} {{ $y2 }}" class="kn-ring" marker-end="url(#kn-arrow2)"/>
                    @endforeach
                    <text x="{{ $cx0 }}" y="{{ $cy0 - 6 }}" text-anchor="middle" class="kn-center-t">Siklus</text>
                    <text x="{{ $cx0 }}" y="{{ $cy0 + 16 }}" text-anchor="middle" class="kn-center-t">bulanan</text>
                    @foreach ($cycle as $i => [$day, $label])
                        @php [$x, $y] = $pt($angles[$i], $R); @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="40" class="kn-node" stroke="{{ ['#1F4E8C', '#2A8C9E', '#5C5AA8', '#2E7D4F', '#D32F2F'][$i] }}"/>
                        <text x="{{ $x }}" y="{{ $y - 4 }}" text-anchor="middle" class="kn-node-d">{{ $day }}</text>
                        <text x="{{ $x }}" y="{{ $y + 14 }}" text-anchor="middle" class="kn-node-t">{{ $label }}</text>
                    @endforeach
                </svg>
            </div>
            <div class="kenali-explain">
                <p>Ruci BSC menetapkan <b>tanggal yang sama setiap bulan</b> untuk semua unit, sehingga angka selalu siap sebelum rapat dan tidak ada lagi rapat yang habis untuk mengumpulkan data.</p>
                <ol class="kenali-cycle-list">
                    @foreach ($cycle as [$day, $label, $desc])
                        <li><b>{{ $day }} · {{ $label }}.</b> {{ $desc }}</li>
                    @endforeach
                </ol>
                <p>Setiap perubahan setelah disetujui tercatat di <b>log audit</b>. Dewan Pengawas bisa memberi <b>catatan pengawasan</b> dan membuat temuan langsung dari hasil tinjauan.</p>
            </div>
        </div>
    </section>

    {{-- ===== Siapa mendapat apa ===== --}}
    <section class="kenali-section">
        <h2>Satu sumber kebenaran, sesuai peran masing-masing</h2>
        <div class="arah-grid arah-grid-3">
            @foreach ([
                ['Dewan Pengawas', 'Mengawasi tanpa menunggu laporan tebal', ['Skor strategis dan status setiap sasaran kapan saja', 'Catatan pengawasan dan temuan pada setiap tinjauan', 'Jejak audit atas setiap koreksi angka']],
                ['Pejabat Pengelola', 'Mengarahkan dengan data yang sama', ['Pusat Kendali: IK merah, inisiatif terlambat, temuan terbuka', 'Persetujuan realisasi bulanan dalam beberapa klik', 'Portofolio inisiatif: mana dipercepat, mana ditinjau ulang']],
                ['Pembina Teknis & DJPb', 'Menerima laporan capaian yang lengkap dan tepat waktu', ['Kontrak Kinerja format Lampiran I beserta Definisi Operasional', 'Perhitungan capaian format Lampiran VI setiap triwulan', 'Uji syarat insentif Pasal 14 ayat (4) yang transparan']],
                ['Kantor Strategi / Corporate Planning', 'Mengelola siklus tanpa spreadsheet berserakan', ['Impor & ekspor Excel untuk seluruh perangkat BSC', 'Pengingat otomatis dan status kelengkapan data', 'Laporan siap cetak untuk rapat']],
                ['Kepala Divisi & Unit', 'Tahu persis kontribusinya', ['Daftar IK dan aktivitas miliknya di Beranda', 'Input realisasi dengan bukti dan penjelasan', 'Tindakan korektif dengan tenggat yang jelas']],
                ['Seluruh Pegawai', 'Memahami ke mana satker BLU bergerak', ['Peta strategi yang mudah dibaca', 'Bahasa yang sama dari rapat pimpinan sampai lapangan', 'Kerja harian yang terhubung ke sasaran satker BLU']],
            ] as [$who, $tag, $items])
                <div class="arah-card kenali-role">
                    <h3>{{ $who }}</h3>
                    <div class="arah-muted">{{ $tag }}</div>
                    <ul>
                        @foreach ($items as $it)<li>{{ $it }}</li>@endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Tangkapan layar ===== --}}
    <section class="kenali-section" id="lihat-langsung">
        <h2>Lihat langsung</h2>
        <p class="kenali-lead">Tampilan berikut diambil dari data demo UIN Sunan Kalijaga Yogyakarta yang tersedia di situs ini (hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak). Klik gambar untuk memperbesar.</p>
        <div class="kenali-gallery">
            @foreach ([
                ['pusat-kendali', 'Pusat Kendali Eksekutif', 'Skor strategis, status semua IK, inisiatif terlambat, dan temuan terbuka dalam satu layar. Bahan utama rapat Pejabat Pengelola.'],
                ['kontrak-kinerja', 'Kontrak Kinerja Pemimpin BLU', 'Indikator aspek layanan & keuangan, target trajektori triwulanan, capaian maksimum, dan Definisi Operasional; versi awal, adendum, dan perubahan tercatat. Cetak format Lampiran I, II, IV, dan VI PER-6/PB/2025.'],
                ['capaian-insentif', 'Capaian & syarat insentif', 'Perhitungan capaian per triwulan, peringatan indikator di bawah 65%, uji syarat insentif Pasal 14 ayat (4), dan kalender tenggat laporan.'],
                ['beranda', 'Beranda setiap pengguna', 'Setiap orang langsung melihat ringkasan kinerja dan tugasnya: realisasi yang harus diisi, persetujuan yang menunggu.'],
                ['portofolio', 'Portofolio Inisiatif Strategis', 'Inisiatif dipetakan menurut nilai dan upaya, lengkap dengan progres, anggaran, serapan, dan tanda terlambat.'],
                ['laporan', 'Kartu skor & laporan', 'Kartu skor Indikator Kinerja bulanan dan YTD, capaian per perspektif, status inisiatif dan temuan. Siap unduh Excel atau cetak.'],
                ['alur-kerja', 'Alur kerja yang terukur', 'Kelengkapan siklus bulanan terpantau: berapa realisasi terisi, terverifikasi, dan sudah disetujui Pimpinan.'],
            ] as [$file, $title, $desc])
                <figure class="arah-card kenali-figure">
                    <a class="kenali-shot" href="{{ $img($file) }}" target="_blank" rel="noopener"><img src="{{ $img($file) }}" alt="{{ $title }}" loading="lazy"></a>
                    <figcaption><b>{{ $title }}</b><span>{{ $desc }}</span></figcaption>
                </figure>
            @endforeach
            <figure class="arah-card kenali-figure kenali-phone">
                <a class="kenali-shot" href="{{ $img('hp-pusat-kendali') }}" target="_blank" rel="noopener"><img src="{{ $img('hp-pusat-kendali') }}" alt="Pusat Kendali di ponsel" loading="lazy"></a>
                <figcaption><b>Di ponsel pun bisa</b><span>Dewan Pengawas dan Pejabat Pengelola dapat memantau kinerja dari mana saja, tanpa aplikasi tambahan.</span></figcaption>
            </figure>
        </div>
    </section>

    {{-- ===== Studi kasus ===== --}}
    <section class="kenali-section" id="studi-kasus">
        <span class="kenali-eyebrow">Bahan ajar</span>
        <h2>Studi kasus satker BLU siap dipelajari</h2>
        <p class="kenali-lead">Satker BLU contoh berisi perioda 2025 (ditutup) dan 2026 (berjalan, proyeksi): lingkungan strategis, SWOT–TOWS, Peta Strategi, Indikator Kinerja beserta Definisi Operasional, Kontrak Kinerja dan perubahannya, capaian & syarat insentif, inisiatif, rapat tinjauan, temuan, dan tindak lanjutnya.</p>
        <div class="arah-demo-banner" role="note"><span class="arah-demo-tag">Data demo</span><span>Hanya data demo untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada LK BLU Audited 2025, Perjanjian Kinerja 2025, dan RSB 2020–2024 UIN Sunan Kalijaga; rincian triwulanan, Kontrak Kinerja, dan data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan.</span></div>
        <div class="kenali-cases">
            @foreach (\App\Support\DemoCatalog::CASES as [$code, $name, $sector, $lesson])
                <div class="arah-card kenali-case">
                    <span class="kenali-case-code">{{ $code }}</span>
                    <b>{{ $name }}</b>
                    <small>{{ $sector }}</small>
                    <p>{{ $lesson }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Lima fase ===== --}}
    <section class="kenali-section">
        <h2>Kerangka lengkap, bukan sekadar dasbor</h2>
        <p class="kenali-lead">Ruci BSC mengikuti siklus manajemen strategi utuh, dari membaca lingkungan sampai melembagakan perubahan.</p>
        <div class="kenali-phases">
            @foreach ([
                'Membaca lingkungan: PESTEL, kekuatan industri, pasar, pesaing, regulasi, SWOT dan TOWS.',
                'Menegaskan visi, misi, nilai inti, dan kapabilitas organisasi.',
                'Menetapkan tema, sasaran, IK, target, dan memantau realisasi.',
                'Menjalankan inisiatif, anggaran, temuan, dan tindakan korektif.',
                'Menilai kematangan dan melembagakan praktik yang berhasil.',
            ] as $i => $desc)
                <div class="kenali-phase">
                    <div class="arah-muted">Fase {{ $i + 1 }}</div>
                    <b>{{ $phases[$i] }}</b>
                    <span>{{ $desc }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Workshop ===== --}}
    <section class="kenali-section kenali-workshop" id="workshop">
        <div>
            <span class="kenali-eyebrow">Workshop sehari · setiap Minggu pertama awal bulan</span>
            <h2>Kenali Ruci BSC dalam satu hari</h2>
            <p>Kelas praktis 08.00–16.30 WIB untuk Pemimpin BLU, Pejabat Pengelola, Dewan Pengawas, dan tim perencanaan. Workshop berikutnya: <b style="color:#fff">{{ \App\Filament\Pages\WorkshopRuciBsc::nextDates(1)[0]->locale('id')->translatedFormat('l, j F Y') }}</b>.</p>
            <div class="kenali-take">
                <b>Yang Anda bawa pulang</b>
                <ul>
                    <li>Pemahaman utuh konsep Balanced Scorecard dan siklus kinerja bulanan</li>
                    <li>Draf Peta Strategi dan contoh kamus Indikator Kinerja hasil latihan kelompok</li>
                    <li>Daftar inisiatif prioritas di matriks nilai–upaya</li>
                    <li>Pengalaman memakai Ruci BSC dan simulasi rapat bulanan</li>
                    <li>Paket materi, template Excel, rencana aksi 30 hari, dan sertifikat</li>
                </ul>
            </div>
            <div class="kenali-actions">
                @if ($workshopUrl)
                    <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop Ruci BSC</a>
                @endif
                <a href="{{ \App\Filament\Pages\WorkshopRuciBsc::getUrl() }}" class="arah-btn kenali-btn-lg">Lihat rundown &amp; materi</a>
            </div>
            @if ($workshopContact)
                <div class="kenali-contact">Informasi &amp; pendaftaran: <b>{{ $workshopContact }}</b></div>
            @endif
        </div>
        <ol class="kenali-agenda">
            <li><b>Mengapa strategi gagal dijalankan</b><span>Konsep Balanced Scorecard dan lima fase Ruci BSC</span></li>
            <li><b>Membaca lingkungan &amp; jati diri</b><span>PESTEL, SWOT, TOWS, tema strategis</span></li>
            <li><b>Menyusun Peta Strategi</b><span>Sasaran empat perspektif dan hubungan sebab-akibat</span></li>
            <li><b>IK &amp; target yang baik</b><span>SMART, kamus Indikator Kinerja, target bulanan, skor</span></li>
            <li><b>Dari strategi ke aksi</b><span>Inisiatif, anggaran, portofolio nilai–upaya</span></li>
            <li><b>Praktik Ruci BSC</b><span>Pusat Kendali dan simulasi rapat bulanan</span></li>
        </ol>
    </section>
</div>
</x-filament-panels::page>
