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
 * Demo PT Mitra Adiperkasa Tbk — MAP (kode MAPI), peritel gaya hidup omni-channel.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (pendapatan per segmen, laba kotor, EBITDA, laba, arus kas, rasio, kanal digital, MAPCLUB).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: pertumbuhan menguntungkan di ritel ber-portofolio besar — ekspansi ASEAN & digital
 *   berjalan, sementara satu segmen (kafe & restoran) merugi karena boikot dan perlu strategi pemulihan.
 */
class MitraAdiperkasaSeeder extends ShowcaseSeeder
{
    public const CODE = 'MAPI';

    public const LT = 'Laporan Tahunan 2025 PT Mitra Adiperkasa Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Mitra Adiperkasa Tbk', 'legal_name' => 'PT Mitra Adiperkasa Tbk',
            'industry' => 'Ritel gaya hidup omnichannel (olahraga, fashion, anak, department store, F&B)',
            'address' => 'Sahid Sudirman Center Lt. 29, Jl. Jend. Sudirman Kav. 86', 'city' => 'Jakarta Pusat',
            'phone' => '+62 21 8064 8488', 'website' => 'https://www.map.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham MAPI). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Ritel memuncak menjelang Lebaran (Maret 2026), musim sekolah (Juli), dan akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 7.6, 7.4, 9.6, 8.0, 7.8, 8.1, 8.6, 8.0, 7.6, 7.9, 8.6, 10.8]),
            2026 => self::weights([1 => 7.6, 8.6, 9.4, 7.4, 7.8, 8.1, 8.6, 8.0, 7.6, 7.9, 8.4, 10.6]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.2', 'F2.3', 'F2.4', 'F3.3', 'C1.2', 'C1.3', 'C3.2', 'C4.2', 'P1.2', 'P2.1', 'P4.1', 'P4.2', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C2.2', 'L1.2'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P2.2', 'Markdown September belum memisahkan diskon program MAPCLUB dari markdown musiman. Mohon pisahkan dan lampirkan rincian per brand.'];
    }

    protected function criticalIn2026(): array
    {
        return ['L3.1'];
    }

    protected function maturityPattern(): array
    {
        return [3, 4, 3, 3, 4];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin MAP (contoh)', 'mapi.admin', 'Admin Sistem', 'Corporate Planning', ['company-admin']],
            'dirut' => ['Presiden Direktur (contoh)', 'mapi.direktur', 'Presiden Direktur', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'mapi.keuangan', 'Chief Financial Officer', 'Keuangan', ['keuangan']],
            'ritel' => ['Direktur Ritel Spesialis (contoh)', 'mapi.ritel', 'Direktur Specialty Retail (Active, Fashion, Kids, Digital)', 'Ritel Spesialis', ['kepala-unit']],
            'dept' => ['Direktur Department Store (contoh)', 'mapi.dept', 'Direktur Department Store', 'Department Store', ['kepala-unit']],
            'fnb' => ['Presiden Direktur Kafe & Restoran (contoh)', 'mapi.fnb', 'Presiden Direktur MAP Boga Adiperkasa', 'Kafe & Restoran', ['kepala-unit']],
            'digital' => ['Chief Digital Officer (contoh)', 'mapi.digital', 'Chief Digital Officer', 'Digital & Omnichannel', ['kepala-unit']],
            'operasi' => ['Direktur Operasi & Supply Chain (contoh)', 'mapi.operasi', 'Direktur Operasi & Supply Chain', 'Operasi & Rantai Pasok', ['kepala-unit']],
            'asean' => ['Direktur Internasional (contoh)', 'mapi.asean', 'Direktur Bisnis Internasional (ASEAN)', 'Bisnis Internasional', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'mapi.sdm', 'Direktur Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Corporate Planning (contoh)', 'mapi.strategi', 'Head of Corporate Planning & Strategy', 'Corporate Planning', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'mapi.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'mapi.analis', 'Performance Analyst', 'Corporate Planning', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Untuk menjadi peritel omni-channel terdepan di Indonesia.',
            'mission' => 'Untuk mendukung kesehatan, kebahagiaan dan gaya hidup yang lebih memuaskan bagi para pelanggan kami melalui portofolio brand kelas dunia, dan jaringan ritel omni-channel.',
            'purpose' => 'Menghadirkan brand kelas dunia yang membuat hidup pelanggan lebih sehat, bahagia, dan bergaya — di gerai maupun daring.',
            'tagline' => 'Future-ready: tumbuh di atas keunggulan eksekusi',
            'brand_promise' => 'Brand favorit, mudah didapat di mana saja: gerai, aplikasi, dan pesan antar dari gerai terdekat.',
            'value_proposition' => '> 150 brand kelas dunia, > 110 konsep ritel, 4.000+ gerai di > 80 kota dan 6 negara ASEAN, 51 platform daring, 13,4 juta anggota MAPCLUB.',
            'culture_statement' => 'Budaya P.E.O.P.L.E: People centered, Empowerment, Originality, Principles, Loyalty, Earnings.',
            'history' => "1995: berdiri, berawal dari ritel olahraga.\n2002: masuk kafe & restoran (Starbucks).\n2004: tercatat di Bursa Efek Indonesia (MAPI).\n2016: MAPEMALL & MAPCLUB diluncurkan.\n2017: MAP Boga Adiperkasa (F&B) tercatat di bursa.\n2018: MAP Aktif Adiperkasa (olahraga) tercatat di bursa.\n2020: ekspansi ke Filipina & Thailand.\n2025: pendapatan menembus Rp43 T; penjualan digital +21%.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['People centered', 'Berpusat pada manusia', 'Pelanggan dan karyawan adalah pusat setiap keputusan.', "Mendengar pelanggan di gerai & aplikasi\nMengembangkan staf gerai"],
            ['Empowerment', 'Pemberdayaan', 'Memberi ruang mengambil keputusan dan bertanggung jawab.', "Manajer gerai mengelola jadwal & stok sendiri\nKeputusan cepat dekat pelanggan"],
            ['Originality', 'Orisinalitas', 'Berani mencoba konsep dan cara baru.', "Konsep gerai baru\nEksperimen kanal digital"],
            ['Principles', 'Berprinsip', 'Integritas dan kepatuhan dalam setiap transaksi.', "Patuh pada perjanjian principal brand\nTransparan dalam pelaporan"],
            ['Loyalty', 'Loyalitas', 'Setia kepada pelanggan, mitra, dan perusahaan.', "Menjaga pelanggan MAPCLUB\nKemitraan jangka panjang dengan principal"],
            ['Earnings', 'Hasil usaha', 'Pertumbuhan yang menguntungkan dan berkelanjutan.', "Disiplin markdown & persediaan\nEkspansi hanya bila menguntungkan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Portofolio brand kelas dunia', 'Kompetensi', '> 150 brand dengan hak distribusi/waralaba eksklusif.', 5, 5, true, 'Kurasi & rasionalisasi brand.'],
            ['Jaringan gerai nasional & ASEAN', 'Sumber daya fisik', '4.000+ gerai di > 80 kota & 6 negara ASEAN.', 4, 5, true, 'Ekspansi hati-hati (prudent expansion).'],
            ['Program loyalitas MAPCLUB', 'Kompetensi', '13,4 juta anggota; satu aplikasi untuk semua brand.', 3 + $lvl, 5, true, 'Personalisasi berbasis data.'],
            ['Kemampuan omnichannel', 'Sistem', 'Endless Aisle, BluTab, pesan antar dari gerai terdekat.', 3, 5, true, 'Integrasi stok gerai–daring.'],
            ['Satu data pelanggan (ONE DATA)', 'Sistem', 'Data pelanggan & transaksi tersebar di banyak brand.', 2 + $lvl, 4, false, 'Platform data terpadu 2026.'],
            ['Pengelolaan persediaan & markdown', 'Sistem', 'Ratusan ribu SKU musiman.', 3, 4, false, 'Perencanaan alokasi berbasis data.'],
            ['Produktivitas staf gerai', 'Kompetensi', 'StoreForce: jadwal kerja sesuai trafik.', 3, 4, false, 'Optimasi headcount gerai.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Lingkungan ritel 2025', '2026-01-20', 'eksternal', self::LT.' — Analisis & Pembahasan Manajemen',
                'Tarif resiprokal, trafik pusat belanja melemah saat tensi politik, fluktuasi bahan baku, dan boikot merek F&B tertentu sejak akhir 2023.',
                'Pertumbuhan harus dijaga lewat portofolio, digital, dan efisiensi, bukan sekadar menambah gerai.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan Rp43,08 T (+13,9%); laba kotor Rp17,95 T (marjin 41,7%); laba usaha Rp3,99 T (+15,6%); EBITDA Rp7,33 T; laba bersih Rp2,72 T (+26,6%); ROE 15,8%. Penjualan digital +21% (9,8% dari total).',
                'Ritel spesialis menjadi mesin utama; kafe & restoran merugi Rp137 M dan perlu strategi pemulihan.'],
            ['Prospek 2026', '2026-02-05', 'eksternal', self::LT.' — Prospek Usaha',
                'Pertumbuhan ekonomi diasumsikan 5,4%; risiko geopolitik, harga energi, kurs, dan inflasi; peluang pertumbuhan organik dan penambahan brand.',
                'Lanjutkan empat strategi: prudent expansion, efisiensi, digitalisasi, pengalaman pelanggan.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Tensi politik & demonstrasi', 'Trafik pusat belanja turun saat situasi memanas.', 'ancaman', 3, 3, 'stabil', 'Penguatan kanal daring & pesan antar.'],
            ['ekonomi', 'Kelas menengah & daya beli', 'Konsumsi gaya hidup tumbuh tetapi selektif.', 'peluang', 4, 4, 'naik', 'Brand lintas harga & promosi MAPCLUB.'],
            ['ekonomi', 'Kurs & tarif impor', 'Sebagian besar barang diimpor dari principal global.', 'ancaman', 4, 4, 'naik', 'Negosiasi harga & lindung nilai.'],
            ['sosial', 'Boikot merek F&B tertentu', 'Sentimen konsumen menekan penjualan kafe & restoran.', 'ancaman', 4, 4, 'stabil', 'Program komunitas & diversifikasi brand F&B.'],
            ['sosial', 'Gaya hidup sehat & olahraga', 'Lari, padel, dan olahraga komunitas tumbuh.', 'peluang', 4, 5, 'naik', 'Perkuat MAP Active.'],
            ['teknologi', 'Social commerce & live shopping', 'Konsumen membeli lewat media sosial dan marketplace.', 'peluang', 4, 5, 'naik', '51 platform daring & endless aisle.'],
            ['teknologi', 'Data & AI ritel', 'Personalisasi & perencanaan stok berbasis data.', 'peluang', 3, 4, 'naik', 'ONE DATA.'],
            ['lingkungan', 'Kemasan & energi gerai', 'Tuntutan pengurangan plastik & efisiensi energi.', 'ancaman', 2, 3, 'naik', 'Program gerai hemat energi.'],
            ['hukum', 'Perlindungan konsumen & data pribadi', 'UU PDP berlaku penuh.', 'ancaman', 3, 4, 'naik', 'Tata kelola data MAPCLUB.'],
            ['hukum', 'Perjanjian lisensi principal', 'Hak distribusi brand bergantung pada pemenuhan target principal.', 'ancaman', 4, 2, 'stabil', 'Hubungan principal & kinerja brand.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Peritel gaya hidup & brand yang menjual langsung', 'Brand global membuka gerai sendiri; peritel lokal agresif.', 4, 'Eksklusivitas & pengalaman gerai.'],
            ['kekuatan_pembeli', 'Konsumen sensitif promosi', 'Membandingkan harga di gerai & marketplace.', 4, 'Harga konsisten lintas kanal & loyalitas.'],
            ['kekuatan_pemasok', 'Principal brand global', 'Principal menentukan harga, konsep gerai, dan target.', 5, 'Kinerja brand & portofolio luas.'],
            ['pendatang_baru', 'Marketplace & social commerce', 'Brand bisa menjual langsung secara daring.', 4, 'Omnichannel & layanan purna jual.'],
            ['produk_pengganti', 'Produk tiruan & brand lokal murah', 'Produk lokal berkualitas tumbuh.', 3, 'Kurasi brand lokal premium.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Ritel spesialis (active, fashion, kids, digital)', 'Ritel', 'Penjualan Rp36,75 T (+16,7%).', 85.3, 'naik', true, 'Brand favorit, stok lengkap, pengalaman gerai.', 'Ekspansi gerai & digital.'],
            ['Department store', 'Ritel', 'Penjualan Rp2,85 T (stagnan).', 6.6, 'stabil', true, 'Belanja satu atap untuk keluarga.', 'Rasionalisasi luas & konsesi.'],
            ['Kafe & restoran', 'F&B', 'Penjualan Rp3,24 T; segmen merugi Rp137 M.', 7.5, 'stabil', true, 'Rasa, suasana, dan nilai.', 'Pemulihan merek & efisiensi gerai.'],
            ['Pelanggan ASEAN (6 negara)', 'Internasional', 'Vietnam, Thailand, Filipina, Singapura, Malaysia, Kamboja.', 9, 'naik', true, 'Brand olahraga & gaya hidup.', 'Prudent expansion.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — penjualan per segmen (porsi ASEAN ilustratif)']);
        }

        foreach ([
            ['PT Mitra Adiperkasa Tbk', 'Peritel gaya hidup', 1, 43083, 13.9, 6.3, true, 'Portofolio brand terluas, MAPCLUB, jaringan ASEAN.', 'Ketergantungan pada principal; F&B merugi.'],
            ['Peritel department store nasional', 'Department store', 2, null, null, null, false, 'Jaringan luas di kota lapis dua.', 'Format kurang relevan bagi generasi muda.'],
            ['Brand global dengan gerai sendiri', 'Direct-to-consumer', 3, null, null, null, false, 'Kendali merek penuh.', 'Jaringan terbatas.'],
            ['Marketplace & social commerce', 'Digital', 4, null, null, null, false, 'Harga & kenyamanan.', 'Keaslian produk & pengalaman.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data 13,4 juta anggota MAPCLUB.', 'Sanksi & kepercayaan pelanggan.', 'sebagian', 'digital', '2026-12-31'],
            ['Ketentuan impor & SNI barang', 'Kementerian Perdagangan', 'Permendag tentang kebijakan & pengaturan impor', 'Perdagangan', null, 'Persetujuan impor & label berbahasa Indonesia.', 'Kelancaran pasokan.', 'patuh', 'operasi', null],
            ['Jaminan produk halal', 'BPJPH', 'UU No. 33/2014 & PP No. 39/2021', 'Mutu', '2024-10-17', 'Sertifikasi halal bagi produk makanan & minuman.', 'Izin edar F&B.', 'patuh', 'fnb', null],
            ['Perlindungan konsumen', 'Kementerian Perdagangan', 'UU No. 8/1999', 'Konsumen', null, 'Promosi, garansi, dan pengembalian barang.', 'Reputasi & sanksi.', 'patuh', 'ritel', null],
            ['Ketenagakerjaan & upah minimum', 'Kementerian Ketenagakerjaan', 'UU Cipta Kerja & PP Pengupahan', 'SDM', null, '> 33.000 karyawan, sebagian besar di gerai.', 'Biaya tenaga kerja.', 'patuh', 'sdm', null],
            ['Keterbukaan informasi emiten', 'OJK', 'POJK tentang laporan tahunan & keterbukaan', 'Tata kelola', null, 'Pelaporan grup dengan anak usaha tercatat (MAA, MBA).', 'Kepatuhan pasar modal.', 'patuh', 'cfo', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham pengendali & publik', 'internal', 'Pemilik', 5, 5, 'Pertumbuhan laba & dividen.', 'RUPS & paparan publik.', 'Investor Relations'],
            'principal' => ['Principal brand global (> 150 brand)', 'eksternal', 'Pemasok & pemberi lisensi', 5, 5, 'Target penjualan, standar gerai, citra merek.', 'Tinjauan bisnis bersama per musim.', 'Brand Management'],
            'pelanggan' => ['Pelanggan & anggota MAPCLUB', 'eksternal', 'Pelanggan', 4, 5, 'Brand favorit, harga wajar, layanan cepat.', 'Aplikasi MAPCLUB & survei.', 'CRM'],
            'mal' => ['Pengelola pusat belanja', 'eksternal', 'Mitra', 4, 4, 'Penyewa jangkar & trafik.', 'Negosiasi sewa & acara bersama.', 'Properti'],
            'karyawan' => ['Karyawan (> 33.000 orang)', 'internal', 'SDM', 4, 5, 'Karier, pelatihan, jadwal kerja adil.', 'Town hall & survei keterikatan.', 'Human Capital'],
            'mitra_investor' => ['Mitra investor anak usaha (CVC, Everstone)', 'eksternal', 'Mitra', 3, 4, 'Kinerja MAP Active & bisnis F&B.', 'Rapat dewan anak usaha.', 'Corporate Finance'],
            'komunitas' => ['Komunitas olahraga & gaya hidup', 'eksternal', 'Komunitas', 2, 4, 'Acara, pelatihan, dan dukungan.', 'Program komunitas.', 'Marketing'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'fnb' => ['Segmen kafe & restoran merugi', 'Hasil segmen −Rp137 M (2024: −Rp96 M); dampak boikot sejak akhir 2023.', 'keuangan', 5, 4, 'fnb', ['pemegang_saham', 'pelanggan']],
            'marjin' => ['Marjin kotor menurun', 'Marjin kotor 41,7% (2024: 42,7%) karena promosi & bauran.', 'keuangan', 4, 4, 'cfo', ['principal']],
            'trafik' => ['Trafik gerai fluktuatif', 'Trafik turun saat tensi politik; konsumen beralih ke daring.', 'pelanggan', 4, 4, 'ritel', ['pelanggan', 'mal']],
            'persediaan' => ['Persediaan & markdown musiman', 'Ratusan ribu SKU musiman berisiko markdown.', 'proses', 4, 4, 'operasi', ['principal']],
            'data' => ['Data pelanggan belum terpadu', 'Data tersebar di banyak brand & platform.', 'proses', 3, 4, 'digital', ['pelanggan']],
            'produktivitas' => ['Produktivitas staf gerai', 'Biaya tenaga kerja naik; penjadwalan belum sesuai trafik.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Portofolio > 150 brand kelas dunia', 'Hak eksklusif & lintas kategori.', 0.15, 4],
            ['S', 'Jaringan 4.000+ gerai & 6 negara ASEAN', 'Skala & kedekatan pelanggan.', 0.12, 4],
            ['S', 'MAPCLUB 13,4 juta anggota', 'Data & loyalitas.', 0.08, 3],
            ['S', 'Neraca & arus kas kuat', 'Arus kas operasi Rp5,8 T; rasio lancar 1,61x.', 0.07, 4],
            ['W', 'F&B merugi', 'Hasil segmen −Rp137 M.', 0.10, 1],
            ['W', 'Data pelanggan belum terpadu', 'Personalisasi terbatas.', 0.06, 2],
            ['W', 'Biaya sewa & tenaga kerja tinggi', 'Struktur biaya gerai fisik.', 0.07, 2],
            ['O', 'Olahraga & gaya hidup sehat', 'Kategori active tumbuh cepat.', 0.10, 4],
            ['O', 'Pasar ASEAN', 'Mega-forces demografi ASEAN.', 0.07, 3],
            ['O', 'Social commerce', 'Kanal baru berbiaya rendah.', 0.05, 3],
            ['T', 'Kurs & tarif impor', 'Biaya barang naik.', 0.05, 2],
            ['T', 'Boikot & sentimen konsumen', 'Risiko reputasi brand.', 0.05, 2],
            ['T', 'Brand menjual langsung', 'Disintermediasi oleh principal.', 0.03, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Prudent expansion: active & ASEAN', 'Tambah gerai di kategori & negara yang terbukti menguntungkan.', 5, ['trafik']],
            ['SO', 'MAPCLUB satu aplikasi semua brand', 'Insentif lintas brand & eMoney.', 4, ['trafik', 'data']],
            ['WO', 'Pemulihan kafe & restoran', 'Diversifikasi brand F&B, program komunitas, rasionalisasi gerai rugi.', 5, ['fnb']],
            ['WO', 'ONE DATA & personalisasi', 'Satu data pelanggan untuk promosi tepat sasaran.', 4, ['data', 'marjin']],
            ['ST', 'Endless Aisle & BluTab', 'Selamatkan penjualan saat stok gerai kosong; kirim dari gerai terdekat.', 4, ['persediaan']],
            ['WT', 'Efisiensi: konsolidasi, rasionalisasi brand & gerai, StoreForce', 'Biaya tetap & tenaga kerja sesuai trafik.', 4, ['produktivitas', 'marjin']],
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
            'T1' => ['Prudent Expansion', 'Tumbuh di kategori & negara yang menguntungkan; portofolio brand dikurasi.', '#B22234'],
            'T2' => ['Enhance Efficiencies', 'Konsolidasi, pangkas biaya, rasionalisasi brand & gerai, produktivitas staf.', '#1E6FA8'],
            'T3' => ['Digitalization', 'Endless Aisle, BluTab O2O, ONE DATA.', '#E08A1E'],
            'T4' => ['Customer Experience', 'MAPCLUB, layanan gerai, dan insentif yang relevan.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Prioritas kategori active & ASEAN', 'produk', 'Kategori dengan pertumbuhan & marjin terbaik.'],
            ['T2', 'Tutup atau relokasi gerai rugi', 'organisasi', 'Strategi enhance efficiencies 2025.'],
            ['T3', 'Gerai sebagai gudang mini untuk pesanan daring', 'saluran', 'BluTab: kirim dari gerai terdekat.'],
            ['T4', 'Satu aplikasi loyalitas lintas brand', 'saluran', 'MAPCLUB menyatukan pengalaman pelanggan.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pendapatan tumbuh dua digit', 'Pendapatan ±Rp47,5 T; ritel spesialis menjadi motor.', 'dirut', 2],
            'F2' => ['keuangan', 'T2', 'Profitabilitas naik', 'EBITDA & laba ke pemilik naik; beban usaha terkendali.', 'cfo', 3],
            'F3' => ['keuangan', 'T2', 'Kas & modal kerja sehat', 'Arus kas operasi kuat; persediaan efisien.', 'cfo', 2],
            'C1' => ['pelanggan', 'T4', 'Pelanggan setia melalui MAPCLUB', 'Anggota & porsi penjualan anggota naik.', 'digital', 2],
            'C2' => ['pelanggan', 'T3', 'Omnichannel tanpa batas', 'Penjualan digital & O2O tumbuh.', 'digital', 2],
            'C3' => ['pelanggan', 'T1', 'Portofolio brand yang relevan', 'Penjualan sama-toko tumbuh; brand baru berhasil.', 'ritel', 1.5],
            'C4' => ['pelanggan', 'T1', 'Ekspansi ASEAN yang menguntungkan', 'Pendapatan & gerai ASEAN naik.', 'asean', 1],
            'P1' => ['proses', 'T2', 'Produktivitas gerai', 'Penjualan per m² naik; gerai rugi berkurang.', 'ritel', 2],
            'P2' => ['proses', 'T2', 'Persediaan & markdown terkendali', 'Sell-through naik; markdown turun.', 'operasi', 1.5],
            'P3' => ['proses', 'T2', 'Pemulihan kafe & restoran', 'Rugi segmen F&B menyempit; kunjungan pulih.', 'fnb', 1.5],
            'P4' => ['proses', 'T2', 'Tata kelola & gerai berkelanjutan', 'Temuan audit tuntas; gerai hemat energi.', 'strategi', 1],
            'L1' => ['sdm', 'T2', 'Tim gerai produktif', 'Pendapatan per karyawan naik; turnover turun.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya P.E.O.P.L.E & kinerja', 'Keterikatan & KPI terhubung.', 'sdm', 1],
            'L3' => ['sdm', 'T3', 'ONE DATA & perangkat digital gerai', 'Data pelanggan terpadu; BluTab di semua gerai.', 'digital', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L2', 'L1', 2], ['L3', 'C1', 3], ['L3', 'C2', 3], ['L3', 'P2', 2],
            ['P1', 'C3', 2], ['P1', 'F2', 3], ['P2', 'F3', 3], ['P2', 'F2', 2], ['P3', 'F2', 2], ['P4', 'P1', 1],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'F1', 3], ['C4', 'F1', 2],
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
            ['F1.1', 'F1', 'Pendapatan konsolidasian', 'Rp miliar', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan neto konsolidasian', 'Laporan keuangan konsolidasian', 37836, 42000, 43083, 47500, .97, $lt],
            ['F1.2', 'F1', 'Penjualan ritel spesialis', 'Rp miliar', 'higher', 'sum', 0, true, false, 'ritel', 'Penjualan eksternal segmen ritel spesialis', 'Laporan segmen', 31494, 35500, 36748, 40500, .98, $lt],
            ['F1.3', 'F1', 'Penjualan department store', 'Rp miliar', 'higher', 'sum', 0, true, false, 'dept', 'Penjualan eksternal segmen department store', 'Laporan segmen', 2842, 2950, 2850, 2950, .97, $lt],
            ['F2.1', 'F2', 'EBITDA', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba usaha + depresiasi & amortisasi', 'Laporan keuangan', 6447, 7100, 7327, 8000, .98, $lt],
            ['F2.2', 'F2', 'Marjin laba kotor', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba kotor ÷ pendapatan × 100', 'Rasio keuangan', 42.7, 42.5, 41.7, 42.0, .99, $lt],
            ['F2.3', 'F2', 'Laba yang dapat diatribusikan ke pemilik', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba bersih bagian pemilik entitas induk', 'Laporan laba rugi', 1768, 2100, 2231, 2500, 1.0, $lt],
            ['F2.4', 'F2', 'Beban usaha terhadap pendapatan', '%', 'lower', 'average', 1, true, false, 'cfo', '(Laba kotor − laba usaha) ÷ pendapatan × 100', 'Laporan laba rugi', 33.5, 33.0, 32.4, 32.0, 1.0, $lt],
            ['F3.1', 'F3', 'Arus kas dari aktivitas operasi', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas bersih aktivitas operasi', 'Laporan arus kas', 6000, 6200, 5800, 6500, .95, $lt],
            ['F3.2', 'F3', 'Hari persediaan (DIO)', 'hari', 'lower', 'last', 0, true, false, 'operasi', 'Persediaan ÷ beban pokok × 365', 'Laporan keuangan', 150, 140, 145, 135, 1.04, $il],
            ['F3.3', 'F3', 'Rasio lancar', 'kali', 'higher', 'last', 2, true, false, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 1.44, 1.50, 1.61, 1.65, 1.0, $lt],
            ['C1.1', 'C1', 'Anggota MAPCLUB', 'juta', 'higher', 'last', 1, false, true, 'digital', 'Anggota terdaftar MAPCLUB akhir perioda', 'Platform MAPCLUB', 11.0, 13.0, 13.4, 15.0, .97, $lt],
            ['C1.2', 'C1', 'Porsi penjualan dari anggota MAPCLUB', '%', 'higher', 'average', 0, false, false, 'digital', 'Penjualan anggota ÷ penjualan ritel × 100', 'Platform MAPCLUB', 60, 63, 64, 67, .98, $il],
            ['C1.3', 'C1', 'Net Promoter Score pelanggan', 'skor', 'higher', 'average', 0, false, false, 'digital', '% promotor − % detraktor', 'Survei pelanggan', 45, 48, 50, 52, 1.0, $il],
            ['C2.1', 'C2', 'Porsi penjualan kanal digital', '%', 'higher', 'average', 1, false, true, 'digital', 'Penjualan daring (51 platform + aplikasi) ÷ penjualan × 100', 'Laporan omnichannel', 9.2, 10.0, 9.8, 11.5, .9, $lt],
            ['C2.2', 'C2', 'Pesanan O2O dipenuhi dari gerai (BluTab/Endless Aisle)', 'ribu', 'higher', 'sum', 0, false, false, 'digital', 'Pesanan daring yang dikirim/diambil dari gerai', 'Sistem O2O', 1800, 2400, 2500, 3200, .95, $il],
            ['C3.1', 'C3', 'Pertumbuhan penjualan sama-toko (SSSG)', '%', 'higher', 'average', 1, false, false, 'ritel', 'Pertumbuhan penjualan gerai yang beroperasi ≥ 12 bulan', 'Laporan ritel', 5.0, 6.0, 5.5, 6.0, .88, $il],
            ['C3.2', 'C3', 'Brand & konsep baru diluncurkan', 'unit', 'higher', 'sum', 0, false, false, 'ritel', 'Brand/konsep baru yang mulai beroperasi', 'Brand Management', 8, 10, 11, 10, 1.0, $il],
            ['C4.1', 'C4', 'Pendapatan dari negara ASEAN lain', 'Rp miliar', 'higher', 'sum', 0, true, false, 'asean', 'Pendapatan entitas di luar Indonesia', 'Laporan geografis (ilustrasi)', 3100, 3800, 3900, 4600, .96, $il],
            ['C4.2', 'C4', 'Gerai di negara ASEAN lain', 'unit', 'higher', 'last', 0, false, false, 'asean', 'Gerai beroperasi di 6 negara ASEAN', 'Data jaringan', 330, 380, 390, 440, .97, $il],
            ['P1.1', 'P1', 'Penjualan per m² per bulan', 'Rp juta', 'higher', 'average', 2, false, false, 'ritel', 'Penjualan gerai ÷ luas area jual ÷ bulan', 'Laporan ritel', 3.60, 3.80, 3.80, 4.00, .97, $il],
            ['P1.2', 'P1', 'Gerai beroperasi', 'unit', 'higher', 'last', 0, false, false, 'ritel', 'Seluruh gerai grup akhir perioda', 'Data jaringan', 3700, 4000, 4050, 4250, .98, $lt],
            ['P1.3', 'P1', 'Porsi gerai dengan EBITDA negatif', '%', 'lower', 'last', 1, false, false, 'ritel', 'Gerai EBITDA negatif ÷ gerai ≥ 12 bulan × 100', 'Laporan profitabilitas gerai', 8.0, 6.0, 7.0, 5.0, 1.05, $il],
            ['P2.1', 'P2', 'Sell-through barang musiman', '%', 'higher', 'average', 0, false, false, 'operasi', 'Unit terjual dengan harga penuh ÷ unit diterima × 100', 'Sistem merchandising', 70, 72, 71, 74, .98, $il],
            ['P2.2', 'P2', 'Markdown terhadap penjualan', '%', 'lower', 'average', 1, false, false, 'operasi', 'Nilai potongan harga ÷ penjualan kotor × 100', 'Sistem merchandising', 9.0, 8.5, 8.8, 8.0, 1.03, $il],
            ['P3.1', 'P3', 'Rugi segmen kafe & restoran', 'Rp miliar', 'lower', 'sum', 1, true, true, 'fnb', 'Hasil segmen F&B (angka positif = rugi; makin kecil makin baik)', 'Laporan segmen', 95.8, 60.0, 137.5, 70.0, 1.35, $lt],
            ['P3.2', 'P3', 'Transaksi pelanggan kafe & restoran', 'juta', 'higher', 'sum', 1, false, false, 'fnb', 'Jumlah transaksi di seluruh gerai F&B', 'Sistem POS', 70.0, 72.0, 66.0, 70.0, .93, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan audit internal selesai sesuai tenggat ÷ jatuh tempo × 100', 'Audit Internal', 75, 85, 86, 90, .97, $il],
            ['P4.2', 'P4', 'Gerai dengan program hemat energi', '%', 'higher', 'last', 0, false, false, 'operasi', 'Gerai dengan LED & kendali AC pintar ÷ total gerai × 100', 'Facility Management', 40, 55, 58, 75, 1.0, $il],
            ['L1.1', 'L1', 'Pendapatan per karyawan', 'Rp juta', 'higher', 'sum', 0, false, false, 'sdm', 'Pendapatan ÷ jumlah karyawan', 'HRIS & keuangan', 1200, 1280, 1305, 1400, .97, $lt],
            ['L1.2', 'L1', 'Kepatuhan jadwal StoreForce', '%', 'higher', 'average', 0, false, false, 'sdm', 'Jam kerja sesuai rekomendasi trafik ÷ total jam × 100', 'StoreForce', 75, 85, 82, 92, .9, $il],
            ['L1.3', 'L1', 'Turnover staf gerai', '%', 'lower', 'last', 0, false, false, 'sdm', 'Staf gerai keluar dalam 12 bulan ÷ rata-rata staf × 100', 'HRIS', 38, 35, 36, 32, 1.1, $il],
            ['L2.1', 'L2', 'Indeks keterikatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterikatan (skala 100)', 'Survei karyawan', 74, 76, 77, 79, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 45, 70, 68, 90, .86, $il],
            ['L3.1', 'L3', 'Brand terhubung ONE DATA', '%', 'higher', 'last', 0, false, false, 'digital', 'Brand yang data pelanggannya masuk platform terpadu ÷ total brand × 100', 'Program ONE DATA', 50, 70, 72, 95, .75, $il],
            ['L3.2', 'L3', 'Gerai memakai BluTab', '%', 'higher', 'last', 0, false, false, 'digital', 'Gerai dengan tablet BluTab aktif ÷ total gerai × 100', 'Sistem O2O', 60, 80, 82, 95, .97, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat MAP, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, pimpinan divisi, Corporate Planning',
                'summary' => "Pendapatan Rp43,08 T (+13,9%; 103% target).\nLaba kotor Rp17,95 T; marjin 41,7% (turun 1 poin).\nEBITDA Rp7,33 T; laba bersih Rp2,72 T (+26,6%); ROE 15,8%.\nPenjualan digital +21% (9,8% dari total); MAPCLUB 13,4 juta anggota.\nSegmen kafe & restoran merugi Rp137 M karena boikot.",
                'decisions' => "1. Target 2026: pendapatan Rp47,5 T, EBITDA Rp8,0 T, laba ke pemilik Rp2,5 T.\n2. Rencana pemulihan F&B: batas rugi Rp70 M.\n3. Empat strategi tetap; KPI diturunkan ke divisi & gerai melalui Ruci BSC.",
                'follow_up' => 'Corporate Planning menurunkan KPI 2026 ke setiap divisi & brand paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta rencana F&B dengan tonggak per kuartal dan kriteria tegas kapan gerai rugi ditutup.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, pimpinan divisi, Corporate Planning',
                'summary' => match ($m) {
                    2 => 'Penjualan menjelang Lebaran kuat untuk fashion & active.',
                    3 => 'Lebaran (20 Maret): penjualan tertinggi kuartal; stok sepatu lari habis di 120 gerai.',
                    5 => 'F&B: rugi menyempit, tetapi transaksi masih 6% di bawah 2025.',
                    6 => 'Semester I: pendapatan 97% target; markdown musim panas lebih tinggi.',
                    7 => 'Musim sekolah: kategori anak tumbuh 14%.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Tambah alokasi stok sepatu lari ke gerai Jawa Timur.',
                    3 => 'Aktifkan Endless Aisle untuk SKU habis.',
                    5 => 'Tutup 14 gerai F&B rugi dan relokasi 6 gerai.',
                    6 => 'Batasi pembelian musim berikut sesuai sell-through.',
                    7 => 'Program back-to-school diperluas ke ASEAN.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Corporate Planning',
                'summary' => $q === 1 ? 'Pendapatan Q1 99% target; porsi digital 10,1%; F&B masih rugi.' : 'Pendapatan H1 97% target; laba ke pemilik sesuai rencana; rugi F&B 128% dari batas.',
                'decisions' => $q === 1 ? 'Percepat ONE DATA untuk 20 brand terbesar.' : 'Rencana pemulihan F&B tahap 2: diversifikasi brand & menu nilai.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit',
            'summary' => 'Komisaris menyoroti rugi F&B yang melebihi batas, keterlambatan ONE DATA, dan markdown.',
            'decisions' => 'Direksi melaporkan rugi F&B per brand dan progres ONE DATA setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon opsi strategis untuk F&B bila pemulihan belum terlihat sampai akhir tahun, termasuk portofolio brand dan dampaknya bagi pemegang saham anak usaha.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, pimpinan divisi, Corporate Planning',
            'summary' => 'Realisasi September sebagian masih draf; markdown dikembalikan untuk dipisahkan dari diskon MAPCLUB.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; belanja modal grup 2025 tercatat Rp1,48 T di laporan tahunan.';

            return [
                'defs' => [
                    'active' => ['MAPI-25-01', 'Ekspansi gerai active & sports', 'Gerai baru & format besar untuk olahraga.', 'T1', 'ritel', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 3, ['F1', 'C3'], 'Ritel spesialis +16,7%.'],
                    'mapclub' => ['MAPI-25-02', 'MAPCLUB satu aplikasi semua brand', 'Integrasi brand & eMoney ke aplikasi MAPCLUB.', 'T4', 'digital', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C1'], '13,4 juta anggota.'],
                    'aisle' => ['MAPI-25-03', 'Endless Aisle & BluTab', 'Save the sale & kirim dari gerai terdekat.', 'T3', 'digital', '2025-02-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C2'], 'Penjualan digital +21%.'],
                    'efisiensi' => ['MAPI-25-04', 'Rasionalisasi brand & gerai', 'Konsolidasi, pangkas biaya, tutup gerai rugi.', 'T2', 'cfo', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 1, ['F2', 'P1'], 'Marjin usaha 9,3%.'],
                    'storeforce' => ['MAPI-25-05', 'StoreForce: jadwal staf sesuai trafik', 'Optimasi headcount gerai.', 'T2', 'sdm', '2025-03-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['L1'], 'Pendapatan per karyawan naik.'],
                ],
                'activities' => [
                    ['active', 'Pembukaan gerai baru', 'ritel', '2025-01-01', '2025-12-31', 100], ['active', 'Format toko besar olahraga', 'ritel', '2025-03-01', '2025-11-30', 100],
                    ['mapclub', 'Migrasi brand ke aplikasi MAPCLUB', 'digital', '2025-01-01', '2025-09-30', 100], ['mapclub', 'Integrasi eMoney', 'digital', '2025-05-01', '2025-12-31', 100],
                    ['aisle', 'Peluncuran BluTab di gerai', 'digital', '2025-02-01', '2025-12-31', 100],
                    ['efisiensi', 'Evaluasi profitabilitas gerai', 'cfo', '2025-01-01', '2025-06-30', 100], ['efisiensi', 'Penutupan & relokasi gerai rugi', 'cfo', '2025-04-01', '2025-12-31', 100],
                    ['storeforce', 'Implementasi StoreForce', 'sdm', '2025-03-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['mapclub', 'Seluruh brand utama di aplikasi MAPCLUB', '2025-09-30', true], ['aisle', 'BluTab di 2.000 gerai', '2025-10-31', true], ['active', 'Format besar ke-10 dibuka', '2025-11-30', true],
                ],
                'budgets' => [
                    ['active', 'Fit-out gerai baru', 'capex', 650_000_000_000, 612_000_000_000, $note, 'Kas internal'],
                    ['mapclub', 'Pengembangan aplikasi & integrasi', 'capex', 60_000_000_000, 55_000_000_000, $note, 'Kas internal'],
                    ['aisle', 'Tablet & integrasi O2O', 'capex', 45_000_000_000, 41_000_000_000, $note, 'Kas internal'],
                    ['efisiensi', 'Biaya penutupan & relokasi gerai', 'opex', 80_000_000_000, 71_000_000_000, null],
                    ['storeforce', 'Lisensi & pelatihan StoreForce', 'opex', 18_000_000_000, 16_500_000_000, null],
                ],
            ];
        }

        return [
            'defs' => [
                'asean' => ['MAPI-26-01', 'Prudent expansion ASEAN', 'Gerai baru di Vietnam, Filipina & Malaysia dengan kriteria balik modal.', 'T1', 'asean', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['C4', 'F1'], '440 gerai ASEAN.'],
                'active' => ['MAPI-26-02', 'Gerai active & komunitas olahraga', 'Format besar & program komunitas lari/padel.', 'T1', 'ritel', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 2, 3, ['C3', 'F1'], 'SSSG kategori active 8%.'],
                'fnb' => ['MAPI-26-03', 'Pemulihan kafe & restoran', 'Diversifikasi brand F&B, menu nilai, penutupan gerai rugi.', 'T2', 'fnb', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 4, 2, ['P3'], 'Rugi segmen ≤ Rp70 M.'],
                'onedata' => ['MAPI-26-04', 'ONE DATA pelanggan', 'Satu profil pelanggan lintas brand untuk personalisasi.', 'T3', 'digital', '2026-01-15', '2027-03-31', 'berjalan', 4, 4, 4, 3, ['L3', 'C1'], '95% brand terhubung.'],
                'o2o' => ['MAPI-26-05', 'O2O dari gerai terdekat', 'Perluasan BluTab & pesan antar instan.', 'T3', 'digital', '2026-02-01', '2026-12-31', 'berjalan', 4, 3, 2, 2, ['C2'], 'Porsi digital 11,5%.'],
                'mapclub' => ['MAPI-26-06', 'MAPCLUB personalisasi & tier', 'Program tier, poin lintas brand, kampanye personal.', 'T4', 'digital', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C1'], '15 juta anggota; 67% penjualan dari anggota.'],
                'alokasi' => ['MAPI-26-07', 'Perencanaan alokasi & markdown', 'Alokasi stok berbasis data; aturan markdown.', 'T2', 'operasi', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 3, 2, ['P2', 'F3'], 'Markdown 8%; DIO 135 hari.'],
                'gerai' => ['MAPI-26-08', 'Produktivitas gerai & rasionalisasi', 'Penjualan per m²; tutup/relokasi gerai EBITDA negatif.', 'T2', 'ritel', '2026-01-01', '2026-12-31', 'berjalan', 4, 3, 2, 1, ['P1'], 'Gerai EBITDA negatif ≤ 5%.'],
                'hijau' => ['MAPI-26-09', 'Gerai hemat energi', 'LED, kendali AC pintar, dan pemantauan energi.', 'T2', 'operasi', '2026-02-01', '2026-12-31', 'berjalan', 2, 2, 1, 2, ['P4'], '75% gerai hemat energi.'],
                'talenta' => ['MAPI-26-10', 'Akademi gerai & budaya P.E.O.P.L.E', 'Jalur karier staf gerai, StoreForce, KPI individu.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 2, 1, ['L1', 'L2'], 'Turnover staf gerai 32%.'],
            ],
            'activities' => [
                ['asean', 'Studi kelayakan 60 lokasi', 'asean', '2026-01-01', '2026-03-31', 100], ['asean', 'Pembukaan gerai ASEAN', 'asean', '2026-03-01', '2026-12-31', 65],
                ['active', 'Format besar di 6 kota', 'ritel', '2026-01-01', '2026-10-31', 75], ['active', 'Program komunitas lari & padel', 'ritel', '2026-02-01', '2026-12-31', 70],
                ['fnb', 'Penutupan & relokasi gerai rugi', 'fnb', '2026-01-15', '2026-06-30', 100], ['fnb', 'Menu nilai & brand F&B baru', 'fnb', '2026-03-01', '2026-12-31', 45],
                ['onedata', 'Desain platform data pelanggan', 'digital', '2026-01-15', '2026-04-30', 100], ['onedata', 'Integrasi 20 brand terbesar', 'digital', '2026-04-01', '2026-10-31', 70], ['onedata', 'Integrasi brand lain', 'digital', '2026-10-01', '2027-03-31', 0],
                ['o2o', 'Perluasan BluTab ke gerai ASEAN', 'digital', '2026-02-01', '2026-09-30', 90], ['o2o', 'Pesan antar instan dari gerai', 'digital', '2026-04-01', '2026-12-31', 55],
                ['mapclub', 'Program tier & poin lintas brand', 'digital', '2026-01-01', '2026-06-30', 100], ['mapclub', 'Kampanye personal berbasis data', 'digital', '2026-06-01', '2026-12-31', 40],
                ['alokasi', 'Model alokasi stok per gerai', 'operasi', '2026-01-15', '2026-06-30', 100], ['alokasi', 'Aturan markdown terpusat', 'operasi', '2026-05-01', '2026-12-31', 60],
                ['gerai', 'Evaluasi profitabilitas gerai', 'ritel', '2026-01-01', '2026-03-31', 100], ['gerai', 'Eksekusi penutupan/relokasi', 'ritel', '2026-04-01', '2026-12-31', 60],
                ['hijau', 'Pemasangan LED & kendali AC', 'operasi', '2026-02-01', '2026-12-31', 70],
                ['talenta', 'Akademi gerai & jalur karier', 'sdm', '2026-01-15', '2026-12-31', 60], ['talenta', 'KPI individu turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100],
            ],
            'milestones' => [
                ['fnb', 'Gerai rugi ditutup/relokasi', '2026-06-30', true], ['onedata', '20 brand terhubung', '2026-10-31', false], ['mapclub', 'Program tier diluncurkan', '2026-06-30', true],
                ['asean', 'Gerai ASEAN ke-400', '2026-08-31', true], ['alokasi', 'Model alokasi live', '2026-06-30', true], ['talenta', 'Kontrak kinerja manajer gerai', '2026-03-31', true],
            ],
            'budgets' => [
                ['asean', 'Fit-out gerai ASEAN', 'capex', 420_000_000_000, 268_000_000_000, null, 'Kas internal'],
                ['active', 'Format besar & acara komunitas', 'capex', 380_000_000_000, 251_000_000_000, null, 'Kas internal'],
                ['fnb', 'Relokasi & renovasi gerai F&B', 'capex', 90_000_000_000, 47_000_000_000, null, 'Kas internal'],
                ['onedata', 'Platform data & integrasi', 'capex', 75_000_000_000, 38_000_000_000, null, 'Kas internal'],
                ['o2o', 'Perangkat & integrasi kurir', 'capex', 30_000_000_000, 21_000_000_000, null, 'Kas internal'],
                ['mapclub', 'Program loyalitas & kampanye', 'opex', 120_000_000_000, 84_000_000_000, null],
                ['alokasi', 'Perangkat lunak perencanaan', 'capex', 25_000_000_000, 17_500_000_000, null, 'Kas internal'],
                ['gerai', 'Biaya penutupan & relokasi', 'opex', 60_000_000_000, 33_000_000_000, null],
                ['hijau', 'LED & kendali AC', 'capex', 35_000_000_000, 22_000_000_000, null, 'Kas internal'],
                ['talenta', 'Akademi gerai', 'opex', 20_000_000_000, 13_500_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Rugi segmen kafe & restoran melebar', 'Rugi Rp137 M vs batas Rp60 M; transaksi turun akibat boikot.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 77000000000, 'P3.1', null, 'komisaris', '2026-02-24',
                [['Rencana pemulihan F&B 2026', 'fnb', '2026-12-31', 'berjalan', 55]], null],
            ['Marjin kotor turun 1 poin', 'Promosi & bauran kategori menekan marjin menjadi 41,7%.', 'tinjauan', 'tahunan', 'keuangan', 'sedang', null, 'F2.2', 'efisiensi', 'cfo', '2026-02-24',
                [['Aturan markdown & promosi terpusat', 'operasi', '2026-04-30', 'selesai', 100]], ['2026-05-15', 'Aturan markdown terpusat berlaku sejak Mei.']],
        ] : [
            ['Rugi F&B melampaui batas', 'Rugi YTD Agustus 128% dari batas; menu nilai belum menaikkan transaksi.', 'timwas', 'boc', 'keuangan', 'kritis', null, 'P3.1', 'fnb', 'komisaris', '2026-07-28',
                [['Diversifikasi brand F&B', 'fnb', '2026-12-31', 'berjalan', 40], ['Tutup 10 gerai rugi tambahan', 'fnb', '2026-10-31', 'berjalan', 70]], null],
            ['ONE DATA tertinggal', 'Baru 72% brand terhubung vs rencana 80%; perjanjian data dengan beberapa principal belum selesai.', 'tinjauan', 'q2', 'sdm', 'tinggi', null, 'L3.1', 'onedata', 'digital', '2026-07-22',
                [['Addendum perjanjian data dengan principal', 'digital', '2026-10-31', 'berjalan', 60]], null],
            ['Porsi digital di bawah target', 'Porsi digital 10,4% vs 11,5%; stok gerai belum tampil real-time di aplikasi.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'C2.1', 'o2o', 'digital', '2026-07-22',
                [['Sinkronisasi stok gerai tiap 15 menit', 'digital', '2026-09-30', 'selesai', 100]], null],
            ['Markdown musim panas tinggi', 'Markdown 9,1% di Juni karena pembelian musim melebihi sell-through.', 'internal', 'rm6', 'proses', 'sedang', null, 'P2.2', 'alokasi', 'operasi', '2026-07-12',
                [['Batasi pembelian musim berikut', 'operasi', '2026-08-31', 'selesai', 100]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan principal atas berbagi data', 'eksternal', 'onedata', 'L3', 'Principal brand global', 'Data pelanggan brand tertentu butuh persetujuan principal.', 'digital', 'tinggi', 'Addendum perjanjian & tata kelola data.'],
                ['Izin & lokasi gerai ASEAN', 'eksternal', 'asean', 'C4', 'Pengelola mal & regulator setempat', 'Lokasi premium terbatas.', 'asean', 'sedang', 'Pipeline lokasi 18 bulan.'],
                ['Sentimen konsumen terhadap brand F&B', 'eksternal', 'fnb', 'P3', 'Konsumen & komunitas', 'Pemulihan bergantung pada sentimen publik.', 'fnb', 'tinggi', 'Diversifikasi brand & program komunitas.'],
                ['Mitra kurir instan', 'eksternal', 'o2o', 'C2', 'Penyedia logistik instan', 'Pesan antar dari gerai bergantung pada mitra.', 'digital', 'sedang', 'Multi-mitra kurir.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat dagang mingguan', 'rapat', 'Penjualan, stok, dan markdown per brand.', 'Mingguan', 'operasi', 'melembaga'],
                ['Tinjauan bisnis bersama principal', 'rapat', 'Target & rencana per musim bersama principal.', 'Per musim', 'ritel', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan ekspansi.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Kriteria buka/tutup gerai', 'sop', 'Gerai baru wajib balik modal ≤ 3 tahun; gerai rugi dievaluasi tiap kuartal.', 'Kuartalan', 'cfo', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Budaya P.E.O.P.L.E', 'budaya', 'Apresiasi staf gerai & internalisasi nilai.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
