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
 * Demo PT Bank Central Asia Tbk (kode BBCA) — bank swasta terbesar, unggul di perbankan transaksi.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (laba, NII, kredit per segmen, DPK & CASA, rasio CAR/NPL/LAR/ROA/ROE/NIM/CIR/LDR, transaksi digital, SDM).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI; target 2026 disusun dari panduan publik.
 * - Kasus belajar utama: menjaga keunggulan pemimpin pasar — rasio sudah sangat baik, tantangannya adalah
 *   tumbuh tanpa mengorbankan kualitas dan efisiensi (strategi "sustain & extend").
 */
class BcaSeeder extends ShowcaseSeeder
{
    public const CODE = 'BBCA';

    public const LT = 'Laporan Tahunan 2025 PT Bank Central Asia Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Bank Central Asia Tbk', 'legal_name' => 'PT Bank Central Asia Tbk',
            'industry' => 'Perbankan: transaksi, kredit korporasi, komersial, UKM & konsumer, layanan keuangan grup',
            'address' => 'Menara BCA, Grand Indonesia, Jl. M.H. Thamrin No. 1', 'city' => 'Jakarta Pusat',
            'phone' => '+62 21 2358 8000', 'website' => 'https://www.bca.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham BBCA). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Pendapatan bank relatif merata; transaksi memuncak menjelang Lebaran & akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.1, 7.7, 8.6, 8.4, 8.2, 8.2, 8.4, 8.3, 8.2, 8.5, 8.4, 9.0]),
            2026 => self::weights([1 => 8.1, 8.2, 8.5, 8.0, 8.2, 8.2, 8.4, 8.3, 8.2, 8.5, 8.4, 9.0]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'F2.2', 'F2.3', 'F3.1', 'C1.2', 'C1.3', 'C3.2', 'C4.1', 'C4.2', 'P2.1', 'P2.2', 'P4.1', 'L1.2', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C3.3', 'L1.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P2.3', 'Cost of credit September memakai CKPN sebelum penyesuaian model ECL kuartal III. Mohon perbarui dengan CKPN final dari Manajemen Risiko.'];
    }

    protected function criticalIn2026(): array
    {
        return ['C2.5'];
    }

    protected function maturityPattern(): array
    {
        return [4, 4, 3, 4, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin BCA (contoh)', 'bbca.admin', 'Admin Sistem', 'Strategi & Perencanaan', ['company-admin']],
            'dirut' => ['Presiden Direktur (contoh)', 'bbca.direktur', 'Presiden Direktur', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'bbca.keuangan', 'Direktur Keuangan & Perencanaan', 'Keuangan & Perencanaan', ['keuangan']],
            'korporasi' => ['Direktur Perbankan Korporasi (contoh)', 'bbca.korporasi', 'Direktur Perbankan Korporasi', 'Perbankan Korporasi', ['kepala-unit']],
            'ukm' => ['Direktur Perbankan Komersial & UKM (contoh)', 'bbca.ukm', 'Direktur Perbankan Komersial & UKM', 'Perbankan Komersial & UKM', ['kepala-unit']],
            'konsumer' => ['Direktur Perbankan Konsumer (contoh)', 'bbca.konsumer', 'Direktur Perbankan Konsumer', 'Perbankan Konsumer', ['kepala-unit']],
            'transaksi' => ['Direktur Perbankan Transaksi (contoh)', 'bbca.transaksi', 'Direktur Perbankan Transaksi', 'Perbankan Transaksi & Jaringan', ['kepala-unit']],
            'risiko' => ['Direktur Manajemen Risiko (contoh)', 'bbca.risiko', 'Direktur Manajemen Risiko', 'Manajemen Risiko', ['kepala-unit']],
            'ti' => ['Direktur Teknologi Informasi (contoh)', 'bbca.ti', 'Direktur Teknologi Informasi', 'Teknologi Informasi', ['kepala-unit']],
            'kepatuhan' => ['Direktur Kepatuhan (contoh)', 'bbca.kepatuhan', 'Direktur Kepatuhan', 'Kepatuhan & Hukum', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'bbca.sdm', 'Direktur Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Strategi & Perencanaan (contoh)', 'bbca.strategi', 'Kepala Divisi Strategi & Perencanaan', 'Strategi & Perencanaan', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'bbca.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'bbca.analis', 'Performance Analyst', 'Strategi & Perencanaan', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Bank pilihan utama andalan masyarakat, yang berperan sebagai pilar penting perekonomian Indonesia.',
            'mission' => "1. Membangun institusi yang unggul di bidang penyelesaian pembayaran dan solusi keuangan bagi nasabah bisnis dan perseorangan.\n2. Memahami beragam kebutuhan nasabah dan memberikan layanan finansial yang tepat demi tercapainya kepuasan optimal bagi nasabah.\n3. Meningkatkan nilai francais dan nilai stakeholder BCA.",
            'purpose' => 'Menjadi pilar perekonomian dengan layanan transaksi yang andal dan solusi keuangan yang tepat bagi masyarakat dan dunia usaha.',
            'tagline' => 'Senantiasa di Sisi Anda',
            'brand_promise' => 'Transaksi aman, cepat, dan selalu tersedia — di cabang, ATM, aplikasi, dan Halo BCA 24/7.',
            'value_proposition' => '34,3 juta nasabah; 37,5 miliar transaksi mobile & internet banking; 1.270 cabang, 20.163 ATM; CASA 83,7% dari DPK.',
            'culture_statement' => 'Tata nilai BCA: Fokus pada Nasabah, Integritas, Kerja Sama Tim, Berusaha Mencapai yang Terbaik.',
            'history' => "1957: mulai beroperasi.\n1975: menggunakan nama Bank Central Asia.\n1977: menjadi bank devisa.\n2000: tercatat di Bursa Efek (BBCA).\n2007–2010: KPR bunga tetap & kartu Flazz.\n2011–2014: perbankan syariah, pembiayaan motor, asuransi, sekuritas.\n2025: pergantian Presiden Direktur & Presiden Komisaris (efektif 1 Juni); laba bersih Rp57,6 T.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Fokus pada Nasabah', 'Customer focus', 'Nasabah adalah alasan kami ada.', "Menyelesaikan pengaduan dengan tuntas\nMerancang layanan dari sudut pandang nasabah"],
            ['Integritas', 'Integrity', 'Jujur, tulus, dan dapat dipercaya.', "Menolak gratifikasi\nMenjaga kerahasiaan data nasabah"],
            ['Kerja Sama Tim', 'Teamwork', 'Saling mendukung untuk hasil terbaik.', "Kolaborasi cabang–kantor pusat\nBerbagi pengetahuan"],
            ['Berusaha Mencapai yang Terbaik', 'Excellence', 'Terus memperbaiki diri dan layanan.', "Standar layanan tinggi\nBelajar dari kesalahan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Franchise perbankan transaksi', 'Kompetensi', 'Rasio CASA 83,7%; ekosistem pembayaran luas.', 5, 5, true, 'Pertahankan sebagai sumber dana murah.'],
            ['Jaringan layanan omnichannel', 'Sumber daya fisik', '1.270 cabang, 20.163 ATM, myBCA, Halo BCA 24/7.', 5, 5, true, 'New Branch Model hybrid.'],
            ['Manajemen risiko kredit', 'Sistem', 'NPL 1,7%; LAR 4,8%.', 4, 5, true, 'Diversifikasi sektor & early warning.'],
            ['Efisiensi biaya', 'Sistem', 'CIR 30,7%.', 4, 5, false, 'Otomasi & digitalisasi.'],
            ['Kecepatan proses kredit UKM & konsumer', 'Sistem', 'Proses kredit masih berlapis.', 3 + $lvl, 4, false, 'Simplifikasi proses kredit.'],
            ['Analitik data & AI', 'Kompetensi', 'Data transaksi masif belum dimanfaatkan penuh.', 3 + $lvl, 5, false, 'Model AI untuk risiko & penawaran.'],
            ['Sinergi grup', 'Sistem', 'BCA Finance, BCA Syariah, blu, sekuritas, asuransi.', 3, 4, false, 'Solusi komprehensif lintas entitas.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi ekonomi & perbankan 2025', '2026-01-20', 'eksternal', self::LT.' — Analisis & Pembahasan Manajemen',
                'Ekonomi tumbuh ±5%; suku bunga mulai turun; permintaan kredit korporasi kuat, kredit kendaraan melemah seiring penjualan mobil nasional turun; transaksi digital & QRIS tumbuh pesat.',
                'Kredit korporasi menjadi penopang; segmen konsumer butuh strategi khusus.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Laba bersih Rp57,6 T (+4,9%); kredit Rp992,9 T (+7,7%); DPK Rp1.249 T; CASA 83,7%; CAR 29,8%; NPL 1,7%; LAR 4,8%; ROA 3,9%; ROE 23,3%; NIM 5,7%; CIR 30,7%.',
                'Kinerja sangat sehat; tantangan menjaga pertumbuhan & imbal hasil pada level tinggi.'],
            ['Panduan 2026', '2026-02-05', 'internal', self::LT.' — Prospek Usaha',
                'Panduan 2026: kredit tumbuh 8–10%, CIR 31–33%, cost of credit 0,4–0,5%, ROA 3,5–3,7%, ROE 21,5–23,5%. Prioritas: franchise transaksi & CASA, kredit berkualitas & terdiversifikasi, solusi komprehensif bersama anak perusahaan.',
                'Target 2026 dalam demo ini disusun dari panduan tersebut.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Program pembangunan & hilirisasi', 'Kebutuhan pembiayaan korporasi & rantai pasok.', 'peluang', 4, 4, 'naik', 'Kredit korporasi terdiversifikasi.'],
            ['ekonomi', 'Penurunan suku bunga', 'Menekan NIM, mendorong permintaan kredit.', 'ancaman', 4, 4, 'naik', 'Jaga CASA & pendapatan non-bunga.'],
            ['ekonomi', 'Pasar otomotif melemah', 'Penjualan mobil turun; KKB −13,3%.', 'ancaman', 3, 4, 'stabil', 'Program KKB bersama dealer & BCA Finance.'],
            ['sosial', 'Digitalisasi & QRIS', 'Nasabah makin bertransaksi lewat ponsel.', 'peluang', 5, 5, 'naik', 'myBCA & ekosistem pembayaran.'],
            ['sosial', 'Penipuan & rekayasa sosial', 'Modus penipuan terhadap nasabah meningkat.', 'ancaman', 4, 5, 'naik', 'Edukasi & deteksi fraud real-time.'],
            ['teknologi', 'AI & analitik', 'Personalisasi & penilaian risiko lebih baik.', 'peluang', 4, 4, 'naik', 'Model AI di produksi.'],
            ['teknologi', 'Bank digital & fintech', 'Pesaing digital menawarkan bunga & kemudahan.', 'ancaman', 3, 4, 'naik', 'blu & myBCA.'],
            ['lingkungan', 'Keuangan berkelanjutan', 'Taksonomi hijau & pembiayaan transisi.', 'peluang', 3, 4, 'naik', 'Pembiayaan berkelanjutan Rp255 T.'],
            ['hukum', 'Ketahanan siber & PDP', 'Aturan OJK tentang ketahanan TI & UU PDP.', 'ancaman', 4, 4, 'naik', 'Program keamanan siber.'],
            ['hukum', 'Ketentuan permodalan & likuiditas', 'Basel III, LCR, NSFR.', 'ancaman', 2, 3, 'stabil', 'CAR 29,8%, LCR 310,8%.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Bank BUMN & bank swasta besar', 'Persaingan dana murah & kredit korporasi.', 4, 'Franchise transaksi & layanan.'],
            ['kekuatan_pembeli', 'Nasabah korporasi besar', 'Daya tawar tinggi untuk suku bunga.', 4, 'Solusi cash management menyeluruh.'],
            ['kekuatan_pemasok', 'Deposan & penyedia teknologi', 'Deposan besar sensitif bunga.', 3, 'Andalkan CASA, bukan deposito.'],
            ['pendatang_baru', 'Bank digital & fintech', 'Akuisisi nasabah murah lewat aplikasi.', 3, 'Inovasi digital & ekosistem.'],
            ['produk_pengganti', 'Dompet digital & paylater', 'Transaksi kecil beralih ke e-wallet.', 3, 'QRIS & integrasi e-wallet.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Korporasi', 'Kredit', 'Rp478,9 T (+11,5%).', 48, 'naik', true, 'Pembiayaan besar & cash management.', 'Diversifikasi sektor.'],
            ['Komersial & UKM', 'Kredit', 'Komersial Rp146,8 T (+8,5%); UKM Rp130,9 T (+5,7%).', 28, 'naik', true, 'Proses cepat & solusi transaksi.', 'Simplifikasi proses kredit.'],
            ['Konsumer (KPR, KKB, kartu kredit)', 'Kredit', 'Rp224,1 T (+0,2%); KKB −13,3%.', 23, 'stabil', true, 'Cicilan terjangkau & proses mudah.', 'KPR & program KKB bersama dealer.'],
            ['Nasabah transaksi (perorangan & bisnis)', 'Dana', '34,3 juta nasabah; CASA Rp1.045 T.', 0, 'naik', true, 'Transaksi andal 24/7.', 'myBCA, QRIS, cabang hybrid.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — portofolio kredit & dana']);
        }

        foreach ([
            ['PT Bank Central Asia Tbk', 'Bank swasta', 1, 112006, 5.1, 51.4, true, 'CASA tertinggi, efisiensi, kualitas aset.', 'Pertumbuhan konsumer melambat.'],
            ['Bank BUMN terbesar', 'Bank BUMN', 2, null, null, null, false, 'Jaringan & segmen mikro.', 'Biaya dana lebih tinggi.'],
            ['Bank swasta besar lain', 'Bank swasta', 3, null, null, null, false, 'Ekosistem grup.', 'CASA lebih rendah.'],
            ['Bank digital', 'Digital', 4, null, null, null, false, 'Pengalaman aplikasi & bunga tinggi.', 'Skala & profitabilitas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan operasional dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Kewajiban penyediaan modal minimum', 'OJK', 'POJK KPMM & Basel III', 'Permodalan', null, 'CAR minimum sesuai profil risiko + buffer.', 'Ekspansi kredit.', 'patuh', 'cfo', null],
            ['Likuiditas (LCR & NSFR)', 'OJK', 'POJK LCR & NSFR', 'Likuiditas', null, 'Rasio kecukupan likuiditas.', 'Stabilitas pendanaan.', 'patuh', 'risiko', null],
            ['Ketahanan & keamanan siber bank', 'OJK', 'POJK tentang ketahanan & keamanan siber bank umum', 'Teknologi', null, 'Tata kelola TI, pengujian, pelaporan insiden.', 'Kepercayaan nasabah.', 'patuh', 'ti', null],
            ['APU-PPT', 'OJK & PPATK', 'POJK APU-PPT', 'Kepatuhan', null, 'Pengenalan nasabah & transaksi mencurigakan.', 'Sanksi & reputasi.', 'patuh', 'kepatuhan', null],
            ['Pelindungan konsumen sektor jasa keuangan', 'OJK', 'POJK Pelindungan Konsumen', 'Konsumen', null, 'Penanganan pengaduan & transparansi produk.', 'Reputasi.', 'patuh', 'transaksi', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data 34 juta nasabah.', 'Sanksi & kepercayaan.', 'sebagian', 'ti', '2026-12-31'],
            ['Taksonomi untuk keuangan berkelanjutan', 'OJK', 'Taksonomi Keuangan Berkelanjutan Indonesia', 'Keberlanjutan', null, 'Klasifikasi pembiayaan hijau & transisi.', 'Pelaporan keberlanjutan.', 'sebagian', 'risiko', $year === 2025 ? null : '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham pengendali & publik', 'internal', 'Pemilik', 5, 5, 'ROE tinggi & dividen.', 'RUPS & paparan publik.', 'Investor Relations'],
            'nasabah' => ['Nasabah perorangan (34,3 juta)', 'eksternal', 'Nasabah', 4, 5, 'Transaksi andal, aman, mudah.', 'Halo BCA, survei, media sosial.', 'Customer Experience'],
            'bisnis' => ['Nasabah korporasi, komersial & UKM', 'eksternal', 'Nasabah', 5, 5, 'Kredit cepat & cash management.', 'Relationship manager.', 'Perbankan Bisnis'],
            'ojk' => ['OJK, Bank Indonesia & LPS', 'eksternal', 'Regulator', 5, 4, 'Kesehatan bank & pelindungan konsumen.', 'Pelaporan & pemeriksaan.', 'Kepatuhan'],
            'karyawan' => ['Karyawan (26.435 orang)', 'internal', 'SDM', 4, 5, 'Karier & kesejahteraan.', 'Survei team engagement.', 'Human Capital'],
            'anak_usaha' => ['Anak perusahaan (BCA Finance, Syariah, blu, sekuritas, asuransi)', 'internal', 'Grup', 3, 4, 'Sinergi nasabah & produk.', 'Komite sinergi grup.', 'Strategi & Perencanaan'],
            'masyarakat' => ['Masyarakat & Desa Bakti BCA', 'eksternal', 'Komunitas', 2, 4, 'Program sosial & literasi keuangan.', 'Bakti BCA.', 'CSR'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'nim' => ['Tekanan NIM saat suku bunga turun', 'NIM 5,7% (2024: 5,8%).', 'keuangan', 4, 4, 'cfo', ['pemegang_saham']],
            'konsumer' => ['Kredit konsumer stagnan', 'Konsumer +0,2%; KKB −13,3%.', 'pelanggan', 4, 4, 'konsumer', ['nasabah']],
            'fraud' => ['Penipuan terhadap nasabah digital', 'Rekayasa sosial & akun palsu meningkat.', 'proses', 5, 5, 'ti', ['nasabah', 'ojk']],
            'proses' => ['Proses kredit UKM & konsumer panjang', 'Simplifikasi proses kredit menjadi prioritas 2026.', 'proses', 3, 4, 'ukm', ['bisnis']],
            'kepemimpinan' => ['Transisi kepemimpinan', 'Pergantian Presiden Direktur & Presiden Komisaris (Juni 2025).', 'sdm', 3, 4, 'sdm', ['karyawan', 'pemegang_saham']],
            'digital' => ['Persaingan bank digital', 'Pesaing menawarkan pengalaman & bunga menarik.', 'pelanggan', 3, 3, 'transaksi', ['nasabah']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Franchise transaksi & CASA 83,7%', 'Dana murah & loyal.', 0.16, 4],
            ['S', 'Permodalan sangat kuat', 'CAR 29,8%; LCR 310,8%.', 0.10, 4],
            ['S', 'Kualitas aset', 'NPL 1,7%; LAR 4,8%.', 0.10, 4],
            ['S', 'Efisiensi', 'CIR 30,7%.', 0.08, 4],
            ['W', 'Pertumbuhan konsumer lambat', 'KKB turun 13,3%.', 0.08, 2],
            ['W', 'Proses kredit berlapis', 'Waktu proses UKM.', 0.06, 2],
            ['W', 'Ketergantungan pada pendapatan bunga', 'NII 76% pendapatan operasional.', 0.05, 2],
            ['O', 'Pembayaran digital & QRIS', 'Nilai QRIS 1,9x.', 0.10, 4],
            ['O', 'Keuangan berkelanjutan', 'Pembiayaan hijau +14,5%.', 0.05, 3],
            ['O', 'Sinergi anak perusahaan', 'Solusi komprehensif.', 0.05, 3],
            ['T', 'Penurunan suku bunga', 'Tekanan NIM.', 0.08, 3],
            ['T', 'Fraud & serangan siber', 'Kepercayaan nasabah.', 0.06, 3],
            ['T', 'Bank digital', 'Persaingan nasabah muda.', 0.03, 3],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Perkuat ekosistem pembayaran & QRIS', 'CASA tumbuh dari transaksi harian.', 5, ['nim', 'digital']],
            ['SO', 'Pembiayaan berkelanjutan & rantai pasok', 'Gunakan permodalan kuat untuk kredit hijau & rantai pasok.', 3, ['konsumer']],
            ['WO', 'Simplifikasi proses kredit digital', 'Keputusan kredit UKM & konsumer lebih cepat.', 4, ['proses', 'konsumer']],
            ['WO', 'Solusi komprehensif bersama anak perusahaan', 'Pendapatan non-bunga dari grup.', 3, ['nim']],
            ['ST', 'Deteksi fraud real-time & edukasi nasabah', 'Lindungi nasabah dari rekayasa sosial.', 5, ['fraud']],
            ['WT', 'Kepemimpinan & budaya di masa transisi', 'Kesinambungan nilai & kinerja.', 3, ['kepemimpinan']],
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
            'T1' => ['Franchise Transaksi & CASA', 'Bank transaksi utama nasabah; dana murah yang loyal.', '#005BAA'],
            'T2' => ['Kredit Berkualitas & Terdiversifikasi', 'Tumbuh 8–10% dengan NPL & cost of credit terjaga.', '#B22234'],
            'T3' => ['Solusi Komprehensif & Digital', 'Sinergi grup, myBCA, AI, pembiayaan berkelanjutan.', '#E08A1E'],
            'T4' => ['Insan BCA & Tata Kelola', 'Talenta, budaya, kepatuhan, keamanan.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'CASA sebagai jangkar pendanaan', 'saluran', 'Biaya dana rendah menopang NIM saat bunga turun.'],
            ['T2', 'Diversifikasi sektor & simplifikasi proses kredit', 'operasi', 'Prioritas 2026 dalam laporan tahunan.'],
            ['T3', 'Satu nasabah, banyak produk grup', 'produk', 'Solusi komprehensif bersama anak perusahaan.'],
            ['T4', 'Cabang hybrid (New Branch Model)', 'saluran', 'Cabang untuk konsultasi, transaksi rutin ke digital.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Laba tumbuh berkelanjutan', 'Laba bersih Rp61 T; pendapatan non-bunga naik.', 'dirut', 3],
            'F2' => ['keuangan', 'T1', 'Imbal hasil & efisiensi terjaga', 'ROE 21,5–23,5%; CIR 31–33%.', 'cfo', 2],
            'F3' => ['keuangan', 'T2', 'Permodalan & likuiditas kuat', 'CAR & LDR pada kisaran sehat.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T1', 'Bank transaksi utama nasabah', 'CASA, nasabah, & transaksi digital naik.', 'transaksi', 2],
            'C2' => ['pelanggan', 'T2', 'Kredit tumbuh di semua segmen', 'Kredit +9%; konsumer pulih.', 'korporasi', 2],
            'C3' => ['pelanggan', 'T3', 'Solusi grup & keuangan berkelanjutan', 'Pembiayaan berkelanjutan & laba anak usaha naik.', 'strategi', 1],
            'C4' => ['pelanggan', 'T1', 'Layanan prima', 'Kepuasan tinggi; pengaduan selesai tepat waktu.', 'transaksi', 1.5],
            'P1' => ['proses', 'T2', 'Proses kredit sederhana & cepat', 'Waktu persetujuan turun; pengajuan digital naik.', 'ukm', 1.5],
            'P2' => ['proses', 'T2', 'Manajemen risiko kredit unggul', 'NPL, LAR, cost of credit terkendali.', 'risiko', 2],
            'P3' => ['proses', 'T3', 'Layanan digital andal & aman', 'Ketersediaan tinggi; fraud ditangani cepat.', 'ti', 2],
            'P4' => ['proses', 'T4', 'Kepatuhan & tata kelola', 'Temuan audit tuntas; pelatihan APU-PPT.', 'kepatuhan', 1],
            'L1' => ['sdm', 'T4', 'Insan BCA kompeten & produktif', 'Pelatihan, retensi, produktivitas.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya & keterikatan', 'Team engagement tinggi; KPI terhubung.', 'sdm', 1],
            'L3' => ['sdm', 'T3', 'Data & AI', 'Model AI di produksi; transaksi digital.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'C4', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'P2', 2], ['L3', 'P3', 2],
            ['P1', 'C2', 3], ['P2', 'F1', 2], ['P2', 'F3', 2], ['P3', 'C1', 3], ['P3', 'C4', 2], ['P4', 'P2', 1],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'F1', 2], ['C4', 'C1', 2],
            ['F1', 'F2', 3], ['F3', 'F2', 2],
        ] as [$s, $t, $str]) {
            ObjectiveRelation::create(['source_objective_id' => $obj[$s]->id, 'target_objective_id' => $obj[$t]->id, 'strength' => $str]);
        }

        return [$themes, $obj];
    }

    // ------------------------------------------------------------------ KPI

    protected function kpiDefs(): array
    {
        $lt = true;
        $il = false;

        return [
            ['F1.1', 'F1', 'Laba bersih', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan konsolidasian', 'Laporan keuangan konsolidasian', 54851, 57000, 57563, 61000, .99, $lt],
            ['F1.2', 'F1', 'Pendapatan bunga bersih', 'Rp miliar', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan bunga − beban bunga', 'Laporan laba rugi', 82264, 86000, 85548, 90000, .98, $lt],
            ['F1.3', 'F1', 'Pendapatan operasional selain bunga', 'Rp miliar', 'higher', 'sum', 0, true, false, 'transaksi', 'Komisi, provisi, dan pendapatan non-bunga lain', 'Laporan laba rugi', 22932, 24500, 26313, 28500, 1.0, $lt],
            ['F2.1', 'F2', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'average', 1, true, true, 'cfo', 'Laba ÷ rata-rata ekuitas × 100', 'Rasio keuangan', 24.6, 23.5, 23.3, 22.5, .99, $lt],
            ['F2.2', 'F2', 'Cost to income ratio (CIR)', '%', 'lower', 'average', 1, true, false, 'cfo', 'Beban operasional ÷ pendapatan operasional × 100', 'Rasio keuangan', 31.3, 31.5, 30.7, 32.0, 1.0, $lt],
            ['F2.3', 'F2', 'Imbal hasil aset (ROA)', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba sebelum pajak ÷ rata-rata aset × 100', 'Rasio keuangan', 3.9, 3.8, 3.9, 3.6, 1.0, $lt],
            ['F2.4', 'F2', 'Marjin bunga bersih (NIM)', '%', 'higher', 'average', 1, true, false, 'cfo', 'Pendapatan bunga bersih ÷ rata-rata aset produktif × 100', 'Rasio keuangan', 5.8, 5.7, 5.7, 5.5, .98, $lt],
            ['F3.1', 'F3', 'Rasio kecukupan modal (CAR)', '%', 'higher', 'last', 1, true, true, 'cfo', 'Modal ÷ ATMR × 100', 'Laporan KPMM', 29.4, 26.0, 29.8, 27.0, 1.0, $lt],
            ['F3.2', 'F3', 'Loan to deposit ratio (LDR)', '%', 'higher', 'last', 1, true, false, 'cfo', 'Kredit ÷ dana pihak ketiga × 100', 'Rasio keuangan', 78.4, 80.0, 76.8, 80.0, .97, $lt],
            ['C1.1', 'C1', 'Dana giro & tabungan (CASA)', 'Rp miliar', 'higher', 'last', 0, true, true, 'transaksi', 'Saldo giro + tabungan', 'Neraca', 923977, 1000000, 1045239, 1120000, .97, $lt],
            ['C1.2', 'C1', 'Rasio CASA', '%', 'higher', 'last', 1, true, false, 'transaksi', 'CASA ÷ DPK × 100', 'Rasio keuangan', 81.5, 82.0, 83.7, 84.0, 1.0, $lt],
            ['C1.3', 'C1', 'Jumlah nasabah', 'juta', 'higher', 'last', 1, false, false, 'transaksi', 'Nasabah perorangan & bisnis', 'Data nasabah', 33.1, 34.0, 34.3, 35.5, .99, $lt],
            ['C1.4', 'C1', 'Transaksi mobile & internet banking', 'juta', 'higher', 'sum', 0, false, false, 'transaksi', 'Frekuensi transaksi myBCA, BCA mobile, KlikBCA (juta transaksi)', 'Data transaksi', 31565, 36000, 37500, 43000, .97, $lt],
            ['C2.1', 'C2', 'Total kredit', 'Rp miliar', 'higher', 'last', 0, true, true, 'korporasi', 'Saldo kredit bruto (panduan 2026: tumbuh 8–10%)', 'Neraca', 921878, 1000000, 992901, 1082000, .97, $lt],
            ['C2.2', 'C2', 'Kredit korporasi', 'Rp miliar', 'higher', 'last', 0, true, false, 'korporasi', 'Saldo kredit segmen korporasi', 'Laporan segmen', 429500, 470000, 478900, 520000, .98, $lt],
            ['C2.3', 'C2', 'Kredit komersial & UKM', 'Rp miliar', 'higher', 'last', 0, true, false, 'ukm', 'Saldo kredit komersial + UKM', 'Laporan segmen', 259100, 275000, 277700, 300000, .96, $lt],
            ['C2.4', 'C2', 'Kredit konsumer', 'Rp miliar', 'higher', 'last', 0, true, false, 'konsumer', 'KPR + KKB + kartu kredit & personal loan', 'Laporan segmen', 223650, 235000, 224100, 232000, .96, $lt],
            ['C2.5', 'C2', 'Kredit kendaraan bermotor (KKB)', 'Rp miliar', 'higher', 'last', 0, true, false, 'konsumer', 'Saldo KKB (termasuk anak usaha)', 'Laporan segmen', 65300, 66000, 56600, 58000, .78, $lt],
            ['C3.1', 'C3', 'Pembiayaan berkelanjutan', 'Rp miliar', 'higher', 'last', 0, true, false, 'risiko', 'Portofolio kegiatan usaha berkelanjutan', 'Laporan keberlanjutan', 228700, 245000, 255400, 280000, .98, $lt],
            ['C3.2', 'C3', 'Porsi pembiayaan berkelanjutan dalam portofolio', '%', 'higher', 'last', 1, false, false, 'risiko', 'Pembiayaan berkelanjutan ÷ total portofolio × 100', 'Laporan keberlanjutan', 24.8, 25.0, 25.8, 26.5, .99, $lt],
            ['C3.3', 'C3', 'Laba anak perusahaan', 'Rp miliar', 'higher', 'sum', 0, true, false, 'strategi', 'Kontribusi laba seluruh anak perusahaan', 'Laporan anak usaha (ilustrasi)', 2600, 2900, 3000, 3400, .9, $il],
            ['C4.1', 'C4', 'Indeks kualitas layanan (BSQ)', 'skor', 'higher', 'average', 2, false, false, 'transaksi', 'Survei kualitas layanan (skala 5)', 'Survei layanan', 4.85, 4.86, 4.88, 4.90, .999, $lt],
            ['C4.2', 'C4', 'Pengaduan diselesaikan', '%', 'higher', 'average', 1, false, false, 'transaksi', 'Pengaduan selesai ÷ pengaduan diterima × 100', 'Halo BCA', 97.0, 97.5, 97.7, 98.0, .995, $lt],
            ['P1.1', 'P1', 'Waktu proses persetujuan kredit UKM', 'hari', 'lower', 'average', 1, false, false, 'ukm', 'Rata-rata hari kerja dari berkas lengkap sampai keputusan', 'Sistem kredit', 10.0, 8.0, 8.5, 6.0, 1.15, $il],
            ['P1.2', 'P1', 'Pengajuan kredit konsumer secara digital', '%', 'higher', 'average', 0, false, false, 'konsumer', 'Pengajuan KPR/KKB/kartu via aplikasi ÷ total × 100', 'Sistem kredit', 45, 55, 57, 65, .95, $il],
            ['P2.1', 'P2', 'Kredit bermasalah (NPL gross)', '%', 'lower', 'last', 2, true, true, 'risiko', 'Kredit kolektibilitas 3–5 ÷ total kredit × 100', 'Laporan kualitas aset', 1.80, 1.90, 1.70, 1.80, .97, $lt],
            ['P2.2', 'P2', 'Loan at risk (LAR)', '%', 'lower', 'last', 1, true, false, 'risiko', '(Kol 2 + restrukturisasi lancar + NPL) ÷ total kredit × 100', 'Laporan kualitas aset', 5.3, 5.0, 4.8, 4.6, 1.0, $lt],
            ['P2.3', 'P2', 'Cost of credit', '%', 'lower', 'average', 2, true, false, 'risiko', 'Beban CKPN ÷ rata-rata kredit × 100 (panduan 2026: 0,4–0,5%)', 'Laporan keuangan', 0.22, 0.40, 0.42, 0.45, 1.05, $lt],
            ['P3.1', 'P3', 'Ketersediaan layanan digital', '%', 'higher', 'average', 2, false, false, 'ti', 'Waktu layanan myBCA/KlikBCA tersedia ÷ total waktu × 100', 'Pemantauan TI', 99.90, 99.95, 99.94, 99.95, .9995, $il],
            ['P3.2', 'P3', 'Laporan fraud nasabah ditangani < 24 jam', '%', 'higher', 'average', 0, false, false, 'ti', 'Laporan fraud yang diblokir/ditindak < 24 jam ÷ total × 100', 'Fraud Management', 85, 90, 91, 95, .97, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'kepatuhan', 'Temuan SKAI/eksternal/OJK selesai sesuai tenggat ÷ jatuh tempo × 100', 'SKAI', 85, 92, 93, 95, .98, $il],
            ['P4.2', 'P4', 'Karyawan lulus pelatihan APU-PPT', '%', 'higher', 'last', 0, false, false, 'kepatuhan', 'Karyawan lulus e-learning APU-PPT ÷ wajib × 100', 'Learning management system', 95, 100, 99, 100, .99, $il],
            ['L1.1', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 0, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'BCA Learning Institute', 55, 58, 60, 62, .93, $il],
            ['L1.2', 'L1', 'Turnover karyawan', '%', 'lower', 'last', 1, false, false, 'sdm', 'Karyawan keluar ÷ rata-rata karyawan × 100', 'HRIS', 3.6, 4.0, 3.4, 4.0, .97, $lt],
            ['L1.3', 'L1', 'Laba bersih per karyawan', 'Rp miliar', 'higher', 'sum', 2, false, false, 'sdm', 'Laba bersih ÷ jumlah karyawan', 'HRIS & keuangan', 2.07, 2.15, 2.18, 2.30, .97, $lt],
            ['L2.1', 'L2', 'Team engagement', 'skor', 'higher', 'last', 2, false, false, 'sdm', 'Survei keterikatan tim (skala 5)', 'Survei karyawan', 4.65, 4.65, 4.67, 4.70, 1.0, $lt, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 70, 85, 86, 95, .9, $il],
            ['L3.1', 'L3', 'Model AI & analitik di produksi', 'unit', 'higher', 'sum', 0, false, false, 'ti', 'Model baru yang dipakai dalam keputusan bisnis', 'Data & AI Office', 10, 15, 16, 24, .82, $il],
            ['L3.2', 'L3', 'Porsi transaksi melalui kanal digital', '%', 'higher', 'average', 1, false, false, 'transaksi', 'Transaksi mobile, internet & QRIS ÷ total transaksi × 100', 'Data transaksi', 98.0, 98.3, 98.5, 98.7, 1.0, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Menara BCA, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, Strategi & Perencanaan',
                'summary' => "Laba bersih Rp57,6 T (+4,9%; 101% target).\nKredit Rp992,9 T (+7,7%); korporasi +11,5%, konsumer +0,2%, KKB −13,3%.\nCASA 83,7%; CAR 29,8%; NPL 1,7%; LAR 4,8%.\nROE 23,3%; NIM 5,7%; CIR 30,7%.\n37,5 miliar transaksi mobile & internet banking (+18,8%).",
                'decisions' => "1. Target 2026 mengikuti panduan publik: kredit +9%, CIR ≤ 32%, cost of credit ≤ 0,45%, ROE 22,5%.\n2. Prioritas: franchise transaksi, simplifikasi kredit, solusi grup.\n3. KPI korporat diturunkan ke divisi & cabang melalui Ruci BSC.",
                'follow_up' => 'Strategi & Perencanaan menurunkan KPI 2026 ke setiap divisi paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta strategi khusus kredit konsumer, khususnya KKB, tanpa melonggarkan standar kredit.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, Strategi & Perencanaan',
                'summary' => match ($m) {
                    2 => 'Penarikan tunai & transaksi menjelang Lebaran memuncak; layanan stabil.',
                    3 => 'Lebaran (20 Maret): 1,2 miliar transaksi digital dalam sepekan.',
                    5 => 'KKB masih turun; penjualan mobil nasional lemah.',
                    6 => 'Semester I: laba 99% target; kredit +4,1% ytd; NIM 5,5%.',
                    7 => 'Modus penipuan melalui tautan palsu meningkat.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Kesiapan ATM & kas menjelang Lebaran.',
                    3 => 'Pertahankan kapasitas server puncak sampai akhir April.',
                    5 => 'Program KKB bunga khusus bersama dealer & BCA Finance.',
                    6 => 'Percepat simplifikasi kredit UKM.',
                    7 => 'Kampanye edukasi anti penipuan & pemblokiran otomatis.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Strategi & Perencanaan',
                'summary' => $q === 1 ? 'Laba Q1 sesuai target; CASA 84%; KKB −3% ytd.' : 'Laba H1 99% target; KKB 82% dari rencana; waktu proses kredit UKM 8,9 hari.',
                'decisions' => $q === 1 ? 'Tambah tim analis kredit UKM di wilayah Jawa Timur.' : 'KKB menjadi KPI kritis; laporan mingguan ke Direksi.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti KKB yang terus turun, waktu proses kredit UKM, dan modus penipuan baru.',
            'decisions' => 'Direksi melaporkan KKB, waktu proses kredit, dan laporan fraud setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon analisis apakah penurunan KKB karena pasar atau karena daya saing produk kita, dibandingkan bank lain.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, Strategi & Perencanaan',
            'summary' => 'Realisasi September sebagian masih draf; cost of credit dikembalikan menunggu CKPN final.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; tidak diungkap per program di laporan tahunan.';

            return [
                'defs' => [
                    'mybca' => ['BBCA-25-01', 'Pengembangan myBCA & BCA mobile', 'Fitur baru, QRIS, dan keamanan aplikasi.', 'T3', 'transaksi', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 3, ['C1', 'L3'], '37,5 miliar transaksi (+18,8%).'],
                    'cabang' => ['BBCA-25-02', 'New Branch Model (cabang hybrid)', 'Cabang untuk konsultasi; transaksi rutin ke mesin & digital.', 'T1', 'transaksi', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 3, ['C4', 'F2'], 'CIR 30,7%.'],
                    'hijau' => ['BBCA-25-03', 'Pembiayaan berkelanjutan', 'Kredit hijau & transisi; sustainability-linked loan.', 'T3', 'risiko', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['C3'], 'Pembiayaan berkelanjutan Rp255,4 T.'],
                    'transisi' => ['BBCA-25-04', 'Transisi kepemimpinan', 'Pergantian Presiden Direktur & Presiden Komisaris efektif 1 Juni 2025.', 'T4', 'sdm', '2025-01-01', '2025-06-30', 'selesai', 4, 4, 2, 1, ['L2'], 'Transisi berjalan mulus.'],
                    'korporasi' => ['BBCA-25-05', 'Diversifikasi kredit korporasi', 'Sektor baru & rantai pasok nasabah korporasi.', 'T2', 'korporasi', '2025-01-01', '2025-12-31', 'selesai', 4, 3, 3, 2, ['C2'], 'Kredit korporasi +11,5%.'],
                ],
                'activities' => [
                    ['mybca', 'Rilis fitur myBCA', 'ti', '2025-01-01', '2025-12-31', 100], ['mybca', 'Penguatan keamanan aplikasi', 'ti', '2025-03-01', '2025-12-31', 100],
                    ['cabang', 'Renovasi cabang hybrid', 'transaksi', '2025-01-01', '2025-12-31', 100],
                    ['hijau', 'Kerangka pembiayaan berkelanjutan', 'risiko', '2025-01-01', '2025-06-30', 100],
                    ['transisi', 'Serah terima kepemimpinan', 'sdm', '2025-01-01', '2025-06-30', 100],
                    ['korporasi', 'Program rantai pasok nasabah korporasi', 'korporasi', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['transisi', 'RUPS menyetujui susunan baru', '2025-06-01', true], ['mybca', 'Rilis besar myBCA', '2025-09-30', true], ['cabang', '100 cabang hybrid', '2025-12-31', true],
                ],
                'budgets' => [
                    ['mybca', 'Pengembangan aplikasi & keamanan', 'capex', 600_000_000_000, 565_000_000_000, $note, 'Kas internal'],
                    ['cabang', 'Renovasi cabang hybrid', 'capex', 350_000_000_000, 322_000_000_000, $note, 'Kas internal'],
                    ['hijau', 'Kerangka & sertifikasi', 'opex', 15_000_000_000, 13_500_000_000, $note],
                    ['transisi', 'Program transisi & komunikasi', 'opex', 5_000_000_000, 4_400_000_000, $note],
                    ['korporasi', 'Solusi rantai pasok', 'opex', 40_000_000_000, 36_000_000_000, $note],
                ],
            ];
        }

        return [
            'defs' => [
                'casa' => ['BBCA-26-01', 'Ekosistem pembayaran & CASA', 'QRIS, cash management, payroll, dan merchant.', 'T1', 'transaksi', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 2, 2, ['C1', 'F1'], 'CASA Rp1.120 T.'],
                'kredit_ukm' => ['BBCA-26-02', 'Simplifikasi proses kredit UKM', 'Skor otomatis, dokumen digital, keputusan cepat.', 'T2', 'ukm', '2026-01-15', '2026-12-31', 'berjalan', 4, 5, 3, 2, ['P1', 'C2'], 'Waktu proses 6 hari.'],
                'kkb' => ['BBCA-26-03', 'Program KKB bersama dealer & BCA Finance', 'Bunga khusus, proses di dealer, dan bundling asuransi.', 'T2', 'konsumer', '2026-05-01', '2026-12-31', 'berjalan', 4, 5, 3, 2, ['C2'], 'KKB Rp58 T.'],
                'kpr' => ['BBCA-26-04', 'KPR digital', 'Pengajuan & persetujuan prinsip melalui aplikasi.', 'T2', 'konsumer', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['P1', 'C2'], '65% pengajuan digital.'],
                'fraud' => ['BBCA-26-05', 'Deteksi fraud real-time & edukasi nasabah', 'Analitik perilaku, pemblokiran otomatis, kampanye edukasi.', 'T3', 'ti', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['P3'], '95% laporan fraud ditangani < 24 jam.'],
                'ai' => ['BBCA-26-06', 'Program data & AI', 'Model risiko, penawaran personal, dan otomasi operasional.', 'T3', 'ti', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 3, 3, ['L3'], '24 model di produksi.'],
                'grup' => ['BBCA-26-07', 'Solusi komprehensif grup', 'Referral lintas anak perusahaan & satu tampilan nasabah.', 'T3', 'strategi', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['C3'], 'Laba anak usaha Rp3,4 T.'],
                'hijau' => ['BBCA-26-08', 'Pembiayaan transisi & hijau', 'Taksonomi, produk transisi, dan pelaporan dampak.', 'T3', 'risiko', '2026-01-01', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['C3'], 'Porsi berkelanjutan 26,5%.'],
                'cabang' => ['BBCA-26-09', 'Cabang hybrid gelombang 2', 'Konversi cabang & penataan jaringan.', 'T1', 'transaksi', '2026-01-01', '2026-12-31', 'berjalan', 3, 3, 2, 3, ['C4', 'F2'], 'CIR ≤ 32%.'],
                'talenta' => ['BBCA-26-10', 'Talenta digital & KPI berjenjang', 'Akademi data, rotasi, dan KPI turunan Peta Strategi.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 1, ['L1', 'L2'], '95% KPI terhubung.'],
            ],
            'activities' => [
                ['casa', 'Akuisisi merchant QRIS', 'transaksi', '2026-01-01', '2026-12-31', 75], ['casa', 'Payroll korporasi baru', 'korporasi', '2026-01-01', '2026-12-31', 70],
                ['kredit_ukm', 'Model skor UKM otomatis', 'risiko', '2026-01-15', '2026-05-31', 100], ['kredit_ukm', 'Dokumen digital & tanda tangan elektronik', 'ukm', '2026-04-01', '2026-12-31', 55],
                ['kkb', 'Kerja sama dealer & bunga khusus', 'konsumer', '2026-05-01', '2026-08-31', 100], ['kkb', 'Proses kredit di dealer', 'konsumer', '2026-07-01', '2026-12-31', 35],
                ['kpr', 'Persetujuan prinsip KPR di aplikasi', 'konsumer', '2026-01-15', '2026-07-31', 100],
                ['fraud', 'Analitik perilaku transaksi', 'ti', '2026-01-01', '2026-07-31', 100], ['fraud', 'Kampanye edukasi anti penipuan', 'transaksi', '2026-03-01', '2026-12-31', 70],
                ['ai', 'Model risiko & penawaran personal', 'ti', '2026-01-15', '2026-12-31', 65],
                ['grup', 'Satu tampilan nasabah grup', 'strategi', '2026-02-01', '2026-12-31', 50],
                ['hijau', 'Produk pembiayaan transisi', 'risiko', '2026-01-01', '2026-09-30', 80],
                ['cabang', 'Konversi 120 cabang', 'transaksi', '2026-01-01', '2026-12-31', 70],
                ['talenta', 'Akademi data & AI', 'sdm', '2026-01-15', '2026-12-31', 60], ['talenta', 'KPI individu turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100],
            ],
            'milestones' => [
                ['kredit_ukm', 'Model skor UKM live', '2026-05-31', true], ['kkb', 'Program KKB diluncurkan', '2026-08-31', true], ['fraud', 'Pemblokiran otomatis live', '2026-07-31', true],
                ['kpr', 'KPR digital live', '2026-07-31', true], ['grup', 'Satu tampilan nasabah grup', '2026-10-31', false], ['talenta', 'KPI individu disetujui', '2026-04-30', true],
            ],
            'budgets' => [
                ['casa', 'Akuisisi merchant & payroll', 'opex', 250_000_000_000, 178_000_000_000, null],
                ['kredit_ukm', 'Sistem kredit UKM', 'capex', 120_000_000_000, 82_000_000_000, null, 'Kas internal'],
                ['kkb', 'Subsidi bunga & kerja sama dealer', 'opex', 180_000_000_000, 64_000_000_000, null],
                ['kpr', 'Pengembangan KPR digital', 'capex', 45_000_000_000, 41_000_000_000, null, 'Kas internal'],
                ['fraud', 'Analitik fraud & edukasi', 'capex', 160_000_000_000, 118_000_000_000, null, 'Kas internal'],
                ['ai', 'Platform data & AI', 'capex', 220_000_000_000, 141_000_000_000, null, 'Kas internal'],
                ['grup', 'Integrasi data grup', 'capex', 60_000_000_000, 27_000_000_000, null, 'Kas internal'],
                ['hijau', 'Kerangka transisi & pelaporan', 'opex', 12_000_000_000, 8_600_000_000, null],
                ['cabang', 'Konversi cabang hybrid', 'capex', 380_000_000_000, 251_000_000_000, null, 'Kas internal'],
                ['talenta', 'Akademi data & program KPI', 'opex', 35_000_000_000, 22_000_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['KKB turun 13,3%', 'Saldo KKB Rp56,6 T vs target Rp66 T; penjualan mobil nasional turun & persaingan bunga.', 'tinjauan', 'tahunan', 'pelanggan', 'tinggi', null, 'C2.5', null, 'komisaris', '2026-02-24',
                [['Program KKB bersama dealer & BCA Finance', 'konsumer', '2026-12-31', 'berjalan', 60]], null],
            ['Kredit di bawah target', 'Kredit Rp992,9 T vs target Rp1.000 T; konsumer stagnan.', 'tinjauan', 'tahunan', 'pelanggan', 'sedang', null, 'C2.1', 'korporasi', 'korporasi', '2026-02-24',
                [['Rencana kredit 2026 per segmen', 'strategi', '2026-01-31', 'selesai', 100]], ['2026-02-28', 'Rencana kredit 2026 disetujui.']],
        ] : [
            ['KKB terus menurun', 'KKB 82% dari rencana per Juni; program dealer baru berjalan Agustus.', 'timwas', 'boc', 'pelanggan', 'kritis', null, 'C2.5', 'kkb', 'komisaris', '2026-07-28',
                [['Program KKB bunga khusus', 'konsumer', '2026-08-31', 'selesai', 100], ['Proses kredit di dealer', 'konsumer', '2026-12-31', 'berjalan', 35]], null],
            ['Waktu proses kredit UKM belum turun', '8,9 hari vs target 6 hari; tanda tangan elektronik belum diterima semua notaris.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P1.1', 'kredit_ukm', 'ukm', '2026-07-22',
                [['Kerja sama notaris rekanan untuk dokumen elektronik', 'ukm', '2026-10-31', 'berjalan', 60]], null],
            ['Modus penipuan tautan palsu meningkat', 'Laporan fraud naik 30% di Juli.', 'pelanggan', 'rm7', 'proses', 'tinggi', null, 'P3.2', 'fraud', 'ti', '2026-08-12',
                [['Pemblokiran otomatis tautan & rekening tujuan', 'ti', '2026-09-30', 'selesai', 100], ['Kampanye edukasi nasional', 'transaksi', '2026-12-31', 'berjalan', 70]], null],
            ['Model AI tertinggal', '11 model di produksi vs rencana 16 per Agustus; antrean validasi model risiko.', 'internal', 'rm8', 'sdm', 'sedang', null, 'L3.1', 'ai', 'ti', '2026-09-12',
                [['Tambah validator model independen', 'risiko', '2026-11-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Penjualan mobil nasional', 'eksternal', 'kkb', 'C2', 'Pasar otomotif & dealer', 'KKB bergantung pada penjualan mobil.', 'konsumer', 'tinggi', 'Diversifikasi ke mobil bekas & refinancing.'],
                ['Penerimaan dokumen elektronik', 'eksternal', 'kredit_ukm', 'P1', 'Notaris & instansi pertanahan', 'Proses agunan butuh dukungan pihak ketiga.', 'ukm', 'sedang', 'Notaris rekanan & integrasi layanan elektronik.'],
                ['Kerja sama operator telekomunikasi & platform', 'eksternal', 'fraud', 'P3', 'Operator & platform pesan', 'Pemblokiran tautan & nomor penipu.', 'ti', 'sedang', 'Nota kesepahaman & pertukaran data ancaman.'],
                ['Integrasi data anak perusahaan', 'internal', 'grup', 'C3', 'Anak perusahaan', 'Satu tampilan nasabah butuh data lintas entitas.', 'strategi', 'sedang', 'Standar data grup.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['ALCO (Asset Liability Committee)', 'rapat', 'Suku bunga, likuiditas, dan struktur neraca.', 'Mingguan', 'cfo', 'melembaga'],
                ['Komite kredit', 'rapat', 'Persetujuan & pemantauan kredit.', 'Mingguan', 'risiko', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan kepatuhan.', 'Kuartalan', 'dirut', 'melembaga'],
                ['Penanganan pengaduan nasabah', 'sop', 'SLA pengaduan & analisis akar masalah bulanan.', 'Bulanan', 'transaksi', 'melembaga'],
                ['Tata nilai BCA', 'budaya', 'Fokus pada nasabah, integritas, kerja sama tim, mencapai yang terbaik.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
