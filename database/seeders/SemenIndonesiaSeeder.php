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
 * Demo PT Semen Indonesia (Persero) Tbk — SIG (kode SMGR), produsen semen terbesar di Indonesia.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (pendapatan, EBITDA, laba, volume domestik/ekspor, pangsa pasar, jaringan ritel, indikator ESG).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: pemimpin pasar di industri kelebihan pasokan — volume & laba tertekan,
 *   turnaround lewat model ritel, keunggulan biaya, ekspor, dan dekarbonisasi.
 */
class SemenIndonesiaSeeder extends ShowcaseSeeder
{
    public const CODE = 'SMGR';

    public const LT = 'Laporan Tahunan 2025 PT Semen Indonesia (Persero) Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Semen Indonesia (Persero) Tbk', 'legal_name' => 'PT Semen Indonesia (Persero) Tbk',
            'industry' => 'Semen & solusi bahan bangunan (semen, klinker, beton siap pakai, precast, mortar)',
            'address' => 'South Quarter Tower A, Jl. R.A. Kartini Kav. 8, Cilandak', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 5296 1888', 'website' => 'https://www.sig.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham SMGR). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Musim hujan (Jan–Feb) & Lebaran menekan konstruksi; puncak permintaan Juli–November. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 7.6, 7.2, 7.9, 6.9, 8.4, 8.3, 8.9, 8.9, 8.9, 9.2, 9.0, 8.8]),
            2026 => self::weights([1 => 7.6, 7.3, 6.8, 8.0, 8.4, 8.3, 8.9, 8.9, 8.9, 9.2, 9.0, 8.7]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'F2.4', 'F3.1', 'C2.3', 'C3.2', 'C4.1', 'C4.2', 'P1.3', 'P2.1', 'P3.1', 'P3.3', 'P4.2', 'L1.2', 'L2.1'];
    }

    protected function missingSeptember(): array
    {
        return ['C2.2', 'L1.3'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P1.2', 'Porsi biaya energi September belum memasukkan penyesuaian harga batu bara kontrak Agustus. Mohon hitung ulang dan lampirkan rekonsiliasi per pabrik.'];
    }

    protected function criticalIn2026(): array
    {
        return ['L3.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 2, 3, 4];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin SIG (contoh)', 'smgr.admin', 'Admin Sistem', 'Strategi Korporat', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'smgr.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan & Manajemen Portofolio (contoh)', 'smgr.keuangan', 'Direktur Keuangan & Manajemen Portofolio', 'Keuangan', ['keuangan']],
            'pemasaran' => ['Direktur Pemasaran & Penjualan (contoh)', 'smgr.pemasaran', 'Direktur Pemasaran & Penjualan', 'Pemasaran & Penjualan', ['kepala-unit']],
            'operasi' => ['Direktur Operasi (contoh)', 'smgr.operasi', 'Direktur Operasi', 'Operasi Pabrik', ['kepala-unit']],
            'rantai' => ['SVP Supply Chain & Logistik (contoh)', 'smgr.logistik', 'SVP Supply Chain & Logistik', 'Rantai Pasok & Logistik', ['kepala-unit']],
            'ekspor' => ['SVP Penjualan Regional & Ekspor (contoh)', 'smgr.ekspor', 'SVP Regional Sales & Export', 'Penjualan Regional & Ekspor', ['kepala-unit']],
            'esg' => ['SVP Keberlanjutan & K3 (contoh)', 'smgr.esg', 'SVP Sustainability & HSE', 'Keberlanjutan & K3', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'smgr.sdm', 'Direktur Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'ti' => ['SVP Digital & Teknologi Informasi (contoh)', 'smgr.ti', 'SVP Digital & IT', 'Digital & Teknologi Informasi', ['kepala-unit']],
            'strategi' => ['Strategy & Transformation Office (contoh)', 'smgr.strategi', 'SVP Strategy & Transformation Office', 'Strategi & Transformasi', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'smgr.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'smgr.analis', 'Performance Analyst', 'Strategi & Transformasi', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi Pilihan Utama untuk Solusi Bangunan Berkelanjutan di Asia Tenggara.',
            'mission' => "1. Menghadirkan solusi bangunan yang menjadi pilihan utama pelanggan (First Choice).\n2. Menjalankan bisnis yang berkelanjutan: rendah karbon, efisien, dan bertanggung jawab (Sustainable).\n3. Memperluas portofolio dari semen ke solusi bangunan terintegrasi (Building Solution).\n4. Menciptakan nilai bagi pemegang saham, pelanggan, karyawan, dan masyarakat.",
            'purpose' => 'Membangun Indonesia dengan solusi bangunan yang terjangkau, bermutu, dan rendah karbon, dari kota besar sampai desa.',
            'tagline' => 'Turnaround strategis menuju solusi bangunan berkelanjutan',
            'brand_promise' => 'Semen dan solusi bangunan bermutu, tersedia dekat dengan pembangun di seluruh Nusantara.',
            'value_proposition' => 'Kapasitas 56,5 juta ton/tahun; 9 pabrik terintegrasi, 27 packing plant, 7 grinding plant, 7 pelabuhan; 289 distributor dan 58.568 toko ritel; merek Semen Gresik, Semen Padang, Semen Tonasa, Dynamix, Semen Baturaja.',
            'culture_statement' => 'Nilai inti BUMN AKHLAK: Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif.',
            'history' => "1957: Semen Gresik diresmikan (kapasitas 250 ribu ton).\n1991: tercatat di Bursa Efek (SMGR).\n1995: konsolidasi Semen Padang & Semen Tonasa.\n2012: menjadi strategic holding dengan nama Semen Indonesia.\n2019: akuisisi Holcim Indonesia → Solusi Bangun Indonesia (SBI).\n2020: rebranding menjadi SIG.\n2022: integrasi Semen Baturaja.\n2025: 51,20% saham seri B dialihkan ke PT Danantara Asset Management; Direksi baru memulai transformasi proses bisnis (Juli).",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Amanah', 'Trustworthy', 'Memegang teguh kepercayaan yang diberikan.', "Melaporkan volume, harga, dan stok apa adanya\nMenepati janji kirim ke distributor"],
            ['Kompeten', 'Competent', 'Terus belajar dan mengembangkan kapabilitas.', "Menguasai proses kiln & efisiensi energi\nBelajar dari insiden keselamatan"],
            ['Harmonis', 'Harmonious', 'Saling peduli dan menghargai perbedaan.', "Pabrik, logistik, dan penjualan memecahkan masalah bersama\nMenghormati masyarakat sekitar tambang"],
            ['Loyal', 'Loyal', 'Berdedikasi dan mengutamakan kepentingan bangsa dan negara.', "Mendukung pembangunan infrastruktur & perumahan rakyat\nMenjaga nama baik merek nasional"],
            ['Adaptif', 'Adaptive', 'Terus berinovasi dan antusias menggerakkan ataupun menghadapi perubahan.', "Mencoba produk rendah karbon & turunan semen\nMemakai data platform ritel untuk keputusan"],
            ['Kolaboratif', 'Collaborative', 'Membangun kerja sama yang sinergis.', "Sinergi antarmerek dalam satu grup\nKemitraan dengan distributor & toko bangunan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Jaringan produksi & distribusi nasional', 'Sumber daya fisik', '9 pabrik terintegrasi, 27 packing plant, 7 grinding plant, 7 pelabuhan dari Aceh sampai Sulawesi.', 4, 5, true, 'Optimasi jejaring pasok terdekat ke pasar.'],
            ['Portofolio merek kuat', 'Kompetensi', 'Semen Gresik, Semen Padang, Semen Tonasa, Dynamix, Semen Baturaja; pangsa pasar 46,9%.', 4, 5, true, 'Strategi merek per segmen & wilayah.'],
            ['Ekosistem ritel & distributor', 'Kompetensi', '289 distributor dan 58.568 toko ritel; platform B2B untuk pemesanan.', 3, 5, true, 'Model bisnis ritel baru sejak transformasi Juli 2025.'],
            ['Keunggulan biaya produksi', 'Sistem', 'Utilisasi ±55%; biaya energi dominan.', 2 + $lvl, 4, false, 'Bahan bakar alternatif, PLTS, efisiensi kiln.'],
            ['Kapabilitas produk rendah karbon', 'Kompetensi', 'Substitusi klinker 34%; TSR 11,13%; intensitas CO₂ 561 kg/ton.', 3, 5, true, 'Peta jalan dekarbonisasi.'],
            ['Visibilitas rantai pasok digital', 'Sistem', 'Data pabrik, gudang, dan pengiriman belum dalam satu control tower.', 2 + $lvl, 4, false, 'Control tower logistik 2026.'],
            ['Manajemen kinerja berbasis KPI', 'Sistem', 'KPI korporat sudah ada; turunan ke anak usaha belum seragam.', 2 + $lvl, 4, false, 'Cascading KPI melalui Ruci BSC.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Industri semen 2025: kelebihan pasokan', '2026-01-20', 'eksternal', self::LT.' — Analisis & Pembahasan Manajemen',
                'Kapasitas terpasang nasional ±113 juta ton dengan utilisasi industri ±56%; permintaan domestik melemah, semen kantong −0,8%, curah −12,2%; harga ekspor tertekan pasokan Tiongkok.',
                'Persaingan harga berlanjut; menang lewat biaya, kedekatan ke pelanggan, dan ekspor selektif.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan Rp35,24 T (−2,6%; 92,6% RKAP); volume 37,93 juta ton (95,9% RKAP); EBITDA Rp4,49 T (marjin 12,75%); laba ke pemilik Rp190,8 M (2024: Rp719,8 M). Ekspor/regional 7,95 juta ton (+14,9%).',
                'Volume ekspor menolong, tetapi marjin domestik dan laba harus dipulihkan pada 2026.'],
            ['Perubahan kepemilikan & transformasi', '2026-01-28', 'internal', self::LT.' — Peristiwa Penting',
                '51,20% saham seri B dialihkan ke PT Danantara Asset Management; negara tetap memegang saham Dwiwarna. Direksi baru (Juli 2025) memulai transformasi proses bisnis: model ritel, keunggulan biaya, produk turunan, portofolio anak usaha.',
                'Tata kelola holding baru menuntut KPI yang jelas dan pelaporan kinerja rutin.'],
            ['Prospek 2026', '2026-02-05', 'eksternal', self::LT.' — Prospek Usaha',
                'Permintaan semen nasional diperkirakan tumbuh 1–2%; curah pulih oleh IKN & proyek PU; program Koperasi Desa Merah Putih berpotensi menambah 3–4 juta ton; rehabilitasi pascabanjir Sumatera.',
                'Siapkan pasokan & distribusi ke desa; kunci volume proyek pemerintah tanpa mengorbankan harga.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Belanja infrastruktur & program desa', 'Proyek PU, IKN, dan Koperasi Desa Merah Putih menopang permintaan.', 'peluang', 4, 4, 'naik', 'Tim proyek pemerintah & pasokan ke desa.'],
            ['politik', 'Moratorium pabrik semen baru', 'Usulan pembatasan kapasitas baru untuk mengatasi kelebihan pasokan.', 'peluang', 3, 2, 'stabil', 'Advokasi melalui asosiasi industri.'],
            ['ekonomi', 'Daya beli & pasar properti', 'Penjualan semen kantong (ritel) melemah.', 'ancaman', 4, 4, 'stabil', 'Program loyalitas toko & produk ekonomis.'],
            ['ekonomi', 'Harga batu bara & energi', 'Energi adalah komponen biaya terbesar produksi klinker.', 'ancaman', 5, 4, 'naik', 'Bahan bakar alternatif & PLTS.'],
            ['ekonomi', 'Harga ekspor semen & klinker', 'Pasokan Tiongkok menekan harga ekspor.', 'ancaman', 3, 4, 'naik', 'Pilih pasar ekspor bermarjin.'],
            ['sosial', 'Kebutuhan rumah & renovasi', 'Backlog perumahan & renovasi rumah swadaya.', 'peluang', 4, 4, 'naik', 'Solusi bangunan untuk rumah tapak.'],
            ['teknologi', 'Digitalisasi rantai pasok & ritel', 'Pemesanan B2B, pelacakan armada, data toko.', 'peluang', 4, 5, 'naik', 'Platform ritel & control tower.'],
            ['lingkungan', 'Dekarbonisasi & pajak karbon', 'Industri semen penyumbang emisi besar; pembeli mulai meminta semen hijau.', 'ancaman', 5, 4, 'naik', 'Peta jalan net zero & semen rendah karbon.'],
            ['lingkungan', 'Bencana & cuaca ekstrem', 'Banjir & curah hujan mengganggu konstruksi dan logistik.', 'ancaman', 3, 3, 'naik', 'Rencana kontinjensi pasokan.'],
            ['hukum', 'Tata kelola BUMN di bawah Danantara', 'Penilaian kinerja & KPI holding baru.', 'peluang', 3, 4, 'naik', 'Pelaporan kinerja terstandar.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Produsen semen nasional & pemain baru', 'Utilisasi industri ±56%; perang harga di beberapa wilayah.', 5, 'Keunggulan biaya & layanan distribusi.'],
            ['kekuatan_pembeli', 'Distributor besar & kontraktor proyek', 'Pembeli proyek menekan harga melalui tender.', 4, 'Diferensiasi layanan & produk turunan.'],
            ['kekuatan_pemasok', 'Pemasok batu bara & energi', 'Harga energi mengikuti pasar global.', 4, 'Kontrak jangka panjang & bahan bakar alternatif.'],
            ['pendatang_baru', 'Pabrik baru & importir', 'Pemain asing membangun kapasitas & grinding plant.', 3, 'Kedekatan pasar & merek.'],
            ['produk_pengganti', 'Material alternatif (baja ringan, panel)', 'Substitusi sebagian kecil penggunaan semen.', 2, 'Masuk ke solusi bangunan pracetak.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Semen kantong (ritel/toko bangunan)', 'Domestik', 'Volume 21,66 juta ton (−0,8%).', 57, 'stabil', true, 'Ketersediaan di toko, harga stabil, merek tepercaya.', 'Model ritel & loyalitas toko.'],
            ['Semen curah (proyek & industri beton)', 'Domestik', 'Volume curah turun 12,2%.', 22, 'turun', true, 'Pasokan terjamin, harga kontrak, layanan teknis.', 'Tim proyek pemerintah & BUMN karya.'],
            ['Ekspor & regional', 'Ekspor', '7,95 juta ton (+14,9%) ke Bangladesh, Australia, Taiwan, Filipina, Timor Leste, dll.; TLCC Vietnam.', 21, 'naik', true, 'Harga kompetitif & keandalan kirim.', 'Pasar bermarjin & kontrak jangka panjang.'],
            ['Produk turunan & solusi bangunan', 'Solusi', 'Beton siap pakai, precast, mortar, bata interlock.', 0, 'naik', true, 'Solusi sekali beli untuk pembangun.', 'Perluas portofolio turunan semen.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — volume penjualan (porsi ilustratif)']);
        }

        foreach ([
            ['PT Semen Indonesia (Persero) Tbk', 'Produsen semen BUMN', 1, 35244, -2.6, 0.5, true, 'Pangsa pasar 46,9%, jaringan nasional, merek kuat.', 'Laba tipis, utilisasi rendah, biaya energi tinggi.'],
            ['Produsen semen swasta nasional besar', 'Semen swasta', 2, null, null, null, false, 'Neraca kuat & efisien.', 'Jaringan di luar Jawa terbatas.'],
            ['Produsen semen asing di Indonesia', 'Semen asing', 3, null, null, null, false, 'Harga agresif & kapasitas baru.', 'Merek kurang dikenal di ritel.'],
            ['Importir & grinding plant independen', 'Semen impor', 4, null, null, null, false, 'Biaya klinker murah.', 'Pasokan tidak stabil.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Standar Nasional Indonesia semen', 'Badan Standardisasi Nasional', 'SNI semen portland & semen portland komposit', 'Mutu', null, 'Seluruh produk wajib memenuhi SNI.', 'Izin edar & kepercayaan pelanggan.', 'patuh', 'operasi', null],
            ['Tata kelola BUMN & holding Danantara', 'Danantara / Kementerian BUMN', 'Ketentuan tata kelola & KPI BUMN', 'Tata kelola', '2025-01-01', 'KPI Direksi, laporan kinerja, budaya AKHLAK.', 'Remunerasi & evaluasi Direksi.', 'patuh', 'strategi', null],
            ['Izin usaha pertambangan (IUP) bahan baku', 'Kementerian ESDM', 'UU Minerba & turunannya', 'Operasi', null, 'Tambang batu kapur & tanah liat.', 'Kelangsungan bahan baku.', 'patuh', 'operasi', null],
            ['Nilai ekonomi karbon & pelaporan emisi', 'Kementerian Lingkungan Hidup', 'Perpres No. 98/2021', 'Lingkungan', '2021-10-29', 'Inventarisasi & pelaporan emisi GRK; potensi pajak karbon.', 'Biaya karbon di masa depan.', 'sebagian', 'esg', $year === 2025 ? null : '2026-12-31'],
            ['PROPER', 'Kementerian Lingkungan Hidup', 'Program Penilaian Peringkat Kinerja Perusahaan', 'Lingkungan', null, 'Kinerja lingkungan pabrik (2025: 3 Emas, 5 Hijau).', 'Reputasi & izin.', 'patuh', 'esg', null],
            ['Sistem manajemen anti-penyuapan', 'Internal / BSN', 'SNI ISO 37001', 'Tata kelola', null, 'Pengendalian gratifikasi & penyuapan.', 'Integritas pengadaan & penjualan.', 'patuh', 'strategi', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data toko & pemilik toko di platform ritel.', 'Sanksi & reputasi.', 'sebagian', 'ti', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham (Danantara, negara via Dwiwarna, publik)', 'internal', 'Pemilik', 5, 5, 'Laba pulih, dividen, tata kelola.', 'RUPS, paparan publik, laporan ke holding.', 'Sekretaris Perusahaan'],
            'distributor' => ['Distributor (289) & toko bangunan (58.568)', 'eksternal', 'Mitra saluran', 4, 5, 'Pasokan lancar, margin wajar, program loyalitas.', 'Pertemuan distributor & aplikasi ritel.', 'Pemasaran'],
            'proyek' => ['Kontraktor & BUMN karya', 'eksternal', 'Pelanggan', 4, 4, 'Pasokan curah terjamin & harga kompetitif.', 'Kontrak & layanan teknis.', 'Penjualan Proyek'],
            'pemerintah' => ['Pemerintah (PU, IKN, program desa)', 'eksternal', 'Regulator & pembeli', 5, 4, 'Dukungan program pembangunan.', 'Koordinasi program & pengadaan.', 'Hubungan Pemerintah'],
            'masyarakat' => ['Masyarakat sekitar pabrik & tambang', 'eksternal', 'Komunitas', 3, 5, 'Lingkungan terjaga, kesempatan kerja, TJSL.', 'Forum warga & program TJSL.', 'CSR'],
            'pemasok' => ['Pemasok energi, kantong, & logistik', 'eksternal', 'Pemasok', 3, 4, 'Pembayaran tepat waktu & volume stabil.', 'Kontrak jangka panjang.', 'Pengadaan'],
            'karyawan' => ['Karyawan (8.969 orang)', 'internal', 'SDM', 4, 5, 'Kepastian kerja, keselamatan, pengembangan.', 'Town hall transformasi & survei budaya.', 'Human Capital'],
            'kreditur' => ['Kreditur & pemegang obligasi', 'eksternal', 'Pemberi dana', 4, 4, 'Leverage terjaga, peringkat idAAA.', 'Paparan kinerja.', 'Treasury'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'laba' => ['Laba turun tajam', 'Laba ke pemilik Rp190,8 M (2024: Rp719,8 M); marjin EBITDA 12,75% (15,18%).', 'keuangan', 5, 5, 'cfo', ['pemegang_saham', 'kreditur']],
            'volume' => ['Volume domestik & pangsa pasar menurun', 'Penjualan domestik 29,98 juta ton (−4,3%); pangsa pasar 46,9%.', 'pelanggan', 5, 5, 'pemasaran', ['distributor', 'proyek']],
            'oversupply' => ['Kelebihan pasokan industri', 'Utilisasi industri ±56%; perang harga.', 'pelanggan', 4, 5, 'dirut', ['pemerintah']],
            'biaya' => ['Biaya energi & logistik tinggi', 'Energi & distribusi menentukan daya saing harga.', 'proses', 5, 4, 'operasi', ['pemasok']],
            'karbon' => ['Tuntutan dekarbonisasi', 'Industri padat emisi; pembeli & investor meminta semen hijau.', 'proses', 3, 4, 'esg', ['pemegang_saham', 'masyarakat']],
            'capex' => ['Realisasi belanja modal rendah', 'Rp1.104 M dari RKAP Rp1.721 M (64%).', 'proses', 3, 3, 'cfo', ['pemegang_saham']],
            'budaya' => ['Budaya kinerja di masa transformasi', 'Perubahan Direksi & proses bisnis butuh akuntabilitas KPI.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Pemimpin pasar & merek kuat', 'Pangsa pasar 46,9%; lima merek nasional.', 0.13, 4],
            ['S', 'Jaringan pabrik & pelabuhan nasional', '9 pabrik terintegrasi, 7 pelabuhan.', 0.11, 4],
            ['S', 'Ekosistem ritel', '58.568 toko ritel & 289 distributor.', 0.08, 3],
            ['S', 'Peringkat idAAA & leverage moderat', 'DER berbunga 23,9%.', 0.06, 3],
            ['W', 'Marjin & laba tipis', 'Marjin usaha 3,55%; ROE 0,44%.', 0.14, 1],
            ['W', 'Utilisasi rendah & biaya tetap tinggi', 'Utilisasi ±55%.', 0.10, 2],
            ['W', 'Eksekusi capex lambat', '64% dari RKAP.', 0.05, 2],
            ['O', 'Program desa & infrastruktur', 'KDMP +3–4 juta ton; IKN; rehabilitasi Sumatera.', 0.10, 3],
            ['O', 'Ekspor regional', 'Ekspor +14,9%.', 0.06, 3],
            ['O', 'Semen hijau & produk turunan', 'Permintaan solusi rendah karbon.', 0.05, 2],
            ['T', 'Perang harga akibat kelebihan pasokan', 'Utilisasi industri ±56%.', 0.06, 2],
            ['T', 'Harga energi', 'Batu bara & listrik.', 0.04, 2],
            ['T', 'Regulasi karbon', 'Potensi pajak karbon.', 0.02, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Pasokan program desa & infrastruktur', 'Gunakan jaringan nasional untuk menangkap volume KDMP, IKN, dan rehabilitasi.', 5, ['volume']],
            ['SO', 'Model bisnis ritel berbasis platform', 'Toko terhubung, pesanan digital, program loyalitas.', 4, ['volume', 'oversupply']],
            ['WO', 'Ekspor selektif untuk mengisi utilisasi', 'Pakai kapasitas lebih untuk pasar ekspor bermarjin.', 4, ['laba']],
            ['WO', 'Produk turunan & semen hijau', 'Masuk ke solusi bangunan bernilai tambah.', 3, ['laba', 'karbon']],
            ['ST', 'Keunggulan biaya energi', 'Bahan bakar alternatif, PLTS, efisiensi kiln.', 5, ['biaya', 'karbon']],
            ['WT', 'Disiplin biaya tetap & portofolio anak usaha', 'Rasionalisasi aset & entitas yang tidak produktif.', 4, ['laba', 'capex']],
            ['WT', 'Budaya kinerja & KPI berjenjang', 'Akuntabilitas sampai unit & individu.', 3, ['budaya']],
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
            'T1' => ['First Choice: Pelanggan & Ritel', 'Pilihan utama toko, pembangun, dan proyek; volume domestik pulih.', '#B22234'],
            'T2' => ['Keunggulan Biaya & Operasi', 'Biaya per ton terendah di industri; utilisasi & logistik efisien.', '#1E6FA8'],
            'T3' => ['Building Solution & Ekspor', 'Produk turunan, solusi bangunan, dan pasar regional.', '#E08A1E'],
            'T4' => ['Sustainable: Karbon, Orang & Digital', 'Dekarbonisasi, keselamatan, budaya AKHLAK, dan digital.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Model bisnis ritel: toko terhubung langsung', 'saluran', 'Transformasi proses bisnis sejak Juli 2025.'],
            ['T2', 'Biaya per ton sebagai senjata utama', 'operasi', 'Industri kelebihan pasokan; harga tidak bisa dinaikkan.'],
            ['T3', 'Ekspor hanya ke pasar bermarjin', 'produk', 'Harga ekspor tertekan pasokan Tiongkok.'],
            ['T4', 'Semen hijau sebagai standar portofolio', 'produk', 'Visi solusi bangunan berkelanjutan.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pendapatan & volume tumbuh kembali', 'Pendapatan ±Rp36,6 T; volume 39,3 juta ton.', 'dirut', 2],
            'F2' => ['keuangan', 'T2', 'Profitabilitas pulih', 'EBITDA & laba ke pemilik naik; biaya per ton turun.', 'cfo', 3],
            'F3' => ['keuangan', 'T2', 'Struktur keuangan sehat & capex disiplin', 'Utang berbunga turun; capex tepat waktu.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Pemimpin pasar domestik', 'Pangsa pasar pulih; volume kantong & curah naik.', 'pemasaran', 2],
            'C2' => ['pelanggan', 'T1', 'Ekosistem ritel & distributor yang loyal', 'Toko aktif terhubung platform & bertransaksi digital.', 'pemasaran', 2],
            'C3' => ['pelanggan', 'T3', 'Solusi bangunan & produk hijau', 'Produk turunan & porsi semen hijau naik.', 'pemasaran', 1.5],
            'C4' => ['pelanggan', 'T1', 'Pelanggan puas & pengiriman andal', 'OTIF tinggi; kepuasan pelanggan naik.', 'rantai', 1],
            'P1' => ['proses', 'T2', 'Keunggulan biaya produksi', 'Utilisasi & keandalan kiln naik; biaya energi turun.', 'operasi', 2],
            'P2' => ['proses', 'T2', 'Rantai pasok & logistik efisien', 'Biaya distribusi per ton turun; stok di titik serah optimal.', 'rantai', 1.5],
            'P3' => ['proses', 'T4', 'Dekarbonisasi & ekonomi sirkular', 'Substitusi klinker & bahan bakar alternatif naik; emisi turun.', 'esg', 1.5],
            'P4' => ['proses', 'T4', 'Keselamatan & tata kelola', 'LTIFR turun; temuan audit ditindaklanjuti.', 'esg', 1],
            'L1' => ['sdm', 'T4', 'Organisasi ramping & produktif', 'Volume per karyawan naik.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya AKHLAK & manajemen kinerja', 'KPI individu terhubung ke Peta Strategi.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Digital & data terintegrasi', 'Pesanan digital & control tower logistik.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 2], ['L2', 'P4', 2], ['L2', 'L1', 2], ['L3', 'P2', 3], ['L3', 'C2', 3],
            ['P1', 'F2', 3], ['P1', 'P3', 2], ['P2', 'C4', 3], ['P2', 'F2', 2], ['P3', 'C3', 2], ['P4', 'P1', 1],
            ['C1', 'F1', 3], ['C2', 'C1', 3], ['C3', 'F1', 2], ['C4', 'C1', 2],
            ['F1', 'F2', 3], ['F2', 'F3', 3],
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
            ['F1.1', 'F1', 'Pendapatan konsolidasian', 'Rp juta', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan neto konsolidasian', 'Laporan keuangan konsolidasian', 36186127, 38063000, 35243751, 36600000, .97, $lt],
            ['F1.2', 'F1', 'Volume penjualan semen & klinker', 'ribu ton', 'higher', 'sum', 0, false, true, 'pemasaran', 'Volume domestik + regional/ekspor', 'Laporan penjualan', 38266, 39557, 37929, 39300, .985, $lt],
            ['F1.3', 'F1', 'Volume ekspor & regional', 'ribu ton', 'higher', 'sum', 0, false, false, 'ekspor', 'Volume penjualan ekspor & regional (termasuk TLCC Vietnam)', 'Laporan penjualan', 6960, 7500, 7954, 8600, 1.03, $lt],
            ['F2.1', 'F2', 'EBITDA', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba usaha + depresiasi & amortisasi', 'Laporan keuangan', 5493699, 5800000, 4493554, 5200000, .9, $lt],
            ['F2.2', 'F2', 'Marjin EBITDA', '%', 'higher', 'average', 2, true, false, 'cfo', 'EBITDA ÷ pendapatan × 100', 'Rasio keuangan', 15.18, 15.50, 12.75, 14.20, .95, $lt],
            ['F2.3', 'F2', 'Laba yang dapat diatribusikan ke pemilik', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan bagian pemilik entitas induk', 'Laporan laba rugi', 719763, 900000, 190848, 650000, .74, $lt],
            ['F2.4', 'F2', 'Biaya kas produksi per ton semen', 'Rp ribu', 'lower', 'average', 0, true, false, 'operasi', 'Biaya kas produksi ÷ ton semen diproduksi', 'Akuntansi biaya', 720, 700, 712, 690, 1.01, $il],
            ['F3.1', 'F3', 'Utang berbunga', 'Rp juta', 'lower', 'last', 0, true, false, 'cfo', 'Pinjaman bank + obligasi + sewa berbunga', 'Neraca', 10900000, 10500000, 10390000, 9500000, 1.0, $lt],
            ['F3.2', 'F3', 'EBITDA terhadap beban bunga', 'kali', 'higher', 'last', 2, true, false, 'cfo', 'EBITDA ÷ beban bunga', 'Rasio keuangan', 4.56, 5.00, 5.44, 5.80, .97, $lt],
            ['F3.3', 'F3', 'Rasio lancar', 'kali', 'higher', 'last', 2, true, false, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 1.25, 1.20, 1.10, 1.20, .97, $lt],
            ['F3.4', 'F3', 'Realisasi belanja modal terhadap RKAP', '%', 'higher', 'last', 0, true, false, 'cfo', 'Realisasi capex ÷ capex RKAP × 100', 'Laporan capex', 85, 90, 64, 90, .82, $lt],
            ['C1.1', 'C1', 'Pangsa pasar semen domestik', '%', 'higher', 'average', 1, false, true, 'pemasaran', 'Volume domestik SIG ÷ konsumsi nasional × 100', 'Data asosiasi semen (ASI)', 48.5, 49.0, 46.9, 48.0, .985, $lt],
            ['C1.2', 'C1', 'Penjualan semen domestik', 'ribu ton', 'higher', 'sum', 0, false, false, 'pemasaran', 'Volume penjualan domestik', 'Laporan penjualan', 31306, 32000, 29975, 30700, .98, $lt],
            ['C1.3', 'C1', 'Penjualan semen kantong (ritel)', 'ribu ton', 'higher', 'sum', 0, false, false, 'pemasaran', 'Volume semen kemasan kantong', 'Laporan penjualan', 21835, 22500, 21660, 22300, .985, $lt],
            ['C2.1', 'C2', 'Toko ritel aktif dalam jaringan', 'unit', 'higher', 'last', 0, false, false, 'pemasaran', 'Toko bangunan bertransaksi dalam 90 hari terakhir', 'Platform ritel', 55000, 58000, 58568, 62000, .97, $lt],
            ['C2.2', 'C2', 'Porsi pesanan toko melalui platform digital', '%', 'higher', 'average', 0, false, false, 'pemasaran', 'Volume dipesan via platform ÷ volume kantong × 100', 'Platform ritel', 35, 40, 42, 50, .9, $il],
            ['C2.3', 'C2', 'Distributor aktif', 'unit', 'higher', 'last', 0, false, false, 'pemasaran', 'Distributor resmi aktif', 'Data jaringan', 290, 290, 289, 290, 1.0, $lt],
            ['C3.1', 'C3', 'Pendapatan produk turunan & solusi bangunan', 'Rp juta', 'higher', 'sum', 0, true, false, 'pemasaran', 'Beton siap pakai, precast, mortar, bata interlock', 'Laporan segmen (ilustrasi)', 2900000, 3200000, 3050000, 3400000, .93, $il],
            ['C3.2', 'C3', 'Porsi semen rendah karbon dalam penjualan', '%', 'higher', 'average', 0, false, false, 'pemasaran', 'Volume semen hijau/komposit ÷ volume semen × 100', 'Laporan penjualan', 70, 75, 78, 82, 1.0, $il],
            ['C4.1', 'C4', 'Indeks kepuasan pelanggan', 'skor', 'higher', 'last', 0, false, false, 'pemasaran', 'Survei kepuasan distributor, toko & proyek (skala 100)', 'Survei pelanggan', 82, 84, 84, 86, 1.0, $il, 'semesteran'],
            ['C4.2', 'C4', 'Pengiriman tepat waktu & lengkap (OTIF)', '%', 'higher', 'average', 1, false, false, 'rantai', 'Pesanan terkirim tepat waktu & lengkap ÷ pesanan × 100', 'Sistem logistik', 90.0, 93.0, 92.0, 95.0, .985, $il],
            ['P1.1', 'P1', 'Utilisasi kapasitas produksi', '%', 'higher', 'average', 1, false, true, 'operasi', 'Produksi semen ÷ kapasitas terpasang × 100', 'Laporan produksi', 58.6, 60.0, 55.3, 58.0, .97, $lt],
            ['P1.2', 'P1', 'Biaya energi terhadap biaya produksi', '%', 'lower', 'average', 1, false, false, 'operasi', 'Biaya bahan bakar & listrik ÷ biaya produksi × 100', 'Akuntansi biaya', 38.0, 37.0, 37.5, 35.5, 1.02, $il],
            ['P1.3', 'P1', 'Keandalan kiln (run factor)', '%', 'higher', 'average', 1, false, false, 'operasi', 'Jam operasi kiln ÷ jam tersedia × 100', 'Sistem pemeliharaan', 88.0, 90.0, 89.5, 91.0, .995, $il],
            ['P2.1', 'P2', 'Biaya distribusi & logistik per ton', 'Rp ribu', 'lower', 'average', 0, false, false, 'rantai', 'Biaya angkut & gudang ÷ ton terkirim', 'Akuntansi biaya', 165, 160, 162, 152, 1.03, $il],
            ['P2.2', 'P2', 'Hari persediaan semen di gudang penyangga', 'hari', 'lower', 'last', 1, false, false, 'rantai', 'Stok gudang penyangga ÷ penjualan harian', 'Sistem gudang', 9.0, 8.0, 8.5, 7.0, 1.06, $il],
            ['P3.1', 'P3', 'Rasio substitusi klinker', '%', 'higher', 'average', 0, false, false, 'esg', 'Bahan pengganti klinker ÷ bahan semen × 100', 'Laporan keberlanjutan', 33, 34, 34, 35, 1.0, $lt],
            ['P3.2', 'P3', 'Thermal substitution rate (bahan bakar alternatif)', '%', 'higher', 'average', 2, false, false, 'esg', 'Energi dari bahan bakar alternatif ÷ energi termal × 100', 'Laporan keberlanjutan', 9.12, 10.50, 11.13, 13.00, .9, $lt],
            ['P3.3', 'P3', 'Intensitas emisi CO₂', 'kg CO₂e/ton', 'lower', 'average', 0, false, false, 'esg', 'Emisi CO₂ cakupan 1 ÷ ton semen ekuivalen', 'Laporan keberlanjutan', 570, 565, 561, 552, 1.0, $lt],
            ['P3.4', 'P3', 'Listrik dari PLTS atap & lahan', 'MWh', 'higher', 'sum', 0, false, false, 'esg', 'Produksi listrik PLTS milik sendiri', 'Laporan energi', 1725, 5000, 7951, 15000, .85, $lt],
            ['P4.1', 'P4', 'Lost Time Injury Frequency Rate (LTIFR)', 'rasio', 'lower', 'average', 2, false, true, 'esg', 'Kecelakaan hilang waktu kerja × 1.000.000 ÷ jam kerja', 'Laporan K3', 0.14, 0.12, 0.13, 0.10, 1.1, $lt],
            ['P4.2', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan SPI/eksternal selesai sesuai tenggat ÷ temuan jatuh tempo × 100', 'SPI', 75, 85, 83, 90, .95, $il],
            ['L1.1', 'L1', 'Volume penjualan per karyawan', 'ribu ton', 'higher', 'sum', 2, false, false, 'sdm', 'Volume penjualan ÷ jumlah karyawan', 'HRIS & penjualan', 4.10, 4.30, 4.23, 4.45, .98, $lt],
            ['L1.2', 'L1', 'Jumlah karyawan', 'orang', 'lower', 'last', 0, false, false, 'sdm', 'Karyawan grup akhir perioda', 'HRIS', 9200, 9000, 8969, 8800, 1.0, $lt],
            ['L1.3', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'HRIS', 30.0, 32.0, 31.0, 34.0, .92, $il],
            ['L2.1', 'L2', 'Indeks budaya AKHLAK', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei budaya AKHLAK (skala 100)', 'Survei budaya', 78, 80, 81, 83, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan kontrak kinerja turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 50, 75, 72, 95, .86, $il],
            ['L3.1', 'L3', 'Pesanan distributor diproses otomatis (order-to-cash digital)', '%', 'higher', 'average', 0, false, false, 'ti', 'Pesanan diproses tanpa entri manual ÷ total pesanan × 100', 'Sistem ERP', 60, 70, 72, 85, .9, $il],
            ['L3.2', 'L3', 'Pabrik & gudang terhubung control tower logistik', '%', 'higher', 'last', 0, false, false, 'ti', 'Lokasi terhubung data real-time ÷ total lokasi × 100', 'Program control tower', 20, 40, 35, 85, .72, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat SIG, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi anak usaha, Strategy & Transformation Office',
                'summary' => "Pendapatan Rp35,24 T (92,6% RKAP); volume 37,93 juta ton (95,9% RKAP).\nEBITDA Rp4,49 T, marjin 12,75%; laba ke pemilik Rp190,8 M (2024: Rp719,8 M).\nEkspor/regional 7,95 juta ton (+14,9%) menahan penurunan domestik (−4,3%).\nPangsa pasar 46,9%, membaik pada semester II setelah transformasi.\nRealisasi capex 64% dari RKAP.",
                'decisions' => "1. Target 2026: pendapatan Rp36,6 T, volume 39,3 juta ton, EBITDA Rp5,2 T, laba ke pemilik Rp650 M.\n2. Prioritas: model ritel, biaya per ton, ekspor bermarjin, pasokan program desa.\n3. KPI korporat diturunkan ke anak usaha & individu melalui Ruci BSC.",
                'follow_up' => 'Strategy & Transformation Office menurunkan KPI 2026 ke setiap opco paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta laba dipulihkan lewat biaya per ton dan mutu volume, bukan perang harga. Mohon laporan bulanan pangsa pasar per wilayah.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi opco, Strategy & Transformation Office',
                'summary' => match ($m) {
                    2 => 'Curah hujan tinggi menekan volume kantong; ekspor sesuai rencana.',
                    3 => 'Lebaran (20 Maret) menurunkan hari kerja konstruksi; stok penyangga dinaikkan sebelum libur.',
                    5 => 'Pasokan pertama ke Koperasi Desa Merah Putih; harga batu bara naik 8%.',
                    6 => 'Semester I: volume 97% target; EBITDA 90% target; laba masih jauh dari rencana.',
                    7 => 'Control tower logistik baru menjangkau 35% lokasi.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Program promosi toko di Jawa & Sumatera.',
                    3 => 'Naikkan stok penyangga di 20 gudang menjelang Lebaran.',
                    5 => 'Percepat kontrak bahan bakar alternatif (biomassa & RDF).',
                    6 => 'Pangkas biaya tetap 5% semester II; tunda capex nonprioritas.',
                    7 => 'Tambah tim implementasi control tower; target 85% akhir tahun.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Strategy & Transformation Office',
                'summary' => $q === 1 ? 'Volume Q1 96% target; ekspor 104%; marjin EBITDA 13,6%.' : 'Volume H1 97% target; laba ke pemilik 73% dari rencana; pangsa pasar 47,8%.',
                'decisions' => $q === 1 ? 'Fokus wilayah dengan penurunan pangsa pasar > 1 poin.' : 'Program efisiensi energi dipercepat; evaluasi portofolio anak usaha.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Komisaris Utama', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti laba yang tertinggal, digitalisasi logistik yang lambat, dan eksekusi capex.',
            'decisions' => 'Direksi melaporkan laba per opco dan progres control tower setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon skenario bila harga batu bara tetap tinggi sampai akhir tahun, termasuk dampaknya ke target laba dan dividen.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi opco, Strategy & Transformation Office',
            'summary' => 'Realisasi September sebagian masih draf; porsi biaya energi dikembalikan untuk penyesuaian harga batu bara.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; realisasi capex grup 2025 tercatat Rp1.104 M dari RKAP Rp1.721 M.';

            return [
                'defs' => [
                    'transformasi' => ['SMGR-25-01', 'Transformasi proses bisnis', 'Model ritel, keunggulan biaya, produk turunan, portofolio anak usaha (sejak Juli 2025).', 'T1', 'strategi', '2025-07-01', '2025-12-31', 'selesai', 5, 5, 3, 2, ['C1', 'F2'], 'Pangsa pasar membaik pada semester II.'],
                    'ekspor' => ['SMGR-25-02', 'Perluasan pasar ekspor', 'Pasar baru & kontrak klinker regional.', 'T3', 'ekspor', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 2, ['F1'], 'Ekspor/regional 7,95 juta ton (+14,9%).'],
                    'plts' => ['SMGR-25-03', 'PLTS di pabrik', 'PLTS atap & lahan di beberapa pabrik.', 'T4', 'esg', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 3, ['P3'], 'PLTS 7.951 MWh (2024: 1.725 MWh).'],
                    'afr' => ['SMGR-25-04', 'Bahan bakar alternatif (AFR)', 'Biomassa, RDF, dan limbah industri sebagai pengganti batu bara.', 'T4', 'operasi', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 2, ['P3', 'P1'], 'TSR 11,13%; AFR 2,2 juta ton.'],
                    'ritel' => ['SMGR-25-05', 'Platform ritel toko bangunan', 'Pemesanan digital & program loyalitas toko.', 'T1', 'pemasaran', '2025-03-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C2'], '58.568 toko ritel dalam jaringan.'],
                ],
                'activities' => [
                    ['transformasi', 'Diagnosis & desain proses bisnis', 'strategi', '2025-07-01', '2025-09-30', 100], ['transformasi', 'Implementasi gelombang 1', 'strategi', '2025-10-01', '2025-12-31', 100],
                    ['ekspor', 'Pembukaan pasar Taiwan & Filipina', 'ekspor', '2025-01-01', '2025-09-30', 100], ['ekspor', 'Kontrak klinker jangka panjang', 'ekspor', '2025-04-01', '2025-12-31', 100],
                    ['plts', 'Pembangunan PLTS tahap 2', 'esg', '2025-01-01', '2025-12-31', 100],
                    ['afr', 'Kontrak pasokan biomassa & RDF', 'operasi', '2025-01-01', '2025-12-31', 100],
                    ['ritel', 'Onboarding toko ke platform', 'pemasaran', '2025-03-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['transformasi', 'Model ritel baru berjalan', '2025-10-31', true], ['plts', 'PLTS tahap 2 beroperasi', '2025-09-30', true], ['ekspor', 'Ekspor menembus 7,5 juta ton', '2025-12-31', true],
                ],
                'budgets' => [
                    ['transformasi', 'Pendamping transformasi', 'opex', 25_000_000_000, 22_500_000_000, null],
                    ['ekspor', 'Perluasan dermaga & pemasaran ekspor', 'capex', 120_000_000_000, 96_000_000_000, $note, 'Kas internal'],
                    ['plts', 'PLTS atap & lahan', 'capex', 90_000_000_000, 81_000_000_000, $note, 'Kas internal'],
                    ['afr', 'Fasilitas pengumpan bahan bakar alternatif', 'capex', 150_000_000_000, 98_000_000_000, $note, 'Kas internal'],
                    ['ritel', 'Pengembangan platform & program loyalitas', 'opex', 40_000_000_000, 36_000_000_000, null],
                ],
            ];
        }

        return [
            'defs' => [
                'desa' => ['SMGR-26-01', 'Pasokan program Koperasi Desa Merah Putih', 'Skema pasokan & distribusi semen ke koperasi desa.', 'T1', 'pemasaran', '2026-02-01', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['C1', 'F1'], 'Tambahan volume 2 juta ton.'],
                'ritel' => ['SMGR-26-02', 'Model ritel & loyalitas toko gelombang 2', 'Toko terhubung, pesanan digital, poin loyalitas.', 'T1', 'pemasaran', '2026-01-15', '2026-12-31', 'berjalan', 5, 4, 2, 2, ['C2', 'C1'], '62.000 toko aktif; 50% pesanan digital.'],
                'ekspor' => ['SMGR-26-03', 'Ekspor bermarjin & kontrak jangka panjang', 'Seleksi pasar ekspor berdasarkan marjin kontribusi.', 'T3', 'ekspor', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 2, ['F1', 'F2'], 'Ekspor 8,6 juta ton dengan marjin positif.'],
                'energi' => ['SMGR-26-04', 'Program efisiensi energi & bahan bakar alternatif', 'Optimasi kiln, biomassa, RDF.', 'T2', 'operasi', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 1, ['P1', 'P3'], 'TSR 13%; porsi biaya energi 35,5%.'],
                'plts' => ['SMGR-26-05', 'PLTS tahap 3', 'Tambahan PLTS di Tuban, Rembang, dan Tonasa.', 'T4', 'esg', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['P3'], '15.000 MWh per tahun.'],
                'tower' => ['SMGR-26-06', 'Control tower logistik', 'Data pabrik, gudang, armada, dan pelabuhan dalam satu dasbor.', 'T2', 'ti', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 4, 3, ['L3', 'P2'], '85% lokasi terhubung.'],
                'turunan' => ['SMGR-26-07', 'Produk turunan & solusi bangunan', 'Precast, mortar, bata interlock untuk rumah & proyek desa.', 'T3', 'pemasaran', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['C3'], 'Pendapatan turunan Rp3,4 T.'],
                'portofolio' => ['SMGR-26-08', 'Rasionalisasi portofolio anak usaha & biaya tetap', 'Evaluasi opco & aset yang tidak produktif.', 'T2', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 4, 1, ['F2', 'F3'], 'Biaya tetap turun 5%.'],
                'k3' => ['SMGR-26-09', 'Program keselamatan "Zero Harm"', 'Kepemimpinan K3, pengendalian risiko kritis, kontraktor.', 'T4', 'esg', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 1, ['P4'], 'LTIFR 0,10.'],
                'kinerja' => ['SMGR-26-10', 'Manajemen kinerja & budaya AKHLAK', 'Kontrak kinerja individu turunan Peta Strategi; agen budaya.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 1, ['L2', 'L1'], '95% karyawan punya KPI terhubung.'],
            ],
            'activities' => [
                ['desa', 'Skema harga & distribusi koperasi', 'pemasaran', '2026-02-01', '2026-04-30', 100], ['desa', 'Pasokan ke koperasi tahap 1', 'pemasaran', '2026-05-01', '2026-12-31', 55],
                ['ritel', 'Onboarding 4.000 toko baru', 'pemasaran', '2026-01-15', '2026-09-30', 85], ['ritel', 'Program poin loyalitas toko', 'pemasaran', '2026-03-01', '2026-12-31', 60],
                ['ekspor', 'Analisis marjin per pasar', 'ekspor', '2026-01-01', '2026-02-28', 100], ['ekspor', 'Kontrak tahunan Australia & Bangladesh', 'ekspor', '2026-02-01', '2026-12-31', 75],
                ['energi', 'Optimasi kiln Tuban & Rembang', 'operasi', '2026-01-01', '2026-08-31', 100], ['energi', 'Pasokan biomassa & RDF tambahan', 'operasi', '2026-03-01', '2026-12-31', 60],
                ['plts', 'Pembangunan PLTS tahap 3', 'esg', '2026-02-01', '2026-12-31', 50],
                ['tower', 'Desain dasbor & integrasi data', 'ti', '2026-01-15', '2026-05-31', 100], ['tower', 'Perluasan ke seluruh gudang & pelabuhan', 'ti', '2026-06-01', '2026-12-31', 35],
                ['turunan', 'Peluncuran mortar & bata interlock di 5 provinsi', 'pemasaran', '2026-02-01', '2026-10-31', 70],
                ['portofolio', 'Kajian portofolio opco', 'cfo', '2026-01-01', '2026-05-31', 100], ['portofolio', 'Eksekusi efisiensi biaya tetap', 'cfo', '2026-06-01', '2026-12-31', 50],
                ['k3', 'Audit risiko kritis di 9 pabrik', 'esg', '2026-01-01', '2026-06-30', 100], ['k3', 'Sertifikasi kontraktor', 'esg', '2026-04-01', '2026-12-31', 70],
                ['kinerja', 'Kontrak kinerja turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100], ['kinerja', 'Agen budaya AKHLAK di setiap unit', 'sdm', '2026-03-01', '2026-12-31', 70],
            ],
            'milestones' => [
                ['desa', 'Kontrak pasokan koperasi ditandatangani', '2026-04-30', true], ['tower', 'Dasbor control tower live', '2026-05-31', true], ['tower', '85% lokasi terhubung', '2026-12-31', false],
                ['plts', 'PLTS Tuban beroperasi', '2026-09-30', false], ['energi', 'TSR menembus 12%', '2026-06-30', true], ['kinerja', 'Kontrak kinerja Direksi–SVP ditandatangani', '2026-02-28', true],
            ],
            'budgets' => [
                ['desa', 'Gudang penyangga & armada desa', 'capex', 80_000_000_000, 46_000_000_000, null, 'Kas internal'],
                ['ritel', 'Platform & program loyalitas', 'opex', 50_000_000_000, 34_500_000_000, null],
                ['ekspor', 'Pemasaran & dermaga ekspor', 'opex', 30_000_000_000, 21_000_000_000, null],
                ['energi', 'Fasilitas bahan bakar alternatif', 'capex', 180_000_000_000, 112_000_000_000, null, 'Kas internal'],
                ['plts', 'PLTS tahap 3', 'capex', 140_000_000_000, 52_000_000_000, null, 'Pinjaman hijau'],
                ['tower', 'Sensor, integrasi & lisensi', 'capex', 45_000_000_000, 19_500_000_000, null, 'Kas internal'],
                ['turunan', 'Lini produksi mortar & interlock', 'capex', 70_000_000_000, 41_000_000_000, null, 'Kas internal'],
                ['portofolio', 'Konsultan & restrukturisasi opco', 'opex', 15_000_000_000, 8_400_000_000, null],
                ['k3', 'Program keselamatan & sertifikasi', 'opex', 12_000_000_000, 8_900_000_000, null],
                ['kinerja', 'Program budaya & kinerja', 'opex', 4_000_000_000, 2_900_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Laba ke pemilik jauh di bawah target', 'Rp190,8 M vs target Rp900 M; marjin domestik tertekan perang harga & biaya energi.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F2.3', null, 'komisaris', '2026-02-24',
                [['Program efisiensi energi & biaya tetap 2026', 'operasi', '2026-12-31', 'berjalan', 60]], null],
            ['Realisasi capex 64% dari RKAP', 'Proyek AFR & dermaga tertunda; manfaat efisiensi ikut mundur.', 'tinjauan', 'tahunan', 'proses', 'sedang', 617000000000, 'F3.4', 'afr', 'cfo', '2026-02-24',
                [['Rapat capex bulanan dengan PMO proyek', 'cfo', '2026-03-31', 'selesai', 100]], ['2026-04-15', 'Pemantauan capex bulanan berjalan rutin.']],
        ] : [
            ['Control tower logistik tertinggal', 'Baru 35% lokasi terhubung per Agustus vs rencana 60%; integrasi data gudang mitra lambat.', 'timwas', 'boc', 'proses', 'kritis', null, 'L3.2', 'tower', 'komisaris', '2026-07-28',
                [['Tambah tim integrasi & vendor kedua', 'ti', '2026-09-15', 'selesai', 100], ['Wajibkan gudang mitra memakai API standar', 'rantai', '2026-11-30', 'berjalan', 40]], null],
            ['Laba ke pemilik di bawah rencana', 'Laba H1 73% dari rencana; harga batu bara naik 8%.', 'tinjauan', 'q2', 'keuangan', 'tinggi', null, 'F2.3', 'portofolio', 'cfo', '2026-07-22',
                [['Pangkas biaya tetap 5% semester II', 'cfo', '2026-12-31', 'berjalan', 50], ['Tunda capex nonprioritas', 'cfo', '2026-08-31', 'selesai', 100]], null],
            ['LTIFR di atas target', 'Dua kecelakaan hilang waktu kerja melibatkan kontraktor di area kiln.', 'internal', 'rm6', 'proses', 'tinggi', null, 'P4.1', 'k3', 'esg', '2026-07-12',
                [['Sertifikasi ulang kontraktor area kiln', 'esg', '2026-10-31', 'berjalan', 70]], null],
            ['Capex 2026 kembali melambat', 'Realisasi capex Agustus 58% dari rencana YTD; PLTS tahap 3 tertunda perizinan.', 'tinjauan', 'q2', 'keuangan', 'sedang', null, 'F3.4', 'plts', 'cfo', '2026-07-22',
                [['Percepat izin PLTS Tuban', 'esg', '2026-09-30', 'selesai', 100]], null],
            ['Pesanan digital toko di bawah target', 'Porsi pesanan digital 45% vs 50%; toko di luar Jawa masih memesan lewat telepon.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'C2.2', 'ritel', 'pemasaran', '2026-07-22',
                [['Pendampingan toko di Sumatera & Sulawesi', 'pemasaran', '2026-11-30', 'berjalan', 55]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Skema pengadaan Koperasi Desa Merah Putih', 'eksternal', 'desa', 'C1', 'Pemerintah & koperasi', 'Volume bergantung pada mekanisme pengadaan koperasi.', 'pemasaran', 'tinggi', 'Skema harga sederhana & gudang penyangga dekat desa.'],
                ['Pasokan biomassa & RDF', 'eksternal', 'energi', 'P1', 'Pemasok & pemerintah daerah', 'Bahan bakar alternatif butuh pasokan stabil.', 'operasi', 'sedang', 'Kontrak multi-pemasok.'],
                ['Integrasi data gudang mitra', 'eksternal', 'tower', 'L3', 'Distributor & operator gudang', 'Control tower butuh data real-time dari mitra.', 'ti', 'tinggi', 'API standar & insentif untuk mitra.'],
                ['Izin PLTS & sambungan jaringan', 'eksternal', 'plts', 'P3', 'PLN & pemerintah daerah', 'PLTS lahan membutuhkan izin & kesepakatan teknis.', 'esg', 'sedang', 'Prioritaskan PLTS atap.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat penjualan & pangsa pasar mingguan', 'rapat', 'Volume, harga, dan pangsa pasar per wilayah.', 'Mingguan', 'pemasaran', 'melembaga'],
                ['Sales & Operations Planning (S&OP)', 'sop', 'Permintaan, produksi, stok, dan ekspor disepakati bulanan.', 'Bulanan', 'rantai', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan transformasi.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Safety walk pimpinan', 'budaya', 'Direksi & SVP melakukan inspeksi keselamatan di pabrik.', 'Bulanan', 'esg', 'melembaga'],
                ['Budaya AKHLAK', 'budaya', 'Agen budaya, apresiasi, dan internalisasi nilai.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
