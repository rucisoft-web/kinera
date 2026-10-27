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
 * Demo lengkap PT Diamond Food Indonesia Tbk (kode DFI) untuk memperlihatkan seluruh fitur Ruci BSC.
 *
 * - Perioda 2025 (ditutup): angka tahunan dari Laporan Tahunan 2025; rincian bulanan ilustratif dengan total yang sama.
 * - Perioda 2026 (berjalan, bawaan): target & realisasi Jan–Sep 2026 adalah SIMULASI untuk demo
 *   (Jan–Agu disetujui, Sep sebagian terverifikasi/draf/dikembalikan) supaya alur persetujuan, peringatan & tinjauan terlihat.
 * - Peta strategi BSC perusahaan makanan & distribusi rantai dingin: 4 tema, 15 sasaran, 35 KPI, relasi sebab-akibat.
 * - Ekosistem (PESTEL, Porter, pasar, pesaing, regulasi, pemangku kepentingan, isu, SWOT, TOWS), jati diri,
 *   inisiatif + aktivitas/milestone/anggaran, tinjauan kinerja, temuan & tindakan korektif, kematangan, ketergantungan, pelembagaan.
 *
 * Semua data selain angka Laporan Tahunan bersifat ilustrasi dan ditandai sebagai proyeksi/ilustrasi.
 */
class DiamondFoodSeeder extends ShowcaseSeeder
{
    public const CODE = 'DFI';

    public const LT = 'Laporan Tahunan 2025 PT Diamond Food Indonesia Tbk';

    /** Bobot musiman pendapatan (Ramadan 2025: Maret; Ramadan 2026: pertengahan Feb–Mar). */
    public const SEASON = [
        2025 => [1 => .079, .083, .095, .075, .080, .081, .083, .084, .082, .085, .083, .090],
        2026 => [1 => .078, .090, .092, .074, .080, .081, .083, .084, .082, .085, .083, .088],
    ];

    /** KPI 2026 yang sengaja di atas target supaya campuran hijau/kuning/merah realistis. */
    public const ON_TRACK_2026 = ['C1.2', 'C2.1', 'C2.2', 'F1.3', 'F2.2', 'F2.3', 'L1.2', 'L2.1', 'L4.2', 'P1.1', 'P1.3', 'P2.1', 'P2.2', 'P3.1', 'P3.2'];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Diamond Food Indonesia Tbk', 'legal_name' => 'PT Diamond Food Indonesia Tbk',
            'industry' => 'Makanan & minuman (manufaktur & distribusi rantai dingin)',
            'address' => 'TCC Batavia Tower One Lt. 15, Jl. KH Mas Mansyur Kav. 126', 'city' => 'Jakarta Pusat',
            'phone' => '+62 21 2864 9888', 'website' => 'https://www.diamondfoodindonesia.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham DMND). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; rincian bulanan ilustratif dengan total yang sama.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return self::ON_TRACK_2026;
    }

    protected function missingSeptember(): array
    {
        return ['P4.2', 'L3.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C3.1', 'Angka September masih memasukkan pesanan uji coba internal di platform B2B. Mohon dikoreksi dan lampirkan ekspor data platform.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P3.2'];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Diamond (contoh)', 'dfi.admin', 'Admin Sistem', 'Corporate Strategy', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'dfi.direktur', 'Presiden Direktur', 'Direksi', ['pimpinan']],
            'cfo' => ['Kepala Keuangan (contoh)', 'dfi.keuangan', 'Chief Financial Officer', 'Keuangan', ['keuangan']],
            'komersial' => ['Kepala Komersial (contoh)', 'dfi.komersial', 'Commercial Director', 'Komersial', ['kepala-unit']],
            'scm' => ['Kepala Rantai Pasok (contoh)', 'dfi.rantaipasok', 'Supply Chain Director', 'Rantai Pasok & Distribusi', ['kepala-unit']],
            'produksi' => ['Kepala Produksi & Mutu (contoh)', 'dfi.produksi', 'Manufacturing & QA Director', 'Produksi & Mutu', ['kepala-unit']],
            'sdm' => ['Kepala SDM (contoh)', 'dfi.sdm', 'Human Capital Director', 'SDM', ['kepala-unit', 'editor-jatidiri']],
            'ti' => ['Kepala TI (contoh)', 'dfi.ti', 'Chief Information Officer', 'Teknologi Informasi', ['kepala-unit']],
            'strategi' => ['Strategy Office (contoh)', 'dfi.strategi', 'Corporate Strategy Manager', 'Corporate Strategy', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'dfi.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Penjualan (contoh)', 'dfi.analis', 'Sales Analyst', 'Komersial', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi platform makanan dan minuman yang paling unggul di Indonesia.',
            'mission' => "1. Menyediakan solusi yang saling menguntungkan bagi pelanggan dan prinsipal kami.\n2. Mengembangkan budaya kinerja tinggi dan inovasi di dalam perusahaan.\n3. Memperkuat masyarakat dengan cara memfasilitasi kegembiraan bagi semua pelanggan dan keluarga mereka.\n4. Menerapkan pendekatan yang berkelanjutan pada semua kegiatan usaha.",
            'purpose' => 'Menghadirkan makanan dan minuman bermutu, aman, dan terjangkau ke seluruh Indonesia melalui rantai dingin yang andal.',
            'tagline' => 'Evolving Greatness',
            'brand_promise' => 'Produk segar dan bermutu, tiba tepat waktu di 38 provinsi.',
            'value_proposition' => 'Platform F&B terintegrasi hulu–hilir: produksi sendiri (dairy, es krim, bakery) + merek prinsipal internasional, didukung 24 titik distribusi, >1.000 armada rantai dingin, dan multi-kanal (modern, tradisional, food service, ritel Diamondfair, e-commerce).',
            'culture_statement' => 'Budaya kinerja tinggi dan inovasi, berlandaskan nilai Longevity, Integrity, Learning & Innovation, Family, Frugality, dan Empowerment.',
            'history' => "1974: memulai usaha sebagai produsen es krim (joint venture dengan AMATIL).\nOperasi di kawasan industri MM2100 Cikarang Barat, diperluas 2005 dan 2010; produksi bakery, dairy dan susu UHT.\nEkspansi hilir ke distribusi produk beku; cabang Denpasar sebagai titik distribusi pertama di luar Jawa.\nPengenalan produk prinsipal internasional; gerai ritel & e-commerce Diamondfair.\n2025: 24 titik distribusi, >1.000 armada, 38 provinsi; pendapatan Rp11,1 triliun.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Keberlangsungan', 'Longevity', 'Membangun masa depan secara konsisten untuk keberlangsungan nilai organisasi bagi pemangku kepentingan.', "Mengambil keputusan dengan horizon jangka panjang\nMenjaga hubungan baik dengan prinsipal & pelanggan"],
            ['Integritas', 'Integrity', 'Berkomitmen untuk selalu jujur dan bertanggung jawab.', "Melaporkan realisasi KPI apa adanya dengan bukti\nMenolak gratifikasi dari pemasok"],
            ['Pembelajaran dan Inovasi', 'Learning & Innovation', 'Berusaha untuk terus berinovasi dan mencoba hal-hal baru.', "Mengusulkan perbaikan proses tiap kuartal\nBerbagi praktik baik antar cabang"],
            ['Keluarga', 'Family', 'Menjunjung kejujuran, loyalitas, kepedulian antar karyawan, dan lingkungan kerja yang solid.', "Saling membantu saat musim puncak (Ramadan, akhir tahun)\nMengutamakan keselamatan rekan kerja"],
            ['Kewaspadaan dalam Penggunaan Dana', 'Frugality', 'Pengeluaran yang hati-hati, investasi strategis, dan pemanfaatan aset maksimal.', "Setiap capex disertai analisis imbal hasil\nMengendalikan biaya energi gudang dingin"],
            ['Pemberdayaan', 'Empowerment', 'Memberdayakan karyawan sebagai komponen penting pertumbuhan berkelanjutan.', "Mendelegasikan keputusan ke kepala cabang\nMemberi ruang talenta muda memimpin proyek"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Jaringan distribusi rantai dingin nasional', 'Sumber daya fisik', '24 titik distribusi, >1.000 armada berpendingin, jangkauan 38 provinsi.', 4, 5, true, 'Tambah titik distribusi luar Jawa; WMS & optimasi rute.'],
            ['Fasilitas produksi dairy, es krim & bakery (MM2100)', 'Sumber daya fisik', 'Produksi sendiri memberi kendali penuh atas mutu dan fleksibilitas kapasitas.', 4, 4, true, 'Upgrade & refurbish mesin; efisiensi energi.'],
            ['Kemitraan prinsipal merek internasional', 'Kompetensi', 'Portofolio merek prinsipal yang luas: dairy, cokelat, daging & seafood olahan, sayuran beku, grocery.', 4, 5, true, 'Diversifikasi prinsipal untuk mengurangi ketergantungan pada merek tertentu.'],
            ['Manajemen mutu & keamanan pangan', 'Sistem', 'FSSC 22000, ISO 22000, HACCP, sistem jaminan halal.', 4, 5, true, 'Sertifikasi halal 100% SKU produksi sendiri sebelum Oktober 2026.'],
            ['Eksekusi multi-kanal', 'Kompetensi', 'Modern trade, tradisional, food service, ritel Diamondfair, e-commerce.', 3 + $lvl, 5, false, 'Platform e-commerce B2B & analitik pelanggan.'],
            ['Digital & data', 'Sistem', 'ERP, WMS (sebagian cabang), platform B2B dalam pengembangan.', 2 + $lvl, 4, false, 'Roadmap B2B, WMS gelombang 2, TMS, keamanan siber.'],
            ['Kepemimpinan & talenta', 'Kompetensi', '7.679 karyawan (2025); program Product Knowledge & Diamond Apex.', 3, 4, false, 'Leadership pipeline & suksesi posisi kunci.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    /** @return array<string,StrategicIssue> */
    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi makro Indonesia 2025', '2026-01-20', 'eksternal', self::LT.' — Laporan Direksi',
                'PDB tumbuh 5,11% (tertinggi sejak 2022), inflasi 2,92% (dalam sasaran BI 2,5±1%). Keyakinan konsumen sempat melemah pada kuartal III lalu pulih di kuartal IV; segmen menengah tetap berhati-hati berbelanja.',
                'Jaga keterjangkauan harga & pack size; dorong kanal food service dan modern trade yang tumbuh.'],
            ['Pandangan 2026', '2026-01-20', 'eksternal', self::LT.' — Pandangan Tahun 2026',
                'Ekonomi diproyeksikan tumbuh ±5,1% (APBN 5,4%); konsumsi domestik & investasi tetap penggerak utama didukung stimulus.',
                'Target pertumbuhan pendapatan dua digit dengan disiplin margin.'],
            ['Kewajiban sertifikasi halal', '2026-02-10', 'eksternal', self::LT.' — Laporan Direksi',
                'Sertifikasi halal akan wajib bagi seluruh produk; kepatuhan halal sudah menjadi bagian kinerja operasional perusahaan.',
                'Percepat sertifikasi halal SKU produksi sendiri & verifikasi dokumen halal prinsipal.'],
            ['Kinerja segmen 2025', '2026-01-25', 'internal', self::LT.' — Tinjauan Operasi per Segmen',
                'Produk bermerek 91,8% pendapatan (Rp10,2 T, +14,4%); produk tidak bermerek 8,2% (Rp905,8 M). Capex Rp476,5 M untuk cabang baru & kapasitas.',
                'Fokus pada produk bermerek bermargin lebih baik; tingkatkan imbal hasil capex.'],
            ['Rencana pengembangan TI 2026', '2026-02-05', 'internal', self::LT.' — Teknologi Informasi',
                'Sistem digital optimasi distribusi, otomasi proses operasional, sistem gudang berbasis teknologi, infrastruktur TI cabang baru, platform e-commerce B2B.',
                'Masukkan ke portofolio inisiatif 2026 dengan milestone yang jelas.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Program pemerintah & stimulus konsumsi', 'Stimulus fiskal & program makan bergizi mendorong permintaan pangan olahan dan dairy.', 'peluang', 4, 3, 'naik', 'Siapkan kapasitas dairy & kanal institusi.'],
            ['ekonomi', 'Daya beli segmen menengah tertekan', 'Pertumbuhan upah lambat dan biaya hidup naik membuat konsumen berhati-hati (Laporan Direksi 2025).', 'ancaman', 4, 4, 'stabil', 'Pack size terjangkau, promosi terarah, efisiensi biaya.'],
            ['ekonomi', 'Depresiasi Rupiah', 'Sebagian besar produk prinsipal & bahan baku impor; pelemahan Rupiah menekan margin kotor (20,26% vs 21,17%).', 'ancaman', 5, 4, 'naik', 'Lindung nilai alami, negosiasi harga prinsipal, penyesuaian harga bertahap.'],
            ['ekonomi', 'Pertumbuhan food service & HoReCa', 'Kafe, hotel, restoran, bakery terus tumbuh dan membutuhkan pasokan beku & dairy yang andal.', 'peluang', 4, 4, 'naik', 'Tim food service khusus & paket solusi untuk outlet.'],
            ['sosial', 'Gaya hidup praktis & produk beku', 'Permintaan makanan siap olah, dairy dan camilan premium meningkat di perkotaan.', 'peluang', 4, 4, 'naik', 'Inovasi SKU & kemitraan prinsipal baru.'],
            ['sosial', 'Puncak musiman Ramadan & akhir tahun', 'Fluktuasi permintaan musiman (faktor risiko perusahaan).', 'ancaman', 3, 5, 'stabil', 'Perencanaan permintaan & stok penyangga.'],
            ['teknologi', 'Digitalisasi B2B & e-commerce', 'Pelanggan toko & food service beralih ke pemesanan digital.', 'peluang', 4, 4, 'naik', 'Platform B2B Diamond (roadmap 2026).'],
            ['teknologi', 'Ancaman keamanan siber', 'Ketergantungan tinggi pada TI; serangan siber dapat mengganggu distribusi.', 'ancaman', 4, 3, 'naik', 'Penguatan keamanan siber & DRP.'],
            ['lingkungan', 'Tanggung jawab kemasan & emisi', 'Tuntutan pengurangan sampah kemasan (peta jalan produsen) dan efisiensi energi gudang dingin.', 'ancaman', 3, 3, 'naik', 'Program efisiensi energi & kemasan.'],
            ['lingkungan', 'Gangguan pasokan listrik gudang', 'Gangguan listrik dapat merusak persediaan beku (faktor risiko perusahaan).', 'ancaman', 4, 2, 'stabil', 'Genset & pemantauan suhu daring.'],
            ['hukum', 'Wajib halal Oktober 2026', 'UU Jaminan Produk Halal dan aturan turunannya mewajibkan sertifikasi halal produk pangan.', 'ancaman', 5, 5, 'naik', 'Program kepatuhan halal 100% SKU.'],
            ['hukum', 'Regulasi pangan BPOM & label', 'Perubahan peraturan registrasi & label pangan olahan dapat memengaruhi peluncuran produk.', 'ancaman', 3, 3, 'stabil', 'Regulatory affairs memantau & mengajukan registrasi lebih awal.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Persaingan distributor & produsen F&B', 'Produsen dairy/es krim lokal dan distributor FMCG nasional bersaing di kanal modern & food service; konsolidasi antar pesaing (faktor risiko).', 4, 'Diferensiasi lewat cakupan rantai dingin & portofolio prinsipal.'],
            ['kekuatan_pembeli', 'Daya tawar modern trade & jaringan food service besar', 'Peritel modern & rantai restoran menuntut margin, listing fee dan syarat pembayaran panjang.', 4, 'Joint business plan & layanan OTIF tinggi.'],
            ['kekuatan_pemasok', 'Ketergantungan pada prinsipal merek', 'Sebagian besar produk berasal dari prinsipal; kehilangan prinsipal kunci berdampak signifikan.', 4, 'Hubungan jangka panjang & diversifikasi prinsipal.'],
            ['pendatang_baru', 'Distributor digital & quick-commerce', 'Platform B2B digital dan distributor baru masuk dengan model ringan aset.', 3, 'Percepat platform B2B sendiri; manfaatkan skala rantai dingin.'],
            ['produk_pengganti', 'Produk segar & lokal', 'Produk segar pasar tradisional dan merek lokal berharga murah menggantikan produk premium.', 3, 'Pack size terjangkau & merek sendiri.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Modern trade (supermarket & minimarket)', 'Ritel modern', 'Jaringan supermarket, hypermarket & minimarket nasional.', 34, 'naik', true, 'Ketersediaan stok, promo bersama, OTIF.', 'Joint business plan dengan peritel utama.'],
            ['Pasar tradisional & grosir', 'Tradisional', 'Toko grosir & pengecer di pasar tradisional.', 21, 'stabil', true, 'Harga terjangkau, kunjungan rutin salesman.', 'Perluas outlet aktif & pemesanan via B2B.'],
            ['Food service (HoReCa & bakery)', 'Food service', 'Hotel, restoran, kafe, bakery, katering.', 30, 'naik', true, 'Pasokan beku & dairy konsisten, dukungan teknis menu.', 'Tim food service & paket solusi.'],
            ['Ritel milik sendiri (Diamondfair)', 'Ritel sendiri', 'Gerai Diamondfair.', 6, 'naik', true, 'Pengalaman belanja & harga anggota.', 'Format gerai & program loyalitas.'],
            ['E-commerce & B2B digital', 'Digital', 'Marketplace, toko daring Diamondfair, platform B2B.', 5, 'naik', true, 'Kemudahan pesan & pengiriman cepat.', 'Platform B2B fase 2.'],
            ['Industri & institusi', 'Institusi', 'Industri makanan (bahan baku), institusi & program pemerintah.', 4, 'naik', false, 'Kontrak volume & spesifikasi.', 'Penetrasi selektif.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Ilustrasi porsi pendapatan per kanal untuk demo (tidak diungkap di laporan tahunan)']);
        }

        foreach ([
            ['PT Diamond Food Indonesia Tbk', 'Produsen & distributor F&B rantai dingin', 1, 11098.7, 13.1, 3.92, true, 'Rantai dingin nasional 38 provinsi, produksi sendiri + prinsipal internasional, multi-kanal.', 'Ketergantungan pada prinsipal & impor (sensitif kurs).'],
            ['Produsen dairy lokal terintegrasi (mis. Cimory, Ultrajaya)', 'Produsen dairy', 2, null, null, null, false, 'Merek kuat di ritel, integrasi peternakan.', 'Portofolio lebih sempit di produk beku & prinsipal.'],
            ['Produsen es krim lokal (mis. Campina)', 'Produsen es krim', 3, null, null, null, false, 'Merek es krim dikenal luas, jaringan freezer di toko.', 'Kategori tunggal; musiman.'],
            ['Produsen pangan olahan beku (mis. Fiesta, So Good)', 'Produsen frozen food', 4, null, null, null, false, 'Skala besar & integrasi protein hewani.', 'Fokus pada merek sendiri, tidak melayani prinsipal.'],
            ['Distributor FMCG nasional', 'Distributor', 5, null, null, null, false, 'Cakupan outlet tradisional sangat luas.', 'Kapabilitas rantai dingin terbatas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Contoh pembanding kualitatif untuk demo; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Jaminan Produk Halal', 'BPJPH Kementerian Agama', 'UU No. 33/2014 jo. PP No. 42/2024', 'Mutu & halal', '2024-10-17', 'Kewajiban sertifikasi halal produk makanan & minuman, tahap berikutnya Oktober 2026.', 'Produk tanpa sertifikat halal tidak boleh beredar.', $year === 2025 ? 'sebagian' : 'dipantau', 'produksi', '2026-10-17'],
            ['Registrasi & label pangan olahan', 'BPOM', 'Peraturan BPOM tentang label & registrasi pangan olahan', 'Mutu & halal', null, 'Setiap SKU wajib nomor izin edar & label sesuai ketentuan.', 'Keterlambatan registrasi menunda peluncuran produk.', 'patuh', 'produksi', null],
            ['Tata kelola perusahaan terbuka', 'OJK', 'POJK No. 21/2015 & POJK 51/2017 (keuangan berkelanjutan)', 'Pasar modal', null, 'Penerapan GCG, laporan tahunan & laporan keberlanjutan.', 'Kepatuhan pelaporan emiten DMND.', 'patuh', 'cfo', null],
            ['Ketenagakerjaan & K3', 'Kemnaker', 'UU Cipta Kerja & PP 35/2021; PP 50/2012 (SMK3)', 'Ketenagakerjaan', null, 'PKWT, alih daya, upah minimum, sistem manajemen K3.', '7.679 karyawan di banyak cabang.', 'patuh', 'sdm', null],
            ['Peta jalan pengurangan sampah oleh produsen', 'KLHK', 'Permen LHK No. P.75/2019', 'Lingkungan', null, 'Produsen wajib menyusun rencana pengurangan sampah kemasan.', 'Desain kemasan & pelaporan.', 'sebagian', 'produksi', '2029-12-31'],
            ['Pelindungan data pribadi', 'Kominfo/Komdigi', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pelanggan e-commerce & B2B wajib dilindungi.', 'Sanksi administratif & reputasi.', 'sebagian', 'ti', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'prinsipal' => ['Prinsipal merek internasional', 'eksternal', 'Mitra pemasok', 5, 5, 'Pertumbuhan penjualan, eksekusi di toko, data sell-out, kepatuhan mutu.', 'Business review kuartalan, joint business plan.', 'Commercial Director'],
            'modern' => ['Peritel modern', 'eksternal', 'Pelanggan', 4, 4, 'OTIF tinggi, promo bersama, syarat dagang.', 'Key account management.', 'Key Account Manager'],
            'foodservice' => ['Pelanggan food service (HoReCa, bakery)', 'eksternal', 'Pelanggan', 4, 5, 'Pasokan konsisten, harga stabil, dukungan teknis.', 'Tim food service & chef consultant.', 'Food Service Manager'],
            'tradisional' => ['Toko tradisional & grosir', 'eksternal', 'Pelanggan', 3, 4, 'Kunjungan rutin, kredit, harga.', 'Salesman & platform B2B.', 'Area Sales Manager'],
            'konsumen' => ['Konsumen akhir', 'eksternal', 'Konsumen', 3, 4, 'Produk aman, halal, segar, terjangkau.', 'Layanan pelanggan & media sosial.', 'Brand Manager'],
            'karyawan' => ['Karyawan (±7.700 orang)', 'internal', 'SDM', 4, 5, 'Pengembangan karier, keselamatan, kesejahteraan.', 'Survei keterlibatan, town hall.', 'Human Capital'],
            'pemegang_saham' => ['Pemegang saham & investor (DMND)', 'internal', 'Pemilik', 5, 4, 'Pertumbuhan laba, dividen, tata kelola.', 'RUPS, paparan publik, laporan tahunan.', 'Corporate Secretary'],
            'regulator' => ['Regulator (OJK, BEI, BPOM, BPJPH)', 'eksternal', 'Regulator', 5, 3, 'Kepatuhan pelaporan, izin edar, halal.', 'Kepatuhan proaktif.', 'Legal & Compliance'],
            'logistik' => ['Mitra logistik & pemasok bahan baku', 'eksternal', 'Pemasok', 3, 3, 'Volume stabil & pembayaran tepat waktu.', 'Kontrak & evaluasi pemasok.', 'Procurement'],
            'masyarakat' => ['Masyarakat sekitar fasilitas', 'eksternal', 'Komunitas', 2, 3, 'Lapangan kerja, lingkungan bersih.', 'Program CSR.', 'CSR'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'margin' => ['Tekanan margin kotor', 'Margin kotor turun ke 20,26% (2024: 21,17%) karena kurs & persaingan harga.', 'keuangan', 5, 5, 'cfo', ['prinsipal', 'pemegang_saham']],
            'kas' => ['Kas operasi turun meski laba naik', 'Kas neto operasi Rp472 M (2024: Rp516 M) akibat kenaikan persediaan.', 'keuangan', 4, 4, 'cfo', ['pemegang_saham']],
            'prinsipal' => ['Ketergantungan pada prinsipal merek', 'Kehilangan prinsipal kunci berdampak signifikan pada pendapatan.', 'pelanggan', 4, 5, 'komersial', ['prinsipal']],
            'halal' => ['Kesiapan wajib halal Oktober 2026', 'Seluruh SKU produksi sendiri & prinsipal harus bersertifikat halal.', 'proses', 5, 5, 'produksi', ['regulator', 'konsumen']],
            'digital' => ['Transformasi digital B2B tertinggal', 'Pesaing & platform digital menawarkan pemesanan daring; adopsi B2B Diamond masih rendah.', 'pelanggan', 4, 4, 'ti', ['tradisional', 'foodservice']],
            'coldchain' => ['Keandalan rantai dingin di luar Jawa', 'Ekspansi cabang baru menuntut standar suhu & listrik yang sama.', 'proses', 4, 4, 'scm', ['foodservice', 'modern']],
            'talenta' => ['Kesiapan talenta & suksesi', 'Ekspansi cabang membutuhkan kepala cabang & supervisor siap pakai.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Jaringan rantai dingin nasional', '24 titik distribusi, >1.000 armada, 38 provinsi.', 0.15, 4],
            ['S', 'Portofolio prinsipal & produksi sendiri', 'Dairy, es krim, bakery sendiri + merek internasional.', 0.12, 4],
            ['S', 'Neraca kuat', 'Liabilitas/ekuitas 0,26; rasio lancar 3,15.', 0.10, 4],
            ['S', 'Sertifikasi mutu FSSC 22000 & HACCP', 'Kepercayaan prinsipal & pelanggan food service.', 0.08, 3],
            ['W', 'Margin kotor menurun', '20,26% vs 21,17%; sensitif kurs.', 0.12, 2],
            ['W', 'Adopsi digital B2B rendah', 'Sebagian besar pesanan masih manual/salesman.', 0.08, 2],
            ['W', 'Konversi kas melemah', 'Persediaan naik; kas operasi turun 8,6%.', 0.08, 2],
            ['O', 'Pertumbuhan food service & HoReCa', 'Kanal dengan pertumbuhan tertinggi.', 0.10, 3],
            ['O', 'Ekspansi luar Jawa', 'Penetrasi masih rendah di Indonesia timur.', 0.08, 3],
            ['O', 'Kewajiban halal sebagai keunggulan', 'Pemain siap halal akan memenangkan listing.', 0.05, 3],
            ['T', 'Depresiasi Rupiah', 'Menekan margin produk impor.', 0.10, 2],
            ['T', 'Daya beli segmen menengah', 'Konsumen berhati-hati.', 0.08, 2],
            ['T', 'Konsolidasi & digitalisasi pesaing', 'Distributor digital & konsolidasi industri.', 0.06, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Ekspansi titik distribusi luar Jawa untuk food service', 'Manfaatkan rantai dingin & neraca kuat untuk masuk kota tumbuh di luar Jawa.', 5, ['coldchain']],
            ['SO', 'Halal-ready sebagai nilai jual ke prinsipal & peritel', 'Sertifikasi halal 100% lebih awal dari tenggat.', 4, ['halal']],
            ['WO', 'Platform B2B untuk toko tradisional & HoReCa', 'Naikkan kontribusi digital & data pelanggan.', 5, ['digital']],
            ['ST', 'Disiplin harga & mix produk bermargin tinggi', 'Kurangi dampak kurs lewat negosiasi prinsipal, mix & pricing.', 5, ['margin']],
            ['ST', 'Diversifikasi prinsipal', 'Tambah prinsipal baru di kategori tumbuh.', 4, ['prinsipal']],
            ['WT', 'Program konversi kas', 'Turunkan hari persediaan & DSO; capex selektif.', 4, ['kas']],
            ['WT', 'Leadership pipeline cabang', 'Siapkan kepala cabang untuk ekspansi.', 3, ['talenta']],
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
            'T1' => ['Pertumbuhan yang Menguntungkan', 'Tumbuh dua digit dengan disiplin margin & arus kas.', '#1F4E8C'],
            'T2' => ['Keunggulan Distribusi & Rantai Dingin', 'Jangkauan nasional dengan layanan OTIF dan mutu suhu terjaga.', '#2A8C9E'],
            'T3' => ['Digital & Inovasi', 'Platform B2B, WMS/TMS, dan inovasi produk bersama prinsipal.', '#5C5AA8'],
            'T4' => ['Insan & Budaya Kinerja Tinggi', 'Talenta siap tumbuh, budaya kinerja, mutu & K3.', '#C88A12'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Fokus pada produk bermerek & mix bermargin tinggi', 'produk', 'Produk bermerek 91,8% pendapatan dan tumbuh 14,4%.'],
            ['T1', 'Penyesuaian harga bertahap & negosiasi prinsipal untuk meredam kurs', 'harga', 'Margin kotor turun 0,9 poin pada 2025.'],
            ['T2', 'Ekspansi titik distribusi luar Jawa', 'pasar', 'Capex 2025 Rp476,5 M diarahkan ke cabang baru.'],
            ['T2', 'Food service sebagai kanal prioritas', 'saluran', 'Kanal dengan pertumbuhan tertinggi.'],
            ['T3', 'Platform e-commerce B2B sesuai roadmap', 'saluran', 'Rencana TI 2026 dalam laporan tahunan.'],
            ['T3', 'Otomasi gudang & optimasi rute', 'operasi', 'Efisiensi distribusi & akurasi stok.'],
            ['T4', 'Leadership pipeline untuk kepala cabang', 'organisasi', 'Kebutuhan talenta untuk ekspansi.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pertumbuhan pendapatan yang berkelanjutan', 'Pendapatan tumbuh dua digit, dipimpin produk bermerek.', 'cfo', 3],
            'F2' => ['keuangan', 'T1', 'Meningkatkan profitabilitas', 'Laba bersih & margin operasi naik meski kurs menekan margin kotor.', 'cfo', 3],
            'F3' => ['keuangan', 'T1', 'Mengoptimalkan imbal hasil modal & arus kas', 'ROE/ROA naik, kas operasi sehat, struktur modal konservatif.', 'cfo', 2],
            'C1' => ['pelanggan', 'T2', 'Memperluas jangkauan pasar & distribusi nasional', 'Lebih banyak titik distribusi, outlet aktif & provinsi terlayani.', 'komersial', 2],
            'C2' => ['pelanggan', 'T2', 'Menjadi mitra pilihan pelanggan & prinsipal', 'Layanan OTIF, kepuasan pelanggan & retensi prinsipal tinggi.', 'scm', 2],
            'C3' => ['pelanggan', 'T3', 'Memperkuat portofolio merek & kanal digital', 'Kontribusi kanal digital & produk baru meningkat.', 'komersial', 1.5],
            'P1' => ['proses', 'T2', 'Keunggulan rantai pasok rantai dingin', 'Persediaan efisien, stock-out rendah, suhu terjaga.', 'scm', 2],
            'P2' => ['proses', 'T1', 'Efisiensi modal kerja', 'Penagihan cepat & piutang sehat.', 'cfo', 1],
            'P3' => ['proses', 'T4', 'Mutu, keamanan pangan & K3 terjamin', 'Audit keamanan pangan, halal, dan keselamatan kerja.', 'produksi', 1.5],
            'P4' => ['proses', 'T3', 'Digitalisasi & otomasi operasi', 'Pesanan via B2B & WMS di seluruh cabang.', 'ti', 1],
            'P5' => ['proses', 'T3', 'Inovasi produk & kemitraan prinsipal', 'SKU baru dan prinsipal baru di kategori tumbuh.', 'komersial', 1],
            'L1' => ['sdm', 'T4', 'SDM kompeten & produktif', 'Pelatihan terstruktur & produktivitas karyawan.', 'sdm', 1],
            'L2' => ['sdm', 'T4', 'Budaya kinerja tinggi & keterlibatan karyawan', 'Keterlibatan tinggi, turnover sukarela rendah.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Kesiapan talenta & kepemimpinan', 'Suksesor siap untuk posisi kunci.', 'sdm', 1],
            'L4' => ['sdm', 'T3', 'Infrastruktur TI andal & aman', 'Sistem inti tersedia & aman dari ancaman siber.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P3', 2], ['L2', 'P5', 2], ['L3', 'P1', 2], ['L4', 'P4', 3],
            ['P1', 'C2', 3], ['P1', 'C1', 2], ['P2', 'F3', 3], ['P3', 'C2', 3], ['P4', 'C3', 3], ['P4', 'C2', 2], ['P5', 'C3', 3],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'F1', 2], ['C3', 'F2', 2],
            ['F1', 'F2', 3], ['F2', 'F3', 2],
        ] as [$s, $t, $str]) {
            ObjectiveRelation::create(['source_objective_id' => $obj[$s]->id, 'target_objective_id' => $obj[$t]->id, 'strength' => $str]);
        }

        return [$themes, $obj];
    }

    // ------------------------------------------------------------------ KPI

    /**
     * kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, pemilik, rumus, sumber,
     * baseline 2024, target 2025, realisasi 2025, target 2026, faktor realisasi 2026, dari laporan tahunan?, frekuensi
     */
    protected function kpiDefs(): array
    {
        $lt = true;
        $il = false;

        return [
            ['F1.1', 'F1', 'Pendapatan bersih', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Pendapatan neto konsolidasian', 'Laporan keuangan konsolidasian', 9811549, 10800000, 11098665, 12320000, .985, $lt],
            ['F1.2', 'F1', 'Pertumbuhan pendapatan (YoY, kumulatif)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Pendapatan YTD ÷ pendapatan YTD tahun lalu − 1', 'Laporan keuangan', 9.5, 10.0, 13.12, 11.0, .90, $lt],
            ['F1.3', 'F1', 'Pendapatan produk bermerek', 'Rp juta', 'higher', 'sum', 0, true, false, 'komersial', 'Pendapatan segmen produk bermerek', 'Informasi segmen', 8912549, 9900000, 10192865, 11300000, .99, $lt],
            ['F2.1', 'F2', 'Laba tahun berjalan', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan konsolidasian', 'Laporan laba rugi', 363732, 400000, 435170, 490000, .96, $lt],
            ['F2.2', 'F2', 'Margin laba bruto', '%', 'higher', 'average', 2, true, true, 'cfo', 'Laba bruto ÷ pendapatan × 100', 'Rasio usaha', 21.17, 21.00, 20.26, 20.80, .985, $lt],
            ['F2.3', 'F2', 'Margin laba operasi', '%', 'higher', 'average', 2, true, false, 'cfo', 'Laba operasi ÷ pendapatan × 100', 'Rasio usaha', 4.53, 4.60, 4.65, 4.80, .97, $lt],
            ['F3.1', 'F3', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Laba ÷ total ekuitas × 100 (disetahunkan)', 'Rasio usaha', 5.86, 6.50, 6.62, 7.20, .95, $lt],
            ['F3.2', 'F3', 'Imbal hasil aset (ROA)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Laba ÷ total aset × 100 (disetahunkan)', 'Rasio usaha', 4.88, 5.00, 5.23, 5.60, .96, $lt],
            ['F3.3', 'F3', 'Kas neto dari aktivitas operasi', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas operasi', 'Laporan arus kas', 516440, 550000, 472091, 560000, .88, $lt],
            ['F3.4', 'F3', 'Rasio liabilitas terhadap ekuitas', 'rasio', 'lower', 'last', 2, true, false, 'cfo', 'Total liabilitas ÷ total ekuitas', 'Rasio keuangan', 0.20, 0.25, 0.26, 0.25, 1.02, $lt],
            ['C1.1', 'C1', 'Jumlah titik distribusi', 'unit', 'higher', 'last', 0, false, false, 'scm', 'Cabang & depo aktif', 'Wilayah operasional', 22, 25, 24, 27, .93, $lt],
            ['C1.2', 'C1', 'Outlet aktif terlayani', 'unit', 'higher', 'last', 0, false, false, 'komersial', 'Outlet bertransaksi dalam 3 bulan terakhir', 'Sistem penjualan', 52000, 58000, 57400, 63000, .97, $il],
            ['C1.3', 'C1', 'Provinsi terlayani', 'unit', 'higher', 'last', 0, false, false, 'scm', 'Provinsi dengan pengiriman rutin', 'Wilayah operasional', 38, 38, 38, 38, 1, $lt],
            ['C1.4', 'C1', 'Realisasi belanja modal terhadap rencana', '%', 'higher', 'last', 1, true, false, 'cfo', 'Capex terealisasi ÷ rencana × 100 (YTD)', 'Laporan capex', 88, 100, 91.6, 100, .90, $lt],
            ['C2.1', 'C2', 'Pengiriman tepat waktu & lengkap (OTIF)', '%', 'higher', 'average', 1, false, true, 'scm', 'Pesanan tepat waktu & lengkap ÷ total pesanan × 100', 'TMS / laporan distribusi', 93.5, 95.0, 94.6, 96.0, .985, $il],
            ['C2.2', 'C2', 'Indeks kepuasan pelanggan', 'skor', 'higher', 'last', 1, false, false, 'komersial', 'Survei kepuasan pelanggan semesteran (skala 100)', 'Survei pihak ketiga', 80, 82, 82.5, 84, .99, $il, 'semesteran'],
            ['C2.3', 'C2', 'Keluhan pelanggan', 'kasus', 'lower', 'sum', 0, false, false, 'scm', 'Jumlah keluhan tercatat', 'Customer care', 1480, 1350, 1290, 1200, 1.06, $il],
            ['C2.4', 'C2', 'Retensi prinsipal merek', '%', 'higher', 'last', 1, false, false, 'komersial', 'Prinsipal yang memperpanjang kontrak ÷ kontrak jatuh tempo × 100', 'Legal & komersial', 96, 97, 98, 98, 1, $il],
            ['C3.1', 'C3', 'Kontribusi penjualan kanal digital', '%', 'higher', 'average', 1, false, false, 'komersial', 'Penjualan B2B digital & e-commerce ÷ total penjualan × 100', 'Platform B2B & marketplace', 3.5, 5.0, 4.8, 8.0, .72, $il],
            ['C3.2', 'C3', 'Kontribusi produk baru (< 2 tahun)', '%', 'higher', 'average', 1, false, false, 'komersial', 'Penjualan SKU < 2 tahun ÷ total × 100', 'Sistem penjualan', 6.0, 7.0, 7.4, 8.0, 1.02, $il],
            ['P1.1', 'P1', 'Perputaran persediaan', 'rasio', 'higher', 'last', 2, true, false, 'scm', 'HPP ÷ rata-rata persediaan (disetahunkan)', 'Rasio aktivitas', 3.60, 3.80, 3.75, 3.95, .97, $lt],
            ['P1.2', 'P1', 'Tingkat kehabisan stok (stock-out)', '%', 'lower', 'average', 1, false, true, 'scm', 'SKU-lokasi kosong ÷ total SKU-lokasi × 100', 'WMS', 3.2, 2.5, 2.7, 2.2, 1.15, $il],
            ['P1.3', 'P1', 'Kepatuhan suhu rantai dingin', '%', 'higher', 'average', 1, false, true, 'scm', 'Pembacaan suhu dalam batas ÷ total pembacaan × 100', 'Data logger armada & gudang', 98.6, 99.5, 99.2, 99.5, .997, $il],
            ['P1.4', 'P1', 'Produk rusak/kedaluwarsa terhadap penjualan', '%', 'lower', 'average', 2, false, false, 'scm', 'Nilai write-off ÷ penjualan × 100', 'Laporan persediaan', 0.65, 0.55, 0.58, 0.50, 1.08, $il],
            ['P2.1', 'P2', 'Perputaran piutang', 'rasio', 'higher', 'last', 2, true, false, 'cfo', 'Pendapatan ÷ rata-rata piutang (disetahunkan)', 'Rasio aktivitas', 8.57, 9.00, 9.01, 9.30, .98, $lt],
            ['P2.2', 'P2', 'Hari penagihan piutang (DSO)', 'hari', 'lower', 'last', 1, true, false, 'cfo', '365 ÷ perputaran piutang', 'Rasio aktivitas', 42.6, 40.6, 40.5, 39.0, 1.03, $lt],
            ['P3.1', 'P3', 'Skor audit keamanan pangan (FSSC 22000/halal)', 'skor', 'higher', 'average', 1, false, true, 'produksi', 'Rata-rata skor audit internal & eksternal', 'QA', 91, 93, 94.5, 95, .995, $il],
            ['P3.2', 'P3', 'SKU produksi sendiri bersertifikat halal', '%', 'higher', 'last', 1, false, true, 'produksi', 'SKU bersertifikat ÷ total SKU produksi sendiri × 100', 'QA & halal', 92, 96, 97, 100, .985, $il],
            ['P3.3', 'P3', 'Tingkat kecelakaan kerja (LTIFR)', 'indeks', 'lower', 'average', 2, false, false, 'produksi', 'Kecelakaan hilang waktu × 1.000.000 ÷ jam kerja', 'HSE', 0.62, 0.50, 0.48, 0.45, 1.10, $il],
            ['P4.1', 'P4', 'Pesanan pelanggan melalui platform B2B', '%', 'higher', 'average', 1, false, false, 'ti', 'Pesanan via B2B ÷ total pesanan × 100', 'Platform B2B', 8, 12, 11, 20, .75, $il],
            ['P4.2', 'P4', 'Cabang dengan WMS terintegrasi', 'unit', 'higher', 'last', 0, false, false, 'ti', 'Cabang yang live WMS', 'Proyek TI', 6, 10, 10, 16, .82, $il],
            ['P5.1', 'P5', 'SKU baru diluncurkan', 'unit', 'higher', 'sum', 0, false, false, 'komersial', 'SKU baru (produksi sendiri & prinsipal)', 'Product management', 64, 70, 76, 80, 1.03, $il],
            ['L1.1', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'HRIS', 10.9, 11.0, 11.1, 12.0, .95, $lt],
            ['L1.2', 'L1', 'Pendapatan per karyawan', 'Rp juta', 'higher', 'sum', 1, false, false, 'sdm', 'Pendapatan ÷ jumlah karyawan', 'HRIS & keuangan', 1292.3, 1400, 1445.3, 1560, .98, $lt],
            ['L2.1', 'L2', 'Indeks keterlibatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterlibatan semesteran (skala 100)', 'Survei karyawan', 74, 76, 77, 78, .99, $il, 'semesteran'],
            ['L2.2', 'L2', 'Turnover karyawan sukarela', '%', 'lower', 'sum', 2, false, false, 'sdm', 'Karyawan mengundurkan diri ÷ rata-rata karyawan × 100', 'HRIS', 9.8, 9.0, 8.6, 8.0, 1.12, $il],
            ['L3.1', 'L3', 'Posisi kunci dengan suksesor siap', '%', 'higher', 'last', 0, false, false, 'sdm', 'Posisi kunci dengan ≥1 suksesor siap ÷ total posisi kunci × 100', 'Talent review', 55, 65, 63, 75, .93, $il],
            ['L4.1', 'L4', 'Ketersediaan sistem inti (ERP/WMS)', '%', 'higher', 'average', 2, false, false, 'ti', 'Uptime ÷ waktu layanan × 100', 'Monitoring TI', 99.2, 99.5, 99.6, 99.7, .999, $il],
            ['L4.2', 'L4', 'Kepatuhan patch keamanan', '%', 'higher', 'average', 0, false, false, 'ti', 'Server & perangkat ter-patch ÷ total × 100', 'Keamanan TI', 88, 95, 96, 97, .98, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    /** @return array<string,PerformanceReview> */
    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-20', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => 'Kantor pusat — TCC Batavia', 'participants' => 'Direksi, kepala divisi, Strategy Office',
                'summary' => "Pendapatan Rp11,10 T (+13,1%), laba Rp435,2 M (+19,6%), EBIT tumbuh 14%.\nMargin kotor turun ke 20,26% akibat kurs & mix.\nKas operasi Rp472,1 M (2024: Rp516,4 M) karena kenaikan persediaan.\n24 titik distribusi, 38 provinsi; capex Rp476,5 M.",
                'decisions' => "1. Target 2026: pendapatan Rp12,32 T, laba Rp490 M.\n2. Program disiplin margin & konversi kas.\n3. Prioritas inisiatif: B2B, WMS gelombang 2, ekspansi luar Jawa, kepatuhan halal.",
                'follow_up' => 'Strategy Office menyusun KPI & target 2026 di Ruci BSC paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta pemantauan bulanan atas margin kotor dan kas operasi, termasuk analisis sensitivitas kurs.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-10', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => 'Kantor pusat', 'participants' => 'Direksi & kepala divisi',
                'summary' => match ($m) {
                    3 => 'Puncak Ramadan/Lebaran: pendapatan Maret tertinggi semester I; stock-out naik di 4 cabang luar Jawa.',
                    6 => 'Semester I: pendapatan 98% target; kanal digital tertinggal (72% target); survei kepuasan pelanggan 83,2.',
                    default => 'Tinjauan sasaran merah/kuning & tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Tambah stok penyangga untuk cabang Makassar & Balikpapan; percepat WMS gelombang 2.',
                    6 => 'Percepat roadmap B2B; insentif salesman untuk pesanan digital.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-20', 'Kuartal I 2026'], [2, '2026-07-20', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => 'Kantor pusat', 'participants' => 'Direksi, Dewan Komisaris (pengamat), Strategy Office',
                'summary' => $q === 1 ? 'Pendapatan Q1 sesuai target berkat Ramadan; margin kotor 20,4% (target 20,8%).' : 'Pendapatan H1 98,4% target; laba 96%; kas operasi 88% target; B2B & WMS terlambat.',
                'decisions' => $q === 1 ? 'Negosiasi ulang harga dengan 5 prinsipal utama.' : 'Bentuk tim percepatan B2B; tunda 1 dari 3 cabang baru ke 2027 bila kas operasi < 90% target pada Q3.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => 'Kantor pusat', 'participants' => 'Dewan Komisaris, Direksi, Komite Audit',
            'summary' => 'Komisaris menyoroti tekanan margin, keterlambatan B2B, dan kesiapan wajib halal Oktober 2026.',
            'decisions' => 'Direksi melaporkan progres halal setiap bulan sampai 100%.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon tambahkan analisis sensitivitas margin terhadap kurs USD/IDR dan EUR/IDR pada tinjauan Q3.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => 'Kantor pusat', 'participants' => 'Direksi & kepala divisi',
            'summary' => 'Realisasi September sebagian masih draf; kanal digital dikembalikan untuk koreksi data.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        $defs = $year === 2025 ? [
            'net' => ['DFI-25-01', 'Ekspansi titik distribusi & gudang cold storage', 'Pembukaan cabang baru & penambahan kapasitas gudang dingin.', 'T2', 'scm', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 5, ['C1', 'F1'], '24 titik distribusi.'],
            'apex' => ['DFI-25-02', 'Product Knowledge Training & Diamond Apex Program', 'Program pengembangan SDM yang disebut di laporan tahunan.', 'T4', 'sdm', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['L1', 'L3'], '85.186 jam pelatihan.'],
            'wms' => ['DFI-25-03', 'WMS gelombang 1 (10 cabang)', 'Implementasi sistem manajemen gudang di 10 cabang utama.', 'T3', 'ti', '2025-02-01', '2025-11-30', 'selesai', 4, 4, 3, 3, ['P1', 'P4'], 'Akurasi stok ≥ 99%.'],
            'machine' => ['DFI-25-04', 'Upgrade & refurbish mesin produksi', 'Peningkatan kapasitas & keandalan lini dairy & bakery.', 'T1', 'produksi', '2025-03-01', '2025-12-15', 'selesai', 4, 3, 3, 4, ['P1', 'F2'], 'Kapasitas naik, downtime turun.'],
            'halal1' => ['DFI-25-05', 'Sertifikasi halal SKU tahap 1', 'Sertifikasi halal SKU produksi sendiri prioritas.', 'T4', 'produksi', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['P3'], '97% SKU bersertifikat.'],
        ] : [
            'net' => ['DFI-26-01', 'Pembukaan 3 titik distribusi baru di luar Jawa', 'Kupang, Jayapura & Palu: gudang dingin, armada, tim penjualan.', 'T2', 'scm', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 4, 5, ['C1', 'F1'], '27 titik distribusi akhir 2026.'],
            'b2b' => ['DFI-26-02', 'Platform e-commerce B2B Diamond (fase 2)', 'Aplikasi pemesanan untuk toko tradisional & food service, integrasi ERP, pembayaran digital.', 'T3', 'ti', '2026-01-15', '2026-11-30', 'berjalan', 5, 5, 4, 4, ['C3', 'P4'], '20% pesanan via B2B.'],
            'wms2' => ['DFI-26-03', 'WMS & otomasi gudang gelombang 2', 'WMS di 6 cabang tambahan + otomasi pick-to-light di DC Cikarang.', 'T3', 'ti', '2026-02-01', '2026-12-15', 'berjalan', 4, 4, 3, 4, ['P1', 'P4'], '16 cabang live WMS.'],
            'tms' => ['DFI-26-04', 'Optimasi rute & perencanaan pengiriman (TMS)', 'Sistem digital perencanaan pengiriman untuk efisiensi distribusi.', 'T2', 'scm', '2026-03-01', '2026-10-31', 'berjalan', 4, 3, 2, 3, ['C2', 'P1'], 'OTIF ≥ 96%, biaya per drop turun 5%.'],
            'halal' => ['DFI-26-05', 'Kepatuhan wajib halal Oktober 2026', 'Sertifikasi sisa SKU produksi sendiri & verifikasi dokumen halal prinsipal.', 'T4', 'produksi', '2026-01-01', '2026-10-17', 'berjalan', 5, 5, 3, 2, ['P3'], '100% SKU bersertifikat halal.'],
            'energy' => ['DFI-26-06', 'Efisiensi energi & cadangan daya gudang dingin', 'Genset otomatis, pemantauan suhu daring, panel surya atap DC Cikarang.', 'T2', 'scm', '2026-04-01', '2027-03-31', 'berjalan', 3, 3, 2, 4, ['P1', 'F2'], 'Biaya energi per palet turun 8%.'],
            'leader' => ['DFI-26-07', 'Diamond Leadership Pipeline', 'Talent review, assessment center & program kepala cabang.', 'T4', 'sdm', '2026-02-01', '2026-12-31', 'berjalan', 4, 3, 2, 2, ['L3', 'L2'], '75% posisi kunci punya suksesor.'],
            'cyber' => ['DFI-26-08', 'Penguatan keamanan siber & DRP', 'SOC terkelola, MFA, uji pemulihan bencana.', 'T3', 'ti', '2026-03-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['L4'], 'Tidak ada insiden signifikan.'],
            'margin' => ['DFI-26-09', 'Program disiplin harga & mix produk', 'Negosiasi prinsipal, revisi harga bertahap, mix produk bermerek.', 'T1', 'komersial', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 3, 1, ['F2', 'C3'], 'Margin kotor ≥ 20,8%.'],
            'esg' => ['DFI-26-10', 'Kemasan ramah lingkungan (peta jalan pengurangan sampah)', 'Kajian kemasan & rencana pengurangan sampah produsen.', 'T4', 'produksi', '2026-09-01', '2027-06-30', 'direncanakan', 2, 2, 2, 2, ['P3'], 'Dokumen peta jalan disetujui.'],
        ];

        // aktivitas: inisiatif, nama, pemilik, mulai, tenggat, progres
        $acts = $year === 2025 ? [
            ['net', 'Pembukaan cabang & depo baru', 'scm', '2025-01-01', '2025-09-30', 100], ['net', 'Penambahan kapasitas gudang dingin', 'scm', '2025-03-01', '2025-12-31', 100],
            ['apex', 'Product Knowledge Training', 'sdm', '2025-01-01', '2025-12-31', 100], ['apex', 'Diamond Apex Program', 'sdm', '2025-02-01', '2025-12-31', 100],
            ['wms', 'Implementasi WMS 10 cabang', 'ti', '2025-02-01', '2025-11-30', 100],
            ['machine', 'Refurbish lini dairy & bakery', 'produksi', '2025-03-01', '2025-12-15', 100],
            ['halal1', 'Audit & sertifikasi SKU prioritas', 'produksi', '2025-01-01', '2025-12-31', 100],
        ] : [
            ['net', 'Studi lokasi & sewa gudang', 'scm', '2026-01-01', '2026-03-31', 100], ['net', 'Pembangunan gudang dingin Kupang', 'scm', '2026-03-01', '2026-08-31', 100],
            ['net', 'Pembangunan gudang dingin Jayapura', 'scm', '2026-04-01', '2026-10-31', 90], ['net', 'Gudang Palu & rekrutmen tim', 'scm', '2026-06-01', '2026-12-31', 60],
            ['b2b', 'Desain UX & prototipe aplikasi', 'ti', '2026-01-15', '2026-03-31', 100], ['b2b', 'Integrasi ERP & katalog harga', 'ti', '2026-03-01', '2026-07-31', 55],
            ['b2b', 'Pilot 500 toko di Jabodetabek', 'komersial', '2026-06-01', '2026-09-30', 20], ['b2b', 'Peluncuran nasional', 'komersial', '2026-10-01', '2026-11-30', 0],
            ['wms2', 'WMS cabang Surabaya, Semarang, Medan', 'ti', '2026-02-01', '2026-06-30', 100], ['wms2', 'WMS cabang Makassar, Balikpapan, Denpasar', 'ti', '2026-06-01', '2026-10-31', 85],
            ['wms2', 'Pick-to-light DC Cikarang', 'scm', '2026-05-01', '2026-12-15', 65],
            ['tms', 'Pemilihan vendor TMS', 'scm', '2026-03-01', '2026-04-30', 100], ['tms', 'Rollout TMS Jawa', 'scm', '2026-05-01', '2026-09-30', 100], ['tms', 'Rollout TMS luar Jawa', 'scm', '2026-08-01', '2026-10-31', 95],
            ['halal', 'Pemetaan SKU & bahan baku', 'produksi', '2026-01-01', '2026-02-28', 100], ['halal', 'Audit LPH & pengajuan sertifikat', 'produksi', '2026-02-01', '2026-08-31', 100],
            ['halal', 'Verifikasi dokumen halal prinsipal', 'komersial', '2026-03-01', '2026-10-10', 60],
            ['energy', 'Genset otomatis 8 cabang', 'scm', '2026-04-01', '2026-09-30', 100], ['energy', 'Panel surya atap DC Cikarang', 'scm', '2026-07-01', '2027-03-31', 30],
            ['leader', 'Talent review & 9-box', 'sdm', '2026-02-01', '2026-04-30', 100], ['leader', 'Program kepala cabang angkatan 1', 'sdm', '2026-05-01', '2026-11-30', 60],
            ['cyber', 'MFA seluruh pengguna', 'ti', '2026-03-01', '2026-05-31', 100], ['cyber', 'SOC terkelola 24/7', 'ti', '2026-05-01', '2026-08-31', 100], ['cyber', 'Uji DRP', 'ti', '2026-10-01', '2026-12-31', 0],
            ['margin', 'Negosiasi 5 prinsipal utama', 'komersial', '2026-01-15', '2026-05-31', 100], ['margin', 'Revisi harga bertahap', 'komersial', '2026-04-01', '2026-12-31', 60],
            ['esg', 'Kajian kemasan', 'produksi', '2026-09-01', '2026-12-31', 10],
        ];
        // milestone: inisiatif, nama, tenggat, tercapai
        $milestones = $year === 2025 ? [
            ['net', '24 titik distribusi beroperasi', '2025-12-31', true], ['wms', 'Go-live 10 cabang', '2025-11-30', true], ['halal1', '97% SKU bersertifikat', '2025-12-31', true],
        ] : [
            ['net', 'Gudang Kupang beroperasi', '2026-08-31', true], ['net', 'Gudang Jayapura beroperasi', '2026-10-31', false], ['net', 'Gudang Palu beroperasi', '2026-12-31', false],
            ['b2b', 'Pilot 500 toko live', '2026-08-31', false], ['b2b', 'Peluncuran nasional', '2026-11-30', false],
            ['wms2', 'WMS 3 cabang Jawa & Sumatra', '2026-06-30', true], ['wms2', 'WMS 16 cabang', '2026-12-15', false],
            ['tms', 'TMS live di Jawa', '2026-09-30', true],
            ['halal', 'Seluruh SKU produksi sendiri bersertifikat', '2026-10-17', false], ['halal', 'Dokumen halal prinsipal lengkap', '2026-10-10', false],
            ['leader', 'Talent review selesai', '2026-04-30', true], ['cyber', 'SOC 24/7 aktif', '2026-08-31', true],
        ];

        // anggaran (Rp): inisiatif, item, kategori, rencana, realisasi, catatan
        $capexNote = 'Bagian dari capex 2025 Rp476,5 M (laporan tahunan); rincian per item ilustratif.';
        $budgets = $year === 2025 ? [
            ['net', 'Cabang baru & peralatan logistik', 'capex', 320_000_000_000, 296_400_000_000], ['machine', 'Upgrade & refurbish mesin', 'capex', 150_000_000_000, 141_600_000_000],
            ['wms', 'Lisensi & implementasi WMS', 'capex', 42_000_000_000, 38_500_000_000], ['apex', 'Program pelatihan', 'opex', 12_000_000_000, 11_400_000_000],
            ['halal1', 'Biaya audit & sertifikasi', 'opex', 3_500_000_000, 3_200_000_000],
        ] : [
            ['net', 'Gudang dingin 3 lokasi', 'capex', 210_000_000_000, 128_000_000_000], ['net', 'Armada berpendingin 60 unit', 'capex', 96_000_000_000, 51_000_000_000],
            ['b2b', 'Pengembangan platform B2B', 'capex', 38_000_000_000, 17_500_000_000], ['b2b', 'Insentif adopsi pelanggan', 'opex', 9_000_000_000, 1_200_000_000],
            ['wms2', 'WMS 6 cabang & pick-to-light', 'capex', 55_000_000_000, 24_800_000_000], ['tms', 'Lisensi TMS', 'opex', 6_500_000_000, 4_900_000_000],
            ['halal', 'Audit LPH & sertifikasi', 'opex', 4_200_000_000, 3_600_000_000], ['energy', 'Genset & panel surya', 'capex', 48_000_000_000, 19_000_000_000],
            ['leader', 'Assessment center & program', 'opex', 7_500_000_000, 4_100_000_000], ['cyber', 'SOC terkelola & DRP', 'opex', 14_000_000_000, 8_200_000_000],
            ['margin', 'Konsultan pricing', 'opex', 2_500_000_000, 2_100_000_000], ['esg', 'Kajian kemasan', 'opex', 1_500_000_000, 0],
        ];
        $budgets = array_map(fn ($b) => [...$b, $year === 2025 && $b[2] === 'capex' ? $capexNote : null], $budgets);

        return ['defs' => $defs, 'activities' => $acts, 'milestones' => $milestones, 'budgets' => $budgets];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Kas operasi turun meski laba naik', 'Kenaikan persediaan menekan kas operasi 2025 (Rp472,1 M vs Rp516,4 M).', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 44349000000, 'F3.3', null, 'komisaris', '2026-02-20',
                [['Program penurunan hari persediaan 2026', 'scm', '2026-06-30', 'berjalan', 40]], null],
            ['Data stock-out belum seragam antar cabang', 'Definisi stock-out berbeda antar cabang sebelum WMS.', 'audit', null, 'proses', 'sedang', null, 'P1.2', 'wms', 'strategi', '2025-09-15',
                [['Standarisasi definisi & laporan stock-out', 'scm', '2025-11-30', 'selesai', 100]], ['2025-12-10', 'Laporan stock-out seragam di seluruh cabang WMS.']],
        ] : [
            ['Kanal digital jauh di bawah target', 'Kontribusi kanal digital 72% dari target; pilot B2B terlambat.', 'tinjauan', 'q2', 'pelanggan', 'tinggi', null, 'C3.1', 'b2b', 'dirut', '2026-07-20',
                [['Bentuk tim percepatan B2B', 'ti', '2026-08-15', 'selesai', 100], ['Insentif salesman untuk pesanan digital', 'komersial', '2026-10-31', 'berjalan', 60], ['Pilot 500 toko live', 'komersial', '2026-09-30', 'berjalan', 20]], null],
            ['Deviasi suhu armada di Makassar', 'Data logger mencatat 37 kejadian suhu di atas batas pada Juli 2026; produk es krim rusak Rp1,2 M.', 'audit', null, 'proses', 'kritis', 1200000000, 'P1.3', 'energy', 'produksi', '2026-08-05',
                [['Ganti unit pendingin 6 armada', 'scm', '2026-09-15', 'selesai', 100], ['Pelatihan ulang pengemudi rantai dingin', 'sdm', '2026-09-30', 'selesai', 100]], null],
            ['Margin kotor di bawah target karena kurs', 'Margin kotor YTD 20,5% vs target 20,8%.', 'timwas', 'boc', 'keuangan', 'tinggi', null, 'F2.2', 'margin', 'komisaris', '2026-07-28',
                [['Analisis sensitivitas kurs', 'cfo', '2026-08-31', 'selesai', 100], ['Revisi harga bertahap 120 SKU', 'komersial', '2026-12-31', 'berjalan', 60]], null],
            ['Dokumen halal 14 prinsipal belum lengkap', 'Tenggat wajib halal 17 Oktober 2026.', 'internal', 'rm8', 'proses', 'kritis', null, 'P3.2', 'halal', 'produksi', '2026-09-10',
                [['Surat permintaan dokumen ke prinsipal', 'komersial', '2026-09-20', 'selesai', 100], ['Rencana delisting SKU tanpa dokumen', 'komersial', '2026-10-10', 'berjalan', 50]], null],
            ['Keluhan pengiriman tidak lengkap meningkat', 'Keluhan Q2 naik 9% dibanding Q1, terutama pesanan food service.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'C2.3', 'tms', 'scm', '2026-07-20',
                [['Checklist pemuatan berbasis TMS', 'scm', '2026-08-31', 'selesai', 100]], null],
            ['Turnover salesman cabang baru tinggi', 'Turnover sukarela salesman Kupang 4 orang dalam 3 bulan.', 'internal', 'rm7', 'sdm', 'sedang', null, 'L2.2', 'leader', 'sdm', '2026-08-10',
                [['Exit interview & penyesuaian insentif', 'sdm', '2026-09-30', 'belum', 0]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan prinsipal atas dokumen halal', 'eksternal', 'halal', 'P3', 'Prinsipal merek internasional', 'Sertifikat halal SKU prinsipal bergantung pada dokumen dari prinsipal.', 'komersial', 'tinggi', 'Surat resmi, eskalasi ke regional, rencana delisting.'],
                ['Vendor integrasi ERP untuk B2B', 'eksternal', 'b2b', 'P4', 'Vendor ERP', 'API harga & stok real-time dibutuhkan sebelum pilot.', 'ti', 'tinggi', 'Tambah sumber daya vendor; fallback sinkronisasi per jam.'],
                ['Izin bangunan gudang Jayapura', 'eksternal', 'net', 'C1', 'Pemerintah daerah', 'PBG gudang dingin Jayapura masih proses.', 'scm', 'sedang', 'Sewa gudang sementara.'],
                ['Kesiapan data WMS sebelum TMS luar Jawa', 'internal', 'tms', 'P1', 'Proyek WMS gelombang 2', 'TMS luar Jawa membutuhkan data stok akurat dari WMS.', 'scm', 'sedang', 'Urutkan rollout mengikuti WMS.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 10.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat manajemen bulanan', 'rapat', 'Membahas sasaran merah/kuning & menutup temuan.', 'Bulanan', 'dirut', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Tinjauan kinerja & risiko bersama Komisaris.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Kebijakan bukti pendukung realisasi', 'kebijakan', 'Setiap realisasi KPI wajib melampirkan sumber data.', 'Berkelanjutan', 'cfo', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Talent review tahunan', 'pelatihan', 'Talent review & rencana suksesi.', 'Tahunan', 'sdm', 'berjalan'],
                ['Budaya kinerja tinggi', 'budaya', 'Pengakuan cabang terbaik tiap kuartal berdasarkan skor BSC.', 'Kuartalan', 'sdm', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
