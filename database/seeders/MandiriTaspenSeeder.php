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
 * Demo PT Bank Mandiri Taspen (Bank Mantap, kode emiten obligasi BMTP) — bank fokus pensiunan & UMKM,
 * anak usaha Bank Mandiri dan PT Taspen.
 *
 * - Perioda 2025 (ditutup): realisasi DAN target RBB 2025 secara garis besar mengacu pada Laporan Tahunan 2025
 *   (aset, DPK per jenis, kredit per produk, laba, rasio CAR/NPL/ROA/ROE/NIM/BOPO/CIR/CASA, jaringan, SDM).
 * - Perioda 2026 (berjalan): target keuangan utama mengikuti proyeksi RBB 2026 dalam laporan tahunan;
 *   realisasi Jan–Sep 2026 dan indikator operasional SIMULASI.
 * - Kasus belajar utama: bank sangat sehat tetapi bergantung pada satu segmen (kredit pensiun ±99% portofolio)
 *   dan dana mahal (CASA ±23%) — strategi diversifikasi yang hati-hati.
 */
class MandiriTaspenSeeder extends ShowcaseSeeder
{
    public const CODE = 'BMTP';

    public const LT = 'Laporan Tahunan 2025 PT Bank Mandiri Taspen';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Bank Mandiri Taspen', 'legal_name' => 'PT Bank Mandiri Taspen',
            'industry' => 'Perbankan fokus pensiunan & UMKM (kredit pensiun, tabungan pensiun, mikro)',
            'address' => 'Graha Mantap, Jl. Proklamasi No. 31, Menteng', 'city' => 'Jakarta Pusat',
            'phone' => 'Mantap Call 14024', 'website' => 'https://www.bankmandiritaspen.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (Bank Mantap, kode emiten obligasi BMTP). Angka 2025 dan target keuangan utama 2026 secara garis besar mengacu pada Laporan Tahunan 2025; rincian bulanan, realisasi 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi & target RBB 2025 mengacu pada Laporan Tahunan 2025; rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: target utama dari proyeksi RBB 2026; Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Penyaluran kredit pensiun memuncak saat pembayaran gaji ke-13/THR dan menjelang akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 7.8, 7.8, 8.8, 8.4, 8.0, 8.6, 8.2, 8.2, 8.2, 8.4, 8.5, 9.1]),
            2026 => self::weights([1 => 7.8, 8.6, 8.8, 8.0, 8.0, 8.6, 8.2, 8.2, 8.2, 8.4, 8.4, 9.0]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'F2.2', 'F3.1', 'F3.3', 'C1.1', 'C1.2', 'C2.3', 'P1.1', 'P3.2', 'P4.1', 'L1.1', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.1', 'L1.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P1.2', 'NPL mikro September belum memasukkan hasil restrukturisasi debitur Bali yang disetujui komite 28 September. Mohon perbarui dari laporan kolektibilitas final.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P1.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 3, 4, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Bank Mantap (contoh)', 'bmtp.admin', 'Admin Sistem', 'Perencanaan Strategis', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'bmtp.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan & Strategi (contoh)', 'bmtp.keuangan', 'Direktur Keuangan & Strategi', 'Keuangan & Strategi', ['keuangan']],
            'bisnis' => ['Direktur Bisnis (contoh)', 'bmtp.bisnis', 'Direktur Bisnis', 'Bisnis Pensiun & UMKM', ['kepala-unit']],
            'dana' => ['Kepala Divisi Funding & Jaringan (contoh)', 'bmtp.dana', 'Kepala Divisi Funding & Jaringan', 'Funding & Jaringan', ['kepala-unit']],
            'risiko' => ['Direktur Manajemen Risiko (contoh)', 'bmtp.risiko', 'Direktur Manajemen Risiko', 'Manajemen Risiko', ['kepala-unit']],
            'ops' => ['Direktur Operasional & TI (contoh)', 'bmtp.operasi', 'Direktur Operasional & TI', 'Operasional & TI', ['kepala-unit']],
            'kepatuhan' => ['Direktur Kepatuhan & SDM (contoh)', 'bmtp.kepatuhan', 'Direktur Kepatuhan & SDM', 'Kepatuhan', ['kepala-unit']],
            'sdm' => ['Kepala Divisi Human Capital (contoh)', 'bmtp.sdm', 'Kepala Divisi Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Perencanaan Strategis (contoh)', 'bmtp.strategi', 'Kepala Divisi Perencanaan Strategis', 'Perencanaan Strategis', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'bmtp.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'bmtp.analis', 'Performance Analyst', 'Perencanaan Strategis', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi mitra terbaik dan terpercaya dalam memberdayakan dan menyejahterakan.',
            'mission' => "1. Peningkatan bisnis pensiun sebagai engine of growth.\n2. Fokus penghimpunan dana melalui retail funding, commercial funding, dan priority banking.\n3. Pengembangan low cost network dan national expansion.\n4. Memberikan layanan dan produk-produk yang kompetitif.\n5. End to end layanan pensiun dan peningkatan layanan nasabah mikro dan ritel.\n6. Merekrut, melatih, dan mengembangkan SDM untuk mendukung bisnis pensiun dan UMKM.\n7. Memahami kebutuhan nasabah khususnya segmen pensiun dan UMKM.\n8. Menciptakan produk dan layanan keuangan segmen pensiun dan UMKM dengan menyelaraskan kepentingan ekonomi, sosial, dan lingkungan.",
            'purpose' => 'Semangat menyejahterakan purnabakti.',
            'tagline' => 'The Undisputed Leader in Indonesia’s Senior Citizen Financial Ecosystem',
            'brand_promise' => 'Kredit pensiun yang cepat dan aman, tabungan pensiun, serta layanan kesehatan & wirausaha bagi purnabakti (Mantap Sehat, Mantap Aktif, Wirausaha Mantap Sejahtera).',
            'value_proposition' => 'Didukung Bank Mandiri (keahlian operasional) dan PT Taspen (akses pensiunan ASN); kerja sama ASABRI & Dapen BUMN; 290 kantor; 863.627 rekening Tabungan Simantap Pensiun.',
            'culture_statement' => 'AKHLAK (Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif) diwujudkan dalam perilaku Mantap: Do the Right Thing the Right Way, Act Innovatively, Think Smart Fast and Detail, Collaborate for Results, Customer First Always.',
            'history' => "1992: berdiri di Denpasar sebagai PT Bank Sinar Harapan Bali.\n2015: berganti nama menjadi PT Bank Mandiri Taspen Pos; fokus pada pensiunan.\n2017: menjadi PT Bank Mandiri Taspen.\n2025: aset Rp73,0 T; kredit Rp50,5 T (99% kredit pensiun); 3.544 pegawai; corporate plan 2026–2030 disusun.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Amanah', 'Do the right thing, the right way', 'Memegang teguh kepercayaan yang diberikan.', "Menolak gratifikasi\nMenjaga data nasabah pensiunan"],
            ['Kompeten', 'Think smart, fast and detail', 'Terus belajar dan mengembangkan kapabilitas.', "Analisis kredit teliti\nIkut sertifikasi"],
            ['Harmonis', 'Customer first, always', 'Saling peduli dan menghargai perbedaan.', "Melayani purnabakti dengan sabar\nMenghargai rekan"],
            ['Loyal', 'Semangat menyejahterakan purnabakti', 'Berdedikasi dan mengutamakan kepentingan bangsa.', "Menjaga nama baik perusahaan\nRela berkontribusi lebih"],
            ['Adaptif', 'Act innovatively', 'Terus berinovasi dan antusias menghadapi perubahan.', "Mengusulkan perbaikan proses\nMenggunakan layanan digital"],
            ['Kolaboratif', 'Collaborate for results', 'Membangun kerja sama yang sinergis.', "Sinergi dengan Bank Mandiri & Taspen\nKolaborasi kantor pusat–cabang"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Akses pasar pensiunan (Taspen, ASABRI, Dapen)', 'Kemitraan', 'Captive market pensiunan ASN, TNI/Polri, BUMN.', 5, 5, true, 'Perluas ke Dapen BUMN & swasta.'],
            ['Risk Assessment Criteria & verifikasi kesehatan', 'Sistem', 'Angsuran dipotong langsung dari manfaat pensiun; mitigasi risiko mortalitas.', 4, 5, true, 'Credit scoring & verifikasi kesehatan diperkuat.'],
            ['Jaringan kantor biaya rendah', 'Sumber daya fisik', '39 KC, 250 KCP, termasuk di kantor ASABRI.', 4, 4, true, 'Smart Branch & ekspansi selektif.'],
            ['Pendanaan murah (CASA)', 'Sistem', 'CASA 23,3%; deposito 77% DPK.', 2 + $lvl, 4, false, 'Tabungan pensiun, payroll, cash management.'],
            ['Kredit di luar segmen pensiun', 'Kompetensi', 'Mikro & ritel hanya ±1% portofolio; Mantap Auto Loan baru.', 2, 3, false, 'Diversifikasi selektif dengan RAC ketat.'],
            ['Layanan digital untuk senior', 'Sumber daya teknologi', '446.986 pengguna mobile banking; core banking baru disiapkan.', 2 + $lvl, 4, false, 'New Core Banking & fitur ramah lansia.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi ekonomi & perbankan 2025', '2026-01-20', 'eksternal', self::LT.' — Tinjauan Ekonomi & Industri',
                'Ekonomi tumbuh ±5%; suku bunga mulai turun; perbankan sehat, tetapi risiko kredit konsumer & UMKM masih tinggi; transaksi digital & QRIS tumbuh pesat.',
                'Segmen pensiun relatif tahan perlambatan; segmen mikro perlu kehati-hatian.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan & Pencapaian Target',
                'Aset Rp73,0 T (+10,3%; 100,9% RBB); kredit Rp50,5 T (+9,2%; 96,6% RBB); DPK Rp55,0 T (+12,7%; 104,7% RBB); laba Rp1,58 T (+0,2%; 93,9% RBB); CAR 30,5%; NPL 0,63%; ROE 18,9%; NIM 5,42%; BOPO 70,9%; CASA 23,3%.',
                'Neraca tumbuh, laba stagnan: pendapatan non-bunga 80,7% target dan CKPN naik.'],
            ['Proyeksi RBB 2026', '2026-02-05', 'internal', self::LT.' — Target ke Depan (Proyeksi) 2026',
                'Proyeksi 2026: aset Rp78,8 T; kredit Rp55,4 T; DPK Rp57,2 T; laba Rp1,55 T; ROE 16,7%; NIM 5,47%; BOPO 72,7%; CAR 28,0%; NPL ≤ 1%. Kebijakan: kredit pensiun meningkat, fee based income, kualitas aset (selektif di Bali), dana tumbuh 4,5% dengan LFR 86–92%.',
                'Target keuangan 2026 dalam demo ini memakai proyeksi tersebut.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['sosial', 'Penduduk lansia bertambah', 'Pasar pensiunan & pra-pensiun terus membesar.', 'peluang', 5, 5, 'naik', 'Financial Wellbeing Partner bagi pensiunan.'],
            ['politik', 'Kebijakan manfaat pensiun ASN & TNI/Polri', 'Perubahan skema pensiun memengaruhi plafon kredit.', 'ancaman', 4, 3, 'stabil', 'Pantau regulasi bersama Taspen & ASABRI.'],
            ['ekonomi', 'Suku bunga & persaingan dana', 'Biaya deposito tinggi menekan NIM (5,56% → 5,42%).', 'ancaman', 4, 4, 'stabil', 'Naikkan tabungan pensiun & giro institusi.'],
            ['ekonomi', 'Risiko kredit UMKM', 'Risiko segmen UMKM & konsumer masih tinggi.', 'ancaman', 3, 4, 'naik', 'Selektif, terutama di Bali.'],
            ['ekonomi', 'Gejolak global 2026', 'Konflik Timur Tengah, harga minyak, arus modal keluar.', 'ancaman', 3, 3, 'naik', 'Jaga likuiditas & permodalan.'],
            ['teknologi', 'AI, open banking & API', 'Proses bisnis lebih sederhana & efisien.', 'peluang', 4, 4, 'naik', 'New Core Banking & risk intelligence.'],
            ['teknologi', 'Bank digital & fintech', 'Persaingan kredit & dana dari platform digital.', 'ancaman', 3, 3, 'naik', 'Mobile banking ramah lansia.'],
            ['lingkungan', 'Keuangan berkelanjutan', 'Kewajiban RAKB & pelaporan.', 'peluang', 2, 4, 'naik', 'Produk sosial: Mantap Sehat, Wirausaha Mantap Sejahtera.'],
            ['hukum', 'Pelindungan konsumen lansia', 'Transparansi produk bagi nasabah rentan.', 'ancaman', 4, 4, 'naik', 'Edukasi & penanganan pengaduan.'],
            ['hukum', 'Ketahanan siber & PDP', 'POJK keamanan siber & UU PDP.', 'ancaman', 4, 4, 'naik', 'Multi-layered security ring 1–3.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Bank penyalur kredit pensiun lain', 'Persaingan plafon, bunga, dan kecepatan pencairan.', 4, 'Proses cepat & layanan purnabakti.'],
            ['kekuatan_pembeli', 'Pensiunan & institusi pembayar pensiun', 'Pensiunan membandingkan bunga; institusi memilih bank mitra.', 4, 'Kemitraan Taspen, ASABRI, Dapen.'],
            ['kekuatan_pemasok', 'Deposan besar', 'Deposito 77% DPK; sensitif bunga.', 4, 'Tabungan pensiun & dana institusi.'],
            ['pendatang_baru', 'Bank digital', 'Akuisisi nasabah ASN aktif & pra-pensiun.', 3, 'Mobile banking & pra-pensiun.'],
            ['produk_pengganti', 'Pinjaman daring & koperasi', 'Pinjaman cepat tanpa agunan.', 2, 'Edukasi & standby loan.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Kredit pensiun (ASN, TNI/Polri, BUMN)', 'Kredit', 'Rp49,8 T (+9,0%).', 98.6, 'naik', true, 'Pencairan cepat, angsuran ringan.', 'Penyalur kredit pensiun terbesar.'],
            ['Kredit mikro', 'Kredit', 'Rp617 M (+45,6%).', 1.2, 'naik', true, 'Modal usaha, termasuk purnabakti wirausaha.', 'Tumbuh selektif dengan RAC ketat.'],
            ['Kredit ritel & Mantap Auto Loan', 'Kredit', 'Rp83 M (−35%).', 0.2, 'turun', false, 'Pembiayaan kendaraan & konsumer.', 'Boosting Auto Loan secara selektif.'],
            ['Dana pensiunan & ritel', 'Dana', 'Tabungan Rp12,4 T (+19,5%); 863.627 rekening Simantap Pensiun.', 0, 'naik', true, 'Tabungan bebas biaya & layanan kesehatan.', 'Tabungan pensiun sebagai sumber dana murah.'],
            ['Dana institusi & deposito', 'Dana', 'Deposito Rp42,2 T (77% DPK); giro Rp435 M.', 0, 'naik', true, 'Bunga kompetitif & cash management.', 'Cash management & dana grup.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — kinerja produk kredit & DPK']);
        }

        foreach ([
            ['PT Bank Mandiri Taspen', 'Bank pensiun', 1, 6991, 5.6, 22.6, true, 'Akses pensiunan Taspen; NPL 0,63%; CAR 30,5%.', 'Konsentrasi segmen; CASA rendah.'],
            ['Bank BUMN & swasta besar (produk pensiun)', 'Bank umum', 2, null, null, null, false, 'Jaringan & dana murah.', 'Pensiun bukan fokus utama.'],
            ['Bank pensiun swasta', 'Bank pensiun', 3, null, null, null, false, 'Pengalaman di segmen pensiun.', 'Skala & dukungan grup.'],
            ['Bank digital', 'Digital', 4, null, null, null, false, 'Aplikasi & akuisisi cepat.', 'Kurang dikenal pensiunan.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan bunga + operasional lainnya dalam Rp miliar; marjin = rasio laba terhadap pendapatan).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Kewajiban penyediaan modal minimum', 'OJK', 'POJK KPMM', 'Permodalan', null, 'CAR minimum sesuai profil risiko.', 'Ekspansi kredit.', 'patuh', 'cfo', null],
            ['Kredit pensiun & pemotongan manfaat', 'OJK, Taspen & ASABRI', 'Perjanjian kerja sama pembayaran pensiun', 'Kredit', null, 'Batas angsuran & mekanisme potong langsung.', 'Inti bisnis.', 'patuh', 'bisnis', null],
            ['Pelindungan konsumen sektor jasa keuangan', 'OJK', 'POJK Pelindungan Konsumen', 'Konsumen', null, 'Transparansi produk bagi nasabah lansia.', 'Reputasi.', 'patuh', 'kepatuhan', null],
            ['APU-PPT', 'OJK & PPATK', 'POJK APU-PPT', 'Kepatuhan', null, 'Pengenalan nasabah & transaksi mencurigakan.', 'Sanksi & reputasi.', 'patuh', 'kepatuhan', null],
            ['Ketahanan & keamanan siber bank', 'OJK', 'POJK tentang ketahanan & keamanan siber bank umum', 'Teknologi', null, 'Tata kelola TI & pelaporan insiden.', 'Kepercayaan nasabah.', 'patuh', 'ops', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pensiunan dari institusi mitra.', 'Sanksi & kepercayaan.', 'sebagian', 'ops', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang' => ['Pemegang saham (Bank Mandiri 51,1%, Taspen 48,4%)', 'internal', 'Pemilik', 5, 5, 'Laba & sinergi grup.', 'RUPS & rapat sinergi.', 'Corporate Secretary'],
            'pensiunan' => ['Nasabah pensiunan & pra-pensiun', 'eksternal', 'Nasabah', 4, 5, 'Kredit cepat, aman, layanan ramah.', 'Survei & cabang.', 'Bisnis Pensiun'],
            'mitra' => ['Taspen, ASABRI & Dapen BUMN', 'eksternal', 'Mitra', 5, 5, 'Penyaluran manfaat & kredit yang tertib.', 'Perjanjian kerja sama & rekonsiliasi.', 'Bisnis Pensiun'],
            'umkm' => ['Nasabah UMKM & mikro', 'eksternal', 'Nasabah', 2, 4, 'Modal usaha & pendampingan.', 'Program Wirausaha Mantap Sejahtera.', 'Bisnis UMKM'],
            'ojk' => ['OJK, Bank Indonesia & LPS', 'eksternal', 'Regulator', 5, 4, 'Kesehatan bank & pelindungan konsumen.', 'Pelaporan & pemeriksaan.', 'Kepatuhan'],
            'karyawan' => ['Pegawai (3.544 orang)', 'internal', 'SDM', 3, 5, 'Karier & kesejahteraan.', 'Survei & forum pegawai.', 'Human Capital'],
            'investor' => ['Pemegang obligasi (BMTP)', 'eksternal', 'Investor', 3, 3, 'Kemampuan bayar & peringkat.', 'Laporan berkala.', 'Treasury'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'konsentrasi' => ['Konsentrasi pada kredit pensiun', '98,6% kredit adalah kredit pensiun.', 'pelanggan', 4, 5, 'bisnis', ['pemegang', 'mitra']],
            'dana' => ['Dana mahal', 'CASA 23,3%; deposito 77% DPK; NIM turun ke 5,42%.', 'keuangan', 4, 4, 'dana', ['pemegang']],
            'laba' => ['Laba stagnan', 'Laba +0,2% (93,9% RBB); pendapatan non-bunga 80,7% RBB.', 'keuangan', 4, 4, 'cfo', ['pemegang']],
            'kualitas' => ['Kualitas kredit mikro, khususnya Bali', 'NPL bank 0,38% → 0,63%; manajemen memilih sangat selektif di Bali.', 'proses', 4, 4, 'risiko', ['ojk', 'umkm']],
            'digital' => ['Transformasi digital & core banking', 'New Core Banking & layanan digital senior citizen.', 'proses', 3, 4, 'ops', ['pensiunan']],
            'talenta' => ['Talenta untuk bisnis baru', 'Kompetensi kredit non-pensiun & digital terbatas.', 'sdm', 3, 3, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Akses captive market pensiunan', 'Dukungan Taspen, ASABRI, Dapen.', 0.16, 4],
            ['S', 'Risiko kredit pensiun rendah', 'Angsuran dipotong langsung dari manfaat pensiun.', 0.12, 4],
            ['S', 'Permodalan kuat', 'CAR 30,5%.', 0.08, 4],
            ['S', 'Dukungan induk Bank Mandiri', 'Keahlian operasional & pendanaan grup.', 0.06, 3],
            ['W', 'Konsentrasi segmen', 'Kredit pensiun 98,6%.', 0.12, 2],
            ['W', 'CASA rendah', '23,3% dari DPK.', 0.10, 2],
            ['W', 'Pendapatan non-bunga kecil', 'Rp420 M; 80,7% target.', 0.06, 2],
            ['O', 'Dapen BUMN & swasta', 'Segmen pensiun baru.', 0.08, 3],
            ['O', 'Pasar lansia & pra-pensiun', 'Populasi lansia bertambah.', 0.06, 4],
            ['O', 'AI, API & open banking', 'Proses lebih efisien.', 0.04, 3],
            ['T', 'Perubahan kebijakan pensiun', 'Plafon & skema manfaat.', 0.05, 3],
            ['T', 'Persaingan bunga deposito', 'Biaya dana.', 0.04, 3],
            ['T', 'Risiko UMKM & siber', 'Kredit mikro & keamanan digital.', 0.03, 3],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Perluas kemitraan Dapen & ASABRI', 'Kredit & tabungan pensiun lewat mitra baru.', 5, ['konsentrasi']],
            ['SO', 'Tabungan pensiun sebagai jangkar CASA', 'Manfaat pensiun dibayarkan ke rekening Simantap.', 4, ['dana']],
            ['WO', 'Diversifikasi selektif: mikro & Auto Loan', 'Tumbuh di luar pensiun dengan RAC & credit scoring.', 3, ['konsentrasi', 'laba']],
            ['WO', 'Fee based income dari ekosistem purnabakti', 'Payment point, rebate asuransi, e-channel.', 3, ['laba']],
            ['WT', 'Penguatan collection & seleksi di Bali', 'Kendalikan NPL mikro.', 5, ['kualitas']],
            ['ST', 'New Core Banking & digital ramah lansia', 'Efisiensi & layanan.', 4, ['digital', 'talenta']],
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
            'T1' => ['Pemimpin Ekosistem Pensiunan', 'Penyalur kredit pensiun terbesar; Financial Wellbeing Partner.', '#003D79'],
            'T2' => ['Dana Murah & Pendapatan Berimbang', 'CASA, fee based income, efisiensi.', '#F2A900'],
            'T3' => ['Diversifikasi yang Hati-hati', 'Mikro, Auto Loan, kualitas aset.', '#C62828'],
            'T4' => ['Digital, Insan & Tata Kelola', 'Core banking baru, layanan senior, talenta.', '#2E7D32'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Kemitraan institusi pembayar pensiun', 'saluran', 'Taspen, ASABRI, dan Dapen sebagai saluran utama.'],
            ['T2', 'Rekening pembayaran manfaat sebagai CASA', 'produk', 'Tabungan Simantap Pensiun sebagai rekening utama purnabakti.'],
            ['T3', 'Tumbuh di luar pensiun hanya dengan RAC ketat', 'operasi', 'Selektif, terutama di Provinsi Bali.'],
            ['T4', 'New Core Banking sebagai fondasi', 'operasi', 'Efisiensi & skalabilitas bisnis.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T2', 'Laba berkelanjutan', 'Laba Rp1,55 T sesuai RBB 2026.', 'dirut', 3],
            'F2' => ['keuangan', 'T2', 'Imbal hasil & efisiensi terjaga', 'ROE, NIM, BOPO, CIR sesuai RBB.', 'cfo', 2],
            'F3' => ['keuangan', 'T1', 'Neraca tumbuh dengan modal kuat', 'Aset Rp78,8 T; CAR 28%.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T2', 'Dana tumbuh & lebih murah', 'DPK Rp57,2 T; tabungan & giro naik.', 'dana', 2],
            'C2' => ['pelanggan', 'T1', 'Penyalur kredit pensiun terbesar', 'Kredit Rp55,4 T; pensiunan baru via mitra.', 'bisnis', 2.5],
            'C3' => ['pelanggan', 'T3', 'Segmen baru tumbuh selektif', 'Mikro & Mantap Auto Loan.', 'bisnis', 1],
            'C4' => ['pelanggan', 'T4', 'Layanan prima bagi purnabakti', 'Kepuasan & pengguna mobile banking.', 'dana', 1.5],
            'P1' => ['proses', 'T3', 'Kualitas aset terjaga', 'NPL ≤ 1%; NPL mikro turun; recovery naik.', 'risiko', 2],
            'P2' => ['proses', 'T1', 'Proses kredit cepat & digital', 'Pencairan cepat lewat MONA & leads management.', 'bisnis', 1.5],
            'P3' => ['proses', 'T4', 'Fondasi digital andal', 'New Core Banking live; layanan tersedia.', 'ops', 1.5],
            'P4' => ['proses', 'T4', 'Tata kelola & kepatuhan', 'Temuan audit tuntas; APU-PPT.', 'kepatuhan', 1],
            'L1' => ['sdm', 'T4', 'Insan Mantap kompeten', 'Pelatihan & retensi.', 'sdm', 1],
            'L2' => ['sdm', 'T4', 'Budaya AKHLAK & kinerja', 'Engagement & KPI berjenjang.', 'sdm', 1],
            'L3' => ['sdm', 'T3', 'Data & risk intelligence', 'Model risiko & transaksi digital.', 'risiko', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P2', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'P2', 2],
            ['P1', 'F1', 3], ['P2', 'C2', 3], ['P2', 'C3', 2], ['P3', 'C4', 3], ['P3', 'F2', 2], ['P4', 'P1', 1],
            ['C1', 'F2', 3], ['C2', 'F1', 3], ['C2', 'F3', 2], ['C3', 'F1', 1], ['C4', 'C1', 2], ['C4', 'C2', 2],
            ['F1', 'F2', 3], ['F3', 'F1', 1],
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
            ['F1.1', 'F1', 'Laba tahun berjalan', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba setelah pajak (target 2025 & 2026 dari RBB)', 'Laporan keuangan', 1577.6, 1683.4, 1581.1, 1550.8, .98, $lt],
            ['F1.2', 'F1', 'Pendapatan bunga bersih', 'Rp miliar', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan bunga − beban bunga', 'Laporan laba rugi', 3441.6, 3676.7, 3665.5, 4053.3, .97, $lt],
            ['F1.3', 'F1', 'Pendapatan operasional lainnya', 'Rp miliar', 'higher', 'sum', 0, true, false, 'dana', 'Fee based income: admin, payment point, rebate asuransi, e-channel', 'Laporan laba rugi', 421.0, 520.1, 419.7, 394.9, 1.04, $lt],
            ['F2.1', 'F2', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'average', 2, true, true, 'cfo', 'Laba ÷ rata-rata ekuitas × 100', 'Rasio keuangan', 22.89, 19.25, 18.90, 16.66, .99, $lt],
            ['F2.2', 'F2', 'BOPO', '%', 'lower', 'average', 2, true, false, 'cfo', 'Beban operasional ÷ pendapatan operasional × 100', 'Rasio keuangan', 69.02, 69.47, 70.85, 72.65, 1.0, $lt],
            ['F2.3', 'F2', 'Marjin bunga bersih (NIM)', '%', 'higher', 'average', 2, true, false, 'cfo', 'Pendapatan bunga bersih ÷ rata-rata aset produktif × 100', 'Rasio keuangan', 5.56, 5.44, 5.42, 5.47, .98, $lt],
            ['F2.4', 'F2', 'Cost to income ratio (CIR)', '%', 'lower', 'average', 2, true, false, 'cfo', 'Beban operasional di luar CKPN ÷ pendapatan operasional × 100', 'Rasio keuangan', 45.67, 45.00, 44.83, 45.50, 1.02, $lt],
            ['F3.1', 'F3', 'Rasio kecukupan modal (CAR)', '%', 'higher', 'last', 2, true, true, 'cfo', 'Modal ÷ ATMR × 100', 'Laporan KPMM', 27.43, 26.30, 30.47, 28.01, 1.02, $lt],
            ['F3.2', 'F3', 'Total aset', 'Rp miliar', 'higher', 'last', 0, true, false, 'cfo', 'Total aset akhir periode', 'Neraca', 66232, 72398, 73019, 78757, .98, $lt],
            ['F3.3', 'F3', 'Ekuitas', 'Rp miliar', 'higher', 'last', 0, true, false, 'cfo', 'Total ekuitas akhir periode', 'Neraca', 7788, 9215, 9395, 10485, 1.0, $lt],
            ['C1.1', 'C1', 'Dana pihak ketiga', 'Rp miliar', 'higher', 'last', 0, true, true, 'dana', 'Giro + tabungan + deposito', 'Neraca', 48825, 52556, 55031, 57200, 1.0, $lt],
            ['C1.2', 'C1', 'Tabungan', 'Rp miliar', 'higher', 'last', 0, true, false, 'dana', 'Saldo tabungan, termasuk Tabungan Simantap Pensiun', 'Neraca', 10372, 11500, 12393, 13000, 1.0, $lt],
            ['C1.3', 'C1', 'Giro', 'Rp miliar', 'higher', 'last', 0, true, false, 'dana', 'Saldo giro institusi & korporasi', 'Neraca', 1026, 550, 435, 600, .84, $lt],
            ['C1.4', 'C1', 'Rasio CASA', '%', 'higher', 'last', 2, true, false, 'dana', '(Giro + tabungan) ÷ DPK × 100', 'Rasio keuangan', 23.35, 23.00, 23.31, 24.00, .97, $lt],
            ['C2.1', 'C2', 'Total kredit', 'Rp miliar', 'higher', 'last', 0, true, true, 'bisnis', 'Saldo kredit bruto (target dari RBB)', 'Neraca', 46261, 52294, 50531, 55446, .97, $lt],
            ['C2.2', 'C2', 'Kredit pensiun', 'Rp miliar', 'higher', 'last', 0, true, false, 'bisnis', 'Saldo kredit pensiunan & pra-pensiun', 'Kinerja produk kredit', 45710, 51600, 49831, 54500, .97, $lt],
            ['C2.3', 'C2', 'Rekening Tabungan Simantap Pensiun', 'ribu', 'higher', 'last', 1, false, false, 'dana', 'Jumlah rekening tabungan pensiun (ribu rekening)', 'Data nasabah', 705.0, 820.0, 863.6, 950.0, 1.0, $lt],
            ['C3.1', 'C3', 'Kredit mikro', 'Rp miliar', 'higher', 'last', 0, true, false, 'bisnis', 'Saldo kredit mikro', 'Kinerja produk kredit', 423.5, 650.0, 616.6, 720.0, .9, $lt],
            ['C3.2', 'C3', 'Penyaluran Mantap Auto Loan', 'Rp miliar', 'higher', 'sum', 0, true, false, 'bisnis', 'Pencairan kredit kendaraan baru', 'Sistem kredit (ilustrasi)', 20, 150, 115, 400, .85, $il],
            ['C4.1', 'C4', 'Indeks kepuasan nasabah', 'skor', 'higher', 'average', 2, false, false, 'dana', 'Survei kepuasan nasabah (skala 5)', 'Survei layanan', 4.40, 4.45, 4.47, 4.55, .99, $il],
            ['C4.2', 'C4', 'Pengguna mobile banking', 'ribu', 'higher', 'last', 0, false, false, 'ops', 'Pengguna terdaftar mobile banking (ribu)', 'Data e-channel', 360, 430, 447, 560, .92, $lt],
            ['P1.1', 'P1', 'Kredit bermasalah (NPL gross)', '%', 'lower', 'last', 2, true, true, 'risiko', 'Kredit kolektibilitas 3–5 ÷ total kredit × 100 (batas RBB 1%)', 'Laporan kualitas aset', 0.38, 1.00, 0.63, 1.00, .8, $lt],
            ['P1.2', 'P1', 'NPL kredit mikro & ritel', '%', 'lower', 'last', 2, true, true, 'risiko', 'Kredit mikro & ritel kolektibilitas 3–5 ÷ saldo × 100', 'Laporan kualitas aset (ilustrasi)', 3.20, 3.00, 4.10, 3.00, 1.35, $il],
            ['P1.3', 'P1', 'Penerimaan kredit hapus buku', 'Rp miliar', 'higher', 'sum', 0, true, false, 'risiko', 'Pemulihan pokok kredit yang telah dihapusbukukan', 'Laporan keuangan', 156.2, 160.0, 110.6, 140.0, .9, $lt],
            ['P2.1', 'P2', 'Waktu pencairan kredit pensiun', 'hari', 'lower', 'average', 1, false, false, 'bisnis', 'Rata-rata hari kerja dari pengajuan lengkap sampai pencairan', 'Sistem MONA', 3.0, 2.5, 2.6, 1.5, 1.12, $il],
            ['P2.2', 'P2', 'Pengajuan kredit melalui leads management & mobile', '%', 'higher', 'average', 0, false, false, 'bisnis', 'Pengajuan digital ÷ total pengajuan × 100', 'Leads management', 10, 20, 18, 35, .88, $il],
            ['P3.1', 'P3', 'Kemajuan New Core Banking System', '%', 'higher', 'last', 0, false, false, 'ops', 'Persentase milestone proyek selesai', 'PMO TI', 30, 60, 55, 100, .85, $il],
            ['P3.2', 'P3', 'Ketersediaan layanan digital', '%', 'higher', 'average', 2, false, false, 'ops', 'Waktu layanan tersedia ÷ total waktu × 100', 'Pemantauan TI', 99.50, 99.70, 99.72, 99.80, .9995, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'kepatuhan', 'Temuan SKAI/eksternal/OJK selesai sesuai tenggat ÷ jatuh tempo × 100', 'SKAI', 85, 92, 93, 95, .98, $il],
            ['P4.2', 'P4', 'Pegawai lulus pelatihan APU-PPT', '%', 'higher', 'last', 0, false, false, 'kepatuhan', 'Pegawai lulus e-learning APU-PPT ÷ wajib × 100', 'Learning management system', 92, 100, 98, 100, .97, $il],
            ['L1.1', 'L1', 'Pegawai mengikuti pelatihan', 'orang', 'higher', 'sum', 0, false, false, 'sdm', 'Jumlah pegawai yang mengikuti pelatihan', 'Human Capital', 3900, 4072, 6586, 5000, .95, $lt],
            ['L1.2', 'L1', 'Turnover pegawai', '%', 'lower', 'last', 1, false, false, 'sdm', 'Pegawai keluar ÷ rata-rata pegawai × 100', 'HRIS', 7.5, 7.0, 6.8, 6.5, 1.03, $il],
            ['L2.1', 'L2', 'Indeks engagement pegawai', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterikatan pegawai (skala 100)', 'Survei pegawai', 78, 80, 81, 82, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Pegawai dengan KPI terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Pegawai dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 55, 70, 72, 90, .9, $il],
            ['L3.1', 'L3', 'Model risiko & scoring di produksi', 'unit', 'higher', 'sum', 0, false, false, 'risiko', 'Model credit scoring, verifikasi kesehatan, early warning', 'Manajemen Risiko', 2, 4, 4, 7, .86, $il],
            ['L3.2', 'L3', 'Porsi transaksi melalui kanal digital', '%', 'higher', 'average', 0, false, false, 'ops', 'Transaksi mobile banking, QRIS, ATM ÷ total transaksi × 100', 'Data transaksi', 55, 60, 61, 66, 1.0, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Graha Mantap, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, pemimpin cabang, Perencanaan Strategis',
                'summary' => "Aset Rp73,0 T (100,9% RBB); DPK Rp55,0 T (104,7%); kredit Rp50,5 T (96,6%).\nLaba Rp1,58 T (93,9% RBB; +0,2%); pendapatan operasional lainnya 80,7% RBB karena recovery asuransi non-recurring belum tercapai.\nCAR 30,5%; NPL 0,63% (2024: 0,38%); ROE 18,9%; NIM 5,42%; BOPO 70,9%; CASA 23,3%.\nPelatihan 6.586 pegawai (161,7% rencana).",
                'decisions' => "1. Target 2026 mengikuti RBB 2026-2027: laba Rp1,55 T, kredit Rp55,4 T, DPK Rp57,2 T, NPL ≤ 1%.\n2. Kredit mikro di Bali sangat selektif; penguatan collection.\n3. KPI korporat diturunkan ke divisi & cabang melalui Ruci BSC.",
                'follow_up' => 'Perencanaan Strategis menurunkan KPI 2026 ke setiap divisi dan cabang paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta rencana diversifikasi yang terukur: berapa porsi kredit non-pensiun yang aman dan bagaimana batas risikonya.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, Perencanaan Strategis',
                'summary' => match ($m) {
                    2 => 'Pencairan kredit pensiun naik menjelang Lebaran; antrean di cabang meningkat.',
                    3 => 'Lebaran (20 Maret) & THR pensiunan: transaksi tabungan pensiun memuncak.',
                    5 => 'NPL mikro naik di cabang Bali; debitur sektor pariwisata kecil tertekan.',
                    6 => 'Semester I: laba 98% RBB; kredit +4,0% ytd; pencairan gaji ke-13 pensiunan mendorong tabungan.',
                    7 => 'Uji coba New Core Banking tahap 2 mundur dua minggu.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Tambah petugas layanan di 30 KCP dengan antrean terpanjang.',
                    3 => 'Pastikan ketersediaan kas di kantor ASABRI & KCP.',
                    5 => 'Moratorium kredit mikro baru di 2 cabang Bali; penagihan intensif.',
                    6 => 'Percepat leads management terintegrasi MONA.',
                    7 => 'Tambah tim uji dari vendor & Bank Mandiri.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Perencanaan Strategis',
                'summary' => $q === 1 ? 'Laba Q1 sesuai RBB; NPL bank 0,66%; NPL mikro 3,9%.' : 'Laba H1 98% RBB; NPL mikro 4,2% vs batas 3%; Mantap Auto Loan 60% rencana.',
                'decisions' => $q === 1 ? 'Credit scoring mikro dikalibrasi ulang.' : 'NPL mikro & ritel menjadi KPI kritis; laporan mingguan ke Direksi.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Komisaris Utama', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti NPL mikro di Bali, keterlambatan New Core Banking, dan giro yang masih di bawah target.',
            'decisions' => 'Direksi melaporkan NPL mikro, kemajuan core banking, dan CASA setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Diversifikasi tidak boleh mengorbankan keunggulan utama kita. Mohon tunjukkan biaya risiko per segmen dibandingkan imbal hasilnya.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, Perencanaan Strategis',
            'summary' => 'Realisasi September sebagian masih draf; NPL mikro dikembalikan menunggu kolektibilitas final.',
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
                    'simantap' => ['BMTP-25-01', 'Tabungan Simantap Pensiun', 'Fitur & layanan tabungan pensiun; KCP di kantor ASABRI.', 'T2', 'dana', '2025-01-01', '2025-12-31', 'selesai', 4, 3, 2, 2, ['C1'], '863.627 rekening (+22,5%).'],
                    'mobile' => ['BMTP-25-02', 'Fitur mobile banking', 'Konsultasi kesehatan, biometric login, standby loan, QRIS, tarik tunai tanpa kartu.', 'T4', 'ops', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 3, 2, ['C4'], '446.986 pengguna.'],
                    'kcp' => ['BMTP-25-03', 'Ekspansi jaringan selektif', 'Tujuh KCP baru termasuk di kantor ASABRI; Smart Branch.', 'T1', 'dana', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 3, ['C2'], '39 KC & 250 KCP.'],
                    'cash' => ['BMTP-25-04', 'Cash management institusi', 'Layanan transaksi untuk nasabah institusi.', 'T2', 'dana', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['C1'], '±46 ribu transaksi.'],
                    'pelatihan' => ['BMTP-25-05', 'Pengembangan kompetensi pegawai', 'Program pelatihan & suksesor.', 'T4', 'sdm', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 1, ['L1'], '6.586 pegawai dilatih.'],
                ],
                'activities' => [
                    ['simantap', 'KCP di kantor ASABRI', 'dana', '2025-01-01', '2025-12-31', 100],
                    ['mobile', 'Rilis fitur mobile banking', 'ops', '2025-01-01', '2025-12-31', 100],
                    ['kcp', 'Pembukaan tujuh KCP', 'dana', '2025-01-01', '2025-12-31', 100],
                    ['cash', 'Akuisisi nasabah institusi', 'dana', '2025-01-01', '2025-12-31', 100],
                    ['pelatihan', 'Program pelatihan pegawai', 'sdm', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['mobile', 'Fitur konsultasi kesehatan live', '2025-06-30', true], ['kcp', 'Tujuh KCP beroperasi', '2025-12-31', true], ['pelatihan', 'Target pelatihan terlampaui', '2025-12-31', true],
                ],
                'budgets' => [
                    ['simantap', 'Promosi & layanan tabungan pensiun', 'opex', 12_000_000_000, 11_000_000_000, $note],
                    ['mobile', 'Pengembangan mobile banking', 'capex', 30_000_000_000, 27_500_000_000, $note, 'Kas internal'],
                    ['kcp', 'Pembukaan KCP & Smart Branch', 'capex', 25_000_000_000, 22_800_000_000, $note, 'Kas internal'],
                    ['cash', 'Platform cash management', 'capex', 8_000_000_000, 7_200_000_000, $note, 'Kas internal'],
                    ['pelatihan', 'Pendidikan & pelatihan', 'opex', 25_300_000_000, 22_360_000_000, 'Realisasi biaya pelatihan Rp22,36 M dari anggaran Rp25,30 M (laporan tahunan).'],
                ],
            ];
        }

        return [
            'defs' => [
                'dapen' => ['BMTP-26-01', 'Kemitraan Dapen BUMN & swasta', 'Kredit & tabungan pensiun bagi peserta Dapen baru.', 'T1', 'bisnis', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 2, ['C2'], 'Kredit pensiun Rp54,5 T.'],
                'mona' => ['BMTP-26-02', 'Leads management terintegrasi MONA', 'Percepatan pengajuan & pencairan kredit.', 'T1', 'bisnis', '2026-01-15', '2026-09-30', 'berjalan', 4, 4, 3, 2, ['P2'], 'Pencairan 1,5 hari.'],
                'casa' => ['BMTP-26-03', 'Program VIP & dana pensiun', 'Optimalisasi dana nasabah pareto; agen Bank Mandiri; open booth.', 'T2', 'dana', '2026-01-01', '2026-12-31', 'berjalan', 4, 3, 2, 2, ['C1'], 'CASA 24%.'],
                'auto' => ['BMTP-26-04', 'Boosting Mantap Auto Loan', 'Program pemasaran selektif untuk kredit kendaraan.', 'T3', 'bisnis', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 2, ['C3'], 'Penyaluran Rp400 M.'],
                'collection' => ['BMTP-26-05', 'Penguatan collection & seleksi Bali', 'Penagihan intensif, restrukturisasi, moratorium selektif.', 'T3', 'risiko', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['P1'], 'NPL mikro 3%.'],
                'scoring' => ['BMTP-26-06', 'Credit scoring & verifikasi kesehatan', 'Penguatan RAC sebagai mitigasi risiko.', 'T3', 'risiko', '2026-01-15', '2026-10-31', 'berjalan', 4, 4, 3, 2, ['L3', 'P1'], '7 model di produksi.'],
                'core' => ['BMTP-26-07', 'New Core Banking System', 'Implementasi core banking baru.', 'T4', 'ops', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 4, 4, ['P3'], 'Live Desember 2026.'],
                'senior' => ['BMTP-26-08', 'Mobile banking untuk senior citizen', 'Fitur ramah lansia & pengajuan kredit di aplikasi.', 'T4', 'ops', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['C4', 'P2'], '560 ribu pengguna.'],
                'efisiensi' => ['BMTP-26-09', 'Otomasi back-office & shared services', 'Penataan proses untuk menekan biaya.', 'T2', 'ops', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['F2'], 'BOPO ≤ 72,65%.'],
                'talenta' => ['BMTP-26-10', 'Talenta & KPI berjenjang', 'Pendidikan calon pegawai, kompetensi kredit non-pensiun, KPI turunan Peta Strategi.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 1, ['L1', 'L2'], '90% KPI terhubung.'],
            ],
            'activities' => [
                ['dapen', 'Perjanjian kerja sama Dapen', 'bisnis', '2026-01-01', '2026-06-30', 100], ['dapen', 'Akuisisi peserta Dapen', 'bisnis', '2026-04-01', '2026-12-31', 55],
                ['mona', 'Integrasi leads management & MONA', 'ops', '2026-01-15', '2026-09-30', 85],
                ['casa', 'Program VIP nasabah pareto', 'dana', '2026-01-01', '2026-12-31', 70], ['casa', 'Open booth & agen Bank Mandiri', 'dana', '2026-03-01', '2026-12-31', 60],
                ['auto', 'Kerja sama dealer & kampanye', 'bisnis', '2026-02-01', '2026-12-31', 55],
                ['collection', 'Penagihan intensif cabang Bali', 'risiko', '2026-05-01', '2026-12-31', 60], ['collection', 'Restrukturisasi debitur mikro', 'risiko', '2026-06-01', '2026-12-31', 45],
                ['scoring', 'Kalibrasi credit scoring mikro', 'risiko', '2026-01-15', '2026-06-30', 100],
                ['core', 'Migrasi data & uji coba', 'ops', '2026-01-01', '2026-10-31', 65], ['core', 'Go-live & stabilisasi', 'ops', '2026-11-01', '2026-12-31', 0],
                ['senior', 'Fitur ramah lansia', 'ops', '2026-01-15', '2026-12-31', 60],
                ['efisiensi', 'Otomasi back-office', 'ops', '2026-02-01', '2026-12-31', 55],
                ['talenta', 'KPI individu turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100], ['talenta', 'Akademi kredit mikro & konsumer', 'sdm', '2026-03-01', '2026-12-31', 55],
            ],
            'milestones' => [
                ['dapen', 'Perjanjian Dapen ditandatangani', '2026-06-30', true], ['scoring', 'Scoring mikro dikalibrasi', '2026-06-30', true], ['mona', 'Leads management live', '2026-09-30', false],
                ['core', 'Uji coba tahap 2 selesai', '2026-08-31', false], ['core', 'Go-live New Core Banking', '2026-12-31', false], ['talenta', 'KPI individu disetujui', '2026-04-30', true],
            ],
            'budgets' => [
                ['dapen', 'Akuisisi & layanan peserta Dapen', 'opex', 20_000_000_000, 12_500_000_000, null],
                ['mona', 'Integrasi sistem kredit', 'capex', 12_000_000_000, 9_000_000_000, null, 'Kas internal'],
                ['casa', 'Program VIP & promosi dana', 'opex', 18_000_000_000, 12_600_000_000, null],
                ['auto', 'Pemasaran Auto Loan', 'opex', 10_000_000_000, 5_800_000_000, null],
                ['collection', 'Penagihan & restrukturisasi', 'opex', 8_000_000_000, 5_300_000_000, null],
                ['scoring', 'Model risiko & verifikasi kesehatan', 'capex', 6_000_000_000, 4_500_000_000, null, 'Kas internal'],
                ['core', 'New Core Banking System', 'capex', 180_000_000_000, 121_000_000_000, null, 'Kas internal'],
                ['senior', 'Pengembangan mobile banking', 'capex', 15_000_000_000, 9_200_000_000, null, 'Kas internal'],
                ['efisiensi', 'Otomasi proses', 'capex', 9_000_000_000, 5_100_000_000, null, 'Kas internal'],
                ['talenta', 'Akademi & program KPI', 'opex', 26_000_000_000, 17_000_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Pendapatan operasional lainnya di bawah target', 'Rp419,7 M vs RBB Rp520,1 M (80,7%): recovery pembayaran asuransi non-recurring tidak tercapai di kuartal IV.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F1.3', null, 'cfo', '2026-02-24',
                [['Rencana fee based income 2026 (payment point, rebate asuransi, e-channel)', 'dana', '2026-02-28', 'selesai', 100]], ['2026-03-05', 'Rencana disetujui Direksi.']],
            ['Kredit di bawah RBB', 'Kredit Rp50,5 T vs RBB Rp52,3 T (96,6%).', 'tinjauan', 'tahunan', 'pelanggan', 'sedang', null, 'C2.1', null, 'bisnis', '2026-02-24',
                [['Kemitraan Dapen & percepatan proses kredit', 'bisnis', '2026-12-31', 'berjalan', 60]], null],
        ] : [
            ['NPL mikro di atas batas', 'NPL mikro & ritel 4,2% per Juni vs batas 3%; terkonsentrasi di cabang Bali.', 'timwas', 'boc', 'proses', 'kritis', null, 'P1.2', 'collection', 'komisaris', '2026-07-28',
                [['Moratorium kredit mikro baru di 2 cabang Bali', 'risiko', '2026-07-31', 'selesai', 100], ['Penagihan intensif & restrukturisasi', 'risiko', '2026-12-31', 'berjalan', 50]], null],
            ['Waktu pencairan kredit pensiun belum turun', '2,4 hari vs target 1,5 hari; integrasi leads management belum selesai.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P2.1', 'mona', 'bisnis', '2026-07-22',
                [['Percepat integrasi leads management–MONA', 'ops', '2026-09-30', 'berjalan', 85]], null],
            ['New Core Banking terlambat', 'Uji coba tahap 2 mundur; kemajuan 52% vs rencana 65% per Agustus.', 'internal', 'rm8', 'proses', 'tinggi', null, 'P3.1', 'core', 'ops', '2026-09-12',
                [['Tambah tim uji dari vendor & Bank Mandiri', 'ops', '2026-10-31', 'berjalan', 60]], null],
            ['Giro di bawah target', 'Giro Rp480 M vs rencana Rp580 M; nasabah institusi baru lambat.', 'internal', 'rm7', 'pelanggan', 'sedang', null, 'C1.3', 'casa', 'dana', '2026-08-12',
                [['Paket cash management untuk institusi pembayar pensiun', 'dana', '2026-11-30', 'berjalan', 45]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Data peserta dari Taspen, ASABRI & Dapen', 'eksternal', 'dapen', 'C2', 'Institusi pembayar pensiun', 'Akuisisi & verifikasi debitur butuh data peserta.', 'bisnis', 'tinggi', 'Perjanjian berbagi data sesuai UU PDP.'],
                ['Vendor core banking', 'eksternal', 'core', 'P3', 'Vendor sistem & Bank Mandiri', 'Go-live bergantung pada kesiapan vendor.', 'ops', 'tinggi', 'Rencana cadangan & tim gabungan.'],
                ['Jaringan agen Bank Mandiri', 'internal', 'casa', 'C1', 'Bank Mandiri', 'Perluasan layanan transaksi tanpa cabang baru.', 'dana', 'sedang', 'Perjanjian sinergi grup.'],
                ['Dealer kendaraan', 'eksternal', 'auto', 'C3', 'Dealer mitra', 'Penyaluran Auto Loan.', 'bisnis', 'sedang', 'Seleksi dealer & insentif berbasis kualitas.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat Komite Kredit (four eyes principle)', 'rapat', 'Persetujuan kredit secara berjenjang.', 'Mingguan', 'risiko', 'melembaga'],
                ['ALCO', 'rapat', 'Likuiditas, suku bunga, dan LFR 86–92%.', 'Bulanan', 'cfo', 'melembaga'],
                ['Rekonsiliasi dengan Taspen & ASABRI', 'sop', 'Pemotongan angsuran dari manfaat pensiun.', 'Bulanan', 'bisnis', 'melembaga'],
                ['Rapat gabungan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, kepatuhan.', 'Kuartalan', 'dirut', 'melembaga'],
                ['Budaya AKHLAK & Mantap', 'budaya', 'Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
