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
 * Demo PT Astra International Tbk (kode ASII) — konglomerasi tujuh divisi usaha.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (pendapatan & laba per divisi, penjualan mobil/motor, pembiayaan, alat berat, tambang, agribisnis, ESG).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: manajemen strategi korporat (corporate strategy) — alokasi modal antar-divisi,
 *   Tinjauan Strategis portofolio, dan Balanced Scorecard berjenjang dari grup ke divisi.
 */
class AstraSeeder extends ShowcaseSeeder
{
    public const CODE = 'ASII';

    public const LT = 'Laporan Tahunan 2025 PT Astra International Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Astra International Tbk', 'legal_name' => 'PT Astra International Tbk',
            'industry' => 'Konglomerasi: otomotif, jasa keuangan, alat berat & tambang, agribisnis, infrastruktur, TI',
            'address' => 'Menara Astra, Jl. Jend. Sudirman Kav. 5-6', 'city' => 'Jakarta Pusat',
            'phone' => '+62 21 5084 3888', 'website' => 'https://www.astra.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham ASII). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Penjualan otomotif naik menjelang Lebaran & akhir tahun; tambang tertekan musim hujan. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.0, 7.8, 9.0, 7.6, 8.2, 8.3, 8.6, 8.5, 8.4, 8.6, 8.5, 8.5]),
            2026 => self::weights([1 => 8.0, 8.6, 8.4, 7.6, 8.2, 8.3, 8.6, 8.5, 8.4, 8.6, 8.4, 8.4]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.4', 'F3.2', 'C1.3', 'C2.2', 'C3.2', 'C4.3', 'P1.2', 'P2.2', 'P3.2', 'P3.3', 'P4.2', 'L1.1', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.2', 'L1.2'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Pembiayaan baru September dari salah satu perusahaan pembiayaan belum termasuk pembiayaan multiguna syariah. Mohon lengkapi dan lampirkan rekonsiliasi per entitas.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P2.1'];
    }

    protected function maturityPattern(): array
    {
        return [4, 3, 4, 3, 4];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Astra (contoh)', 'asii.admin', 'Admin Sistem', 'Corporate Strategy', ['company-admin']],
            'dirut' => ['Presiden Direktur (contoh)', 'asii.direktur', 'Presiden Direktur', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'asii.keuangan', 'Chief Financial Officer', 'Keuangan Grup', ['keuangan']],
            'otomotif' => ['Direktur Otomotif & Mobilitas (contoh)', 'asii.otomotif', 'Direktur Penanggung Jawab Otomotif & Mobilitas', 'Otomotif & Mobilitas', ['kepala-unit']],
            'jasakeu' => ['Direktur Jasa Keuangan (contoh)', 'asii.jasakeuangan', 'Direktur Penanggung Jawab Jasa Keuangan', 'Jasa Keuangan', ['kepala-unit']],
            'alatberat' => ['Direktur Alat Berat, Tambang & Energi (contoh)', 'asii.alatberat', 'Direktur Penanggung Jawab Alat Berat, Pertambangan, Konstruksi & Energi', 'Alat Berat & Tambang', ['kepala-unit']],
            'agri' => ['Direktur Agribisnis (contoh)', 'asii.agri', 'Direktur Penanggung Jawab Agribisnis', 'Agribisnis', ['kepala-unit']],
            'infra' => ['Direktur Infrastruktur, Properti & Kesehatan (contoh)', 'asii.infra', 'Direktur Penanggung Jawab Infrastruktur, Properti & Bisnis Baru', 'Infrastruktur & Bisnis Baru', ['kepala-unit']],
            'esg' => ['Chief Sustainability Officer (contoh)', 'asii.esg', 'Chief Sustainability Officer', 'Keberlanjutan & K3', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'asii.sdm', 'Direktur Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Corporate Strategy (contoh)', 'asii.strategi', 'Head of Corporate Strategy & Portfolio', 'Corporate Strategy', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'asii.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'asii.analis', 'Performance Analyst', 'Corporate Strategy', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => "1. Menjadi salah satu perusahaan dengan pengelolaan terbaik di Asia Pasifik dengan pertumbuhan yang berkelanjutan dan struktur keuangan yang solid.\n2. Menjadi perusahaan yang intelligent dan agile yang berfokus pada karyawan, pelanggan dan masyarakat.",
            'mission' => 'Sejahtera bersama bangsa dengan memberikan nilai terbaik kepada para pemangku kepentingan.',
            'purpose' => 'Menjadi kebanggaan bangsa (pride of the nation) melalui bisnis yang berkelanjutan dan bermanfaat bagi Indonesia.',
            'tagline' => 'Tumbuh Bersama Indonesia',
            'brand_promise' => 'Produk dan layanan yang andal di setiap tahap hidup pelanggan: mobilitas, pembiayaan, energi, pangan, dan infrastruktur.',
            'value_proposition' => '7 divisi usaha, 322 perusahaan, > 190.000 karyawan; pangsa pasar mobil 51% dan sepeda motor 78%; pembiayaan baru Rp112,3 T; 396 km jalan tol.',
            'culture_statement' => 'Filosofi Catur Dharma: menjadi milik yang bermanfaat bagi bangsa dan negara; memberikan pelayanan terbaik kepada pelanggan; menghargai individu dan membina kerja sama; senantiasa berusaha mencapai yang terbaik.',
            'history' => "1957: berdiri sebagai perusahaan perdagangan.\n1969: mulai mendistribusikan kendaraan Toyota.\n1990: tercatat di Bursa Efek (ASII).\n2023: Astra 2030 Sustainability Aspirations.\n2025: investasi kesehatan (Hermina, Halodoc, RS Heartology) Rp8,6 T; akuisisi pergudangan modern; buyback saham.\n2026: Tinjauan Strategis portofolio ditargetkan selesai pertengahan tahun.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Bermanfaat bagi bangsa', 'Catur Dharma 1', 'Menjadi milik yang bermanfaat bagi bangsa dan negara.', "Investasi yang memberi manfaat jangka panjang\nProgram tanggung jawab sosial terarah"],
            ['Pelayanan terbaik', 'Catur Dharma 2', 'Memberikan pelayanan terbaik kepada pelanggan.', "Pelanggan dilayani di seluruh siklus produk\nKeluhan diselesaikan tuntas"],
            ['Menghargai individu & kerja sama', 'Catur Dharma 3', 'Menghargai individu dan membina kerja sama.', "Sinergi antardivisi\nPengembangan talenta internal"],
            ['Mencapai yang terbaik', 'Catur Dharma 4', 'Senantiasa berusaha mencapai yang terbaik.', "Standar operasi kelas dunia\nPerbaikan berkelanjutan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Kemitraan prinsipal global', 'Kompetensi', 'Toyota, Daihatsu, Honda, Komatsu, dan lainnya.', 5, 5, true, 'Kemitraan untuk kendaraan elektrifikasi.'],
            ['Jaringan distribusi & purna jual', 'Sumber daya fisik', 'Dealer, bengkel, suku cadang, dan pembiayaan di seluruh Indonesia.', 5, 5, true, 'Digitalisasi pengalaman pelanggan.'],
            ['Alokasi modal portofolio', 'Sistem', 'Investasi baru di kesehatan, logistik, emas, nikel, energi terbarukan.', 3 + $lvl, 5, true, 'Tinjauan Strategis & kriteria hurdle rate.'],
            ['Manajemen talenta grup', 'Kompetensi', 'Rotasi lintas divisi & sekolah kepemimpinan.', 4, 5, true, 'Suksesi posisi kunci.'],
            ['Kapabilitas kendaraan listrik', 'Kompetensi', 'Pasar EV dipimpin pemain Tiongkok.', 2 + $lvl, 4, false, 'Portofolio xEV bersama prinsipal.'],
            ['Pengelolaan ESG grup', 'Sistem', 'Astra 2030 Sustainability Aspirations; emisi −18,15% vs 2019.', 3 + $lvl, 5, false, 'Data emisi terpadu seluruh divisi.'],
            ['Balanced Scorecard berjenjang', 'Sistem', 'KPI grup diturunkan ke divisi dengan format beragam.', 3, 4, false, 'Satu Peta Strategi grup–divisi.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Lingkungan usaha 2025', '2026-01-20', 'eksternal', self::LT.' — Laporan Direksi',
                'Penjualan mobil nasional 804 ribu unit (−7%); motor 6,4 juta (+1%); persaingan kendaraan & alat berat asal Tiongkok; harga batu bara turun; daya beli kelas menengah melemah; UU Minerba 2025 & royalti progresif.',
                'Bisnis inti tertekan siklus; diversifikasi & disiplin modal semakin penting.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan Rp323,4 T (−2%); laba ke pemilik Rp32,8 T (−3%); ROE 14%. Jasa keuangan +9%, agribisnis +28%, infrastruktur +24%; alat berat & tambang −24%; otomotif stabil.',
                'Portofolio terdiversifikasi meredam penurunan divisi siklikal.'],
            ['Tinjauan Strategis 2026', '2026-02-05', 'internal', self::LT.' — Prospek Usaha',
                'Tinjauan Strategis portofolio ditargetkan selesai pertengahan 2026 untuk meningkatkan Total Shareholder Return; prospek hati-hati namun optimistis.',
                'Setiap divisi harus menunjukkan imbal hasil di atas biaya modal.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Insentif kendaraan listrik', 'Insentif pajak & impor CBU mendorong merek EV baru.', 'ancaman', 4, 5, 'naik', 'Portofolio xEV bersama prinsipal.'],
            ['politik', 'UU Minerba 2025 & royalti progresif', 'Biaya tambang naik.', 'ancaman', 4, 4, 'naik', 'Efisiensi & diversifikasi ke emas/nikel.'],
            ['ekonomi', 'Daya beli kelas menengah', 'Penjualan mobil turun 7%.', 'ancaman', 5, 4, 'stabil', 'Pembiayaan menarik & produk terjangkau.'],
            ['ekonomi', 'Harga komoditas (batu bara, CPO, emas)', 'Batu bara turun 13%; CPO & emas menguat.', 'ancaman', 4, 4, 'stabil', 'Portofolio komoditas seimbang.'],
            ['sosial', 'Layanan kesehatan & logistik modern', 'Permintaan RS, telemedisin, dan gudang modern naik.', 'peluang', 4, 4, 'naik', 'Investasi kesehatan & pergudangan.'],
            ['teknologi', 'Elektrifikasi & mobil bekas digital', 'EV & platform jual-beli mobil bekas.', 'peluang', 4, 4, 'naik', 'OLXmobbi & ekosistem purna jual.'],
            ['lingkungan', 'Transisi energi & net zero', 'Investor & pembeli menuntut dekarbonisasi.', 'ancaman', 4, 4, 'naik', 'Energi terbarukan & pengurangan batu bara termal.'],
            ['lingkungan', 'Deforestasi & sawit berkelanjutan', 'Regulasi bebas deforestasi di pasar ekspor.', 'ancaman', 3, 4, 'naik', 'Sertifikasi ISPO/RSPO & keterlacakan.'],
            ['hukum', 'Regulasi pembiayaan & asuransi', 'Aturan OJK terkait modal & perlindungan konsumen.', 'ancaman', 3, 3, 'stabil', 'Kepatuhan & tata kelola.'],
            ['hukum', 'Keselamatan kerja di tambang', 'Standar K3 & tanggung jawab kontraktor.', 'ancaman', 4, 3, 'stabil', 'Mandatory Key Action K3.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Merek otomotif & alat berat asal Tiongkok', 'Harga agresif & teknologi EV.', 5, 'Nilai total kepemilikan & jaringan purna jual.'],
            ['kekuatan_pembeli', 'Konsumen ritel & perusahaan tambang', 'Pelanggan tambang menekan harga kontrak.', 4, 'Solusi terintegrasi alat–jasa–pembiayaan.'],
            ['kekuatan_pemasok', 'Prinsipal otomotif & alat berat', 'Prinsipal menentukan produk & teknologi.', 4, 'Kemitraan jangka panjang.'],
            ['pendatang_baru', 'Merek EV & pembiayaan digital', 'Masuk dengan model bisnis baru.', 4, 'Ekosistem Astra (pembiayaan, asuransi, purna jual).'],
            ['produk_pengganti', 'Transportasi daring & publik', 'Mengurangi kebutuhan kendaraan pribadi.', 2, 'Layanan mobilitas & sewa.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Otomotif & mobilitas', 'Divisi', 'Pendapatan Rp124,4 T (−8%); laba Rp11,4 T.', 38, 'turun', true, 'Kendaraan andal, purna jual mudah.', 'xEV & ekosistem purna jual.'],
            ['Jasa keuangan', 'Divisi', 'Pendapatan Rp33,3 T (+9%); laba Rp9,0 T.', 10, 'naik', true, 'Pembiayaan cepat & asuransi.', 'Pembiayaan berbasis ekosistem.'],
            ['Alat berat, tambang, konstruksi & energi', 'Divisi', 'Pendapatan Rp130,8 T (−2%); laba Rp9,1 T (−24%).', 40, 'turun', true, 'Alat & jasa tambang produktif.', 'Diversifikasi emas, nikel, EBT.'],
            ['Agribisnis', 'Divisi', 'Pendapatan Rp28,7 T (+31%); laba Rp1,2 T.', 9, 'naik', true, 'CPO berkelanjutan.', 'Produktivitas & replanting.'],
            ['Infrastruktur, TI & properti', 'Divisi', 'Tol 396 km; laba infrastruktur Rp1,3 T.', 3, 'naik', true, 'Jalan tol & gudang modern.', 'Aset infrastruktur & logistik.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — informasi segmen (porsi pendapatan dibulatkan)']);
        }

        foreach ([
            ['PT Astra International Tbk', 'Konglomerasi', 1, 323392, -1.5, 10.1, true, 'Portofolio terdiversifikasi, pangsa pasar dominan.', 'Divisi siklikal, tekanan EV Tiongkok.'],
            ['Merek otomotif Tiongkok', 'Otomotif', 2, null, null, null, false, 'Harga & teknologi EV.', 'Jaringan purna jual baru.'],
            ['Konglomerasi nasional lain', 'Konglomerasi', 3, null, null, null, false, 'Akses modal & sumber daya.', 'Fokus portofolio berbeda.'],
            ['Perusahaan pembiayaan bank', 'Pembiayaan', 4, null, null, null, false, 'Dana murah.', 'Hubungan dengan dealer terbatas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['UU Mineral & Batubara 2025 & royalti', 'Kementerian ESDM', 'UU Minerba & PP royalti progresif', 'Pertambangan', '2025-01-01', 'Kewajiban royalti & perizinan tambang.', 'Biaya & marjin tambang.', 'patuh', 'alatberat', null],
            ['Perusahaan pembiayaan & asuransi', 'OJK', 'POJK perusahaan pembiayaan & perasuransian', 'Keuangan', null, 'Modal minimum, rasio, perlindungan konsumen.', 'Izin usaha.', 'patuh', 'jasakeu', null],
            ['ISPO', 'Kementerian Pertanian', 'Perpres No. 44/2020', 'Agribisnis', '2020-03-13', 'Sertifikasi sawit berkelanjutan wajib.', 'Akses pasar.', 'patuh', 'agri', null],
            ['Regulasi bebas deforestasi Uni Eropa (EUDR)', 'Uni Eropa', 'Regulation (EU) 2023/1115', 'Agribisnis', '2025-12-30', 'Keterlacakan asal CPO yang diekspor ke UE.', 'Akses ekspor.', 'sebagian', 'agri', $year === 2025 ? null : '2026-12-31'],
            ['Standar emisi kendaraan', 'Kementerian Lingkungan Hidup', 'Ketentuan baku mutu emisi Euro 4', 'Otomotif', null, 'Kendaraan baru wajib memenuhi standar emisi.', 'Desain produk.', 'patuh', 'otomotif', null],
            ['Pengungkapan keberlanjutan', 'OJK', 'POJK No. 51/2017', 'Tata kelola', null, 'Laporan keberlanjutan & aspirasi 2030.', 'Kepercayaan investor.', 'patuh', 'esg', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pelanggan otomotif, pembiayaan, asuransi.', 'Sanksi & reputasi.', 'sebagian', 'jasakeu', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham pengendali & publik', 'internal', 'Pemilik', 5, 5, 'TSR naik, dividen, buyback.', 'RUPS, paparan publik, Tinjauan Strategis.', 'Investor Relations'],
            'prinsipal' => ['Prinsipal global (Toyota, Honda, Komatsu, dll.)', 'eksternal', 'Mitra', 5, 5, 'Pangsa pasar & kualitas distribusi.', 'Rencana bisnis bersama.', 'Divisi terkait'],
            'pelanggan' => ['Pelanggan ritel & korporasi', 'eksternal', 'Pelanggan', 4, 5, 'Produk andal & layanan purna jual.', 'Survei & program loyalitas.', 'Divisi terkait'],
            'pemerintah' => ['Pemerintah & regulator (ESDM, OJK, Kemenperin)', 'eksternal', 'Regulator', 5, 4, 'Kepatuhan, investasi, lapangan kerja.', 'Dialog kebijakan.', 'Corporate Affairs'],
            'karyawan' => ['Karyawan grup (> 190.000)', 'internal', 'SDM', 4, 5, 'Karier, keselamatan, kesejahteraan.', 'Forum karyawan & survei.', 'Human Capital'],
            'masyarakat' => ['Masyarakat sekitar operasi', 'eksternal', 'Komunitas', 3, 4, 'Lingkungan & manfaat sosial.', 'Program Kampung Berseri Astra.', 'CSR'],
            'kreditur' => ['Bank & investor obligasi', 'eksternal', 'Pemberi dana', 4, 4, 'Struktur keuangan solid.', 'Paparan kinerja.', 'Treasury'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'otomotif' => ['Pangsa pasar mobil tergerus', 'Penjualan mobil Astra −15%; pangsa pasar 51%; merek EV Tiongkok agresif.', 'pelanggan', 5, 5, 'otomotif', ['prinsipal', 'pelanggan']],
            'tambang' => ['Laba alat berat & tambang turun 24%', 'Harga batu bara turun; volume overburden −10%.', 'keuangan', 4, 5, 'alatberat', ['pemegang_saham']],
            'portofolio' => ['Imbal hasil portofolio', 'ROE 14% (2024: 16%); investor menuntut TSR lebih tinggi.', 'keuangan', 5, 5, 'strategi', ['pemegang_saham']],
            'transisi' => ['Transisi energi & EV', 'Ketergantungan pada batu bara termal & kendaraan BBM.', 'proses', 4, 4, 'esg', ['pemerintah', 'pemegang_saham']],
            'k3' => ['Keselamatan kerja di operasi berisiko', 'LTIFR turun ke 0,092; zero fatality belum tercapai.', 'proses', 4, 5, 'esg', ['karyawan']],
            'talenta' => ['Talenta untuk bisnis baru', 'Kesehatan, logistik, EV, dan digital butuh kompetensi baru.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Portofolio terdiversifikasi', '7 divisi meredam siklus.', 0.14, 4],
            ['S', 'Pangsa pasar dominan', 'Mobil 51%, motor 78%.', 0.12, 4],
            ['S', 'Ekosistem distribusi–pembiayaan–purna jual', 'Nilai tambah sepanjang umur kendaraan.', 0.10, 4],
            ['S', 'Neraca solid', 'Kas bersih di luar jasa keuangan.', 0.06, 4],
            ['W', 'Ketergantungan pada siklus komoditas', 'UT −24%.', 0.10, 2],
            ['W', 'Portofolio EV tertinggal', 'Pemain baru lebih cepat.', 0.08, 2],
            ['W', 'Kompleksitas grup', '322 perusahaan.', 0.05, 2],
            ['O', 'Kesehatan & logistik modern', 'Investasi baru Rp8,6 T di kesehatan.', 0.09, 3],
            ['O', 'Emas, nikel & energi terbarukan', 'Diversifikasi tambang.', 0.07, 3],
            ['O', 'Mobil bekas & purna jual digital', 'OLXmobbi +21%.', 0.04, 3],
            ['T', 'Merek EV & alat berat Tiongkok', 'Harga agresif.', 0.08, 2],
            ['T', 'Daya beli melemah', 'Penjualan mobil nasional −7%.', 0.05, 2],
            ['T', 'Regulasi tambang & royalti', 'Biaya naik.', 0.02, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Investasi di kesehatan & logistik', 'Gunakan kekuatan neraca untuk bisnis bertumbuh.', 4, ['portofolio']],
            ['SO', 'Ekosistem purna jual & mobil bekas', 'Nilai tambah dari basis kendaraan terpasang.', 3, ['otomotif']],
            ['WO', 'Diversifikasi tambang ke emas, nikel, EBT', 'Kurangi ketergantungan batu bara termal.', 4, ['tambang', 'transisi']],
            ['ST', 'Portofolio xEV bersama prinsipal', 'Jawab merek EV Tiongkok dengan nilai total kepemilikan.', 5, ['otomotif', 'transisi']],
            ['WT', 'Tinjauan Strategis & disiplin modal', 'Divestasi aset di bawah biaya modal; buyback.', 5, ['portofolio']],
            ['WT', 'Zero harm & talenta bisnis baru', 'K3 & kompetensi masa depan.', 3, ['k3', 'talenta']],
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
            'T1' => ['Nilai Pemegang Saham & Disiplin Modal', 'Tinjauan Strategis, imbal hasil di atas biaya modal, TSR.', '#1E3F8A'],
            'T2' => ['Keunggulan Bisnis Inti', 'Otomotif, jasa keuangan, alat berat & tambang tetap memimpin.', '#B22234'],
            'T3' => ['Pertumbuhan Bisnis Baru & Digital', 'Kesehatan, logistik, infrastruktur, mobil bekas, digital.', '#E08A1E'],
            'T4' => ['Keberlanjutan & Insan Astra', 'Aspirasi 2030, zero harm, Catur Dharma, talenta.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Setiap divisi harus melampaui biaya modal', 'organisasi', 'Tinjauan Strategis 2026 untuk TSR.'],
            ['T2', 'xEV bersama prinsipal, bukan merek sendiri', 'produk', 'Memanfaatkan teknologi prinsipal & jaringan Astra.'],
            ['T3', 'Kesehatan & logistik sebagai pilar baru', 'produk', 'Investasi 2025 di Hermina, Halodoc, Heartology, gudang modern.'],
            ['T4', 'Kurangi eksposur batu bara termal', 'operasi', 'Astra 2030 Sustainability Aspirations.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T2', 'Pendapatan & laba grup tumbuh', 'Laba ke pemilik Rp34 T; divisi siklikal pulih.', 'dirut', 3],
            'F2' => ['keuangan', 'T1', 'Imbal hasil modal meningkat', 'ROE 15%; marjin laba bersih membaik.', 'cfo', 2],
            'F3' => ['keuangan', 'T1', 'Struktur keuangan solid', 'Rasio lancar & pinjaman terkendali.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T2', 'Pemimpin pasar otomotif', 'Pangsa pasar mobil pulih; motor tetap dominan.', 'otomotif', 2],
            'C2' => ['pelanggan', 'T2', 'Solusi pembiayaan & asuransi pilihan', 'Pembiayaan baru tumbuh dengan kualitas terjaga.', 'jasakeu', 1.5],
            'C3' => ['pelanggan', 'T2', 'Mitra andal pelanggan tambang & konstruksi', 'Alat berat & jasa tambang produktif.', 'alatberat', 1.5],
            'C4' => ['pelanggan', 'T3', 'Bisnis baru melayani kebutuhan masa depan', 'Kesehatan, gudang modern, jalan tol.', 'infra', 1.5],
            'P1' => ['proses', 'T2', 'Produktivitas operasi sumber daya', 'Produksi batu bara, CPO, emas sesuai rencana.', 'alatberat', 1.5],
            'P2' => ['proses', 'T1', 'Alokasi modal & Tinjauan Strategis', 'Keputusan portofolio tepat waktu; proyek melampaui hurdle rate.', 'strategi', 2],
            'P3' => ['proses', 'T4', 'Dekarbonisasi & ekonomi sirkular', 'Emisi turun; bauran EBT naik; limbah dialihkan.', 'esg', 1.5],
            'P4' => ['proses', 'T4', 'Zero harm & tata kelola', 'LTIFR turun; temuan audit tuntas.', 'esg', 1],
            'L1' => ['sdm', 'T4', 'Talenta & kepemimpinan masa depan', 'Suksesor siap; jam pelatihan memadai.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya Catur Dharma & kinerja berjenjang', 'KPI divisi & individu terhubung ke grup.', 'sdm', 1],
            'L3' => ['sdm', 'T3', 'Transformasi digital', 'Kanal digital pelanggan & platform mobil bekas.', 'infra', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P2', 2], ['L1', 'C4', 2], ['L2', 'P4', 2], ['L3', 'C1', 2], ['L3', 'C2', 2],
            ['P1', 'C3', 3], ['P1', 'F1', 2], ['P2', 'F2', 3], ['P2', 'C4', 2], ['P3', 'F2', 1], ['P4', 'P1', 2],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'F1', 3], ['C4', 'F1', 2],
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
            ['F1.1', 'F1', 'Pendapatan bersih konsolidasian', 'Rp miliar', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan bersih konsolidasian', 'Laporan keuangan konsolidasian', 328480, 335000, 323392, 330000, .97, $lt],
            ['F1.2', 'F1', 'Laba yang dapat diatribusikan ke pemilik', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba bersih bagian pemilik entitas induk', 'Laporan laba rugi', 33901, 35000, 32769, 34000, .95, $lt],
            ['F1.3', 'F1', 'Laba bersih divisi otomotif & mobilitas', 'Rp miliar', 'higher', 'sum', 0, true, false, 'otomotif', 'Kontribusi laba divisi ke grup', 'Laporan segmen', 11400, 11800, 11400, 11600, .97, $lt],
            ['F1.4', 'F1', 'Laba bersih divisi jasa keuangan', 'Rp miliar', 'higher', 'sum', 0, true, false, 'jasakeu', 'Kontribusi laba divisi ke grup', 'Laporan segmen', 8200, 8600, 9000, 9700, 1.0, $lt],
            ['F1.5', 'F1', 'Laba bersih divisi alat berat, tambang, konstruksi & energi', 'Rp miliar', 'higher', 'sum', 0, true, false, 'alatberat', 'Kontribusi laba divisi ke grup', 'Laporan segmen', 12000, 11000, 9100, 9500, .86, $lt],
            ['F2.1', 'F2', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'average', 1, true, true, 'cfo', 'Laba ke pemilik ÷ rata-rata ekuitas pemilik × 100', 'Rasio keuangan', 16.0, 15.0, 14.0, 15.0, .95, $lt],
            ['F2.2', 'F2', 'Imbal hasil aset (ROA)', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba tahun berjalan ÷ rata-rata aset × 100', 'Rasio keuangan', 9.0, 8.5, 8.0, 8.5, .96, $lt],
            ['F2.3', 'F2', 'Marjin laba bersih', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba tahun berjalan ÷ pendapatan × 100', 'Rasio keuangan', 13.0, 12.5, 12.0, 12.5, .97, $lt],
            ['F3.1', 'F3', 'Rasio lancar', 'kali', 'higher', 'last', 1, true, false, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 1.4, 1.3, 1.2, 1.3, .97, $lt],
            ['F3.2', 'F3', 'Total pinjaman grup', 'Rp miliar', 'lower', 'last', 0, true, false, 'cfo', 'Pinjaman bank & obligasi (termasuk jasa keuangan)', 'Neraca', 100673, 105000, 110301, 112000, 1.0, $lt],
            ['C1.1', 'C1', 'Pangsa pasar mobil Astra', '%', 'higher', 'average', 0, false, true, 'otomotif', 'Penjualan ritel mobil Astra ÷ pasar nasional × 100', 'Data Gaikindo', 56, 55, 51, 53, .96, $lt],
            ['C1.2', 'C1', 'Penjualan mobil Astra', 'ribu unit', 'higher', 'sum', 0, false, false, 'otomotif', 'Penjualan ritel mobil merek Astra', 'Data penjualan', 482, 480, 409, 430, .93, $lt],
            ['C1.3', 'C1', 'Pangsa pasar sepeda motor Honda', '%', 'higher', 'average', 0, false, false, 'otomotif', 'Penjualan domestik Honda ÷ pasar nasional × 100', 'Data AISI', 78, 78, 78, 78, 1.0, $lt],
            ['C1.4', 'C1', 'Penjualan sepeda motor Honda', 'ribu unit', 'higher', 'sum', 0, false, false, 'otomotif', 'Penjualan domestik sepeda motor Honda', 'Data penjualan', 4950, 5100, 5000, 5150, .985, $lt],
            ['C2.1', 'C2', 'Pembiayaan konsumen baru', 'Rp miliar', 'higher', 'sum', 0, true, false, 'jasakeu', 'Pembiayaan baru disalurkan (termasuk JV)', 'Laporan divisi', 107000, 115000, 112300, 118000, .97, $lt],
            ['C2.2', 'C2', 'Pembiayaan bermasalah (NPF)', '%', 'lower', 'last', 2, true, false, 'jasakeu', 'Piutang > 90 hari ÷ total piutang pembiayaan × 100', 'Laporan risiko', 1.60, 1.80, 1.70, 1.80, .97, $il],
            ['C3.1', 'C3', 'Penjualan alat berat Komatsu', 'unit', 'higher', 'sum', 0, false, false, 'alatberat', 'Unit alat berat terjual', 'Laporan divisi', 4426, 4500, 4515, 4300, .9, $lt],
            ['C3.2', 'C3', 'Volume pengupasan lapisan tanah (overburden)', 'juta', 'higher', 'sum', 0, false, false, 'alatberat', 'Overburden removal kontraktor tambang (juta bcm)', 'Laporan divisi', 1222, 1200, 1100, 1150, .96, $lt],
            ['C4.1', 'C4', 'Jalan tol beroperasi', 'km', 'higher', 'last', 0, false, false, 'infra', 'Panjang konsesi tol beroperasi', 'Laporan divisi', 396, 396, 396, 410, .97, $lt],
            ['C4.2', 'C4', 'Investasi kumulatif di bisnis kesehatan', 'Rp miliar', 'higher', 'last', 0, true, false, 'infra', 'Nilai investasi di RS, klinik & telemedisin', 'Laporan investasi', 4000, 8000, 8600, 10000, .9, $lt],
            ['C4.3', 'C4', 'Okupansi gudang modern', '%', 'higher', 'average', 0, false, false, 'infra', 'Luas tersewa ÷ luas tersedia × 100', 'Laporan properti', 85, 88, 89, 91, .99, $il],
            ['P1.1', 'P1', 'Produksi batu bara', 'ribu ton', 'higher', 'sum', 0, false, false, 'alatberat', 'Produksi batu bara dari tambang milik grup', 'Laporan divisi', 13100, 13500, 14300, 14500, .97, $lt],
            ['P1.2', 'P1', 'Penjualan CPO & produk turunan', 'ribu ton', 'higher', 'sum', 0, false, false, 'agri', 'Volume CPO & turunan terjual', 'Laporan divisi', 1593, 1700, 1800, 1850, .97, $lt],
            ['P1.3', 'P1', 'Produksi emas', 'ribu', 'higher', 'sum', 0, false, false, 'alatberat', 'Produksi emas (ribu oz)', 'Laporan divisi', 232, 240, 227, 260, .9, $lt],
            ['P1.4', 'P1', 'Peremajaan kebun kelapa sawit', 'ha', 'higher', 'sum', 0, false, false, 'agri', 'Luas tanaman tua yang diremajakan', 'Laporan divisi', 4000, 5000, 5000, 6000, .7, $lt],
            ['P2.1', 'P2', 'Kemajuan Tinjauan Strategis portofolio', '%', 'higher', 'last', 0, false, true, 'strategi', 'Tonggak Tinjauan Strategis tercapai ÷ total tonggak × 100', 'PMO Tinjauan Strategis', 0, 30, 35, 100, .85, $il],
            ['P2.2', 'P2', 'Investasi baru melampaui hurdle rate', '%', 'higher', 'last', 0, false, false, 'strategi', 'Nilai investasi dengan IRR ≥ hurdle rate ÷ total investasi baru × 100', 'Komite investasi', 85, 90, 90, 95, .98, $il],
            ['P3.1', 'P3', 'Penurunan emisi GRK terhadap baseline 2019', '%', 'higher', 'last', 2, false, false, 'esg', '(Emisi 2019 − emisi berjalan) ÷ emisi 2019 × 100', 'Laporan keberlanjutan', 15.00, 17.00, 18.15, 20.00, .98, $lt],
            ['P3.2', 'P3', 'Bauran energi terbarukan', '%', 'higher', 'last', 2, false, false, 'esg', 'Energi terbarukan ÷ total energi × 100', 'Laporan keberlanjutan', 45.00, 47.00, 48.42, 50.00, 1.0, $lt],
            ['P3.3', 'P3', 'Limbah padat dialihkan dari TPA', '%', 'higher', 'average', 2, false, false, 'esg', 'Limbah padat didaur ulang/dimanfaatkan ÷ total × 100', 'Laporan keberlanjutan', 98.00, 98.50, 98.85, 99.00, 1.0, $lt],
            ['P4.1', 'P4', 'Lost Time Injury Frequency Rate (LTIFR)', 'rasio', 'lower', 'average', 3, false, true, 'esg', 'Kecelakaan hilang waktu kerja × 1.000.000 ÷ jam kerja', 'Laporan K3', 0.241, 0.150, 0.092, 0.085, 1.05, $lt],
            ['P4.2', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan audit internal grup selesai sesuai tenggat ÷ jatuh tempo × 100', 'Audit Internal Grup', 80, 88, 90, 95, .97, $il],
            ['L1.1', 'L1', 'Posisi kunci dengan suksesor siap', '%', 'higher', 'last', 0, false, false, 'sdm', 'Posisi kunci dengan ≥ 1 suksesor siap ÷ total posisi kunci × 100', 'Talent review', 70, 75, 76, 80, .97, $il],
            ['L1.2', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 0, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'Astra Management Development Institute', 35, 38, 39, 40, .92, $il],
            ['L2.1', 'L2', 'Indeks budaya Catur Dharma', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei budaya (skala 100)', 'Survei budaya', 80, 82, 83, 84, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'KPI divisi & individu terhubung ke Peta Strategi grup', '%', 'higher', 'last', 0, false, false, 'sdm', 'Kontrak kinerja turunan Peta Strategi grup ÷ total × 100', 'Sistem manajemen kinerja', 60, 80, 81, 95, .88, $il],
            ['L3.1', 'L3', 'Porsi transaksi pelanggan melalui kanal digital', '%', 'higher', 'average', 0, false, false, 'infra', 'Transaksi via aplikasi/daring ÷ total transaksi ritel × 100', 'Laporan digital grup', 30, 35, 36, 42, .92, $il],
            ['L3.2', 'L3', 'Mobil bekas terjual melalui platform digital', 'ribu unit', 'higher', 'sum', 1, false, false, 'otomotif', 'Unit mobil bekas via OLXmobbi', 'Laporan divisi', 27.4, 30.0, 33.1, 38.0, .95, $lt],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Menara Astra, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, CEO divisi, Corporate Strategy',
                'summary' => "Pendapatan Rp323,4 T (−2%); laba ke pemilik Rp32,8 T (−3%; 94% target); ROE 14%.\nJasa keuangan +9%, agribisnis +28%, infrastruktur +24% menahan penurunan alat berat & tambang (−24%).\nPenjualan mobil Astra 409 ribu (−15%); pangsa pasar 51%.\nEmisi GRK −18,15% vs 2019; LTIFR 0,092.\nInvestasi kesehatan Rp8,6 T & pergudangan modern.",
                'decisions' => "1. Target 2026: laba ke pemilik Rp34 T, ROE 15%.\n2. Tinjauan Strategis portofolio selesai pertengahan 2026.\n3. Balanced Scorecard grup diturunkan ke tujuh divisi melalui Ruci BSC.",
                'follow_up' => 'Corporate Strategy menurunkan KPI grup ke divisi paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta Tinjauan Strategis memuat kriteria tegas untuk mempertahankan, menumbuhkan, atau melepas setiap bisnis.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, CEO divisi, Corporate Strategy',
                'summary' => match ($m) {
                    2 => 'Penjualan mobil menjelang Lebaran naik; pangsa pasar 52%.',
                    4 => 'Harga batu bara turun lagi; overburden sesuai rencana.',
                    5 => 'Peremajaan sawit tertinggal karena bibit & cuaca.',
                    6 => 'Semester I: laba ke pemilik 95% target; jasa keuangan di atas target.',
                    7 => 'Tinjauan Strategis: dua opsi portofolio dibahas, keputusan mundur ke Q4.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Program pembiayaan bunga ringan untuk model xEV.',
                    4 => 'Optimalkan biaya kontraktor tambang.',
                    5 => 'Tambah pembibitan & kontraktor tanam.',
                    6 => 'Pertahankan target laba; pantau divisi alat berat bulanan.',
                    7 => 'Komite investasi menyiapkan analisis tambahan untuk keputusan Q4.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Corporate Strategy',
                'summary' => $q === 1 ? 'Laba Q1 96% target; pangsa pasar mobil 52%.' : 'Laba H1 95% target; replanting 68% rencana; Tinjauan Strategis 35%.',
                'decisions' => $q === 1 ? 'Lanjutkan program xEV; evaluasi investasi emas.' : 'Tinjauan Strategis dipercepat; laporan bulanan ke Dewan Komisaris.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit',
            'summary' => 'Komisaris menyoroti Tinjauan Strategis yang mundur, peremajaan sawit, dan tekanan merek EV.',
            'decisions' => 'Direksi melaporkan kemajuan Tinjauan Strategis dan KPI divisi setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon tanggal pasti keputusan Tinjauan Strategis dan dampak tiap opsi terhadap ROE grup 2027.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, CEO divisi, Corporate Strategy',
            'summary' => 'Realisasi September sebagian masih draf; pembiayaan baru dikembalikan untuk dilengkapi pembiayaan syariah.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; nilai investasi strategis 2025 diungkap secara agregat di laporan tahunan.';

            return [
                'defs' => [
                    'kesehatan' => ['ASII-25-01', 'Investasi bisnis kesehatan', 'Penyertaan di Hermina, Halodoc, dan RS Heartology.', 'T3', 'infra', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 5, ['C4', 'P2'], 'Investasi kesehatan Rp8,6 T.'],
                    'gudang' => ['ASII-25-02', 'Akuisisi pergudangan modern', 'Integrasi gudang modern ke Mega Manunggal Property.', 'T3', 'infra', '2025-02-01', '2025-12-31', 'selesai', 3, 3, 3, 4, ['C4'], 'Laba properti Rp719 M (termasuk goodwill negatif).'],
                    'buyback' => ['ASII-25-03', 'Pembelian kembali saham', 'Buyback Rp2 T Astra & Rp2 T United Tractors.', 'T1', 'cfo', '2025-03-01', '2025-12-31', 'selesai', 4, 3, 2, 5, ['F2'], 'Buyback selesai awal 2026.'],
                    'k3' => ['ASII-25-04', 'Mandatory Key Action K3', 'Standar wajib K3 di seluruh divisi berisiko tinggi.', 'T4', 'esg', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['P4'], 'LTIFR 0,092 (2024: 0,241).'],
                    'mobbi' => ['ASII-25-05', 'Platform mobil bekas digital', 'Pengembangan OLXmobbi bersama mitra.', 'T3', 'otomotif', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['L3'], '33.100 unit (+21%).'],
                ],
                'activities' => [
                    ['kesehatan', 'Uji tuntas & penyertaan', 'infra', '2025-01-01', '2025-09-30', 100], ['kesehatan', 'Integrasi tata kelola investasi', 'infra', '2025-07-01', '2025-12-31', 100],
                    ['gudang', 'Akuisisi & integrasi aset', 'infra', '2025-02-01', '2025-12-31', 100],
                    ['buyback', 'Pelaksanaan pembelian kembali', 'cfo', '2025-03-01', '2025-12-31', 100],
                    ['k3', 'Audit penerapan MKA di divisi', 'esg', '2025-01-01', '2025-12-31', 100],
                    ['mobbi', 'Perluasan jaringan inspeksi', 'otomotif', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['kesehatan', 'Penyertaan selesai', '2025-09-30', true], ['k3', 'MKA diterapkan di seluruh divisi', '2025-12-31', true], ['buyback', 'Buyback Astra selesai', '2025-12-31', true],
                ],
                'budgets' => [
                    ['kesehatan', 'Penyertaan saham bisnis kesehatan', 'capex', 8_600_000_000_000, 8_600_000_000_000, $note, 'Kas internal'],
                    ['gudang', 'Akuisisi pergudangan', 'capex', 1_500_000_000_000, 1_420_000_000_000, $note, 'Kas internal'],
                    ['buyback', 'Pembelian kembali saham Astra', 'capex', 2_000_000_000_000, 1_850_000_000_000, $note, 'Kas internal'],
                    ['k3', 'Program K3 grup', 'opex', 120_000_000_000, 112_000_000_000, null],
                    ['mobbi', 'Pengembangan platform & inspeksi', 'opex', 150_000_000_000, 138_000_000_000, null],
                ],
            ];
        }

        return [
            'defs' => [
                'tinjauan' => ['ASII-26-01', 'Tinjauan Strategis portofolio', 'Evaluasi setiap bisnis terhadap biaya modal & potensi pertumbuhan.', 'T1', 'strategi', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['P2', 'F2'], 'Keputusan portofolio & peningkatan TSR.'],
                'xev' => ['ASII-26-02', 'Portofolio xEV bersama prinsipal', 'Hybrid & EV dengan pembiayaan dan purna jual Astra.', 'T2', 'otomotif', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 3, ['C1'], 'Pangsa pasar mobil 53%.'],
                'pembiayaan' => ['ASII-26-03', 'Pembiayaan digital terintegrasi', 'Pengajuan kredit & asuransi dari aplikasi dealer.', 'T2', 'jasakeu', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C2', 'L3'], 'Pembiayaan baru Rp118 T.'],
                'tambang' => ['ASII-26-04', 'Efisiensi kontraktor tambang', 'Produktivitas armada & biaya bahan bakar.', 'T2', 'alatberat', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['P1', 'C3'], 'Biaya per bcm turun 5%.'],
                'emas' => ['ASII-26-05', 'Pengembangan tambang emas & nikel', 'Diversifikasi dari batu bara termal.', 'T4', 'alatberat', '2026-01-01', '2027-06-30', 'berjalan', 4, 3, 4, 5, ['P1', 'P3'], 'Produksi emas 260 ribu oz.'],
                'replanting' => ['ASII-26-06', 'Peremajaan & produktivitas kebun', 'Replanting 6.000 ha & mekanisasi panen.', 'T2', 'agri', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['P1'], 'Replanting 6.000 ha.'],
                'kesehatan' => ['ASII-26-07', 'Sinergi ekosistem kesehatan', 'Rujukan, asuransi, dan layanan karyawan grup.', 'T3', 'infra', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 2, ['C4'], 'Sinergi pendapatan dari investasi kesehatan.'],
                'ebt' => ['ASII-26-08', 'Energi terbarukan & efisiensi', 'PLTS, PLTM, dan efisiensi energi di seluruh divisi.', 'T4', 'esg', '2026-01-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['P3'], 'Bauran EBT 50%; emisi −20%.'],
                'zeroharm' => ['ASII-26-09', 'Zero harm', 'Risiko kritis, kontraktor, dan kepemimpinan K3.', 'T4', 'esg', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 1, ['P4'], 'LTIFR 0,085; zero fatality.'],
                'bsc' => ['ASII-26-10', 'Balanced Scorecard berjenjang grup–divisi', 'Satu Peta Strategi grup diturunkan ke divisi & individu.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 2, 1, ['L2', 'L1'], '95% kontrak kinerja terhubung.'],
            ],
            'activities' => [
                ['tinjauan', 'Analisis imbal hasil per bisnis', 'strategi', '2026-01-01', '2026-04-30', 100], ['tinjauan', 'Penyusunan opsi portofolio', 'strategi', '2026-04-01', '2026-08-31', 60], ['tinjauan', 'Keputusan & eksekusi', 'strategi', '2026-09-01', '2026-12-31', 0],
                ['xev', 'Peluncuran model hybrid baru', 'otomotif', '2026-01-01', '2026-06-30', 100], ['xev', 'Program pembiayaan xEV', 'jasakeu', '2026-03-01', '2026-12-31', 65],
                ['pembiayaan', 'Integrasi aplikasi dealer–pembiayaan', 'jasakeu', '2026-01-15', '2026-08-31', 85],
                ['tambang', 'Pemantauan produktivitas armada real-time', 'alatberat', '2026-01-01', '2026-09-30', 80],
                ['emas', 'Pengembangan tambang emas baru', 'alatberat', '2026-01-01', '2027-06-30', 40],
                ['replanting', 'Pembibitan & penanaman', 'agri', '2026-01-01', '2026-12-31', 55], ['replanting', 'Mekanisasi panen', 'agri', '2026-03-01', '2026-12-31', 50],
                ['kesehatan', 'Program kesehatan karyawan grup', 'infra', '2026-02-01', '2026-12-31', 60],
                ['ebt', 'PLTS di pabrik & kantor', 'esg', '2026-01-01', '2026-12-31', 65],
                ['zeroharm', 'Audit risiko kritis kontraktor tambang', 'esg', '2026-01-01', '2026-06-30', 100], ['zeroharm', 'Kepemimpinan K3 untuk supervisor', 'esg', '2026-03-01', '2026-12-31', 70],
                ['bsc', 'Peta Strategi grup–divisi', 'strategi', '2026-01-15', '2026-03-31', 100], ['bsc', 'Kontrak kinerja individu', 'sdm', '2026-03-01', '2026-12-31', 75],
            ],
            'milestones' => [
                ['tinjauan', 'Opsi portofolio ke Dewan Komisaris', '2026-06-30', false], ['xev', 'Model hybrid baru diluncurkan', '2026-06-30', true], ['bsc', 'Peta Strategi tujuh divisi disetujui', '2026-03-31', true],
                ['replanting', 'Replanting 3.000 ha', '2026-06-30', false], ['zeroharm', 'Audit risiko kritis selesai', '2026-06-30', true],
            ],
            'budgets' => [
                ['tinjauan', 'Penasihat strategis', 'opex', 60_000_000_000, 34_000_000_000, null],
                ['xev', 'Peluncuran & jaringan xEV', 'opex', 400_000_000_000, 265_000_000_000, null],
                ['pembiayaan', 'Integrasi sistem', 'capex', 90_000_000_000, 66_000_000_000, null, 'Kas internal'],
                ['tambang', 'Sistem pemantauan armada', 'capex', 150_000_000_000, 108_000_000_000, null, 'Kas internal'],
                ['emas', 'Pengembangan tambang emas', 'capex', 2_500_000_000_000, 1_150_000_000_000, null, 'Kas internal & pinjaman'],
                ['replanting', 'Bibit, tanam & mekanisasi', 'capex', 450_000_000_000, 220_000_000_000, null, 'Kas internal'],
                ['kesehatan', 'Program sinergi kesehatan', 'opex', 40_000_000_000, 22_000_000_000, null],
                ['ebt', 'PLTS & efisiensi energi', 'capex', 600_000_000_000, 360_000_000_000, null, 'Kas internal'],
                ['zeroharm', 'Program K3 grup', 'opex', 130_000_000_000, 92_000_000_000, null],
                ['bsc', 'Implementasi BSC berjenjang', 'opex', 8_000_000_000, 5_600_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Pangsa pasar mobil turun 5 poin', 'Pangsa pasar 51% (2024: 56%); merek EV Tiongkok menekan segmen menengah.', 'tinjauan', 'tahunan', 'pelanggan', 'tinggi', null, 'C1.1', null, 'komisaris', '2026-02-24',
                [['Portofolio xEV & pembiayaan 2026', 'otomotif', '2026-12-31', 'berjalan', 60]], null],
            ['Laba alat berat & tambang turun 24%', 'Harga batu bara turun 13% dan volume overburden −10%.', 'tinjauan', 'tahunan', 'keuangan', 'sedang', null, 'F1.5', null, 'cfo', '2026-02-24',
                [['Program efisiensi kontraktor tambang', 'alatberat', '2026-06-30', 'selesai', 100]], ['2026-07-15', 'Program efisiensi berjalan; biaya per bcm turun.']],
        ] : [
            ['Tinjauan Strategis mundur', 'Opsi portofolio belum diputuskan per Agustus (35% tonggak vs rencana 60%).', 'timwas', 'boc', 'proses', 'kritis', null, 'P2.1', 'tinjauan', 'komisaris', '2026-07-28',
                [['Jadwal keputusan pasti & analisis tambahan', 'strategi', '2026-10-31', 'berjalan', 50]], null],
            ['Peremajaan sawit tertinggal', '2.900 ha per Agustus vs rencana 4.200 ha; bibit terlambat & curah hujan tinggi.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P1.4', 'replanting', 'agri', '2026-07-22',
                [['Tambah pembibitan & kontraktor tanam', 'agri', '2026-11-30', 'berjalan', 55]], null],
            ['Produksi emas di bawah rencana', 'Kadar bijih lebih rendah dari model.', 'internal', 'rm6', 'proses', 'sedang', null, 'P1.3', 'emas', 'alatberat', '2026-07-12',
                [['Pemodelan ulang cadangan', 'alatberat', '2026-10-31', 'berjalan', 60]], null],
            ['Laba divisi alat berat masih tertekan', 'Laba H1 84% dari target karena harga batu bara.', 'tinjauan', 'q2', 'keuangan', 'sedang', null, 'F1.5', 'tambang', 'cfo', '2026-07-22',
                [['Kontrak jasa tambang dengan eskalasi biaya', 'alatberat', '2026-12-31', 'berjalan', 35]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Peta jalan elektrifikasi prinsipal', 'eksternal', 'xev', 'C1', 'Prinsipal otomotif', 'Ketersediaan model xEV ditentukan prinsipal.', 'otomotif', 'tinggi', 'Rencana produk bersama 3 tahun.'],
                ['Persetujuan pemegang saham atas opsi portofolio', 'eksternal', 'tinjauan', 'P2', 'Pemegang saham', 'Transaksi material butuh persetujuan.', 'strategi', 'sedang', 'Komunikasi dini & kajian independen.'],
                ['Izin & cadangan tambang emas', 'eksternal', 'emas', 'P1', 'Kementerian ESDM', 'Perizinan & pemodelan cadangan.', 'alatberat', 'sedang', 'Pengeboran tambahan & konsultan independen.'],
                ['Pasokan bibit sawit unggul', 'eksternal', 'replanting', 'P1', 'Produsen benih', 'Replanting bergantung pada bibit tersertifikasi.', 'agri', 'sedang', 'Pembibitan internal.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan grup–divisi di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Komite investasi grup', 'rapat', 'Setiap investasi diuji terhadap hurdle rate.', 'Bulanan', 'cfo', 'melembaga'],
                ['Tinjauan bisnis divisi (business review)', 'rapat', 'CEO divisi memaparkan BSC divisi kepada Direksi.', 'Kuartalan', 'dirut', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan Tinjauan Strategis.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Mandatory Key Action K3', 'sop', 'Standar wajib K3 di seluruh divisi berisiko tinggi.', 'Berkelanjutan', 'esg', 'melembaga'],
                ['Budaya Catur Dharma', 'budaya', 'Internalisasi filosofi melalui kepemimpinan & program karyawan.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
