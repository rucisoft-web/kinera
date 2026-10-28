@php
    $img = fn (string $n) => asset('images/kenali/'.$n.'.jpg').'?v=3';
    $p = $perspectives;

    // Diagram peta strategi: 4 lapis perspektif, panah sebab-akibat dari bawah ke atas
    $lanes = ['keuangan', 'pelanggan', 'proses', 'sdm'];
    $cy = fn (int $lane) => 10 + $lane * 100 + 46;
    $nodes = [
        'f1' => [0, 390, 'Anggaran tepat sasaran'], 'f2' => [0, 690, 'Akuntabilitas keuangan'],
        'c1' => [1, 390, 'Layanan memuaskan'], 'c2' => [1, 690, 'Kepercayaan publik naik'],
        'p1' => [2, 290, 'Mutu layanan andal'], 'p2' => [2, 540, 'Inovasi layanan'], 'p3' => [2, 790, 'Operasi efisien'],
        'l1' => [3, 290, 'ASN kompeten'], 'l2' => [3, 540, 'Sistem informasi terpadu'], 'l3' => [3, 790, 'Budaya kinerja'],
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
            <span class="kenali-eyebrow">Untuk pimpinan instansi pemerintah dan perguruan tinggi negeri</span>
            <h1>Strategi yang dipahami semua orang, dijalankan setiap bulan.</h1>
            <p>Kinera adalah perangkat pengukuran dan manajemen kinerja berbasis <b>Balanced Scorecard</b> untuk instansi pemerintah: kementerian/lembaga, pemerintah daerah, dan perguruan tinggi negeri. Kinera membantu pimpinan <b>mengomunikasikan strategi</b> ke seluruh organisasi dan <b>menerjemahkannya menjadi rencana aksi operasional</b>: sasaran, Indikator Kinerja Utama, target, inisiatif, anggaran, dan penanggung jawab yang dipantau dalam satu tempat, sampai menjadi bahan Perjanjian Kinerja dan LKjIP.</p>
            <div class="kenali-actions">
                @if ($workshopUrl)
                    <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop Kinera</a>
                @endif
                <a href="#lihat-langsung" class="arah-btn kenali-btn-lg">Lihat tampilannya</a>
            </div>
        </div>
        <a class="kenali-shot kenali-hero-shot" href="{{ $img('peta-strategi') }}" target="_blank" rel="noopener">
            <img src="{{ $img('peta-strategi') }}" alt="Peta Strategi instansi contoh (data demo) di Kinera" loading="eager">
        </a>
    </section>

    {{-- ===== Masalah ===== --}}
    <section class="kenali-section">
        <h2>Mengapa strategi yang baik sering tidak sampai ke lapangan?</h2>
        <p class="kenali-lead">Kaplan &amp; Norton, perancang Balanced Scorecard, menemukan empat hambatan klasik pelaksanaan strategi. Kinera dirancang untuk menjawab keempatnya.</p>
        <div class="arah-grid arah-grid-4">
            @foreach ([
                ['95%', 'pegawai tidak memahami strategi organisasinya', 'Hambatan visi', 'Peta Strategi satu halaman yang bisa dibaca semua orang, dari pimpinan sampai kepala unit.'],
                ['75%', 'manajer tidak memiliki insentif yang terkait dengan strategi', 'Hambatan manusia', 'Setiap IK punya pemilik, target bulanan, dan skor yang terlihat jelas.'],
                ['60%', 'organisasi tidak mengaitkan anggaran dengan strategi', 'Hambatan sumber daya', 'Setiap inisiatif terhubung ke sasaran, lengkap dengan anggaran dan serapannya.'],
                ['85%', 'tim eksekutif membahas strategi kurang dari 1 jam per bulan', 'Hambatan manajemen', 'Pusat Kendali dan ritme rapat bulanan membuat strategi selalu masuk agenda.'],
            ] as [$num, $fact, $label, $answer])
                <div class="arah-card kenali-barrier">
                    <div class="kenali-num">{{ $num }}</div>
                    <div class="kenali-fact">{{ $fact }}</div>
                    <span class="arah-badge arah-merah">{{ $label }}</span>
                    <div class="kenali-answer"><b>Jawaban Kinera:</b> {{ $answer }}</div>
                </div>
            @endforeach
        </div>
        <div class="arah-muted kenali-source">Sumber: Robert S. Kaplan &amp; David P. Norton, <i>The Strategy-Focused Organization</i> (Harvard Business School Press, 2001).</div>
    </section>

    {{-- ===== Diagram 1: strategi ke aksi ===== --}}
    <section class="kenali-section">
        <h2>Dari strategi menjadi rencana aksi</h2>
        <p class="kenali-lead">Kinera menurunkan arah besar instansi, selangkah demi selangkah, sampai menjadi pekerjaan nyata yang punya tanggal, anggaran, dan penanggung jawab.</p>
        <div class="kenali-cascade">
            @foreach ([
                ['Visi & Misi', 'Arah besar instansi', 'Jati Diri'],
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
                <p>Peta Strategi menunjukkan <b>hubungan sebab-akibat</b>: SDM dan sistem yang kuat memperbaiki proses, proses yang baik menghasilkan layanan yang memuaskan masyarakat, dan layanan yang baik menjadi bukti anggaran dipakai tepat sasaran.</p>
                <ul>
                    <li><span class="arah-dot" style="background: {{ $p['keuangan']['color'] }}"></span><b>{{ $p['keuangan']['label'] }}</b>: anggaran yang efektif, efisien, dan akuntabel.</li>
                    <li><span class="arah-dot" style="background: {{ $p['pelanggan']['color'] }}"></span><b>{{ $p['pelanggan']['label'] }}</b>: manfaat yang dijanjikan kepada masyarakat dan pengguna layanan.</li>
                    <li><span class="arah-dot" style="background: {{ $p['proses']['color'] }}"></span><b>{{ $p['proses']['label'] }}</b>: proses yang harus unggul.</li>
                    <li><span class="arah-dot" style="background: {{ $p['sdm']['color'] }}"></span><b>{{ $p['sdm']['label'] }}</b>: orang, sistem, dan budaya penggerak.</li>
                </ul>
                <p>Di Kinera, setiap kotak berwarna sesuai skor terbarunya. Rapat cukup dimulai dari satu gambar: <b>sasaran mana yang merah, dan mengapa</b>.</p>
            </div>
        </div>
    </section>

    {{-- ===== Diagram 3: siklus bulanan ===== --}}
    <section class="kenali-section">
        <h2>Ritme bulanan yang menjaga strategi tetap hidup</h2>
        <div class="arah-grid arah-grid-2">
            <div class="arah-card kenali-center">
                <svg class="kenali-svg kenali-cycle" viewBox="0 0 420 400" role="img" aria-label="Siklus bulanan Kinera">
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
                <p>Kinera menetapkan <b>tanggal yang sama setiap bulan</b> untuk semua unit, sehingga angka selalu siap sebelum rapat dan tidak ada lagi rapat yang habis untuk mengumpulkan data.</p>
                <ol class="kenali-cycle-list">
                    @foreach ($cycle as [$day, $label, $desc])
                        <li><b>{{ $day }} · {{ $label }}.</b> {{ $desc }}</li>
                    @endforeach
                </ol>
                <p>Setiap perubahan setelah disetujui tercatat di <b>log audit</b>. Pengawas (APIP atau Dewan Pengawas) bisa memberi <b>catatan pengawasan</b> dan membuat temuan langsung dari hasil tinjauan.</p>
            </div>
        </div>
    </section>

    {{-- ===== Siapa mendapat apa ===== --}}
    <section class="kenali-section">
        <h2>Satu sumber kebenaran, sesuai peran masing-masing</h2>
        <div class="arah-grid arah-grid-3">
            @foreach ([
                ['Pengawas (APIP / Dewan Pengawas)', 'Mengawasi tanpa menunggu laporan tebal', ['Skor strategis dan status setiap sasaran kapan saja', 'Catatan pengawasan dan temuan pada setiap tinjauan', 'Jejak audit atas setiap koreksi angka']],
                ['Pimpinan instansi', 'Mengarahkan dengan data yang sama', ['Pusat Kendali: IK merah, inisiatif terlambat, temuan terbuka', 'Persetujuan realisasi bulanan dalam beberapa klik', 'Portofolio inisiatif: mana dipercepat, mana ditinjau ulang']],
                ['Kementerian, evaluator SAKIP & pembina', 'Menerima laporan capaian yang lengkap dan tepat waktu', ['Perjanjian Kinerja & IKU beserta Definisi Operasional', 'Capaian triwulanan sebagai bahan LKjIP', 'Bagi BLU: Kontrak Kinerja & lampiran PER-6/PB/2025']],
                ['Perencanaan', 'Mengelola siklus tanpa spreadsheet berserakan', ['Impor & ekspor Excel untuk seluruh perangkat BSC', 'Pengingat otomatis dan status kelengkapan data', 'Laporan siap cetak untuk rapat']],
                ['Kepala Unit Kerja', 'Tahu persis kontribusinya', ['Daftar IK dan aktivitas miliknya di Beranda', 'Input realisasi dengan bukti dan penjelasan', 'Tindakan korektif dengan tenggat yang jelas']],
                ['Seluruh Pegawai', 'Memahami ke mana instansi bergerak', ['Peta strategi yang mudah dibaca', 'Bahasa yang sama dari rapat pimpinan sampai lapangan', 'Kerja harian yang terhubung ke sasaran instansi']],
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
        <p class="kenali-lead">Tampilan berikut diambil dari data demo perguruan tinggi negeri yang tersedia di situs ini (hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak). Klik gambar untuk memperbesar.</p>
        <div class="kenali-gallery">
            @foreach ([
                ['pusat-kendali', 'Pusat Kendali Eksekutif', 'Skor strategis, status semua IK, inisiatif terlambat, dan temuan terbuka dalam satu layar. Bahan utama rapat pimpinan.'],
                ['kontrak-kinerja', 'Modul Kontrak Kinerja (instansi BLU)', 'Indikator aspek layanan & keuangan, target trajektori triwulanan, capaian maksimum, dan Definisi Operasional; versi awal, adendum, dan perubahan tercatat. Cetak format Lampiran I, II, IV, dan VI PER-6/PB/2025.'],
                ['capaian-insentif', 'Capaian triwulanan', 'Perhitungan capaian per triwulan, peringatan indikator yang tertinggal, dan kalender tenggat laporan; bagi BLU termasuk uji syarat insentif Pasal 14 ayat (4).'],
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
                <figcaption><b>Di ponsel pun bisa</b><span>Pimpinan dan pengawas dapat memantau kinerja dari mana saja, tanpa aplikasi tambahan.</span></figcaption>
            </figure>
        </div>
    </section>

    {{-- ===== Studi kasus ===== --}}
    <section class="kenali-section" id="studi-kasus">
        <span class="kenali-eyebrow">Bahan ajar</span>
        <h2>Studi kasus instansi siap dipelajari</h2>
        <p class="kenali-lead">Instansi contoh berisi perioda 2025 (ditutup) dan 2026 (berjalan, proyeksi): lingkungan strategis, SWOT–TOWS, Peta Strategi, Indikator Kinerja beserta Definisi Operasional, Perjanjian/Kontrak Kinerja dan perubahannya, capaian triwulanan, inisiatif, rapat tinjauan, temuan, dan tindak lanjutnya.</p>
        <div class="arah-demo-banner" role="note"><span class="arah-demo-tag">Data demo</span><span>Hanya data demo untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada dokumen publik masing-masing kampus (laporan keuangan, Perjanjian Kinerja, LKjIP, Renstra); rincian triwulanan, Kontrak Kinerja, dan data 2026 (proyeksi) adalah simulasi. Bukan data resmi kampus tersebut, Kementerian Agama, maupun Kementerian Keuangan.</span></div>
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
        <p class="kenali-lead">Kinera mengikuti siklus manajemen strategi utuh, dari membaca lingkungan sampai melembagakan perubahan.</p>
        <div class="kenali-phases">
            @foreach ([
                'Membaca lingkungan: PESTEL, pemangku kepentingan, pembanding, regulasi, SWOT dan TOWS.',
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
            <span class="kenali-eyebrow">Workshop 2 hari · bersama PSMPT UIN Sunan Kalijaga</span>
            <h2>Dari strategi ke pengukuran kinerja dalam dua hari</h2>
            <p>Kelas praktis 08.00–16.30 WIB untuk pimpinan instansi pemerintah dan perguruan tinggi negeri, tim perencanaan dan keuangan, serta APIP/SPI, diselenggarakan bersama Pusat Studi Manajemen Perguruan Tinggi UIN Sunan Kalijaga.</p>
            <div class="kenali-take">
                <b>Yang Anda bawa pulang</b>
                <ul>
                    <li>Draf Peta Strategi empat perspektif</li>
                    <li>Daftar Indikator Kinerja dan definisi operasionalnya</li>
                    <li>Scorecard pimpinan beserta targetnya (format Kontrak Kinerja bagi instansi BLU)</li>
                    <li>Pengalaman memakai Kinera: alur realisasi, capaian, dan laporan</li>
                    <li>Rencana aksi 90 hari, materi PDF, dan sertifikat</li>
                </ul>
            </div>
            <div class="kenali-actions">
                <a href="{{ \App\Filament\Pages\WorkshopKinera::registerUrl() }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop Kinera</a>
                <a href="{{ \App\Filament\Pages\WorkshopKinera::getUrl() }}" class="arah-btn kenali-btn-lg">Lihat rundown &amp; materi</a>
            </div>
            @if ($workshopContact)
                <div class="kenali-contact">Informasi &amp; pendaftaran: <b>{{ $workshopContact }}</b></div>
            @endif
        </div>
        <ol class="kenali-agenda">
            <li><b>Hari 1 · Balanced Scorecard untuk sektor publik</b><span>Pembukaan oleh Kepala PSMPT, empat perspektif sektor publik</span></li>
            <li><b>Hari 1 · Anatomi scorecard</b><span>Sasaran, ukuran hasil dan pendorong, target, dan inisiatif</span></li>
            <li><b>Hari 1 · Peta Strategi dan Indikator Kinerja</b><span>Diagnosis kinerja, sasaran strategis, latihan kelompok</span></li>
            <li><b>Hari 2 · Definisi operasional dan target</b><span>Rumus, sumber data, trajektori triwulanan</span></li>
            <li><b>Hari 2 · Simulasi penetapan target</b><span>Kesepakatan target, perubahan, dan Kontrak Kinerja BLU di Kinera</span></li>
            <li><b>Hari 2 · Praktik Kinera dan rencana aksi</b><span>Realisasi, capaian, temuan, rencana aksi 90 hari</span></li>
        </ol>
    </section>
</div>
</x-filament-panels::page>
