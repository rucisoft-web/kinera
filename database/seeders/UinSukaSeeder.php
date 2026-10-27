<?php

namespace Database\Seeders;

use App\Models\AtmanProfile;
use App\Models\Capability;
use App\Models\Competitor;
use App\Models\CoreValue;
use App\Models\EnvironmentScan;
use App\Models\IndustryForce;
use App\Models\Kpi;
use App\Models\MarketAnalysis;
use App\Models\ObjectiveRelation;
use App\Models\PerformanceContract;
use App\Models\PerformanceReview;
use App\Models\PestelAnalysis;
use App\Models\Regulation;
use App\Models\ReviewNote;
use App\Models\Stakeholder;
use App\Models\StrategicChoice;
use App\Models\StrategicIssue;
use App\Models\StrategicObjective;
use App\Models\StrategicTheme;
use App\Models\SwotItem;
use App\Models\TowsStrategy;
use App\Services\BluContractService;

/**
 * Demo satker BLU: Universitas Islam Negeri Sunan Kalijaga Yogyakarta (kode UINSUKA), bidang layanan pendidikan,
 * Pembina Teknis Direktorat Jenderal Pendidikan Islam Kementerian Agama.
 *
 * DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak:
 * - Perioda 2025 (ditutup): angka keuangan dari Laporan Keuangan BLU Audited TA 2025 (opini WTP), target indikator layanan
 *   dari Perjanjian Kinerja 2025, gambaran kelembagaan dari RSB 2020–2024 & Renstra 2025–2029. Rincian triwulanan,
 *   realisasi indikator layanan, dan Kontrak Kinerja adalah simulasi.
 * - Perioda 2026 (berjalan, bawaan): seluruh target & realisasi Triwulan I–III adalah PROYEKSI/SIMULASI.
 * - Tidak memakai nama atau NIP pejabat sebenarnya; semua penandatangan bertanda "(contoh)".
 */
class UinSukaSeeder extends ShowcaseSeeder
{
    public const CODE = 'UINSUKA';

    public const LT = 'Laporan Keuangan BLU Audited TA 2025, Perjanjian Kinerja 2025 & RSB 2020–2024 UIN Sunan Kalijaga';

    public const LK = 'Laporan Keuangan BLU UIN Sunan Kalijaga TA 2025 (audited, opini WTP)';

    public const PK = 'Perjanjian Kinerja Tahun 2025 UIN Sunan Kalijaga';

    /** Bobot musiman: UKT semester genap (Feb–Mar) & ganjil (Agu–Sep) menaikkan pendapatan; belanja menumpuk di semester II. */
    public const SEASON = [
        2025 => [1 => .070, .085, .090, .075, .078, .080, .082, .095, .098, .082, .080, .085],
        2026 => [1 => .072, .086, .088, .076, .078, .080, .080, .096, .097, .083, .080, .084],
    ];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'UIN Sunan Kalijaga Yogyakarta', 'legal_name' => 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta (Satker BLU)',
            'industry' => 'Pendidikan tinggi keagamaan Islam (BLU)', 'blu_field' => 'pendidikan',
            'ministry' => 'Kementerian Agama', 'technical_supervisor' => 'Direktorat Jenderal Pendidikan Islam',
            'leader_title' => 'Rektor', 'leader_name' => 'Rektor UIN Sunan Kalijaga (contoh)', 'leader_nip' => null,
            'dg_name' => 'Direktur Jenderal Perbendaharaan (contoh)', 'dg_nip' => null,
            'address' => 'Jl. Marsda Adisucipto No. 1', 'city' => 'Yogyakarta',
            'phone' => '+62 274 512474', 'website' => 'https://uin-suka.ac.id',
            'description' => 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada '.self::LT.'; rincian triwulanan, Kontrak Kinerja, realisasi indikator layanan, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Angka keuangan mengacu pada LK BLU Audited 2025 dan target layanan pada Perjanjian Kinerja 2025; rincian triwulanan ilustratif.'
            : 'DATA DEMO — PROYEKSI 2026: Triwulan I–II disetujui, Triwulan III dalam proses verifikasi; usulan perubahan Kontrak Kinerja menunggu DJPb.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['F2.1', 'F2.3', 'C1.2', 'C3.1', 'P1.2', 'P2.2', 'L2.1'];
    }

    protected function missingSeptember(): array
    {
        return ['L1.2'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Angka lulusan bekerja Triwulan III masih memakai responden tracer study 2025 yang belum divalidasi. Mohon pisahkan responden baru dan lampirkan rekap tracer per fakultas.'];
    }

    protected function maturityPattern(): array
    {
        return [3, 2, 3, 2, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Ruci BSC UIN (contoh)', 'uinsuka.admin', 'Admin Sistem', 'Pusat Teknologi Informasi dan Pangkalan Data', ['company-admin']],
            'dirut' => ['Rektor (contoh)', 'uinsuka.rektor', 'Rektor / Pemimpin BLU', 'Pimpinan Universitas', ['pimpinan']],
            'cfo' => ['Wakil Rektor II (contoh)', 'uinsuka.wr2', 'Wakil Rektor Bidang Administrasi Umum, Perencanaan & Keuangan', 'Pejabat Pengelola Keuangan BLU', ['keuangan']],
            'akademik' => ['Wakil Rektor I (contoh)', 'uinsuka.wr1', 'Wakil Rektor Bidang Akademik & Pengembangan Lembaga', 'Pimpinan Universitas', ['kepala-unit']],
            'kemahasiswaan' => ['Wakil Rektor III (contoh)', 'uinsuka.wr3', 'Wakil Rektor Bidang Kemahasiswaan & Kerja Sama', 'Pimpinan Universitas', ['kepala-unit']],
            'lppm' => ['Ketua LPPM (contoh)', 'uinsuka.lppm', 'Ketua Lembaga Penelitian & Pengabdian kepada Masyarakat', 'LPPM', ['kepala-unit']],
            'lpm' => ['Ketua LPM (contoh)', 'uinsuka.lpm', 'Ketua Lembaga Penjaminan Mutu', 'LPM', ['kepala-unit', 'editor-jatidiri']],
            'bisnis' => ['Kepala Pusat Pengembangan Bisnis (contoh)', 'uinsuka.bisnis', 'Kepala Pusat Pengembangan Bisnis', 'Pusat Pengembangan Bisnis', ['kepala-unit']],
            'sdm' => ['Kepala Bagian Kepegawaian (contoh)', 'uinsuka.sdm', 'Kepala Bagian Organisasi, Kepegawaian & Hukum', 'Biro AUPK', ['kepala-unit']],
            'ti' => ['Kepala PTIPD (contoh)', 'uinsuka.ti', 'Kepala Pusat Teknologi Informasi & Pangkalan Data', 'PTIPD', ['kepala-unit']],
            'strategi' => ['Kepala Bagian Perencanaan (contoh)', 'uinsuka.perencanaan', 'Kepala Bagian Perencanaan & Keuangan', 'Biro AUPK', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Ketua Dewan Pengawas (contoh)', 'uinsuka.dewas', 'Ketua Dewan Pengawas BLU', 'Dewan Pengawas', ['pengawas']],
            'analis' => ['Analis Data Kinerja (contoh)', 'uinsuka.analis', 'Analis Perencanaan & Data Kinerja', 'Biro AUPK', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadikan UIN Sunan Kalijaga unggul dan bereputasi global dalam rangka mewujudkan masyarakat yang rukun, mashlahat, dan cerdas.',
            'mission' => "1. Meraih keunggulan akademik melalui peningkatan mutu pendidikan dan pengajaran yang berkualitas, berkelanjutan dan berdaya saing.\n2. Meningkatkan students well-being melalui penyediaan layanan yang komprehensif dan merata.\n3. Mendorong penelitian dan inovasi yang berdampak terhadap perkembangan ilmu pengetahuan, seni, dan teknologi.\n4. Meningkatkan pengabdian kepada masyarakat berbasis keberagaman, integritas akademik, dan budaya inklusi untuk kemaslahatan bangsa.\n5. Memperkuat kemitraan dengan lembaga akademik, organisasi masyarakat dan dunia industri dalam dan luar negeri.\n6. Meningkatkan implementasi Good University Governance (GUG) yang berkelanjutan.\n7. Transformasi kelembagaan dan infrastruktur pendukung melalui pengembangan kampus.",
            'purpose' => 'Menyelenggarakan Tri Dharma Perguruan Tinggi yang mengintegrasikan dan menginterkoneksikan ilmu keislaman dengan sains, sosial, dan teknologi untuk peradaban yang damai.',
            'tagline' => 'Unggul dan bereputasi global',
            'brand_promise' => 'Kampus inklusif dan moderat: layanan pendidikan bermutu yang terbuka bagi semua, termasuk mahasiswa berkebutuhan khusus.',
            'value_proposition' => 'PTKIN tertua dengan paradigma integrasi-interkoneksi, 8 fakultas dan Pascasarjana, layanan difabel yang dikenal luas, serta jejaring kerja sama dalam dan luar negeri; dikelola dengan pola keuangan BLU (DATA DEMO).',
            'culture_statement' => 'Integritas akademik, inklusi, dan moderasi beragama sebagai budaya kampus; tata kelola berbasis kinerja (Good University Governance).',
            'history' => "1964: berdiri Perguruan Tinggi Agama Islam (PTAI) sebagai cikal bakal.\n1969: Institut Agama Islam Negeri (IAIN) Sunan Kalijaga resmi berdiri.\n2004: beralih status menjadi UIN Sunan Kalijaga (Keppres No. 50/2004, 14 Oktober 2004) dengan mandat integrasi-interkoneksi keilmuan.\nRSB 2020–2024: target kemandirian keuangan BLU (rasio PNBP terhadap biaya operasional 60% pada 2024) dan optimalisasi aset.\n2025: Renstra 2025–2029; pengembangan Kampus 2 Pajangan, Bantul; rencana transformasi menuju PTNBH.\n(Ringkasan dari LK BLU 2025 & RSB 2020–2024 untuk keperluan demo.)",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-10 10:00:00',
        ]);
        foreach ([
            ['Integratif-Interkonektif', 'Integrasi-Interkoneksi', 'Ilmu keislaman, sains, sosial, dan teknologi saling menyapa dalam pendidikan, riset, dan pengabdian.', "Merancang mata kuliah dan riset lintas fakultas\nMembuka data kinerja apa adanya untuk perbaikan"],
            ['Inklusif', 'Inklusi', 'Kampus terbuka bagi semua, termasuk mahasiswa berkebutuhan khusus dan kelompok afirmasi.', "Menyediakan layanan pendamping difabel\nMenjaga keterjangkauan UKT melalui kelompok tarif"],
            ['Moderat', 'Moderasi beragama', 'Keberagamaan yang rukun, toleran, dan menghargai keberagaman.', "Literasi keagamaan di setiap program studi\nForum dialog lintas iman dan budaya"],
            ['Akuntabel', 'Good University Governance', 'Tata kelola yang transparan, akuntabel, dan berbasis kinerja sesuai pola keuangan BLU.', "Melaporkan capaian Kontrak Kinerja tepat waktu\nMenindaklanjuti temuan audit dan SPI sampai tuntas"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Program studi & akreditasi', 'Kompetensi', '8 fakultas (FITK, FSH, FUPI, FDK, FST, FEBI, FADIB, FISHUM) dan Pascasarjana; sebagian besar prodi terakreditasi Unggul/A (gambaran RSB).', 4, 5, true, 'Akreditasi internasional prodi unggulan & peningkatan prodi ke Unggul.'],
            ['Paradigma integrasi-interkoneksi', 'Kompetensi', 'Pembeda keilmuan sejak alih status UIN 2004.', 5, 5, true, 'Kurikulum & riset lintas disiplin berbasis SDGs.'],
            ['Layanan inklusi & difabel', 'Kompetensi', 'Layanan pendamping mahasiswa berkebutuhan khusus; target PK 2025 7% mahasiswa afirmasi/berkebutuhan khusus.', 4, 5, true, 'Perluas layanan aksesibilitas di Kampus 2.'],
            ['SDM dosen & tendik', 'Sumber daya manusia', '1.218 pegawai (463 dosen, 755 tendik) per 2025 menurut LK.', 3, 4, true, 'Sertifikasi kompetensi dosen & tendik; manajemen talenta.'],
            ['Aset & unit bisnis BLU', 'Sumber daya fisik', 'Total aset Rp2,08 T; unit bisnis hotel, convention hall, air minum kemasan (Suka Water) menurut RSB.', 2 + $lvl, 4, false, 'Optimalisasi aset & tata kelola unit bisnis untuk pendapatan non-UKT.'],
            ['Sistem informasi akademik & keuangan', 'Sistem', 'SIA, sistem keuangan BLU, dan pangkalan data kinerja.', 2 + $lvl, 4, false, 'Integrasi data kinerja dengan Ruci BSC & pelaporan Kontrak Kinerja.'],
            ['Tata kelola BLU & penjaminan mutu', 'Sistem', 'Dewan Pengawas, SPI, LPM; opini WTP atas LK BLU 2025.', 3, 5, false, 'SPMI terpadu & pelaporan capaian berbasis bukti.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    /** @return array<string,StrategicIssue> */
    protected function ecosystem(int $year): array
    {
        foreach ([
            ['PER-6/PB/2025 tentang Kontrak Kinerja Pemimpin BLU', '2025-03-24', 'eksternal', 'PER-6/PB/2025 (ditetapkan 18 Maret 2025)',
                'Kontrak Kinerja memuat indikator aspek layanan & keuangan dengan trajektori, Definisi Operasional, dan laporan capaian paling lambat 15 hari kerja setelah periode trajektori. Insentif kelebihan capaian mensyaratkan, antara lain, tidak ada indikator di bawah 65%, target PNBP tercapai, surplus EBITDA, dan opini WTP.',
                'Kontrak 2025 di-adendum (Pasal 21); siklus pelaporan triwulanan di Ruci BSC.'],
            ['Kinerja keuangan BLU 2025 (LK audited)', '2026-05-05', 'internal', self::LK,
                'Pendapatan operasional Rp399,9 M; surplus operasional Rp39,7 M (2024: defisit Rp2,3 M); EBITDA ±Rp71,9 M; PNBP Rp219,5 M atau 99,48% dari anggaran Rp220,7 M; penyerapan belanja 94,31%; opini WTP.',
                'Target PNBP belum tercapai sehingga syarat insentif huruf d tidak terpenuhi; pendapatan kerja sama baru 29% dari estimasi.'],
            ['Perjanjian Kinerja 2025', '2025-01-22', 'internal', self::PK,
                'Sasaran: mutu pendidikan, riset, kerja sama, dan tata kelola; anggaran Rp390,2 M (rupiah murni Rp270,9 M & PNBP/BLU Rp119,3 M).',
                'Target layanan PK menjadi dasar indikator aspek layanan Kontrak Kinerja.'],
            ['RSB 2020–2024 & arah Renstra 2025–2029', '2025-01-15', 'internal', 'RSB UIN Sunan Kalijaga 2020–2024',
                'Pendaftar naik dari 69.768 (2017) ke 104.748 (2019) dengan daya tampung ±6 ribu; PNBP 2017–2019 naik dari Rp95,8 M ke Rp135,3 M; target rasio PNBP/biaya operasional 60% pada 2024.',
                'Fokus berikutnya: kemandirian keuangan, optimalisasi aset, Kampus 2 Pajangan, dan transformasi menuju PTNBH.'],
            ['Persaingan perguruan tinggi di Yogyakarta', '2026-01-20', 'eksternal', 'Analisis manajemen (ilustrasi)',
                'Kota pendidikan dengan PTN & PTS besar; mahasiswa membandingkan biaya, akreditasi, dan prospek kerja.',
                'Diferensiasi: integrasi keilmuan, inklusi, dan prodi unggul.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-01-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Arah kebijakan pendidikan tinggi keagamaan', 'Kementerian Agama mendorong PTKIN unggul, moderasi beragama, dan transformasi kelembagaan.', 'peluang', 4, 4, 'naik', 'Selaraskan Kontrak Kinerja dengan Renstra Kemenag.'],
            ['politik', 'Efisiensi belanja negara', 'Kebijakan efisiensi belanja K/L membatasi rupiah murni untuk operasional dan perjalanan dinas.', 'ancaman', 4, 4, 'naik', 'Perkuat PNBP non-UKT dan belanja berbasis kinerja.'],
            ['ekonomi', 'Daya beli keluarga mahasiswa', 'Sensitivitas UKT; kebutuhan beasiswa & KIP-Kuliah tetap tinggi.', 'ancaman', 4, 3, 'stabil', 'Kelompok UKT berjenjang & beasiswa mitra.'],
            ['ekonomi', 'Pasar MICE & properti Yogyakarta', 'Permintaan gedung pertemuan, penginapan, dan pelatihan meningkat.', 'peluang', 3, 4, 'naik', 'Optimalisasi convention hall, hotel, dan pusat pelatihan.'],
            ['sosial', 'Tuntutan kampus inklusif', 'Masyarakat menuntut akses pendidikan bagi penyandang disabilitas dan kelompok afirmasi.', 'peluang', 4, 4, 'naik', 'Pertahankan keunggulan layanan difabel.'],
            ['sosial', 'Relevansi lulusan dengan dunia kerja', 'Pengguna lulusan meminta kompetensi bersertifikat dan pengalaman praktik.', 'ancaman', 4, 4, 'naik', 'Sertifikasi kompetensi lulusan & magang.'],
            ['teknologi', 'Transformasi digital pembelajaran', 'Pembelajaran daring/hibrida dan AI mengubah model pengajaran.', 'peluang', 4, 5, 'naik', 'LMS, kampus digital, literasi AI.'],
            ['teknologi', 'Keamanan siber data akademik', 'Data mahasiswa & keuangan menjadi sasaran serangan.', 'ancaman', 4, 3, 'naik', 'Pengamanan sistem & pelindungan data pribadi.'],
            ['lingkungan', 'Kampus hijau & kampus 2', 'Pengembangan Kampus 2 Pajangan memerlukan rencana lingkungan.', 'peluang', 3, 3, 'stabil', 'Masterplan kampus hijau.'],
            ['hukum', 'PER-6/PB/2025 Kontrak Kinerja Pemimpin BLU', 'Kewajiban trajektori, Definisi Operasional, dan laporan capaian berkala; dasar insentif kelebihan capaian.', 'ancaman', 5, 5, 'naik', 'Siklus pelaporan triwulanan dan dokumen pendukung terarsip.'],
            ['hukum', 'Rencana perubahan status ke PTNBH', 'Transformasi kelembagaan membutuhkan kesiapan tata kelola & keuangan.', 'peluang', 4, 3, 'naik', 'Peta jalan kesiapan PTNBH.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => 'RSB 2020–2024, LK 2025 & analisis manajemen (ilustrasi demo)']);
        }

        foreach ([
            ['persaingan', 'Persaingan PTN, PTKIN, dan PTS', 'PTN besar dan PTS ternama di DIY bersaing merebut calon mahasiswa unggul.', 4, 'Diferensiasi integrasi keilmuan & inklusi; promosi prodi unggul.'],
            ['kekuatan_pembeli', 'Calon mahasiswa & orang tua', 'Pilihan luas; sensitif biaya dan reputasi.', 3, 'UKT berjenjang, beasiswa, layanan karier.'],
            ['kekuatan_pemasok', 'Dosen bergelar doktor & tenaga ahli', 'Persaingan merekrut dosen berkualifikasi tinggi.', 3, 'Skema remunerasi BLU & pengembangan karier.'],
            ['pendatang_baru', 'Kampus baru & kelas jarak jauh', 'Program daring dan kampus cabang memperluas pilihan.', 2, 'Program hibrida bermutu.'],
            ['produk_pengganti', 'Kursus sertifikasi & bootcamp', 'Jalur sertifikasi singkat menggantikan sebagian kebutuhan gelar.', 3, 'Microcredential & sertifikasi kompetensi terintegrasi kurikulum.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Mahasiswa sarjana (S1)', 'Pendidikan', 'Layanan pendidikan utama; sumber terbesar pendapatan jasa layanan (UKT).', 82, 'stabil', true, 'Mutu pengajaran, UKT terjangkau, fasilitas, prospek kerja.', 'Prodi unggul & layanan karier.'],
            ['Mahasiswa pascasarjana', 'Pendidikan', 'Magister & doktor, termasuk dosen PTKIN lain.', 10, 'naik', true, 'Pembimbing bereputasi, publikasi, jejaring.', 'Kelas internasional & kolaborasi riset.'],
            ['Mitra kerja sama (pemerintah daerah, industri)', 'Kerja sama', 'Pelatihan, riset terapan, dan layanan konsultasi.', 2, 'naik', true, 'Solusi tepat guna, pelaporan rapi.', 'Pusat kerja sama satu pintu.'],
            ['Pengguna aset (MICE, penginapan, sewa)', 'Aset', 'Gedung pertemuan, hotel, ruang komersial.', 4, 'naik', true, 'Fasilitas siap pakai, harga bersaing.', 'Pengelolaan profesional unit bisnis.'],
            ['Mahasiswa berkebutuhan khusus & afirmasi', 'Inklusi', 'Mahasiswa difabel, KIP-Kuliah, daerah 3T.', 2, 'naik', true, 'Aksesibilitas & pendampingan.', 'Layanan inklusi terpadu.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan ilustratif untuk demo']);
        }

        foreach ([
            ['UIN Sunan Kalijaga Yogyakarta', 'PTKIN BLU', 1, 399.9, 2.5, 10.0, true, 'PTKIN tertua, integrasi keilmuan, layanan inklusi, kepercayaan masyarakat.', 'Manajemen SDM kurang sistematis, gedung terbatas (RSB), PNBP non-UKT kecil.'],
            ['PTN besar di DIY', 'PTN', 2, null, null, null, false, 'Reputasi & pendanaan riset besar.', 'Biaya & seleksi ketat.'],
            ['PTS Islam ternama di DIY', 'PTS', 3, null, null, null, false, 'Kampus modern, kerja sama industri.', 'Biaya lebih tinggi.'],
            ['PTKIN lain di Jawa', 'PTKIN', 4, null, null, null, false, 'Bidang kajian serupa, biaya bersaing.', 'Skala & reputasi bervariasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'TA 2025 (LK audited)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Pendapatan operasional LO dalam Rp miliar; margin = surplus LO ÷ pendapatan (data demo).' : 'Pembanding kualitatif untuk demo.']);
        }

        foreach ([
            ['Kontrak Kinerja Pemimpin BLU', 'Direktorat Jenderal Perbendaharaan', 'PER-6/PB/2025', 'Kinerja BLU', '2025-03-18', 'Pedoman penyusunan Kontrak Kinerja dan penetapan persetujuan capaian kinerja Pemimpin BLU.', 'Dasar seluruh siklus Kontrak Kinerja di aplikasi ini.', $year === 2025 ? 'sebagian' : 'patuh', 'strategi', $year === 2025 ? '2025-04-01' : null],
            ['Pengelolaan Keuangan BLU', 'Pemerintah', 'PP No. 23/2005 jo. PP No. 74/2012', 'Keuangan BLU', null, 'Fleksibilitas pengelolaan keuangan BLU, tarif layanan, dan remunerasi.', 'Dasar pola pengelolaan keuangan.', 'patuh', 'cfo', null],
            ['Pedoman Pengelolaan BLU', 'Kementerian Keuangan', 'PMK No. 129/PMK.05/2020', 'Keuangan BLU', null, 'Perencanaan, pelaksanaan, pelaporan, dan evaluasi kinerja BLU.', 'Rencana Bisnis & Anggaran, laporan keuangan, penilaian kinerja.', 'patuh', 'cfo', null],
            ['Pendidikan Tinggi', 'Pemerintah', 'UU No. 12/2012', 'Akademik', null, 'Penyelenggaraan Tri Dharma, otonomi perguruan tinggi, akreditasi.', 'Dasar penyelenggaraan dan rencana PTNBH.', 'patuh', 'akademik', null],
            ['Standar Nasional Pendidikan Tinggi & penjaminan mutu', 'Kementerian Pendidikan Tinggi', 'Permendikbudristek No. 53/2023', 'Mutu', '2023-08-18', 'SPMI dan akreditasi program studi.', 'Indikator SPMI & akreditasi Kontrak Kinerja.', $year === 2025 ? 'sebagian' : 'patuh', 'lpm', null],
            ['Pelindungan data pribadi', 'Pemerintah', 'UU No. 27/2022', 'Teknologi', '2024-10-17', 'Data mahasiswa, pegawai, dan keuangan.', 'Sanksi administratif & reputasi.', 'sebagian', 'ti', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'mahasiswa' => ['Mahasiswa & orang tua', 'eksternal', 'Penerima layanan', 4, 5, 'Pembelajaran bermutu, UKT terjangkau, layanan cepat.', 'Survei kepuasan, forum mahasiswa, layanan pengaduan.', 'Wakil Rektor III'],
            'kemenag' => ['Ditjen Pendidikan Islam (Pembina Teknis)', 'eksternal', 'Pembina Teknis', 5, 5, 'Capaian indikator layanan & kepatuhan kebijakan.', 'Persetujuan Definisi Operasional & laporan capaian triwulanan.', 'Biro AUPK'],
            'djpb' => ['Ditjen Perbendaharaan (Pembina Keuangan)', 'eksternal', 'Pembina Keuangan', 5, 4, 'Kontrak Kinerja, laporan capaian tepat waktu, kinerja keuangan BLU.', 'Pembahasan Kontrak Kinerja & verifikasi capaian.', 'Wakil Rektor II'],
            'dewas' => ['Dewan Pengawas BLU', 'internal', 'Pengawas', 5, 5, 'Pengawasan kinerja & tata kelola.', 'Rapat Dewan Pengawas triwulanan.', 'Sekretariat Dewas'],
            'dosen' => ['Dosen (463 orang)', 'internal', 'SDM', 4, 5, 'Karier, riset, remunerasi adil.', 'Senat, forum fakultas.', 'Bagian Kepegawaian'],
            'tendik' => ['Tenaga kependidikan (755 orang)', 'internal', 'SDM', 3, 4, 'Pengembangan kompetensi & kesejahteraan.', 'Pelatihan & sertifikasi.', 'Bagian Kepegawaian'],
            'mitra' => ['Mitra kerja sama dalam & luar negeri', 'eksternal', 'Mitra', 3, 4, 'Kerja sama berdampak & dilaporkan.', 'Pusat kerja sama.', 'Wakil Rektor III'],
            'alumni' => ['Alumni & pengguna lulusan', 'eksternal', 'Pengguna', 3, 4, 'Lulusan kompeten & berintegritas.', 'Tracer study & forum pengguna.', 'Pusat Karier'],
            'bpk' => ['Auditor (BPK, KAP, Itjen)', 'eksternal', 'Regulator', 4, 3, 'Laporan keuangan andal.', 'Tindak lanjut temuan tepat waktu.', 'SPI'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'pnbp' => ['Target PNBP 2025 belum tercapai', 'PNBP Rp219,5 M = 99,48% dari anggaran Rp220,7 M (LK 2025); syarat insentif huruf d tidak terpenuhi.', 'keuangan', 5, 5, 'cfo', ['djpb', 'dewas']],
            'kerjasama' => ['Pendapatan kerja sama jauh di bawah estimasi', 'Rp0,77 M dari estimasi Rp2,65 M (±29%); indikator di bawah ambang 65%.', 'keuangan', 5, 4, 'kemahasiswaan', ['mitra', 'djpb']],
            'aset' => ['Optimalisasi aset & unit bisnis', 'Pendapatan sewa/aset Rp6,4 M dari estimasi Rp9,0 M; unit bisnis belum optimal (RSB).', 'proses', 4, 4, 'bisnis', ['dewas']],
            'sdm' => ['Sertifikasi kompetensi tendik', 'Manajemen SDM kurang sistematis (SWOT RSB); target sertifikasi tendik belum tercapai.', 'sdm', 4, 4, 'sdm', ['tendik']],
            'kontrak' => ['Disiplin siklus Kontrak Kinerja BLU', 'Laporan capaian harus disampaikan ≤ 15 hari kerja setelah triwulan beserta hasil verifikasi internal dan dokumen pendukung.', 'proses', 5, 5, 'strategi', ['djpb', 'kemenag']],
            'gedung' => ['Keterbatasan gedung & Kampus 2', 'Gedung perkuliahan terbatas (RSB); Kampus 2 Pajangan dalam perencanaan.', 'proses', 3, 5, 'cfo', ['mahasiswa', 'kemenag']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => 'LK 2025, RSB 2020–2024 & analisis manajemen (data demo)', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'PTKIN tertua & reputasi integrasi keilmuan', 'Sejarah sejak 1964; paradigma integrasi-interkoneksi (RSB).', 0.14, 4],
            ['S', 'Akreditasi institusi & prodi kuat', 'Sebagian besar prodi terakreditasi Unggul/A (RSB).', 0.12, 4],
            ['S', 'Keuangan BLU sehat', 'Surplus operasional Rp39,7 M, kas BLU Rp69,3 M, opini WTP (LK 2025).', 0.10, 4],
            ['W', 'Manajemen SDM kurang sistematis', 'Butir SWOT RSB 2020–2024.', 0.10, 2],
            ['W', 'Gedung & ruang kuliah terbatas', 'Butir SWOT RSB; Kampus 2 belum terbangun.', 0.08, 2],
            ['W', 'Pendapatan non-UKT kecil', 'Kerja sama & aset di bawah estimasi (LK 2025).', 0.10, 2],
            ['O', 'Yogyakarta kota pendidikan', 'Pendaftar ±93–105 ribu per tahun (RSB).', 0.12, 4],
            ['O', 'Kepercayaan masyarakat & alumni', 'Jejaring alumni luas.', 0.08, 3],
            ['T', 'Persaingan perguruan tinggi', 'PTN/PTS besar di DIY (RSB).', 0.10, 2],
            ['T', 'Persaingan SDM & bisnis non-akademik', 'Butir ancaman RSB.', 0.06, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => 'RSB 2020–2024 & LK 2025']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Optimalisasi aset untuk PNBP non-UKT', 'Convention hall, hotel, Suka Water, dan pusat pelatihan dikelola profesional.', 5, ['aset', 'pnbp']],
            ['SO', 'Kerja sama tri dharma yang menghasilkan pendapatan', 'Pusat kerja sama satu pintu dengan target pendapatan per fakultas.', 5, ['kerjasama']],
            ['WO', 'Kampus 2 Pajangan & kampus digital', 'Tambah kapasitas melalui pembangunan bertahap dan pembelajaran hibrida.', 4, ['gedung']],
            ['WT', 'Manajemen talenta & sertifikasi SDM', 'Peta kompetensi, sertifikasi dosen & tendik.', 4, ['sdm']],
            ['ST', 'Siklus Kontrak Kinerja berbasis bukti', 'Verifikasi SPI, dokumen pendukung, laporan triwulanan tepat waktu.', 5, ['kontrak', 'pnbp']],
        ] as [$type, $title, $desc, $prio, $iss]) {
            $t = TowsStrategy::create(['type' => $type, 'title' => $title, 'description' => $desc, 'priority' => $prio, 'status' => 'dipilih']);
            $t->issues()->sync(collect($iss)->map(fn ($k) => $issues[$k]->id));
        }
    }

    // ------------------------------------------------------------------ peta strategi

    protected function strategy(int $year): array
    {
        $themes = [];
        foreach ([
            'T1' => ['Keunggulan Akademik & Lulusan', 'Mutu pendidikan, akreditasi, dan daya saing lulusan.', '#1F6E4A'],
            'T2' => ['Riset, Inovasi & Kemitraan Berdampak', 'Publikasi bereputasi, SDGs, dan kerja sama tri dharma.', '#2A6F9E'],
            'T3' => ['Kemandirian Keuangan BLU', 'PNBP tumbuh, aset produktif, belanja efisien dan akuntabel.', '#C88A12'],
            'T4' => ['Tata Kelola, SDM & Transformasi Kelembagaan', 'GUG, SDM tersertifikasi, kampus digital, Kampus 2, menuju PTNBH.', '#6B4FA0'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Akreditasi Unggul & internasional untuk prodi prioritas', 'produk', 'Target PK: 65% prodi Unggul/terakreditasi.'],
            ['T1', 'Sertifikasi kompetensi lulusan', 'produk', 'Relevansi lulusan dengan dunia kerja.'],
            ['T2', 'Kerja sama satu pintu yang ditindaklanjuti tri dharma', 'pasar', 'Pendapatan kerja sama 2025 hanya ±29% estimasi.'],
            ['T3', 'Optimalisasi aset & unit bisnis', 'operasi', 'Inisiatif RSB: hotel, convention hall, Suka Water.'],
            ['T4', 'Siklus Kontrak Kinerja & SPMI terintegrasi', 'operasi', 'PER-6/PB/2025.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T3', 'Kemandirian pendapatan BLU', 'PNBP tumbuh dan sumber non-UKT (kerja sama, aset) meningkat.', 'cfo', 3],
            'F2' => ['keuangan', 'T3', 'Pengelolaan keuangan efisien & akuntabel', 'Surplus EBITDA, penyerapan tepat waktu, likuiditas sehat, opini WTP.', 'cfo', 3],
            'C1' => ['pelanggan', 'T1', 'Akses pendidikan tinggi yang luas & inklusif', 'Mahasiswa bertambah, termasuk mahasiswa berkebutuhan khusus & afirmasi.', 'kemahasiswaan', 2],
            'C2' => ['pelanggan', 'T1', 'Lulusan berdaya saing', 'Lulusan cepat bekerja/berwirausaha dan bersertifikat kompetensi.', 'kemahasiswaan', 2],
            'C3' => ['pelanggan', 'T2', 'Kemitraan yang dipercaya', 'Kerja sama dalam & luar negeri ditindaklanjuti dalam tri dharma.', 'kemahasiswaan', 1.5],
            'P1' => ['proses', 'T1', 'Mutu akademik terjamin', 'Akreditasi prodi unggul dan SPMI berjalan di semua unit.', 'lpm', 2],
            'P2' => ['proses', 'T2', 'Riset & publikasi berdampak', 'Publikasi bereputasi yang disitasi dan karya ilmiah terkait SDGs.', 'lppm', 2],
            'P3' => ['proses', 'T4', 'Akuntabilitas kinerja & anggaran', 'Nilai SAKIP dan Nilai Kinerja Anggaran meningkat.', 'strategi', 1.5],
            'P4' => ['proses', 'T3', 'Aset & unit bisnis produktif', 'Utilisasi fasilitas komersial meningkat.', 'bisnis', 1.5],
            'L1' => ['sdm', 'T4', 'SDM kompeten & tersertifikasi', 'Dosen dan tendik bersertifikat kompetensi.', 'sdm', 1.5],
            'L2' => ['sdm', 'T1', 'Literasi keagamaan & moderasi', 'Literasi keagamaan terintegrasi di program studi.', 'akademik', 1],
            'L3' => ['sdm', 'T4', 'Layanan digital & kepuasan sivitas', 'Sistem layanan terintegrasi dan kepuasan mahasiswa tinggi.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P2', 2], ['L2', 'P1', 2], ['L2', 'C2', 2], ['L3', 'P3', 2], ['L3', 'C1', 2],
            ['P1', 'C1', 3], ['P1', 'C2', 3], ['P2', 'C3', 2], ['P2', 'C2', 1], ['P3', 'F2', 3], ['P4', 'F1', 3],
            ['C1', 'F1', 3], ['C2', 'C1', 2], ['C3', 'F1', 2], ['F1', 'F2', 2],
        ] as [$s, $t, $str]) {
            ObjectiveRelation::create(['source_objective_id' => $obj[$s]->id, 'target_objective_id' => $obj[$t]->id, 'strength' => $str]);
        }

        return [$themes, $obj];
    }

    // ------------------------------------------------------------------ Indikator Kinerja

    protected function kpiDefs(): array
    {
        $lt = true;
        $il = false;
        $q = 'kuartalan';
        $y = 'tahunan';

        // kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, PJ, rumus, sumber, 2024, target 2025, realisasi 2025, target 2026, faktor 2026, dari LK/PK?, trajektori
        return [
            ['F1.1', 'F1', 'Realisasi Penerimaan Negara Bukan Pajak (PNBP) BLU', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Realisasi pendapatan BLU (LRA) kumulatif', 'LRA BLU / SAKTI', 206997, 220654, 219502, 232000, .985, $lt, $q],
            ['F1.2', 'F1', 'Pendapatan jasa layanan pendidikan dari masyarakat', 'Rp juta', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan jasa layanan (UKT, biaya pendidikan lain)', 'Laporan Operasional BLU', 193323, 205000, 205625, 216000, .99, $lt, $q],
            ['F1.3', 'F1', 'Pendapatan hasil kerja sama BLU', 'Rp juta', 'higher', 'sum', 0, true, false, 'kemahasiswaan', 'Pendapatan kerja sama dengan pihak lain', 'LRA BLU', 2146, 2649, 766, 2400, .60, $lt, $q],
            ['F1.4', 'F1', 'Pendapatan pengelolaan aset (sewa, hotel, convention hall)', 'Rp juta', 'higher', 'sum', 0, true, false, 'bisnis', 'Pendapatan sewa & pemanfaatan aset BLU', 'LRA BLU', 6100, 9043, 6377, 8000, .94, $lt, $q],
            ['F1.5', 'F1', 'Rasio pendapatan BLU terhadap biaya operasional (POBO)', '%', 'higher', 'last', 1, true, false, 'cfo', 'Pendapatan non-APBN ÷ (beban operasional − penyusutan − bantuan sosial) × 100', 'Laporan Operasional BLU', 66.5, 70.0, 71.1, 72.0, 1.0, $lt, $q],
            ['F2.1', 'F2', 'EBITDA (surplus operasional + penyusutan & amortisasi)', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Surplus/defisit operasional + beban penyusutan & amortisasi', 'Laporan Operasional BLU', 23539, 45000, 71946, 60000, 1.03, $lt, $q],
            ['F2.2', 'F2', 'Penyerapan anggaran belanja (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja ÷ pagu belanja × 100', 'LRA / OM-SPAN', 95.1, 96.0, 94.31, 96.0, .985, $lt, $q],
            ['F2.3', 'F2', 'Rasio kas (cash ratio)', 'kali', 'higher', 'last', 2, true, false, 'cfo', '(Kas & setara kas) ÷ kewajiban jangka pendek', 'Neraca BLU', 2.80, 3.00, 3.16, 3.00, 1.02, $lt, $q],
            ['F2.4', 'F2', 'Opini atas Laporan Keuangan BLU (4 = WTP)', 'skor', 'higher', 'last', 0, true, true, 'cfo', 'WTP = 4, WDP = 3, TW = 2, TMP = 1', 'Laporan auditor independen', 4, 4, 4, 4, 1.0, $lt, $y],
            ['C1.1', 'C1', 'Persentase peningkatan jumlah mahasiswa', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', '(Mahasiswa aktif tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'PDDikti / SIA', 12.0, 20.0, 17.6, 20.0, .95, $il, $q],
            ['C1.2', 'C1', 'Persentase mahasiswa berkebutuhan khusus & afirmasi', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Mahasiswa berkebutuhan khusus/afirmasi ÷ mahasiswa baru × 100', 'SIA & Pusat Layanan Difabel', 5.8, 7.0, 7.4, 7.5, 1.03, $il, $q],
            ['C2.1', 'C2', 'Persentase lulusan bekerja/berwirausaha ≤ 6 bulan', '%', 'higher', 'last', 1, false, true, 'kemahasiswaan', 'Lulusan bekerja/berwirausaha ≤ 6 bulan ÷ responden tracer × 100', 'Tracer study', 16.5, 19.0, 20.3, 22.0, .97, $il, $q],
            ['C2.2', 'C2', 'Persentase lulusan bersertifikat kompetensi', '%', 'higher', 'last', 1, false, false, 'akademik', 'Lulusan bersertifikat kompetensi ÷ total lulusan × 100', 'SIA & LSP', 0.6, 1.0, 1.4, 3.0, .82, $il, $q],
            ['C3.1', 'C3', 'Persentase kerja sama yang ditindaklanjuti dalam tri dharma', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Naskah kerja sama dengan kegiatan nyata ÷ naskah aktif × 100', 'Pusat Kerja Sama', 42.0, 50.0, 53.5, 55.0, 1.02, $il, $q],
            ['P1.1', 'P1', 'Persentase prodi terakreditasi Unggul/internasional', '%', 'higher', 'last', 1, false, true, 'lpm', 'Prodi Unggul/internasional ÷ total prodi × 100', 'SK BAN-PT/LAM', 58.0, 65.0, 66.7, 70.0, .99, $il, $q],
            ['P1.2', 'P1', 'Persentase prodi & lembaga yang menerapkan SPMI', '%', 'higher', 'last', 1, false, false, 'lpm', 'Unit dengan siklus PPEPP lengkap ÷ total unit × 100', 'LPM (audit mutu internal)', 82.0, 90.0, 92.0, 95.0, 1.0, $il, $q],
            ['P2.1', 'P2', 'Persentase artikel jurnal bereputasi nasional yang disitasi', '%', 'higher', 'last', 1, false, false, 'lppm', 'Artikel disitasi ÷ artikel terbit di jurnal bereputasi nasional × 100', 'SINTA / Google Scholar', 41.0, 50.0, 47.5, 52.0, .93, $il, $q],
            ['P2.2', 'P2', 'Persentase karya ilmiah terkait SDGs', '%', 'higher', 'last', 1, false, false, 'lppm', 'Karya ilmiah bertema SDGs ÷ total karya ilmiah × 100', 'LPPM / repositori', 38.0, 45.0, 48.2, 50.0, 1.03, $il, $q],
            ['P3.1', 'P3', 'Nilai Kinerja Anggaran (NKA)', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Nilai aplikasi SMART DJA', 'SMART DJA', 85.10, 83.00, 87.60, 85.00, 1.0, $il, $y],
            ['P3.2', 'P3', 'Nilai SAKIP', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Hasil evaluasi SAKIP Itjen Kemenag (BB = 70–80)', 'LHE SAKIP', 72.50, 75.00, 76.10, 78.00, 1.0, $il, $y],
            ['P4.1', 'P4', 'Utilisasi fasilitas komersial (convention hall & penginapan)', '%', 'higher', 'average', 1, true, false, 'bisnis', 'Hari terpakai ÷ hari tersedia × 100', 'Pusat Pengembangan Bisnis', 48.0, 60.0, 51.0, 60.0, .92, $il, $q],
            ['L1.1', 'L1', 'Persentase dosen bersertifikat kompetensi', '%', 'higher', 'last', 1, false, false, 'sdm', 'Dosen bersertifikat kompetensi ÷ total dosen × 100', 'SIMPEG', 21.0, 26.0, 27.4, 30.0, .98, $il, $q],
            ['L1.2', 'L1', 'Persentase tendik bersertifikat kompetensi', '%', 'higher', 'last', 1, false, false, 'sdm', 'Tendik bersertifikat kompetensi ÷ total tendik × 100', 'SIMPEG', 14.0, 20.0, 18.2, 24.0, .88, $il, $q],
            ['L2.1', 'L2', 'Persentase prodi menerapkan literasi keagamaan', '%', 'higher', 'last', 1, false, false, 'akademik', 'Prodi dengan mata kuliah/kegiatan literasi keagamaan ÷ total prodi × 100', 'Dokumen kurikulum', 80.0, 90.0, 91.5, 95.0, 1.0, $il, $q],
            ['L3.1', 'L3', 'Indeks kepuasan mahasiswa atas layanan', 'skor', 'higher', 'last', 2, false, false, 'ti', 'Survei kepuasan layanan (skala 4)', 'Survei LPM', 3.31, 3.40, 3.42, 3.50, .99, $il, 'semesteran'],
        ];
    }

    /**
     * Atribut BLU per indikator: [aspek, wajib, capaian maksimum, peran insentif, definisi, cara pengambilan data, metode pengujian, dokumen pendukung].
     *
     * @return array<string,array>
     */
    protected function bluDefs(): array
    {
        $tw = 'Kumulatif s.d. akhir triwulan; dilaporkan paling lambat 15 hari kerja setelah triwulan berakhir.';
        $th = 'Sekali setahun setelah dokumen hasil penilaian terbit.';

        return [
            'F1.1' => ['keuangan', true, 110, 'pnbp', 'Seluruh pendapatan BLU yang dicatat sebagai PNBP pada LRA (jasa layanan, hibah, kerja sama, aset, lainnya) dibandingkan target PNBP dalam RBA/DIPA.', $tw, 'Rekonsiliasi LRA BLU dengan SAKTI & rekening koran; uji petik bukti setor oleh SPI.', 'LRA BLU, rekap SAKTI, rekening koran BLU, hasil verifikasi SPI'],
            'F1.2' => ['keuangan', false, 110, null, 'Pendapatan jasa layanan pendidikan dari masyarakat (UKT dan biaya pendidikan lain) basis akrual.', $tw, 'Rekonsiliasi data pembayaran UKT dengan SIA dan Laporan Operasional.', 'Laporan Operasional, rekap pembayaran UKT per fakultas'],
            'F1.3' => ['keuangan', false, 120, null, 'Pendapatan dari perjanjian kerja sama dengan pihak lain yang telah diterima di rekening BLU.', $tw, 'Pencocokan naskah kerja sama, invoice, dan penerimaan kas.', 'Naskah kerja sama, invoice, bukti penerimaan, LRA'],
            'F1.4' => ['keuangan', false, 120, null, 'Pendapatan pemanfaatan aset BLU (sewa gedung, penginapan, convention hall, unit usaha).', $tw, 'Uji petik kontrak sewa dan bukti setor.', 'Kontrak sewa, rekap okupansi, bukti setor, LRA'],
            'F1.5' => ['keuangan', false, 110, null, 'Kemampuan pendapatan non-APBN membiayai biaya operasional BLU di luar penyusutan dan bantuan sosial.', $tw, 'Rekalkulasi dari Laporan Operasional oleh Satuan Pengawas Internal.', 'Laporan Operasional, kertas kerja perhitungan'],
            'F2.1' => ['keuangan', true, 120, 'ebitda', 'Surplus/defisit operasional ditambah beban penyusutan dan amortisasi (Pasal 14 ayat 4 huruf e).', $tw, 'Rekalkulasi dari Laporan Operasional; review SPI.', 'Laporan Operasional, rincian beban penyusutan'],
            'F2.2' => ['keuangan', false, 100, null, 'Realisasi belanja kumulatif terhadap pagu belanja DIPA BLU.', $tw, 'Pencocokan LRA dengan OM-SPAN.', 'LRA, OM-SPAN, revisi DIPA'],
            'F2.3' => ['keuangan', false, 110, null, 'Kas dan setara kas dibandingkan kewajiban jangka pendek pada akhir triwulan.', $tw, 'Rekalkulasi dari neraca triwulanan.', 'Neraca, rekening koran'],
            'F2.4' => ['keuangan', true, 100, 'opini', 'Opini auditor independen atas Laporan Keuangan BLU tahun berjalan.', $th, 'Pemeriksaan Laporan Hasil Audit.', 'Laporan auditor independen atas LK BLU'],
            'C1.1' => ['layanan', false, 120, null, 'Kenaikan jumlah mahasiswa aktif dibandingkan tahun sebelumnya (target Perjanjian Kinerja 2025).', $tw, 'Pencocokan SIA dengan PDDikti.', 'Rekap PDDikti, laporan SIA'],
            'C1.2' => ['layanan', false, 120, null, 'Mahasiswa berkebutuhan khusus dan jalur afirmasi terhadap mahasiswa baru (target PK 2025: 7%).', $tw, 'Verifikasi data Pusat Layanan Difabel & SK penerima afirmasi.', 'Data PLD, SK afirmasi, rekap SIA'],
            'C2.1' => ['layanan', true, 120, null, 'Lulusan yang bekerja atau berwirausaha dalam 6 bulan setelah lulus (target PK 2025: 19%).', $tw, 'Uji petik responden tracer study.', 'Laporan tracer study, daftar responden'],
            'C2.2' => ['layanan', false, 120, null, 'Lulusan yang memperoleh sertifikat kompetensi dari LSP/lembaga berwenang (target PK 2025: 1%).', $tw, 'Pencocokan sertifikat dengan daftar lulusan.', 'Salinan sertifikat, daftar lulusan'],
            'C3.1' => ['layanan', false, 120, null, 'Naskah kerja sama aktif yang ditindaklanjuti dengan kegiatan tri dharma (target PK 2025: 50%).', $tw, 'Uji petik laporan kegiatan kerja sama.', 'Naskah kerja sama, laporan kegiatan'],
            'P1.1' => ['layanan', true, 120, null, 'Program studi terakreditasi Unggul atau internasional terhadap total program studi (target PK 2025: 65%).', $tw, 'Pencocokan SK akreditasi.', 'SK BAN-PT/LAM, sertifikat akreditasi internasional'],
            'P1.2' => ['layanan', false, 120, null, 'Program studi dan lembaga yang menjalankan siklus SPMI (PPEPP) lengkap (target PK 2025: 90%).', $tw, 'Audit mutu internal oleh LPM.', 'Laporan AMI, dokumen PPEPP'],
            'P2.1' => ['layanan', false, 120, null, 'Artikel di jurnal bereputasi nasional yang telah disitasi (target PK 2025: 50%).', $tw, 'Penelusuran SINTA/Google Scholar.', 'Rekap SINTA, tangkapan layar sitasi'],
            'P2.2' => ['layanan', false, 120, null, 'Karya ilmiah dosen yang terkait tujuan pembangunan berkelanjutan (target PK 2025: 45%).', $tw, 'Uji petik pemetaan SDGs.', 'Rekap LPPM, repositori'],
            'P3.1' => ['penugasan', false, 110, null, 'Nilai Kinerja Anggaran dari aplikasi SMART DJA (target PK 2025: 83).', $th, 'Pencocokan dengan aplikasi SMART DJA.', 'Cetakan SMART DJA'],
            'P3.2' => ['penugasan', false, 110, null, 'Nilai evaluasi Sistem Akuntabilitas Kinerja Instansi Pemerintah (target PK 2025: 70–80 / BB).', $th, 'Pencocokan Laporan Hasil Evaluasi SAKIP.', 'LHE SAKIP'],
            'P4.1' => ['keuangan', false, 120, null, 'Tingkat pemakaian fasilitas komersial dibandingkan hari tersedia.', $tw, 'Uji petik log pemesanan.', 'Rekap pemesanan, kontrak sewa'],
            'L1.1' => ['layanan', false, 120, null, 'Dosen yang memiliki sertifikat kompetensi (target PK 2025: 26%).', $tw, 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'],
            'L1.2' => ['layanan', false, 120, null, 'Tenaga kependidikan yang memiliki sertifikat kompetensi (target PK 2025: 20%).', $tw, 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'],
            'L2.1' => ['layanan', false, 120, null, 'Program studi yang menerapkan literasi keagamaan (target PK 2025: 90%).', $tw, 'Telaah dokumen kurikulum.', 'Dokumen kurikulum, RPS'],
            'L3.1' => ['layanan', false, 110, null, 'Indeks kepuasan mahasiswa atas layanan akademik & administrasi (skala 4).', 'Semesteran; survei pada akhir semester.', 'Uji validitas instrumen & sampel survei.', 'Laporan survei LPM'],
        ];
    }

    protected function kpiExtra(string $code, int $year): array
    {
        $d = $this->bluDefs()[$code] ?? null;
        if (! $d) {
            return [];
        }
        [$aspect, $mandatory, $max, $role, $definition, $collection, $test, $documents] = $d;

        return ['aspect' => $aspect, 'is_mandatory' => $mandatory, 'max_achievement' => $max, 'incentive_role' => $role,
            'operational_definition' => $definition, 'data_collection' => $collection, 'test_method' => $test, 'supporting_documents' => $documents];
    }

    // ------------------------------------------------------------------ Kontrak Kinerja

    /** @param array<string,Kpi> $kpis */
    protected function afterKpis(int $year, array $kpis): void
    {
        $svc = app(BluContractService::class);
        $common = ['signed_place' => 'Jakarta', 'technical_approval' => 'Surat persetujuan Dirjen Pendidikan Islam atas Indikator & Definisi Operasional (contoh)', 'created_by' => $this->id('strategi')];

        if ($year === 2025) {
            // kontrak awal disusun sebelum PER-6/PB/2025: belum memuat Definisi Operasional lengkap
            $v0 = collect($svc->snapshot())->map(fn ($i) => array_merge($i, ['collection' => null, 'test' => null, 'documents' => null]))->all();
            PerformanceContract::create($common + [
                'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'KK-01/UIN.02/2025 (contoh)', 'indicators' => $v0,
                'proposed_at' => '2024-12-27', 'discussed_at' => '2025-01-21', 'ba_number' => 'BA-01/PB.2/2025 (contoh)', 'leader_signed_at' => '2025-01-24', 'dg_signed_at' => '2025-01-30',
                'legal_case_free' => true, 'other_requirements_met' => true, 'notes' => 'DATA DEMO. Kontrak Kinerja awal 2025 (simulasi).',
            ]);
            $previous = PerformanceContract::where('version', 0)->first();
            PerformanceContract::create($common + [
                'version' => 1, 'kind' => 'adendum', 'status' => 'ditandatangani', 'number' => 'KK-01.A/UIN.02/2025 (contoh)', 'basis' => 'peralihan',
                'reason' => 'Penyesuaian Kontrak Kinerja 2025 dengan PER-6/PB/2025: melengkapi aspek indikator, trajektori triwulanan, Definisi Operasional (cara pengambilan data, metode pengujian, dokumen pendukung), dan indikator wajib.',
                'indicators' => $svc->snapshot($previous), 'proposed_at' => '2025-03-27', 'discussed_at' => '2025-03-27', 'leader_signed_at' => '2025-03-27', 'dg_signed_at' => '2025-03-28',
                'legal_case_free' => true, 'other_requirements_met' => true, 'incentive_notes' => 'Tidak ada persyaratan tambahan dari Pembina Teknis (contoh).',
                'notes' => 'DATA DEMO. Adendum peralihan Pasal 21 PER-6/PB/2025, paling lambat 10 hari kerja setelah 18 Maret 2025.',
            ]);

            return;
        }

        $signed = PerformanceContract::create($common + [
            'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'KK-01/UIN.02/2026 (contoh)', 'indicators' => $svc->snapshot(),
            'proposed_at' => '2025-12-30', 'discussed_at' => '2026-01-23', 'ba_number' => 'BA-07/PB.2/2026 (contoh)', 'leader_signed_at' => '2026-01-28', 'dg_signed_at' => '2026-01-30',
            'legal_case_free' => true, 'notes' => 'DATA DEMO. Proyeksi Kontrak Kinerja 2026.',
        ]);

        // usulan perubahan (Pasal 19 ayat 1 huruf c): target peningkatan mahasiswa diturunkan karena penetapan daya tampung oleh Pembina Teknis
        $changed = collect($svc->snapshot($signed))->map(function ($i) {
            if ($i['code'] !== 'C1.1') {
                return $i;
            }
            $i['targets'] = [3 => 13.5, 6 => 14.0, 9 => 14.5, 12 => 15.0];
            $i['annual'] = 15.0;
            $i['change'] = 'target_turun';
            $i['previous_annual'] = 20.0;

            return $i;
        })->all();
        PerformanceContract::create($common + [
            'version' => 1, 'kind' => 'perubahan', 'status' => 'diusulkan', 'number' => null, 'basis' => 'perubahan_kebijakan', 'indicators' => $changed,
            'reason' => 'Simulasi: Pembina Teknis menetapkan daya tampung mahasiswa baru 2026/2027 lebih rendah dari rencana sehingga target C1.1 diusulkan turun dari 20% menjadi 15%. Bila disetujui, capaian C1.1 paling tinggi 100% (Pasal 19 ayat 8).',
            'proposed_at' => '2026-09-29', 'technical_approval' => 'Surat persetujuan Dirjen Pendidikan Islam atas perubahan target (contoh)',
            'notes' => 'DATA DEMO. Menunggu pembahasan dengan DJPb; tenggat penandatanganan 1 Desember.',
        ]);
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Gedung Rektorat Lama, Kampus UIN Sunan Kalijaga';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja 2025', 'review_type' => 'tahunan', 'review_date' => '2026-01-14', 'period_label' => 'Tahun 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Dekan, Kepala Biro, Ketua Lembaga, SPI',
                'summary' => "PNBP Rp219,5 M (99,48% target) sehingga syarat insentif PNBP tidak terpenuhi.\nEBITDA ±Rp71,9 M, surplus operasional Rp39,7 M (2024: defisit).\nPendapatan kerja sama ±29% estimasi: di bawah ambang 65%.\nIndikator layanan PK 2025 sebagian besar tercapai (angka simulasi).",
                'decisions' => "1. Target 2026: PNBP Rp232 M, kerja sama Rp2,4 M, utilisasi fasilitas 60%.\n2. Pusat kerja sama satu pintu & target pendapatan per fakultas.\n3. Laporan capaian triwulanan wajib lengkap dengan dokumen pendukung sebelum tanggal 10.",
                'follow_up' => 'Bagian Perencanaan menyiapkan usulan Kontrak Kinerja 2026 (sudah diusulkan 30 Desember 2025).', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Pengawas meminta rencana pemulihan pendapatan kerja sama dan pemantauan PNBP per triwulan.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Pimpinan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
                'summary' => match ($m) {
                    2 => 'Pembayaran UKT semester genap selesai; PNBP Februari sesuai pola musiman.',
                    3 => 'Triwulan I ditutup; laporan capaian disiapkan untuk DJPb & Ditjen Pendis.',
                    6 => 'Triwulan II: pendapatan kerja sama masih ±60% target; utilisasi fasilitas 55%.',
                    8 => 'Daftar ulang mahasiswa baru; indikasi daya tampung lebih rendah dari rencana.',
                    default => 'Tinjauan indikator merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Laporan capaian TW I dikirim paling lambat 17 April 2026.',
                    6 => 'Percepat penagihan kerja sama & paket MICE semester II.',
                    8 => 'Siapkan kajian usulan perubahan Kontrak Kinerja (Pasal 19) sebelum 30 September.',
                    default => 'Lanjutkan rencana aksi; penanggung jawab IK merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-15', 'Triwulan I 2026'], [2, '2026-07-15', 'Triwulan II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Pimpinan, SPI, Dewan Pengawas (pengamat)',
                'summary' => $q === 1 ? 'PNBP TW I 98% trajektori; kerja sama 58%; indikator layanan sesuai trajektori.' : 'PNBP s.d. TW II 98,5%; kerja sama di bawah 65% (peringatan insentif); tendik bersertifikat tertinggal.',
                'decisions' => $q === 1 ? 'SPI memverifikasi dokumen pendukung sebelum laporan dikirim.' : 'Program sertifikasi tendik gelombang 2; satgas pendapatan kerja sama.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['dewas'] = PerformanceReview::create([
            'title' => 'Rapat Dewan Pengawas — Semester I 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-24', 'period_label' => 'Semester I 2026',
            'chair' => 'Ketua Dewan Pengawas', 'venue' => $venue, 'participants' => 'Dewan Pengawas, Rektor, Wakil Rektor II, SPI',
            'summary' => 'Dewas menyoroti indikator di bawah 65% (pendapatan kerja sama) yang menggugurkan syarat insentif huruf c.',
            'decisions' => 'Rektor melaporkan rencana pemulihan pendapatan kerja sama setiap bulan.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['dewas']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon simulasi dampak usulan perubahan target terhadap capaian total dan syarat insentif sebelum diajukan ke DJPb.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Pimpinan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
            'summary' => 'Realisasi Triwulan III sebagian masih draf; data tracer dikembalikan untuk validasi. Laporan TW III berisiko melewati tenggat 21 Oktober.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi TW III disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo; total belanja 2025 mengacu LRA Rp384,8 M.';

            return [
                'defs' => [
                    'adendum' => ['UIN-25-01', 'Adendum Kontrak Kinerja sesuai PER-6/PB/2025', 'Menyusun aspek indikator, trajektori, dan Definisi Operasional bersama Pembina Teknis & DJPb.', 'T4', 'strategi', '2025-03-18', '2025-04-01', 'selesai', 5, 5, 2, 1, ['P3', 'F2'], 'Adendum ditandatangani tepat waktu.'],
                    'akreditasi' => ['UIN-25-02', 'Percepatan akreditasi Unggul prodi', 'Pendampingan borang & visitasi.', 'T1', 'lpm', '2025-01-15', '2025-12-15', 'selesai', 5, 4, 3, 3, ['P1'], '65% prodi Unggul/internasional.'],
                    'aset' => ['UIN-25-03', 'Optimalisasi aset & unit bisnis', 'Tata kelola convention hall, penginapan, Suka Water.', 'T3', 'bisnis', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 3, 3, ['P4', 'F1'], 'Pendapatan aset Rp9 M.'],
                    'sertifikasi' => ['UIN-25-04', 'Sertifikasi kompetensi dosen & tendik', 'Kerja sama LSP dan pelatihan.', 'T4', 'sdm', '2025-03-01', '2025-11-30', 'selesai', 4, 3, 2, 2, ['L1'], '26% dosen & 20% tendik bersertifikat.'],
                ],
                'activities' => [
                    ['adendum', 'Penyusunan Definisi Operasional', 'strategi', '2025-03-18', '2025-03-25', 100], ['adendum', 'Penandatanganan adendum', 'dirut', '2025-03-26', '2025-03-28', 100],
                    ['akreditasi', 'Pendampingan borang & visitasi', 'lpm', '2025-01-15', '2025-12-15', 100],
                    ['aset', 'Pemasaran paket MICE', 'bisnis', '2025-02-01', '2025-12-31', 100],
                    ['sertifikasi', 'Uji kompetensi tendik', 'sdm', '2025-03-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['adendum', 'Adendum diterima DJPb', '2025-03-28', true], ['akreditasi', 'SK akreditasi gelombang 2025 terbit', '2025-12-15', true],
                ],
                'budgets' => [
                    ['akreditasi', 'Pendampingan & visitasi akreditasi', 'opex', 3_500_000_000, 3_120_000_000, $note, 'PNBP BLU'],
                    ['aset', 'Renovasi ruang pertemuan', 'capex', 6_000_000_000, 4_850_000_000, $note, 'PNBP BLU'],
                    ['sertifikasi', 'Uji kompetensi & pelatihan', 'opex', 2_000_000_000, 1_640_000_000, $note, 'PNBP BLU'],
                ],
            ];
        }

        return [
            'defs' => [
                'kerjasama' => ['UIN-26-01', 'Pusat kerja sama satu pintu & pendapatan kerja sama', 'Katalog layanan, target per fakultas, penagihan terpusat.', 'T2', 'kemahasiswaan', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['C3', 'F1'], 'Pendapatan kerja sama Rp2,4 M.'],
                'aset' => ['UIN-26-02', 'Optimalisasi aset: convention hall, penginapan & Suka Water', 'Pengelolaan profesional & pemasaran paket MICE.', 'T3', 'bisnis', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 3, ['P4', 'F1'], 'Utilisasi 60%, pendapatan aset Rp8 M.'],
                'kampus2' => ['UIN-26-03', 'Masterplan & DED Kampus 2 Pajangan', 'Perencanaan kampus hijau dan skema pendanaan.', 'T4', 'cfo', '2026-02-01', '2027-06-30', 'berjalan', 4, 3, 4, 4, ['P1', 'C1'], 'DED disetujui & pendanaan teridentifikasi.'],
                'sertifikasi' => ['UIN-26-04', 'Sertifikasi kompetensi lulusan, dosen & tendik', 'Kerja sama LSP, microcredential terintegrasi kurikulum.', 'T1', 'akademik', '2026-01-15', '2026-12-15', 'berjalan', 4, 4, 2, 2, ['C2', 'L1'], '3% lulusan & 24% tendik bersertifikat.'],
                'kontrak' => ['UIN-26-05', 'Siklus Kontrak Kinerja & SPMI terintegrasi di Ruci BSC', 'Input realisasi, verifikasi SPI, persetujuan Rektor, cetak lampiran PER-6.', 'T4', 'strategi', '2026-01-05', '2026-12-31', 'berjalan', 5, 5, 2, 1, ['P3', 'P1'], 'Laporan triwulanan tepat waktu.'],
                'ptnbh' => ['UIN-26-06', 'Kajian kesiapan PTNBH', 'Analisis tata kelola, keuangan, dan aset menuju PTNBH.', 'T4', 'dirut', '2026-06-01', '2027-06-30', 'direncanakan', 4, 2, 3, 2, ['F2', 'P3'], 'Naskah kajian & peta jalan.'],
            ],
            'activities' => [
                ['kerjasama', 'Katalog layanan kerja sama', 'kemahasiswaan', '2026-01-15', '2026-03-31', 100], ['kerjasama', 'Target pendapatan per fakultas', 'cfo', '2026-02-01', '2026-04-30', 100],
                ['kerjasama', 'Penagihan & pelaporan terpusat', 'cfo', '2026-04-01', '2026-12-31', 55], ['kerjasama', 'Kerja sama pemda & industri baru', 'kemahasiswaan', '2026-03-01', '2026-12-31', 45],
                ['aset', 'Paket MICE & kemitraan agen', 'bisnis', '2026-01-01', '2026-06-30', 100], ['aset', 'Renovasi kamar penginapan', 'bisnis', '2026-03-01', '2026-09-30', 80],
                ['aset', 'Perluasan distribusi Suka Water', 'bisnis', '2026-04-01', '2026-12-31', 50],
                ['kampus2', 'Penyusunan masterplan', 'cfo', '2026-02-01', '2026-08-31', 100], ['kampus2', 'DED tahap 1', 'cfo', '2026-07-01', '2027-03-31', 30],
                ['sertifikasi', 'Kerja sama LSP', 'akademik', '2026-01-15', '2026-04-30', 100], ['sertifikasi', 'Uji kompetensi lulusan', 'akademik', '2026-05-01', '2026-12-15', 50],
                ['sertifikasi', 'Sertifikasi tendik gelombang 2', 'sdm', '2026-07-01', '2026-12-15', 30],
                ['kontrak', 'Kontrak Kinerja 2026 ditandatangani', 'strategi', '2026-01-05', '2026-01-30', 100], ['kontrak', 'Laporan capaian TW I & II', 'strategi', '2026-04-01', '2026-07-21', 100],
                ['kontrak', 'Laporan capaian TW III', 'strategi', '2026-10-01', '2026-10-21', 60], ['kontrak', 'Usulan perubahan Kontrak Kinerja', 'strategi', '2026-08-15', '2026-09-30', 100],
                ['ptnbh', 'Pembentukan tim kajian', 'dirut', '2026-06-01', '2026-07-31', 100], ['ptnbh', 'Analisis kesiapan', 'strategi', '2026-08-01', '2027-03-31', 20],
            ],
            'milestones' => [
                ['kontrak', 'Kontrak Kinerja 2026 ditandatangani', '2026-01-30', true], ['kontrak', 'Laporan TW II terkirim', '2026-07-21', true], ['kontrak', 'Laporan TW III terkirim', '2026-10-21', false],
                ['aset', 'Paket MICE diluncurkan', '2026-03-31', true], ['kampus2', 'Masterplan disetujui', '2026-08-31', true], ['kerjasama', 'Pendapatan kerja sama Rp1,8 M (TW III)', '2026-09-30', false],
            ],
            'budgets' => [
                ['kerjasama', 'Pemasaran & administrasi kerja sama', 'opex', 1_500_000_000, 820_000_000, null, 'PNBP BLU'],
                ['aset', 'Renovasi penginapan & convention hall', 'capex', 7_500_000_000, 5_200_000_000, null, 'PNBP BLU'],
                ['kampus2', 'Masterplan & DED Kampus 2', 'capex', 9_000_000_000, 3_100_000_000, null, 'PNBP BLU & SBSN (rencana)'],
                ['sertifikasi', 'Uji kompetensi lulusan & tendik', 'opex', 2_800_000_000, 1_250_000_000, null, 'PNBP BLU'],
                ['kontrak', 'Implementasi Ruci BSC & pelatihan', 'opex', 450_000_000, 380_000_000, null, 'PNBP BLU'],
                ['ptnbh', 'Kajian kesiapan PTNBH', 'opex', 600_000_000, 90_000_000, null, 'PNBP BLU'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Target PNBP 2025 tidak tercapai (99,48%)', 'PNBP Rp219,5 M dari target Rp220,7 M; syarat insentif Pasal 14 (4) d tidak terpenuhi.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 1152608324, 'F1.1', null, 'komisaris', '2026-01-14',
                [['Pemantauan PNBP per triwulan & proyeksi akhir tahun', 'cfo', '2026-03-31', 'selesai', 100]], ['2026-04-10', 'Pemantauan PNBP triwulanan berjalan di Ruci BSC.']],
            ['Pendapatan kerja sama di bawah 65% target', 'Rp0,77 M dari estimasi Rp2,65 M; indikator F1.3 di bawah ambang Pasal 14 (4) c.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 1882966201, 'F1.3', 'aset', 'cfo', '2026-01-14',
                [['Pusat kerja sama satu pintu', 'kemahasiswaan', '2026-06-30', 'berjalan', 70]], null],
        ] : [
            ['Pendapatan kerja sama masih di bawah ambang 65%', 'Capaian s.d. TW II ±60% trajektori; menggugurkan syarat insentif huruf c bila berlanjut.', 'timwas', 'dewas', 'keuangan', 'kritis', null, 'F1.3', 'kerjasama', 'komisaris', '2026-07-24',
                [['Satgas pendapatan kerja sama', 'kemahasiswaan', '2026-12-31', 'berjalan', 50], ['Penagihan terpusat oleh Biro AUPK', 'cfo', '2026-10-31', 'berjalan', 60]], null],
            ['Sertifikasi tendik tertinggal dari trajektori', 'Realisasi TW II 19% dari trajektori 21%.', 'tinjauan', 'q2', 'sdm', 'sedang', null, 'L1.2', 'sertifikasi', 'dirut', '2026-07-15',
                [['Sertifikasi tendik gelombang 2', 'sdm', '2026-12-15', 'berjalan', 30]], null],
            ['Dokumen pendukung laporan TW I belum lengkap', 'SPI menemukan 4 indikator layanan tanpa dokumen pendukung saat verifikasi internal.', 'audit', null, 'proses', 'sedang', null, 'P2.1', 'kontrak', 'strategi', '2026-04-10',
                [['Unggah dokumen pendukung sebelum persetujuan', 'strategi', '2026-04-15', 'selesai', 100]], ['2026-04-16', 'Dokumen pendukung dilengkapi; laporan TW I terkirim tepat waktu.']],
            ['Laporan capaian TW III berisiko terlambat', 'Realisasi TW III sebagian masih draf/dikembalikan; tenggat 21 Oktober 2026 (Pasal 10).', 'internal', 'rm9', 'proses', 'tinggi', null, 'C2.1', 'kontrak', 'strategi', '2026-10-12',
                [['Validasi tracer study & persetujuan Rektor', 'kemahasiswaan', '2026-10-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan perubahan Kontrak Kinerja oleh DJPb', 'eksternal', 'kontrak', 'P3', 'Direktorat Jenderal Perbendaharaan', 'Perubahan target C1.1 berlaku setelah ditandatangani (paling lambat 1 Desember).', 'strategi', 'tinggi', 'Kajian lengkap & persetujuan Pembina Teknis dilampirkan.'],
                ['Penetapan daya tampung oleh Pembina Teknis', 'eksternal', 'kontrak', 'C1', 'Ditjen Pendidikan Islam', 'Target peningkatan mahasiswa bergantung pada daya tampung.', 'kemahasiswaan', 'sedang', 'Koordinasi sejak perencanaan PMB.'],
                ['Pendanaan Kampus 2', 'eksternal', 'kampus2', 'P1', 'Kementerian Agama & skema SBSN', 'Pembangunan bergantung pada persetujuan pendanaan.', 'cfo', 'tinggi', 'Pentahapan & pendanaan PNBP untuk DED.'],
            ],
            'institutionalizations' => [
                ['Siklus laporan capaian Kontrak Kinerja di Ruci BSC', 'sistem', 'Input realisasi tanggal 5 → verifikasi SPI/Keuangan tanggal 7 → persetujuan Rektor tanggal 8 → kirim ke DJPb & Pendis ≤ 15 hari kerja.', 'Triwulanan', 'strategi', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Rapat Dewan Pengawas', 'rapat', 'Pengawasan capaian kinerja dan syarat insentif.', 'Triwulanan', 'dirut', 'melembaga'],
                ['Audit Mutu Internal (SPMI)', 'sop', 'Siklus PPEPP di seluruh prodi & lembaga.', 'Tahunan', 'lpm', 'melembaga'],
                ['Tracer study lulusan', 'sop', 'Survei lulusan untuk indikator C2.1.', 'Tahunan', 'kemahasiswaan', 'berjalan'],
                ['Pelatihan pengelola kinerja fakultas', 'pelatihan', 'Penanggung jawab IK fakultas dilatih menyusun Definisi Operasional & bukti.', 'Semesteran', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
