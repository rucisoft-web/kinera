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
 * Demo PT Telkom Indonesia (Persero) Tbk (kode TLKM) — BUMN telekomunikasi digital terbesar.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (pendapatan per segmen, EBITDA, laba, capex, pelanggan seluler & IndiHome, BTS/5G, fiber, ESG, SDM).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI; target disusun dari panduan publik.
 * - Kasus belajar utama: transformasi korporasi besar (TLKM 30) — dari operating holding ke strategic holding,
 *   memulihkan pangsa pasar B2C sambil membuka nilai aset infrastruktur.
 */
class TelkomSeeder extends ShowcaseSeeder
{
    public const CODE = 'TLKM';

    public const LT = 'Laporan Tahunan 2025 PT Telkom Indonesia (Persero) Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Telkom Indonesia (Persero) Tbk', 'legal_name' => 'PT Telkom Indonesia (Persero) Tbk',
            'industry' => 'Telekomunikasi digital: seluler, broadband, B2B ICT, infrastruktur digital, internasional',
            'address' => 'Telkom Landmark Tower, Jl. Jend. Gatot Subroto Kav. 52', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 521 5109', 'website' => 'https://www.telkom.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham TLKM). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Trafik & pendapatan naik saat Ramadan/Lebaran; pendapatan B2B memuncak di akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.1, 7.8, 8.6, 8.4, 8.2, 8.2, 8.3, 8.3, 8.2, 8.4, 8.5, 9.0]),
            2026 => self::weights([1 => 8.1, 8.4, 8.6, 8.0, 8.2, 8.2, 8.3, 8.3, 8.2, 8.4, 8.4, 8.9]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.4', 'F2.2', 'F3.1', 'F3.2', 'C1.3', 'C4.1', 'C4.2', 'P1.2', 'P2.2', 'P4.1', 'P4.2', 'P4.3', 'L1.2', 'L2.1'];
    }

    protected function missingSeptember(): array
    {
        return ['C3.2', 'L3.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C1.2', 'ARPU September masih memakai jumlah pelanggan sebelum pembersihan kartu tidak aktif. Mohon hitung ulang dengan basis pelanggan terbaru.'];
    }

    protected function criticalIn2026(): array
    {
        return ['C2.1'];
    }

    protected function maturityPattern(): array
    {
        return [3, 4, 3, 3, 4];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Telkom (contoh)', 'tlkm.admin', 'Admin Sistem', 'Strategic Planning', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'tlkm.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan & Manajemen Risiko (contoh)', 'tlkm.keuangan', 'Direktur Keuangan & Manajemen Risiko', 'Keuangan & Manajemen Risiko', ['keuangan']],
            'b2c' => ['Direktur Utama Telkomsel (contoh)', 'tlkm.b2c', 'Direktur Utama Telkomsel (B2C Terintegrasi)', 'B2C (Seluler & IndiHome)', ['kepala-unit']],
            'b2b' => ['Direktur Enterprise & Business Service (contoh)', 'tlkm.b2b', 'Direktur Enterprise & Business Service', 'B2B ICT', ['kepala-unit']],
            'wholesale' => ['Direktur Wholesale & International Service (contoh)', 'tlkm.wholesale', 'Direktur Wholesale & International Service', 'Wholesale & Internasional', ['kepala-unit']],
            'jaringan' => ['Direktur Network & IT Solution (contoh)', 'tlkm.jaringan', 'Direktur Network & IT Solution', 'Jaringan & TI', ['kepala-unit']],
            'portofolio' => ['Direktur Strategic Portfolio (contoh)', 'tlkm.portofolio', 'Direktur Strategic Portfolio', 'Portofolio Strategis & Infrastruktur Digital', ['kepala-unit']],
            'esg' => ['VP Sustainability & Cyber Security (contoh)', 'tlkm.esg', 'VP Sustainability & Cyber Security', 'Keberlanjutan & Keamanan Siber', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital Management (contoh)', 'tlkm.sdm', 'Direktur Human Capital Management', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Transformation Office TLKM 30 (contoh)', 'tlkm.strategi', 'SVP Corporate Strategy & Transformation Office', 'Strategi & Transformasi', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'tlkm.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'tlkm.analis', 'Performance Analyst', 'Strategi & Transformasi', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi perusahaan telekomunikasi digital kelas dunia yang berdampak besar di tingkat regional, dan membentuk ekosistem digital untuk menciptakan nilai bagi para pemangku kepentingan.',
            'mission' => "1. Bertransformasi menjadi perusahaan telekomunikasi digital terbaik di Indonesia yang berdampak signifikan dalam kancah internasional.\n2. Membangun organisasi dengan tata kelola yang terstandarisasi, agile, kolaboratif, dan berorientasi hasil.\n3. Memperkuat kultur pelayanan yang unggul.",
            'purpose' => 'Membangun bangsa yang lebih sejahtera dan berdaya saing serta memberikan nilai terbaik kepada pemangku kepentingan.',
            'tagline' => 'Menata ulang fondasi untuk membentuk masa depan digital Indonesia',
            'brand_promise' => 'Konektivitas dan layanan digital yang menjangkau lebih dari 97% populasi Indonesia.',
            'value_proposition' => '156,1 juta pelanggan seluler, 10,3 juta pelanggan IndiHome, 293.136 BTS (4.913 BTS 5G), 210.843 km fiber backbone, 44.702 menara, data center, satelit Merah Putih-2.',
            'culture_statement' => 'Nilai inti BUMN AKHLAK dengan Digital Ways of Working: Bravery, Integrity, Service Excellence, Agility.',
            'history' => "1856: layanan telegraf pertama di Batavia.\n1991: menjadi Persero dengan nama PT Telekomunikasi Indonesia.\n1995: tercatat di BEJ, BES, NYSE & LSE.\n2023: integrasi IndiHome ke Telkomsel (Fixed Mobile Convergence).\n2025: transformasi TLKM 30 dicanangkan; RUPSLB 12 Desember menyetujui spin-off bisnis wholesale fiber ke InfraNexia; segmen baru B2C, B2B ICT, B2B Infra, Internasional.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Amanah', 'Integrity', 'Memegang teguh kepercayaan yang diberikan.', "Melaporkan kinerja apa adanya\nMenjaga data pelanggan"],
            ['Kompeten', 'Competent', 'Terus belajar dan mengembangkan kapabilitas.', "Sertifikasi cloud, AI, keamanan siber\nBelajar dari pelanggan"],
            ['Harmonis', 'Harmonious', 'Saling peduli dan menghargai perbedaan.', "Kolaborasi induk–anak usaha\nMenghormati mitra"],
            ['Loyal', 'Loyal', 'Berdedikasi dan mengutamakan kepentingan bangsa dan negara.', "Membangun jaringan di daerah 3T\nMendukung kedaulatan digital"],
            ['Adaptif', 'Agility', 'Terus berinovasi dan antusias menghadapi perubahan.', "Bergerak cepat dengan squad lintas fungsi\nBerani menghentikan produk lama"],
            ['Kolaboratif', 'Bravery & Service Excellence', 'Membangun kerja sama yang sinergis dan berani mengambil keputusan.', "Kemitraan untuk membuka nilai aset\nPelayanan unggul di setiap titik sentuh"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Infrastruktur digital nasional', 'Sumber daya fisik', 'BTS, menara, fiber backbone, kabel laut, satelit, data center.', 5, 5, true, 'Monetisasi melalui kemitraan (unlock value).'],
            ['Basis pelanggan terbesar', 'Kompetensi', '166,4 juta pelanggan seluler + IndiHome.', 4, 5, true, 'Konvergensi FMC & beyond connectivity.'],
            ['Solusi B2B ICT', 'Kompetensi', 'Cloud, data center, keamanan, solusi pemerintah.', 3 + $lvl, 5, true, 'Product & consultative selling.'],
            ['Alokasi modal & manajemen portofolio', 'Sistem', 'Capex 16,7% pendapatan; banyak anak usaha tumpang tindih.', 3 + $lvl, 5, false, 'Strategic holding & streamlining.'],
            ['Visibilitas keuangan end-to-end', 'Sistem', 'Profitabilitas per produk & pelanggan belum penuh.', 2 + $lvl, 4, false, 'E2E financial visibility.'],
            ['Keamanan siber', 'Kompetensi', '99,4% karyawan lulus pelatihan; 0 kebocoran data kritikal.', 4, 5, false, 'Security operations center grup.'],
            ['Budaya digital ways of working', 'Sistem', 'Transisi dari operating ke strategic holding.', 3, 4, false, 'Reformasi budaya & tata kelola.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Industri telekomunikasi 2025', '2026-01-20', 'eksternal', self::LT.' — Tinjauan Industri',
                'Daya beli semester I melemah, konsolidasi operator, persaingan harga paket data & fixed broadband, permintaan data center & AI naik, spektrum 5G mulai diperluas.',
                'Pertumbuhan legacy melambat; nilai baru dari konvergensi, B2B digital, dan infrastruktur.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan Rp146,7 T (−2,2%); EBITDA Rp72,2 T (marjin 49,2%); laba ke pemilik Rp17,8 T (−20,5%); capex Rp24,6 T (16,7% pendapatan). B2C Rp105,9 T (−3,3%); internasional paling solid.',
                'Fondasi ditata ulang; 2026 fokus memulihkan pangsa B2C dan mempercepat B2B.'],
            ['TLKM 30 & panduan 2026', '2026-02-05', 'internal', self::LT.' — Strategi & Prospek',
                'Empat pilar TLKM 30: operational & service excellence, streamlining, unlock value, modus-operandi shift (strategic holding akhir 2027). Panduan 2026: pendapatan tumbuh low–mid single digit, capex 15–20% pendapatan, utang/EBITDA ±0,9x.',
                'Target 2026 dalam demo ini disusun dari panduan tersebut.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Kedaulatan digital & pusat data nasional', 'Kebijakan data & layanan pemerintah digital.', 'peluang', 4, 4, 'naik', 'Data center & solusi pemerintah.'],
            ['politik', 'Tata kelola BUMN di bawah Danantara', 'Penilaian kinerja & alokasi modal BUMN.', 'peluang', 3, 4, 'naik', 'Strategic holding.'],
            ['ekonomi', 'Daya beli & persaingan harga', 'Paket data & broadband makin murah.', 'ancaman', 5, 4, 'stabil', 'Konvergensi & bundling.'],
            ['ekonomi', 'Biaya spektrum & capex 5G', 'Kebutuhan investasi besar.', 'ancaman', 4, 4, 'naik', 'Capex prudent & berbagi jaringan.'],
            ['sosial', 'Rumah tangga digital', 'Streaming, gim, kerja dari rumah.', 'peluang', 4, 5, 'naik', 'FMC & layanan beyond connectivity.'],
            ['teknologi', 'AI & cloud', 'Permintaan GPU & data center hyperscale.', 'peluang', 5, 4, 'naik', 'NeutraDC & kemitraan AI.'],
            ['teknologi', 'Layanan OTT & satelit LEO', 'Mengurangi pendapatan legacy.', 'ancaman', 3, 4, 'naik', 'Kemitraan & produk digital.'],
            ['lingkungan', 'Energi & emisi jaringan', 'Konsumsi listrik BTS & data center.', 'ancaman', 3, 4, 'naik', 'Efisiensi energi & EBT.'],
            ['hukum', 'Pelindungan data pribadi & keamanan siber', 'UU PDP berlaku penuh.', 'ancaman', 5, 4, 'naik', 'Keamanan siber grup.'],
            ['hukum', 'Regulasi pemisahan infrastruktur', 'Spin-off fiber & menara membutuhkan persetujuan.', 'peluang', 3, 3, 'naik', 'InfraNexia & Mitratel.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Operator seluler & ISP hasil konsolidasi', 'Pesaing lebih kuat setelah merger.', 5, 'Kualitas jaringan & konvergensi.'],
            ['kekuatan_pembeli', 'Pelanggan ritel & korporasi', 'Mudah berpindah; korporasi menekan harga.', 4, 'Bundling & solusi konsultatif.'],
            ['kekuatan_pemasok', 'Vendor perangkat jaringan', 'Vendor global terkonsentrasi.', 3, 'Pengadaan grup terpusat.'],
            ['pendatang_baru', 'Hyperscaler & satelit LEO', 'Pemain global masuk pasar data center & internet.', 4, 'Kemitraan & kedaulatan data.'],
            ['produk_pengganti', 'Aplikasi OTT', 'Panggilan & pesan beralih ke aplikasi.', 4, 'Monetisasi data & layanan digital.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['B2C (seluler & IndiHome)', 'B2C', 'Rp105,9 T (−3,3%); 156,1 juta pelanggan seluler, 10,3 juta IndiHome.', 72, 'turun', true, 'Jaringan cepat, harga wajar, layanan rumah tangga digital.', 'FMC & territory management.'],
            ['B2B ICT (korporasi & pemerintah)', 'B2B', 'Rp15,3 T (−3,1%).', 10, 'stabil', true, 'Solusi end-to-end & keamanan.', 'Product & consultative selling.'],
            ['Internasional', 'Internasional', 'Rp10,7 T; segmen paling solid.', 7, 'naik', true, 'Kapasitas kabel laut & layanan global.', 'Perluas kabel & layanan regional.'],
            ['B2B Infra (menara, fiber, data center)', 'Infrastruktur', 'Rp8,9 T (−0,7%).', 6, 'stabil', true, 'Kolokasi & kapasitas terjamin.', 'Unlock value melalui kemitraan.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — segmen usaha baru (Desember 2025)']);
        }

        foreach ([
            ['PT Telkom Indonesia (Persero) Tbk', 'Telekomunikasi BUMN', 1, 146742, -2.2, 12.1, true, 'Jaringan & pelanggan terbesar, infrastruktur digital lengkap.', 'Pendapatan B2C turun; laba −20,5%.'],
            ['Operator seluler hasil merger', 'Telekomunikasi', 2, null, null, null, false, 'Skala baru & efisiensi.', 'Integrasi jaringan.'],
            ['Operator seluler swasta lain', 'Telekomunikasi', 3, null, null, null, false, 'Harga kompetitif.', 'Cakupan luar Jawa.'],
            ['ISP fixed broadband swasta', 'Broadband', 4, null, null, null, false, 'Harga & kecepatan pemasangan.', 'Cakupan terbatas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Penyelenggaraan telekomunikasi & spektrum', 'Kementerian Komunikasi & Digital', 'UU Telekomunikasi & PP Postelsiar', 'Operasi', null, 'Izin jaringan, spektrum, kualitas layanan.', 'Kelangsungan usaha.', 'patuh', 'jaringan', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data ratusan juta pelanggan.', 'Sanksi & kepercayaan.', 'sebagian', 'esg', '2026-12-31'],
            ['Tata kelola BUMN & Danantara', 'Danantara / Kementerian BUMN', 'Ketentuan tata kelola & KPI BUMN', 'Tata kelola', '2025-01-01', 'KPI Direksi & alokasi modal.', 'Evaluasi Direksi.', 'patuh', 'strategi', null],
            ['Pemisahan usaha (spin-off) InfraNexia', 'RUPSLB & OJK', 'Persetujuan RUPSLB 12 Desember 2025', 'Korporasi', '2025-12-12', 'Pemisahan bisnis wholesale fiber.', 'Unlock value.', $year === 2025 ? 'patuh' : 'sebagian', 'portofolio', $year === 2025 ? null : '2026-12-31'],
            ['Sistem manajemen anti-penyuapan', 'Internal / BSN', 'SNI ISO 37001', 'Tata kelola', null, 'Telkom + 12 anak usaha bersertifikat.', 'Integritas.', 'patuh', 'strategi', null],
            ['Keterbukaan informasi & SOX', 'OJK & SEC (ADR NYSE)', 'POJK keterbukaan & ketentuan SEC', 'Tata kelola', null, 'Pelaporan ganda Indonesia–AS.', 'Kepercayaan investor.', 'patuh', 'cfo', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham (Danantara/negara & publik, termasuk ADR)', 'internal', 'Pemilik', 5, 5, 'Pertumbuhan, dividen 60–90%, tata kelola.', 'RUPS & paparan publik.', 'Investor Relations'],
            'pelanggan' => ['Pelanggan seluler & IndiHome', 'eksternal', 'Pelanggan', 4, 5, 'Jaringan cepat & harga wajar.', 'MyTelkomsel, GraPARI, survei NPS.', 'Customer Experience'],
            'korporasi' => ['Pelanggan korporasi & pemerintah', 'eksternal', 'Pelanggan', 4, 4, 'Solusi andal & aman.', 'Account management.', 'Enterprise'],
            'regulator' => ['Komdigi & regulator', 'eksternal', 'Regulator', 5, 4, 'Kualitas layanan & kepatuhan.', 'Pelaporan & konsultasi kebijakan.', 'Regulatory'],
            'mitra' => ['Mitra strategis (hyperscaler, investor infrastruktur)', 'eksternal', 'Mitra', 4, 4, 'Kemitraan yang membuka nilai aset.', 'Negosiasi kemitraan.', 'Strategic Portfolio'],
            'karyawan' => ['Karyawan (21.151 orang)', 'internal', 'SDM', 4, 5, 'Kepastian peran di masa transformasi.', 'Town hall TLKM 30.', 'Human Capital'],
            'vendor' => ['Vendor & pemasok lokal', 'eksternal', 'Pemasok', 3, 4, 'Pembayaran tepat waktu.', 'Pengadaan grup.', 'Procurement'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'b2c' => ['Pangsa pasar B2C menurun', 'Pelanggan seluler 156,1 juta (159,4 juta); pendapatan B2C −3,3%.', 'pelanggan', 5, 5, 'b2c', ['pelanggan']],
            'laba' => ['Laba turun 20,5%', 'Laba ke pemilik Rp17,8 T; marjin usaha 23,6% (27,6%).', 'keuangan', 5, 5, 'cfo', ['pemegang_saham']],
            'b2b' => ['B2B ICT melambat', 'Pendapatan B2B ICT −3,1%; penjualan masih berbasis produk.', 'pelanggan', 4, 4, 'b2b', ['korporasi']],
            'aset' => ['Nilai aset infrastruktur belum terbuka', 'Menara, fiber, data center tercampur dalam grup.', 'proses', 4, 5, 'portofolio', ['mitra', 'pemegang_saham']],
            'portofolio' => ['Anak usaha tumpang tindih', 'Bisnis tumpang tindih & non-core perlu dirapikan.', 'proses', 3, 4, 'strategi', ['pemegang_saham']],
            'budaya' => ['Budaya & tata kelola strategic holding', 'Pergeseran peran induk dari operating ke strategic.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Infrastruktur digital terlengkap', 'BTS, fiber, menara, kabel laut, satelit, data center.', 0.15, 4],
            ['S', 'Basis pelanggan terbesar', '166,4 juta pelanggan.', 0.12, 4],
            ['S', 'Marjin EBITDA tinggi', '49,2%.', 0.08, 4],
            ['S', 'Peringkat idAAA', 'Utang/EBITDA 1,0x.', 0.05, 4],
            ['W', 'Pendapatan legacy menurun', 'B2C −3,3%.', 0.12, 1],
            ['W', 'Portofolio anak usaha kompleks', 'Tumpang tindih bisnis.', 0.06, 2],
            ['W', 'Visibilitas profit per produk', 'E2E financial visibility belum penuh.', 0.04, 2],
            ['O', 'Data center & AI', 'Permintaan hyperscale.', 0.10, 3],
            ['O', 'Konvergensi FMC', 'Bundling seluler + IndiHome.', 0.08, 3],
            ['O', 'Monetisasi infrastruktur', 'Spin-off fiber & kemitraan.', 0.06, 3],
            ['T', 'Persaingan operator hasil merger', 'Perang harga.', 0.07, 2],
            ['T', 'OTT & satelit LEO', 'Substitusi layanan.', 0.04, 2],
            ['T', 'Serangan siber', 'Risiko kebocoran data.', 0.03, 3],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Konvergensi FMC & beyond connectivity', 'Bundling seluler, IndiHome, dan layanan digital per wilayah.', 5, ['b2c']],
            ['SO', 'Data center & cloud untuk AI', 'Manfaatkan lahan, listrik, & fiber Telkom.', 4, ['aset']],
            ['WO', 'Unlock value: InfraNexia & kemitraan menara/data center', 'Buka nilai aset melalui pemisahan & mitra strategis.', 5, ['aset', 'laba']],
            ['WO', 'Consultative selling B2B', 'Solusi end-to-end per industri.', 4, ['b2b']],
            ['ST', 'Kualitas jaringan & 5G terarah', 'Capex prudent di area bernilai tinggi.', 4, ['b2c']],
            ['WT', 'Streamlining & strategic holding', 'Konsolidasi anak usaha; induk fokus alokasi modal.', 4, ['portofolio', 'budaya']],
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
            'T1' => ['Regain B2C & Konvergensi', 'Pangsa pasar B2C pulih lewat FMC & territory management.', '#E4002B'],
            'T2' => ['Akselerasi B2B & Internasional', 'Consultative selling, data center, layanan regional.', '#1E6FA8'],
            'T3' => ['Unlock Value & Disiplin Modal', 'Monetisasi menara, fiber, data center; capex prudent.', '#E08A1E'],
            'T4' => ['Excellence & Strategic Holding', 'Streamlining, budaya, tata kelola, keamanan siber.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Konvergensi sebagai proposisi utama B2C', 'produk', 'FMC offerings & beyond connectivity.'],
            ['T2', 'Menjual solusi, bukan produk', 'saluran', 'Product & consultative selling B2B.'],
            ['T3', 'Pisahkan infrastruktur untuk dibuka nilainya', 'organisasi', 'Spin-off InfraNexia (RUPSLB 12 Desember 2025).'],
            ['T4', 'Induk sebagai strategic holding (akhir 2027)', 'organisasi', 'Modus-operandi shift dalam TLKM 30.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pendapatan tumbuh kembali', 'Pendapatan tumbuh low–mid single digit (±Rp151 T).', 'dirut', 2],
            'F2' => ['keuangan', 'T4', 'Profitabilitas pulih', 'Marjin EBITDA terjaga; laba ke pemilik naik.', 'cfo', 3],
            'F3' => ['keuangan', 'T3', 'Disiplin modal & neraca sehat', 'Capex 15–20% pendapatan; utang/EBITDA ±0,9x.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Pangsa pasar B2C pulih', 'Pelanggan seluler & IndiHome naik; ARPU terjaga.', 'b2c', 2],
            'C2' => ['pelanggan', 'T1', 'Rumah tangga konvergen', 'Pelanggan FMC tumbuh.', 'b2c', 1.5],
            'C3' => ['pelanggan', 'T2', 'Mitra digital korporasi & global', 'Data center & proyek B2B besar.', 'b2b', 1.5],
            'C4' => ['pelanggan', 'T4', 'Pengalaman pelanggan unggul', 'NPS naik; gangguan cepat selesai.', 'b2c', 1],
            'P1' => ['proses', 'T3', 'Jaringan efisien & berkualitas', '5G terarah; fiber; biaya jaringan terkendali.', 'jaringan', 2],
            'P2' => ['proses', 'T3', 'Monetisasi aset infrastruktur', 'InfraNexia; tenancy menara naik.', 'portofolio', 2],
            'P3' => ['proses', 'T4', 'Streamlining portofolio', 'Anak usaha dikonsolidasi/didivestasi.', 'strategi', 1],
            'P4' => ['proses', 'T4', 'Keamanan siber, tata kelola & ESG', 'Pelatihan siber; emisi turun; audit tuntas.', 'esg', 1],
            'L1' => ['sdm', 'T4', 'Organisasi produktif', 'Pendapatan per karyawan naik.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya AKHLAK & digital ways of working', 'Indeks budaya & KPI terhubung.', 'sdm', 1],
            'L3' => ['sdm', 'T2', 'Talenta digital & AI', 'Talenta tersertifikasi; AI di operasi.', 'sdm', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P3', 2], ['L2', 'L1', 2], ['L3', 'C3', 2], ['L3', 'P1', 2], ['L2', 'P4', 1],
            ['P1', 'C1', 3], ['P1', 'C4', 2], ['P1', 'F2', 2], ['P2', 'F3', 3], ['P3', 'F2', 2], ['P4', 'C4', 1],
            ['C1', 'F1', 3], ['C2', 'C1', 3], ['C3', 'F1', 2], ['C4', 'C1', 2],
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
            ['F1.1', 'F1', 'Pendapatan konsolidasian', 'Rp miliar', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan konsolidasian (panduan 2026: tumbuh low–mid single digit)', 'Laporan keuangan konsolidasian', 149967, 152000, 146742, 151000, .97, $lt],
            ['F1.2', 'F1', 'Pendapatan segmen B2C', 'Rp miliar', 'higher', 'sum', 0, true, false, 'b2c', 'Pendapatan seluler & IndiHome', 'Laporan segmen', 109520, 110000, 105900, 108500, .965, $lt],
            ['F1.3', 'F1', 'Pendapatan segmen B2B ICT', 'Rp miliar', 'higher', 'sum', 0, true, false, 'b2b', 'Pendapatan solusi ICT korporasi & pemerintah', 'Laporan segmen', 15790, 16500, 15300, 16200, .92, $lt],
            ['F1.4', 'F1', 'Pendapatan segmen internasional', 'Rp miliar', 'higher', 'sum', 0, true, false, 'wholesale', 'Pendapatan Telin & layanan internasional', 'Laporan segmen', 9900, 10500, 10700, 11800, 1.0, $lt],
            ['F2.1', 'F2', 'EBITDA', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba usaha + depresiasi & amortisasi', 'Laporan keuangan', 75029, 76000, 72240, 74000, .97, $lt],
            ['F2.2', 'F2', 'Marjin EBITDA', '%', 'higher', 'average', 1, true, false, 'cfo', 'EBITDA ÷ pendapatan × 100', 'Rasio keuangan', 50.0, 50.0, 49.2, 49.5, .99, $lt],
            ['F2.3', 'F2', 'Laba yang dapat diatribusikan ke pemilik', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba bersih bagian pemilik entitas induk', 'Laporan laba rugi', 22403, 23000, 17814, 20000, .9, $lt],
            ['F3.1', 'F3', 'Utang terhadap EBITDA', 'kali', 'lower', 'last', 1, true, false, 'cfo', 'Utang berbunga ÷ EBITDA 12 bulan (panduan 2026 ±0,9x)', 'Rasio keuangan', 0.9, 1.0, 1.0, 0.9, 1.0, $lt],
            ['F3.2', 'F3', 'Capex untuk bisnis inti & next core', '%', 'higher', 'average', 0, true, false, 'cfo', 'Capex ke inti & next core ÷ total capex × 100', 'Laporan capex', 80, 85, 86, 90, .97, $il],
            ['F3.3', 'F3', 'Arus kas bebas', 'Rp miliar', 'higher', 'sum', 0, true, false, 'cfo', 'Arus kas operasi − capex', 'Laporan arus kas (ilustrasi)', 25000, 26000, 24500, 26000, .93, $il],
            ['C1.1', 'C1', 'Pelanggan seluler', 'juta', 'higher', 'last', 1, false, true, 'b2c', 'Pelanggan prabayar + pascabayar', 'Data pelanggan', 159.4, 160.0, 156.1, 158.0, .99, $lt],
            ['C1.2', 'C1', 'ARPU seluler', 'Rp ribu', 'higher', 'average', 0, false, false, 'b2c', 'Pendapatan seluler ÷ rata-rata pelanggan ÷ bulan', 'Laporan B2C (ilustrasi)', 45, 46, 45, 47, .97, $il],
            ['C1.3', 'C1', 'Pelanggan IndiHome', 'juta', 'higher', 'last', 1, false, false, 'b2c', 'Pelanggan fixed broadband B2C', 'Data pelanggan', 9.6, 10.2, 10.3, 11.0, .97, $lt],
            ['C2.1', 'C2', 'Pelanggan konvergen (FMC)', 'juta', 'higher', 'last', 1, false, false, 'b2c', 'Rumah tangga berlangganan paket seluler + IndiHome', 'Data pelanggan (ilustrasi)', 2.0, 3.0, 3.2, 4.5, .78, $il],
            ['C3.1', 'C3', 'Pendapatan data center', 'Rp miliar', 'higher', 'sum', 0, true, false, 'portofolio', 'Pendapatan kolokasi & cloud', 'Laporan anak usaha (ilustrasi)', 1700, 2000, 2100, 2500, .95, $il],
            ['C3.2', 'C3', 'Proyek B2B bernilai > Rp100 miliar dimenangkan', 'unit', 'higher', 'sum', 0, false, false, 'b2b', 'Kontrak baru bernilai di atas Rp100 miliar', 'Sistem penjualan', 20, 25, 23, 28, .86, $il],
            ['C4.1', 'C4', 'Net Promoter Score', 'skor', 'higher', 'last', 0, false, false, 'b2c', '% promotor − % detraktor', 'Survei NPS', 58, 60, 63, 65, 1.0, $lt, 'semesteran'],
            ['C4.2', 'C4', 'Gangguan IndiHome diselesaikan < 24 jam', '%', 'higher', 'average', 1, false, false, 'jaringan', 'Tiket gangguan selesai < 24 jam ÷ total × 100', 'Sistem assurance', 85.0, 90.0, 90.5, 93.0, .98, $il],
            ['P1.1', 'P1', 'BTS 5G beroperasi', 'unit', 'higher', 'last', 0, false, false, 'jaringan', 'BTS 5G on air', 'Data jaringan', 975, 3000, 4913, 8000, .9, $lt],
            ['P1.2', 'P1', 'Fiber optik backbone', 'km', 'higher', 'last', 0, false, false, 'jaringan', 'Panjang kabel fiber backbone (domestik + internasional)', 'Data jaringan', 177443, 200000, 210843, 225000, .99, $lt],
            ['P1.3', 'P1', 'Biaya operasi & pemeliharaan jaringan terhadap pendapatan', '%', 'lower', 'average', 1, false, false, 'jaringan', 'Beban O&M jaringan ÷ pendapatan × 100', 'Akuntansi biaya', 24.0, 24.0, 25.0, 24.0, 1.03, $il],
            ['P2.1', 'P2', 'Kemajuan pemisahan InfraNexia', '%', 'higher', 'last', 0, false, true, 'portofolio', 'Tonggak spin-off tercapai ÷ total tonggak × 100', 'PMO InfraNexia', 0, 40, 45, 100, .88, $il],
            ['P2.2', 'P2', 'Tenancy ratio menara', 'kali', 'higher', 'last', 2, false, false, 'portofolio', 'Jumlah penyewa ÷ jumlah menara', 'Laporan anak usaha menara', 1.50, 1.55, 1.55, 1.60, .99, $il],
            ['P3.1', 'P3', 'Anak usaha dikonsolidasi atau didivestasi (kumulatif)', 'unit', 'higher', 'last', 0, false, false, 'strategi', 'Entitas yang selesai digabung/dilepas sejak TLKM 30', 'Transformation Office', 2, 5, 4, 6, .85, $il],
            ['P4.1', 'P4', 'Karyawan lulus pelatihan keamanan siber', '%', 'higher', 'last', 1, false, false, 'esg', 'Karyawan lulus ÷ karyawan wajib × 100', 'Learning management system', 98.0, 99.0, 99.4, 99.5, 1.0, $lt],
            ['P4.2', 'P4', 'Penurunan emisi cakupan 1 terhadap 2023', '%', 'higher', 'last', 0, false, false, 'esg', '(Emisi 2023 − emisi berjalan) ÷ emisi 2023 × 100', 'Laporan keberlanjutan', 20, 35, 44, 48, .98, $lt],
            ['P4.3', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan audit selesai sesuai tenggat ÷ jatuh tempo × 100', 'Internal Audit', 80, 90, 91, 95, .97, $il],
            ['L1.1', 'L1', 'Pendapatan per karyawan', 'Rp miliar', 'higher', 'sum', 2, false, false, 'sdm', 'Pendapatan ÷ jumlah karyawan', 'HRIS & keuangan', 6.92, 7.10, 6.94, 7.30, .97, $lt],
            ['L1.2', 'L1', 'Jumlah karyawan', 'orang', 'lower', 'last', 0, false, false, 'sdm', 'Karyawan grup akhir perioda', 'HRIS', 21673, 21300, 21151, 20800, 1.0, $lt],
            ['L2.1', 'L2', 'Indeks budaya AKHLAK', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei budaya (skala 100)', 'Survei budaya', 80, 82, 83, 85, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 65, 85, 84, 95, .9, $il],
            ['L3.1', 'L3', 'Talenta digital tersertifikasi', 'orang', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan bersertifikat cloud, AI, data, atau keamanan siber', 'HRIS', 3000, 4000, 4200, 5500, .9, $il],
            ['L3.2', 'L3', 'Kasus penggunaan AI di operasi', 'unit', 'higher', 'sum', 0, false, false, 'jaringan', 'Kasus AI yang dipakai di jaringan, layanan, atau penjualan', 'AI Center of Excellence', 8, 12, 13, 20, .85, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Telkom Landmark Tower, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi anak usaha, Transformation Office',
                'summary' => "Pendapatan Rp146,7 T (−2,2%; 97% target); B2C −3,3%, internasional tumbuh.\nEBITDA Rp72,2 T (marjin 49,2%); laba ke pemilik Rp17,8 T (−20,5%).\nCapex Rp24,6 T (16,7% pendapatan).\nBTS 5G 4.913 (≈ +400%); fiber backbone 210.843 km; NPS 63.\nRUPSLB 12 Desember menyetujui spin-off InfraNexia.",
                'decisions' => "1. Target 2026 mengikuti panduan publik: pendapatan Rp151 T, EBITDA Rp74 T, capex 15–20%.\n2. Prioritas TLKM 30: regain B2C, akselerasi B2B, unlock value, strategic holding.\n3. KPI korporat diturunkan ke anak usaha & individu melalui Ruci BSC.",
                'follow_up' => 'Transformation Office menurunkan KPI 2026 ke setiap anak usaha paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta jadwal rinci InfraNexia dan ukuran keberhasilan konvergensi B2C yang dipantau bulanan.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi anak usaha, Transformation Office',
                'summary' => match ($m) {
                    2 => 'Ramadan: trafik data +18%; jaringan stabil.',
                    3 => 'Lebaran (20 Maret): pelanggan seluler bertambah 1,1 juta.',
                    5 => 'Paket konvergen baru diluncurkan, adopsi lebih lambat dari rencana.',
                    6 => 'Semester I: pendapatan 98% target; laba 89% target.',
                    7 => 'Kajian valuasi InfraNexia selesai; menunggu persetujuan regulator.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Tambah kapasitas di 1.200 lokasi mudik.',
                    3 => 'Program retensi pelanggan pasca-Lebaran.',
                    5 => 'Sederhanakan paket konvergen & tambah kanal penjualan di wilayah.',
                    6 => 'Efisiensi biaya O&M semester II.',
                    7 => 'Siapkan skenario mitra strategis InfraNexia.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Transformation Office',
                'summary' => $q === 1 ? 'Pendapatan Q1 +1,8% yoy; marjin EBITDA 49,6%.' : 'Pendapatan H1 98% target; pelanggan FMC 3,4 juta (target 3,7 juta).',
                'decisions' => $q === 1 ? 'Perluas territory management ke 20 wilayah.' : 'Konvergensi menjadi KPI kritis; pemantauan mingguan.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Komisaris Utama', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti adopsi FMC yang lambat, kemajuan InfraNexia, dan laba yang tertinggal.',
            'decisions' => 'Direksi melaporkan FMC per wilayah dan tonggak InfraNexia setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon analisis apakah paket konvergen kurang menarik dari sisi harga atau karena kendala pemasangan IndiHome di wilayah.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi anak usaha, Transformation Office',
            'summary' => 'Realisasi September sebagian masih draf; ARPU dikembalikan untuk dihitung ulang.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; capex grup 2025 tercatat Rp24,6 T di laporan tahunan.';

            return [
                'defs' => [
                    'tlkm30' => ['TLKM-25-01', 'Perancangan transformasi TLKM 30', 'Empat pilar & peta jalan strategic holding.', 'T4', 'strategi', '2025-03-01', '2025-12-31', 'selesai', 5, 5, 3, 2, ['P3', 'L2'], 'TLKM 30 disetujui.'],
                    'infranexia' => ['TLKM-25-02', 'Persiapan spin-off InfraNexia', 'Pemisahan bisnis wholesale fiber.', 'T3', 'portofolio', '2025-04-01', '2025-12-12', 'selesai', 5, 4, 4, 2, ['P2'], 'RUPSLB menyetujui pada 12 Desember 2025.'],
                    '5g' => ['TLKM-25-03', 'Perluasan 5G', 'BTS 5G di kota besar & kawasan industri.', 'T1', 'jaringan', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 5, ['P1'], '4.913 BTS 5G.'],
                    'fiber' => ['TLKM-25-04', 'Fiber backbone & kabel laut', 'Penambahan backbone domestik & internasional.', 'T2', 'wholesale', '2025-01-01', '2025-12-31', 'selesai', 4, 3, 3, 5, ['P1', 'C3'], 'Backbone 210.843 km.'],
                    'nps' => ['TLKM-25-05', 'Perbaikan pengalaman pelanggan', 'Assurance IndiHome & layanan digital.', 'T4', 'b2c', '2025-01-01', '2025-12-31', 'selesai', 3, 4, 2, 2, ['C4'], 'NPS 63 (+5).'],
                ],
                'activities' => [
                    ['tlkm30', 'Diagnosis & desain pilar', 'strategi', '2025-03-01', '2025-08-31', 100], ['tlkm30', 'Persetujuan Dewan Komisaris', 'strategi', '2025-09-01', '2025-12-31', 100],
                    ['infranexia', 'Pemisahan aset & kajian hukum', 'portofolio', '2025-04-01', '2025-10-31', 100], ['infranexia', 'RUPSLB', 'portofolio', '2025-11-01', '2025-12-12', 100],
                    ['5g', 'Pembangunan BTS 5G', 'jaringan', '2025-01-01', '2025-12-31', 100],
                    ['fiber', 'Penggelaran backbone & kabel laut', 'wholesale', '2025-01-01', '2025-12-31', 100],
                    ['nps', 'Program assurance IndiHome', 'jaringan', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['infranexia', 'RUPSLB menyetujui spin-off', '2025-12-12', true], ['5g', '4.000 BTS 5G', '2025-10-31', true], ['tlkm30', 'TLKM 30 diumumkan', '2025-12-31', true],
                ],
                'budgets' => [
                    ['tlkm30', 'Pendamping transformasi', 'opex', 80_000_000_000, 74_000_000_000, $note],
                    ['infranexia', 'Penasihat transaksi & hukum', 'opex', 60_000_000_000, 55_000_000_000, $note],
                    ['5g', 'Perangkat & pembangunan 5G', 'capex', 3_200_000_000_000, 3_050_000_000_000, $note, 'Kas internal'],
                    ['fiber', 'Fiber backbone & kabel laut', 'capex', 4_500_000_000_000, 4_120_000_000_000, $note, 'Kas internal'],
                    ['nps', 'Program assurance & digital care', 'opex', 150_000_000_000, 138_000_000_000, $note],
                ],
            ];
        }

        return [
            'defs' => [
                'fmc' => ['TLKM-26-01', 'Konvergensi FMC & beyond connectivity', 'Paket seluler + IndiHome + layanan digital per wilayah.', 'T1', 'b2c', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 3, ['C2', 'C1'], '4,5 juta rumah tangga konvergen.'],
                'territory' => ['TLKM-26-02', 'Territory management', 'Target, kanal, dan jaringan dikelola per wilayah.', 'T1', 'b2c', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C1'], 'Pelanggan seluler 158 juta.'],
                'b2b' => ['TLKM-26-03', 'Consultative selling B2B', 'Solusi per industri & visibilitas profit end-to-end.', 'T2', 'b2b', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 3, 2, ['C3', 'F1'], 'Pendapatan B2B ICT Rp16,2 T.'],
                'dc' => ['TLKM-26-04', 'Data center untuk AI', 'Kapasitas hyperscale & kemitraan GPU.', 'T2', 'portofolio', '2026-01-01', '2027-06-30', 'berjalan', 4, 3, 4, 5, ['C3'], 'Pendapatan data center Rp2,5 T.'],
                'infranexia' => ['TLKM-26-05', 'Eksekusi InfraNexia & mitra strategis', 'Pemisahan efektif & mitra investor.', 'T3', 'portofolio', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 2, ['P2', 'F3'], 'InfraNexia beroperasi mandiri.'],
                'capex' => ['TLKM-26-06', 'Capex & pengadaan grup', 'Prioritas inti & next core; pengadaan terpusat.', 'T3', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 1, ['F3', 'P1'], 'Capex 15–20% pendapatan.'],
                'jaringan' => ['TLKM-26-07', 'Jaringan akses efisien & 5G terarah', 'Modernisasi akses & 5G di area bernilai tinggi.', 'T3', 'jaringan', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 5, ['P1'], '8.000 BTS 5G; O&M 24%.'],
                'streamline' => ['TLKM-26-08', 'Streamlining anak usaha', 'Penggabungan bisnis tumpang tindih & divestasi non-core.', 'T4', 'strategi', '2026-01-01', '2026-12-31', 'berjalan', 4, 3, 3, 2, ['P3'], '6 entitas dirapikan sejak TLKM 30.'],
                'siber' => ['TLKM-26-09', 'Keamanan siber & PDP grup', 'SOC grup, pelatihan, kepatuhan UU PDP.', 'T4', 'esg', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 2, ['P4'], '0 kebocoran data kritikal.'],
                'budaya' => ['TLKM-26-10', 'Budaya digital ways of working & KPI berjenjang', 'Bravery, Integrity, Service Excellence, Agility; KPI turunan.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 2, 1, ['L2', 'L3'], '95% KPI terhubung.'],
            ],
            'activities' => [
                ['fmc', 'Paket konvergen baru', 'b2c', '2026-01-01', '2026-05-31', 100], ['fmc', 'Penjualan konvergen per wilayah', 'b2c', '2026-06-01', '2026-12-31', 45],
                ['territory', 'Penetapan target & kanal per wilayah', 'b2c', '2026-01-15', '2026-04-30', 100], ['territory', 'Dasbor kinerja wilayah', 'b2c', '2026-03-01', '2026-09-30', 85],
                ['b2b', 'Tim solusi per industri', 'b2b', '2026-01-15', '2026-06-30', 100], ['b2b', 'Visibilitas profit per proyek', 'cfo', '2026-03-01', '2026-12-31', 55],
                ['dc', 'Ekspansi kapasitas hyperscale', 'portofolio', '2026-01-01', '2027-06-30', 35],
                ['infranexia', 'Persetujuan regulator', 'portofolio', '2026-01-01', '2026-09-30', 70], ['infranexia', 'Seleksi mitra strategis', 'portofolio', '2026-06-01', '2026-12-31', 30],
                ['capex', 'Pengadaan grup terpusat', 'cfo', '2026-01-01', '2026-12-31', 70],
                ['jaringan', 'Pembangunan 5G terarah', 'jaringan', '2026-01-01', '2026-12-31', 60], ['jaringan', 'Modernisasi akses tembaga ke fiber', 'jaringan', '2026-02-01', '2026-12-31', 55],
                ['streamline', 'Penggabungan dua anak usaha', 'strategi', '2026-01-01', '2026-09-30', 70],
                ['siber', 'SOC grup', 'esg', '2026-01-01', '2026-08-31', 100], ['siber', 'Audit kepatuhan PDP', 'esg', '2026-04-01', '2026-12-31', 60],
                ['budaya', 'KPI turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100], ['budaya', 'Sertifikasi talenta digital', 'sdm', '2026-02-01', '2026-12-31', 65],
            ],
            'milestones' => [
                ['fmc', 'Paket konvergen diluncurkan', '2026-05-31', true], ['infranexia', 'Persetujuan regulator', '2026-09-30', false], ['siber', 'SOC grup beroperasi', '2026-08-31', true],
                ['streamline', 'Penggabungan pertama efektif', '2026-06-30', true], ['budaya', 'KPI individu disetujui', '2026-04-30', true],
            ],
            'budgets' => [
                ['fmc', 'Peluncuran & promosi konvergen', 'opex', 600_000_000_000, 410_000_000_000, null],
                ['territory', 'Kanal & dasbor wilayah', 'opex', 120_000_000_000, 86_000_000_000, null],
                ['b2b', 'Tim solusi & sistem visibilitas profit', 'opex', 90_000_000_000, 58_000_000_000, null],
                ['dc', 'Kapasitas data center', 'capex', 3_500_000_000_000, 1_420_000_000_000, null, 'Kas internal & pinjaman'],
                ['infranexia', 'Penasihat transaksi', 'opex', 70_000_000_000, 41_000_000_000, null],
                ['capex', 'Sistem pengadaan grup', 'capex', 50_000_000_000, 33_000_000_000, null, 'Kas internal'],
                ['jaringan', '5G & modernisasi akses', 'capex', 6_000_000_000_000, 3_900_000_000_000, null, 'Kas internal'],
                ['streamline', 'Biaya integrasi entitas', 'opex', 45_000_000_000, 29_000_000_000, null],
                ['siber', 'SOC & kepatuhan PDP', 'capex', 250_000_000_000, 172_000_000_000, null, 'Kas internal'],
                ['budaya', 'Program budaya & sertifikasi', 'opex', 40_000_000_000, 27_000_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Laba ke pemilik turun 20,5%', 'Rp17,8 T vs target Rp23 T; pendapatan B2C turun & beban naik.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F2.3', null, 'komisaris', '2026-02-24',
                [['Program FMC & efisiensi O&M 2026', 'b2c', '2026-12-31', 'berjalan', 55]], null],
            ['Pelanggan seluler berkurang 3,3 juta', 'Persaingan harga & daya beli semester I.', 'tinjauan', 'tahunan', 'pelanggan', 'sedang', null, 'C1.1', 'nps', 'b2c', '2026-02-24',
                [['Territory management 2026', 'b2c', '2026-04-30', 'selesai', 100]], ['2026-05-15', 'Territory management berjalan di seluruh wilayah.']],
        ] : [
            ['Adopsi konvergensi lambat', 'Pelanggan FMC 3,4 juta per Juni vs target 3,7 juta; kendala pemasangan IndiHome di luar Jawa.', 'timwas', 'boc', 'pelanggan', 'kritis', null, 'C2.1', 'fmc', 'komisaris', '2026-07-28',
                [['Sederhanakan paket & percepat pemasangan', 'b2c', '2026-09-30', 'selesai', 100], ['Mitra pemasangan di 15 wilayah', 'jaringan', '2026-12-31', 'berjalan', 45]], null],
            ['InfraNexia menunggu persetujuan regulator', 'Tonggak persetujuan mundur dari Juni ke September.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P2.1', 'infranexia', 'portofolio', '2026-07-22',
                [['Lengkapi dokumen tambahan regulator', 'portofolio', '2026-08-31', 'selesai', 100]], null],
            ['Biaya O&M jaringan naik', 'Biaya O&M 25,6% pendapatan di Juli karena listrik & sewa lahan.', 'internal', 'rm7', 'proses', 'sedang', null, 'P1.3', 'jaringan', 'jaringan', '2026-08-12',
                [['Efisiensi energi BTS & renegosiasi sewa', 'jaringan', '2026-12-31', 'berjalan', 40]], null],
            ['Proyek B2B besar tertunda', '17 proyek > Rp100 M per Agustus vs rencana 19; siklus pengadaan pemerintah mundur.', 'tinjauan', 'q2', 'pelanggan', 'sedang', null, 'C3.2', 'b2b', 'b2b', '2026-07-22',
                [['Fokus ke pelanggan swasta & BUMN', 'b2b', '2026-10-31', 'berjalan', 60]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan regulator untuk InfraNexia', 'eksternal', 'infranexia', 'P2', 'Komdigi & OJK', 'Pemisahan usaha butuh izin.', 'portofolio', 'tinggi', 'Dialog rutin & dokumen lengkap.'],
                ['Kapasitas pemasangan IndiHome', 'internal', 'fmc', 'C2', 'Unit jaringan & mitra pemasangan', 'Konvergensi bergantung pada pemasangan fixed broadband.', 'jaringan', 'tinggi', 'Mitra pemasangan tambahan.'],
                ['Pasokan listrik untuk data center', 'eksternal', 'dc', 'C3', 'PLN', 'Kapasitas hyperscale butuh daya besar.', 'portofolio', 'sedang', 'Kontrak daya jangka panjang & EBT.'],
                ['Spektrum 5G', 'eksternal', 'jaringan', 'P1', 'Komdigi', 'Perluasan 5G bergantung alokasi spektrum.', 'jaringan', 'sedang', 'Optimalkan spektrum yang ada.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Transformation Office TLKM 30', 'rapat', 'Pemantauan empat pilar & tonggak transformasi.', 'Dua mingguan', 'strategi', 'melembaga'],
                ['Komite investasi & capex grup', 'rapat', 'Prioritas capex inti & next core.', 'Bulanan', 'cfo', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan transformasi.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Tinjauan kinerja wilayah (territory review)', 'sop', 'Kinerja B2C per wilayah dibahas bulanan.', 'Bulanan', 'b2c', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Budaya AKHLAK & digital ways of working', 'budaya', 'Agen budaya & apresiasi.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
