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
 * Demo satker BLU: Universitas Islam Negeri Sultan Syarif Kasim Riau (kode UINSUSKA), bidang layanan pendidikan,
 * Pembina Teknis Direktorat Jenderal Pendidikan Islam Kementerian Agama.
 *
 * DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak:
 * - Perioda 2025 (ditutup): target indikator dari Kontrak Kinerja 2025 dengan DJPb dan Perjanjian Kinerja 2025 yang dimuat
 *   dalam Laporan Kinerja 2025; realisasi anggaran & sebaran akreditasi dari laporan yang sama; arah jangka panjang dari
 *   RIP 2014–2034. Realisasi indikator lain, rincian triwulanan, dan angka pendapatan adalah simulasi.
 * - Perioda 2026 (berjalan, bawaan): target sebagian dari Perjanjian Kinerja 2026; realisasi Triwulan I–III PROYEKSI/SIMULASI.
 * - Tidak memakai nama atau NIP pejabat sebenarnya; semua penandatangan bertanda "(contoh)".
 *
 * Pelajaran utama: Perjanjian Kinerja (Pendis) dan Kontrak Kinerja (DJPb) belum selaras (pertumbuhan mahasiswa 6,70% vs 1%),
 * target prodi Unggul 25% padahal posisi 42%, penyerapan dana BLU 87,76% & belanja modal 80,27%, serta perubahan indikator
 * pada PK 2026 (dosen bersertifikat pendidik turun ke 58%) yang memicu usulan perubahan Kontrak Kinerja.
 */
class UinSuskaRiauSeeder extends ShowcaseSeeder
{
    public const CODE = 'UINSUSKA';

    public const LT = 'Laporan Kinerja 2025 (Perjanjian Kinerja, Kontrak Kinerja & realisasi anggaran), Perjanjian Kinerja 2026, dan RIP 2014–2034 UIN Suska Riau';

    public const LKJ = 'Laporan Kinerja UIN Sultan Syarif Kasim Riau Tahun 2025';

    public const KK = 'Kontrak Kinerja 2025 antara Dirjen Perbendaharaan dan Rektor UIN Suska Riau (dimuat dalam Laporan Kinerja 2025)';

    /** Bobot musiman: UKT semester genap (Jan–Feb) & ganjil (Jul–Agu); belanja menumpuk di Triwulan IV. */
    public const SEASON = [
        2025 => [1 => .085, .086, .072, .070, .072, .075, .090, .095, .080, .083, .087, .105],
        2026 => [1 => .086, .086, .072, .071, .072, .075, .089, .094, .080, .084, .087, .104],
    ];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'UIN Sultan Syarif Kasim Riau', 'legal_name' => 'Universitas Islam Negeri Sultan Syarif Kasim Riau (Satker BLU)',
            'industry' => 'Pendidikan tinggi keagamaan Islam (BLU)', 'blu_field' => 'pendidikan',
            'ministry' => 'Kementerian Agama', 'technical_supervisor' => 'Direktorat Jenderal Pendidikan Islam',
            'leader_title' => 'Rektor', 'leader_name' => 'Rektor UIN Suska Riau (contoh)', 'leader_nip' => null,
            'dg_name' => 'Direktur Jenderal Perbendaharaan (contoh)', 'dg_nip' => null,
            'address' => 'Jl. H.R. Soebrantas No. 155 KM 15, Simpang Baru, Panam', 'city' => 'Pekanbaru',
            'phone' => null, 'website' => 'https://uin-suska.ac.id',
            'description' => 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada '.self::LT.'; realisasi sebagian besar indikator, angka pendapatan, rincian triwulanan, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Suska Riau, Kementerian Agama, maupun Kementerian Keuangan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Target mengacu pada Kontrak Kinerja & Perjanjian Kinerja 2025; realisasi anggaran dan akreditasi dari Laporan Kinerja 2025; realisasi indikator lain dan rincian triwulanan ilustratif.'
            : 'DATA DEMO — PROYEKSI 2026: Triwulan I–II disetujui, Triwulan III dalam proses verifikasi; usulan perubahan target dosen bersertifikat pendidik (mengikuti PK 2026) menunggu DJPb.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'C1.2', 'C2.4', 'P1.1', 'P1.3', 'P2.2', 'L2.1'];
    }

    protected function missingSeptember(): array
    {
        return ['P4.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Persentase lulusan bekerja Triwulan III dihitung dari seluruh alumni, bukan responden tracer study tahun berjalan. Mohon hitung ulang sesuai Definisi Operasional dan lampirkan rekap tracer per fakultas.'];
    }

    protected function criticalIn2026(): array
    {
        return ['F2.3'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 2, 3, 2];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Kinera UIN Suska (contoh)', 'uinsuska.admin', 'Admin Sistem', 'Pusat Teknologi Informasi dan Pangkalan Data', ['company-admin']],
            'dirut' => ['Rektor (contoh)', 'uinsuska.rektor', 'Rektor / Pemimpin BLU', 'Pimpinan Universitas', ['pimpinan']],
            'cfo' => ['Wakil Rektor II (contoh)', 'uinsuska.wr2', 'Wakil Rektor Bidang Administrasi Umum, Perencanaan & Keuangan', 'Pejabat Pengelola Keuangan BLU', ['keuangan']],
            'akademik' => ['Wakil Rektor I (contoh)', 'uinsuska.wr1', 'Wakil Rektor Bidang Akademik & Pengembangan Lembaga', 'Pimpinan Universitas', ['kepala-unit']],
            'kemahasiswaan' => ['Wakil Rektor III (contoh)', 'uinsuska.wr3', 'Wakil Rektor Bidang Kemahasiswaan, Alumni & Kerja Sama', 'Pimpinan Universitas', ['kepala-unit']],
            'lppm' => ['Ketua LPPM (contoh)', 'uinsuska.lppm', 'Ketua Lembaga Penelitian & Pengabdian kepada Masyarakat', 'LPPM', ['kepala-unit']],
            'lpm' => ['Ketua LPM (contoh)', 'uinsuska.lpm', 'Ketua Lembaga Penjaminan Mutu', 'LPM', ['kepala-unit', 'editor-jatidiri']],
            'bisnis' => ['Kepala Pusat Pengembangan Bisnis (contoh)', 'uinsuska.bisnis', 'Kepala Pusat Pengembangan Bisnis', 'Pusat Pengembangan Bisnis', ['kepala-unit']],
            'sdm' => ['Kepala Bagian Kepegawaian (contoh)', 'uinsuska.sdm', 'Kepala Bagian Organisasi, Kepegawaian & Hukum', 'Biro AUPK', ['kepala-unit']],
            'ti' => ['Kepala PTIPD (contoh)', 'uinsuska.ti', 'Kepala Pusat Teknologi Informasi & Pangkalan Data', 'PTIPD', ['kepala-unit']],
            'strategi' => ['Kepala Bagian Perencanaan (contoh)', 'uinsuska.perencanaan', 'Kepala Bagian Perencanaan & Keuangan', 'Biro AUPK', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Ketua Dewan Pengawas (contoh)', 'uinsuska.dewas', 'Ketua Dewan Pengawas BLU', 'Dewan Pengawas', ['pengawas']],
            'analis' => ['Analis Data Kinerja (contoh)', 'uinsuska.analis', 'Analis Perencanaan & Data Kinerja', 'Biro AUPK', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Terwujudnya Universitas sebagai perguruan tinggi Islam yang gemilang dan terbilang dalam mengembangkan ilmu keislaman, sains, teknologi, dan/atau seni secara terintegrasi.',
            'mission' => "1. Menyelenggarakan pendidikan dan pembelajaran berbasis integrasi ilmu, teknologi, dan/atau seni dengan Islam untuk menghasilkan sumber daya manusia yang saleh, moderat, cerdas dan berkualitas secara akademik.\n2. Menyelenggarakan penelitian dan pengkajian yang inovatif dan tepat guna dalam mengembangkan ilmu pengetahuan, teknologi dan/atau seni yang terintegrasi dengan Islam.\n3. Menyelenggarakan pengabdian berbasis integrasi ilmu pengetahuan, teknologi dan/atau seni dengan Islam yang bermanfaat bagi masyarakat dan lingkungan.\n4. Menyelenggarakan tata kelola universitas yang baik (good university governance) serta adaptif dengan sistem dan teknologi.",
            'purpose' => 'Mengintegrasikan sains dan Islam dalam Tri Dharma agar lulusan berilmu, saleh, dan moderat; menuju perguruan tinggi Islam utama berdaya saing regional.',
            'tagline' => 'Gemilang dan terbilang',
            'brand_promise' => 'Integrasi sains dan Islam di jantung Riau: pendidikan bermutu dengan 42% prodi berpredikat Unggul.',
            'value_proposition' => '8 fakultas dan Pascasarjana, 57 program studi, 23.671 mahasiswa aktif; satker BLU sejak 2009 (DATA DEMO).',
            'culture_statement' => 'The guardian of knowledge and learning center: budaya akademik dan ilmiah yang berbasis integrasi keilmuan.',
            'history' => "1970: berdiri IAIN Sulthan Syarif Qasim dengan Fakultas Tarbiyah, Syariah, dan Ushuluddin.\n2005: berubah menjadi UIN Sultan Syarif Kasim Riau (Perpres No. 2/2005).\n2009: ditetapkan sebagai satker BLU (KMK No. 77/KMK.05/2009).\nRIP 2014–2034: pemantapan identitas (2014–2018), kapasitas kelembagaan (2018–2022), penguatan menuju PT Islam unggul (2022–2026), pematangan berdaya saing regional (2026–2030), daya saing internasional (2030–2034).\n2025: 57 prodi (24 Unggul); Kontrak Kinerja dengan DJPb ditandatangani 31 Januari 2025; persiapan Fakultas Kedokteran.\n(Ringkasan dari Laporan Kinerja 2025 & RIP 2014–2034 untuk keperluan demo.)",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-10 10:00:00',
        ]);
        foreach ([
            ['Integratif', 'Integrasi sains & Islam', 'Ilmu keislaman, sains, teknologi, dan seni dikembangkan secara terpadu.', "Memasukkan kajian turats dalam mata kuliah sains\nMenginternalisasi nilai keislaman dalam pembelajaran"],
            ['Moderat', 'Wasathiyah', 'Keberagamaan yang toleran dan menghargai keberagaman budaya Melayu & Nusantara.', "Literasi keagamaan bagi seluruh mahasiswa\nDialog lintas budaya"],
            ['Unggul', 'Gemilang & terbilang', 'Mutu yang diakui melalui akreditasi Unggul dan kinerja yang terukur.', "Menetapkan target di atas posisi saat ini\nMenindaklanjuti hasil audit mutu"],
            ['Akuntabel', 'Good University Governance', 'Tata kelola BLU yang transparan, efisien, dan berbasis kinerja.', "Menyelaraskan Perjanjian Kinerja & Kontrak Kinerja\nMelaporkan capaian tepat waktu"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Program studi & akreditasi', 'Kompetensi', '57 prodi: 24 Unggul (42%), 4 A, 4 Baik Sekali, 20 B, 4 Baik, 1 terakreditasi sementara (Laporan Kinerja 2025).', 4, 5, true, 'Naikkan prodi B/Baik ke Baik Sekali/Unggul; skor rata-rata akreditasi 346 (PK 2026).'],
            ['Integrasi sains & Islam', 'Kompetensi', 'Pembeda keilmuan sejak alih status UIN 2005 (RIP 2014–2034).', 4, 5, true, 'Kurikulum & riset terintegrasi di seluruh fakultas.'],
            ['Fakultas Pertanian & Peternakan, Sains & Teknologi', 'Kompetensi', 'Prodi terapan yang relevan dengan ekonomi Riau; persiapan Fakultas Kedokteran.', 3, 4, true, 'Kerja sama industri perkebunan & kesehatan.'],
            ['SDM dosen & tendik', 'Sumber daya manusia', '1.207 pegawai; 723 dosen tetap (termasuk ±193 PPPK) dan 440 tendik; rasio dosen–mahasiswa 1:35 (Laporan Kinerja 2025).', 2 + $lvl, 4, true, 'Sertifikasi pendidik dosen PPPK & penambahan dosen prodi padat.'],
            ['Aset & unit usaha BLU', 'Sumber daya fisik', 'Pendapatan optimalisasi aset tetap & kerja sama non-tridharma ditargetkan Rp2,26 M dalam Kontrak Kinerja 2025.', 2, 4, false, 'Katalog aset komersial & unit usaha.'],
            ['Sistem informasi & data', 'Sistem', 'PTIPD; target SPBE, Satu Data Indonesia, dan statistik sektoral dalam PK 2025.', 2 + $lvl, 4, false, 'Satu data kinerja untuk PK, Kontrak Kinerja, dan LKj.'],
            ['Tata kelola BLU & penjaminan mutu', 'Sistem', 'Dewan Pengawas, SPI, LPM; modernisasi pengelolaan BLU 80% (target Kontrak Kinerja 2025).', 3, 5, false, 'Selaraskan indikator PK Pendis dan Kontrak Kinerja DJPb.'],
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
                'Kontrak 2025 yang ditandatangani 31 Januari 2025 perlu di-adendum (Pasal 21).'],
            ['Kontrak Kinerja 2025 dengan DJPb', '2025-01-31', 'internal', self::KK,
                'Margin EBITDA 14,22%; pertumbuhan jumlah mahasiswa 1%; pertumbuhan output penelitian terindeks 83,11%; PNBP operasional Rp160,61 M; pendapatan optimalisasi aset tetap & kerja sama Rp2,26 M dan aset lancar Rp1,59 M; akurasi proyeksi 3,5; modernisasi BLU 80%; prodi Unggul 25%; lulusan bekerja 80,05%.',
                'Target pertumbuhan mahasiswa 1% berbeda dengan Perjanjian Kinerja (6,70%); target prodi Unggul 25% di bawah posisi 42%.'],
            ['Realisasi anggaran 2025', '2026-01-31', 'internal', self::LKJ,
                'Pagu Rp353,45 M, realisasi Rp326,33 M (92,33%): rupiah murni 96,09%, BLU Rp140,05 M dari Rp159,59 M (87,76%). Belanja modal 80,27% (BLU 77,43%), belanja barang 90,20%, pegawai 95,20%, bansos 100%.',
                'Sisa dana BLU Rp19,54 M; pergantian pimpinan dan perubahan aturan pelaksanaan anggaran memperlambat belanja.'],
            ['Perjanjian Kinerja 2026', '2026-01-20', 'internal', 'Perjanjian Kinerja 2026 UIN Suska Riau',
                'Indikator berubah: peningkatan mahasiswa 0,5%; skor rata-rata akreditasi prodi 346; dosen bersertifikat pendidik 58%; masa tunggu lulusan 7 bulan; kerja sama menghasilkan program mutu 20%; indeks keberagamaan 90; SAKIP 90.',
                'Target dosen bersertifikat pendidik turun dari 66% (2025) ke 58% sehingga Kontrak Kinerja 2026 perlu diubah.'],
            ['RIP 2014–2034: penguatan menuju PT Islam unggul', '2025-01-15', 'internal', 'RIP UIN Suska Riau 2014–2034',
                'Periode 2022–2026 penguatan menuju perguruan tinggi Islam unggul; 2026–2030 pematangan menuju perguruan tinggi Islam utama berdaya saing regional.',
                'Kontrak Kinerja 2026 menjadi jembatan ke fase berdaya saing regional.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-01-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Kebijakan multikulturalisme & moderasi beragama', 'UIN Suska mewadahi aspirasi dan identitas keagamaan masyarakat (RIP).', 'peluang', 4, 4, 'stabil', 'Program moderasi & literasi keagamaan.'],
            ['politik', 'Efisiensi belanja negara', 'Rupiah murni dibatasi; sisa dana BLU menjadi sorotan.', 'ancaman', 4, 4, 'naik', 'Rencana penarikan dana BLU per triwulan.'],
            ['ekonomi', 'Ekonomi syariah & kewirausahaan', 'Tuntutan SDM ekonomi & keuangan syariah meningkat (RIP).', 'peluang', 4, 4, 'naik', 'Prodi & pelatihan ekonomi syariah berbayar.'],
            ['ekonomi', 'Ekonomi Riau bertumpu perkebunan & migas', 'Kebutuhan riset terapan pertanian, peternakan, dan lingkungan.', 'peluang', 3, 4, 'stabil', 'Kerja sama riset dengan industri & pemda.'],
            ['sosial', 'Daya beli keluarga mahasiswa', 'Sensitivitas UKT; penerima beasiswa ditargetkan naik 9,97%.', 'ancaman', 4, 3, 'stabil', 'Beasiswa mitra & UKT berjenjang.'],
            ['sosial', 'Relevansi lulusan dengan dunia kerja', 'Target lulusan bekerja 80,05% & masa tunggu 7 bulan.', 'ancaman', 4, 4, 'naik', 'Pusat karier & sertifikasi kompetensi.'],
            ['teknologi', 'SPBE & Satu Data Indonesia', 'Indikator tata kelola digital masuk Perjanjian Kinerja.', 'peluang', 4, 4, 'naik', 'Integrasi data akademik, keuangan, dan kinerja.'],
            ['lingkungan', 'Kampus hijau & lahan gambut', 'Risiko kabut asap musiman mengganggu kegiatan kampus.', 'ancaman', 3, 3, 'stabil', 'Rencana kesinambungan layanan & pembelajaran daring.'],
            ['hukum', 'PER-6/PB/2025 Kontrak Kinerja Pemimpin BLU', 'Trajektori, Definisi Operasional, laporan capaian, dan syarat insentif.', 'ancaman', 5, 5, 'naik', 'Selaraskan PK dan Kontrak Kinerja dalam satu sistem.'],
            ['hukum', 'Statuta UIN Suska Riau (PMA No. 16/2023)', 'Dasar visi, misi, dan tata kelola universitas.', 'peluang', 3, 3, 'stabil', 'Turunkan visi ke Kontrak Kinerja.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => 'Laporan Kinerja 2025, RIP 2014–2034 & analisis manajemen (ilustrasi demo)']);
        }

        foreach ([
            ['persaingan', 'Persaingan PTN BLU di Pekanbaru', 'Universitas Riau sebagai kompetitor utama dengan pembina keuangan yang sama (Kanwil DJPb Riau).', 4, 'Diferensiasi integrasi sains & Islam; prodi Unggul.'],
            ['kekuatan_pembeli', 'Calon mahasiswa & orang tua', 'Sensitif biaya, akreditasi, dan prospek kerja.', 3, 'UKT berjenjang, beasiswa, akreditasi Unggul.'],
            ['kekuatan_pemasok', 'Dosen bersertifikat & bergelar doktor', 'Rasio dosen–mahasiswa 1:35 belum ideal.', 4, 'Rekrutmen PPPK & sertifikasi pendidik.'],
            ['pendatang_baru', 'PTS & kelas jarak jauh', 'Program daring memperluas pilihan.', 2, 'Program hibrida bermutu.'],
            ['produk_pengganti', 'Kursus sertifikasi & pelatihan kerja', 'Jalur sertifikasi singkat menggantikan sebagian kebutuhan gelar.', 3, 'Sertifikasi kompetensi lulusan dari lembaga bereputasi.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Mahasiswa sarjana (S1)', 'Pendidikan', '22.502 mahasiswa aktif S1 (Laporan Kinerja 2025); UKT sumber terbesar pendapatan BLU.', 88, 'stabil', true, 'Mutu pengajaran, UKT terjangkau, prospek kerja.', 'Prodi Unggul & layanan karier.'],
            ['Mahasiswa pascasarjana & diploma', 'Pendidikan', '822 S2, 219 S3, 128 diploma.', 6, 'naik', true, 'Pembimbing bereputasi, kelas fleksibel.', 'Prodi S2 baru (Pertanian & Peternakan).'],
            ['Mitra kerja sama (pemda, industri)', 'Kerja sama', 'Pelatihan, riset terapan, dan konsultasi.', 2, 'naik', true, 'Solusi tepat guna, pelaporan rapi.', 'Kerja sama non-tridharma yang menghasilkan pendapatan.'],
            ['Pengguna aset & unit usaha', 'Aset', 'Gedung, auditorium, dan unit usaha.', 2, 'naik', true, 'Fasilitas siap pakai, harga bersaing.', 'Pengelolaan aset oleh Pusat Pengembangan Bisnis.'],
            ['Penerima beasiswa & afirmasi', 'Inklusi', 'KIP-Kuliah, beasiswa daerah, mahasiswa berkebutuhan khusus.', 2, 'naik', true, 'Keterjangkauan & pendampingan.', 'Beasiswa mitra & layanan inklusi.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan ilustratif untuk demo']);
        }

        foreach ([
            ['UIN Sultan Syarif Kasim Riau', 'PTKIN BLU', 1, 158.2, 3.9, 12.6, true, 'Integrasi sains & Islam, 42% prodi Unggul, 23 ribu mahasiswa.', 'Pendapatan masih bertumpu UKT, penyerapan dana BLU 87,76%, rasio dosen 1:35.'],
            ['Universitas Riau', 'PTN BLU', 2, null, null, null, false, 'PTN terbesar di Riau, reputasi kuat.', 'Seleksi ketat.'],
            ['PTS besar di Pekanbaru', 'PTS', 3, null, null, null, false, 'Kampus kota, kerja sama industri.', 'Biaya lebih tinggi.'],
            ['PTKIN lain di Sumatera', 'PTKIN', 4, null, null, null, false, 'Bidang kajian serupa, biaya bersaing.', 'Skala & reputasi bervariasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'TA 2025 (simulasi)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'PNBP operasional dalam Rp miliar; margin = margin EBITDA (angka simulasi demo).' : 'Pembanding kualitatif untuk demo.']);
        }

        foreach ([
            ['Kontrak Kinerja Pemimpin BLU', 'Direktorat Jenderal Perbendaharaan', 'PER-6/PB/2025', 'Kinerja BLU', '2025-03-18', 'Pedoman penyusunan Kontrak Kinerja dan penetapan persetujuan capaian kinerja Pemimpin BLU.', 'Dasar seluruh siklus Kontrak Kinerja di aplikasi ini.', $year === 2025 ? 'sebagian' : 'patuh', 'strategi', $year === 2025 ? '2025-04-01' : null],
            ['Penetapan UIN Suska Riau sebagai satker BLU', 'Kementerian Keuangan', 'KMK No. 77/KMK.05/2009', 'Keuangan BLU', '2009-01-01', 'Penetapan pola pengelolaan keuangan BLU.', 'Dasar fleksibilitas keuangan & remunerasi.', 'patuh', 'cfo', null],
            ['Pengelolaan Keuangan BLU', 'Pemerintah', 'PP No. 23/2005 jo. PP No. 74/2012', 'Keuangan BLU', null, 'Fleksibilitas pengelolaan keuangan BLU, tarif layanan, dan remunerasi.', 'Dasar pola pengelolaan keuangan.', 'patuh', 'cfo', null],
            ['Pedoman Pengelolaan BLU', 'Kementerian Keuangan', 'PMK No. 129/PMK.05/2020', 'Keuangan BLU', null, 'Perencanaan, pelaksanaan, pelaporan, dan evaluasi kinerja BLU.', 'RBA, laporan keuangan, penilaian kinerja.', 'patuh', 'cfo', null],
            ['Statuta UIN Suska Riau', 'Kementerian Agama', 'PMA No. 16/2023', 'Kelembagaan', null, 'Visi, misi, organisasi, dan tata kelola universitas.', 'Dasar peta strategi.', 'patuh', 'strategi', null],
            ['Pelindungan data pribadi', 'Pemerintah', 'UU No. 27/2022', 'Teknologi', '2024-10-17', 'Data mahasiswa, pegawai, dan keuangan.', 'Sanksi administratif & reputasi.', 'sebagian', 'ti', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'mahasiswa' => ['Mahasiswa & orang tua (23.671 mahasiswa aktif)', 'eksternal', 'Penerima layanan', 4, 5, 'Pembelajaran bermutu, UKT terjangkau, layanan cepat.', 'Survei kepuasan & layanan pengaduan.', 'Wakil Rektor III'],
            'kemenag' => ['Ditjen Pendidikan Islam (Pembina Teknis)', 'eksternal', 'Pembina Teknis', 5, 5, 'Capaian Perjanjian Kinerja & kepatuhan kebijakan.', 'Persetujuan Definisi Operasional & laporan capaian.', 'Biro AUPK'],
            'djpb' => ['Ditjen Perbendaharaan & Kanwil DJPb Riau (Pembina Keuangan)', 'eksternal', 'Pembina Keuangan', 5, 4, 'Kontrak Kinerja, laporan capaian tepat waktu, kinerja keuangan BLU.', 'Pembahasan Kontrak Kinerja & verifikasi capaian.', 'Wakil Rektor II'],
            'dewas' => ['Dewan Pengawas BLU', 'internal', 'Pengawas', 5, 5, 'Pengawasan kinerja & tata kelola.', 'Rapat Dewan Pengawas triwulanan.', 'Sekretariat Dewas'],
            'dosen' => ['Dosen tetap (723 orang)', 'internal', 'SDM', 4, 5, 'Sertifikasi pendidik, karier, beban mengajar wajar.', 'Senat & forum fakultas.', 'Bagian Kepegawaian'],
            'tendik' => ['Tenaga kependidikan (440 orang)', 'internal', 'SDM', 3, 4, 'Pengembangan kompetensi & kesejahteraan.', 'Pelatihan & sertifikasi.', 'Bagian Kepegawaian'],
            'mitra' => ['Mitra kerja sama, pemda & industri Riau', 'eksternal', 'Mitra', 3, 4, 'Kerja sama berdampak & dilaporkan.', 'Bagian Kerja Sama.', 'Wakil Rektor III'],
            'bpk' => ['Auditor (BPK, KAP, Itjen)', 'eksternal', 'Regulator', 4, 3, 'Laporan keuangan andal.', 'Tindak lanjut temuan tepat waktu.', 'SPI'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'selaras' => ['Perjanjian Kinerja & Kontrak Kinerja belum selaras', 'Pertumbuhan mahasiswa 6,70% di PK vs 1% di Kontrak Kinerja; indikator & target dikelola dua dokumen terpisah.', 'proses', 5, 5, 'strategi', ['kemenag', 'djpb']],
            'serap' => ['Penyerapan dana BLU & belanja modal rendah', 'Dana BLU terserap 87,76% (sisa Rp19,54 M); belanja modal 80,27% (Laporan Kinerja 2025).', 'keuangan', 5, 4, 'cfo', ['djpb', 'dewas']],
            'nonukt' => ['Pendapatan BLU bertumpu UKT', 'Pendapatan non-akademik masih kecil (Laporan Kinerja 2025); target aset & kerja sama Rp2,26 M.', 'keuangan', 4, 5, 'bisnis', ['dewas', 'mitra']],
            'dosen' => ['Rasio dosen–mahasiswa & sertifikasi pendidik', 'Rasio 1:35 belum ideal; banyak dosen PPPK baru belum bersertifikat pendidik.', 'sdm', 4, 4, 'sdm', ['dosen']],
            'lulusan' => ['Lulusan bekerja & masa tunggu', 'Target lulusan bekerja 80,05% dan masa tunggu 7 bulan (PK 2026).', 'pelanggan', 4, 4, 'kemahasiswaan', ['mahasiswa', 'mitra']],
            'kontrak' => ['Disiplin siklus Kontrak Kinerja BLU', 'Laporan capaian ≤ 15 hari kerja setelah triwulan beserta verifikasi internal dan dokumen pendukung.', 'proses', 5, 5, 'strategi', ['djpb', 'kemenag']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => 'Laporan Kinerja 2025, PK 2026, RIP 2014–2034 & analisis manajemen (data demo)', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', '42% prodi berpredikat Unggul', '24 dari 57 prodi Unggul (Laporan Kinerja 2025).', 0.14, 4],
            ['S', 'Integrasi sains & Islam', 'Identitas keilmuan sejak 2005 (RIP).', 0.10, 4],
            ['S', 'Rupiah murni terserap 96,09%', 'Disiplin belanja rupiah murni (Laporan Kinerja 2025).', 0.08, 3],
            ['W', 'Dana BLU terserap 87,76%', 'Sisa Rp19,54 M; belanja modal BLU 77,43%.', 0.12, 2],
            ['W', 'Rasio dosen–mahasiswa 1:35', 'Belum ideal di beberapa prodi (Laporan Kinerja 2025).', 0.10, 2],
            ['W', 'PK & Kontrak Kinerja belum selaras', 'Target berbeda untuk indikator serupa.', 0.08, 2],
            ['O', 'Ekonomi syariah & ekonomi Riau', 'Kebutuhan SDM & riset terapan (RIP).', 0.12, 4],
            ['O', 'Persiapan Fakultas Kedokteran', 'Perluasan layanan pendidikan (Laporan Kinerja 2025).', 0.08, 3],
            ['T', 'Persaingan PTN BLU di Pekanbaru', 'Kompetitor utama dengan pembina keuangan sama.', 0.10, 2],
            ['T', 'Pergantian pimpinan', 'Memengaruhi kesinambungan program 2025.', 0.08, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => 'Laporan Kinerja 2025 & RIP 2014–2034']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Prodi Unggul sebagai magnet PMB & kerja sama', 'Promosikan 24 prodi Unggul; kerja sama industri berbayar.', 5, ['nonukt', 'lulusan']],
            ['WO', 'Satu dokumen kinerja: PK & Kontrak Kinerja selaras', 'Indikator dan target yang sama dikelola di Kinera.', 5, ['selaras', 'kontrak']],
            ['WT', 'Percepatan belanja BLU & modal', 'Rencana penarikan dana BLU per triwulan & lelang dini.', 5, ['serap']],
            ['WT', 'Sertifikasi pendidik dosen PPPK', 'Kuota serdik & pendampingan portofolio.', 4, ['dosen']],
            ['ST', 'Pusat karier & tracer study', 'Lulusan bekerja ≥ 80% dengan masa tunggu ≤ 7 bulan.', 4, ['lulusan']],
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
            'T1' => ['Layanan Prima Akademik', 'Akses terjangkau, akreditasi Unggul, lulusan bekerja dan bersertifikat.', '#1F6E4A'],
            'T2' => ['Riset & Integrasi Keilmuan', 'Output penelitian terindeks, artikel disitasi, integrasi sains & Islam.', '#2A6F9E'],
            'T3' => ['Pengelolaan Keuangan BLU Efektif, Efisien & Akuntabel', 'Margin EBITDA, PNBP operasional, aset & kerja sama, penyerapan dana BLU.', '#C88A12'],
            'T4' => ['Tata Kelola Unggul & SDM', 'PK & Kontrak Kinerja selaras, SAKIP, SPIP, sertifikasi dosen & tendik.', '#6B4FA0'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Naikkan skor rata-rata akreditasi prodi', 'produk', 'PK 2026: skor rata-rata 346.'],
            ['T1', 'Pusat karier & tracer study', 'pasar', 'Lulusan bekerja 80,05%; masa tunggu 7 bulan.'],
            ['T3', 'Optimalisasi aset tetap & kerja sama non-tridharma', 'operasi', 'Kontrak Kinerja 2025: Rp2,26 M.'],
            ['T3', 'Rencana penarikan dana BLU per triwulan', 'operasi', 'Penyerapan BLU 87,76% (2025).'],
            ['T4', 'Satu set indikator untuk PK & Kontrak Kinerja', 'operasi', 'PER-6/PB/2025 & Perjanjian Kinerja Pendis.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T3', 'Pendapatan BLU tumbuh & beragam', 'PNBP operasional tercapai; pendapatan aset & kerja sama meningkat.', 'cfo', 3],
            'F2' => ['keuangan', 'T3', 'Pengelolaan keuangan efektif, efisien & akuntabel', 'Margin EBITDA, penyerapan dana BLU, modernisasi pengelolaan, opini WTP.', 'cfo', 3],
            'C1' => ['pelanggan', 'T1', 'Akses pendidikan yang tumbuh & terjangkau', 'Jumlah mahasiswa tumbuh dan penerima beasiswa bertambah.', 'kemahasiswaan', 2],
            'C2' => ['pelanggan', 'T1', 'Lulusan bekerja, bersertifikat & berkarakter', 'Lulusan cepat bekerja, bersertifikat kompetensi, dan berkeberagamaan baik.', 'kemahasiswaan', 2],
            'C3' => ['pelanggan', 'T2', 'Kemitraan yang menghasilkan program mutu', 'Kerja sama ditindaklanjuti dalam tri dharma.', 'kemahasiswaan', 1.5],
            'P1' => ['proses', 'T1', 'Akreditasi Unggul & penjaminan mutu', 'Prodi Unggul, skor rata-rata akreditasi, SPMI berjalan.', 'lpm', 2],
            'P2' => ['proses', 'T2', 'Riset terindeks & berdampak', 'Output penelitian terindeks/diterapkan dan artikel disitasi.', 'lppm', 2],
            'P3' => ['proses', 'T4', 'Akuntabilitas kinerja & anggaran', 'Nilai Kinerja Anggaran, SAKIP, dan SPIP.', 'strategi', 1.5],
            'P4' => ['proses', 'T3', 'Aset & unit usaha produktif', 'Utilisasi aset komersial meningkat.', 'bisnis', 1.5],
            'L1' => ['sdm', 'T4', 'Dosen & tendik berkualitas', 'Dosen bersertifikat pendidik; dosen & tendik bersertifikat kompetensi.', 'sdm', 1.5],
            'L2' => ['sdm', 'T2', 'Integrasi sains & Islam dalam kurikulum', 'Mata kuliah berbasis integrasi keilmuan.', 'akademik', 1],
            'L3' => ['sdm', 'T4', 'Layanan publik & digital', 'Indeks pelayanan publik dan layanan terintegrasi.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P2', 2], ['L2', 'C2', 2], ['L2', 'P1', 2], ['L3', 'C1', 2], ['L3', 'P3', 2],
            ['P1', 'C1', 3], ['P1', 'C2', 3], ['P2', 'C3', 2], ['P3', 'F2', 3], ['P4', 'F1', 3],
            ['F1', 'C1', 3], ['C2', 'C1', 2], ['F1', 'C3', 2], ['F1', 'F2', 2], ['C3', 'C2', 1],
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

        // kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, PJ, rumus, sumber, 2024, target 2025, realisasi 2025, target 2026, faktor 2026, dari laporan?, trajektori
        return [
            ['F1.1', 'F1', 'Jumlah pendapatan PNBP operasional', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Pendapatan BLU operasional (LRA) kumulatif', 'LRA BLU / SAKTI', 152300, 160613, 158240, 166000, .975, $il, $q],
            ['F1.2', 'F1', 'Pendapatan optimalisasi aset tetap, aset lainnya, kerja sama non-tridharma & unit usaha', 'Rp juta', 'higher', 'sum', 0, true, false, 'bisnis', 'Pendapatan sewa, pemanfaatan aset, kerja sama non-tridharma, dan unit usaha', 'LRA BLU', 1900, 2257, 1410, 2500, .63, $il, $q],
            ['F1.3', 'F1', 'Pendapatan optimalisasi aset lancar', 'Rp juta', 'higher', 'sum', 0, true, false, 'cfo', 'Jasa giro & bunga deposito dana BLU', 'LRA BLU, rekening koran', 1450, 1593, 1720, 1700, 1.0, $il, $q],
            ['F1.4', 'F1', 'Indeks akurasi proyeksi pendapatan & belanja BLU', 'indeks', 'higher', 'last', 2, true, false, 'strategi', 'Skor kedekatan proyeksi RBA dengan realisasi (skala 4)', 'RBA & LRA BLU', 3.0, 3.5, 3.2, 3.5, 1.0, $il, $y],
            ['F2.1', 'F2', 'Margin EBITDA', '%', 'higher', 'last', 2, true, true, 'cfo', '(Surplus operasional + penyusutan & amortisasi) ÷ pendapatan operasional × 100', 'Laporan Operasional BLU', 11.40, 14.22, 12.60, 15.00, .96, $il, $q],
            ['F2.2', 'F2', 'Penyerapan dana BLU (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja sumber dana BLU ÷ pagu BLU × 100', 'LRA / OM-SPAN', null, 95.0, 87.76, 95.0, .95, $lt, $q],
            ['F2.3', 'F2', 'Penyerapan belanja modal (kumulatif)', '%', 'higher', 'sum', 2, true, false, 'cfo', 'Realisasi belanja modal ÷ pagu belanja modal × 100', 'LRA / OM-SPAN', null, 90.0, 80.27, 90.0, .86, $lt, $q],
            ['F2.4', 'F2', 'Modernisasi pengelolaan BLU', '%', 'higher', 'last', 1, true, false, 'cfo', 'Komponen modernisasi yang terpenuhi ÷ komponen yang ditetapkan DJPb × 100', 'Penilaian DJPb', 60.0, 80.0, 76.0, 85.0, .98, $il, $q],
            ['F2.5', 'F2', 'Opini atas Laporan Keuangan BLU (4 = WTP)', 'skor', 'higher', 'last', 0, true, true, 'cfo', 'WTP = 4, WDP = 3, TW = 2, TMP = 1', 'Laporan auditor independen', 4, 4, 4, 4, 1.0, $il, $y],
            ['C1.1', 'C1', 'Rasio pertumbuhan jumlah mahasiswa', '%', 'higher', 'last', 2, false, true, 'kemahasiswaan', '(Mahasiswa aktif tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'PDDikti / SIA', null, 1.0, 0.62, 0.5, .90, $il, $q],
            ['C1.2', 'C1', 'Peningkatan persentase penerima beasiswa', '%', 'higher', 'last', 2, false, false, 'kemahasiswaan', '(Penerima beasiswa tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'Bagian Kemahasiswaan', null, 9.97, 10.40, 10.0, 1.0, $il, $q],
            ['C2.1', 'C2', 'Persentase lulusan yang bekerja atau berwiraswasta', '%', 'higher', 'last', 2, false, true, 'kemahasiswaan', 'Lulusan bekerja/berwiraswasta ÷ responden tracer × 100', 'Tracer study', null, 80.05, 76.40, 82.0, .96, $il, $q],
            ['C2.2', 'C2', 'Rata-rata masa tunggu lulusan memperoleh pekerjaan', 'bulan', 'lower', 'last', 1, false, false, 'kemahasiswaan', 'Rata-rata bulan sejak lulus sampai bekerja (responden tracer)', 'Tracer study', null, 7.5, 7.2, 7.0, 1.04, $il, 'semesteran'],
            ['C2.3', 'C2', 'Persentase lulusan bersertifikat kompetensi dari lembaga bereputasi', '%', 'higher', 'last', 2, false, false, 'akademik', 'Lulusan bersertifikat kompetensi ÷ total lulusan × 100', 'SIA & LSP', null, 5.0, 3.60, 6.0, .85, $il, $q],
            ['C2.4', 'C2', 'Indeks keberagamaan mahasiswa', 'skor', 'higher', 'last', 2, false, false, 'akademik', 'Skor survei keberagamaan mahasiswa (skala 100)', 'Survei LPM', null, 85.0, 86.40, 90.0, 1.0, $il, 'semesteran'],
            ['C3.1', 'C3', 'Persentase kerja sama yang ditindaklanjuti menjadi program peningkatan mutu', '%', 'higher', 'last', 2, false, false, 'kemahasiswaan', 'Kerja sama aktif yang menghasilkan program ÷ kerja sama aktif × 100', 'Bagian Kerja Sama', null, 2.0, 2.60, 20.0, .82, $il, $q],
            ['P1.1', 'P1', 'Persentase prodi terakreditasi A/Unggul', '%', 'higher', 'last', 1, false, true, 'lpm', '(Prodi Unggul + A) ÷ total prodi × 100', 'SK BAN-PT/LAM', 38.0, 25.0, 49.1, 52.0, 1.0, $lt, $q],
            ['P1.2', 'P1', 'Skor rata-rata akreditasi program studi', 'skor', 'higher', 'last', 1, false, false, 'lpm', 'Rata-rata nilai akreditasi seluruh prodi (skala 400)', 'SK BAN-PT/LAM', 331.0, 335.0, 338.5, 346.0, .995, $il, $q],
            ['P1.3', 'P1', 'Persentase unit yang melaksanakan sistem penjaminan mutu', '%', 'higher', 'last', 1, false, false, 'lpm', 'Unit dengan siklus PPEPP lengkap ÷ total unit × 100', 'LPM (audit mutu internal)', null, 62.5, 68.4, 75.0, 1.0, $il, $q],
            ['P2.1', 'P2', 'Rasio pertumbuhan output penelitian terindeks atau diterapkan masyarakat', '%', 'higher', 'last', 2, false, false, 'lppm', '(Output terindeks/diterapkan tahun berjalan − tahun lalu) ÷ tahun lalu × 100', 'SINTA & LPPM', null, 83.11, 71.50, 85.0, .92, $il, $q],
            ['P2.2', 'P2', 'Persentase artikel jurnal bereputasi nasional yang disitasi', '%', 'higher', 'last', 2, false, false, 'lppm', 'Artikel disitasi ÷ artikel terbit di jurnal bereputasi nasional × 100', 'SINTA / Google Scholar', null, 77.60, 74.20, 78.0, 1.0, $il, $q],
            ['P3.1', 'P3', 'Nilai Kinerja Anggaran (NKA)', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Nilai aplikasi SMART DJA', 'SMART DJA', null, 83.00, 86.20, 87.00, 1.0, $il, $y],
            ['P3.2', 'P3', 'Nilai SAKIP', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Hasil evaluasi SAKIP', 'LHE SAKIP', null, 89.50, 88.10, 90.00, 1.0, $il, $y],
            ['P3.3', 'P3', 'Tingkat maturitas SPIP', 'skor', 'higher', 'last', 2, false, false, 'strategi', 'Skor penilaian maturitas SPIP (skala 5)', 'Hasil penilaian maturitas SPIP', null, 2.50, 3.10, 3.20, 1.0, $il, $y],
            ['P4.1', 'P4', 'Utilisasi aset komersial (gedung, auditorium & unit usaha)', '%', 'higher', 'average', 1, true, false, 'bisnis', 'Hari terpakai ÷ hari tersedia × 100', 'Pusat Pengembangan Bisnis', 40.0, 55.0, 46.0, 55.0, .92, $il, $q],
            ['L1.1', 'L1', 'Persentase dosen yang memperoleh sertifikasi pendidik', '%', 'higher', 'last', 2, false, false, 'sdm', 'Dosen bersertifikat pendidik ÷ total dosen tetap × 100', 'SIMPEG & SISTER', null, 66.0, 61.20, 66.0, .93, $il, $q],
            ['L1.2', 'L1', 'Persentase dosen & tendik bersertifikat peningkatan kompetensi', '%', 'higher', 'last', 2, false, false, 'sdm', 'Dosen & tendik bersertifikat kompetensi ÷ total dosen & tendik × 100', 'SIMPEG', null, 3.40, 3.90, 4.50, .97, $il, $q],
            ['L2.1', 'L2', 'Persentase mata kuliah berbasis integrasi sains & Islam', '%', 'higher', 'last', 1, false, false, 'akademik', 'Mata kuliah dengan RPS integrasi ÷ total mata kuliah × 100', 'Dokumen kurikulum & RPS', 52.0, 60.0, 58.0, 65.0, 1.0, $il, $q],
            ['L3.1', 'L3', 'Indeks pelayanan publik', 'skor', 'higher', 'last', 2, false, false, 'ti', 'Hasil penilaian indeks pelayanan publik (skala 5)', 'Evaluasi pelayanan publik', null, 3.00, 3.62, 3.80, .99, $il, 'semesteran'],
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
        $kk = ' (Kontrak Kinerja 2025: ';

        return [
            'F1.1' => ['keuangan', true, 110, 'pnbp', 'Pendapatan BLU operasional yang dicatat sebagai PNBP pada LRA dibandingkan target'.$kk.'Rp160,61 M).', $tw, 'Rekonsiliasi LRA BLU dengan SAKTI & rekening koran; uji petik bukti setor oleh SPI.', 'LRA BLU, rekap SAKTI, rekening koran BLU, hasil verifikasi SPI'],
            'F1.2' => ['keuangan', false, 120, null, 'Pendapatan optimalisasi aset tetap, aset lainnya, kerja sama non-tridharma, dan unit usaha'.$kk.'Rp2,26 M, bobot 70%).', $tw, 'Pencocokan kontrak sewa/kerja sama, invoice, dan bukti setor.', 'Kontrak sewa & kerja sama, bukti setor, LRA'],
            'F1.3' => ['keuangan', false, 120, null, 'Pendapatan optimalisasi aset lancar (jasa giro, deposito)'.$kk.'Rp1,59 M, bobot 30%).', $tw, 'Pencocokan rekening koran & bilyet deposito.', 'Rekening koran, bilyet deposito, LRA'],
            'F1.4' => ['keuangan', false, 110, null, 'Indeks akurasi proyeksi pendapatan dan belanja BLU'.$kk.'3,5).', $th, 'Rekalkulasi selisih proyeksi RBA terhadap realisasi.', 'RBA, LRA, kertas kerja'],
            'F2.1' => ['keuangan', true, 120, 'ebitda', 'Margin EBITDA: surplus operasional ditambah penyusutan & amortisasi dibagi pendapatan operasional'.$kk.'14,22%).', $tw, 'Rekalkulasi dari Laporan Operasional; review SPI.', 'Laporan Operasional, rincian beban penyusutan'],
            'F2.2' => ['keuangan', false, 100, null, 'Realisasi belanja sumber dana BLU terhadap pagu BLU (2025: Rp140,05 M dari Rp159,59 M = 87,76%).', $tw, 'Pencocokan LRA dengan OM-SPAN.', 'LRA, OM-SPAN, revisi DIPA'],
            'F2.3' => ['keuangan', false, 100, null, 'Realisasi belanja modal terhadap pagu belanja modal (2025: 80,27%).', $tw, 'Pencocokan kontrak pengadaan, BAST, dan SP2D.', 'Kontrak, BAST, SP2D, rencana penarikan dana'],
            'F2.4' => ['keuangan', false, 110, null, 'Tingkat pemenuhan komponen modernisasi pengelolaan BLU'.$kk.'80%).', $tw, 'Telaah bukti pemenuhan tiap komponen.', 'Daftar komponen, bukti implementasi'],
            'F2.5' => ['keuangan', true, 100, 'opini', 'Opini auditor independen atas Laporan Keuangan BLU tahun berjalan.', $th, 'Pemeriksaan Laporan Hasil Audit.', 'Laporan auditor independen atas LK BLU'],
            'C1.1' => ['layanan', true, 120, null, 'Pertumbuhan jumlah mahasiswa aktif'.$kk.'1%; Perjanjian Kinerja 2025: 6,70%; PK 2026: 0,5%).', $tw, 'Pencocokan SIA dengan PDDikti.', 'Rekap PDDikti, laporan SIA'],
            'C1.2' => ['layanan', false, 120, null, 'Peningkatan persentase mahasiswa penerima beasiswa'.$kk.'9,97%).', $tw, 'Pencocokan SK penerima beasiswa.', 'SK beasiswa, rekap penerima'],
            'C2.1' => ['layanan', true, 120, null, 'Lulusan yang bekerja atau berwiraswasta menurut tracer study'.$kk.'80,05%).', $tw, 'Uji petik responden tracer study.', 'Laporan tracer study, daftar responden'],
            'C2.2' => ['layanan', false, 110, null, 'Rata-rata masa tunggu lulusan memperoleh pekerjaan (PK 2026: 7 bulan).', 'Semesteran; dari tracer study.', 'Uji petik responden tracer study.', 'Laporan tracer study'],
            'C2.3' => ['layanan', false, 120, null, 'Lulusan yang memperoleh sertifikat kompetensi dari lembaga sertifikasi bereputasi'.$kk.'5%).', $tw, 'Pencocokan sertifikat dengan daftar lulusan.', 'Salinan sertifikat, daftar lulusan'],
            'C2.4' => ['layanan', false, 110, null, 'Indeks keberagamaan mahasiswa Islam (PK 2026: 90).', 'Semesteran; survei pada akhir semester.', 'Uji validitas instrumen & sampel survei.', 'Laporan survei, instrumen'],
            'C3.1' => ['layanan', false, 120, null, 'Kerja sama aktif yang ditindaklanjuti dalam tri dharma dan menghasilkan program peningkatan mutu'.$kk.'2%; PK 2026: 20%).', $tw, 'Uji petik laporan kegiatan kerja sama.', 'Naskah kerja sama, laporan kegiatan'],
            'P1.1' => ['layanan', true, 120, null, 'Prodi berpredikat Unggul atau A terhadap total prodi'.$kk.'25%). Posisi akhir 2025: 24 Unggul + 4 A dari 57 prodi.', $tw, 'Pencocokan SK akreditasi.', 'SK BAN-PT/LAM'],
            'P1.2' => ['layanan', false, 110, null, 'Rata-rata nilai akreditasi seluruh program studi (PK 2026: 346).', $tw, 'Rekalkulasi dari SK & nilai akreditasi.', 'SK & laporan penilaian akreditasi'],
            'P1.3' => ['layanan', false, 120, null, 'Unit yang melaksanakan sistem penjaminan mutu (Perjanjian Kinerja 2025: 62,5%).', $tw, 'Audit mutu internal oleh LPM.', 'Laporan AMI, dokumen PPEPP'],
            'P2.1' => ['layanan', false, 120, null, 'Pertumbuhan output penelitian terindeks atau diterapkan oleh masyarakat'.$kk.'83,11%).', $tw, 'Penelusuran SINTA & bukti penerapan.', 'Rekap SINTA, surat keterangan penerapan'],
            'P2.2' => ['layanan', false, 120, null, 'Artikel di jurnal bereputasi nasional yang telah disitasi (Perjanjian Kinerja 2025: 77,60%).', $tw, 'Penelusuran SINTA/Google Scholar.', 'Rekap SINTA, tangkapan layar sitasi'],
            'P3.1' => ['penugasan', false, 110, null, 'Nilai Kinerja Anggaran dari aplikasi SMART DJA (Perjanjian Kinerja 2025: 83).', $th, 'Pencocokan dengan aplikasi SMART DJA.', 'Cetakan SMART DJA'],
            'P3.2' => ['penugasan', false, 110, null, 'Nilai SAKIP (Perjanjian Kinerja 2025: 89,50; PK 2026: 90).', $th, 'Pencocokan Laporan Hasil Evaluasi SAKIP.', 'LHE SAKIP'],
            'P3.3' => ['penugasan', false, 110, null, 'Tingkat maturitas SPIP (Perjanjian Kinerja 2025: 2,5).', $th, 'Pencocokan hasil penilaian maturitas.', 'Laporan penilaian maturitas SPIP'],
            'P4.1' => ['keuangan', false, 120, null, 'Tingkat pemakaian aset komersial dibandingkan hari tersedia.', $tw, 'Uji petik log pemesanan.', 'Rekap pemesanan, kontrak sewa'],
            'L1.1' => ['layanan', false, 120, null, 'Dosen tetap yang memperoleh sertifikasi pendidik (Perjanjian Kinerja 2025: 66%; PK 2026: 58% karena tambahan dosen PPPK).', $tw, 'Pencocokan SIMPEG/SISTER dengan sertifikat pendidik.', 'Rekap SISTER, salinan sertifikat'],
            'L1.2' => ['layanan', false, 120, null, 'Dosen dan tendik yang memperoleh sertifikasi peningkatan kompetensi'.$kk.'3,40%).', $tw, 'Pencocokan SIMPEG dengan sertifikat.', 'Rekap SIMPEG, salinan sertifikat'],
            'L2.1' => ['layanan', false, 120, null, 'Mata kuliah yang RPS-nya memuat integrasi sains dan Islam.', $tw, 'Telaah sampel RPS.', 'Dokumen kurikulum, RPS'],
            'L3.1' => ['layanan', false, 110, null, 'Indeks pelayanan publik (Perjanjian Kinerja 2025: 3).', 'Semesteran; dari evaluasi pelayanan publik.', 'Pencocokan hasil evaluasi.', 'Laporan evaluasi pelayanan publik'],
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
            // kontrak awal ditandatangani 31 Januari 2025, sebelum PER-6/PB/2025: belum memuat Definisi Operasional lengkap
            $v0 = collect($svc->snapshot())->map(fn ($i) => array_merge($i, ['collection' => null, 'test' => null, 'documents' => null]))->all();
            PerformanceContract::create($common + [
                'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'PRJ-xx/PB/2025 (contoh)', 'indicators' => $v0,
                'proposed_at' => '2024-12-27', 'discussed_at' => '2025-01-22', 'ba_number' => 'BA-xx/PB/2025 (contoh)', 'leader_signed_at' => '2025-01-31', 'dg_signed_at' => '2025-01-31',
                'legal_case_free' => true, 'other_requirements_met' => true, 'notes' => 'DATA DEMO. Target indikator mengikuti Kontrak Kinerja 2025 yang dimuat dalam Laporan Kinerja 2025; nomor & pejabat diganti contoh.',
            ]);
            $previous = PerformanceContract::where('version', 0)->first();
            PerformanceContract::create($common + [
                'version' => 1, 'kind' => 'adendum', 'status' => 'ditandatangani', 'number' => 'PRJ-xx.A/PB/2025 (contoh)', 'basis' => 'peralihan',
                'reason' => 'Penyesuaian Kontrak Kinerja 2025 dengan PER-6/PB/2025: melengkapi aspek indikator, trajektori triwulanan, Definisi Operasional, dan indikator wajib.',
                'indicators' => $svc->snapshot($previous), 'proposed_at' => '2025-03-27', 'discussed_at' => '2025-03-28', 'leader_signed_at' => '2025-03-28', 'dg_signed_at' => '2025-04-01',
                'legal_case_free' => true, 'other_requirements_met' => true, 'incentive_notes' => 'Tidak ada persyaratan tambahan dari Pembina Teknis (contoh).',
                'notes' => 'DATA DEMO. Adendum peralihan Pasal 21 PER-6/PB/2025 (simulasi).',
            ]);

            return;
        }

        $signed = PerformanceContract::create($common + [
            'version' => 0, 'kind' => 'awal', 'status' => 'ditandatangani', 'number' => 'PRJ-xx/PB/2026 (contoh)', 'indicators' => $svc->snapshot(),
            'proposed_at' => '2025-12-30', 'discussed_at' => '2026-01-14', 'ba_number' => 'BA-xx/PB/2026 (contoh)', 'leader_signed_at' => '2026-01-16', 'dg_signed_at' => '2026-01-19',
            'legal_case_free' => true, 'notes' => 'DATA DEMO. Proyeksi Kontrak Kinerja 2026, ditandatangani sebelum Perjanjian Kinerja 2026 terbit.',
        ]);

        // usulan perubahan (Pasal 19 ayat 1 huruf c): PK 2026 dari Pembina Teknis menetapkan target dosen bersertifikat pendidik 58%
        $changed = collect($svc->snapshot($signed))->map(function ($i) {
            if ($i['code'] !== 'L1.1') {
                return $i;
            }
            $i['targets'] = [3 => 60.4, 6 => 59.6, 9 => 58.8, 12 => 58.0];
            $i['annual'] = 58.0;
            $i['change'] = 'target_turun';
            $i['previous_annual'] = 66.0;

            return $i;
        })->all();
        PerformanceContract::create($common + [
            'version' => 1, 'kind' => 'perubahan', 'status' => 'diusulkan', 'number' => null, 'basis' => 'perubahan_kebijakan', 'indicators' => $changed,
            'reason' => 'Perjanjian Kinerja 2026 dengan Ditjen Pendidikan Islam menetapkan target dosen bersertifikat pendidik 58% (penyebut bertambah karena dosen PPPK baru), lebih rendah dari 66% dalam Kontrak Kinerja. Target L1.1 diusulkan disamakan menjadi 58%. Bila disetujui, capaian L1.1 paling tinggi 100% (Pasal 19 ayat 8).',
            'proposed_at' => '2026-09-29', 'technical_approval' => 'Perjanjian Kinerja 2026 & surat persetujuan Dirjen Pendidikan Islam (contoh)',
            'notes' => 'DATA DEMO. Menunggu pembahasan dengan DJPb; tenggat penandatanganan 1 Desember.',
        ]);
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Gedung Rektorat, Kampus Panam UIN Suska Riau';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja & Laporan Kinerja 2025', 'review_type' => 'tahunan', 'review_date' => '2026-01-21', 'period_label' => 'Tahun 2025',
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Dekan, Kepala Biro, Ketua Lembaga, SPI',
                'summary' => "Realisasi anggaran 92,33%: rupiah murni 96,09%, dana BLU 87,76%, belanja modal 80,27%.\nProdi A/Unggul 49,1% jauh di atas target 25%.\nPendapatan aset & kerja sama ±62% target (simulasi) — di bawah ambang 65%.\nPertumbuhan mahasiswa: PK menargetkan 6,70%, Kontrak Kinerja 1%.",
                'decisions' => "1. Mulai 2026 indikator PK dan Kontrak Kinerja dikelola dalam satu set di Kinera.\n2. Target prodi Unggul ditetapkan dari posisi terakhir.\n3. Rencana penarikan dana BLU per triwulan wajib disusun unit.\n4. Katalog aset komersial & kerja sama non-tridharma.",
                'follow_up' => 'Bagian Perencanaan menyiapkan usulan Kontrak Kinerja 2026.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Pengawas meminta penjelasan sisa dana BLU Rp19,54 M dan rencana pemulihan pendapatan aset.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Pimpinan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-11', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
                'summary' => match ($m) {
                    1 => 'Perjanjian Kinerja 2026 terbit; beberapa target berbeda dengan Kontrak Kinerja yang sudah ditandatangani.',
                    3 => 'Triwulan I ditutup; penyerapan dana BLU 92% trajektori.',
                    6 => 'Triwulan II: pendapatan aset & kerja sama ±62% trajektori; belanja modal 86%.',
                    8 => 'Tracer study 2026 berjalan; data dosen PPPK bersertifikat pendidik diperbarui.',
                    default => 'Tinjauan indikator merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    1 => 'Petakan perbedaan PK 2026 dengan Kontrak Kinerja 2026.',
                    3 => 'Laporan capaian TW I dikirim paling lambat 21 April 2026.',
                    6 => 'Satgas pendapatan aset & kerja sama non-tridharma.',
                    8 => 'Siapkan usulan perubahan target L1.1 (Pasal 19) sebelum 30 September.',
                    default => 'Lanjutkan rencana aksi; penanggung jawab IK merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-15', 'Triwulan I 2026'], [2, '2026-07-15', 'Triwulan II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Evaluasi Capaian Kontrak Kinerja — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Pimpinan, SPI, Dewan Pengawas (pengamat)',
                'summary' => $q === 1 ? 'PNBP operasional TW I 97% trajektori; margin EBITDA di bawah target; indikator layanan sesuai trajektori.' : 'Pendapatan aset & kerja sama di bawah 65% (peringatan insentif); dosen bersertifikat pendidik 93% trajektori.',
                'decisions' => $q === 1 ? 'SPI memverifikasi dokumen pendukung sebelum laporan dikirim.' : 'Percepat paket sewa aset; kaji usulan perubahan target L1.1.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['dewas'] = PerformanceReview::create([
            'title' => 'Rapat Dewan Pengawas — Semester I 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-23', 'period_label' => 'Semester I 2026',
            'chair' => 'Ketua Dewan Pengawas', 'venue' => $venue, 'participants' => 'Dewan Pengawas, Rektor, Wakil Rektor II, SPI',
            'summary' => 'Dewas menyoroti pendapatan aset & kerja sama di bawah 65% (syarat insentif huruf c) dan margin EBITDA di bawah target.',
            'decisions' => 'Rektor melaporkan pemulihan pendapatan aset setiap bulan.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['dewas']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Pastikan usulan perubahan target L1.1 dilampiri PK 2026 dan simulasi dampak terhadap capaian total.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Pimpinan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Rektor', 'venue' => $venue, 'participants' => 'Rektor, Wakil Rektor, Kepala Biro, Ketua Lembaga',
            'summary' => 'Realisasi Triwulan III sebagian masih draf; data lulusan bekerja dikembalikan dan utilisasi aset belum diinput. Laporan TW III berisiko melewati tenggat 21 Oktober.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi TW III disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo; realisasi belanja 2025 mengacu Laporan Kinerja Rp326,3 M.';

            return [
                'defs' => [
                    'adendum' => ['USR-25-01', 'Adendum Kontrak Kinerja sesuai PER-6/PB/2025', 'Melengkapi aspek indikator, trajektori, dan Definisi Operasional Kontrak Kinerja 2025.', 'T4', 'strategi', '2025-03-18', '2025-04-01', 'selesai', 5, 5, 2, 1, ['P3', 'F2'], 'Adendum ditandatangani.'],
                    'akreditasi' => ['USR-25-02', 'Pendampingan akreditasi Unggul', 'Pendampingan borang & visitasi prodi.', 'T1', 'lpm', '2025-01-15', '2025-12-15', 'selesai', 5, 4, 3, 3, ['P1'], 'Prodi Unggul bertambah.'],
                    'aset' => ['USR-25-03', 'Optimalisasi aset & kerja sama non-tridharma', 'Inventarisasi aset komersial & tarif layanan.', 'T3', 'bisnis', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 3, 3, ['P4', 'F1'], 'Pendapatan aset Rp2,26 M.'],
                    'tracer' => ['USR-25-04', 'Tracer study & pusat karier', 'Survei lulusan dan layanan karier.', 'T1', 'kemahasiswaan', '2025-03-01', '2025-11-30', 'selesai', 4, 3, 2, 2, ['C2'], 'Lulusan bekerja 80,05%.'],
                ],
                'activities' => [
                    ['adendum', 'Penyusunan Definisi Operasional', 'strategi', '2025-03-18', '2025-03-26', 100], ['adendum', 'Penandatanganan adendum', 'dirut', '2025-03-27', '2025-04-01', 100],
                    ['akreditasi', 'Pendampingan borang & visitasi', 'lpm', '2025-01-15', '2025-12-15', 100],
                    ['aset', 'Inventarisasi aset komersial', 'bisnis', '2025-02-01', '2025-12-31', 100],
                    ['tracer', 'Survei tracer study 2025', 'kemahasiswaan', '2025-03-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['adendum', 'Adendum diterima DJPb', '2025-04-01', true], ['akreditasi', 'SK akreditasi gelombang 2025 terbit', '2025-12-15', true],
                ],
                'budgets' => [
                    ['akreditasi', 'Pendampingan & visitasi akreditasi', 'opex', 3_000_000_000, 2_700_000_000, $note, 'PNBP BLU'],
                    ['aset', 'Perbaikan gedung & auditorium untuk sewa', 'capex', 4_000_000_000, 3_060_000_000, $note, 'PNBP BLU'],
                    ['tracer', 'Tracer study & pusat karier', 'opex', 900_000_000, 760_000_000, $note, 'PNBP BLU'],
                ],
            ];
        }

        return [
            'defs' => [
                'selaras' => ['USR-26-01', 'Penyelarasan Perjanjian Kinerja & Kontrak Kinerja di Kinera', 'Satu set indikator, target, dan Definisi Operasional untuk Pendis dan DJPb; siklus laporan triwulanan.', 'T4', 'strategi', '2026-01-05', '2026-12-31', 'berjalan', 5, 5, 2, 1, ['P3', 'F2'], 'Tidak ada lagi target berbeda untuk indikator yang sama.'],
                'aset' => ['USR-26-02', 'Optimalisasi aset tetap & kerja sama non-tridharma', 'Paket sewa gedung & auditorium, unit usaha, kerja sama berbayar.', 'T3', 'bisnis', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 3, ['P4', 'F1'], 'Pendapatan aset Rp2,5 M; utilisasi 55%.'],
                'serap' => ['USR-26-03', 'Rencana penarikan dana BLU & percepatan belanja modal', 'Rencana penarikan per unit, lelang dini, pemantauan kontrak.', 'T3', 'cfo', '2026-01-05', '2026-12-15', 'berjalan', 5, 5, 2, 1, ['F2'], 'Penyerapan BLU 95%, belanja modal 90%.'],
                'serdik' => ['USR-26-04', 'Sertifikasi pendidik dosen PPPK', 'Pendampingan portofolio & kuota serdik.', 'T4', 'sdm', '2026-02-01', '2026-12-15', 'berjalan', 4, 4, 2, 2, ['L1'], 'Dosen bersertifikat pendidik ≥ 58%.'],
                'karier' => ['USR-26-05', 'Pusat karier & sertifikasi kompetensi lulusan', 'Tracer study tahunan, bursa kerja, kerja sama LSP.', 'T1', 'kemahasiswaan', '2026-01-15', '2026-12-15', 'berjalan', 4, 4, 2, 2, ['C2'], 'Lulusan bekerja 82%; masa tunggu ≤ 7 bulan.'],
                'akreditasi' => ['USR-26-06', 'Peningkatan skor akreditasi prodi B/Baik', 'Pendampingan 24 prodi non-Unggul menuju Baik Sekali/Unggul.', 'T1', 'lpm', '2026-01-15', '2027-06-30', 'berjalan', 4, 4, 3, 3, ['P1'], 'Skor rata-rata akreditasi 346.'],
            ],
            'activities' => [
                ['selaras', 'Pemetaan perbedaan PK 2026 & Kontrak Kinerja 2026', 'strategi', '2026-01-20', '2026-02-28', 100], ['selaras', 'Laporan capaian TW I & II', 'strategi', '2026-04-01', '2026-07-21', 100],
                ['selaras', 'Laporan capaian TW III', 'strategi', '2026-10-01', '2026-10-21', 55], ['selaras', 'Usulan perubahan Kontrak Kinerja', 'strategi', '2026-08-15', '2026-09-29', 100],
                ['aset', 'Katalog & tarif aset komersial', 'bisnis', '2026-01-01', '2026-03-31', 100], ['aset', 'Paket sewa gedung & auditorium', 'bisnis', '2026-04-01', '2026-12-31', 50],
                ['aset', 'Kerja sama non-tridharma dengan industri', 'kemahasiswaan', '2026-03-01', '2026-12-31', 40],
                ['serap', 'Rencana penarikan dana per unit', 'cfo', '2026-01-05', '2026-02-15', 100], ['serap', 'Lelang dini belanja modal', 'cfo', '2026-01-15', '2026-04-30', 100],
                ['serap', 'Pemantauan kontrak & BAST', 'cfo', '2026-05-01', '2026-12-15', 60],
                ['serdik', 'Pendampingan portofolio serdik', 'sdm', '2026-02-01', '2026-10-31', 70],
                ['karier', 'Tracer study 2026', 'kemahasiswaan', '2026-05-01', '2026-10-31', 60], ['karier', 'Kerja sama LSP', 'akademik', '2026-02-01', '2026-06-30', 100],
                ['akreditasi', 'Pendampingan prodi B/Baik', 'lpm', '2026-01-15', '2026-12-15', 55], ['akreditasi', 'Simulasi asesmen lapangan', 'lpm', '2026-06-01', '2027-06-30', 25],
            ],
            'milestones' => [
                ['selaras', 'Kontrak Kinerja 2026 ditandatangani', '2026-01-19', true], ['selaras', 'Laporan TW II terkirim', '2026-07-21', true], ['selaras', 'Laporan TW III terkirim', '2026-10-21', false],
                ['serap', 'Seluruh kontrak belanja modal ditandatangani', '2026-05-31', true], ['aset', 'Pendapatan aset Rp1,5 M (TW III)', '2026-09-30', false],
            ],
            'budgets' => [
                ['selaras', 'Implementasi Kinera & pelatihan', 'opex', 450_000_000, 360_000_000, null, 'PNBP BLU'],
                ['aset', 'Penyiapan gedung & auditorium sewa', 'capex', 3_500_000_000, 2_100_000_000, null, 'PNBP BLU'],
                ['serap', 'Pendampingan pengadaan', 'opex', 300_000_000, 220_000_000, null, 'PNBP BLU'],
                ['serdik', 'Pendampingan serdik dosen', 'opex', 600_000_000, 380_000_000, null, 'PNBP BLU'],
                ['karier', 'Tracer study, bursa kerja & uji kompetensi', 'opex', 1_800_000_000, 1_020_000_000, null, 'PNBP BLU'],
                ['akreditasi', 'Pendampingan akreditasi', 'opex', 2_800_000_000, 1_500_000_000, null, 'PNBP BLU'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Dana BLU terserap 87,76%', 'Realisasi Rp140,05 M dari pagu Rp159,59 M; sisa Rp19,54 M; belanja modal BLU 77,43% (Laporan Kinerja 2025).', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 19541466670, 'F2.2', null, 'komisaris', '2026-01-21',
                [['Rencana penarikan dana BLU per unit', 'cfo', '2026-02-15', 'selesai', 100]], null],
            ['Target PK dan Kontrak Kinerja berbeda', 'Pertumbuhan mahasiswa 6,70% (PK) vs 1% (Kontrak Kinerja); prodi Unggul ditargetkan 25% padahal posisi 42%.', 'tinjauan', 'tahunan', 'proses', 'tinggi', null, 'C1.1', 'adendum', 'dirut', '2026-01-21',
                [['Satu set indikator & target di Kinera', 'strategi', '2026-02-28', 'selesai', 100]], ['2026-03-02', 'Pemetaan PK–Kontrak Kinerja 2026 selesai.']],
            ['Pendapatan aset & kerja sama di bawah 65% target', 'Rp1,41 M dari target Rp2,26 M (simulasi); syarat insentif huruf c tidak terpenuhi.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F1.2', 'aset', 'cfo', '2026-01-21',
                [['Katalog & tarif aset komersial', 'bisnis', '2026-03-31', 'selesai', 100]], null],
        ] : [
            ['Pendapatan aset & kerja sama masih di bawah ambang 65%', 'Capaian s.d. TW II ±62% trajektori; menggugurkan syarat insentif huruf c bila berlanjut.', 'timwas', 'dewas', 'keuangan', 'kritis', null, 'F1.2', 'aset', 'komisaris', '2026-07-23',
                [['Paket sewa gedung & auditorium', 'bisnis', '2026-12-31', 'berjalan', 50], ['Kerja sama non-tridharma dengan industri', 'kemahasiswaan', '2026-12-31', 'berjalan', 40]], null],
            ['Belanja modal tertinggal dari trajektori', 'Realisasi TW II 86% trajektori.', 'tinjauan', 'q2', 'keuangan', 'sedang', null, 'F2.3', 'serap', 'dirut', '2026-07-15',
                [['Pemantauan mingguan kontrak & BAST', 'cfo', '2026-12-15', 'berjalan', 60]], null],
            ['Target L1.1 Kontrak Kinerja berbeda dengan PK 2026', 'Kontrak Kinerja 66%, Perjanjian Kinerja 2026 58%.', 'internal', 'rm8', 'sdm', 'sedang', null, 'L1.1', 'selaras', 'strategi', '2026-09-11',
                [['Usulan perubahan Kontrak Kinerja (Pasal 19)', 'strategi', '2026-09-29', 'selesai', 100]], null],
            ['Laporan capaian TW III berisiko terlambat', 'Realisasi TW III sebagian masih draf/dikembalikan; tenggat 21 Oktober 2026 (Pasal 10).', 'internal', 'rm9', 'proses', 'tinggi', null, 'C2.1', 'selaras', 'strategi', '2026-10-12',
                [['Hitung ulang lulusan bekerja sesuai Definisi Operasional', 'kemahasiswaan', '2026-10-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan perubahan Kontrak Kinerja oleh DJPb', 'eksternal', 'selaras', 'P3', 'Direktorat Jenderal Perbendaharaan', 'Perubahan target L1.1 berlaku setelah ditandatangani (paling lambat 1 Desember).', 'strategi', 'tinggi', 'PK 2026 & persetujuan Pembina Teknis dilampirkan.'],
                ['Kuota sertifikasi pendidik', 'eksternal', 'serdik', 'L1', 'Kementerian Agama', 'Jumlah dosen PPPK yang dapat mengikuti serdik bergantung kuota nasional.', 'sdm', 'sedang', 'Prioritaskan dosen dengan portofolio siap.'],
                ['Jadwal asesmen lapangan akreditasi', 'eksternal', 'akreditasi', 'P1', 'BAN-PT & LAM', 'Skor rata-rata akreditasi bergantung jadwal asesmen.', 'lpm', 'sedang', 'Koordinasi jadwal sejak awal tahun.'],
            ],
            'institutionalizations' => [
                ['Siklus laporan capaian Kontrak Kinerja di Kinera', 'sistem', 'Input realisasi tanggal 5 → verifikasi SPI/Keuangan tanggal 7 → persetujuan Rektor tanggal 8 → kirim ke DJPb & Pendis ≤ 15 hari kerja.', 'Triwulanan', 'strategi', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Penyelarasan PK & Kontrak Kinerja', 'sop', 'Setiap indikator PK yang juga ada di Kontrak Kinerja memakai target dan Definisi Operasional yang sama.', 'Tahunan', 'strategi', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Rapat Dewan Pengawas', 'rapat', 'Pengawasan capaian kinerja dan syarat insentif.', 'Triwulanan', 'dirut', 'melembaga'],
                ['Audit Mutu Internal (SPMI)', 'sop', 'Siklus PPEPP di seluruh unit.', 'Tahunan', 'lpm', 'melembaga'],
                ['Tracer study lulusan', 'sop', 'Survei lulusan untuk indikator C2.1 & C2.2.', 'Tahunan', 'kemahasiswaan', 'berjalan'],
            ],
        ];
    }
}
