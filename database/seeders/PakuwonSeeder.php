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
 * Demo PT Pakuwon Jati Tbk (kode PWON) — pengembang superblok, mal, hotel, perkantoran & kota mandiri.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (pendapatan, EBITDA, laba, okupansi mal/kantor/hotel, marketing sales, rasio keuangan, SDM).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: bisnis berbasis pendapatan berulang (±79%) vs. siklus residensial yang melemah.
 */
class PakuwonSeeder extends ShowcaseSeeder
{
    public const CODE = 'PWON';

    public const LT = 'Laporan Tahunan 2025 PT Pakuwon Jati Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Pakuwon Jati Tbk', 'legal_name' => 'PT Pakuwon Jati Tbk',
            'industry' => 'Properti (superblok, pusat perbelanjaan, hotel, perkantoran, kota mandiri)',
            'address' => 'Eastcoast Center Lt. 5, Pakuwon City, Jl. Kejawan Putih Mutiara No. 17', 'city' => 'Surabaya',
            'phone' => '+62 31 5820 8788', 'website' => 'https://www.pakuwonjati.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham PWON). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Ramadan–Lebaran (akhir Maret 2025, 20 Maret 2026) dan libur Desember menaikkan kunjungan & pendapatan mal. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.0, 7.6, 8.6, 8.3, 8.1, 8.4, 8.5, 8.2, 8.0, 8.2, 8.3, 9.6]),
            2026 => self::weights([1 => 8.1, 7.9, 8.7, 8.1, 8.1, 8.4, 8.5, 8.2, 8.0, 8.2, 8.3, 9.5]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['C1.1', 'C1.2', 'C1.4', 'C2.2', 'C2.3', 'F1.3', 'F2.3', 'P3.2', 'F2.2', 'F2.4', 'F3.1', 'F3.2', 'L1.3', 'L2.1', 'P1.1', 'P1.2', 'P3.1', 'P4.2', 'C4.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C1.3', 'P3.3'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C2.1', 'Okupansi September masih memasukkan kamar Four Points Bekasi yang sedang renovasi lantai 7–8. Mohon gunakan kamar tersedia (available rooms) sebagai pembagi dan lampirkan laporan night audit per hotel.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P5.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 2, 3, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Pakuwon (contoh)', 'pwon.admin', 'Admin Sistem', 'Corporate Planning', ['company-admin']],
            'dirut' => ['Presiden Direktur (contoh)', 'pwon.direktur', 'Presiden Direktur', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'pwon.keuangan', 'Direktur Keuangan', 'Keuangan & Treasury', ['keuangan']],
            'mal' => ['Direktur Pusat Perbelanjaan (contoh)', 'pwon.mal', 'Direktur Retail & Leasing', 'Pusat Perbelanjaan & Leasing', ['kepala-unit']],
            'hotel' => ['Direktur Hospitality (contoh)', 'pwon.hotel', 'Direktur Hospitality', 'Hotel & Apartemen Servis', ['kepala-unit']],
            'properti' => ['Direktur Pengembangan & Residensial (contoh)', 'pwon.properti', 'Direktur Pengembangan', 'Pengembangan Proyek & Residensial', ['kepala-unit']],
            'operasional' => ['Kepala Building Management (contoh)', 'pwon.operasional', 'Head of Building Management', 'Engineering & Building Management', ['kepala-unit']],
            'pemasaran' => ['Kepala Pemasaran & CRM (contoh)', 'pwon.pemasaran', 'Head of Marketing & CRM', 'Pemasaran & CRM', ['kepala-unit']],
            'sdm' => ['Direktur SDM (contoh)', 'pwon.sdm', 'Direktur SDM', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Perencanaan Strategis (contoh)', 'pwon.strategi', 'Corporate Planning & Risk Manager', 'Perencanaan Strategis & Manajemen Risiko', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'pwon.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'pwon.analis', 'Performance Analyst', 'Perencanaan Strategis & Manajemen Risiko', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Tumbuh Bersama Pemegang Saham, Karyawan, Penyewa, dan Pembeli.',
            'mission' => "1. Menjadi pengembang pusat perbelanjaan retail non-strata yang terdepan di Indonesia.\n2. Mengembangkan superblok terbaik dan pengembang hunian berskala kota mandiri untuk kualitas hidup yang lebih baik.\n3. Menjadi tempat kerja terbaik dalam industri properti.\n4. Mengoptimalkan pengembalian investasi bagi penyewa dan pembeli.",
            'purpose' => 'Menghadirkan kawasan terpadu tempat orang berbelanja, bekerja, menginap, dan tinggal dengan kualitas hidup yang lebih baik.',
            'tagline' => 'Together We Grow',
            'brand_promise' => 'Mal dan superblok yang selalu relevan sebagai destinasi gaya hidup, dikelola dengan disiplin.',
            'value_proposition' => '11 pusat perbelanjaan (NLA 859.000 m², okupansi 94%), 10 hotel (2.621 kamar), 6 perkantoran (GFA 155.000 m²), 2 apartemen servis, dan kota mandiri 1.100 ha di Surabaya; ±79% pendapatan berulang.',
            'culture_statement' => 'Nilai I CARE: Integrity, Agility, Collaboration, Resilience, Excellence.',
            'history' => "1982: berdiri; proyek pertama Tunjungan Plaza I, mal modern pertama di Surabaya.\n1989: perusahaan properti pertama yang tercatat di Bursa Efek Jakarta.\n2007: ekspansi ke Jakarta — Gandaria City.\n2010: akuisisi superblok Kota Kasablanka.\n2020: akuisisi Hartono Mall Yogyakarta & Solo serta Marriott Yogyakarta.\n2024: Pakuwon City Mall Tahap 3 & Pakuwon Mall Bekasi dibuka.\n2025: Fairfield Bekasi, Aloft Surabaya & Four Points Bekasi dibuka; tender offer obligasi US\$66,6 juta; groundbreaking Pakuwon Mall Semarang.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Integritas', 'Integrity', 'Bekerja dengan jujur serta menjaga kehormatan harkat dan martabat perusahaan.', "Melaporkan okupansi & penjualan apa adanya\nTransparan dalam negosiasi dengan tenant"],
            ['Kelincahan', 'Agility', 'Menyesuaikan diri terhadap perubahan strategi dan bersikap proaktif, tidak defensif.', "Cepat mengubah tenant mix sesuai tren\nMencoba format aktivasi baru"],
            ['Kolaborasi', 'Collaboration', 'Membangun kerja sama internal dan eksternal serta menjaga hubungan baik dengan pelanggan.', "Mal, hotel, dan residensial saling mengisi pengunjung\nMenyelesaikan keluhan tenant lintas fungsi"],
            ['Ketangguhan', 'Resilience', 'Bangkit, beradaptasi, dan pulih secara efektif dari tantangan.', "Menyiapkan rencana B saat pasar residensial melemah\nBelajar dari proyek yang terlambat"],
            ['Keunggulan', 'Excellence', 'Menjaga kualitas kerja, kreatif dan inovatif, serta perbaikan berkelanjutan agar menjadi pemimpin pasar.', "Standar layanan mal kelas utama\nPerbaikan berkelanjutan biaya & energi"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Portofolio mal kelas utama', 'Sumber daya fisik', '11 mal, NLA 859.000 m², okupansi 94% di Jakarta, Surabaya, Bekasi, Yogyakarta, Solo.', 5, 5, true, 'Renovasi & aktivasi berkelanjutan.'],
            ['Model superblok terintegrasi', 'Kompetensi', 'Mal + hotel + kantor + hunian saling menghidupkan (Tunjungan City sebagai superblok pertama di Indonesia).', 5, 5, true, 'Replikasi ke Semarang & Bekasi.'],
            ['Cadangan lahan', 'Sumber daya fisik', '±521,7 ha land bank, memadai untuk pipeline > 10 tahun.', 4, 5, true, 'Monetisasi terukur sesuai daya serap pasar.'],
            ['Neraca kuat & kas besar', 'Sumber daya keuangan', 'DER 0,21x; kas & aset keuangan lain Rp12,5 T.', 5, 5, false, 'Lindung nilai alami USD; disiplin capex.'],
            ['Pengelolaan hotel bersama operator global', 'Kompetensi', 'Marriott, Sheraton, Westin, Four Points, Aloft, Fairfield.', 3 + $lvl, 4, false, 'Fokus segmen korporasi & non-pemerintah.'],
            ['Data pengunjung & CRM', 'Sistem', 'Data pengunjung dan program loyalitas belum terintegrasi lintas mal.', 2 + $lvl, 4, false, 'Platform CRM & aplikasi loyalitas terpadu.'],
            ['Pengembangan talenta properti', 'Kompetensi', '3.478 karyawan; jam pelatihan rata-rata turun ke 21 jam.', 2 + $lvl, 4, false, 'Akademi Pakuwon & jalur karier.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi makro & properti 2025', '2026-01-20', 'eksternal', self::LT.' — Laporan Direksi',
                'Ekonomi Indonesia tumbuh 5,11%; penjualan residensial primer Q4 +7,83% yoy, tetapi kelas menengah tertekan sehingga pembelian rumah ditunda. Okupansi hotel berbintang nasional ±49,3%.',
                'Residensial selektif; ritel terpolarisasi ke F&B dan experiential; kantor masih tertekan pola kerja hybrid.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Kilas Kinerja',
                'Pendapatan Rp7,11 T (+7%, target Rp7,21 T); pendapatan mal Rp3,93 T (+14%); EBITDA Rp3,71 T; laba bersih atribusi Rp2,35 T (+13,1%); marketing sales Rp1,30 T (−16%).',
                'Pendapatan berulang ±79% menjadi penopang; residensial dan kantor perlu strategi baru.'],
            ['Pembatasan kegiatan pemerintah di hotel', '2026-02-05', 'eksternal', self::LT.' — Laporan Direksi',
                'Kebijakan efisiensi anggaran menekan okupansi & F&B hotel pada semester I 2025, pulih sejak pertengahan tahun.',
                'Geser bauran tamu ke korporasi & leisure.'],
            ['Tantangan 2026', '2026-02-05', 'internal', self::LT.' — Tantangan & Mitigasi 2026',
                'Suku bunga tinggi, volatilitas rupiah, biaya utilitas & tenaga kerja naik; kompleksitas teknis Pakuwon Mall Phase 5, Kota Kasablanka Phase 4 & Pakuwon Mall Semarang (kondisi tanah).',
                'Penguatan perencanaan teknis, skema bayar fleksibel residensial, unit kantor siap pakai.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Efisiensi anggaran pemerintah', 'Pembatasan rapat & perjalanan dinas menurunkan permintaan MICE hotel.', 'ancaman', 4, 4, 'stabil', 'Segmen korporasi, leisure, dan acara komunitas.'],
            ['politik', 'Insentif PPN DTP rumah', 'Insentif pajak rumah siap huni mendorong penjualan residensial.', 'peluang', 3, 3, 'stabil', 'Percepat stok siap huni.'],
            ['ekonomi', 'Suku bunga & daya beli kelas menengah', 'Suku bunga tinggi menunda keputusan membeli rumah.', 'ancaman', 5, 4, 'stabil', 'Skema bayar fleksibel & produk ukuran lebih kecil.'],
            ['ekonomi', 'Volatilitas rupiah', 'Obligasi USD US$333 juta setelah tender offer menimbulkan eksposur kurs.', 'ancaman', 4, 4, 'naik', 'Natural hedging: pembelian USD & reksa dana.'],
            ['sosial', 'Pergeseran ke ritel berbasis pengalaman', 'Konsumen mengutamakan pengalaman & nilai, bukan sekadar merek prestisius.', 'peluang', 5, 5, 'naik', 'Park Shanghai, Food Festival, aktivasi event.'],
            ['sosial', 'Pola kerja hybrid', 'Kebutuhan ruang kantor berkurang; vacancy kantor Jakarta tinggi.', 'ancaman', 4, 5, 'stabil', 'Unit kantor siap pakai & sewa fleksibel.'],
            ['teknologi', 'E-commerce & social commerce', 'Belanja daring menggerus kunjungan untuk kategori barang tertentu.', 'ancaman', 3, 4, 'naik', 'Perkuat F&B, hiburan, layanan.'],
            ['teknologi', 'Data pengunjung & aplikasi loyalitas', 'Personalisasi meningkatkan frekuensi kunjungan dan belanja.', 'peluang', 4, 4, 'naik', 'Platform CRM terpadu.'],
            ['lingkungan', 'Bangunan hijau & efisiensi energi', 'Tenant & investor menuntut gedung bersertifikat hijau.', 'peluang', 3, 4, 'naik', 'Sertifikasi green building, panel surya.'],
            ['lingkungan', 'Pengelolaan sampah kota', 'Kewajiban pengurangan sampah ke TPA (mis. Pergub DKI 102/2021).', 'ancaman', 3, 4, 'naik', 'Pengolahan sampah menjadi RDF (#mulaidarisini).'],
            ['hukum', 'Perizinan bangunan & tata ruang', 'PBG & kesesuaian tata ruang menentukan jadwal proyek.', 'ancaman', 3, 3, 'stabil', 'Tim perizinan sejak tahap desain.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Persaingan mal & proyek mixed-use', 'Pertumbuhan mal & mixed-use baru di Jakarta dan Surabaya.', 4, 'Tenant mix eksklusif & aktivasi kawasan.'],
            ['kekuatan_pembeli', 'Tenant jangkar & merek besar', 'Tenant jangkar memiliki daya tawar tinggi atas tarif sewa.', 3, 'Diversifikasi tenant & porsi F&B.'],
            ['kekuatan_pemasok', 'Kontraktor & material bangunan', 'Harga material & ketersediaan kontraktor memengaruhi biaya proyek.', 3, 'Kontrak harga tetap & pengawasan teknis.'],
            ['pendatang_baru', 'Pengembang baru di kota tier-2', 'Pengembang regional masuk Semarang, Solo, Yogyakarta.', 3, 'Masuk lebih awal dengan lahan strategis.'],
            ['produk_pengganti', 'Belanja daring & hiburan rumah', 'Kanal daring menggantikan sebagian kunjungan belanja.', 4, 'Mal sebagai destinasi pengalaman.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Tenant ritel, F&B & hiburan', 'Pusat perbelanjaan', 'Penyewa di 11 mal (NLA 859.000 m²).', 55, 'naik', true, 'Traffic tinggi, biaya okupansi wajar, dukungan promosi.', 'Kurasi tenant mix & aktivasi.'],
            ['Tamu hotel (korporasi, leisure, MICE)', 'Hospitality', '10 hotel, 2.621 kamar.', 18, 'naik', true, 'Lokasi terintegrasi, layanan merek global.', 'Fokus segmen korporasi & non-pemerintah.'],
            ['Penyewa & pembeli ruang kantor', 'Perkantoran', '6 gedung kantor, GFA 155.000 m².', 6, 'stabil', true, 'Ruang siap pakai, fleksibel.', 'Show unit siap pakai & sewa fleksibel.'],
            ['Pembeli rumah & apartemen', 'Residensial', 'Kota mandiri Pakuwon City & Grand Pakuwon (1.100 ha), kondominium superblok.', 21, 'turun', true, 'Harga terjangkau, skema bayar fleksibel, serah terima tepat waktu.', 'Produk relevan & skema pembayaran fleksibel.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan per segmen ilustratif untuk demo; pendapatan berulang ±79% dari laporan tahunan']);
        }

        foreach ([
            ['PT Pakuwon Jati Tbk', 'Pengembang superblok & mal', 1, 7111, 6.6, 33.0, true, 'Pendapatan berulang ±79%, mal kelas utama, neraca sangat kuat.', 'Residensial & kantor tertekan; konsentrasi di Surabaya & Jakarta.'],
            ['Pengembang kota mandiri nasional', 'Pengembang township', 2, null, null, null, false, 'Land bank sangat besar, merek hunian kuat.', 'Porsi pendapatan berulang lebih kecil.'],
            ['Pengembang mal & mixed-use Jakarta', 'Pengembang komersial', 3, null, null, null, false, 'Lokasi CBD premium.', 'Portofolio terkonsentrasi di satu kota.'],
            ['REIT & dana properti regional', 'Investor', 4, null, null, null, false, 'Biaya modal rendah.', 'Kurang lincah dalam aktivasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar; marjin laba bersih atribusi).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Persetujuan Bangunan Gedung (PBG) & SLF', 'Pemerintah kabupaten/kota', 'PP No. 16/2021 tentang Bangunan Gedung', 'Perizinan', null, 'PBG sebelum konstruksi dan SLF sebelum operasi.', 'Menentukan jadwal Semarang, PM Phase 5, Kokas Phase 4.', 'patuh', 'properti', null],
            ['Rumah susun & strata title', 'Kementerian ATR/BPN & PUPR', 'UU No. 20/2011 tentang Rumah Susun', 'Residensial', null, 'Pengelolaan strata title dan P3SRS.', 'Serah terima & pengelolaan kondominium.', 'patuh', 'properti', null],
            ['Pengelolaan sampah', 'Pemprov DKI Jakarta', 'Pergub DKI No. 102/2021', 'Lingkungan', '2021-10-06', 'Kewajiban pengelolaan sampah kawasan secara mandiri.', 'Program RDF & pemilahan sampah di mal.', 'sebagian', 'operasional', '2026-12-31'],
            ['SMK3', 'Kementerian Ketenagakerjaan', 'PP No. 50/2012', 'K3', null, 'Penerapan sistem manajemen K3; penilaian "Baik".', 'Keselamatan pengunjung & pekerja.', 'patuh', 'operasional', null],
            ['Tata kelola emiten & keuangan berkelanjutan', 'OJK & BEI', 'POJK No. 21/2015 & POJK 51/2017', 'Pasar modal', null, 'Laporan tahunan & keberlanjutan.', 'Kepatuhan emiten PWON.', 'patuh', 'cfo', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pengunjung, member loyalitas, dan pembeli.', 'Prasyarat platform CRM.', 'sebagian', 'pemasaran', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham & investor (PWON)', 'internal', 'Pemilik', 5, 5, 'Pertumbuhan laba, dividen, tata kelola.', 'RUPS, paparan publik, laporan tahunan.', 'Investor Relations'],
            'tenant' => ['Tenant mal & penyewa kantor', 'eksternal', 'Pelanggan', 4, 5, 'Traffic tinggi, layanan gedung, biaya wajar.', 'Forum tenant & account manager.', 'Leasing'],
            'pengunjung' => ['Pengunjung mal & tamu hotel', 'eksternal', 'Pelanggan', 3, 5, 'Pengalaman menyenangkan, aman, nyaman.', 'Aplikasi loyalitas & survei.', 'Pemasaran & CRM'],
            'pembeli' => ['Pembeli rumah & apartemen', 'eksternal', 'Pelanggan', 3, 5, 'Serah terima tepat waktu, kualitas bangunan.', 'Customer care & komunitas penghuni.', 'Residensial'],
            'operator' => ['Operator hotel global (Marriott dll.)', 'eksternal', 'Mitra', 4, 4, 'Standar merek & kinerja hotel.', 'Rapat kinerja bulanan.', 'Hospitality'],
            'kreditur' => ['Pemegang obligasi & lembaga pemeringkat', 'eksternal', 'Pemberi dana', 4, 4, 'Likuiditas & leverage rendah.', 'Laporan berkala & pertemuan pemeringkat.', 'Treasury'],
            'karyawan' => ['Karyawan (3.478 orang)', 'internal', 'SDM', 4, 5, 'Pengembangan karier & kesejahteraan.', 'Survei keterlibatan & town hall.', 'Human Capital'],
            'pemda' => ['Pemerintah daerah & regulator', 'eksternal', 'Regulator', 5, 3, 'Perizinan, tata ruang, lingkungan.', 'Komunikasi proaktif sejak desain.', 'Legal & Perizinan'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'residensial' => ['Marketing sales turun 16%', 'Marketing sales Rp1,30 T (2024: ±Rp1,55 T) karena kehati-hatian pembeli dan suku bunga.', 'pelanggan', 5, 4, 'properti', ['pembeli', 'pemegang_saham']],
            'kantor' => ['Okupansi kantor di bawah target', 'Okupansi kantor 74%; pola kerja hybrid & persaingan ketat.', 'pelanggan', 4, 3, 'mal', ['tenant']],
            'hotel' => ['Okupansi hotel tertekan & tiga hotel baru dalam ramp-up', 'Okupansi hotel 64%; pembatasan kegiatan pemerintah semester I.', 'pelanggan', 4, 4, 'hotel', ['operator']],
            'proyek' => ['Kompleksitas teknis proyek strategis', 'PM Phase 5, Kokas Phase 4 & PM Semarang (kondisi tanah) berisiko terlambat.', 'proses', 5, 5, 'properti', ['pemda', 'pemegang_saham']],
            'valas' => ['Eksposur obligasi USD', 'Pokok obligasi US$333,4 juta setelah tender offer; rupiah bergejolak.', 'keuangan', 4, 4, 'cfo', ['kreditur']],
            'sdm' => ['Pengembangan SDM melemah', 'Rata-rata jam pelatihan turun ke 21 jam (2024: 37 jam); turnover 13% (2024: 10%).', 'sdm', 4, 4, 'sdm', ['karyawan']],
            'relevansi' => ['Relevansi mal di era belanja daring', 'Konsumen mengutamakan pengalaman; tenant repositioning sempat mengurangi pendapatan.', 'pelanggan', 4, 5, 'mal', ['tenant', 'pengunjung']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Pendapatan berulang ±79%', 'Mal, hotel, kantor menstabilkan kinerja.', 0.15, 4],
            ['S', 'Mal kelas utama berokupansi 94%', '11 mal di kota besar.', 0.13, 4],
            ['S', 'Neraca sangat kuat', 'DER 0,21x; kas & aset keuangan Rp12,5 T.', 0.10, 4],
            ['S', 'Land bank ±521,7 ha', 'Pipeline > 10 tahun.', 0.07, 3],
            ['W', 'Ketergantungan pada Surabaya & Jakarta', 'Diversifikasi kota baru masih awal.', 0.07, 2],
            ['W', 'Residensial & kantor lemah', 'Marketing sales −16%; okupansi kantor 74%.', 0.10, 2],
            ['W', 'Pengembangan SDM menurun', 'Jam pelatihan & turnover memburuk.', 0.05, 2],
            ['O', 'Ritel berbasis pengalaman', 'F&B & hiburan menarik traffic.', 0.09, 3],
            ['O', 'Kota tier-2 (Semarang, Bekasi)', 'Kebutuhan mal modern belum terpenuhi.', 0.07, 3],
            ['O', 'Pemulihan hotel & pariwisata', 'Permintaan pulih sejak pertengahan 2025.', 0.05, 3],
            ['T', 'Suku bunga & daya beli', 'Menunda pembelian rumah.', 0.06, 2],
            ['T', 'Belanja daring', 'Menggerus kategori ritel tertentu.', 0.04, 2],
            ['T', 'Volatilitas rupiah', 'Obligasi USD.', 0.02, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Replikasi superblok ke kota tier-2', 'Pakuwon Mall Semarang memakai model Tunjungan City & Pakuwon Mall.', 5, ['proyek']],
            ['SO', 'Destinasi pengalaman di setiap mal', 'Park Shanghai, Food Festival, event komunitas, tenant experiential.', 5, ['relevansi']],
            ['WO', 'Residensial: produk relevan & bayar fleksibel', 'Ukuran lebih kecil, stok siap huni, insentif PPN DTP.', 4, ['residensial']],
            ['WO', 'Kantor siap pakai & sewa fleksibel', 'Show unit fully-fitted untuk mempercepat penyerapan.', 3, ['kantor']],
            ['ST', 'Natural hedging & pelunasan obligasi bertahap', 'Gunakan kas kuat untuk mengurangi eksposur USD.', 4, ['valas']],
            ['WT', 'Hotel fokus segmen korporasi & leisure', 'Kurangi ketergantungan pada acara pemerintah.', 4, ['hotel']],
            ['WT', 'Akademi Pakuwon & retensi talenta', 'Kembalikan jam pelatihan & turunkan turnover.', 3, ['sdm']],
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
            'T1' => ['Pendapatan Berulang & Destinasi', 'Mal, hotel, dan kantor sebagai mesin pendapatan berulang yang stabil.', '#8C1D40'],
            'T2' => ['Pertumbuhan Superblok & Land Bank', 'Ekspansi ke kota baru dan monetisasi cadangan lahan secara disiplin.', '#1F6E8C'],
            'T3' => ['Disiplin Keuangan & Keunggulan Operasi', 'Struktur modal kuat, biaya terkendali, gedung efisien dan hijau.', '#B8860B'],
            'T4' => ['SDM, Budaya I CARE & Data Pelanggan', 'Tempat kerja terbaik industri properti dan CRM terintegrasi.', '#3C7D3C'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Mal non-strata yang disewakan, bukan dijual', 'produk', 'Misi 1: pengembang mal retail non-strata terdepan.'],
            ['T1', 'Ritel berbasis pengalaman (F&B, hiburan, komunitas)', 'pasar', 'Traffic ritel ditopang F&B & experiential.'],
            ['T2', 'Superblok di kota tier-2 dengan daya beli tumbuh', 'pasar', 'Groundbreaking Pakuwon Mall Semarang 2025.'],
            ['T3', 'Natural hedging & disiplin belanja modal', 'organisasi', 'Tender offer obligasi & pembelian USD.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pertumbuhan pendapatan berulang', 'Pendapatan tumbuh ±7–8% dengan porsi berulang ≥ 80%.', 'cfo', 3],
            'F2' => ['keuangan', 'T3', 'Profitabilitas & marjin terjaga', 'EBITDA & laba atribusi naik; marjin stabil.', 'cfo', 3],
            'F3' => ['keuangan', 'T3', 'Struktur keuangan kuat & likuid', 'ROE naik, DER rendah, eksposur USD turun.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Mal sebagai destinasi gaya hidup pilihan', 'Okupansi tinggi, kunjungan naik, tenant puas dan bertahan.', 'mal', 3],
            'C2' => ['pelanggan', 'T1', 'Hospitality tumbuh dalam ekosistem superblok', 'Okupansi & RevPAR hotel naik; apartemen servis terisi.', 'hotel', 2],
            'C3' => ['pelanggan', 'T1', 'Perkantoran kompetitif', 'Okupansi kantor naik lewat ruang siap pakai.', 'mal', 1],
            'C4' => ['pelanggan', 'T2', 'Hunian & kota mandiri bernilai bagi pembeli', 'Marketing sales pulih; serah terima tepat waktu.', 'properti', 2],
            'P1' => ['proses', 'T1', 'Kurasi tenant & aktivasi kawasan', 'Event, porsi F&B/experiential, kekosongan unit singkat.', 'mal', 2],
            'P2' => ['proses', 'T2', 'Eksekusi proyek tepat waktu & anggaran', 'Semarang, PM Phase 5, Kokas Phase 4 sesuai rencana.', 'properti', 2],
            'P3' => ['proses', 'T3', 'Operasi gedung efisien & hijau', 'Energi, sampah, dan sertifikasi bangunan hijau.', 'operasional', 1],
            'P4' => ['proses', 'T3', 'Pengelolaan modal kerja & risiko keuangan', 'Piutang sehat dan likuiditas terjaga.', 'cfo', 1],
            'P5' => ['proses', 'T2', 'Ekspansi superblok & monetisasi land bank', 'Pra-sewa proyek baru dan lahan dimonetisasi.', 'properti', 1.5],
            'L1' => ['sdm', 'T4', 'Tempat kerja terbaik industri properti', 'Pelatihan cukup, turnover rendah.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya I CARE & keterlibatan', 'Keterlibatan karyawan tinggi.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Data pelanggan & sistem terintegrasi', 'CRM & loyalitas lintas mal.', 'pemasaran', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 2], ['L1', 'P2', 2], ['L2', 'P1', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'C1', 2],
            ['P1', 'C1', 3], ['P1', 'C2', 2], ['P2', 'C4', 3], ['P2', 'P5', 2], ['P3', 'F2', 2], ['P3', 'C1', 1], ['P4', 'F3', 3], ['P5', 'F1', 2],
            ['C1', 'F1', 3], ['C2', 'F1', 2], ['C3', 'F1', 1], ['C4', 'F1', 2], ['C4', 'F3', 2],
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
            ['F1.1', 'F1', 'Pendapatan usaha', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Pendapatan usaha konsolidasian', 'Laporan keuangan konsolidasian', 6671000, 7210000, 7111000, 7650000, .975, $lt],
            ['F1.2', 'F1', 'Pendapatan pusat perbelanjaan', 'Rp juta', 'higher', 'sum', 0, true, false, 'mal', 'Sewa, service charge & pendapatan lain mal', 'Laporan segmen', 3447000, 3900000, 3930000, 4250000, .985, $lt],
            ['F1.3', 'F1', 'Porsi pendapatan berulang', '%', 'higher', 'average', 0, true, true, 'cfo', '(Pendapatan mal + kantor sewa + hotel + apartemen servis) ÷ pendapatan × 100', 'Laporan segmen', 77, 78, 79, 80, .995, $lt],
            ['F2.1', 'F2', 'EBITDA', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba sebelum bunga, pajak, depresiasi & amortisasi', 'Laporan laba rugi', 3584000, 3750000, 3708000, 3950000, .97, $lt],
            ['F2.2', 'F2', 'Laba bersih diatribusikan ke pemilik', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan bagian pemilik entitas induk', 'Laporan laba rugi', 2075000, 2250000, 2346000, 2500000, 1.0, $lt],
            ['F2.3', 'F2', 'Marjin laba kotor', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba bruto ÷ pendapatan × 100', 'Laporan laba rugi', 56.5, 56.0, 55.5, 56.0, .985, $lt],
            ['F2.4', 'F2', 'Marjin EBITDA', '%', 'higher', 'average', 1, true, false, 'cfo', 'EBITDA ÷ pendapatan × 100', 'Rasio keuangan', 53.7, 52.0, 52.1, 52.5, .99, $lt],
            ['F3.1', 'F3', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Laba tahun berjalan ÷ total ekuitas × 100 (disetahunkan)', 'Rasio keuangan', 9.79, 10.00, 10.36, 10.50, .98, $lt],
            ['F3.2', 'F3', 'Rasio liabilitas terhadap ekuitas (DER)', 'kali', 'lower', 'last', 2, true, false, 'cfo', 'Total liabilitas ÷ total ekuitas', 'Rasio keuangan', 0.26, 0.24, 0.21, 0.22, 1.0, $lt],
            ['F3.3', 'F3', 'Kas bersih dari aktivitas operasi', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas operasi', 'Laporan arus kas', 3354000, 3300000, 3029000, 3300000, .93, $lt],
            ['F3.4', 'F3', 'Pokok obligasi USD beredar', 'US$ juta', 'lower', 'last', 1, true, false, 'cfo', 'Pokok Senior Notes 2028 yang masih beredar', 'Treasury', 400.0, 350.0, 333.4, 300.0, 1.04, $lt],
            ['C1.1', 'C1', 'Okupansi pusat perbelanjaan', '%', 'higher', 'average', 0, false, true, 'mal', 'NLA tersewa ÷ NLA total × 100 (11 mal)', 'Sistem leasing', 93, 94, 94, 95, 1.0, $lt],
            ['C1.2', 'C1', 'Kunjungan pengunjung mal', 'ribu', 'higher', 'sum', 0, false, false, 'pemasaran', 'Hitungan people counter seluruh mal', 'People counter', 128000, 140000, 142500, 150000, .98, $il],
            ['C1.3', 'C1', 'Retensi tenant saat jatuh tempo sewa', '%', 'higher', 'average', 0, false, false, 'mal', 'Tenant memperpanjang ÷ tenant jatuh tempo × 100', 'Sistem leasing', 88, 90, 89, 91, .97, $il],
            ['C1.4', 'C1', 'Indeks kepuasan tenant', 'skor', 'higher', 'last', 0, false, false, 'mal', 'Survei kepuasan tenant (skala 100)', 'Survei tenant', 80, 82, 83, 84, 1.0, $il, 'semesteran'],
            ['C2.1', 'C2', 'Okupansi hotel', '%', 'higher', 'average', 0, false, true, 'hotel', 'Kamar terjual ÷ kamar tersedia × 100 (10 hotel)', 'Night audit', 66, 68, 64, 68, .93, $lt],
            ['C2.2', 'C2', 'Pendapatan per kamar tersedia (RevPAR)', 'Rp ribu', 'higher', 'average', 0, false, false, 'hotel', 'Pendapatan kamar ÷ kamar tersedia', 'Night audit', 720, 760, 735, 790, .95, $il],
            ['C2.3', 'C2', 'Kamar hotel beroperasi', 'unit', 'higher', 'last', 0, false, false, 'hotel', 'Kamar siap jual di seluruh hotel', 'Data operasional', 2266, 2621, 2621, 2621, 1.0, $lt],
            ['C2.4', 'C2', 'Okupansi apartemen servis', '%', 'higher', 'average', 0, false, false, 'hotel', 'Unit terisi ÷ unit tersedia × 100 (286 unit)', 'Night audit', 62, 65, 60, 65, .95, $lt],
            ['C3.1', 'C3', 'Okupansi perkantoran', '%', 'higher', 'average', 0, false, true, 'mal', 'Luas tersewa ÷ luas sewa total × 100 (6 gedung)', 'Sistem leasing', 72, 76, 74, 77, .97, $lt],
            ['C3.2', 'C3', 'Luas kantor tersewa baru', 'm²', 'higher', 'sum', 0, false, false, 'mal', 'Luas sewa baru ditandatangani', 'Sistem leasing', 9000, 12000, 10500, 13000, .8, $il],
            ['C4.1', 'C4', 'Marketing sales', 'Rp juta', 'higher', 'sum', 0, true, true, 'properti', 'Nilai penjualan (pre-sales) residensial & strata', 'Sistem penjualan', 1548000, 1600000, 1300000, 1450000, .78, $lt],
            ['C4.2', 'C4', 'Serah terima unit tepat waktu', '%', 'higher', 'last', 0, false, false, 'properti', 'Unit diserahkan sesuai PPJB ÷ unit jatuh tempo × 100', 'Customer care', 92, 95, 93, 96, .97, $il],
            ['C4.3', 'C4', 'Unit rumah & kavling terjual (township)', 'unit', 'higher', 'sum', 0, false, false, 'properti', 'Unit terjual di Pakuwon City & Grand Pakuwon', 'Sistem penjualan', 420, 450, 380, 430, .85, $il],
            ['P1.1', 'P1', 'Aktivasi kawasan (event)', 'event', 'higher', 'sum', 0, false, false, 'pemasaran', 'Event & aktivasi terselenggara di seluruh mal', 'Kalender event', 260, 300, 312, 330, 1.0, $il],
            ['P1.2', 'P1', 'Porsi area F&B & experiential', '%', 'higher', 'last', 0, false, false, 'mal', 'NLA F&B + hiburan + layanan ÷ NLA tersewa × 100', 'Sistem leasing', 38, 40, 41, 43, .99, $il],
            ['P1.3', 'P1', 'Rata-rata kekosongan unit ritel', 'hari', 'lower', 'average', 0, false, false, 'mal', 'Hari dari unit kosong sampai tenant baru buka', 'Sistem leasing', 95, 90, 92, 85, 1.05, $il],
            ['P2.1', 'P2', 'Progres fisik Pakuwon Mall Semarang', '%', 'higher', 'last', 0, false, true, 'properti', 'Progres fisik tertimbang (S-curve)', 'Laporan konsultan MK', 0, 5, 5, 30, .72, $il],
            ['P2.2', 'P2', 'Proyek sesuai jadwal & anggaran', '%', 'higher', 'last', 0, false, false, 'properti', 'Proyek on-time & on-budget ÷ proyek aktif × 100', 'Laporan proyek', 80, 85, 82, 88, .9, $il],
            ['P2.3', 'P2', 'Deviasi biaya proyek', '%', 'lower', 'last', 1, false, false, 'properti', '(Estimasi biaya akhir − anggaran) ÷ anggaran × 100', 'Laporan proyek', 4.0, 3.0, 3.5, 3.0, 1.1, $il],
            ['P3.1', 'P3', 'Intensitas energi pusat perbelanjaan', 'rasio', 'lower', 'average', 0, false, false, 'operasional', 'kWh per m² NLA per tahun (disetahunkan)', 'Meter energi', 245, 240, 238, 232, 1.01, $il],
            ['P3.2', 'P3', 'Luas bangunan bersertifikat green building', 'm²', 'higher', 'last', 0, false, false, 'operasional', 'Luas tersertifikasi (Gandaria City, Pakuwon Mall 2, 4 hotel)', 'Sertifikat', 380000, 600000, 609300, 700000, .9, $lt],
            ['P3.3', 'P3', 'Sampah diolah menjadi RDF/didaur ulang', '%', 'higher', 'average', 0, false, false, 'operasional', 'Sampah diolah ÷ sampah dihasilkan × 100', 'Manifes sampah', 20, 30, 32, 40, .92, $il],
            ['P4.1', 'P4', 'Piutang usaha > 90 hari', '%', 'lower', 'last', 1, true, false, 'cfo', 'Piutang > 90 hari ÷ piutang usaha × 100', 'Umur piutang', 6.0, 5.5, 5.8, 5.0, 1.08, $il],
            ['P4.2', 'P4', 'Rasio lancar', 'kali', 'higher', 'last', 2, true, false, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 4.77, 4.00, 3.52, 3.50, 1.0, $lt],
            ['P5.1', 'P5', 'Lahan dimonetisasi (dikembangkan/dijual)', 'unit', 'higher', 'sum', 0, false, false, 'properti', 'Hektar land bank mulai dikembangkan atau dijual', 'Data land bank', 18, 20, 16, 22, .85, $il],
            ['P5.2', 'P5', 'Pra-sewa (pre-leasing) Pakuwon Mall Semarang', '%', 'higher', 'last', 0, false, false, 'mal', 'NLA dengan LOI/kontrak ÷ NLA rencana × 100', 'Sistem leasing', 0, 10, 12, 35, .8, $il],
            ['L1.1', 'L1', 'Rata-rata jam pelatihan per karyawan', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'HRIS', 36.99, 30.0, 21.14, 30.0, .85, $lt],
            ['L1.2', 'L1', 'Tingkat perputaran karyawan', '%', 'lower', 'sum', 1, false, true, 'sdm', 'Karyawan keluar ÷ rata-rata karyawan × 100', 'HRIS', 10.0, 10.0, 13.0, 11.0, 1.05, $lt],
            ['L1.3', 'L1', 'Karyawan yang mendapat pelatihan', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dilatih ÷ jumlah karyawan × 100', 'HRIS', 60, 75, 80, 85, .97, $lt],
            ['L2.1', 'L2', 'Indeks keterlibatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterlibatan (skala 100)', 'Survei karyawan', 72, 75, 74, 77, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan lulus modul nilai I CARE & kode etik', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan lulus modul ÷ jumlah karyawan × 100', 'LMS', 70, 90, 86, 95, .95, $il],
            ['L3.1', 'L3', 'Mal terintegrasi platform CRM', '%', 'higher', 'last', 0, false, false, 'pemasaran', 'Mal terhubung ke CRM & aplikasi loyalitas ÷ 11 mal × 100', 'Proyek CRM', 45, 60, 55, 80, .75, $il],
            ['L3.2', 'L3', 'Anggota aplikasi loyalitas aktif', 'ribu', 'higher', 'last', 0, false, false, 'pemasaran', 'Anggota bertransaksi dalam 90 hari terakhir', 'Aplikasi loyalitas', 650, 800, 760, 950, .9, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat, Pakuwon City Surabaya';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, general manager mal & hotel, Perencanaan Strategis',
                'summary' => "Pendapatan Rp7,11 T (+7%; 98,6% target Rp7,21 T); mal Rp3,93 T (+14%).\nEBITDA Rp3,71 T; laba atribusi Rp2,35 T (+13,1%); ±79% pendapatan berulang.\nMarketing sales Rp1,30 T (−16%; 81% target); okupansi kantor 74% di bawah target.\nTiga hotel baru dibuka; okupansi hotel 64% tertekan pembatasan kegiatan pemerintah semester I.\nJam pelatihan rata-rata turun ke 21 jam; turnover 13%.",
                'decisions' => "1. Target 2026: pendapatan Rp7,65 T, EBITDA Rp3,95 T, marketing sales Rp1,45 T.\n2. Prioritas: Semarang sesuai jadwal & pra-sewa 35%, kantor siap pakai, hotel segmen korporasi.\n3. Program pemulihan SDM: jam pelatihan kembali ≥ 30 jam.",
                'follow_up' => 'Perencanaan Strategis menurunkan KPI & target 2026 ke setiap mal, hotel, dan proyek di Ruci BSC paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta laporan bulanan terpisah untuk bisnis berulang dan bisnis pengembangan, karena keduanya punya siklus dan risiko yang berbeda.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, GM mal & hotel, kepala proyek',
                'summary' => match ($m) {
                    3 => 'Ramadan–Lebaran (20 Maret) mendorong kunjungan mal +12% yoy; okupansi hotel turun saat libur Lebaran.',
                    5 => 'Pakuwon Mall Semarang: perbaikan tanah membutuhkan tambahan 6 minggu.',
                    6 => 'Semester I: pendapatan 98% target; marketing sales 76% target.',
                    7 => 'Pra-sewa Semarang 18% (target 25%); dua tenant jangkar masih negosiasi.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Paket staycation pasca-Lebaran di hotel Surabaya & Bekasi.',
                    5 => 'Percepat pekerjaan struktur paralel; minta konsultan MK menyusun S-curve baru.',
                    6 => 'Luncurkan skema cicilan bertahap untuk stok siap huni Grand Pakuwon.',
                    7 => 'Tawarkan insentif fit-out untuk tenant jangkar Semarang.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Perencanaan Strategis',
                'summary' => $q === 1 ? 'Pendapatan Q1 99% target; okupansi mal 95%; marketing sales 74% target.' : 'Pendapatan H1 98% target; okupansi kantor 75%; progres fisik Semarang 11% vs rencana 15%.',
                'decisions' => $q === 1 ? 'Show unit kantor siap pakai di Kokas & Pakuwon Tower.' : 'Laporan S-curve mingguan Semarang ke Direksi.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Investasi & Manajemen Risiko',
            'summary' => 'Komisaris menyoroti jadwal & pra-sewa Semarang, pemulihan marketing sales, dan eksposur obligasi USD.',
            'decisions' => 'Direksi melaporkan progres fisik dan pra-sewa Semarang setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon skenario bila tenant jangkar Semarang mundur: dampak ke tanggal pembukaan, okupansi tahun pertama, dan imbal hasil proyek.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Presiden Direktur', 'venue' => $venue, 'participants' => 'Direksi, GM mal & hotel, kepala proyek',
            'summary' => 'Realisasi September sebagian masih draf; okupansi hotel dikembalikan untuk koreksi pembagi kamar.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; arus kas investasi 2025 tercatat Rp4,98 T di laporan tahunan.';

            return [
                'defs' => [
                    'hotel' => ['PWON-25-01', 'Pembukaan tiga hotel baru', 'Fairfield Bekasi (April), Aloft Surabaya Pakuwon City (Agustus), Four Points Bekasi (September).', 'T1', 'hotel', '2025-01-01', '2025-09-30', 'selesai', 4, 4, 3, 5, ['C2', 'F1'], 'Kamar beroperasi 2.621.'],
                    'destinasi' => ['PWON-25-02', 'Park Shanghai & revitalisasi Food Festival', 'Destinasi kuliner semi-outdoor di Pakuwon City Mall.', 'T1', 'mal', '2025-02-01', '2025-10-31', 'selesai', 4, 4, 2, 3, ['C1', 'P1'], 'Traffic & dwell time naik.'],
                    'semarang' => ['PWON-25-03', 'Groundbreaking Pakuwon Mall Semarang', 'Desain, perizinan, kontraktor, dan groundbreaking Agustus 2025.', 'T2', 'properti', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 4, 5, ['P2', 'P5'], 'Konstruksi dimulai.'],
                    'obligasi' => ['PWON-25-04', 'Tender offer obligasi USD & natural hedging', 'Pembelian kembali US$66,6 juta pada harga 97,05%; pembelian USD & reksa dana.', 'T3', 'cfo', '2025-04-01', '2025-06-30', 'selesai', 4, 4, 2, 1, ['F3', 'P4'], 'Pokok obligasi US$333,4 juta.'],
                    'hijau' => ['PWON-25-05', 'Sertifikasi green building & panel surya', 'Sertifikasi 6 properti; panel surya Four Points Kuta.', 'T3', 'operasional', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 2, ['P3'], '609 ribu m² bersertifikat.'],
                ],
                'activities' => [
                    ['hotel', 'Pembukaan Fairfield Bekasi', 'hotel', '2025-01-01', '2025-04-18', 100], ['hotel', 'Pembukaan Aloft Surabaya', 'hotel', '2025-02-01', '2025-08-31', 100], ['hotel', 'Pembukaan Four Points Bekasi', 'hotel', '2025-03-01', '2025-09-30', 100],
                    ['destinasi', 'Konstruksi Park Shanghai', 'mal', '2025-02-01', '2025-08-31', 100], ['destinasi', 'Kurasi tenant F&B', 'mal', '2025-04-01', '2025-10-31', 100],
                    ['semarang', 'Desain & PBG', 'properti', '2025-01-01', '2025-07-31', 100], ['semarang', 'Groundbreaking', 'properti', '2025-08-01', '2025-08-31', 100],
                    ['obligasi', 'Tender offer obligasi', 'cfo', '2025-04-01', '2025-05-19', 100],
                    ['hijau', 'Audit & sertifikasi bangunan hijau', 'operasional', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['hotel', 'Fairfield Bekasi dibuka', '2025-04-18', true], ['semarang', 'Groundbreaking Semarang', '2025-08-31', true], ['obligasi', 'Settlement tender offer', '2025-05-19', true],
                ],
                'budgets' => [
                    ['hotel', 'Fit-out & FF&E tiga hotel', 'capex', 650_000_000_000, 628_000_000_000, $note, 'Kas internal'],
                    ['destinasi', 'Konstruksi Park Shanghai & Food Festival', 'capex', 120_000_000_000, 114_000_000_000, $note, 'Kas internal'],
                    ['semarang', 'Desain, perizinan & pekerjaan awal', 'capex', 250_000_000_000, 231_000_000_000, $note, 'Kas internal'],
                    ['obligasi', 'Biaya transaksi tender offer', 'opex', 8_000_000_000, 7_200_000_000, null],
                    ['hijau', 'Sertifikasi & panel surya', 'capex', 25_000_000_000, 22_500_000_000, $note, 'Kas internal'],
                ],
            ];
        }

        return [
            'defs' => [
                'semarang' => ['PWON-26-01', 'Konstruksi Pakuwon Mall Semarang', 'Perbaikan tanah, pondasi, struktur; target topping-off 2027.', 'T2', 'properti', '2026-01-01', '2027-12-31', 'berjalan', 5, 5, 5, 5, ['P2', 'P5'], 'Progres fisik 30% akhir 2026.'],
                'prasewa' => ['PWON-26-02', 'Pra-sewa Pakuwon Mall Semarang', 'Tenant jangkar, F&B, hiburan; insentif fit-out.', 'T2', 'mal', '2026-01-15', '2026-12-31', 'berjalan', 5, 4, 4, 2, ['P5', 'C1'], 'Pra-sewa 35% NLA.'],
                'pm5' => ['PWON-26-03', 'Ekspansi Pakuwon Mall Phase 5 & Kota Kasablanka Phase 4', 'Perluasan mal di dua superblok utama.', 'T2', 'properti', '2026-01-01', '2027-06-30', 'berjalan', 4, 3, 4, 5, ['P2', 'F1'], 'Konstruksi sesuai S-curve.'],
                'aktivasi' => ['PWON-26-04', 'Renovasi mal & kurasi tenant experiential', 'Titik aktivitas baru, perbaikan tenant mix, kalender event.', 'T1', 'mal', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 3, ['C1', 'P1'], 'Kunjungan +5%; okupansi 95%.'],
                'hotel' => ['PWON-26-05', 'Penjualan hotel segmen korporasi & leisure', 'Tim sales korporasi, paket staycation, kemitraan OTA.', 'T1', 'hotel', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C2'], 'Okupansi hotel 68%.'],
                'kantor' => ['PWON-26-06', 'Kantor siap pakai & sewa fleksibel', 'Show unit fully-fitted di Kokas, Gandaria 8, Pakuwon Tower.', 'T1', 'mal', '2026-02-01', '2026-10-31', 'berjalan', 3, 3, 2, 2, ['C3'], 'Okupansi kantor 77%.'],
                'residensial' => ['PWON-26-07', 'Program pemulihan marketing sales', 'Produk ukuran lebih kecil, skema bayar fleksibel, stok siap huni.', 'T2', 'properti', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 3, 2, ['C4'], 'Marketing sales Rp1,45 T.'],
                'crm' => ['PWON-26-08', 'Platform CRM & aplikasi loyalitas terpadu', 'Satu ID pengunjung untuk mal, hotel, dan residensial; patuh UU PDP.', 'T4', 'pemasaran', '2026-02-01', '2027-03-31', 'berjalan', 4, 3, 3, 3, ['L3', 'C1'], '80% mal terintegrasi.'],
                'akademi' => ['PWON-26-09', 'Akademi Pakuwon & program retensi', 'Kurikulum leasing, building management, hospitality; jalur karier.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 4, 2, 2, ['L1', 'L2'], 'Jam pelatihan ≥ 30; turnover ≤ 11%.'],
                'hijau' => ['PWON-26-10', 'Efisiensi energi & pengolahan sampah RDF', 'Chiller efisien, LED, panel surya, RDF di mal Jakarta.', 'T3', 'operasional', '2026-03-01', '2026-12-31', 'berjalan', 3, 3, 2, 3, ['P3'], 'Intensitas energi −3%; RDF 40%.'],
            ],
            'activities' => [
                ['semarang', 'Perbaikan tanah & pondasi', 'properti', '2026-01-01', '2026-06-30', 85], ['semarang', 'Struktur basement', 'properti', '2026-05-01', '2026-12-31', 30], ['semarang', 'Struktur atas', 'properti', '2026-10-01', '2027-09-30', 0],
                ['prasewa', 'Negosiasi tenant jangkar', 'mal', '2026-01-15', '2026-09-30', 60], ['prasewa', 'Pemasaran tenant F&B & hiburan', 'mal', '2026-03-01', '2026-12-31', 40],
                ['pm5', 'Desain & PBG PM Phase 5', 'properti', '2026-01-01', '2026-06-30', 100], ['pm5', 'Pekerjaan pondasi Kokas Phase 4', 'properti', '2026-04-01', '2026-12-31', 45],
                ['aktivasi', 'Renovasi area publik 3 mal', 'operasional', '2026-01-01', '2026-09-30', 90], ['aktivasi', 'Kalender event 2026', 'pemasaran', '2026-01-01', '2026-12-31', 75], ['aktivasi', 'Penggantian 40 unit tenant', 'mal', '2026-02-01', '2026-11-30', 70],
                ['hotel', 'Tim sales korporasi', 'hotel', '2026-01-01', '2026-03-31', 100], ['hotel', 'Paket staycation & OTA', 'hotel', '2026-02-01', '2026-12-31', 70],
                ['kantor', 'Show unit fully-fitted', 'mal', '2026-02-01', '2026-06-30', 100], ['kantor', 'Kampanye sewa fleksibel', 'mal', '2026-05-01', '2026-10-31', 70],
                ['residensial', 'Produk tipe kecil Grand Pakuwon', 'properti', '2026-01-15', '2026-06-30', 100], ['residensial', 'Skema cicilan bertahap', 'cfo', '2026-03-01', '2026-07-31', 100], ['residensial', 'Pameran & digital marketing', 'pemasaran', '2026-02-01', '2026-12-31', 65],
                ['crm', 'Pemilihan platform & kebijakan PDP', 'pemasaran', '2026-02-01', '2026-05-31', 100], ['crm', 'Integrasi 6 mal tahap 1', 'pemasaran', '2026-05-01', '2026-11-30', 45], ['crm', 'Integrasi hotel & residensial', 'pemasaran', '2026-10-01', '2027-03-31', 0],
                ['akademi', 'Kurikulum Akademi Pakuwon', 'sdm', '2026-01-15', '2026-04-30', 100], ['akademi', 'Pelatihan angkatan 1–3', 'sdm', '2026-03-01', '2026-12-31', 65],
                ['hijau', 'Penggantian chiller 2 mal', 'operasional', '2026-03-01', '2026-10-31', 70], ['hijau', 'Fasilitas RDF mal Jakarta', 'operasional', '2026-04-01', '2026-12-31', 50],
            ],
            'milestones' => [
                ['semarang', 'Pondasi selesai', '2026-06-30', false], ['prasewa', 'LOI tenant jangkar pertama', '2026-06-30', true], ['prasewa', 'LOI tenant jangkar kedua', '2026-09-30', false],
                ['pm5', 'PBG PM Phase 5 terbit', '2026-06-30', true], ['kantor', 'Show unit siap', '2026-06-30', true], ['residensial', 'Peluncuran tipe kecil', '2026-06-30', true],
                ['crm', 'Platform CRM live di mal pertama', '2026-08-31', true], ['akademi', 'Angkatan 1 lulus', '2026-06-30', true],
            ],
            'budgets' => [
                ['semarang', 'Pekerjaan tanah, pondasi & struktur', 'capex', 900_000_000_000, 520_000_000_000, null, 'Kas internal'],
                ['prasewa', 'Insentif fit-out tenant jangkar', 'capex', 60_000_000_000, 12_000_000_000, null, 'Kas internal'],
                ['pm5', 'Desain & pondasi ekspansi', 'capex', 700_000_000_000, 310_000_000_000, null, 'Kas internal'],
                ['aktivasi', 'Renovasi & aktivasi mal', 'capex', 180_000_000_000, 141_000_000_000, null, 'Kas internal'],
                ['hotel', 'Pemasaran hotel', 'opex', 15_000_000_000, 10_800_000_000, null],
                ['kantor', 'Fit-out show unit', 'capex', 20_000_000_000, 18_500_000_000, null, 'Kas internal'],
                ['residensial', 'Pemasaran residensial', 'opex', 35_000_000_000, 24_000_000_000, null],
                ['crm', 'Platform CRM & aplikasi', 'capex', 30_000_000_000, 13_500_000_000, null, 'Kas internal'],
                ['akademi', 'Program pelatihan', 'opex', 6_000_000_000, 3_900_000_000, null],
                ['hijau', 'Chiller, LED & RDF', 'capex', 45_000_000_000, 27_000_000_000, null, 'Kas internal'],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Marketing sales 19% di bawah target', 'Marketing sales Rp1,30 T vs target Rp1,60 T; pembeli menunda keputusan karena suku bunga.', 'tinjauan', 'tahunan', 'pelanggan', 'tinggi', 300000000000, 'C4.1', null, 'komisaris', '2026-02-24',
                [['Program pemulihan marketing sales 2026', 'properti', '2026-12-31', 'berjalan', 75]], null],
            ['Jam pelatihan turun 43% & turnover naik', 'Rata-rata jam pelatihan 21 jam (2024: 37 jam); turnover 13%.', 'internal', null, 'sdm', 'sedang', null, 'L1.1', null, 'sdm', '2025-12-15',
                [['Akademi Pakuwon 2026', 'sdm', '2026-12-31', 'berjalan', 70]], null],
        ] : [
            ['Progres fisik Semarang tertinggal', 'Progres 11% vs rencana 15% pada Juni karena perbaikan tanah tambahan.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P2.1', 'semarang', 'dirut', '2026-07-22',
                [['S-curve baru & pekerjaan paralel', 'properti', '2026-08-31', 'selesai', 100], ['Tambah shift pekerjaan struktur', 'properti', '2026-12-31', 'berjalan', 50]], null],
            ['Pra-sewa Semarang di bawah rencana', 'Pra-sewa 18% vs 25%; tenant jangkar kedua belum menandatangani LOI.', 'timwas', 'boc', 'pelanggan', 'kritis', null, 'P5.2', 'prasewa', 'komisaris', '2026-07-28',
                [['Insentif fit-out tenant jangkar', 'mal', '2026-09-30', 'selesai', 100], ['Skenario tanpa tenant jangkar kedua', 'strategi', '2026-10-31', 'berjalan', 60]], null],
            ['Okupansi kantor masih di bawah target', 'Okupansi 75% vs 77%; permintaan kantor besar lemah.', 'tinjauan', 'q2', 'pelanggan', 'sedang', null, 'C3.1', 'kantor', 'mal', '2026-07-22',
                [['Kampanye sewa fleksibel', 'mal', '2026-10-31', 'berjalan', 70]], null],
            ['Integrasi CRM terlambat', 'Baru 5 dari 11 mal terhubung; migrasi data member lama lambat.', 'internal', 'rm8', 'sdm', 'sedang', null, 'L3.1', 'crm', 'pemasaran', '2026-09-12',
                [['Pembersihan data member lama', 'pemasaran', '2026-11-30', 'berjalan', 40]], null],
            ['Okupansi hotel Bekasi di bawah studi kelayakan', 'Dua hotel Bekasi rata-rata 55% pada H1.', 'tinjauan', 'q2', 'pelanggan', 'sedang', null, 'C2.1', 'hotel', 'hotel', '2026-07-22',
                [['Kerja sama korporasi kawasan industri Bekasi', 'hotel', '2026-10-31', 'berjalan', 65]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Tenant jangkar Semarang', 'eksternal', 'prasewa', 'P5', 'Tenant jangkar (department store & supermarket)', 'Pembukaan mal bergantung pada tenant jangkar.', 'mal', 'tinggi', 'Daftar alternatif & insentif fit-out.'],
                ['Kontraktor struktur Semarang', 'eksternal', 'semarang', 'P2', 'Kontraktor utama', 'Kapasitas kontraktor menentukan percepatan.', 'properti', 'sedang', 'Bonus percepatan & pengawasan MK.'],
                ['Kebijakan PDP untuk CRM', 'internal', 'crm', 'L3', 'Legal & TI', 'Data member hanya boleh dipakai sesuai persetujuan.', 'pemasaran', 'sedang', 'Consent management di aplikasi.'],
                ['Perizinan ekspansi Kokas Phase 4', 'eksternal', 'pm5', 'P2', 'Pemprov DKI Jakarta', 'PBG ekspansi di kawasan padat.', 'properti', 'sedang', 'Konsultan perizinan sejak desain.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat leasing mingguan', 'rapat', 'Status negosiasi, unit kosong, dan tenant jatuh tempo per mal.', 'Mingguan', 'mal', 'melembaga'],
                ['Tinjauan kinerja hotel bersama operator', 'rapat', 'Okupansi, ADR, RevPAR, dan rencana penjualan.', 'Bulanan', 'hotel', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja bisnis berulang vs pengembangan, risiko, belanja modal.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Gerbang keputusan proyek', 'sop', 'Proyek baru wajib lolos studi kelayakan & target pra-sewa sebelum konstruksi penuh.', 'Per proyek', 'properti', 'berjalan'],
                ['Akademi Pakuwon', 'pelatihan', 'Kurikulum leasing, building management, hospitality.', 'Berkelanjutan', 'sdm', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
