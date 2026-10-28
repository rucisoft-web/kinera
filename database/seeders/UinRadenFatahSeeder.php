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
 * Demo satker BLU: Universitas Islam Negeri Raden Fatah Palembang (kode UINRF), bidang layanan pendidikan,
 * Pembina Teknis Direktorat Jenderal Pendidikan Islam Kementerian Agama.
 *
 * DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak:
 * - Perioda 2025 (ditutup): target & realisasi indikator Perjanjian Kinerja serta realisasi anggaran dari LKjIP 2025;
 *   gambaran kelembagaan dari Renstra 2020–2024. Angka PNBP, EBITDA, rincian triwulanan, dan Kontrak Kinerja adalah simulasi.
 * - Perioda 2026 (berjalan, bawaan): seluruh target & realisasi Triwulan I–III adalah PROYEKSI/SIMULASI.
 * - Tidak memakai nama atau NIP pejabat sebenarnya; semua penandatangan bertanda "(contoh)".
 *
 * Pelajaran utama: target Perjanjian Kinerja yang terlalu rendah (capaian sampai 553%, dibatasi capaian maksimum),
 * Definisi Operasional yang lemah (prodi berkontribusi SDGs 116,69%), PNBP yang bergantung pada UKT, dan
 * penyerapan belanja modal 72,55% yang menahan Nilai Kinerja Anggaran.
 */
class UinRadenFatahSeeder extends ShowcaseSeeder
{
    public const CODE = 'UINRF';

    public const LT = 'LKjIP 2025 (Perjanjian Kinerja & realisasi anggaran) dan Renstra 2020–2024 UIN Raden Fatah Palembang';

    public const LKJIP = 'Laporan Kinerja Instansi Pemerintah (LKjIP) UIN Raden Fatah Palembang Tahun 2025';

    public const RENSTRA = 'Renstra UIN Raden Fatah Palembang 2020–2024';

    /** Bobot musiman: UKT semester genap (Jan–Feb) & ganjil (Jul–Agu); belanja modal menumpuk di Triwulan IV. */
    public const SEASON = [
        2025 => [1 => .082, .088, .070, .070, .072, .074, .092, .098, .080, .084, .088, .102],
        2026 => [1 => .084, .088, .070, .072, .072, .074, .090, .097, .080, .084, .088, .101],
    ];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'UIN Raden Fatah Palembang', 'legal_name' => 'Universitas Islam Negeri Raden Fatah Palembang (Satker BLU)',
            'industry' => 'Pendidikan tinggi keagamaan Islam (BLU)', 'blu_field' => 'pendidikan',
            'ministry' => 'Kementerian Agama', 'technical_supervisor' => 'Direktorat Jenderal Pendidikan Islam',
            'leader_title' => 'Rektor', 'leader_name' => 'Rektor UIN Raden Fatah (contoh)', 'leader_nip' => null,
            'dg_name' => 'Direktur Jenderal Perbendaharaan (contoh)', 'dg_nip' => null,
            'address' => 'Jl. Prof. K.H. Zainal Abidin Fikri No. 1A KM 3,5', 'city' => 'Palembang',
            'phone' => '+62 711 353276', 'website' => 'https://radenfatah.ac.id',
            'description' => 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada '.self::LT.'; angka PNBP & EBITDA, rincian triwulanan, Kontrak Kinerja, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Raden Fatah Palembang, Kementerian Agama, maupun Kementerian Keuangan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Target & realisasi indikator layanan mengacu pada Perjanjian Kinerja dalam LKjIP 2025; angka PNBP/EBITDA dan rincian triwulanan ilustratif.'
            : 'DATA DEMO — PROYEKSI 2026: Triwulan I–II disetujui, Triwulan III dalam proses verifikasi; usulan perubahan target akreditasi menunggu DJPb.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['F2.4', 'C2.1', 'C2.3', 'C3.1', 'P1.2', 'P1.3', 'L1.1'];
    }

    protected function missingSeptember(): array
    {
        return ['L2.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['L1.2', 'Persentase tendik bersertifikat Triwulan III sama persis dengan dosen (63,81%), seperti pada LKjIP 2025. Mohon hitung ulang dari SIMPEG khusus tendik dan lampirkan daftar sertifikat.'];
    }

    protected function criticalIn2026(): array
    {
        return ['F2.2'];
    }

    protected function maturityPattern(): array
    {
        return [2, 3, 2, 2, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Kinera UIN RF (contoh)', 'uinrf.admin', 'Admin Sistem', 'Pusat Teknologi Informasi dan Pangkalan Data', ['company-admin']],
            'dirut' => ['Rektor (contoh)', 'uinrf.rektor', 'Rektor / Pemimpin BLU', 'Pimpinan Universitas', ['pimpinan']],
            'cfo' => ['Wakil Rektor II (contoh)', 'uinrf.wr2', 'Wakil Rektor Bidang Administrasi Umum, Perencanaan & Keuangan', 'Pejabat Pengelola Keuangan BLU', ['keuangan']],
            'akademik' => ['Wakil Rektor I (contoh)', 'uinrf.wr1', 'Wakil Rektor Bidang Akademik & Kelembagaan', 'Pimpinan Universitas', ['kepala-unit']],
            'kemahasiswaan' => ['Wakil Rektor III (contoh)', 'uinrf.wr3', 'Wakil Rektor Bidang Kemahasiswaan & Kerja Sama', 'Pimpinan Universitas', ['kepala-unit']],
            'lppm' => ['Ketua LPPM (contoh)', 'uinrf.lppm', 'Ketua Lembaga Penelitian & Pengabdian kepada Masyarakat', 'LPPM', ['kepala-unit']],
            'lpm' => ['Ketua LPM (contoh)', 'uinrf.lpm', 'Ketua Lembaga Penjaminan Mutu', 'LPM', ['kepala-unit', 'editor-jatidiri']],
            'bisnis' => ['Kepala Pusat Pengembangan Bisnis (contoh)', 'uinrf.bisnis', 'Kepala UPT Pusat Pengembangan Bisnis', 'Pusat Pengembangan Bisnis', ['kepala-unit']],
            'sdm' => ['Kepala Bagian Kepegawaian (contoh)', 'uinrf.sdm', 'Kepala Bagian Organisasi, Kepegawaian & Hukum', 'Biro AUPK', ['kepala-unit']],
            'ti' => ['Kepala PTIPD (contoh)', 'uinrf.ti', 'Kepala UPT Teknologi Informasi & Pangkalan Data', 'PTIPD', ['kepala-unit']],
            'internasional' => ['Kepala Pusat Layanan Internasional (contoh)', 'uinrf.internasional', 'Kepala UPT Pusat Layanan Internasional', 'Pusat Layanan Internasional', ['kepala-unit']],
            'strategi' => ['Kepala Bagian Perencanaan (contoh)', 'uinrf.perencanaan', 'Kepala Bagian Perencanaan & Keuangan', 'Biro AUPK', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Ketua Dewan Pengawas (contoh)', 'uinrf.dewas', 'Ketua Dewan Pengawas BLU', 'Dewan Pengawas', ['pengawas']],
            'analis' => ['Analis Data Kinerja (contoh)', 'uinrf.analis', 'Analis Perencanaan & Data Kinerja', 'Biro AUPK', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi Universitas Berstandar Internasional, Berwawasan Kebangsaan, dan Berkarakter Islami.',
            'mission' => "1. Melahirkan sarjana dan komunitas akademik yang berkomitmen pada mutu, keberagamaan, dan kecendekiawanan.\n2. Mengembangkan kegiatan Tri Dharma yang sejalan dengan perkembangan ilmu pengetahuan dan teknologi, relevan dengan kebutuhan bangsa, dan berbasis pada tradisi keilmuan Islam yang integralistik.\n3. Mengembangkan tradisi akademik yang universal, jujur, objektif, dan bertanggung jawab.",
            'purpose' => 'Menyelenggarakan pendidikan tinggi yang terintegrasi, berkeadilan, ramah, dan terjangkau, dengan internasionalisasi Tri Dharma yang berpijak pada nilai kebangsaan dan keislaman.',
            'tagline' => 'Berstandar internasional, berwawasan kebangsaan, berkarakter Islami',
            'brand_promise' => 'Kampus Islam terbesar di Sumatera Selatan yang terbuka dan terjangkau, dengan layanan yang ramah bagi mahasiswa berkebutuhan khusus.',
            'value_proposition' => '9 fakultas dan Pascasarjana, Kampus A di pusat kota dan Kampus B Jakabaring, layanan Ma\'had dan pusat bahasa, serta jejaring kerja sama daerah; dikelola dengan pola keuangan BLU (DATA DEMO).',
            'culture_statement' => 'Mutu, keberagamaan, dan kecendekiawanan; tradisi akademik yang jujur, objektif, dan bertanggung jawab.',
            'history' => "1964: berdiri IAIN Raden Fatah Palembang.\n2014 (Oktober): bertransformasi menjadi Universitas Islam Negeri Raden Fatah Palembang.\n2015–2019: tahap transformatif (penambahan fakultas & prodi, pembangunan Kampus B Jakabaring dengan pembiayaan IsDB).\n2020–2024: Renstra dengan target PNBP BLU Rp105 M dan porsi PNBP 50% dari total pendanaan pada 2024.\n2025–2029: visi berstandar internasional; target Top 100 ASEAN, menuju World Class University pada 2039.\n(Ringkasan dari LKjIP 2025 & Renstra 2020–2024 untuk keperluan demo.)",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-10 10:00:00',
        ]);
        foreach ([
            ['Bermutu', 'Komitmen pada mutu', 'Setiap layanan Tri Dharma diukur dan diperbaiki melalui SPMI.', "Menetapkan target yang menantang, bukan sekadar mudah dicapai\nMenindaklanjuti hasil audit mutu internal"],
            ['Berkarakter Islami', 'Keberagamaan', 'Nilai keislaman yang moderat menjiwai kegiatan akademik dan layanan.', "Pembinaan Ma'had & literasi keagamaan\nMenjaga etika akademik"],
            ['Berwawasan Kebangsaan', 'Kebangsaan', 'Tri Dharma yang mengedepankan kepentingan nasional dan daerah.', "Kerja sama dengan pemerintah daerah Sumatera Selatan\nProgram pengabdian di desa binaan"],
            ['Akuntabel', 'Tata kelola yang baik', 'Kinerja dan anggaran dilaporkan apa adanya dengan bukti yang dapat diuji.', "Definisi Operasional yang jelas untuk setiap indikator\nPenyerapan anggaran sesuai rencana penarikan dana"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Program studi & akreditasi', 'Kompetensi', '9 fakultas (FAH, FDK, FEBI, FISIP, FITK, Psikologi, Saintek, Syariah & Hukum, Ushuluddin & Pemikiran Islam) dan Pascasarjana; porsi prodi Unggul masih kecil (Renstra: 10,64% prodi A pada 2019).', 2 + $lvl, 4, true, 'Percepatan akreditasi Unggul prodi prioritas & APT Unggul.'],
            ['Pendidikan keagamaan & Ma\'had', 'Kompetensi', 'Ma\'had al-Jami\'ah dan pembinaan keberagamaan; indeks keberagamaan mahasiswa 82,25 (LKjIP 2025).', 4, 5, true, 'Integrasikan pembinaan Ma\'had dengan capaian pembelajaran prodi.'],
            ['Layanan inklusi', 'Kompetensi', 'Mahasiswa berkebutuhan khusus naik 100% pada 2025 (LKjIP).', 3, 4, false, 'Unit layanan disabilitas & aksesibilitas Kampus B.'],
            ['SDM dosen & tendik', 'Sumber daya manusia', '972 pegawai (598 dosen, 374 tendik) menurut LKjIP 2025; SDM aparatur belum ideal dan kemampuan bahasa asing terbatas.', 2 + $lvl, 4, true, 'Sertifikasi kompetensi & program bahasa asing dosen.'],
            ['Aset Kampus B Jakabaring', 'Sumber daya fisik', 'Lahan ±35 ha dari Pemprov Sumsel; 9 gedung lima lantai dari pembiayaan IsDB ±Rp450 M (Renstra).', 2, 4, false, 'Pemanfaatan gedung & lahan Kampus B untuk pendapatan non-UKT.'],
            ['Unit bisnis BLU', 'Sumber daya fisik', 'Pusat Pengembangan Bisnis belum optimal; PNBP sebagian besar dari UKT (LKjIP 2025).', 1 + $lvl, 3, false, 'Katalog layanan bisnis & kerja sama berbayar.'],
            ['Sistem informasi & pangkalan data', 'Sistem', 'PTIPD mengelola sistem akademik, keuangan, dan kepegawaian.', 2 + $lvl, 4, false, 'Satu data kinerja untuk laporan Kontrak Kinerja & LKjIP.'],
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
                'Kontrak 2025 di-adendum (Pasal 21); target indikator layanan perlu ditetapkan ulang agar tidak terlalu rendah.'],
            ['Capaian Perjanjian Kinerja 2025 (LKjIP)', '2026-02-06', 'internal', self::LKJIP,
                '15 indikator PK 2025 dengan capaian rata-rata 295,72%: kerja sama ditindaklanjuti 553%, prodi berkontribusi SDGs 467%, mahasiswa berkebutuhan khusus 1000%, sementara peningkatan jumlah mahasiswa 0%. NKA 89,33 (98%).',
                'Target terlalu rendah dan Definisi Operasional lemah membuat capaian tidak informatif; KMA 94/2021 membatasi capaian 120%.'],
            ['Realisasi anggaran 2025', '2026-02-06', 'internal', self::LKJIP,
                'Pagu Rp282,29 M, realisasi Rp258,91 M (91,72%). Belanja modal hanya 72,55% (sisa ±Rp3,99 M); belanja pegawai 92,49%, barang 91,62%, bansos 100%.',
                'Rencana penarikan dana & pengadaan belanja modal perlu dimulai sejak Triwulan I.'],
            ['Renstra 2020–2024: arah kemandirian keuangan', '2025-01-15', 'internal', self::RENSTRA,
                'PNBP BLU 2019 Rp92,6 M (layanan pendidikan Rp87,8 M); target Rp105 M pada 2024. PNBP aset & kerja sama hanya Rp3,18 M (2019) dan Rp0,89 M (2020). Porsi PNBP 20% dari total pendanaan, target 50%.',
                'Ketergantungan pada UKT tinggi; aset Kampus B menjadi peluang pendapatan non-UKT.'],
            ['Persaingan perguruan tinggi di Sumatera Selatan', '2026-01-20', 'eksternal', 'Renstra 2020–2024 & analisis manajemen (ilustrasi)',
                'Pendaftar 2019: 57.397 dengan 4.575 diterima (±8%); pesaing utama PTN besar di Palembang dengan ±74 ribu pendaftar.',
                'Diferensiasi: keilmuan Islam integralistik, biaya terjangkau, dan prodi baru di Kampus B.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-01-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Arah kebijakan PTKIN menuju internasionalisasi', 'Kemenag mendorong PTKIN berdaya saing global dan moderasi beragama.', 'peluang', 4, 4, 'naik', 'Selaraskan Kontrak Kinerja dengan target Top 100 ASEAN.'],
            ['politik', 'Efisiensi belanja negara', 'Kebijakan efisiensi membatasi rupiah murni; sisa belanja modal berisiko dipotong.', 'ancaman', 4, 4, 'naik', 'Percepat belanja modal & perkuat PNBP non-UKT.'],
            ['ekonomi', 'Daya beli keluarga mahasiswa Sumsel', 'Sensitivitas UKT; peningkatan jumlah mahasiswa 2025 nol.', 'ancaman', 4, 4, 'stabil', 'UKT berjenjang, beasiswa mitra daerah, promosi PMB.'],
            ['ekonomi', 'Pertumbuhan kawasan Jakabaring', 'Kawasan olahraga & pemerintahan membuka pasar sewa gedung, pelatihan, dan MICE.', 'peluang', 3, 4, 'naik', 'Pemanfaatan aset Kampus B.'],
            ['sosial', 'Tuntutan kampus inklusif', 'Masyarakat menuntut akses pendidikan bagi penyandang disabilitas.', 'peluang', 3, 4, 'naik', 'Layanan disabilitas terpadu.'],
            ['sosial', 'Relevansi lulusan dengan dunia kerja', 'Masa tunggu kerja lulusan S1 ±7 bulan (Renstra).', 'ancaman', 4, 4, 'naik', 'Sertifikasi kompetensi & pusat karier.'],
            ['teknologi', 'Transformasi digital layanan', 'Layanan akademik daring dan pembelajaran hibrida.', 'peluang', 4, 4, 'naik', 'Satu data & layanan digital terpadu.'],
            ['lingkungan', 'Kampus B di lahan 35 ha', 'Pengembangan kampus memerlukan masterplan hijau dan drainase.', 'peluang', 3, 3, 'stabil', 'Masterplan kampus hijau.'],
            ['hukum', 'PER-6/PB/2025 Kontrak Kinerja Pemimpin BLU', 'Kewajiban trajektori, Definisi Operasional, dan laporan capaian berkala; dasar insentif kelebihan capaian.', 'ancaman', 5, 5, 'naik', 'Definisi Operasional diuji SPI sebelum kontrak ditandatangani.'],
            ['hukum', 'Pemanfaatan BMN oleh BLU', 'Sewa & kerja sama pemanfaatan aset memerlukan persetujuan pengelola barang.', 'ancaman', 3, 4, 'stabil', 'Ajukan persetujuan pemanfaatan BMN sejak awal tahun.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => 'LKjIP 2025, Renstra 2020–2024 & analisis manajemen (ilustrasi demo)']);
        }

        foreach ([
            ['persaingan', 'Persaingan PTN, PTKIN, dan PTS di Sumsel', 'PTN besar di Palembang menarik pendaftar terbanyak (Renstra).', 4, 'Diferensiasi keilmuan Islam integralistik & prodi baru.'],
            ['kekuatan_pembeli', 'Calon mahasiswa & orang tua', 'Sensitif biaya, akreditasi, dan prospek kerja.', 3, 'UKT terjangkau, akreditasi Unggul, layanan karier.'],
            ['kekuatan_pemasok', 'Dosen bergelar doktor & berkemampuan bahasa asing', 'SDM aparatur belum ideal (LKjIP 2025).', 3, 'Rekrutmen & pengembangan dosen terencana.'],
            ['pendatang_baru', 'Kampus cabang & program daring', 'PTS dan program jarak jauh membuka kelas di Sumsel.', 2, 'Program hibrida bermutu.'],
            ['produk_pengganti', 'Kursus sertifikasi & pelatihan kerja', 'Jalur sertifikasi singkat menggantikan sebagian kebutuhan gelar.', 3, 'Microcredential terintegrasi kurikulum.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Mahasiswa sarjana (S1)', 'Pendidikan', 'Layanan pendidikan utama; UKT menjadi sumber terbesar PNBP.', 86, 'stabil', true, 'UKT terjangkau, akreditasi, prospek kerja.', 'Promosi PMB berbasis prodi unggul.'],
            ['Mahasiswa pascasarjana', 'Pendidikan', 'Magister & doktor, termasuk guru dan dosen di Sumsel.', 8, 'naik', true, 'Pembimbing bereputasi, kelas fleksibel.', 'Kelas akhir pekan & kerja sama pemda.'],
            ['Mitra kerja sama (pemda, BUMN, industri)', 'Kerja sama', 'Pelatihan, riset terapan, dan konsultasi.', 2, 'naik', true, 'Solusi tepat guna, pelaporan rapi.', 'Katalog layanan kerja sama berbayar.'],
            ['Pengguna aset Kampus B', 'Aset', 'Sewa gedung, aula, lapangan, dan ruang komersial.', 2, 'naik', true, 'Fasilitas siap pakai, akses mudah.', 'Pengelolaan aset oleh Pusat Pengembangan Bisnis.'],
            ['Mahasiswa berkebutuhan khusus & afirmasi', 'Inklusi', 'Mahasiswa difabel, KIP-Kuliah, daerah 3T.', 2, 'naik', true, 'Aksesibilitas & pendampingan.', 'Layanan inklusi terpadu.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan ilustratif untuk demo']);
        }

        foreach ([
            ['UIN Raden Fatah Palembang', 'PTKIN BLU', 1, 111.9, -2.7, 8.0, true, 'PTKIN terbesar di Sumsel, Kampus B Jakabaring, biaya terjangkau.', 'PNBP bergantung UKT, akreditasi Unggul masih sedikit, SDM bahasa asing terbatas.'],
            ['PTN besar di Palembang', 'PTN', 2, null, null, null, false, 'Reputasi & pendaftar terbanyak.', 'Seleksi ketat & biaya lebih tinggi.'],
            ['PTS besar di Palembang', 'PTS', 3, null, null, null, false, 'Kerja sama industri, kampus kota.', 'Biaya lebih tinggi.'],
            ['PTKIN lain di Sumatera', 'PTKIN', 4, null, null, null, false, 'Bidang kajian serupa, biaya bersaing.', 'Skala & reputasi bervariasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'TA 2025 (simulasi)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'PNBP BLU dalam Rp miliar (angka simulasi demo).' : 'Pembanding kualitatif untuk demo.']);
        }

        foreach ([
            ['Kontrak Kinerja Pemimpin BLU', 'Direktorat Jenderal Perbendaharaan', 'PER-6/PB/2025', 'Kinerja BLU', '2025-03-18', 'Pedoman penyusunan Kontrak Kinerja dan penetapan persetujuan capaian kinerja Pemimpin BLU.', 'Dasar seluruh siklus Kontrak Kinerja di aplikasi ini.', $year === 2025 ? 'sebagian' : 'patuh', 'strategi', $year === 2025 ? '2025-04-01' : null],
            ['Pengelolaan Keuangan BLU', 'Pemerintah', 'PP No. 23/2005 jo. PP No. 74/2012', 'Keuangan BLU', null, 'Fleksibilitas pengelolaan keuangan BLU, tarif layanan, dan remunerasi.', 'Dasar pola pengelolaan keuangan.', 'patuh', 'cfo', null],
            ['Pedoman Pengelolaan BLU', 'Kementerian Keuangan', 'PMK No. 129/PMK.05/2020', 'Keuangan BLU', null, 'Perencanaan, pelaksanaan, pelaporan, dan evaluasi kinerja BLU.', 'RBA, laporan keuangan, penilaian kinerja.', 'patuh', 'cfo', null],
            ['Pengukuran capaian kinerja Kemenag', 'Kementerian Agama', 'KMA No. 94/2021', 'Kinerja', null, 'Batas atas capaian indikator 120% dalam evaluasi kinerja.', 'Capaian 553% pada LKjIP 2025 tidak diakui penuh.', 'sebagian', 'strategi', '2026-03-31'],
            ['Organisasi dan tata kerja UIN', 'Kementerian Agama', 'PMA No. 6/2022', 'Kelembagaan', null, 'Tugas internasionalisasi Tri Dharma dan fungsi layanan pendidikan.', 'Dasar struktur 21 unit kerja.', 'patuh', 'sdm', null],
            ['Pemanfaatan Barang Milik Negara', 'Kementerian Keuangan', 'PMK No. 115/2020', 'Aset', null, 'Sewa & kerja sama pemanfaatan BMN memerlukan persetujuan.', 'Pendapatan aset Kampus B bergantung pada persetujuan.', 'sebagian', 'bisnis', '2026-06-30'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'mahasiswa' => ['Mahasiswa & orang tua', 'eksternal', 'Penerima layanan', 4, 5, 'Pembelajaran bermutu, UKT terjangkau, layanan cepat.', 'Survei kepuasan & layanan pengaduan.', 'Wakil Rektor III'],
            'kemenag' => ['Ditjen Pendidikan Islam (Pembina Teknis)', 'eksternal', 'Pembina Teknis', 5, 5, 'Capaian indikator layanan & kepatuhan kebijakan.', 'Persetujuan Definisi Operasional & laporan capaian triwulanan.', 'Biro AUPK'],
            'djpb' => ['Ditjen Perbendaharaan (Pembina Keuangan)', 'eksternal', 'Pembina Keuangan', 5, 4, 'Kontrak Kinerja, laporan capaian tepat waktu, kinerja keuangan BLU.', 'Pembahasan Kontrak Kinerja & verifikasi capaian.', 'Wakil Rektor II'],
            'dewas' => ['Dewan Pengawas BLU', 'internal', 'Pengawas', 5, 5, 'Pengawasan kinerja & tata kelola.', 'Rapat Dewan Pengawas triwulanan.', 'Sekretariat Dewas'],
            'dosen' => ['Dosen (598 orang)', 'internal', 'SDM', 4, 5, 'Karier, riset, kemampuan bahasa asing.', 'Senat & forum fakultas.', 'Bagian Kepegawaian'],
            'tendik' => ['Tenaga kependidikan (374 orang)', 'internal', 'SDM', 3, 4, 'Pengembangan kompetensi & kesejahteraan.', 'Pelatihan & sertifikasi.', 'Bagian Kepegawaian'],
            'pemda' => ['Pemerintah Provinsi Sumsel & kabupaten/kota', 'eksternal', 'Mitra', 4, 4, 'Lulusan & riset untuk pembangunan daerah; pemanfaatan lahan Kampus B.', 'Kerja sama tri dharma & beasiswa daerah.', 'Wakil Rektor III'],
            'mitra' => ['Mitra industri & luar negeri', 'eksternal', 'Mitra', 3, 4, 'Kerja sama berdampak & dilaporkan.', 'Pusat Layanan Internasional & kerja sama.', 'Wakil Rektor III'],
            'bpk' => ['Auditor (BPK, KAP, Itjen)', 'eksternal', 'Regulator', 4, 3, 'Laporan keuangan & kinerja andal.', 'Tindak lanjut temuan tepat waktu.', 'SPI'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'target' => ['Target Perjanjian Kinerja terlalu rendah', 'Capaian rata-rata 295,72%; kerja sama 553%, SDGs 467%, mahasiswa berkebutuhan khusus 1000% (LKjIP 2025). Capaian di atas 120% tidak diakui.', 'proses', 5, 5, 'strategi', ['kemenag', 'djpb']],
            'mahasiswa' => ['Jumlah mahasiswa tidak bertambah', 'Peningkatan mahasiswa 2025 0% dari target 2,5% (LKjIP); indikator paling rendah.', 'pelanggan', 5, 5, 'kemahasiswaan', ['mahasiswa', 'kemenag']],
            'pnbp' => ['PNBP bergantung pada UKT', 'Unit bisnis belum optimal; pendapatan aset & kerja sama kecil (LKjIP 2025, Renstra).', 'keuangan', 5, 5, 'cfo', ['djpb', 'dewas']],
            'modal' => ['Penyerapan belanja modal rendah', 'Belanja modal 72,55%, sisa ±Rp3,99 M; penyerapan total 91,72% (LKjIP 2025).', 'keuangan', 4, 4, 'cfo', ['djpb', 'bpk']],
            'sdm' => ['SDM aparatur & kemampuan bahasa asing', 'SDM belum ideal; internasionalisasi terkendala bahasa asing (LKjIP 2025).', 'sdm', 4, 4, 'sdm', ['dosen', 'tendik']],
            'kampusb' => ['Aset Kampus B belum produktif', 'Gedung & lahan Jakabaring belum dimanfaatkan untuk pendapatan non-UKT.', 'proses', 4, 4, 'bisnis', ['pemda', 'dewas']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => 'LKjIP 2025, Renstra 2020–2024 & analisis manajemen (data demo)', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'PTKIN terbesar di Sumatera Selatan', '9 fakultas & Pascasarjana; 972 pegawai (LKjIP 2025).', 0.12, 4],
            ['S', 'Kampus B Jakabaring', 'Lahan ±35 ha & gedung baru dari pembiayaan IsDB (Renstra).', 0.12, 4],
            ['S', 'Akuntabilitas kinerja membaik', 'SPIP 4,59 dan NKA 89,33 pada 2025 (2024: 37,50).', 0.10, 4],
            ['W', 'PNBP bergantung UKT', 'Unit bisnis belum optimal (LKjIP 2025).', 0.12, 1],
            ['W', 'Target kinerja terlalu rendah', 'Capaian melampaui 120% di banyak indikator (LKjIP 2025).', 0.08, 2],
            ['W', 'Akreditasi Unggul masih sedikit', '10,64% prodi A pada 2019 (Renstra).', 0.08, 2],
            ['O', 'Pendaftar jauh di atas daya tampung', '57.397 pendaftar, 4.575 diterima pada 2019 (Renstra).', 0.12, 4],
            ['O', 'Dukungan pemerintah daerah', 'Hibah lahan Kampus B dari Pemprov Sumsel.', 0.08, 3],
            ['T', 'Persaingan PTN di Palembang', 'Pesaing utama dengan ±74 ribu pendaftar (Renstra).', 0.10, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => 'LKjIP 2025 & Renstra 2020–2024']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Kampus B sebagai sumber PNBP non-UKT', 'Sewa gedung, aula, pelatihan, dan kerja sama pemanfaatan lahan dengan pemda.', 5, ['kampusb', 'pnbp']],
            ['SO', 'Promosi PMB berbasis prodi baru di Kampus B', 'Manfaatkan pendaftar yang jauh di atas daya tampung untuk menumbuhkan mahasiswa.', 5, ['mahasiswa']],
            ['WO', 'Target menantang & Definisi Operasional yang diuji', 'Target ditetapkan dari tren realisasi, bukan dari target tahun lalu.', 5, ['target']],
            ['WT', 'Percepatan belanja modal', 'Lelang dini & rencana penarikan dana per triwulan.', 4, ['modal']],
            ['ST', 'Internasionalisasi melalui bahasa asing', 'Program bahasa asing dosen & kerja sama luar negeri.', 4, ['sdm']],
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
            'T1' => ['Mutu Akademik & Daya Saing Lulusan', 'Akses yang tumbuh, akreditasi Unggul, lulusan bersertifikat dan berkarakter.', '#0F6B5C'],
            'T2' => ['Riset, Kemitraan & Internasionalisasi', 'Publikasi disitasi, kontribusi SDGs, kerja sama luar negeri, bahasa asing.', '#2A6F9E'],
            'T3' => ['Kemandirian Keuangan BLU', 'PNBP non-UKT tumbuh, aset Kampus B produktif, anggaran terserap tepat waktu.', '#C88A12'],
            'T4' => ['Tata Kelola, SDM & Kampus Digital', 'Akuntabilitas kinerja, SDM tersertifikasi, layanan digital.', '#6B4FA0'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Pertumbuhan mahasiswa melalui prodi di Kampus B', 'pasar', 'Peningkatan mahasiswa 2025 0%.'],
            ['T1', 'Percepatan akreditasi Unggul prodi prioritas', 'produk', 'Target Renstra ≥50% prodi Unggul.'],
            ['T2', 'Internasionalisasi berbasis bahasa asing', 'operasi', 'Masalah utama LKjIP 2025.'],
            ['T3', 'Pemanfaatan aset Kampus B', 'operasi', 'PNBP non-UKT kecil sejak 2019.'],
            ['T4', 'Target berbasis tren & Definisi Operasional teruji', 'operasi', 'Capaian PK 2025 jauh di atas 120%.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T3', 'Kemandirian pendapatan non-UKT', 'PNBP tumbuh dengan porsi aset & kerja sama yang meningkat.', 'cfo', 3],
            'F2' => ['keuangan', 'T3', 'Anggaran terserap efektif & akuntabel', 'Penyerapan tepat waktu, belanja modal sesuai rencana, surplus EBITDA, opini WTP.', 'cfo', 3],
            'C1' => ['pelanggan', 'T1', 'Akses pendidikan tinggi yang tumbuh & inklusif', 'Jumlah mahasiswa bertambah, termasuk mahasiswa berkebutuhan khusus.', 'kemahasiswaan', 2],
            'C2' => ['pelanggan', 'T1', 'Lulusan berdaya saing & berkarakter', 'Lulusan cepat bekerja, bersertifikat, dan berkarakter Islami.', 'kemahasiswaan', 2],
            'C3' => ['pelanggan', 'T2', 'Kemitraan yang ditindaklanjuti', 'Kerja sama dalam & luar negeri menghasilkan kegiatan tri dharma.', 'kemahasiswaan', 1.5],
            'P1' => ['proses', 'T1', 'Mutu akademik & kelembagaan terjamin', 'Akreditasi Unggul, SPMI, dan standar manajemen kelembagaan.', 'lpm', 2],
            'P2' => ['proses', 'T2', 'Riset & publikasi berdampak', 'Artikel disitasi dan kontribusi prodi pada SDGs.', 'lppm', 2],
            'P3' => ['proses', 'T4', 'Akuntabilitas kinerja & anggaran', 'Nilai SAKIP, SPIP, dan Nilai Kinerja Anggaran.', 'strategi', 1.5],
            'P4' => ['proses', 'T3', 'Aset Kampus B produktif', 'Gedung & lahan Jakabaring terpakai untuk layanan dan pendapatan.', 'bisnis', 1.5],
            'L1' => ['sdm', 'T4', 'SDM kompeten & tersertifikasi', 'Dosen dan tendik bersertifikat kompetensi.', 'sdm', 1.5],
            'L2' => ['sdm', 'T2', 'Kapasitas internasionalisasi', 'Dosen berkemampuan bahasa asing bersertifikat.', 'internasional', 1],
            'L3' => ['sdm', 'T4', 'Layanan digital & kepuasan sivitas', 'Layanan terintegrasi dan kepuasan mahasiswa tinggi.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P3', 2], ['L2', 'C3', 3], ['L2', 'P2', 2], ['L3', 'C1', 2], ['L3', 'P3', 2],
            ['P1', 'C1', 3], ['P1', 'C2', 3], ['P2', 'C3', 2], ['P3', 'F2', 3], ['P4', 'F1', 3], ['P4', 'C1', 2],
            ['F1', 'C1', 3], ['C2', 'C1', 2], ['F1', 'C3', 2], ['F1', 'F2', 2],
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

        // kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, PJ, rumus, sumber, 2024, target 2025, realisasi 2025, target 2026, faktor 2026, dari LKjIP?, trajektori
        return [
            ['F1.1', 'F1', 'Realisasi Penerimaan Negara Bukan Pajak (PNBP) BLU', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Realisasi pendapatan BLU (LRA) kumulatif', 'LRA BLU / SAKTI', 104800, 115000, 111850, 120000, .97, $il, $q],
            ['F1.2', 'F1', 'Pendapatan jasa layanan pendidikan (UKT & biaya pendidikan lain)', 'Rp juta', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan jasa layanan pendidikan dari masyarakat', 'Laporan Operasional BLU', 99600, 108000, 107250, 112000, .99, $il, $q],
            ['F1.3', 'F1', 'Pendapatan pemanfaatan aset & kerja sama', 'Rp juta', 'higher', 'sum', 0, true, false, 'bisnis', 'Pendapatan sewa, pemanfaatan aset, dan kerja sama BLU', 'LRA BLU', 2400, 4000, 2300, 4500, .62, $il, $q],
            ['F1.4', 'F1', 'Porsi PNBP non-UKT terhadap total PNBP', '%', 'higher', 'last', 1, true, false, 'cfo', '(PNBP − pendapatan UKT & biaya pendidikan) ÷ PNBP × 100', 'LRA BLU', 5.0, 6.1, 4.1, 6.7, .85, $il, $q],
            ['F2.1', 'F2', 'Penyerapan anggaran belanja (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja ÷ pagu belanja × 100', 'LRA / OM-SPAN', null, 95.0, 91.72, 95.0, .975, $lt, $q],
            ['F2.2', 'F2', 'Penyerapan belanja modal (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja modal ÷ pagu belanja modal × 100', 'LRA / OM-SPAN', null, 90.0, 72.55, 90.0, .80, $lt, $q],
            ['F2.3', 'F2', 'EBITDA (surplus operasional + penyusutan & amortisasi)', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Surplus/defisit operasional + beban penyusutan & amortisasi', 'Laporan Operasional BLU', 14200, 20000, 21800, 24000, .96, $il, $q],
            ['F2.4', 'F2', 'Opini atas Laporan Keuangan BLU (4 = WTP)', 'skor', 'higher', 'last', 0, true, true, 'cfo', 'WTP = 4, WDP = 3, TW = 2, TMP = 1', 'Laporan auditor independen', 4, 4, 4, 4, 1.0, $il, $y],
            ['C1.1', 'C1', 'Persentase peningkatan jumlah mahasiswa', '%', 'higher', 'last', 2, false, true, 'kemahasiswaan', '(Mahasiswa aktif tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'PDDikti / SIA', null, 2.5, 0, 2.5, .70, $lt, $q],
            ['C1.2', 'C1', 'Persentase peningkatan mahasiswa berkebutuhan khusus', '%', 'higher', 'average', 2, false, false, 'kemahasiswaan', '(Mahasiswa berkebutuhan khusus tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'SIA & unit layanan disabilitas', null, 10.0, 100.0, 20.0, 1.05, $lt, $q],
            ['C2.1', 'C2', 'Persentase lulusan bekerja/berwirausaha', '%', 'higher', 'last', 2, false, true, 'kemahasiswaan', 'Lulusan bekerja/berwirausaha ÷ responden tracer × 100', 'Tracer study', null, 18.0, 32.69, 35.0, .97, $lt, $q],
            ['C2.2', 'C2', 'Persentase lulusan bersertifikat kompetensi', '%', 'higher', 'last', 2, false, false, 'akademik', 'Lulusan bersertifikat kompetensi ÷ total lulusan × 100', 'SIA & LSP', null, 1.25, 1.5, 3.0, .85, $lt, $q],
            ['C2.3', 'C2', 'Indeks keberagamaan mahasiswa', 'skor', 'higher', 'last', 2, false, false, 'akademik', 'Skor survei keberagamaan mahasiswa (skala 100)', "Survei Ma'had & LPM", null, 75.0, 82.25, 83.0, 1.0, $lt, 'semesteran'],
            ['C3.1', 'C3', 'Persentase kerja sama yang ditindaklanjuti', '%', 'higher', 'last', 2, false, false, 'kemahasiswaan', 'Naskah kerja sama dengan kegiatan nyata ÷ naskah aktif × 100', 'Bagian Kerja Sama', null, 15.0, 83.0, 85.0, 1.0, $lt, $q],
            ['P1.1', 'P1', 'Persentase prodi terakreditasi Unggul', '%', 'higher', 'last', 1, false, true, 'lpm', 'Prodi terakreditasi Unggul ÷ total prodi × 100', 'SK BAN-PT/LAM', 30.0, 40.0, 36.5, 45.0, .95, $il, $q],
            ['P1.2', 'P1', 'Persentase prodi yang melaksanakan SPMI', '%', 'higher', 'last', 2, false, false, 'lpm', 'Prodi dengan siklus PPEPP lengkap ÷ total prodi × 100', 'LPM (audit mutu internal)', 94.0, 85.0, 100.0, 100.0, 1.0, $lt, $q],
            ['P1.3', 'P1', 'Persentase prodi yang melaksanakan standar manajemen kelembagaan', '%', 'higher', 'last', 2, false, false, 'lpm', 'Prodi memenuhi standar manajemen kelembagaan ÷ total prodi × 100', 'LPM', 94.0, 80.0, 100.0, 100.0, 1.0, $lt, $q],
            ['P2.1', 'P2', 'Persentase artikel jurnal nasional yang disitasi', '%', 'higher', 'last', 2, false, false, 'lppm', 'Artikel disitasi ÷ artikel terbit di jurnal nasional × 100', 'SINTA / Google Scholar', null, 38.0, 47.93, 50.0, .93, $lt, $q],
            ['P2.2', 'P2', 'Persentase prodi yang berkontribusi pada SDGs', '%', 'higher', 'average', 2, false, false, 'lppm', 'Prodi dengan minimal satu karya terpetakan SDGs ÷ total prodi × 100 (maks. 100)', 'LPPM / repositori', null, 25.0, 116.69, 60.0, .96, $lt, $q],
            ['P3.1', 'P3', 'Nilai Kinerja Anggaran (NKA)', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Nilai aplikasi SMART DJA', 'SMART DJA', 37.50, 91.00, 89.33, 92.00, 1.0, $lt, $y],
            ['P3.2', 'P3', 'Capaian nilai SAKIP sesuai Perjanjian Kinerja', '%', 'higher', 'last', 2, false, false, 'strategi', 'Nilai SAKIP sebagaimana dilaporkan pada LKjIP', 'LHE SAKIP / LKjIP', 95.0, 85.0, 100.0, 100.0, 1.0, $lt, $y],
            ['P3.3', 'P3', 'Tingkat maturitas SPIP', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Skor penilaian maturitas SPIP (skala 5)', 'Hasil penilaian maturitas SPIP', 4.20, 4.00, 4.59, 4.60, 1.0, $lt, $y],
            ['P4.1', 'P4', 'Utilisasi gedung & fasilitas Kampus B', '%', 'higher', 'average', 1, true, false, 'bisnis', 'Ruang/fasilitas terpakai ÷ tersedia × 100', 'Pusat Pengembangan Bisnis & Bagian Umum', 35.0, 50.0, 38.0, 50.0, .90, $il, $q],
            ['L1.1', 'L1', 'Persentase dosen bersertifikat kompetensi', '%', 'higher', 'last', 2, false, false, 'sdm', 'Dosen bersertifikat kompetensi ÷ total dosen × 100', 'SIMPEG', null, 26.0, 63.81, 66.0, 1.0, $lt, $q],
            ['L1.2', 'L1', 'Persentase tendik bersertifikat kompetensi', '%', 'higher', 'last', 2, false, false, 'sdm', 'Tendik bersertifikat kompetensi ÷ total tendik × 100', 'SIMPEG', null, 17.0, 63.81, 65.0, .92, $lt, $q],
            ['L2.1', 'L2', 'Persentase dosen bersertifikat bahasa asing', '%', 'higher', 'last', 1, false, false, 'internasional', 'Dosen dengan sertifikat TOEFL/IELTS/TOAFL sesuai standar ÷ total dosen × 100', 'Pusat Pengembangan Bahasa', 18.0, 25.0, 21.5, 30.0, .93, $il, $q],
            ['L3.1', 'L3', 'Indeks kepuasan mahasiswa atas layanan', 'skor', 'higher', 'last', 2, false, false, 'ti', 'Survei kepuasan layanan (skala 4)', 'Survei LPM', 3.20, 3.30, 3.28, 3.40, .99, $il, 'semesteran'],
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
            'F1.1' => ['keuangan', true, 110, 'pnbp', 'Seluruh pendapatan BLU yang dicatat sebagai PNBP pada LRA dibandingkan target PNBP dalam RBA/DIPA.', $tw, 'Rekonsiliasi LRA BLU dengan SAKTI & rekening koran; uji petik bukti setor oleh SPI.', 'LRA BLU, rekap SAKTI, rekening koran BLU, hasil verifikasi SPI'],
            'F1.2' => ['keuangan', false, 110, null, 'Pendapatan jasa layanan pendidikan dari masyarakat (UKT dan biaya pendidikan lain) basis akrual.', $tw, 'Rekonsiliasi data pembayaran UKT dengan SIA dan Laporan Operasional.', 'Laporan Operasional, rekap pembayaran UKT per fakultas'],
            'F1.3' => ['keuangan', false, 120, null, 'Pendapatan sewa gedung/lahan, pemanfaatan aset Kampus B, dan kerja sama berbayar yang telah diterima di rekening BLU.', $tw, 'Pencocokan kontrak sewa/kerja sama, persetujuan pemanfaatan BMN, invoice, dan bukti setor.', 'Kontrak sewa & kerja sama, persetujuan BMN, bukti setor, LRA'],
            'F1.4' => ['keuangan', false, 120, null, 'Porsi PNBP di luar UKT dan biaya pendidikan terhadap total PNBP BLU.', $tw, 'Rekalkulasi dari LRA per akun pendapatan.', 'LRA per akun, kertas kerja perhitungan'],
            'F2.1' => ['keuangan', false, 100, null, 'Realisasi belanja kumulatif terhadap pagu belanja DIPA (target PK 2025: 95%).', $tw, 'Pencocokan LRA dengan OM-SPAN.', 'LRA, OM-SPAN, revisi DIPA'],
            'F2.2' => ['keuangan', false, 100, null, 'Realisasi belanja modal kumulatif terhadap pagu belanja modal (LKjIP 2025: 72,55%).', $tw, 'Pencocokan kontrak pengadaan, BAST, dan SP2D.', 'Kontrak, BAST, SP2D, rencana penarikan dana'],
            'F2.3' => ['keuangan', true, 120, 'ebitda', 'Surplus/defisit operasional ditambah beban penyusutan dan amortisasi (Pasal 14 ayat 4 huruf e).', $tw, 'Rekalkulasi dari Laporan Operasional; review SPI.', 'Laporan Operasional, rincian beban penyusutan'],
            'F2.4' => ['keuangan', true, 100, 'opini', 'Opini auditor independen atas Laporan Keuangan BLU tahun berjalan.', $th, 'Pemeriksaan Laporan Hasil Audit.', 'Laporan auditor independen atas LK BLU'],
            'C1.1' => ['layanan', true, 120, null, 'Kenaikan jumlah mahasiswa aktif dibandingkan tahun sebelumnya (target PK 2025: 2,5%; realisasi 0%).', $tw, 'Pencocokan SIA dengan PDDikti.', 'Rekap PDDikti, laporan SIA'],
            'C1.2' => ['layanan', false, 120, null, 'Kenaikan jumlah mahasiswa berkebutuhan khusus dibandingkan tahun sebelumnya (target PK 2025: 10%; realisasi 100% sehingga capaian dibatasi 120%).', $tw, 'Verifikasi data unit layanan disabilitas & asesmen kebutuhan.', 'Data unit layanan disabilitas, rekap SIA'],
            'C2.1' => ['layanan', true, 120, null, 'Lulusan yang bekerja atau berwirausaha menurut tracer study (target PK 2025: 18%).', $tw, 'Uji petik responden tracer study.', 'Laporan tracer study, daftar responden'],
            'C2.2' => ['layanan', false, 120, null, 'Lulusan yang memperoleh sertifikat kompetensi dari LSP/lembaga berwenang (target PK 2025: 1,25%).', $tw, 'Pencocokan sertifikat dengan daftar lulusan.', 'Salinan sertifikat, daftar lulusan'],
            'C2.3' => ['layanan', false, 110, null, "Indeks keberagamaan mahasiswa hasil survei Ma'had dan LPM (target PK 2025: 75).", 'Semesteran; survei pada akhir semester.', 'Uji validitas instrumen & sampel survei.', 'Laporan survei, instrumen'],
            'C3.1' => ['layanan', false, 120, null, 'Naskah kerja sama aktif yang ditindaklanjuti dengan kegiatan (target PK 2025: 15%; realisasi 83% atau 553%).', $tw, 'Uji petik laporan kegiatan kerja sama.', 'Naskah kerja sama, laporan kegiatan'],
            'P1.1' => ['layanan', true, 120, null, 'Program studi terakreditasi Unggul terhadap total program studi (arah Renstra: ≥50%).', $tw, 'Pencocokan SK akreditasi.', 'SK BAN-PT/LAM'],
            'P1.2' => ['layanan', false, 120, null, 'Program studi yang menjalankan siklus SPMI (PPEPP) lengkap (target PK 2025: 85%).', $tw, 'Audit mutu internal oleh LPM.', 'Laporan AMI, dokumen PPEPP'],
            'P1.3' => ['layanan', false, 120, null, 'Program studi yang memenuhi standar manajemen kelembagaan (target PK 2025: 80%).', $tw, 'Telaah dokumen tata kelola prodi oleh LPM.', 'Dokumen tata kelola, laporan LPM'],
            'P2.1' => ['layanan', false, 120, null, 'Artikel di jurnal nasional yang telah disitasi (target PK 2025: 38%).', $tw, 'Penelusuran SINTA/Google Scholar.', 'Rekap SINTA, tangkapan layar sitasi'],
            'P2.2' => ['layanan', false, 120, null, 'Mulai 2026: prodi yang memiliki minimal satu karya terpetakan SDGs dibagi total prodi, paling tinggi 100%. Pada 2025 dilaporkan 116,69% karena pembilang menghitung karya, bukan prodi.', $tw, 'Uji petik pemetaan SDGs per prodi.', 'Rekap LPPM per prodi, repositori'],
            'P3.1' => ['penugasan', false, 110, null, 'Nilai Kinerja Anggaran dari aplikasi SMART DJA (target PK 2025: 91).', $th, 'Pencocokan dengan aplikasi SMART DJA.', 'Cetakan SMART DJA'],
            'P3.2' => ['penugasan', false, 110, null, 'Nilai SAKIP sebagaimana target Perjanjian Kinerja (target PK 2025: 85).', $th, 'Pencocokan Laporan Hasil Evaluasi SAKIP.', 'LHE SAKIP'],
            'P3.3' => ['penugasan', false, 110, null, 'Tingkat maturitas Sistem Pengendalian Intern Pemerintah (target PK 2025: 4).', $th, 'Pencocokan hasil penilaian maturitas.', 'Laporan penilaian maturitas SPIP'],
            'P4.1' => ['keuangan', false, 120, null, 'Tingkat pemakaian gedung & fasilitas Kampus B dibandingkan kapasitas tersedia.', $tw, 'Uji petik log pemakaian ruang & kontrak sewa.', 'Rekap pemakaian ruang, kontrak sewa'],
            'L1.1' => ['layanan', false, 120, null, 'Dosen yang memiliki sertifikat kompetensi (target PK 2025: 26%).', $tw, 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'],
            'L1.2' => ['layanan', false, 120, null, 'Tenaga kependidikan yang memiliki sertifikat kompetensi (target PK 2025: 17%). Realisasi 2025 sama dengan dosen (63,81%) dan perlu diuji.', $tw, 'Pencocokan SIMPEG khusus tendik dengan sertifikat.', 'Rekap SIMPEG tendik, salinan sertifikat'],
            'L2.1' => ['layanan', false, 120, null, 'Dosen yang memiliki sertifikat kemampuan bahasa asing sesuai standar universitas.', $tw, 'Pencocokan sertifikat dengan data Pusat Pengembangan Bahasa.', 'Salinan sertifikat TOEFL/IELTS/TOAFL'],
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
            $v0 = collect($svc->snapshot())->map(fn ($i) => array_merge($i, ['collection' => null, 'test' => null, 'documents' => null]))->all();
            PerformanceContract::create($common + [
                'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'KK-01/UIN.RF/2025 (contoh)', 'indicators' => $v0,
                'proposed_at' => '2024-12-27', 'discussed_at' => '2025-01-22', 'ba_number' => 'BA-03/PB.2/2025 (contoh)', 'leader_signed_at' => '2025-01-27', 'dg_signed_at' => '2025-01-31',
                'legal_case_free' => true, 'other_requirements_met' => true, 'notes' => 'DATA DEMO. Kontrak Kinerja awal 2025 (simulasi); target indikator layanan mengikuti Perjanjian Kinerja 2025.',
            ]);
            $previous = PerformanceContract::where('version', 0)->first();
            PerformanceContract::create($common + [
                'version' => 1, 'kind' => 'adendum', 'status' => 'ditandatangani', 'number' => 'KK-01.A/UIN.RF/2025 (contoh)', 'basis' => 'peralihan',
                'reason' => 'Penyesuaian Kontrak Kinerja 2025 dengan PER-6/PB/2025: melengkapi aspek indikator, trajektori triwulanan, Definisi Operasional, dan indikator wajib.',
                'indicators' => $svc->snapshot($previous), 'proposed_at' => '2025-03-27', 'discussed_at' => '2025-03-28', 'leader_signed_at' => '2025-03-28', 'dg_signed_at' => '2025-04-01',
                'legal_case_free' => true, 'other_requirements_met' => true, 'incentive_notes' => 'Tidak ada persyaratan tambahan dari Pembina Teknis (contoh).',
                'notes' => 'DATA DEMO. Adendum peralihan Pasal 21 PER-6/PB/2025.',
            ]);

            return;
        }

        $signed = PerformanceContract::create($common + [
            'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'KK-01/UIN.RF/2026 (contoh)', 'indicators' => $svc->snapshot(),
            'proposed_at' => '2025-12-30', 'discussed_at' => '2026-01-26', 'ba_number' => 'BA-11/PB.2/2026 (contoh)', 'leader_signed_at' => '2026-01-29', 'dg_signed_at' => '2026-02-02',
            'legal_case_free' => true, 'notes' => 'DATA DEMO. Proyeksi Kontrak Kinerja 2026: target indikator layanan ditetapkan ulang dari tren realisasi 2025.',
        ]);

        // usulan perubahan (Pasal 19 ayat 1 huruf c): target akreditasi Unggul turun karena jadwal visitasi LAM ditunda
        $changed = collect($svc->snapshot($signed))->map(function ($i) {
            if ($i['code'] !== 'P1.1') {
                return $i;
            }
            $i['targets'] = [3 => 37.4, 6 => 38.3, 9 => 39.1, 12 => 40.0];
            $i['annual'] = 40.0;
            $i['change'] = 'target_turun';
            $i['previous_annual'] = 45.0;

            return $i;
        })->all();
        PerformanceContract::create($common + [
            'version' => 1, 'kind' => 'perubahan', 'status' => 'diusulkan', 'number' => null, 'basis' => 'perubahan_kebijakan', 'indicators' => $changed,
            'reason' => 'Simulasi: lembaga akreditasi menunda jadwal visitasi empat prodi ke 2027 sehingga target P1.1 diusulkan turun dari 45% menjadi 40%. Bila disetujui, capaian P1.1 paling tinggi 100% (Pasal 19 ayat 8).',
            'proposed_at' => '2026-09-30', 'technical_approval' => 'Surat persetujuan Dirjen Pendidikan Islam atas perubahan target (contoh)',
            'notes' => 'DATA DEMO. Menunggu pembahasan dengan DJPb; tenggat penandatanganan 1 Desember.',
        ]);
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Gedung Rektorat, Kampus A UIN Raden Fatah';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja & LKjIP 2025', 'review_type' => 'tahunan', 'review_date' => '2026-01-20', 'period_label' => 'Tahun 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Dekan, Kepala Biro, Ketua Lembaga, SPI',
                'summary' => "Capaian PK rata-rata 295,72%: kerja sama 553%, SDGs 467%, mahasiswa berkebutuhan khusus 1000% — target terlalu rendah.\nPeningkatan mahasiswa 0% dari target 2,5%.\nPenyerapan anggaran 91,72%; belanja modal 72,55%.\nPNBP (simulasi) 97% target; pendapatan aset & kerja sama ±58% estimasi.",
                'decisions' => "1. Target 2026 ditetapkan dari tren realisasi; capaian di atas 120% tidak lagi dipakai sebagai prestasi.\n2. Definisi Operasional SDGs & sertifikasi tendik diperbaiki.\n3. Lelang belanja modal dimulai Triwulan I.\n4. Pusat Pengembangan Bisnis menyusun rencana pemanfaatan aset Kampus B.",
                'follow_up' => 'Bagian Perencanaan menyiapkan usulan Kontrak Kinerja 2026 dengan target baru.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Pengawas meminta target 2026 diuji SPI terhadap realisasi tiga tahun terakhir sebelum Kontrak Kinerja diusulkan.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Pimpinan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-13', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
                'summary' => match ($m) {
                    2 => 'Pembayaran UKT semester genap selesai; dokumen lelang belanja modal disiapkan.',
                    3 => 'Triwulan I ditutup; belanja modal baru 9% pagu.',
                    6 => 'Triwulan II: pendapatan aset & kerja sama ±60% trajektori; utilisasi Kampus B 44%.',
                    8 => 'PMB 2026: pendaftar naik, tetapi daya tampung prodi baru Kampus B belum ditetapkan.',
                    default => 'Tinjauan indikator merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Laporan capaian TW I dikirim paling lambat 21 April 2026.',
                    6 => 'Percepat persetujuan pemanfaatan BMN Kampus B & paket sewa semester II.',
                    8 => 'Siapkan kajian usulan perubahan target akreditasi (Pasal 19) sebelum 30 September.',
                    default => 'Lanjutkan rencana aksi; penanggung jawab IK merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-16', 'Triwulan I 2026'], [2, '2026-07-16', 'Triwulan II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Pimpinan, SPI, Dewan Pengawas (pengamat)',
                'summary' => $q === 1 ? 'PNBP TW I 97% trajektori; belanja modal 80% trajektori; pendapatan aset 58%.' : 'Pendapatan aset & kerja sama di bawah 65% (peringatan insentif); belanja modal tertinggal; indikator layanan sesuai trajektori.',
                'decisions' => $q === 1 ? 'SPI menguji Definisi Operasional baru P2.2 dan L1.2 sebelum laporan dikirim.' : 'Satgas pemanfaatan aset Kampus B; jadwal ulang paket pengadaan belanja modal.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['dewas'] = PerformanceReview::create([
            'title' => 'Rapat Dewan Pengawas — Semester I 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-27', 'period_label' => 'Semester I 2026',
            'chair' => 'Ketua Dewan Pengawas', 'venue' => $venue, 'participants' => 'Dewan Pengawas, Rektor, Wakil Rektor II, SPI',
            'summary' => 'Dewas menyoroti pendapatan aset & kerja sama di bawah 65% (syarat insentif huruf c) dan risiko PNBP tidak tercapai (huruf d).',
            'decisions' => 'Rektor melaporkan kemajuan pemanfaatan aset Kampus B setiap bulan.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['dewas']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon pastikan target 2026 tidak lagi dicapai ratusan persen; capaian wajar lebih bermanfaat untuk pengambilan keputusan.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Pimpinan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-13', 'period_label' => 'September 2026',
            'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
            'summary' => 'Realisasi Triwulan III sebagian masih draf; data tendik bersertifikat dikembalikan dan data bahasa asing belum diinput. Laporan TW III berisiko melewati tenggat 21 Oktober.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi TW III disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo; realisasi belanja 2025 mengacu LKjIP Rp258,9 M.';

            return [
                'defs' => [
                    'adendum' => ['URF-25-01', 'Adendum Kontrak Kinerja sesuai PER-6/PB/2025', 'Menyusun aspek indikator, trajektori, dan Definisi Operasional bersama Pembina Teknis & DJPb.', 'T4', 'strategi', '2025-03-18', '2025-04-01', 'selesai', 5, 5, 2, 1, ['P3', 'F2'], 'Adendum ditandatangani.'],
                    'akreditasi' => ['URF-25-02', 'Pendampingan akreditasi prodi', 'Pendampingan borang & visitasi prodi prioritas.', 'T1', 'lpm', '2025-01-15', '2025-12-15', 'selesai', 5, 4, 3, 3, ['P1'], '40% prodi Unggul.'],
                    'kampusb' => ['URF-25-03', 'Penataan aset Kampus B', 'Inventarisasi gedung & lahan Jakabaring untuk pemanfaatan.', 'T3', 'bisnis', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 3, 3, ['P4', 'F1'], 'Daftar aset siap sewa.'],
                    'sertifikasi' => ['URF-25-04', 'Sertifikasi kompetensi dosen & tendik', 'Kerja sama LSP dan pelatihan.', 'T4', 'sdm', '2025-03-01', '2025-11-30', 'selesai', 4, 3, 2, 2, ['L1'], '26% dosen & 17% tendik bersertifikat.'],
                ],
                'activities' => [
                    ['adendum', 'Penyusunan Definisi Operasional', 'strategi', '2025-03-18', '2025-03-26', 100], ['adendum', 'Penandatanganan adendum', 'dirut', '2025-03-27', '2025-04-01', 100],
                    ['akreditasi', 'Pendampingan borang & visitasi', 'lpm', '2025-01-15', '2025-12-15', 100],
                    ['kampusb', 'Inventarisasi gedung & lahan', 'bisnis', '2025-02-01', '2025-12-31', 100],
                    ['sertifikasi', 'Uji kompetensi dosen & tendik', 'sdm', '2025-03-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['adendum', 'Adendum diterima DJPb', '2025-04-01', true], ['akreditasi', 'SK akreditasi gelombang 2025 terbit', '2025-12-15', true],
                ],
                'budgets' => [
                    ['akreditasi', 'Pendampingan & visitasi akreditasi', 'opex', 2_400_000_000, 2_150_000_000, $note, 'PNBP BLU'],
                    ['kampusb', 'Penilaian & penataan aset Kampus B', 'capex', 3_500_000_000, 2_540_000_000, $note, 'PNBP BLU'],
                    ['sertifikasi', 'Uji kompetensi & pelatihan', 'opex', 1_600_000_000, 1_380_000_000, $note, 'PNBP BLU'],
                ],
            ];
        }

        return [
            'defs' => [
                'pmb' => ['URF-26-01', 'Promosi PMB & prodi baru Kampus B', 'Promosi ke sekolah/madrasah Sumsel, jalur kerja sama pemda, dan beasiswa daerah.', 'T1', 'kemahasiswaan', '2026-01-15', '2026-09-30', 'berjalan', 5, 5, 3, 2, ['C1', 'F1'], 'Mahasiswa bertambah 2,5%.'],
                'kampusb' => ['URF-26-02', 'Bisnis aset Kampus B Jakabaring', 'Persetujuan pemanfaatan BMN, paket sewa gedung & aula, pusat pelatihan.', 'T3', 'bisnis', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 3, ['P4', 'F1'], 'Pendapatan aset & kerja sama Rp4,5 M; utilisasi 50%.'],
                'modal' => ['URF-26-03', 'Percepatan belanja modal', 'Lelang dini, rencana penarikan dana per triwulan, pemantauan kontrak.', 'T3', 'cfo', '2026-01-05', '2026-12-15', 'berjalan', 5, 5, 2, 1, ['F2', 'P3'], 'Penyerapan belanja modal 90%.'],
                'internasional' => ['URF-26-04', 'Internasionalisasi & bahasa asing dosen', 'Kelas bahasa, tes TOEFL/IELTS/TOAFL, kerja sama luar negeri.', 'T2', 'internasional', '2026-02-01', '2026-12-15', 'berjalan', 4, 4, 2, 2, ['L2', 'C3'], '30% dosen bersertifikat bahasa asing.'],
                'kontrak' => ['URF-26-05', 'Siklus Kontrak Kinerja & Definisi Operasional teruji di Kinera', 'Target berbasis tren, uji SPI, input realisasi, persetujuan Rektor, cetak lampiran PER-6.', 'T4', 'strategi', '2026-01-05', '2026-12-31', 'berjalan', 5, 5, 2, 1, ['P3', 'P2'], 'Laporan triwulanan tepat waktu, tidak ada capaian di atas batas.'],
                'akreditasi' => ['URF-26-06', 'Percepatan akreditasi Unggul', 'Pendampingan prodi prioritas & persiapan APT Unggul.', 'T1', 'lpm', '2026-01-15', '2027-06-30', 'berjalan', 4, 4, 3, 3, ['P1'], '45% prodi Unggul.'],
            ],
            'activities' => [
                ['pmb', 'Roadshow sekolah & madrasah', 'kemahasiswaan', '2026-01-15', '2026-05-31', 100], ['pmb', 'Kerja sama beasiswa pemda', 'kemahasiswaan', '2026-02-01', '2026-06-30', 100],
                ['pmb', 'Penetapan daya tampung prodi baru', 'akademik', '2026-04-01', '2026-09-30', 70],
                ['kampusb', 'Usulan persetujuan pemanfaatan BMN', 'bisnis', '2026-01-01', '2026-04-30', 100], ['kampusb', 'Paket sewa gedung & aula', 'bisnis', '2026-05-01', '2026-12-31', 55],
                ['kampusb', 'Pusat pelatihan Kampus B', 'bisnis', '2026-06-01', '2026-12-31', 35],
                ['modal', 'Lelang dini paket belanja modal', 'cfo', '2026-01-05', '2026-03-31', 100], ['modal', 'Pemantauan kontrak & BAST', 'cfo', '2026-04-01', '2026-12-15', 60],
                ['internasional', 'Kelas bahasa asing dosen', 'internasional', '2026-02-01', '2026-11-30', 65], ['internasional', 'Tes sertifikasi bahasa', 'internasional', '2026-06-01', '2026-12-15', 40],
                ['kontrak', 'Kontrak Kinerja 2026 ditandatangani', 'strategi', '2026-01-05', '2026-02-02', 100], ['kontrak', 'Laporan capaian TW I & II', 'strategi', '2026-04-01', '2026-07-21', 100],
                ['kontrak', 'Laporan capaian TW III', 'strategi', '2026-10-01', '2026-10-21', 55], ['kontrak', 'Usulan perubahan Kontrak Kinerja', 'strategi', '2026-08-15', '2026-09-30', 100],
                ['akreditasi', 'Pendampingan borang prodi prioritas', 'lpm', '2026-01-15', '2026-12-15', 60], ['akreditasi', 'Persiapan APT Unggul', 'lpm', '2026-07-01', '2027-06-30', 20],
            ],
            'milestones' => [
                ['kontrak', 'Kontrak Kinerja 2026 ditandatangani', '2026-02-02', true], ['kontrak', 'Laporan TW II terkirim', '2026-07-21', true], ['kontrak', 'Laporan TW III terkirim', '2026-10-21', false],
                ['kampusb', 'Persetujuan pemanfaatan BMN terbit', '2026-04-30', true], ['modal', 'Seluruh kontrak belanja modal ditandatangani', '2026-06-30', true], ['kampusb', 'Pendapatan aset Rp3 M (TW III)', '2026-09-30', false],
            ],
            'budgets' => [
                ['pmb', 'Promosi & roadshow PMB', 'opex', 1_200_000_000, 1_050_000_000, null, 'PNBP BLU'],
                ['kampusb', 'Penyiapan gedung & aula sewa', 'capex', 4_000_000_000, 2_300_000_000, null, 'PNBP BLU'],
                ['modal', 'Pendampingan pengadaan & pemantauan kontrak', 'opex', 300_000_000, 210_000_000, null, 'PNBP BLU'],
                ['internasional', 'Kelas & tes bahasa asing', 'opex', 900_000_000, 470_000_000, null, 'PNBP BLU'],
                ['kontrak', 'Implementasi Kinera & pelatihan', 'opex', 450_000_000, 360_000_000, null, 'PNBP BLU'],
                ['akreditasi', 'Pendampingan akreditasi', 'opex', 2_600_000_000, 1_500_000_000, null, 'PNBP BLU'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Target Perjanjian Kinerja terlalu rendah', 'Capaian kerja sama 553%, SDGs 467%, mahasiswa berkebutuhan khusus 1000%; KMA 94/2021 membatasi capaian 120%.', 'tinjauan', 'tahunan', 'proses', 'tinggi', null, 'C3.1', 'adendum', 'komisaris', '2026-01-20',
                [['Target 2026 disusun dari tren realisasi & diuji SPI', 'strategi', '2026-01-26', 'selesai', 100]], ['2026-02-02', 'Target 2026 ditetapkan ulang dalam Kontrak Kinerja 2026.']],
            ['Penyerapan belanja modal 72,55%', 'Sisa belanja modal ±Rp3,99 M; penyerapan total 91,72% dari pagu Rp282,29 M (LKjIP 2025).', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 3990000000, 'F2.2', null, 'cfo', '2026-01-20',
                [['Lelang dini & rencana penarikan dana per triwulan', 'cfo', '2026-03-31', 'selesai', 100]], null],
            ['Peningkatan jumlah mahasiswa 0%', 'Realisasi 0% dari target 2,5% (LKjIP 2025).', 'tinjauan', 'tahunan', 'pelanggan', 'tinggi', null, 'C1.1', null, 'dirut', '2026-01-20',
                [['Rencana promosi PMB 2026', 'kemahasiswaan', '2026-02-28', 'selesai', 100]], null],
        ] : [
            ['Pendapatan aset & kerja sama di bawah ambang 65%', 'Capaian s.d. TW II ±60% trajektori; menggugurkan syarat insentif huruf c bila berlanjut.', 'timwas', 'dewas', 'keuangan', 'kritis', null, 'F1.3', 'kampusb', 'komisaris', '2026-07-27',
                [['Paket sewa gedung & aula Kampus B', 'bisnis', '2026-12-31', 'berjalan', 55], ['Penagihan terpusat kerja sama', 'cfo', '2026-10-31', 'berjalan', 60]], null],
            ['Belanja modal tertinggal dari trajektori', 'Realisasi TW II 80% trajektori meski lelang dimulai lebih awal.', 'tinjauan', 'q2', 'keuangan', 'sedang', null, 'F2.2', 'modal', 'dirut', '2026-07-16',
                [['Pemantauan mingguan kontrak & BAST', 'cfo', '2026-12-15', 'berjalan', 60]], null],
            ['Definisi Operasional SDGs menghasilkan capaian di atas 100%', 'Pada 2025 pembilang menghitung karya, bukan prodi (116,69%).', 'audit', null, 'proses', 'sedang', null, 'P2.2', 'kontrak', 'strategi', '2026-04-10',
                [['Perbaiki Definisi Operasional P2.2', 'lppm', '2026-04-15', 'selesai', 100]], ['2026-04-16', 'Definisi Operasional baru dipakai sejak laporan TW I.']],
            ['Laporan capaian TW III berisiko terlambat', 'Realisasi TW III sebagian masih draf/dikembalikan; tenggat 21 Oktober 2026 (Pasal 10).', 'internal', 'rm9', 'proses', 'tinggi', null, 'L1.2', 'kontrak', 'strategi', '2026-10-13',
                [['Hitung ulang data tendik bersertifikat dari SIMPEG', 'sdm', '2026-10-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan perubahan Kontrak Kinerja oleh DJPb', 'eksternal', 'kontrak', 'P3', 'Direktorat Jenderal Perbendaharaan', 'Perubahan target P1.1 berlaku setelah ditandatangani (paling lambat 1 Desember).', 'strategi', 'tinggi', 'Kajian lengkap & persetujuan Pembina Teknis dilampirkan.'],
                ['Persetujuan pemanfaatan BMN Kampus B', 'eksternal', 'kampusb', 'P4', 'Kementerian Keuangan (pengelola barang)', 'Sewa gedung & lahan bergantung pada persetujuan pemanfaatan BMN.', 'bisnis', 'tinggi', 'Usulan diajukan sejak Januari; paket sewa disiapkan paralel.'],
                ['Jadwal visitasi lembaga akreditasi', 'eksternal', 'akreditasi', 'P1', 'BAN-PT & LAM', 'Target prodi Unggul bergantung pada jadwal visitasi.', 'lpm', 'sedang', 'Koordinasi jadwal sejak awal tahun.'],
            ],
            'institutionalizations' => [
                ['Siklus laporan capaian Kontrak Kinerja di Kinera', 'sistem', 'Input realisasi tanggal 5 → verifikasi SPI/Keuangan tanggal 7 → persetujuan Rektor tanggal 8 → kirim ke DJPb & Pendis ≤ 15 hari kerja.', 'Triwulanan', 'strategi', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Uji target & Definisi Operasional oleh SPI', 'sop', 'Target baru dibandingkan realisasi tiga tahun terakhir sebelum Kontrak Kinerja diusulkan.', 'Tahunan', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Rapat Dewan Pengawas', 'rapat', 'Pengawasan capaian kinerja dan syarat insentif.', 'Triwulanan', 'dirut', 'melembaga'],
                ['Audit Mutu Internal (SPMI)', 'sop', 'Siklus PPEPP di seluruh prodi.', 'Tahunan', 'lpm', 'melembaga'],
                ['Rapat pemantauan belanja modal', 'rapat', 'Pemantauan kontrak, BAST, dan rencana penarikan dana.', 'Bulanan', 'cfo', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
