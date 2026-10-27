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
 * Demo PT GoTo Gojek Tokopedia Tbk (kode GOTO) — ekosistem digital on-demand & teknologi finansial.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (GTV & pendapatan pro forma per segmen, EBITDA disesuaikan, rugi, kas, pengguna GoPay, buku pinjaman).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: dari "bakar uang" ke profitabilitas — EBITDA disesuaikan positif, rugi menyempit,
 *   fintech menjadi motor. Rugi dicatat sebagai KPI "lebih kecil lebih baik".
 */
class GotoSeeder extends ShowcaseSeeder
{
    public const CODE = 'GOTO';

    public const LT = 'Laporan Tahunan 2025 PT GoTo Gojek Tokopedia Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT GoTo Gojek Tokopedia Tbk', 'legal_name' => 'PT GoTo Gojek Tokopedia Tbk',
            'industry' => 'Ekosistem digital: layanan on-demand (Gojek) & teknologi finansial (GoPay)',
            'address' => 'Pasaraya Blok M Gedung B, Jl. Iskandarsyah II No. 7', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 5084 9000', 'website' => 'https://www.gotocompany.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham GOTO). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Transaksi naik menjelang Lebaran & akhir tahun; Desember tertinggi (belanja & liburan). */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 7.6, 7.4, 8.4, 8.2, 8.1, 8.1, 8.3, 8.4, 8.3, 8.6, 8.8, 9.8]),
            2026 => self::weights([1 => 7.6, 7.9, 8.6, 7.9, 8.1, 8.1, 8.3, 8.4, 8.3, 8.6, 8.7, 9.5]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'F3.1', 'F3.2', 'F3.3', 'C1.1', 'C2.2', 'C3.2', 'C4.2', 'P2.1', 'P2.2', 'P4.1', 'L1.2', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.1', 'L3.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Buku pinjaman September belum mengeluarkan pinjaman yang dihapusbukukan. Mohon sesuaikan dengan laporan risiko kredit dan lampirkan rekonsiliasinya.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P3.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 4, 2, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin GoTo (contoh)', 'goto.admin', 'Admin Sistem', 'Strategy', ['company-admin']],
            'dirut' => ['Direktur Utama & CEO Grup (contoh)', 'goto.direktur', 'Direktur Utama & Group CEO', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'goto.keuangan', 'Group Chief Financial Officer', 'Keuangan', ['keuangan']],
            'fintech' => ['Presiden Teknologi Finansial (contoh)', 'goto.fintech', 'President, Financial Technology (GoPay)', 'Teknologi Finansial', ['kepala-unit']],
            'ods' => ['Presiden On-Demand Services (contoh)', 'goto.ods', 'President, On-Demand Services (Gojek)', 'On-Demand Services', ['kepala-unit']],
            'risiko' => ['Chief Risk Officer (contoh)', 'goto.risiko', 'Chief Risk Officer', 'Manajemen Risiko', ['kepala-unit']],
            'mitra' => ['Head of Driver & Merchant (contoh)', 'goto.mitra', 'Head of Driver & Merchant Ecosystem', 'Ekosistem Mitra', ['kepala-unit']],
            'teknologi' => ['Chief Technology Officer (contoh)', 'goto.teknologi', 'Group Chief Technology Officer', 'Teknologi', ['kepala-unit']],
            'esg' => ['Head of Sustainability (contoh)', 'goto.esg', 'Head of Sustainability', 'Keberlanjutan', ['kepala-unit']],
            'sdm' => ['Chief People Officer (contoh)', 'goto.sdm', 'Chief People Officer', 'People', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Strategy & Business Planning (contoh)', 'goto.strategi', 'Head of Strategy & Business Planning', 'Strategy', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'goto.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'goto.analis', 'Performance Analyst', 'Strategy', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Memberikan kesempatan kepada siapa pun untuk berpartisipasi dan meraih keberhasilan dalam ekonomi digital.',
            'mission' => 'Mendorong kemajuan (empower progress) bagi konsumen, pedagang, dan mitra pengemudi dengan menghadirkan ekosistem layanan on-demand dan keuangan digital yang memudahkan hidup sehari-hari.',
            'purpose' => 'Membuat ekonomi digital terbuka bagi semua orang — dari pengemudi dan pedagang kecil sampai konsumen yang baru pertama memakai layanan keuangan.',
            'tagline' => 'Go Together, Go Far',
            'brand_promise' => 'Transportasi, makanan, kiriman, dan pembayaran yang andal, terjangkau, dan aman dalam satu ekosistem.',
            'value_proposition' => '66 juta pengguna transaksi tahunan; 57 juta pengguna GoPay; > 6,4 juta pedagang dan > 3 juta mitra pengemudi; GTV pro forma Rp685,6 T.',
            'culture_statement' => 'Prinsip GoTo: pelanggan prioritas utama; kemakmuran bersama pemangku kepentingan; lebih cepat, fokus, gesit — "do more with less".',
            'history' => "2010: Gojek berdiri sebagai layanan ojek panggilan.\n2015: aplikasi Gojek & GoPay diluncurkan.\n2021: merger Gojek–Tokopedia membentuk GoTo.\n2022: tercatat di Bursa Efek Indonesia (GOTO).\n2024: Tokopedia didekonsolidasi (kemitraan TikTok); keluar dari Vietnam.\n2025: EBITDA disesuaikan grup Rp2,0 T (melampaui panduan); migrasi cloud ke pusat data lokal; GoRide Electric & GoGreen.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Pelanggan prioritas utama', 'Customer obsessed', 'Setiap keputusan dimulai dari kebutuhan pengguna, mitra, dan pedagang.', "Mengukur dampak fitur pada pengguna\nMenyelesaikan keluhan dengan cepat"],
            ['Kemakmuran bersama', 'Shared prosperity', 'Pertumbuhan dinikmati semua pemangku kepentingan.', "Pendapatan mitra yang layak\nPedagang kecil ikut tumbuh"],
            ['Berpikir seperti pemilik', 'Owner mindset', 'Melakukan yang terbaik untuk seluruh GoTo, bukan hanya unit sendiri.', "Menghemat biaya seperti uang sendiri\nBerbagi data & sumber daya lintas unit"],
            ['Saling menghormati', 'Respect', 'Kami saling menghormati dan bekerja dengan integritas.', "Berbeda pendapat dengan santun\nTidak menoleransi kecurangan"],
            ['Cepat, fokus, gesit', 'Do more with less', 'Mengerjakan yang paling penting dengan sumber daya efisien.', "Menghentikan proyek yang tidak berdampak\nKeputusan berbasis data"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Ekosistem dua sisi (pengguna–mitra–pedagang)', 'Kompetensi', 'Efek jaringan Gojek & GoPay.', 4, 5, true, 'Lintas-jual antarlayanan.'],
            ['Data ekosistem untuk penilaian kredit', 'Sistem', 'Riwayat transaksi pengguna, mitra, dan pedagang.', 3 + $lvl, 5, true, 'Model risiko kredit berbasis data.'],
            ['Platform teknologi berskala besar', 'Sistem', 'Ratusan juta transaksi; migrasi ke pusat data lokal.', 4, 5, true, 'Keandalan & efisiensi biaya cloud.'],
            ['Disiplin biaya & operating leverage', 'Sistem', 'Rugi usaha turun dari −14% ke −2% terhadap pendapatan.', 3 + $lvl, 5, false, 'Cost to serve per pesanan.'],
            ['Produk terjangkau untuk pasar massal', 'Kompetensi', 'GoRide Hemat, GoCar Hemat.', 3, 4, false, 'Segmentasi produk.'],
            ['AI & bahasa Indonesia', 'Kompetensi', 'Sahabat-AI (LLM bahasa Indonesia).', 2 + $lvl, 4, false, 'AI untuk layanan pelanggan & mitra.'],
            ['Manajemen kinerja berbasis OKR/KPI', 'Sistem', 'OKR unit belum terhubung penuh ke KPI korporat.', 3, 4, false, 'Satu Peta Strategi grup.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Industri on-demand & fintech 2025', '2026-01-20', 'eksternal', self::LT.' — Tinjauan Industri',
                'GMV transportasi & pesan-antar makanan Indonesia ±US$10 M (+13%); persaingan ketat; regulator memperketat pinjaman daring & BNPL; adopsi pembayaran digital terus naik.',
                'Pertumbuhan harus disertai kualitas kredit dan disiplin biaya.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'GTV pro forma Rp685,6 T (+32%); pendapatan bersih Rp18,3 T; EBITDA disesuaikan Rp2,0 T (2024: Rp312 M), melampaui panduan Rp1,8–1,9 T. Rugi tahun berjalan Rp1,50 T (2024: Rp5,46 T). Kas Rp21,8 T. Buku pinjaman Rp8,8 T (+68%).',
                'Fintech sudah positif secara EBITDA; tantangan berikutnya adalah laba bersih.'],
            ['Panduan 2026', '2026-02-10', 'internal', self::LT.' — Prospek Usaha',
                'Panduan EBITDA disesuaikan 2026 Rp3,2–3,4 T; fokus pada operating leverage, pertumbuhan buku pinjaman yang sehat, dan produk per segmen di ODS.',
                'Target 2026 disusun dari panduan publik ini.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Regulasi tarif & kemitraan pengemudi', 'Kebijakan tarif ojek daring & status kemitraan.', 'ancaman', 5, 4, 'naik', 'Dialog dengan regulator & asosiasi mitra.'],
            ['ekonomi', 'Daya beli kelas menengah', 'Konsumen makin sensitif harga.', 'ancaman', 4, 4, 'stabil', 'Produk hemat & program langganan.'],
            ['ekonomi', 'Inklusi keuangan', 'Banyak pengguna & pedagang belum terlayani kredit formal.', 'peluang', 5, 5, 'naik', 'GoPay Later & GoPay Pinjam.'],
            ['sosial', 'Kesejahteraan mitra pengemudi', 'Isu pendapatan & perlindungan sosial mitra.', 'ancaman', 4, 4, 'naik', 'Program kesejahteraan & asuransi mitra.'],
            ['sosial', 'Judi online', 'Risiko penyalahgunaan dompet digital.', 'ancaman', 4, 4, 'stabil', 'Pemantauan transaksi & kampanye anti judi online.'],
            ['teknologi', 'AI generatif', 'Efisiensi layanan pelanggan & produk baru.', 'peluang', 4, 5, 'naik', 'Sahabat-AI & otomasi.'],
            ['teknologi', 'Kedaulatan data & cloud lokal', 'Pusat data lokal menurunkan biaya & risiko.', 'peluang', 3, 4, 'naik', 'Migrasi cloud bersama mitra lokal.'],
            ['lingkungan', 'Kendaraan listrik', 'Transisi ke motor & mobil listrik.', 'peluang', 3, 4, 'naik', 'GoRide Electric & GoGreen.'],
            ['hukum', 'Aturan OJK pinjaman daring & BNPL', 'Batas bunga, rasio kredit macet, perlindungan konsumen.', 'ancaman', 4, 4, 'naik', 'Kualitas kredit & kepatuhan.'],
            ['hukum', 'Pelindungan data pribadi', 'UU PDP berlaku penuh.', 'ancaman', 4, 4, 'naik', 'Program privasi data.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Platform super-app regional & dompet digital', 'Persaingan promosi di ride-hailing, makanan, dan pembayaran.', 5, 'Produk per segmen & efisiensi.'],
            ['kekuatan_pembeli', 'Konsumen mudah berpindah aplikasi', 'Biaya berpindah rendah.', 4, 'Langganan & loyalitas.'],
            ['kekuatan_pemasok', 'Mitra pengemudi & pedagang', 'Mitra aktif di beberapa platform sekaligus.', 4, 'Pendapatan mitra & alat bantu pedagang.'],
            ['pendatang_baru', 'Bank digital & aplikasi pembayaran', 'Bank & e-commerce masuk ke pembayaran & kredit.', 4, 'Data ekosistem sebagai keunggulan.'],
            ['produk_pengganti', 'QRIS & transfer bank instan', 'Pembayaran langsung tanpa dompet digital.', 3, 'GoPay sebagai pusat layanan keuangan.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Pengguna pembayaran GoPay', 'Fintech', '57 juta pengguna transaksi tahunan (+36%).', 30, 'naik', true, 'Pembayaran cepat, aman, banyak diterima.', 'Perluas penerimaan & fitur.'],
            ['Peminjam konsumen & pedagang', 'Fintech', 'Buku pinjaman Rp8,8 T (+68%).', 10, 'naik', true, 'Kredit cepat & terjangkau.', 'Penilaian kredit berbasis data ekosistem.'],
            ['Pengguna premium ODS', 'On-demand', 'Mengutamakan kualitas, kecepatan, kenyamanan.', 35, 'stabil', true, 'Layanan cepat & nyaman.', 'GoCar premium, prioritas.'],
            ['Pengguna pasar massal ODS', 'On-demand', 'Mengutamakan harga terjangkau & terprediksi.', 25, 'naik', true, 'Harga murah & pasti.', 'GoRide Hemat, GoCar Hemat.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' (porsi ilustratif)']);
        }

        foreach ([
            ['PT GoTo Gojek Tokopedia Tbk', 'Ekosistem digital', 1, 18322, 15.3, -8.2, true, 'Ekosistem Gojek + GoPay, data, merek kuat.', 'Masih rugi bersih; persaingan promosi.'],
            ['Super-app regional', 'On-demand', 2, null, null, null, false, 'Skala regional & modal besar.', 'Biaya promosi tinggi.'],
            ['Dompet digital lain', 'Pembayaran', 3, null, null, null, false, 'Terintegrasi dengan e-commerce/bank.', 'Ekosistem layanan harian terbatas.'],
            ['Bank digital', 'Perbankan', 4, null, null, null, false, 'Lisensi bank & dana murah.', 'Data perilaku transaksi harian terbatas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar; marjin = rugi tahun berjalan ÷ pendapatan).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Penyelenggaraan LPBBTI & paylater', 'OJK', 'POJK tentang LPBBTI & ketentuan BNPL', 'Keuangan', null, 'Batas bunga, rasio TWP90, perlindungan konsumen.', 'Izin & reputasi.', 'patuh', 'risiko', null],
            ['Sistem pembayaran & uang elektronik', 'Bank Indonesia', 'PBI tentang Sistem Pembayaran', 'Keuangan', null, 'Izin uang elektronik & QRIS.', 'Kelangsungan GoPay.', 'patuh', 'fintech', null],
            ['Anti pencucian uang & judi online', 'PPATK / OJK / BI', 'UU TPPU & ketentuan APU-PPT', 'Kepatuhan', null, 'Pemantauan transaksi mencurigakan.', 'Sanksi & reputasi.', 'patuh', 'risiko', null],
            ['Tarif angkutan sewa khusus & ojek daring', 'Kementerian Perhubungan', 'Permenhub & Kepmenhub tarif', 'Operasi', null, 'Batas tarif & potongan aplikasi.', 'Pendapatan ODS.', 'patuh', 'ods', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pengguna, mitra, dan pedagang.', 'Sanksi & kepercayaan.', 'sebagian', 'teknologi', '2026-12-31'],
            ['Keterbukaan informasi emiten', 'OJK', 'POJK keterbukaan & laporan tahunan', 'Tata kelola', null, 'Panduan kinerja & informasi material.', 'Kepercayaan investor.', 'patuh', 'cfo', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'investor' => ['Pemegang saham & investor publik', 'internal', 'Pemilik', 5, 5, 'Jalan ke laba bersih & pengembalian modal.', 'Paparan kinerja kuartalan.', 'Investor Relations'],
            'pengguna' => ['Pengguna Gojek & GoPay (66 juta)', 'eksternal', 'Pelanggan', 4, 5, 'Layanan cepat, murah, aman.', 'Survei & riset pengguna.', 'Product'],
            'pengemudi' => ['Mitra pengemudi (> 3 juta)', 'eksternal', 'Mitra', 4, 5, 'Pendapatan layak & perlindungan.', 'Kopdar mitra & kanal aspirasi.', 'Driver Ecosystem'],
            'pedagang' => ['Pedagang (> 6,4 juta)', 'eksternal', 'Mitra', 4, 5, 'Penjualan naik & biaya wajar.', 'Aplikasi GoBiz & pendampingan.', 'Merchant Ecosystem'],
            'regulator' => ['OJK, Bank Indonesia, Kemenhub, Komdigi', 'eksternal', 'Regulator', 5, 4, 'Kepatuhan & perlindungan konsumen.', 'Pelaporan & dialog rutin.', 'Regulatory Affairs'],
            'mitra_bank' => ['Bank mitra & lembaga pembiayaan', 'eksternal', 'Mitra pendanaan', 4, 4, 'Kualitas kredit & pertumbuhan.', 'Komite kredit bersama.', 'Fintech Partnerships'],
            'karyawan' => ['Karyawan (3.553 orang)', 'internal', 'SDM', 3, 5, 'Kepastian kerja & pengembangan.', 'Town hall & survei.', 'People'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'rugi' => ['Masih rugi bersih', 'Rugi tahun berjalan Rp1,50 T meski EBITDA disesuaikan positif Rp2,0 T.', 'keuangan', 5, 5, 'cfo', ['investor']],
            'kredit' => ['Pertumbuhan pinjaman vs kualitas kredit', 'Buku pinjaman +68%; regulator memperketat aturan paylater.', 'keuangan', 4, 5, 'risiko', ['regulator', 'mitra_bank']],
            'persaingan' => ['Persaingan promosi ODS', 'GTV ODS hanya +6%; konsumen sensitif harga.', 'pelanggan', 4, 4, 'ods', ['pengguna']],
            'mitra' => ['Kesejahteraan mitra pengemudi', 'Isu tarif & potongan aplikasi menjadi perhatian publik.', 'pelanggan', 4, 5, 'mitra', ['pengemudi', 'regulator']],
            'keandalan' => ['Keandalan platform saat migrasi cloud', 'Migrasi ke pusat data lokal berisiko gangguan layanan.', 'proses', 4, 4, 'teknologi', ['pengguna']],
            'budaya' => ['Organisasi ramping yang tetap inovatif', '"Do more with less" tanpa kehilangan talenta kunci.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Ekosistem Gojek + GoPay', 'Efek jaringan & data lintas layanan.', 0.14, 4],
            ['S', 'EBITDA disesuaikan positif', 'Rp2,0 T; melampaui panduan.', 0.10, 3],
            ['S', 'Kas besar', 'Kas & setara Rp21,8 T.', 0.09, 4],
            ['S', 'Merek nasional', 'Gojek identik dengan layanan on-demand.', 0.07, 4],
            ['W', 'Rugi bersih', 'Rp1,50 T.', 0.12, 1],
            ['W', 'Pertumbuhan ODS lambat', 'GTV ODS +6%.', 0.08, 2],
            ['W', 'Ketergantungan promosi', 'Insentif menekan marjin.', 0.06, 2],
            ['O', 'Inklusi keuangan & kredit', 'Permintaan kredit digital besar.', 0.12, 4],
            ['O', 'AI generatif', 'Efisiensi & produk baru.', 0.06, 3],
            ['O', 'Kendaraan listrik', 'Biaya operasi mitra lebih rendah.', 0.04, 2],
            ['T', 'Regulasi tarif & paylater', 'Batas tarif, bunga, kredit macet.', 0.06, 2],
            ['T', 'Persaingan super-app', 'Perang promosi.', 0.04, 2],
            ['T', 'Insiden keamanan & gangguan', 'Kepercayaan pengguna.', 0.02, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Kredit berbasis data ekosistem', 'Pinjaman ke pengguna, mitra, dan pedagang dengan data transaksi.', 5, ['kredit']],
            ['SO', 'Lintas-jual GoPay di setiap pesanan Gojek', 'Tingkatkan pengguna aktif & interaksi.', 4, ['persaingan']],
            ['WO', 'Produk per segmen ODS', 'Premium vs pasar massal (Hemat).', 4, ['persaingan']],
            ['WO', 'AI untuk menurunkan cost to serve', 'Layanan pelanggan & operasi otomatis.', 3, ['rugi']],
            ['ST', 'Program kesejahteraan mitra', 'Pendapatan per jam, asuransi, kendaraan listrik.', 4, ['mitra']],
            ['WT', 'Operating leverage & disiplin biaya', 'Biaya tetap datar saat pendapatan tumbuh.', 5, ['rugi', 'budaya']],
            ['WT', 'Keandalan & keamanan platform', 'Migrasi cloud aman; ketahanan siber.', 4, ['keandalan']],
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
            'T1' => ['Fintech sebagai Mesin Pertumbuhan', 'Pengguna GoPay, interaksi, dan buku pinjaman yang sehat.', '#00AA13'],
            'T2' => ['On-Demand per Segmen', 'Premium: kualitas & kecepatan; massal: harga terjangkau & terprediksi.', '#1E6FA8'],
            'T3' => ['Profitabilitas & Operating Leverage', 'Do more with less: cost to serve turun, laba bersih mendekat.', '#B22234'],
            'T4' => ['Platform Andal, AI & Keberlanjutan', 'Keandalan, keamanan, AI, kendaraan listrik, dan budaya.', '#E08A1E'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Kredit hanya untuk pengguna dengan data ekosistem', 'produk', 'Menjaga kualitas kredit saat tumbuh cepat.'],
            ['T2', 'Dua lini produk: premium dan hemat', 'produk', 'Strategi ODS dalam laporan tahunan.'],
            ['T3', 'Biaya tetap datar, pertumbuhan dari operating leverage', 'organisasi', 'Rugi usaha turun ke −2% pendapatan.'],
            ['T4', 'Pusat data lokal', 'operasi', 'Biaya cloud & kedaulatan data.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pendapatan & GTV tumbuh', 'Pendapatan bersih ±Rp21 T; GTV Rp800 T.', 'dirut', 2],
            'F2' => ['keuangan', 'T3', 'Menuju laba bersih', 'EBITDA disesuaikan Rp3,2–3,4 T; rugi menyempit.', 'cfo', 3],
            'F3' => ['keuangan', 'T3', 'Kas kuat & biaya terkendali', 'Kas terjaga; beban pemasaran turun terhadap pendapatan.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T1', 'Pengguna GoPay aktif & setia', 'ATU GoPay & GTV inti naik.', 'fintech', 2],
            'C2' => ['pelanggan', 'T1', 'Kredit terjangkau dan sehat', 'Buku pinjaman tumbuh dengan NPL terjaga.', 'fintech', 2],
            'C3' => ['pelanggan', 'T2', 'Layanan ODS yang tepat untuk tiap segmen', 'GTV ODS naik; produk hemat diterima.', 'ods', 1.5],
            'C4' => ['pelanggan', 'T2', 'Mitra & pedagang sejahtera', 'Pendapatan mitra per jam naik; pedagang bertambah.', 'mitra', 1.5],
            'P1' => ['proses', 'T3', 'Cost to serve turun', 'Biaya per pesanan & insentif terhadap GTV turun.', 'ods', 2],
            'P2' => ['proses', 'T1', 'Manajemen risiko kredit unggul', 'Keputusan kredit berbasis data; penagihan efektif.', 'risiko', 1.5],
            'P3' => ['proses', 'T4', 'Platform andal & aman', 'Ketersediaan tinggi; gangguan kritis berkurang; migrasi cloud tuntas.', 'teknologi', 2],
            'P4' => ['proses', 'T4', 'Tata kelola & ekosistem hijau', 'Temuan audit tuntas; armada listrik bertambah.', 'esg', 1],
            'L1' => ['sdm', 'T3', 'Organisasi ramping & produktif', 'Pendapatan per karyawan naik.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya & kinerja', 'Keterikatan tinggi; KPI terhubung.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'AI di seluruh organisasi', 'Fitur AI & layanan pelanggan otomatis.', 'teknologi', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'P2', 2], ['L3', 'P3', 2],
            ['P1', 'F2', 3], ['P1', 'C3', 2], ['P2', 'C2', 3], ['P2', 'F2', 2], ['P3', 'C1', 2], ['P3', 'C3', 2], ['P4', 'C4', 1],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'F1', 2], ['C4', 'C3', 2],
            ['F1', 'F2', 3], ['F2', 'F3', 2],
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
            ['F1.1', 'F1', 'Pendapatan bersih konsolidasian', 'Rp miliar', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan bersih konsolidasian', 'Laporan keuangan konsolidasian', 15894, 17500, 18322, 21000, .97, $lt],
            ['F1.2', 'F1', 'Nilai transaksi bruto (GTV) pro forma', 'Rp miliar', 'higher', 'sum', 0, false, false, 'dirut', 'GTV fintech + ODS (pro forma)', 'Laporan pro forma', 519784, 620000, 685649, 800000, .96, $lt],
            ['F1.3', 'F1', 'Pendapatan segmen teknologi finansial', 'Rp miliar', 'higher', 'sum', 0, true, false, 'fintech', 'Pendapatan bersih GoPay & layanan keuangan (pro forma)', 'Laporan segmen', 3557, 5000, 5780, 7600, .98, $lt],
            ['F1.4', 'F1', 'Pendapatan segmen on-demand services', 'Rp miliar', 'higher', 'sum', 0, true, false, 'ods', 'Pendapatan bersih Gojek (pro forma)', 'Laporan segmen', 10880, 12000, 12596, 13400, .97, $lt],
            ['F2.1', 'F2', 'EBITDA disesuaikan grup', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'EBITDA disesuaikan (pro forma), panduan 2026 Rp3,2–3,4 T', 'Laporan pro forma', 312, 1500, 2008, 3300, .95, $lt],
            ['F2.2', 'F2', 'Rugi tahun berjalan', 'Rp miliar', 'lower', 'sum', 0, true, true, 'cfo', 'Rugi tahun berjalan konsolidasian (angka positif = rugi; makin kecil makin baik)', 'Laporan laba rugi', 5465, 2500, 1502, 600, 1.15, $lt],
            ['F2.3', 'F2', 'Marjin EBITDA disesuaikan', '%', 'higher', 'average', 1, true, false, 'cfo', 'EBITDA disesuaikan ÷ pendapatan bersih × 100', 'Laporan pro forma', 2.1, 9.0, 11.0, 15.7, .95, $lt],
            ['F2.4', 'F2', 'Beban umum & administrasi', 'Rp miliar', 'lower', 'sum', 0, true, false, 'cfo', 'Beban umum & administrasi konsolidasian', 'Laporan laba rugi', 4396, 4200, 3989, 3800, 1.0, $lt],
            ['F3.1', 'F3', 'Kas & setara kas', 'Rp miliar', 'higher', 'last', 0, true, true, 'cfo', 'Kas & setara kas akhir perioda', 'Neraca', 19178, 19500, 21755, 22000, .98, $lt],
            ['F3.2', 'F3', 'Rasio lancar', 'kali', 'higher', 'last', 1, true, false, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 2.6, 2.5, 2.7, 2.6, 1.0, $lt],
            ['F3.3', 'F3', 'Beban penjualan & pemasaran terhadap pendapatan', '%', 'lower', 'average', 1, true, false, 'cfo', 'Beban penjualan & pemasaran ÷ pendapatan × 100', 'Laporan laba rugi', 17.9, 17.0, 16.9, 16.0, 1.02, $lt],
            ['C1.1', 'C1', 'Pengguna transaksi tahunan GoPay', 'juta', 'higher', 'last', 0, false, true, 'fintech', 'Pengguna unik bertransaksi dengan GoPay dalam 12 bulan', 'Data platform', 42, 52, 57, 68, .96, $lt],
            ['C1.2', 'C1', 'Pengguna transaksi tahunan grup', 'juta', 'higher', 'last', 0, false, false, 'dirut', 'Pengguna unik bertransaksi di ekosistem dalam 12 bulan', 'Data platform', 58, 63, 66, 72, .98, $lt],
            ['C1.3', 'C1', 'GTV inti teknologi finansial', 'Rp miliar', 'higher', 'sum', 0, false, false, 'fintech', 'GTV fintech di luar transaksi pass-through', 'Laporan pro forma', 240789, 330000, 369965, 450000, .96, $lt],
            ['C2.1', 'C2', 'Buku pinjaman konsumen & pedagang', 'Rp miliar', 'higher', 'last', 0, true, true, 'fintech', 'Saldo pinjaman beredar (GoPay Later, GoPay Pinjam, pedagang)', 'Laporan risiko kredit', 5240, 8000, 8800, 12500, .94, $lt],
            ['C2.2', 'C2', 'Kredit bermasalah > 90 hari (NPL)', '%', 'lower', 'last', 2, true, false, 'risiko', 'Saldo tunggakan > 90 hari ÷ buku pinjaman × 100', 'Laporan risiko kredit', 2.80, 3.00, 2.70, 3.00, .97, $il],
            ['C3.1', 'C3', 'GTV on-demand services', 'Rp miliar', 'higher', 'sum', 0, false, false, 'ods', 'GTV GoRide, GoCar, GoFood, GoSend', 'Laporan pro forma', 63039, 65000, 66534, 70000, .97, $lt],
            ['C3.2', 'C3', 'Porsi pesanan produk hemat', '%', 'higher', 'average', 0, false, false, 'ods', 'Pesanan GoRide/GoCar Hemat ÷ pesanan transportasi × 100', 'Data platform', 18, 25, 27, 32, 1.0, $il],
            ['C3.3', 'C3', 'Kepuasan pelanggan ODS (CSAT)', '%', 'higher', 'average', 0, false, false, 'ods', 'Pesanan dengan rating ≥ 4 ÷ pesanan dinilai × 100', 'Data platform', 85, 87, 87, 89, .99, $il],
            ['C4.1', 'C4', 'Pendapatan rata-rata mitra per jam online', 'Rp ribu', 'higher', 'average', 0, false, false, 'mitra', 'Pendapatan mitra ÷ jam online', 'Data platform', 32, 34, 34, 36, .97, $il],
            ['C4.2', 'C4', 'Pedagang terdaftar', 'juta', 'higher', 'last', 1, false, false, 'mitra', 'Pedagang terdaftar di ekosistem', 'Data platform', 5.8, 6.2, 6.4, 6.9, .98, $lt],
            ['P1.1', 'P1', 'Biaya layanan per pesanan ODS', 'Rp ribu', 'lower', 'average', 1, false, false, 'ods', 'Biaya operasi variabel ODS ÷ jumlah pesanan', 'Akuntansi manajemen', 4.6, 4.3, 4.2, 3.9, 1.03, $il],
            ['P1.2', 'P1', 'Insentif & promosi terhadap GTV ODS', '%', 'lower', 'average', 1, false, false, 'ods', 'Insentif konsumen & mitra ÷ GTV ODS × 100', 'Akuntansi manajemen', 3.0, 2.7, 2.6, 2.4, 1.04, $il],
            ['P2.1', 'P2', 'Pinjaman diputus dengan model skor ekosistem', '%', 'higher', 'average', 0, false, false, 'risiko', 'Pencairan dengan skor kredit berbasis data ekosistem ÷ total pencairan × 100', 'Sistem kredit', 80, 88, 90, 94, .99, $il],
            ['P2.2', 'P2', 'Tingkat penagihan tepat waktu', '%', 'higher', 'average', 1, false, false, 'risiko', 'Angsuran tertagih sesuai jadwal ÷ angsuran jatuh tempo × 100', 'Sistem penagihan', 95.5, 96.0, 96.2, 96.5, .995, $il],
            ['P3.1', 'P3', 'Ketersediaan platform', '%', 'higher', 'average', 2, false, false, 'teknologi', 'Waktu layanan inti tersedia ÷ total waktu × 100', 'Pemantauan SRE', 99.90, 99.95, 99.93, 99.95, .9995, $il],
            ['P3.2', 'P3', 'Durasi gangguan layanan kritis', 'menit', 'lower', 'sum', 0, false, false, 'teknologi', 'Total menit gangguan layanan inti (P1)', 'Laporan insiden', 420, 300, 280, 200, 1.4, $il],
            ['P3.3', 'P3', 'Beban kerja dimigrasikan ke pusat data lokal', '%', 'higher', 'last', 0, false, false, 'teknologi', 'Beban kerja di pusat data lokal ÷ total beban kerja × 100', 'Program migrasi cloud', 20, 60, 65, 100, .9, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan audit selesai sesuai tenggat ÷ jatuh tempo × 100', 'Audit Internal', 75, 85, 87, 92, .97, $il],
            ['P4.2', 'P4', 'Kendaraan listrik dalam ekosistem Gojek', 'ribu', 'higher', 'last', 1, false, false, 'esg', 'Motor & mobil listrik aktif dipakai mitra', 'Program GoRide Electric & GoGreen', 9.0, 15.0, 16.0, 25.0, .84, $il],
            ['L1.1', 'L1', 'Pendapatan per karyawan', 'Rp miliar', 'higher', 'sum', 2, false, false, 'sdm', 'Pendapatan bersih ÷ karyawan tetap', 'HRIS & keuangan', 4.20, 4.80, 5.16, 5.80, .97, $lt],
            ['L1.2', 'L1', 'Karyawan tetap', 'orang', 'lower', 'last', 0, false, false, 'sdm', 'Karyawan tetap akhir perioda', 'HRIS', 3800, 3600, 3553, 3500, 1.0, $lt],
            ['L2.1', 'L2', 'Indeks keterikatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterikatan (skala 100)', 'Survei karyawan', 70, 73, 74, 76, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan OKR/KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 60, 80, 82, 95, .9, $il],
            ['L3.1', 'L3', 'Fitur produk berbasis AI generatif', 'unit', 'higher', 'sum', 0, false, false, 'teknologi', 'Fitur AI (termasuk Sahabat-AI) yang diluncurkan ke pengguna', 'Product', 2, 6, 7, 12, .9, $il],
            ['L3.2', 'L3', 'Kontak layanan pelanggan diselesaikan asisten AI', '%', 'higher', 'average', 0, false, false, 'teknologi', 'Kontak selesai tanpa agen manusia ÷ total kontak × 100', 'Sistem layanan pelanggan', 40, 50, 52, 60, .95, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat GoTo, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama & CEO Grup', 'venue' => $venue, 'participants' => 'Direksi, presiden unit bisnis, Strategy',
                'summary' => "GTV pro forma Rp685,6 T (+32%); pendapatan bersih Rp18,3 T.\nEBITDA disesuaikan Rp2,0 T (2024: Rp312 M), melampaui panduan yang sudah dinaikkan.\nFintech: EBITDA disesuaikan Rp497 M (2024: −Rp467 M); buku pinjaman Rp8,8 T.\nRugi tahun berjalan Rp1,50 T (2024: Rp5,46 T).\nKas Rp21,8 T.",
                'decisions' => "1. Target 2026: EBITDA disesuaikan Rp3,3 T, rugi ≤ Rp600 M, buku pinjaman Rp12,5 T dengan NPL ≤ 3%.\n2. ODS: produk per segmen; fintech: pengguna & kredit sehat.\n3. KPI unit diselaraskan ke Peta Strategi grup melalui Ruci BSC.",
                'follow_up' => 'Strategy menurunkan KPI 2026 ke setiap unit paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta pertumbuhan buku pinjaman tidak mendahului kesiapan penagihan, dan rencana jelas menuju laba bersih.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama & CEO Grup', 'venue' => $venue, 'participants' => 'Direksi, presiden unit bisnis, Strategy',
                'summary' => match ($m) {
                    3 => 'Lebaran (20 Maret): GoSend & GoFood puncak; GTV ODS +9% yoy.',
                    4 => 'Gangguan layanan 95 menit saat pemindahan klaster pembayaran.',
                    5 => 'Buku pinjaman Rp10,1 T; NPL 2,8%.',
                    6 => 'Semester I: EBITDA disesuaikan 96% target; rugi menyempit.',
                    7 => 'Migrasi cloud 60%; dua insiden kecil.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Pertahankan promosi hanya di kota dengan ROI positif.',
                    4 => 'Bekukan perubahan produksi saat jam sibuk; wajibkan rencana rollback.',
                    5 => 'Batasi plafon pinjaman baru untuk pengguna < 6 bulan.',
                    6 => 'Percepat program biaya per pesanan semester II.',
                    7 => 'Audit keandalan oleh tim SRE independen.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama & CEO Grup', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Strategy',
                'summary' => $q === 1 ? 'EBITDA disesuaikan Q1 Rp690 M; ATU GoPay 60 juta.' : 'EBITDA disesuaikan H1 96% target; gangguan layanan kritis sudah 210 menit (batas setahun 200).',
                'decisions' => $q === 1 ? 'Panduan EBITDA 2026 dipertahankan.' : 'Program keandalan menjadi prioritas Direksi; laporan insiden mingguan.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti gangguan layanan selama migrasi cloud dan kualitas kredit di tengah pertumbuhan pinjaman.',
            'decisions' => 'Direksi melaporkan insiden, NPL, dan progres migrasi setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon kajian dampak gangguan layanan terhadap pengguna dan pedagang, serta kompensasi yang diberikan.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama & CEO Grup', 'venue' => $venue, 'participants' => 'Direksi, presiden unit bisnis, Strategy',
            'summary' => 'Realisasi September sebagian masih draf; buku pinjaman dikembalikan untuk penyesuaian hapus buku.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi untuk demo; tidak diungkap per program di laporan tahunan.';

            return [
                'defs' => [
                    'cloud' => ['GOTO-25-01', 'Migrasi cloud ke pusat data lokal (tahap 1)', 'Bersama mitra cloud lokal (Juni 2025).', 'T4', 'teknologi', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 4, 2, ['P3', 'F3'], '60% beban kerja dimigrasi.'],
                    'kredit' => ['GOTO-25-02', 'Ekspansi GoPay Later & GoPay Pinjam', 'Pinjaman ke pengguna dengan riwayat transaksi.', 'T1', 'fintech', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 4, 2, ['C2'], 'Buku pinjaman Rp8,8 T (+68%).'],
                    'hemat' => ['GOTO-25-03', 'GoRide Hemat & GoCar Hemat', 'Produk pasar massal dengan harga terprediksi.', 'T2', 'ods', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C3'], 'Porsi pesanan hemat 27%.'],
                    'ev' => ['GOTO-25-04', 'GoRide Electric & GoGreen', 'Motor listrik mitra & taksi listrik GoGreen.', 'T4', 'esg', '2025-03-01', '2025-12-31', 'selesai', 3, 3, 3, 3, ['P4'], '16 ribu kendaraan listrik.'],
                    'ai' => ['GOTO-25-05', 'Sahabat-AI', 'LLM bahasa Indonesia untuk produk & layanan.', 'T4', 'teknologi', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 3, 2, ['L3'], '7 fitur AI.'],
                ],
                'activities' => [
                    ['cloud', 'Pemindahan layanan non-kritis', 'teknologi', '2025-01-01', '2025-06-30', 100], ['cloud', 'Pemindahan layanan pembayaran tahap 1', 'teknologi', '2025-06-01', '2025-12-31', 100],
                    ['kredit', 'Model skor kredit ekosistem v2', 'risiko', '2025-01-01', '2025-05-31', 100], ['kredit', 'Perluasan ke pedagang', 'fintech', '2025-04-01', '2025-12-31', 100],
                    ['hemat', 'Peluncuran di 50 kota', 'ods', '2025-01-01', '2025-12-31', 100],
                    ['ev', 'Kemitraan pembiayaan motor listrik', 'esg', '2025-03-01', '2025-12-31', 100],
                    ['ai', 'Pengembangan & peluncuran Sahabat-AI', 'teknologi', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['cloud', 'Pusat data lokal beroperasi', '2025-06-30', true], ['ev', 'GoGreen diluncurkan', '2025-06-30', true], ['kredit', 'Buku pinjaman Rp8 T', '2025-11-30', true],
                ],
                'budgets' => [
                    ['cloud', 'Migrasi & infrastruktur pusat data', 'capex', 650_000_000_000, 590_000_000_000, $note, 'Kas internal'],
                    ['kredit', 'Pengembangan model risiko & penagihan', 'opex', 120_000_000_000, 108_000_000_000, $note],
                    ['hemat', 'Peluncuran & komunikasi produk', 'opex', 90_000_000_000, 84_000_000_000, $note],
                    ['ev', 'Program kendaraan listrik mitra', 'opex', 60_000_000_000, 52_000_000_000, $note],
                    ['ai', 'Komputasi & pengembangan AI', 'capex', 110_000_000_000, 97_000_000_000, $note, 'Kas internal'],
                ],
            ];
        }

        return [
            'defs' => [
                'kredit' => ['GOTO-26-01', 'Pertumbuhan pinjaman berkualitas', 'Plafon berbasis data ekosistem; pinjaman pedagang.', 'T1', 'fintech', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 2, ['C2', 'F1'], 'Buku pinjaman Rp12,5 T; NPL ≤ 3%.'],
                'gopay' => ['GOTO-26-02', 'GoPay di setiap pesanan & QRIS', 'Lintas-jual GoPay ke pengguna Gojek; penerimaan QRIS.', 'T1', 'fintech', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C1'], 'ATU GoPay 68 juta.'],
                'segmen' => ['GOTO-26-03', 'Produk ODS per segmen', 'Premium & hemat; langganan GoClub.', 'T2', 'ods', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C3'], 'GTV ODS Rp70 T.'],
                'biaya' => ['GOTO-26-04', 'Program cost to serve', 'Otomasi operasi, routing, dan insentif cerdas.', 'T3', 'ods', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 3, 1, ['P1', 'F2'], 'Biaya per pesanan Rp3,9 ribu.'],
                'cloud' => ['GOTO-26-05', 'Migrasi cloud tahap 2', 'Layanan pembayaran & pesanan inti ke pusat data lokal.', 'T4', 'teknologi', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 5, 2, ['P3'], '100% beban kerja di pusat data lokal.'],
                'keandalan' => ['GOTO-26-06', 'Program keandalan & ketahanan siber', 'SRE, chaos testing, rollback otomatis.', 'T4', 'teknologi', '2026-05-01', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['P3'], 'Gangguan kritis ≤ 200 menit/tahun.'],
                'mitra' => ['GOTO-26-07', 'Kesejahteraan mitra pengemudi', 'Pendapatan per jam, asuransi, pelatihan.', 'T2', 'mitra', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C4'], 'Pendapatan mitra Rp36 ribu/jam.'],
                'ev' => ['GOTO-26-08', 'Kendaraan listrik mitra', 'Pembiayaan motor listrik & penukaran baterai.', 'T4', 'esg', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['P4'], '25 ribu kendaraan listrik.'],
                'ai' => ['GOTO-26-09', 'AI untuk layanan pelanggan & mitra', 'Asisten AI di aplikasi pengguna, mitra, dan pedagang.', 'T4', 'teknologi', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 3, 2, ['L3', 'P1'], '60% kontak diselesaikan AI.'],
                'kinerja' => ['GOTO-26-10', 'OKR terhubung & budaya pemilik', 'OKR unit diturunkan dari Peta Strategi grup.', 'T3', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 1, ['L2', 'L1'], '95% karyawan dengan KPI terhubung.'],
            ],
            'activities' => [
                ['kredit', 'Model skor v3 dengan data pedagang', 'risiko', '2026-01-01', '2026-04-30', 100], ['kredit', 'Pinjaman modal kerja pedagang', 'fintech', '2026-03-01', '2026-12-31', 60],
                ['gopay', 'Kampanye GoPay di checkout Gojek', 'fintech', '2026-01-01', '2026-06-30', 100], ['gopay', 'Perluasan penerimaan QRIS', 'fintech', '2026-03-01', '2026-12-31', 70],
                ['segmen', 'Langganan GoClub baru', 'ods', '2026-01-01', '2026-05-31', 100], ['segmen', 'Perluasan produk hemat ke 30 kota', 'ods', '2026-04-01', '2026-12-31', 65],
                ['biaya', 'Routing & batching pesanan', 'ods', '2026-01-15', '2026-07-31', 100], ['biaya', 'Insentif berbasis model', 'ods', '2026-05-01', '2026-12-31', 55],
                ['cloud', 'Pemindahan klaster pembayaran', 'teknologi', '2026-01-01', '2026-06-30', 100], ['cloud', 'Pemindahan layanan pesanan inti', 'teknologi', '2026-06-01', '2026-12-31', 45],
                ['keandalan', 'Audit SRE independen', 'teknologi', '2026-05-01', '2026-08-31', 100], ['keandalan', 'Rollback otomatis & chaos testing', 'teknologi', '2026-07-01', '2026-12-31', 40],
                ['mitra', 'Asuransi kecelakaan mitra', 'mitra', '2026-01-15', '2026-06-30', 100], ['mitra', 'Pelatihan & sertifikasi mitra', 'mitra', '2026-03-01', '2026-12-31', 60],
                ['ev', 'Penukaran baterai di 3 kota', 'esg', '2026-02-01', '2026-12-31', 55],
                ['ai', 'Asisten AI di aplikasi mitra', 'teknologi', '2026-01-15', '2026-07-31', 100], ['ai', 'Asisten AI untuk pedagang', 'teknologi', '2026-06-01', '2026-12-31', 35],
                ['kinerja', 'OKR turunan Peta Strategi', 'sdm', '2026-01-15', '2026-03-31', 100], ['kinerja', 'Tinjauan kinerja semesteran', 'sdm', '2026-06-01', '2026-12-31', 60],
            ],
            'milestones' => [
                ['cloud', 'Klaster pembayaran di pusat data lokal', '2026-06-30', true], ['cloud', 'Migrasi 100%', '2026-12-31', false], ['keandalan', 'Audit SRE selesai', '2026-08-31', true],
                ['kredit', 'Buku pinjaman Rp10 T', '2026-06-30', true], ['segmen', 'GoClub baru diluncurkan', '2026-05-31', true], ['kinerja', 'OKR seluruh unit disetujui', '2026-03-31', true],
            ],
            'budgets' => [
                ['kredit', 'Model risiko & penagihan', 'opex', 140_000_000_000, 96_000_000_000, null],
                ['gopay', 'Kampanye & insentif GoPay', 'opex', 300_000_000_000, 214_000_000_000, null],
                ['segmen', 'Peluncuran produk & langganan', 'opex', 180_000_000_000, 121_000_000_000, null],
                ['biaya', 'Pengembangan sistem routing & insentif', 'capex', 70_000_000_000, 49_000_000_000, null, 'Kas internal'],
                ['cloud', 'Infrastruktur pusat data lokal', 'capex', 520_000_000_000, 318_000_000_000, null, 'Kas internal'],
                ['keandalan', 'SRE & ketahanan siber', 'opex', 60_000_000_000, 31_000_000_000, null],
                ['mitra', 'Asuransi & pelatihan mitra', 'opex', 150_000_000_000, 102_000_000_000, null],
                ['ev', 'Penukaran baterai & subsidi pembiayaan', 'opex', 80_000_000_000, 41_000_000_000, null],
                ['ai', 'Komputasi & pengembangan AI', 'capex', 130_000_000_000, 84_000_000_000, null, 'Kas internal'],
                ['kinerja', 'Program OKR & budaya', 'opex', 5_000_000_000, 3_400_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Rugi bersih masih Rp1,5 T', 'EBITDA disesuaikan positif, tetapi kompensasi berbasis saham, penyusutan, dan penurunan nilai goodwill Rp357 M membuat rugi bersih.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F2.2', null, 'komisaris', '2026-02-24',
                [['Peta jalan menuju laba bersih', 'cfo', '2026-06-30', 'selesai', 100]], ['2026-07-15', 'Peta jalan laba bersih disetujui Direksi.']],
            ['Pertumbuhan GTV ODS lambat', 'GTV ODS +6%; promosi pesaing di kota besar.', 'tinjauan', 'tahunan', 'pelanggan', 'sedang', null, 'C3.1', 'hemat', 'ods', '2026-02-24',
                [['Produk per segmen & GoClub baru', 'ods', '2026-05-31', 'selesai', 100]], null],
        ] : [
            ['Gangguan layanan kritis melampaui batas', '210 menit per Juni vs batas setahun 200 menit; dua insiden saat migrasi klaster pembayaran.', 'timwas', 'boc', 'proses', 'kritis', null, 'P3.2', 'keandalan', 'komisaris', '2026-07-28',
                [['Audit SRE independen', 'teknologi', '2026-08-31', 'selesai', 100], ['Rollback otomatis untuk semua layanan inti', 'teknologi', '2026-11-30', 'berjalan', 40]], null],
            ['Migrasi cloud tertinggal', '65% vs rencana 75% per Agustus; layanan pesanan inti ditunda setelah insiden.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P3.3', 'cloud', 'teknologi', '2026-07-22',
                [['Jadwal ulang migrasi di luar jam sibuk', 'teknologi', '2026-12-31', 'berjalan', 45]], null],
            ['Armada listrik di bawah target', '16,8 ribu vs 20 ribu; infrastruktur penukaran baterai terbatas.', 'internal', 'rm7', 'proses', 'sedang', null, 'P4.2', 'ev', 'esg', '2026-08-12',
                [['Kemitraan stasiun penukaran baterai', 'esg', '2026-11-30', 'berjalan', 50]], null],
            ['Rugi bersih belum sesuai rencana', 'Rugi YTD 115% dari batas; biaya migrasi ganda (cloud lama & baru).', 'tinjauan', 'q2', 'keuangan', 'tinggi', null, 'F2.2', 'biaya', 'cfo', '2026-07-22',
                [['Hentikan kontrak cloud lama lebih awal', 'teknologi', '2026-12-31', 'berjalan', 30]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Kapasitas pusat data mitra lokal', 'eksternal', 'cloud', 'P3', 'Penyedia cloud lokal', 'Migrasi butuh kapasitas & SLA mitra.', 'teknologi', 'tinggi', 'Kontrak SLA & uji beban bersama.'],
                ['Pendanaan pinjaman dari bank mitra', 'eksternal', 'kredit', 'C2', 'Bank & lembaga pembiayaan', 'Pertumbuhan buku pinjaman membutuhkan dana mitra.', 'fintech', 'sedang', 'Diversifikasi mitra pendanaan.'],
                ['Ketentuan tarif & potongan aplikasi', 'eksternal', 'mitra', 'C4', 'Kementerian Perhubungan', 'Perubahan tarif memengaruhi pendapatan mitra & ODS.', 'ods', 'tinggi', 'Simulasi skenario tarif & dialog regulator.'],
                ['Stasiun penukaran baterai', 'eksternal', 'ev', 'P4', 'Mitra energi', 'Adopsi motor listrik bergantung pada infrastruktur.', 'esg', 'sedang', 'Kemitraan multi-operator.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Komite kredit mingguan', 'rapat', 'Kualitas kredit, plafon, dan penagihan.', 'Mingguan', 'risiko', 'melembaga'],
                ['Tinjauan insiden (post-mortem tanpa menyalahkan)', 'sop', 'Setiap insiden P1 dianalisis dan ditindaklanjuti.', 'Per insiden', 'teknologi', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan panduan publik.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Forum aspirasi mitra', 'rapat', 'Pertemuan rutin dengan perwakilan mitra pengemudi.', 'Bulanan', 'mitra', 'berjalan'],
                ['Budaya "do more with less"', 'budaya', 'Prioritas, efisiensi, dan berpikir seperti pemilik.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
