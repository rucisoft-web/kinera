<?php

namespace Database\Seeders;

use App\Models\AtmanProfile;
use App\Models\AuditLog;
use App\Models\KpiActual;
use App\Models\StrategicInitiative;
use App\Models\Capability;
use App\Models\Competitor;
use App\Models\CoreValue;
use App\Models\DataImport;
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
use App\Models\WorkPlanDraft;
use App\Services\BluContractService;
use App\Services\WorkPlanService;

/**
 * Demo satker BLU: Universitas Islam Negeri Sunan Kalijaga Yogyakarta (kode UINSUKA), bidang layanan pendidikan,
 * Pembina Teknis Direktorat Jenderal Pendidikan Islam Kementerian Agama.
 *
 * DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak:
 * - Kerangka strategi mengacu pada Renstra UIN Sunan Kalijaga 2025–2029: visi, misi, tujuan, delapan Sasaran Program
 *   beserta IKSP/IKSK, target 2026 pada Matriks Kinerja, tujuh strategi & enam lingkup transformasi, peta jalan,
 *   kerangka regulasi, dan kerangka pendanaan. Baseline 2024 diambil dari capaian 2020–2024 pada Bab I bila tersedia.
 * - Perioda 2025 (ditutup): angka keuangan dari Laporan Keuangan BLU Audited TA 2025; realisasi indikator layanan,
 *   rincian bulanan/triwulanan, dan Kontrak Kinerja adalah simulasi.
 * - Perioda 2026 (berjalan, bawaan): seluruh target & realisasi Januari–September adalah PROYEKSI/SIMULASI.
 * - Tidak memakai nama atau NIP pejabat sebenarnya; semua penandatangan bertanda "(contoh)".
 */
class UinSukaSeeder extends ShowcaseSeeder
{
    public const CODE = 'UINSUKA';

    /** Pengecualian jenis IK supaya setiap perspektif memuat campuran hasil & pendorong (bahan ajar sesi 2 dan 4). */
    public const INDICATOR_TYPE_OVERRIDES = [
        'C1.2' => 'lead', 'C2.3' => 'lead', 'C3.2' => 'lead', 'C4.2' => 'lead',
        'P1.1' => 'lag', 'P3.3' => 'lag', 'P4.1' => 'lag',
    ];

    public const LT = 'Renstra UIN Sunan Kalijaga 2025–2029 & Laporan Keuangan BLU Audited TA 2025';

    public const RENSTRA = 'Renstra UIN Sunan Kalijaga Yogyakarta 2025–2029';

    public const LK = 'Laporan Keuangan BLU UIN Sunan Kalijaga TA 2025 (audited, opini WTP)';

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
            'description' => 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Kerangka strategi mengacu pada '.self::RENSTRA.' dan angka keuangan 2025 pada '.self::LK.'; realisasi indikator layanan, rincian bulanan, Kontrak Kinerja, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Tahun pertama Renstra 2025–2029 (pematangan rencana transformasi). Angka keuangan mengacu LK BLU Audited 2025; realisasi indikator layanan ilustratif.'
            : 'DATA DEMO — PROYEKSI 2026 (tahun implementasi transformasi): Januari–Agustus disetujui, September dalam proses verifikasi; usulan perubahan Kontrak Kinerja menunggu DJPb.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['C1.2', 'C3.2', 'P3.2', 'F2.1', 'F2.3', 'P2.1', 'L1.3', 'F1.5', 'C4.1', 'C4.2', 'P1.2', 'L1.1', 'L3.1', 'F1.2', 'C3.1', 'F2.5', 'P4.3'];
    }

    protected function missingSeptember(): array
    {
        return ['L2.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Masa tunggu lulusan September masih memakai responden tracer study 2025 yang belum divalidasi. Mohon pisahkan responden baru dan lampirkan rekap tracer per fakultas.'];
    }

    protected function criticalIn2026(): array
    {
        return ['C2.3', 'P3.4'];
    }

    protected function maturityPattern(): array
    {
        return [3, 2, 3, 2, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Kinera UIN (contoh)', 'uinsuka.admin', 'Admin Sistem', 'Pusat Teknologi Informasi dan Pangkalan Data', ['company-admin']],
            'dirut' => ['Rektor (contoh)', 'uinsuka.rektor', 'Rektor / Pemimpin BLU', 'Pimpinan Universitas', ['pimpinan']],
            'cfo' => ['Wakil Rektor II (contoh)', 'uinsuka.wr2', 'Wakil Rektor Bidang Administrasi Umum, Perencanaan & Keuangan', 'Pejabat Pengelola Keuangan BLU', ['keuangan']],
            'akademik' => ['Wakil Rektor I (contoh)', 'uinsuka.wr1', 'Wakil Rektor Bidang Akademik & Pengembangan Lembaga', 'Pimpinan Universitas', ['kepala-unit']],
            'kemahasiswaan' => ['Wakil Rektor III (contoh)', 'uinsuka.wr3', 'Wakil Rektor Bidang Kemahasiswaan & Kerja Sama', 'Pimpinan Universitas', ['kepala-unit']],
            'lppm' => ['Ketua LPPM (contoh)', 'uinsuka.lppm', 'Ketua Lembaga Penelitian & Pengabdian kepada Masyarakat', 'LPPM', ['kepala-unit']],
            'lpm' => ['Ketua LPM (contoh)', 'uinsuka.lpm', 'Ketua Lembaga Penjaminan Mutu', 'LPM', ['kepala-unit', 'editor-jatidiri']],
            'bisnis' => ['Kepala Pusat Pengembangan Bisnis (contoh)', 'uinsuka.bisnis', 'Kepala Pusat Pengembangan Bisnis', 'Pusat Pengembangan Bisnis', ['kepala-unit']],
            'sdm' => ['Kepala Bagian OKH (contoh)', 'uinsuka.sdm', 'Kepala Bagian Organisasi, Kepegawaian & Hukum', 'Biro AUPK', ['kepala-unit']],
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
            'vision' => 'Unggul dan Terkemuka dalam Pemaduan dan Pengembangan Keislaman dan Keilmuan bagi Peradaban untuk mewujudkan masyarakat yang Rukun, Mashlahat, dan Cerdas.',
            'mission' => "1. Memadukan dan mengembangkan keislaman, keilmuan, dan keindonesiaan dalam pendidikan yang bermutu, inklusif, dan berdaya saing.\n2. Membangun budaya ijtihad akademik melalui penelitian multidisipliner dan kolaboratif yang berdampak bagi keilmuan dan masyarakat.\n3. Memperkuat peran dalam pengabdian kepada masyarakat dan penyelesaian persoalan kebangsaan dan kemanusiaan.\n4. Mewujudkan tata kelola yang transparan, akuntabel, dan berkelanjutan melalui kerja sama strategis, inovasi kelembagaan, dan kepercayaan publik.",
            'purpose' => "1. Terwujudnya sistem pendidikan unggul yang integratif-interkonektif.\n2. Lahirnya lulusan yang beriman, berakhlak mulia, berwawasan luas, dan berjiwa kewirausahaan.\n3. Terwujudnya pusat studi dan penelitian yang unggul.\n4. Terwujudnya pengabdian untuk masyarakat yang rukun, maslahat, dan cerdas.\n5. Terbangunnya jejaring alumni yang kuat.\n6. Terwujudnya tata kelola yang profesional, transparan, akuntabel, dan berbasis digital.",
            'tagline' => 'Empowering Knowledge – Shaping the Future',
            'brand_promise' => 'Kampus moderat dan inklusif: pendidikan bermutu yang terbuka bagi semua, dengan paradigma integrasi-interkoneksi keilmuan.',
            'value_proposition' => 'PTKIN tertua dengan paradigma integrasi-interkoneksi, layanan inklusi yang dikenal luas, 72% prodi berakreditasi Unggul/A (2024), dan masa tunggu lulusan 4,21 bulan (2024); dikelola dengan pola keuangan BLU menuju PTNBH (DATA DEMO).',
            'culture_statement' => 'Integratif-Interkonektif, Dedikatif-Inovatif, Inklusif-Continuous Improvement sebagai budaya kerja; tata kelola berbasis kinerja.',
            'history' => "1951: PTAIN Yogyakarta, cikal bakal pendidikan tinggi Islam negeri.\n1960: IAIN Al-Jami'ah Al-Islamiyah Al-Hukumiyah.\n2004: alih status menjadi UIN Sunan Kalijaga dengan mandat integrasi-interkoneksi keilmuan.\n2020–2024: capaian akreditasi Unggul/A naik ke 72%, dosen bersertifikat pendidik 87,05%, masa tunggu lulusan 4,21 bulan.\n2025: Renstra 2025–2029 ditetapkan; pematangan enam lingkup transformasi.\n2026: implementasi transformasi; persiapan Kampus 2 Pajangan (±71 ha, Forest Campus).\n2027–2029 (rencana): fakultas & prodi STEM dan FKIK, Kampus 2 terbangun (2028), visi tercapai (2029).\n(Ringkasan dari Renstra 2025–2029 untuk keperluan demo.)",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-10 10:00:00',
        ]);
        foreach ([
            ['Integratif-Interkonektif', 'Integrasi-Interkoneksi', 'Ilmu keislaman, sains, sosial, humaniora, dan teknologi saling menyapa dalam pendidikan, riset, dan pengabdian.', "Merancang mata kuliah dan riset lintas fakultas\nMemakai data kinerja bersama untuk keputusan"],
            ['Dedikatif-Inovatif', 'Dedikasi & inovasi', 'Pengabdian sepenuh hati yang terus mencari cara baru yang lebih baik.', "Menuntaskan tugas sesuai standar layanan\nMengusulkan perbaikan proses setiap triwulan"],
            ['Inklusif-Continuous Improvement', 'Inklusi & perbaikan berkelanjutan', 'Kampus terbuka bagi semua dan selalu memperbaiki diri berbasis evaluasi.', "Menyediakan layanan pendamping difabel\nMenindaklanjuti hasil evaluasi sampai tuntas"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Paradigma integrasi-interkoneksi', 'Kompetensi', 'Pembeda keilmuan sejak alih status UIN 2004; target 71% prodi mengimplementasikan integrasi keilmuan (2026).', 4, 5, true, 'Kurikulum dan riset lintas disiplin berbasis SDGs.'],
            ['Program studi & akreditasi', 'Kompetensi', '72% prodi berakreditasi Unggul/A pada 2024 (Renstra Bab I).', 4, 5, true, 'Akreditasi internasional prodi unggulan; prodi STEM & FKIK baru (2027).'],
            ['Moderasi beragama & layanan inklusi', 'Kompetensi', 'Pemahaman moderasi beragama mahasiswa S1 78,14% (2024); layanan difabel dikenal luas.', 4, 5, true, 'Indeks Keberagamaan Mahasiswa sebagai IKSP.'],
            ['Dosen bersertifikat & berkualifikasi', 'Sumber daya manusia', 'Dosen bersertifikat pendidik 87,05% dan berkualifikasi S3 41,32% (2024).', 3, 5, true, 'Percepatan S3, lektor kepala & guru besar; dosen asing.'],
            ['Riset & publikasi', 'Kompetensi', 'Jurnal terakreditasi nasional 55% (2024); paten masih tertinggal (2022: 75%).', 3, 4, false, 'Hilirisasi riset ke DUDI dan paten granted.'],
            ['Aset & unit bisnis BLU', 'Sumber daya fisik', 'University Hotel, convention hall, unit usaha; lahan Kampus 2 Pajangan ±71 ha.', 2 + $lvl, 4, false, 'Aset produktif & fullboard University Hotel (rancangan regulasi 2027–2028).'],
            ['Sistem informasi & data', 'Sistem', 'SIA, sistem keuangan BLU, pangkalan data; Indeks Transformasi Digital masih rendah.', 2 + $lvl, 4, false, 'Dokumen Sistem Informasi & Teknologi (2027); integrasi dengan Kinera.'],
            ['Tata kelola BLU & penjaminan mutu', 'Sistem', 'SPMI 100% (2024), Dewan Pengawas, SPI; opini WTP atas LK BLU 2025.', 3, 5, false, 'Statuta baru & manajemen risiko menuju PTNBH.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    /** @return array<string,StrategicIssue> */
    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Renstra UIN Sunan Kalijaga 2025–2029', '2025-02-10', 'internal', self::RENSTRA,
                'Delapan Sasaran Program Kemenag diturunkan ke IKSP/IKSK; tujuh strategi (transformasi akademik, kelembagaan, Kampus 2, SDM, budaya riset, kewirausahaan, kemitraan) dan enam lingkup transformasi menuju perguruan tinggi otonom.',
                'Peta strategi & indikator Kinera disusun dari IKSP/IKSK Renstra; target 2026 mengikuti Matriks Kinerja.'],
            ['Capaian kinerja 2020–2024 (Renstra Bab I)', '2025-02-10', 'internal', self::RENSTRA.' Bab I',
                'Akreditasi Unggul/A 62% → 72%; dosen bersertifikat pendidik 87,05%; dosen S3 41,32% (target 43%); masa tunggu lulusan 4,21 bulan; peningkatan mahasiswa 10,84%; mahasiswa asing 19,8% dari target 25%; paten 2022 hanya 75% target.',
                'Baseline 2024 indikator Kinera; prioritas perbaikan: kualifikasi S3, paten, internasionalisasi.'],
            ['PER-6/PB/2025 tentang Kontrak Kinerja Pemimpin BLU', '2025-03-24', 'eksternal', 'PER-6/PB/2025 (ditetapkan 18 Maret 2025)',
                'Kontrak Kinerja memuat indikator aspek layanan & keuangan dengan trajektori, Definisi Operasional, dan laporan capaian paling lambat 15 hari kerja setelah periode trajektori.',
                'Kontrak 2025 di-adendum (Pasal 21); siklus pelaporan triwulanan di Kinera.'],
            ['Kinerja keuangan BLU 2025 (LK audited)', '2026-05-05', 'internal', self::LK,
                'Pendapatan operasional Rp399,9 M; surplus operasional Rp39,7 M; EBITDA ±Rp71,9 M; PNBP Rp219,5 M atau 99,48% dari anggaran Rp220,7 M; penyerapan belanja 94,31%; opini WTP.',
                'Target PNBP belum tercapai; pendapatan kerja sama baru 29% dari estimasi.'],
            ['Kerangka pendanaan Renstra', '2025-02-10', 'internal', self::RENSTRA.' Bab IV',
                'Pagu 2024 Rp429,7 M (Program Pendidikan Tinggi Rp296,0 M; Dukungan Manajemen Rp133,7 M). Sumber: RM, PNBP BLU, SBSN, PHLN, hibah dalam negeri; SBSN/PHLN untuk Kampus 2.',
                'Pagu indikatif inisiatif & skema pendanaan Kampus 2.'],
            ['Persaingan perguruan tinggi di Yogyakarta', '2026-01-20', 'eksternal', 'Analisis manajemen (ilustrasi)',
                'Kota pendidikan dengan PTN & PTS besar; mahasiswa membandingkan biaya, akreditasi, dan prospek kerja.',
                'Diferensiasi: integrasi keilmuan, inklusi, prodi STEM & FKIK baru.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-01-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Asta Cita & arah kebijakan Kementerian Agama', 'Renstra mengacu Asta Cita, misi Kemenag, dan tujuan Ditjen Pendis: mutu, akses, moderasi beragama.', 'peluang', 4, 5, 'naik', 'Selaraskan IKSP/IKSK dengan Perjanjian Kinerja & Kontrak Kinerja.'],
            ['politik', 'Efisiensi belanja negara', 'Efisiensi belanja K/L membatasi rupiah murni untuk operasional.', 'ancaman', 4, 4, 'naik', 'Perkuat PNBP non-UKT dan belanja berbasis kinerja.'],
            ['ekonomi', 'Daya beli keluarga mahasiswa', 'Sensitivitas UKT; kebutuhan beasiswa & UKT 1 tetap tinggi.', 'ancaman', 4, 3, 'stabil', 'IKSK beasiswa & UKT 1; beasiswa mitra.'],
            ['ekonomi', 'Pasar MICE & properti Yogyakarta', 'Permintaan gedung pertemuan, hotel, dan pelatihan meningkat.', 'peluang', 3, 4, 'naik', 'University Hotel & aset produktif (rancangan regulasi 2027–2028).'],
            ['sosial', 'Tuntutan kampus inklusif & moderat', 'Masyarakat menuntut akses bagi penyandang disabilitas dan keberagamaan yang ramah.', 'peluang', 4, 4, 'naik', 'Indeks Keberagamaan Mahasiswa & fasilitas inklusif per fakultas.'],
            ['sosial', 'Relevansi lulusan dengan dunia kerja', 'Pengguna lulusan meminta kompetensi bersertifikat dan pengalaman magang.', 'ancaman', 4, 4, 'naik', 'Sertifikasi kompetensi & magang di luar kampus.'],
            ['teknologi', 'Transformasi digital & AI', 'Pembelajaran hibrida dan AI mengubah model pengajaran dan layanan.', 'peluang', 4, 5, 'naik', 'Indeks Transformasi Digital & digitalisasi arsip.'],
            ['teknologi', 'Keamanan siber data akademik', 'Data mahasiswa & keuangan menjadi sasaran serangan.', 'ancaman', 4, 3, 'naik', 'Dokumen Sistem Informasi & Teknologi; pelindungan data pribadi.'],
            ['lingkungan', 'Kampus 2 Pajangan sebagai Forest Campus', 'Lahan ±71 ha di Pajangan, Bantul untuk fakultas baru (RIP 2015–2039).', 'peluang', 4, 3, 'naik', 'Masterplan kampus hijau & pendanaan SBSN/PHLN.'],
            ['hukum', 'PER-6/PB/2025 Kontrak Kinerja Pemimpin BLU', 'Kewajiban trajektori, Definisi Operasional, dan laporan capaian berkala.', 'ancaman', 5, 5, 'naik', 'Siklus pelaporan triwulanan dan dokumen pendukung terarsip.'],
            ['hukum', 'Transformasi status BLU ke PTNBH (UU 12/2012)', 'Strategi transformasi kelembagaan Renstra; statuta baru ditargetkan 2026.', 'peluang', 4, 3, 'naik', 'Kajian kesiapan & rancangan statuta.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => 'Renstra 2025–2029 & analisis manajemen (ilustrasi demo)']);
        }

        foreach ([
            ['persaingan', 'Persaingan PTN, PTKIN, dan PTS', 'PTN besar dan PTS ternama di DIY bersaing merebut calon mahasiswa unggul.', 4, 'Diferensiasi integrasi keilmuan & inklusi; prodi STEM baru.'],
            ['kekuatan_pembeli', 'Calon mahasiswa & orang tua', 'Pilihan luas; sensitif biaya dan reputasi.', 3, 'UKT berjenjang, beasiswa, layanan karier.'],
            ['kekuatan_pemasok', 'Dosen bergelar doktor & tenaga ahli', 'Persaingan merekrut dosen S3 dan dosen asing.', 3, 'Remunerasi BLU & percepatan jabatan fungsional.'],
            ['pendatang_baru', 'Kampus cabang & pendidikan jarak jauh', 'PJJ dan kampus cabang (termasuk luar negeri) memperluas pilihan.', 2, 'PJJ per fakultas; rancangan UIN cabang Malaysia/Brunei.'],
            ['produk_pengganti', 'Kursus sertifikasi & bootcamp', 'Jalur sertifikasi singkat menggantikan sebagian kebutuhan gelar.', 3, 'Rekognisi Pembelajaran Lampau & sertifikasi kompetensi.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Mahasiswa sarjana (S1)', 'Pendidikan', 'Layanan pendidikan utama; sumber terbesar pendapatan jasa layanan (UKT).', 80, 'stabil', true, 'Mutu pengajaran, UKT terjangkau, magang, prospek kerja.', 'Prodi unggul & layanan karier.'],
            ['Mahasiswa pascasarjana (S2/S3)', 'Pendidikan', 'Strategi Renstra: S2/S3 untuk semua prodi, fast track, double degree.', 10, 'naik', true, 'Pembimbing bereputasi, publikasi, jejaring.', 'Kelas internasional & fast track.'],
            ['Mahasiswa internasional', 'Pendidikan', 'Peningkatan mahasiswa asing 19,8% (2024) dari target 25%.', 2, 'naik', true, 'Program berbahasa Inggris, asrama, beasiswa.', 'IUP & database beasiswa internasional.'],
            ['Mitra kerja sama (pemerintah, DUDI, internasional)', 'Kerja sama', 'Pelatihan, riset terapan, magang, dan konsultasi.', 3, 'naik', true, 'Solusi tepat guna, pelaporan rapi.', 'Kerja sama satu pintu & knowledge enterprise.'],
            ['Pengguna aset (MICE, University Hotel, sewa)', 'Aset', 'Gedung pertemuan, hotel, ruang komersial.', 5, 'naik', true, 'Fasilitas siap pakai, harga bersaing.', 'Fullboard University Hotel & campus service centre.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan ilustratif untuk demo']);
        }

        foreach ([
            ['UIN Sunan Kalijaga Yogyakarta', 'PTKIN BLU', 1, 399.9, 2.5, 10.0, true, 'Integrasi keilmuan, inklusi, akreditasi kuat, masa tunggu lulusan singkat.', 'Kualifikasi S3 di bawah target, paten rendah, PNBP non-UKT kecil.'],
            ['PTN besar di DIY', 'PTN', 2, null, null, null, false, 'Reputasi & pendanaan riset besar.', 'Biaya & seleksi ketat.'],
            ['PTS Islam ternama di DIY', 'PTS', 3, null, null, null, false, 'Kampus modern, kerja sama industri.', 'Biaya lebih tinggi.'],
            ['PTKIN lain di Jawa', 'PTKIN', 4, null, null, null, false, 'Bidang kajian serupa, biaya bersaing.', 'Skala & reputasi bervariasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'TA 2025 (LK audited)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Pendapatan operasional LO dalam Rp miliar; margin = surplus LO ÷ pendapatan (data demo).' : 'Pembanding kualitatif untuk demo.']);
        }

        $rancangan = $year === 2025 ? 'belum' : 'dipantau';
        foreach ([
            ['Kontrak Kinerja Pemimpin BLU', 'Direktorat Jenderal Perbendaharaan', 'PER-6/PB/2025', 'Kinerja BLU', '2025-03-18', 'Pedoman penyusunan Kontrak Kinerja dan penetapan persetujuan capaian kinerja Pemimpin BLU.', 'Dasar seluruh siklus Kontrak Kinerja di aplikasi ini.', $year === 2025 ? 'sebagian' : 'patuh', 'strategi', $year === 2025 ? '2025-04-01' : null],
            ['Pengelolaan Keuangan BLU', 'Pemerintah', 'PP No. 23/2005 jo. PP No. 74/2012', 'Keuangan BLU', null, 'Fleksibilitas pengelolaan keuangan BLU, tarif layanan, dan remunerasi.', 'Dasar pola pengelolaan keuangan.', 'patuh', 'cfo', null],
            ['Pendidikan Tinggi', 'Pemerintah', 'UU No. 12/2012', 'Akademik', null, 'Tri Dharma, otonomi perguruan tinggi, pilihan status PTNBH.', 'Dasar strategi transformasi kelembagaan.', 'patuh', 'akademik', null],
            ['Standar Nasional Pendidikan Tinggi', 'Kementerian Pendidikan Tinggi', 'Permendikbudristek No. 53/2023', 'Mutu', '2023-08-18', 'SPMI dan akreditasi program studi.', 'Indikator akreditasi & SPMI.', 'patuh', 'lpm', null],
            ['Pelindungan data pribadi', 'Pemerintah', 'UU No. 27/2022', 'Teknologi', '2024-10-17', 'Data mahasiswa, pegawai, dan keuangan.', 'Sanksi administratif & reputasi.', 'sebagian', 'ti', '2026-12-31'],
            // Lampiran Kerangka Regulasi Renstra 2025–2029 (rancangan internal)
            ['Statuta UIN Sunan Kalijaga (baru)', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 1', 'Kelembagaan', null, 'Terwujudnya transformasi kelembagaan. Unit terkait: Rektorat, OKH, SPI, LPM, Dekanat.', 'Prasyarat transformasi menuju PTNBH.', $rancangan, 'sdm', '2026-12-31'],
            ['Kebijakan keuangan & strategi bisnis', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 2', 'Keuangan BLU', null, 'Terwujudnya transformasi kelembagaan. Unit terkait: Pusat Pengembangan Bisnis, Perencanaan & Keuangan.', 'Dasar peningkatan PNBP non-UKT.', $rancangan, 'bisnis', '2026-12-31'],
            ['Manajemen SDM', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 3', 'SDM', null, 'Terwujudnya transformasi kelembagaan. Unit terkait: OKH dan LPM.', 'Dasar manajemen talenta & sertifikasi kompetensi.', $rancangan, 'sdm', '2026-12-31'],
            ['Kebijakan akademik', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 4', 'Akademik', null, 'Prodi STEM, S2/S3, double degree, IUP, fast track, rekognisi pembelajaran lampau.', 'Dasar transformasi akademik.', 'belum', 'akademik', '2027-12-31'],
            ['Sistem informasi & teknologi', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 5', 'Teknologi', null, 'Pengelolaan sistem informasi untuk keputusan berbasis data.', 'Dasar Indeks Transformasi Digital.', 'belum', 'ti', '2027-12-31'],
            ['Sistem pengelolaan risiko', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 6', 'Tata kelola', null, 'Risiko akademik, keuangan, hukum, dan reputasi. Unit terkait: LPM, LPPM, SPI.', 'Prasyarat tata kelola PTNBH.', 'belum', 'lpm', '2027-12-31'],
            ['Ruang terbuka sebagai aset produktif', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 7', 'Aset', null, 'Pemanfaatan lahan/aset produktif untuk menambah pendapatan.', 'Pendapatan aset BLU.', 'belum', 'bisnis', '2027-12-31'],
            ['Alokasi anggaran fullboard di University Hotel', 'UIN Sunan Kalijaga', 'Rancangan – Kerangka Regulasi Renstra no. 8', 'Aset', null, 'Pelaksanaan fullboard (sebagian) di University Hotel UIN Suka.', 'Utilisasi hotel & pendapatan BLU.', 'belum', 'bisnis', '2028-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'mahasiswa' => ['Mahasiswa & orang tua', 'eksternal', 'Penerima layanan', 4, 5, 'Pembelajaran bermutu, UKT terjangkau, layanan inklusif.', 'Survei kepuasan, forum mahasiswa, layanan pengaduan.', 'Wakil Rektor III'],
            'kemenag' => ['Ditjen Pendidikan Islam (Pembina Teknis)', 'eksternal', 'Pembina Teknis', 5, 5, 'Capaian IKSP/IKSK & kepatuhan kebijakan.', 'Persetujuan Definisi Operasional & laporan capaian triwulanan.', 'Biro AUPK'],
            'djpb' => ['Ditjen Perbendaharaan (Pembina Keuangan)', 'eksternal', 'Pembina Keuangan', 5, 4, 'Kontrak Kinerja & kinerja keuangan BLU.', 'Pembahasan Kontrak Kinerja & verifikasi capaian.', 'Wakil Rektor II'],
            'dewas' => ['Dewan Pengawas BLU', 'internal', 'Pengawas', 5, 5, 'Pengawasan kinerja & tata kelola.', 'Rapat Dewan Pengawas triwulanan.', 'Sekretariat Dewas'],
            'dosen' => ['Dosen', 'internal', 'SDM', 4, 5, 'Karier, riset, remunerasi adil, studi S3.', 'Senat, forum fakultas.', 'Bagian OKH'],
            'tendik' => ['Tenaga kependidikan', 'internal', 'SDM', 3, 4, 'Pengembangan kompetensi & jabatan fungsional.', 'Pelatihan & sertifikasi.', 'Bagian OKH'],
            'mitra' => ['Mitra kerja sama dalam & luar negeri', 'eksternal', 'Mitra', 3, 4, 'Kerja sama berdampak & dilaporkan.', 'Pusat kerja sama satu pintu.', 'Wakil Rektor III'],
            'alumni' => ['Alumni & pengguna lulusan', 'eksternal', 'Pengguna', 3, 4, 'Lulusan kompeten & berintegritas.', 'Tracer study & jejaring alumni.', 'Pusat Karier'],
            'pemda' => ['Pemerintah Kabupaten Bantul', 'eksternal', 'Mitra daerah', 3, 4, 'Kampus 2 Pajangan berdampak bagi warga.', 'Forum koordinasi tata ruang & perizinan.', 'Wakil Rektor II'],
            'bpk' => ['Auditor (BPK, KAP, Itjen)', 'eksternal', 'Regulator', 4, 3, 'Laporan keuangan andal.', 'Tindak lanjut temuan tepat waktu.', 'SPI'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'pnbp' => ['Target PNBP 2025 belum tercapai', 'PNBP Rp219,5 M = 99,48% dari anggaran Rp220,7 M (LK 2025); pendapatan kerja sama ±29% estimasi.', 'keuangan', 5, 5, 'cfo', ['djpb', 'dewas']],
            's3' => ['Kualifikasi S3 dosen di bawah target', 'Dosen S3 41,32% dari target 43% (2024); syarat prodi S2/S3 baru dan akreditasi internasional.', 'sdm', 4, 5, 'sdm', ['dosen', 'kemenag']],
            'riset' => ['Hilirisasi riset & paten lemah', 'Hasil penelitian berpaten 2022 hanya 75% target; riset yang dimanfaatkan DUDI masih sedikit.', 'proses', 4, 4, 'lppm', ['mitra']],
            'internasional' => ['Internasionalisasi belum optimal', 'Peningkatan mahasiswa asing 19,8% dari target 25% (2024).', 'pelanggan', 3, 4, 'kemahasiswaan', ['mitra', 'mahasiswa']],
            'kampus2' => ['Kapasitas kampus & Kampus 2 Pajangan', 'Fakultas STEM & FKIK (2027) membutuhkan Kampus 2 terbangun (2028); pendanaan SBSN/PHLN.', 'proses', 4, 5, 'cfo', ['kemenag', 'pemda']],
            'ptnbh' => ['Kesiapan transformasi menuju PTNBH', 'Statuta, kebijakan keuangan, dan manajemen SDM baru ditargetkan 2026.', 'proses', 4, 5, 'dirut', ['kemenag', 'dewas']],
            'kontrak' => ['Disiplin siklus Kontrak Kinerja BLU', 'Laporan capaian ≤ 15 hari kerja setelah triwulan beserta verifikasi internal dan dokumen pendukung.', 'proses', 5, 4, 'strategi', ['djpb', 'kemenag']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => 'Renstra 2025–2029, LK 2025 & analisis manajemen (data demo)', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Paradigma integrasi-interkoneksi & moderasi', 'Pembeda keilmuan; pemahaman moderasi mahasiswa 78,14% (2024).', 0.12, 4],
            ['S', 'Akreditasi & penjaminan mutu kuat', 'Prodi Unggul/A 72% dan SPMI 100% (2024).', 0.12, 4],
            ['S', 'Lulusan cepat terserap', 'Masa tunggu 4,21 bulan; 6 fakultas bermitra dengan DUDI (2024).', 0.10, 4],
            ['S', 'Keuangan BLU sehat', 'Surplus operasional Rp39,7 M, opini WTP (LK 2025).', 0.08, 3],
            ['W', 'Kualifikasi S3 & guru besar', 'S3 41,32% di bawah target 43%.', 0.10, 2],
            ['W', 'Paten & hilirisasi riset', 'Paten 2022 hanya 75% target.', 0.08, 2],
            ['W', 'Pendapatan non-UKT kecil', 'Kerja sama & aset di bawah estimasi (LK 2025).', 0.08, 2],
            ['O', 'Status PTNBH & otonomi', 'UU 12/2012 membuka transformasi kelembagaan.', 0.10, 3],
            ['O', 'Lahan Kampus 2 Pajangan ±71 ha', 'Ruang untuk fakultas STEM & FKIK.', 0.08, 3],
            ['O', 'Kepercayaan publik & jejaring alumni', 'Peningkatan mahasiswa 10,84% (2024).', 0.06, 3],
            ['T', 'Persaingan perguruan tinggi', 'PTN/PTS besar di DIY & kampus luar negeri.', 0.10, 2],
            ['T', 'Efisiensi anggaran negara', 'Rupiah murni terbatas untuk investasi.', 0.08, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => 'Renstra 2025–2029 & LK 2025 (ilustrasi)']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Transformasi kelembagaan menuju PTNBH', 'Akreditasi & tata kelola kuat menjadi modal statuta baru dan otonomi.', 5, ['ptnbh']],
            ['SO', 'Prodi STEM & FKIK di Kampus 2', 'Integrasi keilmuan diperluas ke sains, kesehatan, dan teknologi.', 5, ['kampus2']],
            ['WO', 'Percepatan S3, lektor kepala & guru besar', 'Beasiswa studi lanjut & klinik jabatan fungsional.', 4, ['s3']],
            ['WO', 'Knowledge enterprise & paten', 'Riset bersama DUDI, pendampingan HKI, hilirisasi.', 4, ['riset']],
            ['ST', 'Aset produktif & University Hotel untuk PNBP', 'Fullboard sebagian kegiatan dan sewa ruang terbuka.', 4, ['pnbp']],
            ['WT', 'Siklus Kontrak Kinerja berbasis bukti', 'Verifikasi SPI & laporan triwulanan tepat waktu.', 5, ['kontrak', 'pnbp']],
            ['SO', 'Internasionalisasi: IUP, double degree, mahasiswa asing', 'Kerja sama luar negeri & database beasiswa internasional.', 3, ['internasional']],
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
            'T1' => ['Transformasi Akademik', 'Mutu & relevansi pendidikan: akreditasi, integrasi keilmuan, prodi STEM, S2/S3, double degree, IUP, fast track.', '#1F6E4A'],
            'T2' => ['Riset, Inovasi & Kemitraan', 'Budaya riset & inovasi berdampak SDGs, kewirausahaan, dan kerja sama strategis.', '#2A6F9E'],
            'T3' => ['Transformasi Kelembagaan & Keuangan', 'Menuju PTNBH: statuta, kemandirian keuangan BLU, aset produktif, tata kelola akuntabel.', '#C88A12'],
            'T4' => ['SDM, Digital & Kampus 2', 'SDM bersertifikat & bereputasi, transformasi digital, dan pembangunan Kampus 2 Pajangan.', '#6B4FA0'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Prodi STEM & FKIK dibuka 2027', 'produk', 'Peta jalan Renstra: pembukaan fakultas & prodi baru STEM dan FKIK pada 2027.'],
            ['T1', 'S2/S3 untuk semua prodi, double degree, IUP & fast track', 'produk', 'Strategi transformasi akademik Renstra.'],
            ['T2', 'Knowledge enterprise & hilirisasi riset', 'pasar', 'Riset dimanfaatkan DUDI dan paten granted.'],
            ['T2', 'Kerja sama satu pintu & mahasiswa internasional', 'pasar', 'Mahasiswa asing 19,8% dari target 25% (2024).'],
            ['T3', 'Statuta baru & kesiapan PTNBH', 'operasi', 'Kerangka regulasi: statuta, kebijakan keuangan & SDM (2026).'],
            ['T3', 'Aset produktif & fullboard University Hotel', 'operasi', 'Kerangka regulasi no. 7–8 (2027–2028).'],
            ['T4', 'Kampus 2 Pajangan sebagai Forest Campus', 'operasi', 'Peta jalan: Kampus 2 terbangun 2028.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'C1' => ['pelanggan', 'T1', 'Partisipasi peserta didik meningkat & inklusif', 'SP.6 Meningkatnya partisipasi peserta didik: jumlah mahasiswa naik, beasiswa & UKT 1 terjangkau, fasilitas inklusif.', 'akademik', 2],
            'C2' => ['pelanggan', 'T1', 'Lulusan berdaya saing', 'Meningkatnya daya saing lulusan: masa tunggu singkat, bekerja/berwirausaha, bersertifikat kompetensi.', 'kemahasiswaan', 2],
            'C3' => ['pelanggan', 'T1', 'Karakter keagamaan mahasiswa yang ramah & inklusif', 'Meningkatnya kualitas karakter keagamaan mahasiswa yang ramah, inklusif, dan selaras dengan nilai kebangsaan.', 'kemahasiswaan', 1.5],
            'C4' => ['pelanggan', 'T2', 'Kemitraan strategis yang relevan', 'Meningkatnya relevansi pendidikan tinggi keagamaan melalui penguatan kemitraan strategis.', 'kemahasiswaan', 1.5],
            'F1' => ['keuangan', 'T3', 'Kemandirian pendapatan BLU', 'PNBP tumbuh dan sumber non-UKT (kerja sama, aset, unit bisnis) meningkat sebagai bekal PTNBH.', 'cfo', 2],
            'F2' => ['keuangan', 'T3', 'Anggaran efisien & akuntabel', 'Surplus EBITDA, penyerapan & IKPA baik, likuiditas sehat, opini WTP.', 'cfo', 2],
            'P1' => ['proses', 'T1', 'Standar & sistem penjaminan mutu meningkat', 'Meningkatnya kualitas standar dan sistem penjaminan mutu: akreditasi Unggul/internasional dan SPMI.', 'lpm', 2],
            'P2' => ['proses', 'T1', 'Transformasi akademik terintegrasi', 'Integrasi keilmuan di semua prodi, magang di luar kampus, prodi STEM & S2/S3 baru.', 'akademik', 1.5],
            'P3' => ['proses', 'T2', 'Riset & inovasi berdampak SDGs', 'Meningkatnya produktivitas dan daya saing: publikasi bereputasi tersitasi, karya SDGs, hilirisasi ke DUDI, THE Impact.', 'lppm', 2],
            'P4' => ['proses', 'T3', 'Tata kelola organisasi efektif & akuntabel', 'Meningkatnya tata kelola organisasi: Nilai SAKIP, NKA, tindak lanjut pemeriksaan, kesiapan PTNBH.', 'strategi', 1.5],
            'P5' => ['proses', 'T3', 'Aset & unit bisnis produktif', 'University Hotel, convention hall, dan ruang terbuka menjadi aset produktif.', 'bisnis', 1],
            'L1' => ['sdm', 'T4', 'Dosen berkualitas & bereputasi', 'Meningkatnya dosen yang berkualitas: sertifikasi kompetensi, S3, jabatan lektor kepala & guru besar.', 'sdm', 2],
            'L2' => ['sdm', 'T4', 'Tenaga kependidikan profesional', 'Meningkatnya tenaga kependidikan yang berkualitas: sertifikat kompetensi & profesionalisme ASN.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Infrastruktur digital & kampus memadai', 'Transformasi digital, arsip digital, dan pembangunan Kampus 2 Pajangan.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P2', 2], ['L1', 'P3', 3], ['L2', 'P4', 2], ['L2', 'P5', 1], ['L3', 'P2', 2], ['L3', 'P4', 2],
            ['P1', 'C1', 3], ['P1', 'C2', 2], ['P2', 'C2', 3], ['P2', 'C3', 2], ['P3', 'C4', 2], ['P3', 'C2', 1],
            ['P4', 'F2', 3], ['P5', 'F1', 3], ['P2', 'C4', 1],
            ['F1', 'C1', 2], ['F2', 'C1', 1], ['F1', 'C4', 1], ['C3', 'C2', 1],
        ] as [$s, $t, $str]) {
            ObjectiveRelation::create(['source_objective_id' => $obj[$s]->id, 'target_objective_id' => $obj[$t]->id, 'strength' => $str]);
        }

        return [$themes, $obj];
    }

    // ------------------------------------------------------------------ Indikator Kinerja

    protected function kpiDefs(): array
    {
        $lk = true;   // realisasi tahunan 2025 dari LK BLU audited
        $il = false;  // realisasi 2025 ilustrasi; baseline/target dari Renstra bila tersedia
        $m = 'bulanan';
        $q = 'kuartalan';
        $s = 'semesteran';
        $y = 'tahunan';

        // kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, PJ, rumus, sumber, 2024, target 2025, realisasi 2025, target 2026, faktor 2026, dari LK?, frekuensi
        return [
            // Pemangku kepentingan
            ['C1.1', 'C1', 'Persentase peningkatan jumlah mahasiswa (IKSP.6.2)', '%', 'higher', 'last', 2, false, true, 'akademik', '(Mahasiswa aktif tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'PDDikti / SIA', 10.84, 10.0, 10.6, 10.0, .93, $il, $m],
            ['C1.2', 'C1', 'Persentase mahasiswa penerima beasiswa (IKSK.2132.2.1)', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Mahasiswa aktif penerima beasiswa ÷ mahasiswa aktif × 100', 'Bagian Kemahasiswaan', 18.0, 19.0, 19.4, 20.0, 1.0, $il, $s],
            ['C1.3', 'C1', 'Persentase fakultas dengan fasilitas pendidikan inklusif berbasis gender (IKSK.2132.1.2)', '%', 'higher', 'last', 1, false, false, 'cfo', 'Fakultas yang menyediakan fasilitas inklusif ÷ total fakultas × 100', 'Bagian Umum & PLD', 62.5, 75.0, 75.0, 87.5, .95, $il, $s],
            ['C2.1', 'C2', 'Rata-rata masa tunggu lulusan memperoleh pekerjaan (IKSP)', 'bulan', 'lower', 'last', 2, false, true, 'kemahasiswaan', 'Rerata bulan sejak lulus hingga bekerja pertama (responden tracer)', 'Tracer study', 4.21, 4.20, 4.05, 4.00, 1.06, $il, $q],
            ['C2.2', 'C2', 'Persentase lulusan bekerja/berwirausaha ≤ 1 tahun (IKSK.2132.5.3)', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Lulusan bekerja/berwirausaha ≤ 1 tahun ÷ responden tracer × 100', 'Tracer study', 17.0, 18.5, 18.9, 20.0, .97, $il, $m],
            ['C2.3', 'C2', 'Persentase mahasiswa bersertifikat kompetensi/profesi (IKSK.2132.5.5)', '%', 'higher', 'last', 1, false, false, 'akademik', 'Mahasiswa bersertifikat kompetensi ÷ mahasiswa tingkat akhir × 100', 'SIA & LSP', 2.1, 2.5, 2.7, 3.0, .80, $il, $q],
            ['C3.1', 'C3', 'Indeks Keberagamaan Mahasiswa (IKSP)', 'indeks', 'higher', 'last', 2, false, false, 'kemahasiswaan', 'Σ skor dimensi keberagamaan ÷ jumlah dimensi (skala 0–100)', 'Survei karakter keagamaan', 78.14, 79.00, 80.30, 81.00, .99, $il, $s],
            ['C3.2', 'C3', 'Persentase mahasiswa mengikuti program literasi Kitab Suci (IKSK.2132.7.1)', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Mahasiswa peserta literasi Kitab Suci ÷ mahasiswa aktif × 100', 'Ma\'had & SIA', 30.0, 33.0, 34.2, 36.0, 1.0, $il, $m],
            ['C4.1', 'C4', 'Persentase kerja sama aktif yang menghasilkan program peningkatan mutu (IKSP)', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Kerja sama aktif yang menghasilkan program mutu ÷ kerja sama aktif × 100', 'Pusat Kerja Sama', 42.0, 50.0, 53.5, 55.0, 1.0, $il, $m],
            ['C4.2', 'C4', 'Persentase prodi bekerja sama dengan dunia kerja/industri/pemerintah/internasional (IKSK.2132.6.1)', '%', 'higher', 'last', 1, false, false, 'kemahasiswaan', 'Prodi dengan kerja sama aktif ÷ total prodi × 100', 'Pusat Kerja Sama', 80.0, 85.0, 86.0, 88.0, .99, $il, $m],
            // Keuangan
            ['F1.1', 'F1', 'Realisasi PNBP BLU (IKSK.2135.1.6)', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Realisasi pendapatan BLU (LRA) kumulatif', 'LRA BLU / SAKTI', 206997, 220654, 219502, 232000, .985, $lk, $m],
            ['F1.2', 'F1', 'Pendapatan jasa layanan pendidikan dari masyarakat', 'Rp juta', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan jasa layanan (UKT, biaya pendidikan lain)', 'Laporan Operasional BLU', 193323, 205000, 205625, 216000, .99, $lk, $m],
            ['F1.3', 'F1', 'Pendapatan hasil kerja sama BLU', 'Rp juta', 'higher', 'sum', 0, true, false, 'kemahasiswaan', 'Pendapatan kerja sama dengan pihak lain', 'LRA BLU', 2146, 2649, 766, 2400, .60, $lk, $m],
            ['F1.4', 'F1', 'Pendapatan pengelolaan aset (University Hotel, convention hall, sewa)', 'Rp juta', 'higher', 'sum', 0, true, false, 'bisnis', 'Pendapatan sewa & pemanfaatan aset BLU', 'LRA BLU', 6100, 9043, 6377, 8000, .94, $lk, $m],
            ['F1.5', 'F1', 'Rasio pendapatan BLU terhadap biaya operasional (POBO)', '%', 'higher', 'last', 1, true, false, 'cfo', 'Pendapatan non-APBN ÷ (beban operasional − penyusutan − bantuan sosial) × 100', 'Laporan Operasional BLU', 66.5, 70.0, 71.1, 72.0, 1.0, $lk, $m],
            ['F2.1', 'F2', 'EBITDA (surplus operasional + penyusutan & amortisasi)', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Surplus/defisit operasional + beban penyusutan & amortisasi', 'Laporan Operasional BLU', 23539, 45000, 71946, 60000, 1.03, $lk, $q],
            ['F2.2', 'F2', 'Penyerapan anggaran belanja (kumulatif)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Realisasi belanja kumulatif ÷ pagu belanja × 100', 'LRA / OM-SPAN', 95.1, 96.0, 94.31, 96.0, .97, $lk, $m],
            ['F2.3', 'F2', 'Rasio kas (cash ratio)', 'kali', 'higher', 'last', 2, true, false, 'cfo', '(Kas & setara kas) ÷ kewajiban jangka pendek', 'Neraca BLU', 2.80, 3.00, 3.16, 3.00, 1.02, $lk, $m],
            ['F2.4', 'F2', 'Opini atas Laporan Keuangan BLU (4 = WTP)', 'skor', 'higher', 'last', 0, true, true, 'cfo', 'WTP = 4, WDP = 3, TW = 2, TMP = 1', 'Laporan auditor independen', 4, 4, 4, 4, 1.0, $lk, $y],
            ['F2.5', 'F2', 'Nilai Indikator Kinerja Pelaksanaan Anggaran (IKSK.2135.1.3)', 'skor', 'higher', 'last', 2, true, false, 'cfo', 'Nilai IKPA dari OM-SPAN', 'OM-SPAN', 95.20, 95.00, 96.10, 96.00, .98, $il, $m],
            // Proses
            ['P1.1', 'P1', 'Persentase prodi terakreditasi Unggul/A/Internasional (IKSP tambahan)', '%', 'higher', 'last', 1, false, true, 'lpm', 'Prodi Unggul/A/internasional ÷ total prodi × 100', 'SK BAN-PT/LAM', 72.0, 70.0, 69.0, 70.0, .99, $il, $q],
            ['P1.2', 'P1', 'Persentase prodi & unit yang menerapkan SPMI (PPEPP) lengkap', '%', 'higher', 'last', 1, false, false, 'lpm', 'Unit dengan siklus PPEPP lengkap ÷ total unit × 100', 'LPM (audit mutu internal)', 100.0, 100.0, 98.5, 100.0, .99, $il, $s],
            ['P2.1', 'P2', 'Persentase prodi mengimplementasikan integrasi keilmuan (IKSK.2132.7.2)', '%', 'higher', 'last', 1, false, false, 'akademik', 'Prodi dengan kurikulum terintegrasi ÷ total prodi × 100', 'Dokumen kurikulum & RPS', 62.0, 66.0, 67.5, 71.0, 1.0, $il, $m],
            ['P2.2', 'P2', 'Persentase mahasiswa S1 dengan pengalaman magang di luar kampus (IKSK.2132.5.1)', '%', 'higher', 'last', 1, false, false, 'akademik', 'Mahasiswa S1 magang di luar kampus ÷ mahasiswa S1 tingkat akhir × 100', 'SIA & Pusat Karier', 8.0, 9.5, 9.8, 11.0, .90, $il, $m],
            ['P3.1', 'P3', 'Persentase publikasi ilmiah bereputasi internasional yang disitasi (IKSK.2138.8.1)', '%', 'higher', 'last', 1, false, false, 'lppm', 'Publikasi internasional bereputasi tersitasi ÷ publikasi internasional bereputasi × 100', 'Scopus / SINTA', 41.0, 45.0, 44.0, 48.0, .93, $il, $m],
            ['P3.2', 'P3', 'Jumlah karya tulis ilmiah berorientasi SDGs di jurnal internasional bereputasi (IKSK.2138.8.2)', 'unit', 'higher', 'sum', 0, false, false, 'lppm', 'Jumlah artikel SDGs terbit di jurnal internasional bereputasi', 'LPPM / Scopus', 120, 135, 142, 150, 1.0, $il, $m],
            ['P3.3', 'P3', 'Skor THE Impact Rankings (IKSP)', 'skor', 'higher', 'last', 1, false, false, 'lppm', 'Skor keseluruhan THE Impact Rankings', 'Times Higher Education', 55.0, 57.0, 58.4, 60.0, 1.0, $il, $y],
            ['P3.4', 'P3', 'Jumlah hasil riset & inovasi yang dimanfaatkan DUDI/masyarakat (IKSK.2138.8.5)', 'unit', 'higher', 'sum', 0, false, false, 'lppm', 'Jumlah hasil riset dengan bukti pemanfaatan oleh DUDI/masyarakat', 'LPPM', 10, 12, 13, 15, .78, $il, $m],
            ['P4.1', 'P4', 'Nilai SAKIP (IKSP)', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Hasil evaluasi SAKIP Itjen Kemenag', 'LHE SAKIP', 72.50, 75.00, 76.10, 78.00, 1.0, $il, $y],
            ['P4.2', 'P4', 'Nilai Kinerja Anggaran (IKSK.2135.1.1)', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Nilai aplikasi SMART DJA', 'SMART DJA', 85.10, 83.00, 87.60, 85.00, 1.0, $il, $y],
            ['P4.3', 'P4', 'Persentase penyelesaian tindak lanjut hasil pemeriksaan (IKSK.2135.1.4)', '%', 'higher', 'last', 1, false, false, 'strategi', 'Rekomendasi selesai ditindaklanjuti ÷ total rekomendasi × 100', 'SPI / SIPTL', 85.0, 90.0, 91.2, 95.0, .96, $il, $m],
            ['P5.1', 'P5', 'Utilisasi fasilitas komersial (University Hotel & convention hall)', '%', 'higher', 'average', 1, true, false, 'bisnis', 'Hari terpakai ÷ hari tersedia × 100', 'Pusat Pengembangan Bisnis', 48.0, 60.0, 51.0, 60.0, .92, $il, $m],
            // SDM & infrastruktur
            ['L1.1', 'L1', 'Persentase dosen memperoleh sertifikasi peningkatan kompetensi (IKSP)', '%', 'higher', 'last', 1, false, false, 'sdm', 'Dosen bersertifikat peningkatan kompetensi ÷ total dosen × 100', 'SIMPEG', 50.0, 53.0, 54.1, 56.0, .98, $il, $m],
            ['L1.2', 'L1', 'Persentase dosen berkualifikasi akademik S3 (IKSK.2132.4.1)', '%', 'higher', 'last', 2, false, false, 'sdm', 'Dosen tetap berkualifikasi S3 ÷ total dosen tetap × 100', 'SIMPEG / PDDikti', 41.32, 43.00, 42.60, 45.00, .97, $il, $s],
            ['L1.3', 'L1', 'Persentase dosen lektor/lektor kepala/guru besar (IKSK.2132.4.3)', '%', 'higher', 'last', 1, false, false, 'sdm', 'Dosen dengan jabatan lektor ke atas ÷ total dosen × 100', 'SIMPEG', 62.0, 64.0, 64.8, 66.0, 1.0, $il, $s],
            ['L2.1', 'L2', 'Persentase ASN tendik memperoleh sertifikat kompetensi (IKSK.2135.1.11)', '%', 'higher', 'last', 1, false, false, 'sdm', 'Tendik bersertifikat kompetensi ÷ total tendik × 100', 'SIMPEG', 14.0, 20.0, 18.2, 24.0, .88, $il, $m],
            ['L2.2', 'L2', 'Indeks Profesionalisme ASN (IKSK.2135.1.8)', 'indeks', 'higher', 'last', 2, false, false, 'sdm', 'Hasil pengukuran Indeks Profesionalitas ASN (BKN)', 'Laporan IP ASN', 78.00, 80.00, 81.50, 83.00, 1.0, $il, $y],
            ['L3.1', 'L3', 'Indeks Implementasi Transformasi Digital (IKSK.2135.1.20)', 'indeks', 'higher', 'last', 2, false, false, 'ti', 'Penilaian mandiri transformasi digital (skala 5)', 'PTIPD', 2.90, 3.20, 3.30, 3.60, .99, $il, $s],
            ['L3.2', 'L3', 'Persentase digitalisasi arsip yang mudah diakses (IKSK.2135.1.15)', '%', 'higher', 'last', 1, false, false, 'ti', 'Arsip terdigitalisasi & terindeks ÷ total arsip aktif × 100', 'Unit Kearsipan', 35.0, 45.0, 47.0, 60.0, .90, $il, $m],
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
        $sm = 'Semesteran; dilaporkan pada laporan triwulan II dan IV.';
        $th = 'Sekali setahun setelah dokumen hasil penilaian terbit.';
        $layanan = fn (string $def, string $test, string $docs, bool $mandatory = false, string $collect = '') => ['layanan', $mandatory, 120, null, $def, $collect ?: $tw, $test, $docs];

        return [
            'C1.1' => $layanan('Kenaikan jumlah mahasiswa aktif dibandingkan tahun sebelumnya (IKSP.6.2 Renstra; target 2026: 10%).', 'Pencocokan SIA dengan PDDikti.', 'Rekap PDDikti, laporan SIA'),
            'C1.2' => $layanan('Mahasiswa aktif penerima beasiswa (KIP-Kuliah, beasiswa mitra, afirmasi) terhadap mahasiswa aktif.', 'Uji petik SK penerima beasiswa.', 'SK penerima, rekap SIA', false, $sm),
            'C1.3' => $layanan('Fakultas yang menyediakan fasilitas pendidikan tinggi inklusif berbasis gender (ramp, toilet difabel, ruang laktasi, pendamping).', 'Observasi lapangan oleh LPM.', 'Daftar periksa fasilitas, foto', false, $sm),
            'C2.1' => $layanan('Rata-rata waktu tunggu lulusan memperoleh pekerjaan pertama (IKSP Renstra; baseline 2024: 4,21 bulan).', 'Uji petik responden tracer study.', 'Laporan tracer study, daftar responden', true),
            'C2.2' => $layanan('Lulusan yang bekerja/berwirausaha dalam 1 tahun setelah lulus (target Renstra 2026: 20%).', 'Uji petik responden tracer study.', 'Laporan tracer study'),
            'C2.3' => $layanan('Mahasiswa yang memperoleh sertifikat kompetensi/profesi dari LSP/lembaga berwenang (target Renstra 2026: 3%).', 'Pencocokan sertifikat dengan daftar mahasiswa.', 'Salinan sertifikat'),
            'C3.1' => $layanan('Indeks komposit sikap keberagamaan yang ramah, inklusif, dan kebangsaan (skala 0–100).', 'Uji validitas instrumen & sampel survei.', 'Laporan survei', false, $sm),
            'C3.2' => $layanan('Mahasiswa yang mengikuti program peningkatan literasi Kitab Suci (target Renstra 2026: 36%).', 'Uji petik presensi program.', 'Daftar peserta, presensi'),
            'C4.1' => $layanan('Kerja sama aktif yang menghasilkan program peningkatan mutu tri dharma.', 'Uji petik laporan kegiatan kerja sama.', 'Naskah kerja sama, laporan kegiatan'),
            'C4.2' => $layanan('Prodi yang bekerja sama dengan dunia kerja/industri/pemerintah/lembaga internasional (target Renstra 2026: 88%).', 'Pencocokan naskah kerja sama per prodi.', 'Naskah kerja sama'),
            'F1.1' => ['keuangan', true, 110, 'pnbp', 'Seluruh pendapatan BLU yang dicatat sebagai PNBP pada LRA dibandingkan target PNBP dalam RBA/DIPA.', $tw, 'Rekonsiliasi LRA BLU dengan SAKTI & rekening koran; uji petik bukti setor oleh SPI.', 'LRA BLU, rekap SAKTI, rekening koran BLU, hasil verifikasi SPI'],
            'F1.2' => ['keuangan', false, 110, null, 'Pendapatan jasa layanan pendidikan dari masyarakat (UKT dan biaya pendidikan lain) basis akrual.', $tw, 'Rekonsiliasi pembayaran UKT dengan SIA dan Laporan Operasional.', 'Laporan Operasional, rekap pembayaran UKT'],
            'F1.3' => ['keuangan', false, 120, null, 'Pendapatan dari perjanjian kerja sama dengan pihak lain yang telah diterima di rekening BLU.', $tw, 'Pencocokan naskah kerja sama, invoice, dan penerimaan kas.', 'Naskah kerja sama, invoice, bukti penerimaan, LRA'],
            'F1.4' => ['keuangan', false, 120, null, 'Pendapatan pemanfaatan aset BLU (University Hotel, convention hall, sewa ruang, unit usaha).', $tw, 'Uji petik kontrak sewa dan bukti setor.', 'Kontrak sewa, rekap okupansi, bukti setor, LRA'],
            'F1.5' => ['keuangan', false, 110, null, 'Kemampuan pendapatan non-APBN membiayai biaya operasional BLU di luar penyusutan dan bantuan sosial.', $tw, 'Rekalkulasi dari Laporan Operasional oleh SPI.', 'Laporan Operasional, kertas kerja'],
            'F2.1' => ['keuangan', true, 120, 'ebitda', 'Surplus/defisit operasional ditambah beban penyusutan dan amortisasi (Pasal 14 ayat 4 huruf e).', $tw, 'Rekalkulasi dari Laporan Operasional; review SPI.', 'Laporan Operasional, rincian beban penyusutan'],
            'F2.2' => ['keuangan', false, 100, null, 'Realisasi belanja kumulatif terhadap pagu belanja DIPA BLU.', $tw, 'Pencocokan LRA dengan OM-SPAN.', 'LRA, OM-SPAN, revisi DIPA'],
            'F2.3' => ['keuangan', false, 110, null, 'Kas dan setara kas dibandingkan kewajiban jangka pendek pada akhir triwulan.', $tw, 'Rekalkulasi dari neraca triwulanan.', 'Neraca, rekening koran'],
            'F2.4' => ['keuangan', true, 100, 'opini', 'Opini auditor independen atas Laporan Keuangan BLU tahun berjalan.', $th, 'Pemeriksaan Laporan Hasil Audit.', 'Laporan auditor independen atas LK BLU'],
            'F2.5' => ['penugasan', false, 110, null, 'Nilai Indikator Kinerja Pelaksanaan Anggaran dari OM-SPAN (penugasan Menteri Keuangan).', $tw, 'Pencocokan dengan OM-SPAN.', 'Cetakan IKPA OM-SPAN'],
            'P1.1' => $layanan('Program studi terakreditasi Unggul/A/internasional terhadap total prodi (target Renstra 2026: 70%).', 'Pencocokan SK akreditasi.', 'SK BAN-PT/LAM, sertifikat akreditasi internasional', true),
            'P1.2' => $layanan('Prodi dan unit yang menjalankan siklus SPMI (PPEPP) lengkap.', 'Audit mutu internal oleh LPM.', 'Laporan AMI, dokumen PPEPP', false, $sm),
            'P2.1' => $layanan('Prodi yang mengimplementasikan integrasi keilmuan dalam kurikulum (target Renstra 2026: 71%).', 'Telaah dokumen kurikulum.', 'Dokumen kurikulum, RPS'),
            'P2.2' => $layanan('Mahasiswa S1 dengan pengalaman magang di luar kampus (target Renstra 2026: 11%).', 'Uji petik laporan magang.', 'Surat tugas & laporan magang'),
            'P3.1' => $layanan('Publikasi ilmiah bereputasi internasional yang telah disitasi.', 'Penelusuran Scopus/SINTA.', 'Rekap Scopus/SINTA'),
            'P3.2' => $layanan('Karya tulis ilmiah berorientasi SDGs yang terbit di jurnal internasional bereputasi.', 'Uji petik pemetaan SDGs.', 'Rekap LPPM, tautan artikel'),
            'P3.3' => $layanan('Skor keseluruhan UIN Sunan Kalijaga pada THE Impact Rankings (IKSP Sasaran Program produktivitas & daya saing).', 'Pencocokan laman THE.', 'Hasil pemeringkatan THE', false, $th),
            'P3.4' => $layanan('Hasil riset dan inovasi yang dimanfaatkan DUDI/masyarakat dengan bukti pemanfaatan.', 'Konfirmasi ke pengguna hasil riset.', 'Surat keterangan pemanfaatan'),
            'P4.1' => ['penugasan', false, 110, null, 'Nilai evaluasi Sistem Akuntabilitas Kinerja Instansi Pemerintah (IKSP Sasaran Program tata kelola).', $th, 'Pencocokan Laporan Hasil Evaluasi SAKIP.', 'LHE SAKIP'],
            'P4.2' => ['penugasan', false, 110, null, 'Nilai Kinerja Anggaran dari aplikasi SMART DJA.', $th, 'Pencocokan dengan aplikasi SMART DJA.', 'Cetakan SMART DJA'],
            'P4.3' => ['penugasan', false, 110, null, 'Rekomendasi hasil pemeriksaan BPK/Itjen/KAP yang selesai ditindaklanjuti.', $tw, 'Pencocokan dengan SIPTL.', 'Rekap SIPTL, bukti tindak lanjut'],
            'P5.1' => ['keuangan', false, 120, null, 'Tingkat pemakaian fasilitas komersial dibandingkan hari tersedia.', $tw, 'Uji petik log pemesanan.', 'Rekap pemesanan, kontrak sewa'],
            'L1.1' => $layanan('Dosen yang memperoleh sertifikasi peningkatan kompetensi (IKSP; target Renstra 2026: 56%).', 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'),
            'L1.2' => $layanan('Dosen tetap berkualifikasi akademik S3 (baseline 2024: 41,32%).', 'Pencocokan ijazah dengan PDDikti.', 'Rekap SIMPEG, ijazah', false, $sm),
            'L1.3' => $layanan('Dosen yang menduduki jabatan lektor/lektor kepala/guru besar (target Renstra 2026: 66%).', 'Pencocokan SK jabatan fungsional.', 'SK jabatan fungsional', false, $sm),
            'L2.1' => $layanan('Tenaga kependidikan ASN yang memperoleh sertifikat kompetensi.', 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'),
            'L2.2' => ['penugasan', false, 110, null, 'Indeks Profesionalitas ASN hasil pengukuran BKN.', $th, 'Pencocokan laporan BKN.', 'Laporan IP ASN'],
            'L3.1' => $layanan('Indeks implementasi transformasi digital layanan akademik & administrasi (skala 5).', 'Reviu PTIPD & SPI.', 'Laporan penilaian mandiri', false, $sm),
            'L3.2' => $layanan('Arsip aktif yang terdigitalisasi, terindeks, dan mudah diakses.', 'Uji petik aplikasi arsip.', 'Rekap aplikasi kearsipan'),
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

    // ------------------------------------------------------------------ Kontrak Kinerja, impor & Rencana Kerja

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
                'reason' => 'Penyesuaian Kontrak Kinerja 2025 dengan PER-6/PB/2025 dan IKSP/IKSK Renstra 2025–2029: melengkapi aspek indikator, trajektori triwulanan, Definisi Operasional, dan indikator wajib.',
                'indicators' => $svc->snapshot($previous), 'proposed_at' => '2025-03-27', 'discussed_at' => '2025-03-27', 'leader_signed_at' => '2025-03-27', 'dg_signed_at' => '2025-03-28',
                'legal_case_free' => true, 'other_requirements_met' => true, 'incentive_notes' => 'Tidak ada persyaratan tambahan dari Pembina Teknis (contoh).',
                'notes' => 'DATA DEMO. Adendum peralihan Pasal 21 PER-6/PB/2025, paling lambat 10 hari kerja setelah 18 Maret 2025.',
            ]);
            $this->importHistory($year);

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
            $i['targets'] = [3 => 8.5, 6 => 8.4, 9 => 8.2, 12 => 8.0];
            $i['annual'] = 8.0;
            $i['change'] = 'target_turun';
            $i['previous_annual'] = 10.0;

            return $i;
        })->all();
        PerformanceContract::create($common + [
            'version' => 1, 'kind' => 'perubahan', 'status' => 'diusulkan', 'number' => null, 'basis' => 'perubahan_kebijakan', 'indicators' => $changed,
            'reason' => 'Simulasi: Pembina Teknis menetapkan daya tampung mahasiswa baru 2026/2027 lebih rendah dari rencana sambil menunggu prodi STEM & FKIK (2027) sehingga target C1.1 diusulkan turun dari 10% (Renstra) menjadi 8%. Bila disetujui, capaian C1.1 paling tinggi 100% (Pasal 19 ayat 8).',
            'proposed_at' => '2026-09-29', 'technical_approval' => 'Surat persetujuan Dirjen Pendidikan Islam atas perubahan target (contoh)',
            'notes' => 'DATA DEMO. Menunggu pembahasan dengan DJPb; tenggat penandatanganan 1 Desember.',
        ]);
        $this->importHistory($year);
    }

    /** Riwayat upload workbook (ilustrasi) supaya halaman Import Data menampilkan contoh hasil import. */
    protected function importHistory(int $year): void
    {
        $period = app(\App\Support\BscContext::class)->period();
        $rows = fn (int $made, int $changed, int $same = 0, int $failed = 0) => ['dibuat' => $made, 'diubah' => $changed, 'sama' => $same, 'gagal' => $failed];
        $at = $year === 2025 ? '2025-02-14 10:30:00' : '2026-01-16 09:15:00';
        $summary = $year === 2025
            ? ['Jati Diri' => $rows(8, 0), 'Nilai Inti' => $rows(3, 0), 'Tema' => $rows(4, 0), 'Sasaran' => $rows(14, 0), 'Indikator' => $rows(39, 0, 0, 2), 'Target' => $rows(39, 0)]
            : ['Sasaran' => $rows(0, 2, 12), 'Indikator' => $rows(0, 11, 28), 'Target' => $rows(0, 39), 'Realisasi' => $rows(24, 0, 0, 1)];
        $issues = $year === 2025
            ? [['sheet' => 'Indikator', 'baris' => 27, 'level' => 'error', 'pesan' => 'Satuan "peringkat" tidak dikenal; pakai salah satu satuan baku.'],
                ['sheet' => 'Indikator', 'baris' => 33, 'level' => 'error', 'pesan' => 'Kode sasaran "P6" tidak ada di sheet Sasaran.'],
                ['sheet' => 'Target', 'baris' => 12, 'level' => 'peringatan', 'pesan' => 'Target Desember lebih rendah dari baseline untuk arah "naik".']]
            : [['sheet' => 'Realisasi', 'baris' => 18, 'level' => 'error', 'pesan' => 'Realisasi Januari untuk IK tahunan diabaikan.']];
        $import = DataImport::create([
            'company_id' => $this->company->id, 'analysis_period_id' => $period->id, 'user_id' => $this->id('strategi'),
            'file_name' => $year === 2025 ? 'Renstra-UINSUKA-2025-2029-IKSP-IKSK.xlsx' : 'Target-Realisasi-UINSUKA-2026-TW-awal.xlsx',
            'status' => 'selesai', 'options' => ['approve_actuals' => false], 'summary' => $summary, 'issues' => $issues,
            'rows_ok' => array_sum(array_map(fn ($s) => $s['dibuat'] + $s['diubah'] + $s['sama'], $summary)),
            'rows_failed' => array_sum(array_column($summary, 'gagal')),
        ]);
        $import->forceFill(['created_at' => $at, 'updated_at' => $at])->saveQuietly();
    }

    /** Contoh draf Rencana Kerja (mode contoh, tanpa API) supaya halaman Rencana Kerja langsung punya riwayat. */
    protected function workPlan(int $year): void
    {
        $svc = app(WorkPlanService::class);
        foreach ([$year, $year + 1] as $planYear) {
            $facts = $svc->facts($planYear);
            WorkPlanDraft::create([
                'year' => $planYear, 'title' => 'Rencana Kerja '.$facts['instansi'].' Tahun '.$planYear, 'mode' => 'contoh', 'status' => 'selesai',
                'instructions' => $planYear === $year ? 'Fokus pada implementasi enam lingkup transformasi Renstra 2025–2029.' : 'Siapkan pembukaan prodi STEM & FKIK 2027 dan pembangunan Kampus 2 Pajangan.',
                'attachments' => [['name' => 'Renstra-UIN-Sunan-Kalijaga-2025-2029.pdf', 'size_kb' => 8650, 'type' => 'pdf']],
                'content' => ['facts' => $facts, 'ai' => $svc->sample($facts)], 'created_by' => $this->id('strategi'),
            ]);
        }
    }

    /** Jejak audit ilustratif (seeder tidak tercatat otomatis) agar menu Log Audit menampilkan alur input → verifikasi → persetujuan. */
    protected function auditTrail(): void
    {
        $log = function (string $user, string $event, $model, string $label, ?array $old, ?array $new, string $at, string $url) {
            AuditLog::create(['user_id' => $this->id($user), 'company_id' => $this->company->id, 'event' => $event, 'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(), 'auditable_label' => $label, 'old_values' => $old, 'new_values' => $new,
                'url' => 'https://kinera.example/admin/'.$url, 'ip_address' => '10.10.'.random_int(1, 20).'.'.random_int(2, 250),
                'user_agent' => 'Mozilla/5.0 (DATA DEMO)', 'created_at' => $at]);
        };
        mt_srand(77);
        $actuals = KpiActual::with('kpi')->whereIn('month', [8, 9])->orderBy('month')->orderBy('kpi_id')->get();
        foreach ($actuals as $a) {
            $code = $a->kpi->code;
            $owner = collect($this->u)->search(fn ($u) => $u->id === $a->submitted_by) ?: 'analis';
            $d = $a->month === 8 ? '2026-09-0' : '2026-10-0';
            $log($owner, 'dibuat', $a, $code.' bulan '.$a->month, null, ['actual' => $a->actual, 'status' => 'draf'], $d.'5 10:'.sprintf('%02d', mt_rand(0, 59)).':00', 'kpi-actuals/create');
            if (in_array($a->status, ['terverifikasi', 'disetujui'], true)) {
                $log('cfo', 'diubah', $a, $code.' bulan '.$a->month, ['status' => 'draf'], ['status' => 'terverifikasi'], $d.'7 15:'.sprintf('%02d', mt_rand(0, 59)).':00', 'kpi-actuals/'.$a->id.'/edit');
            }
            if ($a->status === 'disetujui') {
                $log('dirut', 'diubah', $a, $code.' bulan '.$a->month, ['status' => 'terverifikasi'], ['status' => 'disetujui'], $d.'8 11:'.sprintf('%02d', mt_rand(0, 59)).':00', 'kpi-actuals/'.$a->id.'/edit');
            }
            if ($a->status === 'dikembalikan') {
                $log('cfo', 'diubah', $a, $code.' bulan '.$a->month, ['status' => 'draf'], ['status' => 'dikembalikan', 'return_note' => $a->return_note], '2026-10-07 14:00:00', 'kpi-actuals/'.$a->id.'/edit');
            }
        }
        $kpi = Kpi::where('code', 'P1.1')->first();
        $log('strategi', 'diubah', $kpi, 'P1.1', ['annual_target' => 72], ['annual_target' => 70], '2026-01-16 09:20:00', 'kpis/'.$kpi->id.'/edit');
        foreach (StrategicInitiative::orderBy('code')->take(4)->get() as $i => $ini) {
            $log($i % 2 ? 'akademik' : 'strategi', 'diubah', $ini, $ini->code, ['progress' => max(0, (int) $ini->progress - 15)], ['progress' => (int) $ini->progress], '2026-10-1'.($i + 3).' 16:05:00', 'strategic-initiatives/'.$ini->id.'/edit');
        }
        $contract = PerformanceContract::where('version', 1)->first();
        $log('strategi', 'dibuat', $contract, 'Usulan perubahan Kontrak Kinerja 2026', null, ['kind' => 'perubahan', 'status' => 'diusulkan'], '2026-09-29 13:40:00', 'performance-contracts/create');
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Gedung Rektorat, Kampus UIN Sunan Kalijaga';
        if ($year === 2025) {
            $r['renstra'] = PerformanceReview::create([
                'title' => 'Penetapan Renstra 2025–2029 & Cascading IKSP/IKSK', 'review_type' => 'tahunan', 'review_date' => '2025-02-10', 'period_label' => 'Awal 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Dekan, Kepala Biro, Ketua Lembaga, Senat',
                'summary' => "Renstra 2025–2029 ditetapkan: 8 Sasaran Program, IKSP/IKSK, 7 strategi, 6 lingkup transformasi.\nPeta jalan: 2025 pematangan, 2026 implementasi, 2027 prodi STEM & FKIK, 2028 Kampus 2, 2029 visi tercapai.",
                'decisions' => "1. Peta strategi Kinera disusun dari IKSP/IKSK Renstra.\n2. Setiap IKSK punya penanggung jawab & Definisi Operasional.\n3. Kontrak Kinerja BLU diselaraskan dengan Renstra.",
                'follow_up' => 'Bagian Perencanaan mengunggah workbook IKSP/IKSK ke Kinera (14 Februari 2025).', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kinerja & Kontrak Kinerja 2025', 'review_type' => 'tahunan', 'review_date' => '2026-01-14', 'period_label' => 'Tahun 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Dekan, Kepala Biro, Ketua Lembaga, SPI',
                'summary' => "PNBP Rp219,5 M (99,48% target) sehingga syarat insentif PNBP tidak terpenuhi.\nEBITDA ±Rp71,9 M, surplus operasional Rp39,7 M.\nPendapatan kerja sama ±29% estimasi: di bawah ambang 65%.\nAkreditasi Unggul/A 69% (target 70%); dosen S3 42,6% (target 43%) — angka simulasi.",
                'decisions' => "1. Target 2026 mengikuti Matriks Kinerja Renstra.\n2. Kerja sama satu pintu & target pendapatan per fakultas.\n3. Percepatan studi S3 dan jabatan guru besar.",
                'follow_up' => 'Usulan Kontrak Kinerja 2026 sudah diajukan 30 Desember 2025.', 'status' => 'final', 'recorded_by' => $rec,
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
                    1 => 'Kick-off tahun implementasi transformasi; rancangan statuta, kebijakan keuangan & SDM dibagi ke tim.',
                    2 => 'Pembayaran UKT semester genap selesai; PNBP Februari sesuai pola musiman.',
                    3 => 'Triwulan I ditutup; laporan capaian disiapkan untuk DJPb & Ditjen Pendis.',
                    5 => 'Masterplan Kampus 2 Pajangan dipresentasikan; skema SBSN/PHLN dibahas.',
                    6 => 'Triwulan II: pendapatan kerja sama ±60% target; riset dimanfaatkan DUDI tertinggal.',
                    8 => 'Daftar ulang mahasiswa baru; indikasi daya tampung lebih rendah dari rencana.',
                    default => 'Tinjauan indikator merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Laporan capaian TW I dikirim paling lambat 17 April 2026.',
                    5 => 'Lanjutkan DED tahap 1 Kampus 2; ajukan usulan SBSN 2027.',
                    6 => 'Percepat penagihan kerja sama; klinik hilirisasi riset bersama mitra DUDI.',
                    8 => 'Siapkan kajian usulan perubahan Kontrak Kinerja (Pasal 19) sebelum 30 September.',
                    default => 'Lanjutkan rencana aksi; penanggung jawab IK merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-15', 'Triwulan I 2026'], [2, '2026-07-15', 'Triwulan II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kinerja — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Pimpinan, SPI, Dewan Pengawas (pengamat)',
                'summary' => $q === 1 ? 'PNBP TW I 98% trajektori; kerja sama 58%; IKSP layanan sesuai trajektori.' : 'PNBP s.d. TW II 98,5%; kerja sama di bawah 65%; sertifikasi kompetensi mahasiswa & tendik tertinggal.',
                'decisions' => $q === 1 ? 'SPI memverifikasi dokumen pendukung sebelum laporan dikirim.' : 'Sertifikasi tendik gelombang 2; LSP untuk uji kompetensi mahasiswa; satgas pendapatan kerja sama.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['dewas'] = PerformanceReview::create([
            'title' => 'Rapat Dewan Pengawas — Semester I 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-24', 'period_label' => 'Semester I 2026',
            'chair' => 'Ketua Dewan Pengawas', 'venue' => $venue, 'participants' => 'Dewan Pengawas, Rektor, Wakil Rektor II, SPI',
            'summary' => 'Dewas menyoroti pendapatan kerja sama di bawah 65% dan kesiapan pendanaan Kampus 2.',
            'decisions' => 'Rektor melaporkan rencana pemulihan pendapatan kerja sama setiap bulan.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['dewas']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon simulasi dampak usulan perubahan target terhadap capaian total dan syarat insentif sebelum diajukan ke DJPb.']);
        ReviewNote::create(['performance_review_id' => $r['q2']->id, 'user_id' => $this->id('akademik'),
            'note' => 'Prodi STEM baru membutuhkan dosen S3 bidang sains; usulkan formasi 2027.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Pimpinan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
            'summary' => 'Realisasi September sebagian masih draf; data tracer dikembalikan untuk validasi. Laporan TW III berisiko melewati tenggat 21 Oktober.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi TW III disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo; pagu 2024 Renstra Rp429,7 M.';

            return [
                'defs' => [
                    'renstra' => ['UIN-25-01', 'Pematangan rencana transformasi (Renstra 2025–2029)', 'Penyusunan Renstra, cascading IKSP/IKSK, dan rancangan enam lingkup transformasi.', 'T3', 'strategi', '2025-01-06', '2025-12-31', 'selesai', 5, 5, 2, 1, ['P4', 'F2'], 'Renstra ditetapkan & IKSK punya penanggung jawab.'],
                    'adendum' => ['UIN-25-02', 'Adendum Kontrak Kinerja sesuai PER-6/PB/2025', 'Menyusun aspek indikator, trajektori, dan Definisi Operasional bersama Pembina Teknis & DJPb.', 'T3', 'strategi', '2025-03-18', '2025-04-01', 'selesai', 5, 5, 2, 1, ['P4', 'F2'], 'Adendum ditandatangani tepat waktu.'],
                    'akreditasi' => ['UIN-25-03', 'Percepatan akreditasi Unggul & internasional', 'Pendampingan borang & visitasi.', 'T1', 'lpm', '2025-01-15', '2025-12-15', 'selesai', 5, 4, 3, 3, ['P1'], '70% prodi Unggul/A/internasional.'],
                    'aset' => ['UIN-25-04', 'Optimalisasi University Hotel & convention hall', 'Paket MICE dan kemitraan agen.', 'T3', 'bisnis', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 3, 3, ['P5', 'F1'], 'Pendapatan aset Rp9 M.'],
                    'sdm' => ['UIN-25-05', 'Sertifikasi kompetensi dosen & tendik', 'Kerja sama LSP dan pelatihan.', 'T4', 'sdm', '2025-03-01', '2025-11-30', 'selesai', 4, 3, 2, 2, ['L1', 'L2'], '53% dosen & 20% tendik bersertifikat.'],
                ],
                'activities' => [
                    ['renstra', 'Analisis capaian 2020–2024', 'strategi', '2025-01-06', '2025-01-31', 100], ['renstra', 'Penetapan Renstra & cascading IKSK', 'dirut', '2025-02-01', '2025-02-28', 100],
                    ['renstra', 'Rancangan enam lingkup transformasi', 'strategi', '2025-03-01', '2025-12-31', 100],
                    ['adendum', 'Penyusunan Definisi Operasional', 'strategi', '2025-03-18', '2025-03-25', 100], ['adendum', 'Penandatanganan adendum', 'dirut', '2025-03-26', '2025-03-28', 100],
                    ['akreditasi', 'Pendampingan borang & visitasi', 'lpm', '2025-01-15', '2025-12-15', 100],
                    ['aset', 'Pemasaran paket MICE', 'bisnis', '2025-02-01', '2025-12-31', 100],
                    ['sdm', 'Uji kompetensi dosen & tendik', 'sdm', '2025-03-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['renstra', 'Renstra 2025–2029 ditetapkan', '2025-02-28', true], ['adendum', 'Adendum diterima DJPb', '2025-03-28', true],
                    ['akreditasi', 'SK akreditasi gelombang 2025 terbit', '2025-12-15', true],
                ],
                'budgets' => [
                    ['renstra', 'Penyusunan Renstra & lokakarya', 'opex', 650_000_000, 610_000_000, $note, 'PNBP BLU'],
                    ['akreditasi', 'Pendampingan & visitasi akreditasi', 'opex', 3_500_000_000, 3_120_000_000, $note, 'PNBP BLU'],
                    ['aset', 'Renovasi ruang pertemuan', 'capex', 6_000_000_000, 4_850_000_000, $note, 'PNBP BLU'],
                    ['sdm', 'Uji kompetensi & pelatihan', 'opex', 2_000_000_000, 1_640_000_000, $note, 'Rupiah Murni'],
                ],
            ];
        }

        return [
            'defs' => [
                'akademik' => ['UIN-26-01', 'Transformasi akademik: prodi STEM, S2/S3, double degree, IUP & fast track', 'Strategi 1 Renstra: pengembangan prodi sains & teknologi, rekognisi pembelajaran lampau, pembukaan prodi S2/S3, double degree, IUP, dan fast track.', 'T1', 'akademik', '2026-01-15', '2027-08-31', 'berjalan', 5, 4, 3, 4, ['P2', 'C2', 'P1'], 'Izin prodi STEM & FKIK untuk dibuka 2027; 71% prodi terintegrasi.'],
                'kelembagaan' => ['UIN-26-02', 'Transformasi kelembagaan & siklus kinerja menuju PTNBH', 'Strategi 2 Renstra: statuta baru, kebijakan keuangan & SDM, siklus Kontrak Kinerja & SAKIP di Kinera.', 'T3', 'dirut', '2026-01-05', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['P4', 'F2'], 'Rancangan statuta disetujui senat; laporan triwulanan tepat waktu.'],
                'kampus2' => ['UIN-26-03', 'Pembangunan Kampus 2 Pajangan (Forest Campus)', 'Strategi 3 Renstra: masterplan, DED, dan skema pendanaan SBSN/PHLN untuk lahan ±71 ha.', 'T4', 'cfo', '2026-02-01', '2028-12-31', 'berjalan', 5, 4, 4, 4, ['L3', 'C1'], 'Kampus 2 terbangun 2028 (peta jalan Renstra).'],
                'sdm' => ['UIN-26-04', 'Transformasi SDM: sertifikasi, studi S3 & guru besar', 'Strategi 4 Renstra: kapasitas pimpinan, dosen, dan tendik untuk internasionalisasi.', 'T4', 'sdm', '2026-01-15', '2026-12-15', 'berjalan', 4, 4, 2, 3, ['L1', 'L2'], '45% dosen S3, 66% lektor ke atas, 24% tendik bersertifikat.'],
                'riset' => ['UIN-26-05', 'Penguatan budaya riset & inovasi SDGs', 'Strategi 5 Renstra: hibah riset kolaboratif, klinik publikasi internasional, pendampingan paten, knowledge enterprise.', 'T2', 'lppm', '2026-02-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['P3'], '150 artikel SDGs, 15 hasil riset dimanfaatkan DUDI.'],
                'bisnis' => ['UIN-26-06', 'Kewirausahaan & unit bisnis (University Hotel, campus service centre)', 'Strategi 6 Renstra: inkubator wirausaha mahasiswa, fullboard sebagian kegiatan di University Hotel, ruang terbuka sebagai aset produktif.', 'T3', 'bisnis', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['P5', 'F1', 'C2'], 'Utilisasi 60%, pendapatan aset Rp8 M.'],
                'arsip' => ['UIN-26-08', 'Digitalisasi arsip & penguatan PPID', 'Pemindaian arsip aktif, klasifikasi, dan layanan informasi publik daring (IKSK.2135.1.15 & 2135.1.17).', 'T4', 'ti', '2026-03-01', '2026-12-31', 'berjalan', 3, 2, 2, 2, ['L3'], '60% arsip aktif terdigitalisasi & mudah diakses.'],
                'cabang' => ['UIN-26-09', 'Kajian UIN cabang Malaysia/Brunei', 'Rancangan arah kebijakan Renstra: kajian kelayakan kampus cabang luar negeri (biaya & risiko tinggi).', 'T2', 'kemahasiswaan', '2026-07-01', '2027-06-30', 'tertunda', 3, 1, 4, 4, ['C4'], 'Rekomendasi lanjut/tunda berbasis kajian kelayakan.'],
                'kerjasama' => ['UIN-26-07', 'Kemitraan & kerja sama strategis satu pintu', 'Strategi 7 Renstra: katalog layanan, target pendapatan per fakultas, IUP & mahasiswa internasional.', 'T2', 'kemahasiswaan', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['C4', 'F1'], 'Pendapatan kerja sama Rp2,4 M; 88% prodi bermitra.'],
            ],
            'activities' => [
                ['akademik', 'Naskah akademik prodi STEM & FKIK', 'akademik', '2026-01-15', '2026-06-30', 100], ['akademik', 'Usulan izin prodi ke Kemenag', 'akademik', '2026-07-01', '2026-12-31', 40],
                ['akademik', 'Kurikulum integrasi keilmuan seluruh prodi', 'akademik', '2026-02-01', '2026-11-30', 65], ['akademik', 'Skema fast track & double degree', 'akademik', '2026-03-01', '2026-12-31', 35],
                ['kelembagaan', 'Rancangan statuta baru', 'sdm', '2026-01-05', '2026-09-30', 85], ['kelembagaan', 'Kebijakan keuangan & strategi bisnis', 'cfo', '2026-02-01', '2026-10-31', 70],
                ['kelembagaan', 'Kontrak Kinerja 2026 ditandatangani', 'strategi', '2026-01-05', '2026-01-30', 100], ['kelembagaan', 'Laporan capaian TW I & II', 'strategi', '2026-04-01', '2026-07-21', 100],
                ['kelembagaan', 'Laporan capaian TW III', 'strategi', '2026-10-01', '2026-10-21', 60], ['kelembagaan', 'Usulan perubahan Kontrak Kinerja', 'strategi', '2026-08-15', '2026-09-30', 100],
                ['kampus2', 'Masterplan Forest Campus', 'cfo', '2026-02-01', '2026-06-30', 100], ['kampus2', 'DED tahap 1', 'cfo', '2026-06-01', '2027-03-31', 35],
                ['kampus2', 'Usulan pendanaan SBSN 2027', 'cfo', '2026-05-01', '2026-11-30', 60],
                ['sdm', 'Beasiswa studi S3 dosen', 'sdm', '2026-01-15', '2026-12-15', 70], ['sdm', 'Klinik jabatan guru besar', 'sdm', '2026-02-01', '2026-12-15', 60],
                ['sdm', 'Sertifikasi tendik gelombang 2', 'sdm', '2026-07-01', '2026-12-15', 30],
                ['riset', 'Hibah riset kolaboratif SDGs', 'lppm', '2026-02-01', '2026-11-30', 75], ['riset', 'Klinik publikasi internasional', 'lppm', '2026-03-01', '2026-12-31', 60],
                ['riset', 'Pendampingan paten & hilirisasi DUDI', 'lppm', '2026-04-01', '2026-12-31', 30],
                ['bisnis', 'Paket fullboard University Hotel', 'bisnis', '2026-01-01', '2026-06-30', 100], ['bisnis', 'Inkubator wirausaha mahasiswa', 'kemahasiswaan', '2026-03-01', '2026-12-31', 55],
                ['bisnis', 'Campus service centre', 'bisnis', '2026-05-01', '2026-12-31', 40],
                ['arsip', 'Pemindaian arsip aktif fakultas', 'ti', '2026-03-01', '2026-11-30', 70], ['arsip', 'Portal PPID daring', 'ti', '2026-05-01', '2026-10-31', 90],
                ['cabang', 'Kajian kelayakan & regulasi negara mitra', 'kemahasiswaan', '2026-07-01', '2027-03-31', 10],
                ['kerjasama', 'Katalog layanan kerja sama', 'kemahasiswaan', '2026-01-15', '2026-03-31', 100], ['kerjasama', 'Penagihan & pelaporan terpusat', 'cfo', '2026-04-01', '2026-12-31', 55],
                ['kerjasama', 'Program IUP & rekrutmen mahasiswa internasional', 'kemahasiswaan', '2026-03-01', '2026-12-31', 45],
            ],
            'milestones' => [
                ['kelembagaan', 'Kontrak Kinerja 2026 ditandatangani', '2026-01-30', true], ['kelembagaan', 'Laporan TW II terkirim', '2026-07-21', true], ['kelembagaan', 'Laporan TW III terkirim', '2026-10-21', false],
                ['kelembagaan', 'Rancangan statuta disetujui senat', '2026-11-30', false],
                ['kampus2', 'Masterplan Kampus 2 disetujui', '2026-06-30', true], ['kampus2', 'Kampus 2 terbangun', '2028-12-31', false],
                ['akademik', 'Naskah akademik prodi STEM & FKIK', '2026-06-30', true], ['akademik', 'Prodi STEM & FKIK dibuka', '2027-08-31', false],
                ['bisnis', 'Paket fullboard diluncurkan', '2026-03-31', true], ['kerjasama', 'Pendapatan kerja sama Rp1,8 M (TW III)', '2026-09-30', false],
                ['riset', '100 artikel SDGs terbit', '2026-09-30', true],
                ['arsip', 'Portal PPID diluncurkan', '2026-08-31', true], ['cabang', 'Rekomendasi kajian ke Senat', '2027-06-30', false],
            ],
            'budgets' => [
                ['akademik', 'Naskah akademik & izin prodi baru', 'opex', 2_400_000_000, 1_350_000_000, null, 'PNBP BLU'],
                ['kelembagaan', 'Penyusunan statuta & kebijakan PTNBH', 'opex', 900_000_000, 560_000_000, null, 'PNBP BLU'],
                ['kelembagaan', 'Implementasi Kinera & pelatihan pengelola kinerja', 'opex', 450_000_000, 380_000_000, null, 'PNBP BLU'],
                ['kampus2', 'Masterplan & DED Kampus 2', 'capex', 12_000_000_000, 4_100_000_000, null, 'PNBP BLU & SBSN (rencana)'],
                ['sdm', 'Beasiswa S3 & sertifikasi kompetensi', 'opex', 5_200_000_000, 3_050_000_000, null, 'Rupiah Murni'],
                ['riset', 'Hibah riset & publikasi', 'opex', 9_500_000_000, 6_400_000_000, null, 'Rupiah Murni'],
                ['bisnis', 'Renovasi kamar University Hotel', 'capex', 7_500_000_000, 5_200_000_000, null, 'PNBP BLU'],
                ['kerjasama', 'Pemasaran & administrasi kerja sama', 'opex', 1_500_000_000, 820_000_000, null, 'PNBP BLU'],
                ['arsip', 'Pemindaian & aplikasi arsip', 'capex', 650_000_000, 410_000_000, null, 'PNBP BLU'],
                ['cabang', 'Kajian kelayakan kampus cabang', 'opex', 1_200_000_000, 60_000_000, null, 'PNBP BLU'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Target PNBP 2025 tidak tercapai (99,48%)', 'PNBP Rp219,5 M dari target Rp220,7 M; syarat insentif Pasal 14 (4) d tidak terpenuhi.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 1152608324, 'F1.1', null, 'komisaris', '2026-01-14',
                [['Pemantauan PNBP bulanan & proyeksi akhir tahun', 'cfo', '2026-03-31', 'selesai', 100]], ['2026-04-10', 'Pemantauan PNBP bulanan berjalan di Kinera.']],
            ['Pendapatan kerja sama di bawah 65% target', 'Rp0,77 M dari estimasi Rp2,65 M; indikator F1.3 di bawah ambang Pasal 14 (4) c.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 1882966201, 'F1.3', 'aset', 'cfo', '2026-01-14',
                [['Pusat kerja sama satu pintu', 'kemahasiswaan', '2026-06-30', 'berjalan', 70]], null],
            ['Akreditasi Unggul/A di bawah target', '69% dari target 70% karena dua prodi turun peringkat pada reakreditasi (simulasi).', 'audit', null, 'proses', 'sedang', null, 'P1.1', 'akreditasi', 'lpm', '2025-12-20',
                [['Klinik reakreditasi prodi', 'lpm', '2026-06-30', 'selesai', 100]], ['2026-07-02', 'Dua prodi diajukan reakreditasi ulang.']],
        ] : [
            ['Pendapatan kerja sama masih di bawah ambang 65%', 'Capaian s.d. TW II ±60% trajektori; menggugurkan syarat insentif huruf c bila berlanjut.', 'timwas', 'dewas', 'keuangan', 'kritis', null, 'F1.3', 'kerjasama', 'komisaris', '2026-07-24',
                [['Satgas pendapatan kerja sama', 'kemahasiswaan', '2026-12-31', 'berjalan', 50], ['Penagihan terpusat oleh Biro AUPK', 'cfo', '2026-10-31', 'berjalan', 60]], null],
            ['Sertifikasi kompetensi mahasiswa tertinggal', 'Realisasi s.d. TW II ±80% trajektori; LSP baru terbentuk.', 'tinjauan', 'q2', 'pelanggan', 'tinggi', null, 'C2.3', 'akademik', 'dirut', '2026-07-15',
                [['Kerja sama LSP & uji kompetensi semester ganjil', 'akademik', '2026-12-15', 'berjalan', 40]], null],
            ['Hilirisasi riset ke DUDI di bawah target', 'Baru 8 dari 15 hasil riset memiliki bukti pemanfaatan.', 'tinjauan', 'q2', 'proses', 'sedang', null, 'P3.4', 'riset', 'lppm', '2026-07-15',
                [['Temu bisnis riset dengan mitra DUDI', 'lppm', '2026-11-30', 'berjalan', 35]], null],
            ['Sertifikasi tendik tertinggal dari trajektori', 'Realisasi TW II 19% dari trajektori 21%.', 'tinjauan', 'q2', 'sdm', 'sedang', null, 'L2.1', 'sdm', 'dirut', '2026-07-15',
                [['Sertifikasi tendik gelombang 2', 'sdm', '2026-12-15', 'berjalan', 30]], null],
            ['Dokumen pendukung laporan TW I belum lengkap', 'SPI menemukan 4 indikator layanan tanpa dokumen pendukung saat verifikasi internal.', 'audit', null, 'proses', 'sedang', null, 'P3.1', 'kelembagaan', 'strategi', '2026-04-10',
                [['Unggah dokumen pendukung sebelum persetujuan', 'strategi', '2026-04-15', 'selesai', 100]], ['2026-04-16', 'Dokumen pendukung dilengkapi; laporan TW I terkirim tepat waktu.']],
            ['Pengaduan aksesibilitas gedung fakultas', 'Mahasiswa difabel melaporkan ramp rusak di dua gedung.', 'pelanggan', null, 'pelanggan', 'rendah', 85000000, 'C1.3', null, 'kemahasiswaan', '2026-05-06',
                [['Perbaikan ramp & jalur pemandu', 'cfo', '2026-06-30', 'selesai', 100]], ['2026-07-01', 'Ramp diperbaiki; diverifikasi Pusat Layanan Difabel.']],
            ['Laporan capaian TW III berisiko terlambat', 'Realisasi TW III sebagian masih draf/dikembalikan; tenggat 21 Oktober 2026 (Pasal 10).', 'internal', 'rm9', 'proses', 'tinggi', null, 'C2.1', 'kelembagaan', 'strategi', '2026-10-12',
                [['Validasi tracer study & persetujuan Rektor', 'kemahasiswaan', '2026-10-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        if ($year === 2026) {
            $this->workPlan($year);
            $this->auditTrail();
        }

        return [
            'dependencies' => $year === 2025 ? [
                ['Persetujuan Definisi Operasional oleh Pembina Teknis', 'eksternal', 'adendum', 'P4', 'Ditjen Pendidikan Islam', 'Adendum Kontrak Kinerja menunggu persetujuan indikator layanan.', 'strategi', 'sedang', 'Koordinasi sejak Januari.'],
            ] : [
                ['Persetujuan perubahan Kontrak Kinerja oleh DJPb', 'eksternal', 'kelembagaan', 'P4', 'Direktorat Jenderal Perbendaharaan', 'Perubahan target C1.1 berlaku setelah ditandatangani (paling lambat 1 Desember).', 'strategi', 'tinggi', 'Kajian lengkap & persetujuan Pembina Teknis dilampirkan.'],
                ['Izin pembukaan prodi STEM & FKIK', 'eksternal', 'akademik', 'P2', 'Kementerian Agama & Kemdiktisaintek', 'Pembukaan 2027 bergantung pada izin prodi.', 'akademik', 'tinggi', 'Naskah akademik lengkap & dosen S3 bidang sains disiapkan.'],
                ['Pendanaan Kampus 2 (SBSN/PHLN)', 'eksternal', 'kampus2', 'L3', 'Kementerian Agama, Bappenas & Kementerian Keuangan', 'Pembangunan fisik bergantung pada persetujuan pendanaan.', 'cfo', 'tinggi', 'Pentahapan & DED dari PNBP.'],
                ['Rancangan statuta untuk transformasi PTNBH', 'internal', 'kelembagaan', 'P4', 'Senat & Rektorat', 'Statuta menjadi prasyarat kebijakan keuangan & SDM.', 'sdm', 'sedang', 'Jadwal sidang senat ditetapkan.'],
                ['Data tracer study', 'internal', 'akademik', 'C2', 'Pusat Karier & fakultas', 'IK lulusan bergantung pada respons tracer.', 'kemahasiswaan', 'sedang', 'Insentif pengisian & integrasi SIA.'],
            ],
            'institutionalizations' => [
                ['Siklus laporan capaian Kontrak Kinerja di Kinera', 'sistem', 'Input realisasi tanggal 5 → verifikasi SPI/Keuangan tanggal 7 → persetujuan Rektor tanggal 8 → kirim ke DJPb & Pendis ≤ 15 hari kerja.', 'Triwulanan', 'strategi', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Rapat pimpinan bulanan berbasis Kinera', 'rapat', 'Membahas IK merah/kuning dan tindak lanjut keputusan.', 'Bulanan', 'dirut', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Rapat Dewan Pengawas', 'rapat', 'Pengawasan capaian kinerja dan syarat insentif.', 'Triwulanan', 'dirut', 'melembaga'],
                ['Audit Mutu Internal (SPMI)', 'sop', 'Siklus PPEPP di seluruh prodi & lembaga.', 'Tahunan', 'lpm', 'melembaga'],
                ['Tracer study lulusan', 'sop', 'Survei lulusan untuk IKSP masa tunggu & IKSK bekerja ≤ 1 tahun.', 'Tahunan', 'kemahasiswaan', 'berjalan'],
                ['Pelatihan pengelola kinerja fakultas', 'pelatihan', 'Penanggung jawab IKSK fakultas dilatih menyusun Definisi Operasional & bukti.', 'Semesteran', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }

    protected function indicatorType(string $code, StrategicObjective $objective): string
    {
        return self::INDICATOR_TYPE_OVERRIDES[$code] ?? parent::indicatorType($code, $objective);
    }
}
