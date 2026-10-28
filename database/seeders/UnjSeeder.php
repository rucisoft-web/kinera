<?php

namespace Database\Seeders;

use App\Models\AtmanProfile;
use App\Models\Capability;
use App\Models\Competitor;
use App\Models\CoreValue;
use App\Models\EnvironmentScan;
use App\Models\IndustryForce;
use App\Models\MarketAnalysis;
use App\Models\ObjectiveRelation;
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

/**
 * Demo instansi pemerintah yang BUKAN BLU: Universitas Negeri Jakarta (kode UNJ), PTN Badan Hukum sejak 14 Agustus 2024
 * (PP No. 31 Tahun 2024). Modul Kontrak Kinerja BLU tidak dipakai; pengukuran kinerja memakai IKU PTN
 * (Kepmendikbudristek No. 210/M/2023), Perjanjian Kinerja, dan Program Kerja tahunan.
 *
 * DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak:
 * - Visi, misi, tujuan, kerangka pengembangan, dan sejarah kelembagaan dari Renstra UNJ 2025–2029.
 * - Nama, penanggung jawab, jadwal, dan pagu 13 program pada Program Kerja UNJ Tahun 2026 (total Rp51,58 M, sumber dana selain APBN)
 *   menjadi inisiatif strategis 2026; realisasi anggaran dan progresnya simulasi.
 * - Seluruh angka indikator 2025 dan 2026 adalah ilustrasi, bukan capaian resmi UNJ.
 * - Tidak memakai nama pejabat sebenarnya; semua akun bertanda "(contoh)".
 *
 * Pelajaran utama: prodi berstandar internasional (IKU 8) dan pendapatan kerja sama tertinggal, sementara publikasi Q1
 * melampaui target; Program Kerja yang beranggaran menjadi inisiatif yang terukur terhadap sasaran Renstra.
 */
class UnjSeeder extends ShowcaseSeeder
{
    public const CODE = 'UNJ';

    public const LT = 'Renstra UNJ 2025–2029 dan Program Kerja UNJ Tahun 2026';

    public const RENSTRA = 'Rencana Strategis Universitas Negeri Jakarta 2025–2029';

    public const PROKER = 'Program Kerja Universitas Negeri Jakarta Tahun 2026';

    /** Bobot musiman: UKT semester genap (Feb) & ganjil (Agu); kegiatan Program Kerja menumpuk Jan–Agu. */
    public const SEASON = [
        2025 => [1 => .078, .092, .074, .074, .076, .078, .086, .098, .082, .084, .084, .094],
        2026 => [1 => .080, .092, .076, .076, .078, .080, .086, .097, .080, .082, .082, .091],
    ];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'Universitas Negeri Jakarta', 'legal_name' => 'Universitas Negeri Jakarta (Perguruan Tinggi Negeri Badan Hukum)',
            'industry' => 'Pendidikan tinggi negeri (PTN Badan Hukum, bukan BLU)', 'blu_field' => null, 'blu_since' => null,
            'ministry' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi', 'technical_supervisor' => 'Direktorat Jenderal Pendidikan Tinggi',
            'leader_title' => 'Rektor', 'leader_name' => 'Rektor UNJ (contoh)', 'leader_nip' => null,
            'address' => 'Jl. Rawamangun Muka Raya, Rawamangun', 'city' => 'Jakarta Timur',
            'phone' => '+62 21 4893982', 'website' => 'https://www.unj.ac.id',
            'description' => 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Visi, misi, tujuan, dan kerangka pengembangan mengacu pada '.self::RENSTRA.'; nama, penanggung jawab, jadwal, dan pagu program mengacu pada '.self::PROKER.'. Seluruh angka indikator, realisasi anggaran, dan data 2026 (proyeksi) adalah simulasi. UNJ berstatus PTN Badan Hukum, sehingga modul Kontrak Kinerja BLU tidak dipakai. Bukan data resmi Universitas Negeri Jakarta maupun kementerian.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Tahun pertama penuh sebagai PTN Badan Hukum; sasaran dan IKU mengikuti Renstra 2025–2029, angka realisasi ilustratif.'
            : 'DATA DEMO — PROYEKSI 2026: 13 program dan pagunya mengikuti Program Kerja UNJ 2026; Triwulan I–II disetujui, Triwulan III dalam proses verifikasi.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['P2.2', 'C2.2', 'C1.2', 'L3.1', 'L2.1', 'P1.2', 'L1.1'];
    }

    protected function missingSeptember(): array
    {
        return ['C3.2'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P4.1', 'Persentase sampah terkelola Triwulan III hanya menghitung sampah organik dari kantin. Mohon hitung dari total timbulan sampah seluruh kampus (A, B, D, E) dan lampirkan log penimbangan.'];
    }

    protected function criticalIn2026(): array
    {
        return ['F1.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 2, 3, 3, 2];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Kinera UNJ (contoh)', 'unj.admin', 'Admin Sistem', 'UPT Teknologi Informasi dan Komunikasi', ['company-admin']],
            'dirut' => ['Rektor (contoh)', 'unj.rektor', 'Rektor', 'Pimpinan Universitas', ['pimpinan']],
            'cfo' => ['Wakil Rektor Keuangan & Sumber Daya (contoh)', 'unj.wrkeu', 'Wakil Rektor Bidang Keuangan dan Sumber Daya', 'Pimpinan Universitas', ['keuangan']],
            'akademik' => ['Wakil Rektor Akademik (contoh)', 'unj.wrakademik', 'Wakil Rektor Bidang Akademik, Kemahasiswaan dan Alumni', 'Pimpinan Universitas', ['kepala-unit']],
            'riset' => ['Wakil Rektor Riset & Sistem Informasi (contoh)', 'unj.wrriset', 'Wakil Rektor Bidang Riset, Inovasi dan Sistem Informasi', 'Pimpinan Universitas', ['kepala-unit']],
            'kerjasama' => ['Wakil Rektor Kerja Sama & Bisnis (contoh)', 'unj.wrkerjasama', 'Wakil Rektor Bidang Kerja Sama dan Bisnis', 'Pimpinan Universitas', ['kepala-unit']],
            'lppm' => ['Ketua LPPM (contoh)', 'unj.lppm', 'Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat', 'LPPM', ['kepala-unit']],
            'lpm' => ['Kepala Badan Penjaminan Mutu (contoh)', 'unj.mutu', 'Kepala Badan Penjaminan Mutu', 'Badan Penjaminan Mutu', ['kepala-unit', 'editor-jatidiri']],
            'sdm' => ['Kepala Bagian SDM (contoh)', 'unj.sdm', 'Kepala Bagian Sumber Daya Manusia', 'Biro Umum dan Keuangan', ['kepala-unit']],
            'ti' => ['Kepala UPT TIK (contoh)', 'unj.tik', 'Kepala UPT Teknologi Informasi dan Komunikasi', 'UPT TIK', ['kepala-unit']],
            'strategi' => ['Sekretaris Universitas (contoh)', 'unj.sekretaris', 'Sekretaris Universitas', 'Sekretariat Universitas', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Ketua Komite Audit MWA (contoh)', 'unj.mwa', 'Ketua Komite Audit Majelis Wali Amanat', 'Majelis Wali Amanat', ['pengawas']],
            'analis' => ['Analis Data Kinerja (contoh)', 'unj.analis', 'Analis Perencanaan & Data Kinerja', 'Sekretariat Universitas', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi universitas berkelas dunia yang unggul dalam bidang kependidikan, sains, teknologi, dan humaniora.',
            'mission' => "1. Menyelenggarakan pendidikan tinggi yang unggul dan berdaya saing internasional dalam memajukan perkembangan intelektual dan kesejahteraan masyarakat.\n2. Melaksanakan penelitian yang inovatif, bereputasi, dan berguna bagi kemaslahatan manusia.\n3. Melaksanakan pengabdian kepada masyarakat yang berdampak kepada masyarakat lokal, nasional, regional, dan global.\n4. Mengembangkan inovasi dan keunggulan dalam bidang kependidikan yang bereputasi internasional.\n5. Mengembangkan dan menerapkan tata kelola yang baik dan mampu beradaptasi dan bersinergi dengan lingkungan secara berkelanjutan.\n6. Melaksanakan kerja sama nasional dan internasional yang bermutu, berkelanjutan, dan berdampak terhadap pengembangan kualitas pendidikan dan sumber daya manusia.",
            'purpose' => "1. Pendidikan dan pembelajaran yang unggul dan bereputasi untuk menghasilkan lulusan berdaya saing.\n2. SDM dan budaya akademik yang mendukung pengembangan ilmu pengetahuan, seni, dan teknologi.\n3. Penelitian dan inovasi yang berkontribusi pada penyelesaian masalah masyarakat.\n4. Pengabdian yang memberdayakan masyarakat secara inovatif dan berkelanjutan.\n5. Jejaring masyarakat, akademik, industri, dan media di tingkat nasional maupun internasional.\n6. Tata kelola universitas yang transparan dan akuntabel dalam pelaksanaan otonomi perguruan tinggi.",
            'tagline' => 'Mencerdaskan dan Memartabatkan Bangsa',
            'brand_promise' => 'LPTK terkemuka di ibu kota yang mencetak pendidik dan ilmuwan unggul, kini dengan otonomi PTN Badan Hukum.',
            'value_proposition' => 'Sejarah panjang sebagai pencetak tenaga pendidik sejak IKIP Jakarta, lokasi di pusat Jakarta, serta tiga fokus pengembangan Renstra 2025–2029: keunggulan tata kelola, keunggulan akademik dan pengabdian, dan rekognisi internasional (DATA DEMO).',
            'culture_statement' => 'Adaptif, unggul, profesional, kreatif, inovatif, dan kolaboratif (Kata Pengantar Renstra 2025–2029).',
            'history' => "1964 (16 Mei): IKIP Jakarta, integrasi FKIP Universitas Indonesia dan Institut Pendidikan Guru.\n1999: IKIP Jakarta berubah menjadi Universitas Negeri Jakarta dengan perluasan mandat.\n2009: menerapkan pola pengelolaan keuangan BLU.\n2021: mulai menyiapkan transformasi menjadi PTN Badan Hukum.\n2024 (14 Agustus): ditetapkan sebagai PTN Badan Hukum melalui PP No. 31 Tahun 2024.\n2025–2029: Renstra menuju universitas berkelas dunia.\n(Ringkasan dari Renstra UNJ 2025–2029 untuk keperluan demo.)",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-10 10:00:00',
        ]);
        foreach ([
            ['Unggul', 'Keunggulan', 'Menetapkan target yang menantang dan mengukurnya dengan jujur.', "Target IKU disusun dari tren realisasi\nMenindaklanjuti hasil audit mutu internal"],
            ['Profesional', 'Profesionalisme', 'Bekerja sesuai standar dan tanggung jawab jabatan dalam otonomi PTN Badan Hukum.', "Laporan kinerja tepat waktu\nKeputusan berbasis data"],
            ['Kolaboratif', 'Kolaborasi', 'Kerja sama lintas unit dan dengan mitra sekolah, industri, serta luar negeri.', "Kerja sama yang ditindaklanjuti dengan kegiatan\nBerbagi data antarunit"],
            ['Inovatif', 'Inovasi', 'Pembelajaran, riset, dan layanan yang terus diperbarui.', "Pembelajaran digital berbasis OBE\nHasil riset yang dipakai masyarakat"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Pendidikan guru & kependidikan (LPTK)', 'Kompetensi', 'Reputasi panjang sebagai pencetak tenaga pendidik; PPG dan prodi kependidikan menjadi keunggulan pembeda (Renstra).', 4, 5, true, 'Prodi kependidikan sebagai rintisan akreditasi internasional.'],
            ['Akreditasi & standar internasional prodi', 'Kompetensi', 'Sebagian besar prodi terakreditasi, tetapi prodi berakreditasi internasional masih sedikit (ilustrasi).', 2 + $lvl, 4, true, 'Program WCU: akreditasi internasional & kelas internasional.'],
            ['Riset & publikasi bereputasi', 'Kompetensi', 'Publikasi Scopus dibandingkan LPTK lain menjadi tolok ukur Renstra; jurnal UNJ terindeks SINTA dan DOAJ.', 3, 4, true, 'Program Publikasi Q1 dan penelitian didanai.'],
            ['SDM dosen', 'Sumber daya manusia', 'Kualifikasi doktor dan jabatan guru besar terus meningkat (ilustrasi).', 3, 4, true, 'Studi lanjut S3 & percepatan guru besar.'],
            ['Otonomi PTN Badan Hukum', 'Sistem', 'Keleluasaan pengelolaan akademik, keuangan, dan SDM sejak 2024; organ MWA, Senat Akademik, dan Rektor.', 2 + $lvl, 4, false, 'Peraturan turunan statuta, RKAT, dan OTK yang efektif.'],
            ['Pembelajaran digital', 'Sistem', 'LMS sudah dipakai, tetapi belum seluruhnya berbasis OBE.', 2 + $lvl, 4, false, 'Penguatan LMS OBE di seluruh prodi.'],
            ['Kampus berkelanjutan', 'Sumber daya fisik', 'Kampus padat di tengah kota; pengelolaan sampah belum tertata.', 1 + $lvl, 3, false, 'Waste management & implementasi SDGs.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    /** @return array<string,StrategicIssue> */
    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Penetapan UNJ sebagai PTN Badan Hukum', '2024-08-20', 'eksternal', 'PP No. 31 Tahun 2024',
                'UNJ ditetapkan menjadi PTN Badan Hukum pada 14 Agustus 2024 setelah berstatus BLU sejak 2009. Status ini memberi keleluasaan pengelolaan akademik, keuangan, dan SDM.',
                'Kontrak Kinerja BLU tidak berlaku lagi; akuntabilitas melalui Perjanjian Kinerja, IKU, RKAT, dan laporan ke Majelis Wali Amanat.'],
            ['Renstra UNJ 2025–2029', '2025-01-10', 'internal', self::RENSTRA,
                'Visi universitas berkelas dunia; enam tujuan; tiga fokus kerangka pengembangan: keunggulan tata kelola, keunggulan akademik dan pengabdian, serta rekognisi internasional. Target IKU dan IKK dijabarkan per tahun.',
                'Sasaran dan IKU di Kinera diturunkan dari Renstra; target tahunan diuji terhadap tren realisasi.'],
            ['Program Kerja UNJ 2026', '2026-01-15', 'internal', self::PROKER,
                '13 program beranggaran total Rp51,58 M dari sumber dana selain APBN, antara lain penelitian didanai Rp46,06 M, Publikasi Q1 Rp909 jt, WCU akreditasi internasional Rp903,6 jt, LMS OBE Rp828,2 jt, dan waste management Rp823,7 jt.',
                'Setiap program dicatat sebagai inisiatif dengan penanggung jawab, jadwal, dan anggaran, lalu dikaitkan ke sasaran Renstra.'],
            ['IKU Perguruan Tinggi', '2025-01-15', 'eksternal', 'Kepmendikbudristek No. 210/M/2023',
                'Delapan IKU: lulusan bekerja layak, mahasiswa di luar kampus, dosen di luar kampus, praktisi mengajar, hasil kerja dosen dipakai/rekognisi, prodi bermitra, kelas kolaboratif, dan prodi berstandar internasional.',
                'IKU menjadi indikator sasaran pelanggan, proses, dan pembelajaran di Peta Strategi.'],
            ['Posisi publikasi dibanding LPTK lain', '2025-02-10', 'eksternal', self::RENSTRA.' (Bab II)',
                'Renstra membandingkan publikasi Scopus, sitasi per dosen, dan jurnal terindeks SINTA/DOAJ UNJ dengan universitas LPTK lain periode 2020–2024.',
                'Publikasi Q1 menjadi program unggulan 2026.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-01-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Otonomi PTN Badan Hukum', 'Kewenangan lebih luas disertai tuntutan akuntabilitas kepada MWA dan kementerian.', 'peluang', 5, 5, 'naik', 'Perkuat RKAT, Perjanjian Kinerja, dan laporan kinerja berbasis data.'],
            ['politik', 'Efisiensi anggaran pemerintah', 'Bantuan pendanaan PTN Badan Hukum dari APBN berpotensi ditekan.', 'ancaman', 4, 4, 'naik', 'Tumbuhkan pendapatan kerja sama dan usaha.'],
            ['ekonomi', 'Daya beli keluarga mahasiswa', 'Sensitivitas UKT di wilayah Jabodetabek.', 'ancaman', 3, 4, 'stabil', 'UKT berjenjang dan beasiswa mitra.'],
            ['ekonomi', 'Kebutuhan pelatihan guru dan ASN', 'Pemda dan sekolah membutuhkan pelatihan dan sertifikasi pendidik.', 'peluang', 4, 4, 'naik', 'Paket kerja sama pelatihan berbayar.'],
            ['sosial', 'Kebutuhan guru bermutu', 'Kekurangan guru bermutu di banyak daerah.', 'peluang', 4, 4, 'naik', 'Perkuat PPG dan prodi kependidikan.'],
            ['teknologi', 'Pembelajaran digital & AI', 'Mahasiswa menuntut pembelajaran fleksibel berbasis teknologi.', 'peluang', 4, 5, 'naik', 'LMS berbasis OBE di seluruh prodi.'],
            ['lingkungan', 'Kampus berkelanjutan', 'Pemeringkatan dan mitra menilai kinerja SDGs dan pengelolaan sampah.', 'peluang', 3, 4, 'naik', 'Program SDGs dan waste management.'],
            ['hukum', 'Statuta & peraturan turunan PTN Badan Hukum', 'Peraturan MWA, Senat, dan Rektor harus lengkap dan selaras.', 'ancaman', 4, 4, 'stabil', 'Percepat regulasi tata kelola akademik dan keuangan.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => 'Renstra 2025–2029 & analisis manajemen (ilustrasi demo)']);
        }

        foreach ([
            ['persaingan', 'Persaingan PTN di Jabodetabek', 'PTN besar dan LPTK lain bersaing merebut calon mahasiswa unggul.', 4, 'Diferensiasi kependidikan dan rekognisi internasional.'],
            ['kekuatan_pembeli', 'Calon mahasiswa & orang tua', 'Memilih berdasarkan akreditasi, reputasi, dan prospek kerja.', 3, 'Akreditasi internasional dan layanan karier.'],
            ['kekuatan_pemasok', 'Dosen bereputasi & guru besar', 'Persaingan merekrut doktor bereputasi.', 3, 'Insentif riset dan percepatan jabatan.'],
            ['pendatang_baru', 'Kampus asing & program daring', 'Kampus asing dan kelas daring masuk ke Jakarta.', 2, 'Kelas internasional dan kerja sama gelar ganda.'],
            ['produk_pengganti', 'Sertifikasi & pelatihan singkat', 'Kursus singkat menggantikan sebagian kebutuhan gelar.', 3, 'Microcredential dan pelatihan berbayar.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Mahasiswa sarjana & sarjana terapan', 'Pendidikan', 'Masuk melalui SNBP, SNBT, dan jalur mandiri.', 78, 'stabil', true, 'Akreditasi, prospek kerja, UKT terjangkau.', 'Promosi prodi unggulan & beasiswa.'],
            ['Mahasiswa pascasarjana & PPG', 'Pendidikan', 'Guru, dosen, dan ASN yang melanjutkan studi.', 14, 'naik', true, 'Kelas fleksibel, pembimbing bereputasi.', 'Kelas akhir pekan & kerja sama pemda.'],
            ['Mitra kerja sama & pengguna jasa', 'Kerja sama', 'Pemda, sekolah, industri, dan mitra luar negeri.', 6, 'naik', true, 'Solusi tepat guna & pelaporan rapi.', 'Katalog layanan kerja sama berbayar.'],
            ['Pengguna aset & unit usaha', 'Usaha', 'Sewa gedung, laboratorium, dan layanan unit usaha.', 2, 'naik', true, 'Fasilitas siap pakai di pusat kota.', 'Pengelolaan aset oleh unit bisnis.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan ilustratif untuk demo']);
        }

        foreach ([
            ['Universitas Negeri Jakarta', 'PTN Badan Hukum', 1, 728.5, 5.6, 6.0, true, 'LPTK di pusat Jakarta, otonomi PTN Badan Hukum, publikasi tumbuh.', 'Prodi berstandar internasional masih sedikit, pendapatan kerja sama kecil.'],
            ['LPTK PTN Badan Hukum lain', 'PTN Badan Hukum', 2, null, null, null, false, 'Reputasi kependidikan nasional.', 'Lokasi di luar ibu kota.'],
            ['PTN besar di Jabodetabek', 'PTN', 3, null, null, null, false, 'Reputasi & peringkat internasional.', 'Seleksi ketat, biaya lebih tinggi.'],
            ['PTS besar di Jakarta', 'PTS', 4, null, null, null, false, 'Kerja sama industri, kampus kota.', 'Biaya lebih tinggi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'TA 2025 (simulasi)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Pendapatan selain APBN dalam Rp miliar (angka simulasi demo).' : 'Pembanding kualitatif untuk demo.']);
        }

        foreach ([
            ['Penetapan UNJ sebagai PTN Badan Hukum', 'Pemerintah', 'PP No. 31 Tahun 2024', 'Kelembagaan', '2024-08-14', 'Status badan hukum, organ universitas, dan otonomi pengelolaan.', 'Dasar tata kelola, RKAT, dan pelaporan kepada MWA.', $year === 2025 ? 'sebagian' : 'patuh', 'strategi', $year === 2025 ? '2025-12-31' : null],
            ['Pendidikan Tinggi', 'Pemerintah', 'UU No. 12 Tahun 2012', 'Kelembagaan', null, 'Otonomi perguruan tinggi, Tri Dharma, dan penjaminan mutu.', 'Kerangka seluruh layanan akademik.', 'patuh', 'akademik', null],
            ['Indikator Kinerja Utama Perguruan Tinggi', 'Kemendikbudristek', 'Kepmendikbudristek No. 210/M/2023', 'Kinerja', '2023-07-01', 'Delapan IKU perguruan tinggi negeri.', 'Dasar indikator Perjanjian Kinerja Rektor.', 'patuh', 'strategi', null],
            ['Sistem Akuntabilitas Kinerja Instansi Pemerintah', 'Pemerintah', 'Perpres No. 29 Tahun 2014', 'Kinerja', null, 'Perencanaan, Perjanjian Kinerja, pengukuran, dan pelaporan kinerja.', 'Program Kerja 13: evaluasi implementasi SAKIP 2026.', 'sebagian', 'strategi', '2026-08-31'],
            ['Pedoman evaluasi AKIP', 'Kementerian PANRB', 'PermenPAN-RB No. 88 Tahun 2021', 'Kinerja', null, 'Evaluasi akuntabilitas kinerja instansi pemerintah.', 'Acuan penilaian SAKIP internal.', 'sebagian', 'strategi', '2026-08-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'mahasiswa' => ['Mahasiswa & orang tua', 'eksternal', 'Penerima layanan', 4, 5, 'Pembelajaran bermutu, UKT terjangkau, layanan cepat.', 'Survei kepuasan & layanan pengaduan.', 'WR Akademik'],
            'kementerian' => ['Kemdiktisaintek (Ditjen Pendidikan Tinggi)', 'eksternal', 'Pembina', 5, 5, 'Capaian IKU dan Perjanjian Kinerja.', 'Perjanjian Kinerja Rektor & laporan kinerja tahunan.', 'Sekretaris Universitas'],
            'mwa' => ['Majelis Wali Amanat', 'internal', 'Pengawas', 5, 5, 'Pengesahan RKAT, pengawasan kinerja & keuangan.', 'Rapat pleno & Komite Audit.', 'Sekretariat MWA'],
            'senat' => ['Senat Akademik', 'internal', 'Organ', 4, 4, 'Kebijakan akademik & mutu.', 'Sidang senat.', 'Sekretaris Senat'],
            'dosen' => ['Dosen', 'internal', 'SDM', 4, 5, 'Karier, riset, studi lanjut.', 'Forum fakultas & program insentif riset.', 'Bagian SDM'],
            'tendik' => ['Tenaga kependidikan', 'internal', 'SDM', 3, 4, 'Kompetensi & kesejahteraan.', 'Pelatihan & sertifikasi.', 'Bagian SDM'],
            'mitra' => ['Mitra sekolah, pemda, industri & luar negeri', 'eksternal', 'Mitra', 4, 4, 'Kerja sama berdampak & dilaporkan.', 'Optimalisasi kerja sama (Program Kerja 01).', 'WR Kerja Sama & Bisnis'],
            'auditor' => ['Auditor (KAP, BPK, SPI)', 'eksternal', 'Regulator', 4, 3, 'Laporan keuangan & kinerja andal.', 'Tindak lanjut temuan tepat waktu.', 'SPI'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'internasional' => ['Prodi berstandar internasional masih sedikit', 'IKU 8 tertinggal dari target Renstra menuju universitas berkelas dunia (ilustrasi).', 'proses', 5, 5, 'lpm', ['kementerian', 'mwa']],
            'pendapatan' => ['Pendapatan kerja sama & usaha kecil', 'Pendapatan selain UKT belum cukup menopang otonomi PTN Badan Hukum (ilustrasi).', 'keuangan', 5, 5, 'kerjasama', ['mwa', 'mitra']],
            'tatakelola' => ['Transisi tata kelola PTN Badan Hukum', 'Peraturan turunan, OTK, dan siklus RKAT–Perjanjian Kinerja–laporan kinerja masih dibangun.', 'proses', 4, 5, 'strategi', ['mwa', 'senat', 'kementerian']],
            'publikasi' => ['Reputasi riset dibanding LPTK lain', 'Publikasi bereputasi dan sitasi per dosen menjadi tolok ukur Renstra.', 'proses', 4, 4, 'riset', ['dosen', 'kementerian']],
            'digital' => ['Pembelajaran digital belum berbasis OBE', 'LMS belum dipakai merata dan belum terhubung ke capaian pembelajaran.', 'sdm', 4, 4, 'akademik', ['mahasiswa', 'dosen']],
            'lingkungan' => ['Pengelolaan sampah kampus belum tertata', 'Program Kerja 02 (waste management) dan 10 (SDGs) menjawab isu ini.', 'proses', 3, 3, 'cfo', ['mahasiswa', 'mitra']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => 'Renstra 2025–2029, Program Kerja 2026 & analisis manajemen (data demo)', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'LPTK bereputasi pencetak pendidik', 'Sejarah panjang sejak IKIP Jakarta (Renstra).', 0.14, 4],
            ['S', 'Otonomi PTN Badan Hukum', 'Keleluasaan akademik, keuangan, dan SDM sejak 2024.', 0.12, 4],
            ['S', 'Lokasi di pusat Jakarta', 'Dekat dengan pemerintah pusat, industri, dan media.', 0.10, 4],
            ['W', 'Prodi berstandar internasional sedikit', 'IKU 8 rendah (ilustrasi).', 0.12, 1],
            ['W', 'Pendapatan non-UKT kecil', 'Kerja sama dan usaha belum optimal (ilustrasi).', 0.10, 2],
            ['W', 'Sampah kampus belum tertata', 'Program Kerja 2026: waste management.', 0.06, 2],
            ['O', 'Kebutuhan guru bermutu', 'PPG dan pelatihan pendidik.', 0.12, 4],
            ['O', 'Kemitraan internasional', 'Peluang kelas internasional dan gelar ganda.', 0.12, 3],
            ['T', 'Persaingan PTN & kampus asing di Jabodetabek', 'Calon mahasiswa unggul diperebutkan.', 0.12, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => 'Renstra 2025–2029 (Bab III) & ilustrasi demo']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Prodi kependidikan sebagai pelopor akreditasi internasional', 'Reputasi LPTK dimanfaatkan untuk kelas internasional dan akreditasi internasional.', 5, ['internasional']],
            ['SO', 'Paket kerja sama pelatihan pendidik', 'Pelatihan guru dan ASN berbayar untuk pemda dan sekolah.', 5, ['pendapatan']],
            ['WO', 'Publikasi Q1 bersama mitra luar negeri', 'Riset kolaboratif untuk menaikkan publikasi bereputasi.', 4, ['publikasi']],
            ['WT', 'Siklus kinerja PTN Badan Hukum di Kinera', 'RKAT, Perjanjian Kinerja, IKU, dan laporan kepada MWA dalam satu alur.', 5, ['tatakelola']],
            ['ST', 'LMS OBE untuk daya saing pembelajaran', 'Pembelajaran digital terukur melawan kampus daring dan asing.', 4, ['digital']],
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
            'T1' => ['Keunggulan Akademik & Pengabdian', 'Lulusan berdaya saing, pembelajaran berpengalaman nyata, riset dan pengabdian berdampak.', '#0F6B5C'],
            'T2' => ['Rekognisi Internasional', 'Prodi berstandar internasional, publikasi bereputasi, kemitraan luar negeri.', '#2A6F9E'],
            'T3' => ['Keunggulan Tata Kelola PTN Badan Hukum', 'Tata kelola transparan dan akuntabel, keuangan mandiri.', '#C88A12'],
            'T4' => ['Kampus Digital & Berkelanjutan', 'Pembelajaran digital OBE, sistem terintegrasi, dan kampus ramah lingkungan.', '#6B4FA0'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T2', 'Akreditasi internasional dimulai dari prodi kependidikan', 'produk', 'Program WCU dalam Program Kerja 2026.'],
            ['T2', 'Publikasi Q1 berbasis kolaborasi', 'operasi', 'Program Publikasi Q1 2026.'],
            ['T3', 'Pendapatan kerja sama pelatihan pendidik', 'pasar', 'Pendapatan non-UKT masih kecil.'],
            ['T3', 'Siklus RKAT–Perjanjian Kinerja–laporan dalam satu sistem', 'operasi', 'Transisi tata kelola PTN Badan Hukum.'],
            ['T4', 'LMS berbasis OBE di seluruh prodi', 'operasi', 'Program Kerja 07.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T3', 'Pendapatan selain APBN tumbuh & beragam', 'Pendapatan UKT, kerja sama, dan usaha menopang otonomi PTN Badan Hukum.', 'cfo', 3],
            'F2' => ['keuangan', 'T3', 'Anggaran RKAT efektif & akuntabel', 'Penyerapan sesuai rencana dan laporan keuangan wajar tanpa pengecualian.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Lulusan berdaya saing', 'Lulusan mendapat pekerjaan layak dan mahasiswa berprestasi.', 'akademik', 2.5],
            'C2' => ['pelanggan', 'T1', 'Pengalaman belajar yang relevan', 'Mahasiswa belajar di luar kampus dan diajar praktisi.', 'akademik', 2],
            'C3' => ['pelanggan', 'T2', 'Kemitraan nasional & internasional berdampak', 'Prodi bermitra dan kerja sama luar negeri ditindaklanjuti.', 'kerjasama', 2],
            'P1' => ['proses', 'T2', 'Program studi berstandar internasional', 'Akreditasi internasional dan Unggul.', 'lpm', 2.5],
            'P2' => ['proses', 'T1', 'Riset & publikasi bereputasi', 'Hasil kerja dosen dipakai masyarakat atau mendapat rekognisi internasional.', 'riset', 2.5],
            'P3' => ['proses', 'T3', 'Tata kelola transparan & akuntabel', 'SAKIP, Program Kerja tepat jadwal, dan tindak lanjut temuan.', 'strategi', 2],
            'P4' => ['proses', 'T4', 'Kampus berkelanjutan', 'Sampah terkelola dan kontribusi SDGs terlaporkan.', 'cfo', 1],
            'L1' => ['sdm', 'T1', 'Dosen berkualifikasi & berkegiatan di luar kampus', 'Doktor, guru besar, dan dosen berkegiatan di luar kampus.', 'sdm', 2],
            'L2' => ['sdm', 'T4', 'Pembelajaran digital berbasis OBE', 'Kelas kolaboratif dan mata kuliah di LMS berbasis OBE.', 'akademik', 1.5],
            'L3' => ['sdm', 'T4', 'Sistem informasi terintegrasi', 'Layanan daring terintegrasi dan kepuasan sivitas.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P2', 3], ['L1', 'C2', 2], ['L2', 'C1', 2], ['L2', 'P1', 2], ['L3', 'P3', 2], ['L3', 'L2', 2],
            ['P1', 'C1', 3], ['P1', 'C3', 2], ['P2', 'C3', 2], ['P2', 'F1', 2], ['P3', 'F2', 3], ['P4', 'C3', 1],
            ['F1', 'C1', 2], ['C2', 'C1', 3], ['F1', 'C3', 3], ['F1', 'F2', 2],
        ] as [$s, $t, $str]) {
            ObjectiveRelation::create(['source_objective_id' => $obj[$s]->id, 'target_objective_id' => $obj[$t]->id, 'strength' => $str]);
        }

        return [$themes, $obj];
    }

    // ------------------------------------------------------------------ Indikator Kinerja

    protected function kpiDefs(): array
    {
        $il = false;
        $q = 'kuartalan';
        $s = 'semesteran';
        $y = 'tahunan';

        // kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, PJ, rumus, sumber, 2024, target 2025, realisasi 2025, target 2026, faktor 2026, dari dokumen?, frekuensi
        return [
            ['F1.1', 'F1', 'Pendapatan selain APBN (UKT, kerja sama, usaha)', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Realisasi pendapatan selain APBN kumulatif', 'Laporan realisasi RKAT', 690000, 740000, 728500, 780000, .97, $il, $q],
            ['F1.2', 'F1', 'Pendapatan kerja sama & unit usaha', 'Rp juta', 'higher', 'sum', 0, true, false, 'kerjasama', 'Pendapatan kerja sama, pelatihan, sewa aset, dan unit usaha', 'Laporan realisasi RKAT', 48000, 55000, 51200, 62000, .76, $il, $q],
            ['F1.3', 'F1', 'Porsi pendapatan non-UKT terhadap pendapatan selain APBN', '%', 'higher', 'last', 1, true, false, 'cfo', '(Pendapatan selain APBN − UKT) ÷ pendapatan selain APBN × 100', 'Laporan realisasi RKAT', 9.5, 11.0, 10.2, 12.5, .90, $il, $q],
            ['F2.1', 'F2', 'Penyerapan anggaran RKAT (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja ÷ pagu RKAT × 100', 'Sistem keuangan universitas', null, 95.0, 93.4, 95.0, .975, $il, $q],
            ['F2.2', 'F2', 'Opini auditor atas laporan keuangan (4 = WTP)', 'skor', 'higher', 'last', 0, true, true, 'cfo', 'WTP = 4, WDP = 3, TW = 2, TMP = 1', 'Laporan auditor independen', 4, 4, 4, 4, 1.0, $il, $y],
            ['C1.1', 'C1', 'Lulusan mendapat pekerjaan layak (IKU 1)', '%', 'higher', 'last', 2, false, true, 'akademik', 'Lulusan bekerja, studi lanjut, atau berwirausaha dalam ≤ 6 bulan ÷ responden tracer × 100', 'Tracer study', 58.0, 60.0, 61.4, 63.0, 1.0, $il, $s],
            ['C1.2', 'C1', 'Mahasiswa berprestasi tingkat nasional & internasional', 'orang', 'higher', 'sum', 0, false, false, 'akademik', 'Jumlah mahasiswa peraih juara/penghargaan tingkat nasional dan internasional', 'SIM Kemahasiswaan', 310, 340, 356, 370, 1.04, $il, $q],
            ['C2.1', 'C2', 'Mahasiswa berkegiatan di luar kampus (IKU 2)', '%', 'higher', 'last', 2, false, false, 'akademik', 'Mahasiswa dengan ≥ 20 sks di luar kampus atau berprestasi ÷ total mahasiswa × 100', 'SIA & PDDikti', 32.0, 35.0, 36.8, 40.0, .95, $il, $q],
            ['C2.2', 'C2', 'Mata kuliah melibatkan praktisi mengajar (IKU 4)', '%', 'higher', 'last', 2, false, false, 'akademik', 'Mata kuliah dengan praktisi mengajar ÷ total mata kuliah × 100', 'SIA', 22.0, 25.0, 27.5, 30.0, 1.02, $il, $q],
            ['C3.1', 'C3', 'Program studi bekerja sama dengan mitra (IKU 6)', '%', 'higher', 'last', 2, false, false, 'kerjasama', 'Prodi dengan kerja sama aktif ÷ total prodi × 100', 'Sistem kerja sama', 70.0, 75.0, 78.1, 82.0, .97, $il, $q],
            ['C3.2', 'C3', 'Kerja sama luar negeri yang ditindaklanjuti', 'kegiatan', 'higher', 'sum', 0, false, false, 'kerjasama', 'Jumlah kegiatan nyata dari naskah kerja sama luar negeri', 'Kantor Urusan Internasional', 48, 55, 52, 60, .85, $il, $q],
            ['P1.1', 'P1', 'Program studi berstandar internasional (IKU 8)', '%', 'higher', 'last', 2, false, true, 'lpm', 'Prodi berakreditasi/bersertifikat internasional ÷ total prodi × 100', 'SK lembaga akreditasi internasional', 8.5, 11.0, 10.1, 14.0, .74, $il, $q],
            ['P1.2', 'P1', 'Program studi terakreditasi Unggul', '%', 'higher', 'last', 1, false, false, 'lpm', 'Prodi terakreditasi Unggul ÷ total prodi × 100', 'SK BAN-PT/LAM', 52.0, 56.0, 57.4, 60.0, 1.0, $il, $q],
            ['P2.1', 'P2', 'Hasil kerja dosen dipakai masyarakat atau berekognisi internasional (IKU 5)', 'per dosen', 'higher', 'last', 2, false, false, 'riset', 'Jumlah luaran yang dipakai/berekognisi ÷ jumlah dosen', 'SINTA, LPPM', 0.42, 0.48, 0.47, 0.55, .93, $il, $q],
            ['P2.2', 'P2', 'Publikasi internasional bereputasi Q1', 'artikel', 'higher', 'sum', 0, false, false, 'riset', 'Artikel terbit di jurnal Scopus kuartil 1', 'Scopus & SINTA', 118, 140, 151, 170, 1.03, $il, $q],
            ['P2.3', 'P2', 'Judul penelitian didanai yang selesai sesuai rencana', '%', 'higher', 'last', 2, false, false, 'lppm', 'Judul selesai sesuai jadwal ÷ judul didanai × 100', 'LPPM', 88.0, 92.0, 90.5, 95.0, .96, $il, $q],
            ['P3.1', 'P3', 'Nilai evaluasi SAKIP', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Nilai hasil evaluasi akuntabilitas kinerja', 'LHE SAKIP', 78.5, 80.0, 80.4, 82.0, 1.0, $il, $y],
            ['P3.2', 'P3', 'Program Kerja terlaksana sesuai jadwal', '%', 'higher', 'last', 2, false, false, 'strategi', 'Program dengan progres ≥ rencana ÷ jumlah program × 100', 'Laporan Program Kerja', null, 85.0, 82.0, 90.0, .95, $il, $q],
            ['P3.3', 'P3', 'Rekomendasi SPI & auditor yang tuntas ditindaklanjuti', '%', 'higher', 'last', 2, false, false, 'strategi', 'Rekomendasi tuntas ÷ total rekomendasi × 100', 'SPI', 72.0, 80.0, 76.0, 85.0, .88, $il, $q],
            ['P4.1', 'P4', 'Sampah kampus yang terkelola', '%', 'higher', 'last', 1, false, false, 'cfo', 'Timbulan sampah dipilah, diolah, atau didaur ulang ÷ total timbulan × 100', 'Unit pengelola lingkungan', 20.0, 30.0, 27.0, 45.0, .86, $il, $q],
            ['P4.2', 'P4', 'Tujuan SDGs yang dilaporkan dengan bukti', 'tujuan', 'higher', 'last', 0, false, false, 'cfo', 'Jumlah tujuan SDGs dengan kegiatan & bukti terdokumentasi', 'Tim SDGs universitas', 6, 8, 9, 11, 1.0, $il, $y],
            ['L1.1', 'L1', 'Dosen berkegiatan di luar kampus (IKU 3)', '%', 'higher', 'last', 2, false, false, 'sdm', 'Dosen berkegiatan tridharma di kampus lain/industri atau membina mahasiswa berprestasi ÷ total dosen × 100', 'SISTER & SIMPEG', 30.0, 33.0, 34.2, 38.0, 1.0, $il, $q],
            ['L1.2', 'L1', 'Dosen berpendidikan doktor', '%', 'higher', 'last', 1, false, false, 'sdm', 'Dosen bergelar S3 ÷ total dosen × 100', 'SIMPEG', 54.0, 56.0, 56.8, 59.0, .99, $il, $q],
            ['L1.3', 'L1', 'Proporsi guru besar', '%', 'higher', 'last', 1, false, false, 'sdm', 'Dosen berjabatan profesor ÷ total dosen × 100', 'SIMPEG', 9.8, 10.5, 10.6, 11.5, .97, $il, $q],
            ['L2.1', 'L2', 'Kelas kolaboratif & partisipatif (IKU 7)', '%', 'higher', 'last', 2, false, false, 'akademik', 'Mata kuliah dengan metode kasus/proyek ≥ 50% bobot nilai ÷ total mata kuliah × 100', 'SIA', 62.0, 66.0, 68.0, 72.0, 1.0, $il, $q],
            ['L2.2', 'L2', 'Mata kuliah di LMS berbasis OBE', '%', 'higher', 'last', 1, false, false, 'ti', 'Mata kuliah aktif di LMS dengan capaian pembelajaran terpetakan ÷ total mata kuliah × 100', 'LMS', 38.0, 50.0, 47.0, 75.0, .84, $il, $q],
            ['L3.1', 'L3', 'Layanan administrasi terintegrasi daring', '%', 'higher', 'last', 1, false, false, 'ti', 'Layanan daring terintegrasi ÷ total jenis layanan × 100', 'UPT TIK', 60.0, 70.0, 72.0, 80.0, 1.0, $il, $q],
            ['L3.2', 'L3', 'Indeks kepuasan layanan (skala 4)', 'skor', 'higher', 'last', 2, false, false, 'ti', 'Survei kepuasan mahasiswa, dosen, dan tendik', 'Survei Badan Penjaminan Mutu', 3.21, 3.30, 3.32, 3.40, .99, $il, $s],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Gedung Rektorat, Kampus A UNJ Rawamangun';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Rapat Kerja: Laporan Kerja 2025 dan Pemantapan Program Kerja 2026', 'review_type' => 'tahunan', 'review_date' => '2026-02-12', 'period_label' => 'Tahun 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Sekretaris Universitas, Dekan, Direktur Pascasarjana, Ketua Lembaga, Kepala Badan, SPI',
                'summary' => "Publikasi Q1 151 artikel (target 140); prodi berstandar internasional 10,1% (target 11%).\nPendapatan kerja sama & usaha Rp51,2 M dari target Rp55 M.\nPenyerapan RKAT 93,4%.\nSeluruh angka adalah ilustrasi demo.",
                'decisions' => "1. Perjanjian Kinerja 2026 antara Rektor dan seluruh pimpinan ditandatangani bulan ini (Program Kerja 05).\n2. Program WCU memprioritaskan akreditasi internasional prodi kependidikan.\n3. Setiap program pada Program Kerja 2026 dicatat sebagai inisiatif di Kinera dengan penanggung jawab dan pagu.",
                'follow_up' => 'Sekretaris Universitas menyiapkan pemantauan bulanan Program Kerja 2026.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Komite Audit MWA meminta laporan triwulanan capaian IKU dan realisasi RKAT dalam satu dokumen.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Pimpinan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Sekretaris Universitas, Ketua Lembaga',
                'summary' => match ($m) {
                    2 => 'Perjanjian Kinerja 2026 ditandatangani; RKAT 2026 mulai berjalan.',
                    3 => 'Triwulan I ditutup; pendaftaran akreditasi internasional dua prodi masih menunggu dokumen.',
                    4 => 'Rapat Pimpinan Antar Organ (6–8 April) membahas RPJP dan Renstra.',
                    6 => 'Triwulan II: pendapatan kerja sama ±76% trajektori; publikasi Q1 di atas trajektori.',
                    8 => 'Sebagian besar program terjadwal Januari–Agustus; WCU dan waste management belum selesai.',
                    default => 'Tinjauan indikator merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Badan Penjaminan Mutu menyusun jadwal ulang asesmen akreditasi internasional.',
                    6 => 'WR Kerja Sama & Bisnis menyiapkan paket pelatihan pendidik berbayar untuk pemda.',
                    8 => 'Program LMS OBE dan kerja sama diperpanjang sampai Desember; WCU dan waste management wajib menyerahkan rencana penyelesaian tertulis.',
                    default => 'Lanjutkan rencana aksi; penanggung jawab IK merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-20', 'Triwulan I 2026'], [2, '2026-07-20', 'Triwulan II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Perjanjian Kinerja & IKU — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Pimpinan universitas, Dekan, SPI',
                'summary' => $q === 1 ? 'IKU 8 tertinggal; publikasi Q1 dan praktisi mengajar di atas trajektori.' : 'Pendapatan kerja sama di bawah 80% trajektori; LMS OBE baru 84% trajektori; sampah terkelola tertinggal.',
                'decisions' => $q === 1 ? 'Tim WCU melapor setiap bulan ke Rektor.' : 'Paket kerja sama pelatihan pendidik dan percepatan migrasi mata kuliah ke LMS OBE.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['mwa'] = PerformanceReview::create([
            'title' => 'Rapat Komite Audit MWA — Semester I 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-29', 'period_label' => 'Semester I 2026',
            'chair' => 'Ketua Komite Audit MWA', 'venue' => $venue, 'participants' => 'Komite Audit MWA, Rektor, WR Keuangan & Sumber Daya, SPI',
            'summary' => 'Komite Audit menyoroti pendapatan kerja sama yang tertinggal dan tindak lanjut rekomendasi SPI yang belum tuntas.',
            'decisions' => 'Rektor melaporkan kemajuan pendapatan kerja sama dan tindak lanjut rekomendasi setiap bulan.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['mwa']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon realisasi 13 Program Kerja 2026 disajikan bersama capaian IKU agar terlihat program mana yang menggerakkan indikator.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Pimpinan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Sekretaris Universitas, Ketua Lembaga',
            'summary' => 'Realisasi Triwulan III sebagian masih draf; data sampah terkelola dikembalikan dan kerja sama luar negeri belum diinput.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi Triwulan III disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo.';

            return [
                'defs' => [
                    'transisi' => ['UNJ-25-01', 'Transisi tata kelola PTN Badan Hukum', 'Peraturan turunan statuta, OTK, pembentukan organ, dan siklus RKAT.', 'T3', 'strategi', '2025-01-06', '2025-12-31', 'selesai', 5, 5, 3, 2, ['P3', 'F2'], 'Organ dan peraturan turunan utama berlaku.'],
                    'akreditasi' => ['UNJ-25-02', 'Pendampingan akreditasi prodi', 'Pendampingan borang dan visitasi prodi prioritas.', 'T2', 'lpm', '2025-01-15', '2025-12-15', 'selesai', 5, 4, 3, 3, ['P1'], '56% prodi Unggul.'],
                    'publikasi' => ['UNJ-25-03', 'Klinik publikasi bereputasi', 'Pendampingan penulisan dan insentif publikasi Scopus.', 'T2', 'riset', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['P2'], '140 artikel Q1.'],
                    'kerjasama' => ['UNJ-25-04', 'Penataan kerja sama', 'Inventarisasi naskah kerja sama aktif dan tindak lanjutnya.', 'T3', 'kerjasama', '2025-03-01', '2025-11-30', 'selesai', 4, 3, 2, 2, ['C3', 'F1'], 'Basis data kerja sama terpusat.'],
                ],
                'activities' => [
                    ['transisi', 'Penyusunan peraturan turunan statuta', 'strategi', '2025-01-06', '2025-06-30', 100], ['transisi', 'Penyusunan RKAT 2026', 'cfo', '2025-07-01', '2025-12-31', 100],
                    ['akreditasi', 'Pendampingan borang & visitasi', 'lpm', '2025-01-15', '2025-12-15', 100],
                    ['publikasi', 'Klinik penulisan artikel', 'riset', '2025-02-01', '2025-12-31', 100],
                    ['kerjasama', 'Inventarisasi naskah kerja sama', 'kerjasama', '2025-03-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['transisi', 'RKAT 2026 disahkan MWA', '2025-12-31', true], ['akreditasi', 'SK akreditasi gelombang 2025 terbit', '2025-12-15', true],
                ],
                'budgets' => [
                    ['transisi', 'Penyusunan regulasi & RKAT', 'opex', 650_000_000, 610_000_000, $note, 'Selain APBN'],
                    ['akreditasi', 'Pendampingan & visitasi akreditasi', 'opex', 2_100_000_000, 1_940_000_000, $note, 'Selain APBN'],
                    ['publikasi', 'Insentif & klinik publikasi', 'opex', 800_000_000, 760_000_000, $note, 'Selain APBN'],
                ],
            ];
        }

        $pk = 'Pagu sesuai Program Kerja UNJ 2026; realisasi simulasi.';

        return [
            'defs' => [
                'wcu' => ['UNJ-26-01', 'World Class University: akreditasi & kelas internasional', 'Program Kerja 11. Akreditasi internasional dan pengembangan kelas internasional. Penanggung jawab: seluruh unit kerja.', 'T2', 'lpm', '2026-01-05', '2026-08-31', 'berjalan', 5, 5, 3, 2, ['P1', 'C3'], 'Tercapainya akreditasi internasional dan berkembangnya kelas internasional.'],
                'riset' => ['UNJ-26-02', 'Penelitian didanai & Publikasi Q1', 'Program Kerja 06 dan 12. Seluruh judul penelitian didanai berjalan sesuai rencana; publikasi Q1 meningkat.', 'T1', 'riset', '2026-01-05', '2026-12-31', 'berjalan', 5, 4, 3, 3, ['P2'], '170 artikel Q1 dan 95% judul selesai tepat waktu.'],
                'lms' => ['UNJ-26-03', 'Pembelajaran digital & LMS OBE', 'Program Kerja 07. Penguatan sistem pembelajaran digital dan LMS berbasis Outcome Based Education.', 'T4', 'akademik', '2026-01-05', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['L2', 'C1'], '75% mata kuliah di LMS berbasis OBE.'],
                'kerjasama' => ['UNJ-26-04', 'Optimalisasi kerja sama & reputasi media', 'Program Kerja 01 dan 03. Penjajakan/penguatan kerja sama dalam dan luar negeri; aplikasi monitoring media informasi.', 'T3', 'kerjasama', '2026-01-05', '2026-12-31', 'berjalan', 5, 4, 2, 2, ['C3', 'F1'], 'Pendapatan kerja sama Rp62 M dan 60 kegiatan kerja sama luar negeri.'],
                'hijau' => ['UNJ-26-05', 'Kampus berkelanjutan: SDGs & waste management', 'Program Kerja 02 dan 10. Pengelolaan sampah dan limbah serta implementasi SDGs pada tata kelola dan kegiatan.', 'T4', 'cfo', '2026-01-05', '2026-08-31', 'berjalan', 3, 3, 2, 2, ['P4'], '45% sampah terkelola dan 11 tujuan SDGs terlaporkan.'],
                'tatakelola' => ['UNJ-26-06', 'Siklus perencanaan & akuntabilitas kinerja', 'Program Kerja 04, 05, 08, 09, dan 13: Rapim antar organ (RPJP & Renstra), Perjanjian Kinerja 2026, RKAT 2026, Renstra 2025–2029, dan evaluasi SAKIP.', 'T3', 'strategi', '2026-01-05', '2026-08-31', 'selesai', 5, 5, 2, 1, ['P3', 'F2'], 'Dokumen perencanaan lengkap dan nilai SAKIP 82.'],
            ],
            'activities' => [
                ['wcu', 'Asesmen akreditasi internasional prodi kependidikan', 'lpm', '2026-01-05', '2026-08-31', 55], ['wcu', 'Pembukaan kelas internasional', 'akademik', '2026-02-01', '2026-08-31', 70],
                ['riset', 'Pelaksanaan penelitian didanai', 'lppm', '2026-01-05', '2026-12-31', 72], ['riset', 'Pendampingan publikasi Q1', 'riset', '2026-01-05', '2026-08-31', 100],
                ['lms', 'Migrasi mata kuliah ke LMS OBE', 'ti', '2026-01-05', '2026-12-15', 65], ['lms', 'Pelatihan dosen desain pembelajaran OBE', 'akademik', '2026-02-01', '2026-07-31', 100],
                ['kerjasama', 'Penjajakan & penguatan kerja sama', 'kerjasama', '2026-01-05', '2026-12-15', 80], ['kerjasama', 'Pengadaan aplikasi monitoring media', 'riset', '2026-07-01', '2026-07-31', 100],
                ['hijau', 'Sistem pemilahan & pengolahan sampah', 'cfo', '2026-01-05', '2026-08-31', 60], ['hijau', 'Pemetaan & pelaporan SDGs', 'cfo', '2026-01-05', '2026-08-31', 85],
                ['tatakelola', 'Raker & Perjanjian Kinerja 2026', 'strategi', '2026-02-01', '2026-02-28', 100], ['tatakelola', 'Rapim antar organ: RPJP & Renstra', 'strategi', '2026-04-06', '2026-04-08', 100],
                ['tatakelola', 'Penyusunan RKAT 2026', 'cfo', '2026-04-01', '2026-04-30', 100], ['tatakelola', 'Evaluasi implementasi SAKIP', 'strategi', '2026-07-01', '2026-08-31', 100],
            ],
            'milestones' => [
                ['tatakelola', 'Perjanjian Kinerja 2026 ditandatangani', '2026-02-27', true], ['tatakelola', 'Rumusan RPJP & Renstra antar organ', '2026-04-08', true],
                ['kerjasama', 'Aplikasi monitoring media beroperasi', '2026-07-31', true], ['wcu', 'Dua prodi lolos asesmen akreditasi internasional', '2026-08-31', false],
                ['hijau', 'Rumah pengolahan sampah beroperasi', '2026-08-31', false], ['riset', 'Laporan kemajuan penelitian semester I', '2026-07-31', true],
            ],
            'budgets' => [
                ['wcu', 'PK 11 · WCU: akreditasi internasional & kelas internasional', 'opex', 903_610_000, 512_000_000, $pk, 'Selain APBN'],
                ['riset', 'PK 06 · Penelitian didanai', 'opex', 46_061_658_813, 30_840_000_000, $pk, 'Selain APBN'],
                ['riset', 'PK 12 · Publikasi Q1 2026', 'opex', 909_000_000, 858_000_000, $pk, 'Selain APBN'],
                ['lms', 'PK 07 · Pembelajaran digital & LMS OBE', 'opex', 828_182_000, 602_500_000, $pk, 'Selain APBN'],
                ['kerjasama', 'PK 01 · Penjajakan/penguatan kerja sama', 'opex', 803_796_072, 655_300_000, $pk, 'Selain APBN'],
                ['kerjasama', 'PK 03 · Aplikasi monitoring media informasi', 'capex', 445_800_000, 445_800_000, $pk, 'Selain APBN'],
                ['hijau', 'PK 02 · Waste management', 'capex', 823_661_590, 478_000_000, $pk, 'Selain APBN'],
                ['hijau', 'PK 10 · Implementasi SDGs', 'opex', 273_004_500, 231_500_000, $pk, 'Selain APBN'],
                ['tatakelola', 'PK 04 · Rapim antar organ (RPJP & Renstra)', 'opex', 102_400_000, 99_850_000, $pk, 'Selain APBN'],
                ['tatakelola', 'PK 05 · Raker & Perjanjian Kinerja 2026', 'opex', 58_230_000, 57_100_000, $pk, 'Selain APBN'],
                ['tatakelola', 'PK 08 · RKAT 2026', 'opex', 41_800_000, 41_800_000, $pk, 'Selain APBN'],
                ['tatakelola', 'PK 09 · Penyusunan Renstra 2025–2029', 'opex', 287_319_820, 246_000_000, $pk, 'Selain APBN'],
                ['tatakelola', 'PK 13 · Evaluasi implementasi SAKIP', 'opex', 40_000_000, 32_000_000, $pk, 'Selain APBN'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Prodi berstandar internasional di bawah target', 'IKU 8 tercapai 10,1% dari target 11% (ilustrasi).', 'tinjauan', 'tahunan', 'proses', 'tinggi', null, 'P1.1', 'akreditasi', 'dirut', '2026-02-12',
                [['Masukkan akreditasi internasional ke Program Kerja 2026 (WCU)', 'lpm', '2026-02-28', 'selesai', 100]], ['2026-03-02', 'Program WCU masuk Program Kerja 2026 dengan pagu Rp903,6 jt.']],
            ['Pendapatan kerja sama di bawah target', 'Rp51,2 M dari target Rp55 M (ilustrasi).', 'tinjauan', 'tahunan', 'keuangan', 'sedang', null, 'F1.2', 'kerjasama', 'komisaris', '2026-02-12',
                [['Susun katalog layanan kerja sama berbayar', 'kerjasama', '2026-04-30', 'selesai', 100]], null],
        ] : [
            ['Pendapatan kerja sama & usaha tertinggal', 'Capaian s.d. Triwulan II ±76% trajektori; berisiko menekan pendapatan selain APBN.', 'timwas', 'mwa', 'keuangan', 'tinggi', null, 'F1.2', 'kerjasama', 'komisaris', '2026-07-29',
                [['Paket pelatihan pendidik berbayar untuk pemda', 'kerjasama', '2026-11-30', 'berjalan', 50], ['Penagihan terpusat kerja sama', 'cfo', '2026-10-31', 'berjalan', 60]], null],
            ['Akreditasi internasional melewati jadwal Program Kerja', 'Program WCU terjadwal Januari–Agustus 2026, asesmen baru 55%.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P1.1', 'wcu', 'dirut', '2026-07-20',
                [['Rencana penyelesaian asesmen dua prodi', 'lpm', '2026-12-15', 'berjalan', 40]], null],
            ['Rekomendasi SPI belum tuntas', 'Tindak lanjut rekomendasi 2025 baru ±76%.', 'audit', null, 'proses', 'sedang', null, 'P3.3', 'tatakelola', 'komisaris', '2026-05-15',
                [['Rapat bulanan tindak lanjut rekomendasi', 'strategi', '2026-12-15', 'berjalan', 65]], null],
            ['Data sampah terkelola tidak lengkap', 'Realisasi Triwulan III hanya menghitung sampah organik kantin.', 'internal', 'rm9', 'proses', 'sedang', null, 'P4.1', 'hijau', 'strategi', '2026-10-12',
                [['Hitung ulang dari log penimbangan seluruh kampus', 'cfo', '2026-10-31', 'berjalan', 30]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Jadwal asesmen lembaga akreditasi internasional', 'eksternal', 'wcu', 'P1', 'Lembaga akreditasi internasional', 'Target IKU 8 bergantung pada jadwal asesmen.', 'lpm', 'tinggi', 'Daftarkan prodi sejak awal tahun dan siapkan dokumen lebih dulu.'],
                ['Kesediaan mitra pemda untuk pelatihan berbayar', 'eksternal', 'kerjasama', 'F1', 'Pemerintah daerah & sekolah', 'Pendapatan kerja sama bergantung pada anggaran pelatihan mitra.', 'kerjasama', 'sedang', 'Tawarkan paket sejak penyusunan anggaran mitra.'],
                ['Integrasi LMS dengan sistem akademik', 'internal', 'lms', 'L2', 'UPT TIK', 'Pemetaan capaian pembelajaran membutuhkan data kurikulum dari SIA.', 'ti', 'sedang', 'Sinkronisasi kurikulum per semester.'],
            ],
            'institutionalizations' => [
                ['Pemantauan IKU & Program Kerja di Kinera', 'sistem', 'Input realisasi tanggal 5 → verifikasi tanggal 7 → persetujuan Rektor tanggal 8; dipakai untuk laporan kinerja dan MWA.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Perjanjian Kinerja Rektor dengan pimpinan', 'sop', 'Ditandatangani setiap Februari dalam Rapat Kerja tahunan.', 'Tahunan', 'strategi', 'melembaga'],
                ['Rapat Komite Audit MWA', 'rapat', 'Pengawasan capaian kinerja dan keuangan.', 'Semesteran', 'dirut', 'melembaga'],
                ['Audit Mutu Internal', 'sop', 'Siklus PPEPP di seluruh prodi.', 'Tahunan', 'lpm', 'melembaga'],
                ['Evaluasi implementasi SAKIP', 'sop', 'Evaluasi internal akuntabilitas kinerja (Program Kerja 13).', 'Tahunan', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
