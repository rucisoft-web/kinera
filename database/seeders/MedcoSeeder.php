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
 * Demo PT Medco Energi Internasional Tbk (kode MEDC) — grup energi & sumber daya alam terdiversifikasi.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (produksi migas, penjualan listrik, EBITDA, laba, leverage, K3, emisi, SDM). Angka keuangan dalam AS$ juta
 *   karena mata uang pelaporan Medco adalah dolar AS.
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Tema peta strategi mengikuti tema laporan tahunan "Nilai. Fokus. Pertumbuhan." ditambah tema keberlanjutan.
 */
class MedcoSeeder extends ShowcaseSeeder
{
    public const CODE = 'MEDC';

    public const LT = 'Laporan Tahunan 2025 PT Medco Energi Internasional Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Medco Energi Internasional Tbk', 'legal_name' => 'PT Medco Energi Internasional Tbk',
            'industry' => 'Energi & sumber daya alam (migas, ketenagalistrikan, tembaga & emas)',
            'address' => 'The Energy Building Lt. 53, SCBD Lot 11A, Jl. Jend. Sudirman Kav. 52–53', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 2995 3000', 'website' => 'https://www.medcoenergi.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham MEDC). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Produksi migas relatif rata; Februari lebih pendek; kuartal IV 2026 naik karena Senoro 2A, Bualuang & Corridor penuh. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.2, 7.5, 8.3, 8.1, 8.3, 8.2, 8.4, 8.5, 8.3, 8.6, 8.7, 8.9]),
            2026 => self::weights([1 => 8.1, 7.4, 8.2, 8.0, 8.2, 8.3, 8.5, 8.6, 8.5, 8.7, 8.7, 8.8]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['C1.2', 'C1.3', 'C2.2', 'C2.4', 'C3.1', 'C4.3', 'F1.3', 'F2.2', 'F3.3', 'L1.2', 'L2.1', 'L3.2', 'P1.2', 'P1.3', 'P2.1', 'P3.1', 'P3.3', 'P4.1'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.1', 'L1.3'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C1.2', 'Angka September masih memakai nominasi awal bulan. Mohon gunakan nominasi revisi dari pembeli gas dan lampirkan rekap per PSC (Corridor, South Sumatra, Senoro).'];
    }

    protected function criticalIn2026(): array
    {
        return ['P5.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 4, 3, 3, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Medco (contoh)', 'medc.admin', 'Admin Sistem', 'Corporate Planning', ['company-admin']],
            'dirut' => ['Chief Executive Officer (contoh)', 'medc.direktur', 'Chief Executive Officer', 'Direksi', ['pimpinan']],
            'cfo' => ['Chief Financial Officer (contoh)', 'medc.keuangan', 'Chief Financial Officer', 'Keuangan & Treasury', ['keuangan']],
            'migas' => ['Chief Operating Officer Migas (contoh)', 'medc.migas', 'Chief Operating Officer', 'Minyak & Gas', ['kepala-unit']],
            'listrik' => ['Direktur Utama Medco Power (contoh)', 'medc.power', 'Presiden Direktur Medco Power', 'Ketenagalistrikan', ['kepala-unit']],
            'hse' => ['Kepala HSE & Keberlanjutan (contoh)', 'medc.hse', 'Head of HSE & Sustainability', 'HSE & Keberlanjutan', ['kepala-unit']],
            'bisdev' => ['Kepala Pengembangan Bisnis & M&A (contoh)', 'medc.bisdev', 'Head of Business Development', 'Pengembangan Bisnis & M&A', ['kepala-unit']],
            'sdm' => ['Chief Human Capital Officer (contoh)', 'medc.sdm', 'Chief Human Capital Officer', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'ti' => ['Kepala Digital & TI (contoh)', 'medc.ti', 'Head of Digital & IT', 'Digital & Teknologi Informasi', ['kepala-unit']],
            'strategi' => ['Perencanaan Strategis & Risiko (contoh)', 'medc.strategi', 'VP Corporate Planning & ERM', 'Perencanaan Strategis & Manajemen Risiko', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'medc.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'medc.analis', 'Performance Analyst', 'Perencanaan Strategis & Manajemen Risiko', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi Perusahaan Energi Pilihan untuk investor, pemegang saham, mitra, pekerja, dan masyarakat di mana kami beroperasi.',
            'mission' => 'Untuk membangun nilai jangka panjang dengan portofolio energi berkelanjutan dan bisnis sumber daya alam.',
            'purpose' => 'Menyediakan energi yang terjangkau, andal, dan berkelanjutan sambil memberikan imbal hasil jangka panjang bagi pemegang saham dan pemangku kepentingan.',
            'tagline' => 'Nilai. Fokus. Pertumbuhan.',
            'brand_promise' => 'Operator berbiaya rendah yang andal, aman, dan bertanggung jawab terhadap lingkungan dan masyarakat.',
            'value_proposition' => 'Portofolio terdiversifikasi: migas (156 mboepd, 72% gas dengan kontrak jangka panjang), ketenagalistrikan bersih (4.371 GWh, 25% terbarukan), dan 20,9% kepemilikan di Amman Mineral (tembaga & emas). Biaya kas produksi di bawah AS$10/boe.',
            'culture_statement' => 'Nilai perusahaan: Etis, Inovatif, Terbuka, Profesional. Keselamatan kerja dan integritas (ISO 37001) sebagai izin untuk beroperasi.',
            'history' => "1980: memulai sebagai kontraktor pengeboran nasional pertama di Indonesia.\n1992: masuk ke eksplorasi & produksi di Kalimantan Timur.\n1994: tercatat di Bursa Efek Indonesia.\n1995: ekspansi ke PSC Rimau dan South Sumatra.\n2025: produksi 156 mboepd, hak partisipasi Corridor naik ke 70%, operator PSC Sakakemang, peringkat Moody's naik ke Ba3, ADR MDCOY di OTCQX.\n2026: PSC Cendramas (Malaysia) ditandatangani; MSCI ESG naik ke AAA.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Etis', 'Ethical', 'Menjalankan usaha secara adil dengan integritas tinggi dan standar etika tertinggi.', "Menolak segala bentuk suap & gratifikasi\nMelaporkan realisasi KPI apa adanya, termasuk yang merah"],
            ['Inovatif', 'Innovative', 'Selalu mencari terobosan untuk hasil atau proses yang lebih baik, lebih aman, lebih murah, dan lebih cepat.', "Mengusulkan perbaikan biaya per boe\nMencoba otomasi lapangan secara terukur"],
            ['Terbuka', 'Open', 'Mendorong komunikasi terbuka dan rasa saling percaya antara pekerja dan manajemen.', "Membahas deviasi proyek lebih awal\nBerbagi pelajaran insiden lintas aset"],
            ['Profesional', 'Professional', 'Kompeten di bidangnya, memiliki semangat juara, dan terus meningkatkan kemampuan diri.', "Menjaga sertifikasi teknis tetap berlaku\nMembimbing insinyur muda"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Operator migas berbiaya rendah', 'Kompetensi', 'Biaya kas produksi AS$8,6/boe (2025), di bawah AS$10/boe selama beberapa tahun.', 5, 5, true, 'Pertahankan lewat anggaran berbasis nol & efisiensi pengeboran.'],
            ['Portofolio gas dengan kontrak jangka panjang', 'Sumber daya fisik', '72% produksi gas; sekitar setengah volume migas dijual dengan harga tetap (EBITDA AS$404 juta).', 5, 5, true, 'Perpanjang kontrak & cari pembeli gas baru untuk Sakakemang.'],
            ['Akuisisi & integrasi aset', 'Kompetensi', 'Rekam jejak akuisisi terarah: Corridor, Sakakemang, TGI, Cendramas.', 4, 5, true, 'Playbook integrasi 100 hari & pelacakan sinergi.'],
            ['Akses permodalan beragam', 'Sumber daya keuangan', 'Obligasi global 144A, obligasi berkelanjutan rupiah, fasilitas > AS$1 miliar, ADR OTCQX.', 4, 5, false, 'Jaga peringkat kredit & profil jatuh tempo.'],
            ['Pengembangan IPP energi terbarukan', 'Kompetensi', '15 IPP gas, panas bumi, surya; O&M 2.160 MW.', 3 + $lvl, 5, false, 'Bonjol & Samosir geotermal, PLTS skala besar.'],
            ['Digital oilfield & data operasi', 'Sistem', 'Pemantauan produksi real-time belum merata di seluruh aset.', 2 + $lvl, 4, false, 'Program digital oilfield 2026–2027.'],
            ['Talenta petroteknikal', 'Kompetensi', '580+ profesional petroteknikal; 6.300+ pekerja.', 3, 4, false, 'Akademi teknis & rencana suksesi posisi kunci.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Harga komoditas 2025 melemah', '2026-01-20', 'eksternal', self::LT.' — Tinjauan Keuangan',
                'Brent rata-rata AS$69/bbl (2024: AS$81). Harga realisasi minyak turun 15% ke AS$67/bbl; gas AS$6,8/mmbtu (2024: AS$7,0).',
                'Kontrak gas harga tetap menjadi lindung nilai struktural; disiplin biaya dan belanja modal wajib.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Kinerja',
                'Produksi 156 mboepd (panduan 155–160), listrik 4.371 GWh (panduan 4.300), EBITDA AS$1.264 juta (marjin 53%), laba bersih AS$101 juta (2024: AS$367 juta) karena AMMN, harga minyak, sumur kering & penurunan nilai.',
                'Prioritas 2026: rekor produksi, pemulihan laba, deleveraging.'],
            ['Volatilitas geopolitik awal 2026', '2026-03-31', 'eksternal', self::LT.' — Prospek 2026',
                'Perkembangan di Timur Tengah sempat mendorong Brent di atas AS$100/bbl, padahal konsensus sebelumnya AS$60-an.',
                'Rencanakan skenario harga; manfaatkan kenaikan untuk deleveraging, bukan belanja ekspansif.'],
            ['Panduan 2026', '2026-02-10', 'internal', self::LT.' — Prospek 2026',
                'Produksi 165–170 mboepd; penjualan listrik ±4.550 GWh; Senoro 2A & Bualuang Fase I target Q2 2026; Sakakemang onstream 2027.',
                'Target KPI 2026 diturunkan dari panduan ini.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Prioritas swasembada energi nasional', 'Pemerintah mendorong peningkatan produksi migas & pemanfaatan gas domestik.', 'peluang', 4, 4, 'naik', 'Percepat pengembangan Sakakemang & Suban.'],
            ['politik', 'Risiko geopolitik Timur Tengah', 'Aset di Oman & Yaman terpapar ketegangan kawasan; harga minyak bergejolak.', 'ancaman', 4, 3, 'naik', 'Rencana kesinambungan bisnis & asuransi aset.'],
            ['ekonomi', 'Volatilitas harga minyak', 'Brent turun ke AS$69/bbl pada 2025 lalu melonjak > AS$100 awal 2026.', 'ancaman', 5, 5, 'naik', 'Porsi gas harga tetap, lindung nilai, uji stres arus kas.'],
            ['ekonomi', 'Pelemahan rupiah', 'Biaya domestik dalam rupiah turun bila dihitung dalam dolar; utang rupiah perlu swap.', 'peluang', 3, 4, 'stabil', 'Cross-currency swap untuk menyelaraskan liabilitas.'],
            ['sosial', 'Ekspektasi masyarakat sekitar wilayah operasi', 'Penerimaan sosial menentukan kelancaran operasi & proyek baru.', 'ancaman', 4, 3, 'stabil', 'Program sosial terukur (25 ribu penerima manfaat).'],
            ['teknologi', 'Digital oilfield & otomasi', 'Pemantauan real-time dan analitik menurunkan biaya serta waktu henti.', 'peluang', 4, 4, 'naik', 'Program digital oilfield.'],
            ['teknologi', 'Teknologi panas bumi & surya makin ekonomis', 'Biaya PLTS turun; panas bumi mendapat dukungan kebijakan.', 'peluang', 4, 4, 'naik', 'Pipeline Bonjol, Samosir, PLTS skala besar.'],
            ['lingkungan', 'Transisi energi & target net zero', 'Net zero Cakupan 1&2 pada 2050 dan Cakupan 3 pada 2060; investor menilai kinerja iklim.', 'peluang', 5, 5, 'naik', 'Pengurangan metana & flaring; pertumbuhan EBT.'],
            ['lingkungan', 'Biaya karbon & standar pengungkapan', 'Pasar karbon & standar pengungkapan iklim mengetat.', 'ancaman', 3, 4, 'naik', 'Inventaris emisi terverifikasi; CDP & MSCI.'],
            ['hukum', 'Perpanjangan & syarat PSC', 'Perpanjangan kontrak bagi hasil menentukan umur aset (mis. Bualuang hingga 2035, Blok B melampaui 2028).', 'peluang', 4, 4, 'stabil', 'Negosiasi dini dengan regulator.'],
            ['hukum', 'Ketentuan ekspor konsentrat mineral', 'Izin ekspor konsentrat AMMN diperoleh kuartal IV 2025.', 'ancaman', 4, 3, 'stabil', 'Pantau kebijakan hilirisasi.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Persaingan mendapatkan aset & blok', 'NOC dan perusahaan regional bersaing memperebutkan blok dan akuisisi di Asia Tenggara.', 4, 'Akuisisi terarah dekat infrastruktur sendiri (sinergi).'],
            ['kekuatan_pembeli', 'Pembeli gas besar & PLN', 'Pembeli terkonsentrasi (PLN, industri, pembeli negara) dengan kontrak take-or-pay.', 4, 'Kontrak jangka panjang; diversifikasi pembeli.'],
            ['kekuatan_pemasok', 'Rig, FPSO & jasa penunjang', 'Ketersediaan rig dan FPSO memengaruhi biaya dan jadwal.', 3, 'Kontrak kerangka; akuisisi FPSO Marlin.'],
            ['pendatang_baru', 'Investor EBT baru', 'Masuknya pengembang EBT asing menekan tarif listrik.', 3, 'Keunggulan O&M dan lokasi.'],
            ['produk_pengganti', 'Energi terbarukan menggantikan sebagian gas', 'Dalam jangka panjang permintaan gas untuk listrik dapat tergantikan EBT.', 3, 'Bangun portofolio EBT sendiri.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Pembeli gas domestik (PLN, industri, distribusi)', 'Migas', 'Kontrak gas jangka panjang, sebagian dengan harga tetap.', 50, 'stabil', true, 'Pasokan andal sesuai DCQ.', 'Keandalan fasilitas & pengembangan lapangan baru.'],
            ['Pembeli minyak mentah', 'Migas', 'Penjualan minyak domestik & internasional (Oman, Thailand).', 25, 'stabil', true, 'Kualitas & jadwal lifting.', 'Optimasi pemasaran kargo.'],
            ['PLN (pembeli listrik IPP)', 'Ketenagalistrikan', 'PPA jangka panjang pembangkit gas, panas bumi, surya.', 15, 'naik', true, 'Ketersediaan pembangkit tinggi.', 'Ekspansi IPP & EBT.'],
            ['Klien O&M pembangkit pihak ketiga', 'Ketenagalistrikan', 'Layanan operasi & pemeliharaan 2.160 MW.', 5, 'naik', true, 'Kinerja pembangkit & biaya.', 'Perluas klien O&M.'],
            ['Pertambangan tembaga & emas (AMMN)', 'Investasi', '20,9% kepemilikan di Amman Mineral (ekuitas, bukan pelanggan langsung).', 5, 'naik', false, 'Ramp-up smelter.', 'Pantau kontribusi laba ekuitas.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi pendapatan per segmen ilustratif untuk demo']);
        }

        foreach ([
            ['PT Medco Energi Internasional Tbk', 'Grup energi terdiversifikasi', 1, 39280, -0.2, 4.2, true, 'Operator berbiaya rendah, gas kontrak jangka panjang, portofolio migas + listrik + mineral.', 'Laba sensitif harga minyak & kinerja AMMN; leverage 2,0x.'],
            ['Perusahaan migas nasional (BUMN)', 'NOC', 2, null, null, null, false, 'Skala & akses blok terbesar di Indonesia.', 'Birokrasi & beban penugasan.'],
            ['Perusahaan migas internasional di Indonesia', 'IOC', 3, null, null, null, false, 'Teknologi laut dalam & modal besar.', 'Sebagian melepas aset di Indonesia.'],
            ['Produsen listrik swasta (IPP) nasional', 'IPP', 4, null, null, null, false, 'Portofolio pembangkit besar.', 'Bergantung pada tarif PLN.'],
            ['Perusahaan energi regional Asia Tenggara', 'Regional', 5, null, null, null, false, 'Ekspansi agresif lewat akuisisi.', 'Kurang pengalaman operasi di Indonesia.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Pendapatan AS$2.395 juta dikonversi ±Rp16.400/AS$ menjadi Rp miliar (ilustrasi); sumber '.self::LT.'.' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Kontrak bagi hasil (PSC) & perpanjangan', 'Kementerian ESDM & SKK Migas', 'UU No. 22/2001 tentang Migas & peraturan turunannya', 'Hulu migas', null, 'Persetujuan POD, WP&B, dan perpanjangan PSC.', 'Menentukan umur aset & jadwal proyek.', 'patuh', 'migas', null],
            ['Kewajiban pasokan dalam negeri (DMO) & harga gas tertentu', 'Kementerian ESDM', 'Kebijakan harga gas bumi tertentu', 'Pemasaran gas', null, 'Alokasi & harga gas untuk sektor tertentu.', 'Memengaruhi harga realisasi gas.', 'patuh', 'migas', null],
            ['Keselamatan migas & K3', 'Kementerian ESDM / Kemnaker', 'Peraturan keselamatan operasi migas & SMK3', 'K3', null, 'Standar keselamatan instalasi & pekerja.', 'TRIR & izin operasi.', 'patuh', 'hse', null],
            ['Pelaporan emisi & nilai ekonomi karbon', 'KLHK & OJK', 'Perpres No. 98/2021 & POJK 51/2017', 'Lingkungan', null, 'Inventaris emisi & pengungkapan keberlanjutan.', 'Kredibilitas target net zero.', $year === 2025 ? 'patuh' : 'sebagian', 'hse', $year === 2025 ? null : '2026-12-31'],
            ['Sistem manajemen anti-penyuapan', 'BSN / lembaga sertifikasi', 'SNI ISO 37001 & ISO 37002', 'Tata kelola', null, '11 sertifikasi ISO 37001 & sistem whistleblowing ISO 37002.', 'Integritas & kepercayaan investor.', 'patuh', 'strategi', null],
            ['Tata kelola emiten & ADR', 'OJK, BEI & OTC Markets', 'POJK tata kelola emiten; ketentuan OTCQX', 'Pasar modal', null, 'Pelaporan emiten MEDC & ADR MDCOY.', 'Akses pasar modal.', 'patuh', 'cfo', null],
            ['PPA & ketentuan EBT', 'Kementerian ESDM & PLN', 'Perpres No. 112/2022 tentang percepatan EBT', 'Ketenagalistrikan', '2022-09-13', 'Harga & skema pembelian listrik EBT.', 'Kelayakan proyek panas bumi & surya.', 'dipantau', 'listrik', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham & investor (MEDC, MDCOY)', 'internal', 'Pemilik', 5, 5, 'Dividen tumbuh, deleveraging, total imbal hasil.', 'RUPS, paparan publik, investor day.', 'Investor Relations'],
            'kreditur' => ['Pemegang obligasi, bank & lembaga pemeringkat', 'eksternal', 'Pemberi dana', 5, 5, 'Leverage terkendali, likuiditas, peringkat kredit.', 'Laporan kuartalan & pertemuan pemeringkat.', 'Treasury'],
            'regulator' => ['SKK Migas & Kementerian ESDM', 'eksternal', 'Regulator', 5, 4, 'Produksi sesuai WP&B, kepatuhan PSC.', 'Rapat koordinasi berkala.', 'Hubungan Pemerintah'],
            'pembeli' => ['Pembeli gas & PLN', 'eksternal', 'Pelanggan', 4, 5, 'Pasokan sesuai kontrak, keandalan pembangkit.', 'Forum pembeli & nominasi bulanan.', 'Pemasaran Migas'],
            'mitra' => ['Mitra PSC & joint venture', 'eksternal', 'Mitra', 4, 4, 'Eksekusi proyek & transparansi biaya.', 'Operating committee.', 'Pengembangan Bisnis'],
            'pekerja' => ['Pekerja (6.300+) & kontraktor (13.000+)', 'internal', 'SDM', 4, 5, 'Keselamatan, pengembangan, kesejahteraan.', 'Town hall, survei keterlibatan.', 'Human Capital'],
            'masyarakat' => ['Masyarakat sekitar wilayah operasi', 'eksternal', 'Masyarakat', 3, 5, 'Lapangan kerja lokal, program sosial, lingkungan terjaga.', 'Program sosial & mekanisme keluhan.', 'Hubungan Masyarakat'],
            'esg' => ['Lembaga ESG & investor berkelanjutan (MSCI, CDP)', 'eksternal', 'Penilai', 3, 4, 'Kinerja iklim & tata kelola terukur.', 'Pengungkapan keberlanjutan.', 'Keberlanjutan'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'laba' => ['Laba bersih turun 73% meski EBITDA stabil', 'Laba AS$101 juta (2024: AS$367 juta): kontribusi AMMN turun, harga minyak, sumur kering Beluga, penurunan nilai aset.', 'keuangan', 5, 5, 'cfo', ['pemegang_saham']],
            'leverage' => ['Leverage naik karena akuisisi & FPSO', 'Total utang AS$3.646 juta (2024: AS$3.300 juta); utang bersih/EBITDA 2,0x (2024: 1,8x).', 'keuangan', 4, 5, 'cfo', ['kreditur', 'pemegang_saham']],
            'proyek' => ['Eksekusi proyek tepat waktu', 'Senoro 2A, Bualuang Fase I (Q2 2026), Sakakemang (2027) menentukan rekor produksi.', 'proses', 5, 5, 'migas', ['regulator', 'mitra']],
            'harga' => ['Volatilitas harga minyak', 'Harga realisasi minyak turun 15% pada 2025, lalu bergejolak awal 2026.', 'keuangan', 4, 5, 'strategi', ['pemegang_saham']],
            'transisi' => ['Kredibilitas transisi energi', 'Target net zero 2050/2060; EBT 25% penjualan listrik.', 'pelanggan', 3, 5, 'hse', ['esg', 'pemegang_saham']],
            'integrasi' => ['Integrasi aset akuisisi', 'Corridor (70%), Sakakemang, Cendramas: sinergi harus terealisasi.', 'proses', 4, 4, 'bisdev', ['mitra']],
            'talenta' => ['Regenerasi talenta teknis', 'Kebutuhan petroteknikal untuk portofolio yang membesar dan lintas negara.', 'sdm', 3, 4, 'sdm', ['pekerja']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Biaya produksi rendah', 'AS$8,6/boe; posisi kuartil bawah biaya.', 0.14, 4],
            ['S', 'Gas berkontrak & harga tetap', 'EBITDA AS$404 juta dari kontrak gas harga tetap.', 0.12, 4],
            ['S', 'Portofolio terdiversifikasi', 'Migas, listrik, mineral; 5 negara.', 0.10, 3],
            ['S', 'Rekam jejak K3 & ESG', 'TRIR 0,22; MSCI AAA; 11 ISO 37001.', 0.08, 4],
            ['W', 'Laba sensitif aset ekuitas & penurunan nilai', 'Laba bersih AS$101 juta.', 0.10, 2],
            ['W', 'Leverage relatif tinggi', 'Utang bersih/EBITDA 2,0x.', 0.10, 2],
            ['W', 'Digitalisasi operasi belum merata', 'Pemantauan real-time terbatas di aset lama.', 0.06, 2],
            ['O', 'Prioritas swasembada energi', 'Dukungan pengembangan gas domestik.', 0.08, 3],
            ['O', 'Pertumbuhan listrik bersih', 'Panas bumi & surya makin ekonomis.', 0.08, 3],
            ['O', 'Akuisisi aset regional', 'IOC melepas aset di Asia Tenggara.', 0.06, 3],
            ['T', 'Volatilitas harga minyak', 'Brent AS$60–100+.', 0.10, 2],
            ['T', 'Risiko geopolitik & regulasi', 'Timur Tengah; kebijakan harga gas.', 0.04, 2],
            ['T', 'Tekanan iklim & biaya karbon', 'Standar pengungkapan mengetat.', 0.04, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Akuisisi terarah dekat infrastruktur sendiri', 'Gunakan biaya rendah & jaringan pipa untuk monetisasi cepat (Corridor–Sakakemang).', 5, ['integrasi', 'proyek']],
            ['SO', 'Tumbuhkan listrik bersih dari kemampuan O&M', 'Panas bumi Bonjol & Samosir, PLTS skala besar.', 4, ['transisi']],
            ['WO', 'Deleveraging dari kas produksi tambahan', 'Produksi 165–170 mboepd untuk menurunkan utang bersih/EBITDA ke ≤ 1,8x.', 5, ['leverage']],
            ['WO', 'Digital oilfield untuk aset lama', 'Pemantauan real-time & pemeliharaan prediktif.', 3, ['proyek']],
            ['ST', 'Perluas porsi gas harga tetap', 'Kontrak baru untuk gas Sakakemang & Senoro.', 4, ['harga']],
            ['WT', 'Anggaran berbasis nol & disiplin modal', 'Belanja modal hanya untuk proyek imbal hasil tinggi; uji stres harga.', 5, ['laba', 'harga']],
            ['WT', 'Akademi petroteknikal & suksesi', 'Talenta siap untuk aset lintas negara.', 3, ['talenta']],
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
            'T1' => ['Nilai: Eksekusi Disiplin & Kas', 'Operator berbiaya rendah, operasi selamat dan andal, arus kas stabil.', '#0B5C8A'],
            'T2' => ['Fokus: Alokasi Modal & Deleveraging', 'Belanja modal selektif, struktur modal sehat, imbal hasil pemegang saham.', '#7A4FA0'],
            'T3' => ['Pertumbuhan: Akuisisi Selektif & Cadangan', 'Akuisisi terarah, eksplorasi, eksekusi proyek, integrasi aset.', '#C0702A'],
            'T4' => ['Energi Berkelanjutan & Talenta', 'Dekarbonisasi, listrik bersih, penerimaan sosial, talenta dan budaya.', '#2E8B57'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Tetap menjadi operator berbiaya kas < AS$10/boe', 'operasi', 'Keunggulan biaya adalah pertahanan utama terhadap siklus harga.'],
            ['T2', 'Pendanaan akuisisi ±30% kas internal, tanpa dilusi', 'organisasi', 'Model pendanaan yang disebut laporan tahunan.'],
            ['T3', 'Akuisisi dekat infrastruktur sendiri di Sumatra & kawasan', 'pasar', 'Corridor, Sakakemang, TGI, Cendramas.'],
            ['T3', 'Gas sebagai tulang punggung transisi', 'produk', '72% produksi gas dengan kontrak jangka panjang.'],
            ['T4', 'Listrik bersih sebagai mesin pertumbuhan kedua', 'produk', 'EBT 26% kapasitas terpasang.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'EBITDA & arus kas yang tangguh terhadap siklus harga', 'EBITDA ≥ AS$1,4 miliar dengan marjin ≥ 53%.', 'cfo', 3],
            'F2' => ['keuangan', 'T1', 'Memulihkan laba bersih & efisiensi biaya', 'Laba bersih pulih; biaya kas < AS$10/boe.', 'cfo', 3],
            'F3' => ['keuangan', 'T2', 'Struktur modal sehat & imbal hasil pemegang saham', 'Utang bersih/EBITDA ≤ 1,8x; dividen tumbuh.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Pasokan energi andal bagi pembeli gas, minyak & listrik', 'Produksi sesuai panduan; komitmen pasokan terpenuhi.', 'migas', 2],
            'C2' => ['pelanggan', 'T4', 'Portofolio listrik bersih tumbuh', 'Porsi EBT naik; kapasitas IPP & O&M bertambah.', 'listrik', 2],
            'C3' => ['pelanggan', 'T2', 'Kepercayaan investor & pemberi dana', 'Kontrak harga tetap melindungi kas; komunikasi investor aktif.', 'cfo', 1.5],
            'C4' => ['pelanggan', 'T4', 'Penerimaan sosial di wilayah operasi', 'Program sosial berdampak, keluhan ditangani cepat, belanja lokal.', 'hse', 1.5],
            'P1' => ['proses', 'T1', 'Operasi selamat & andal', 'TRIR rendah, fasilitas tersedia, jam kerja aman bertambah.', 'hse', 2],
            'P2' => ['proses', 'T3', 'Cadangan bertambah & proyek tepat waktu', 'Cadangan 2P & umur cadangan naik; proyek sesuai jadwal.', 'migas', 2],
            'P3' => ['proses', 'T4', 'Dekarbonisasi operasi', 'Emisi Cakupan 1&2 & metana turun sesuai jalur net zero.', 'hse', 1.5],
            'P4' => ['proses', 'T2', 'Tata kelola, integritas & manajemen risiko', 'ISO 37001, target keberlanjutan, temuan audit ditutup.', 'strategi', 1.5],
            'P5' => ['proses', 'T3', 'Akuisisi & integrasi aset bernilai tambah', 'Produksi & sinergi dari aset akuisisi sesuai kasus bisnis.', 'bisdev', 1.5],
            'L1' => ['sdm', 'T4', 'Talenta teknis & kepemimpinan', 'Jam pelatihan, perempuan pemimpin, suksesor posisi kunci.', 'sdm', 1],
            'L2' => ['sdm', 'T4', 'Budaya Etis, Inovatif, Terbuka, Profesional', 'Keterlibatan tinggi dan kepatuhan kode etik.', 'sdm', 1],
            'L3' => ['sdm', 'T1', 'Digitalisasi & otomasi operasi', 'Pemantauan produksi real-time & sistem andal.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P2', 2], ['L2', 'P4', 3], ['L2', 'P1', 2], ['L3', 'P1', 2], ['L3', 'P2', 2],
            ['P1', 'C1', 3], ['P2', 'C1', 3], ['P3', 'C2', 2], ['P3', 'C4', 2], ['P4', 'C3', 3], ['P5', 'C1', 2], ['P5', 'F1', 2],
            ['C1', 'F1', 3], ['C2', 'F1', 2], ['C3', 'F3', 3], ['C4', 'C1', 2],
            ['F1', 'F2', 3], ['F1', 'F3', 3], ['F2', 'F3', 2],
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
            ['F1.1', 'F1', 'Pendapatan konsolidasian', 'US$ juta', 'higher', 'sum', 0, true, true, 'cfo', 'Penjualan & pendapatan usaha lainnya', 'Laporan keuangan konsolidasian', 2399, 2450, 2395, 2600, .975, $lt],
            ['F1.2', 'F1', 'EBITDA', 'US$ juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba sebelum bunga, pajak, depresiasi, deplesi & amortisasi', 'Laporan keuangan', 1272, 1300, 1264, 1400, .965, $lt],
            ['F1.3', 'F1', 'Marjin EBITDA', '%', 'higher', 'average', 1, true, false, 'cfo', 'EBITDA ÷ pendapatan × 100', 'Rasio keuangan', 53.0, 53.0, 53.0, 53.5, .99, $lt],
            ['F2.1', 'F2', 'Laba bersih diatribusikan ke pemilik', 'US$ juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan bagian pemilik entitas induk', 'Laporan laba rugi', 367, 380, 101, 250, .88, $lt],
            ['F2.2', 'F2', 'Biaya kas produksi migas', 'US$/boe', 'lower', 'average', 1, true, true, 'migas', 'Biaya kas operasi migas ÷ produksi (boe)', 'Laporan biaya operasi', 9.0, 9.0, 8.6, 9.0, .98, $lt],
            ['F2.3', 'F2', 'Beban penjualan, umum & administrasi', 'US$ juta', 'lower', 'sum', 0, true, false, 'cfo', 'Beban SG&A konsolidasian', 'Laporan laba rugi', 217, 220, 228, 225, 1.03, $lt],
            ['F3.1', 'F3', 'Utang bersih terhadap EBITDA', 'kali', 'lower', 'last', 1, true, true, 'cfo', 'Utang bersih ÷ EBITDA 12 bulan', 'Rasio keuangan', 1.8, 2.0, 2.0, 1.8, 1.06, $lt],
            ['F3.2', 'F3', 'Kas & setara kas akhir perioda', 'US$ juta', 'higher', 'last', 0, true, false, 'cfo', 'Saldo kas & setara kas', 'Laporan posisi keuangan', 697, 650, 633, 650, .97, $lt],
            ['F3.3', 'F3', 'Dividen tunai dibayarkan', 'US$ juta', 'higher', 'sum', 0, true, false, 'cfo', 'Dividen final + interim dibayar dalam tahun kalender', 'Laporan arus kas', 63, 75, 80, 90, 1.0, $lt, 'semesteran'],
            ['F3.4', 'F3', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'last', 1, true, false, 'cfo', 'Laba bersih ÷ ekuitas rata-rata × 100 (disetahunkan)', 'Rasio keuangan', 17.0, 15.0, 5.0, 10.0, .85, $lt],
            ['C1.1', 'C1', 'Produksi minyak & gas', 'mboepd', 'higher', 'average', 1, false, true, 'migas', 'Rata-rata produksi bersih bagian Perseroan (ribu boe per hari)', 'Laporan produksi harian', 152.0, 157.5, 156.0, 167.5, .985, $lt],
            ['C1.2', 'C1', 'Pemenuhan komitmen pasokan gas (DCQ)', '%', 'higher', 'average', 1, false, false, 'migas', 'Volume gas tersalur ÷ nominasi pembeli × 100', 'Rekap nominasi gas', 98.2, 99.0, 99.1, 99.0, 1.0, $il],
            ['C1.3', 'C1', 'Penjualan tenaga listrik', 'GWh', 'higher', 'sum', 0, false, true, 'listrik', 'Energi listrik terjual (IPP)', 'Berita acara transaksi PLN', 3950, 4300, 4371, 4550, .99, $lt],
            ['C2.1', 'C2', 'Porsi energi terbarukan dalam penjualan listrik', '%', 'higher', 'average', 1, false, false, 'listrik', 'GWh dari EBT ÷ total GWh × 100', 'Laporan pembangkitan', 20.0, 24.0, 25.0, 27.0, .97, $lt],
            ['C2.2', 'C2', 'Porsi kapasitas terpasang energi terbarukan', '%', 'higher', 'last', 1, false, false, 'listrik', 'MW EBT ÷ total MW terpasang × 100', 'Data aset pembangkit', 22.0, 26.0, 26.0, 28.0, .985, $lt],
            ['C2.3', 'C2', 'Kapasitas IPP beroperasi', 'MW', 'higher', 'last', 0, false, false, 'listrik', 'Kapasitas IPP berstatus COD', 'Data aset pembangkit', 912, 1011, 1011, 1060, .96, $lt],
            ['C2.4', 'C2', 'Kapasitas pembangkit di bawah layanan O&M', 'MW', 'higher', 'last', 0, false, false, 'listrik', 'MW pembangkit milik sendiri & pihak ketiga yang dioperasikan', 'Kontrak O&M', 2100, 2150, 2160, 2250, .99, $lt],
            ['C3.1', 'C3', 'EBITDA dari kontrak gas harga tetap', 'US$ juta', 'higher', 'sum', 0, true, false, 'cfo', 'EBITDA dari penjualan gas domestik berharga tetap', 'Analisis segmen', 390, 400, 404, 420, 1.0, $lt],
            ['C3.2', 'C3', 'Pertemuan investor & analis', 'event', 'higher', 'sum', 0, false, false, 'cfo', 'Pertemuan satu-satu, konferensi, paparan publik', 'Investor Relations', 180, 200, 215, 230, 1.0, $il],
            ['C4.1', 'C4', 'Penerima manfaat program sosial', 'orang', 'higher', 'sum', 0, false, false, 'hse', 'Penerima manfaat langsung program sosial', 'Laporan program sosial', 22000, 24000, 25000, 27000, .93, $lt],
            ['C4.2', 'C4', 'Keluhan masyarakat selesai ≤ 30 hari', '%', 'higher', 'average', 1, false, false, 'hse', 'Keluhan ditutup ≤ 30 hari ÷ keluhan diterima × 100', 'Mekanisme keluhan', 85.0, 90.0, 91.0, 93.0, .96, $il],
            ['C4.3', 'C4', 'Porsi belanja ke pemasok dalam negeri', '%', 'higher', 'average', 1, false, false, 'migas', 'Nilai pengadaan dari pemasok domestik ÷ total pengadaan × 100', 'Sistem pengadaan', 68.0, 70.0, 71.0, 72.0, 1.0, $il],
            ['P1.1', 'P1', 'Total Recordable Incident Rate (TRIR) konsolidasi', 'rasio', 'lower', 'average', 2, false, true, 'hse', 'Kasus tercatat × 1.000.000 ÷ jam kerja', 'Sistem pelaporan HSE', 0.26, 0.25, 0.22, 0.22, 1.12, $lt],
            ['P1.2', 'P1', 'Jam kerja aman', 'ribu', 'higher', 'sum', 0, false, false, 'hse', 'Jam kerja tanpa kecelakaan hilang waktu (ribu jam)', 'Sistem pelaporan HSE', 50000, 52000, 56000, 58000, 1.0, $lt],
            ['P1.3', 'P1', 'Keandalan fasilitas produksi', '%', 'higher', 'average', 1, false, false, 'migas', 'Waktu fasilitas beroperasi ÷ waktu terjadwal × 100', 'Sistem pemeliharaan', 96.0, 96.5, 96.8, 97.0, .99, $il],
            ['P2.1', 'P2', 'Cadangan terbukti & terduga (2P)', 'mmboe', 'higher', 'last', 0, false, true, 'migas', 'Cadangan 2P bagian Perseroan akhir perioda', 'Laporan cadangan tersertifikasi', 520, 545, 564, 580, .985, $lt],
            ['P2.2', 'P2', 'Indeks umur cadangan 2P', 'tahun', 'higher', 'last', 1, false, false, 'migas', 'Cadangan 2P ÷ produksi tahunan', 'Laporan cadangan', 10.6, 11.0, 11.4, 11.5, .99, $lt],
            ['P2.3', 'P2', 'Proyek kunci sesuai jadwal', '%', 'higher', 'last', 0, false, true, 'migas', 'Proyek on-schedule ÷ proyek kunci aktif × 100', 'Laporan proyek', 80, 85, 83, 90, .78, $il],
            ['P2.4', 'P2', 'Sumur pengembangan selesai dibor', 'unit', 'higher', 'sum', 0, false, false, 'migas', 'Sumur pengembangan mencapai total depth', 'Laporan pengeboran', 40, 46, 48, 52, .9, $il],
            ['P3.1', 'P3', 'Penurunan emisi Cakupan 1 & 2 dibanding 2019', '%', 'higher', 'last', 0, false, false, 'hse', '(Emisi 2019 − emisi tahun berjalan) ÷ emisi 2019 × 100', 'Inventaris GRK', 26, 25, 30, 32, .97, $lt],
            ['P3.2', 'P3', 'Penurunan emisi metana dibanding 2019', '%', 'higher', 'last', 0, false, false, 'hse', '(Metana 2019 − metana tahun berjalan) ÷ metana 2019 × 100', 'Inventaris GRK', 35, 30, 40, 42, .96, $lt],
            ['P3.3', 'P3', 'Intensitas emisi operasi migas', 'rasio', 'lower', 'average', 1, false, false, 'hse', 'kg CO2e per boe diproduksi', 'Inventaris GRK', 21.5, 21.0, 20.4, 19.8, 1.0, $il],
            ['P4.1', 'P4', 'Target KPI keberlanjutan Tier-1 tercapai', '%', 'higher', 'last', 0, false, false, 'strategi', 'Target Tier-1 tercapai ÷ total target Tier-1 × 100', 'Laporan keberlanjutan', 92, 95, 97, 97, .99, $lt, 'semesteran'],
            ['P4.2', 'P4', 'Unit bersertifikat ISO 37001', 'unit', 'higher', 'last', 0, false, false, 'strategi', 'Entitas/unit dengan sertifikat SMAP berlaku', 'Kepatuhan', 9, 10, 11, 12, .92, $lt],
            ['P4.3', 'P4', 'Temuan audit risiko tinggi ditutup tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan ditutup sesuai tenggat ÷ temuan jatuh tempo × 100', 'Audit internal', 85, 90, 88, 92, .9, $il],
            ['P5.1', 'P5', 'Produksi dari aset hasil akuisisi', 'mboepd', 'higher', 'average', 1, false, false, 'bisdev', 'Tambahan produksi dari hak partisipasi yang diakuisisi', 'Laporan produksi', 0.0, 8.0, 9.5, 14.0, .95, $il],
            ['P5.2', 'P5', 'Sinergi integrasi tercapai', 'US$ juta', 'higher', 'sum', 1, true, false, 'bisdev', 'Penghematan biaya & tambahan pendapatan dari integrasi', 'Pelacak sinergi', 0.0, 15.0, 12.0, 25.0, .72, $il],
            ['L1.1', 'L1', 'Jam pelatihan per pekerja', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan (124.709 jam pada 2025) ÷ jumlah pekerja', 'HRIS', 18.0, 20.0, 19.8, 22.0, .95, $lt],
            ['L1.2', 'L1', 'Perempuan di posisi kepemimpinan', '%', 'higher', 'last', 0, false, false, 'sdm', 'Perempuan di jabatan manajer ke atas ÷ total × 100', 'HRIS', 19, 20, 21, 23, .97, $lt],
            ['L1.3', 'L1', 'Posisi kunci dengan suksesor siap', '%', 'higher', 'last', 0, false, false, 'sdm', 'Posisi kunci dengan ≥ 1 suksesor ready-now ÷ total posisi kunci × 100', 'Talent review', 62, 70, 68, 75, .9, $il],
            ['L2.1', 'L2', 'Indeks keterlibatan pekerja', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterlibatan (skala 100)', 'Survei pekerja', 74, 76, 77, 79, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Pekerja menyelesaikan pelatihan etika & anti-penyuapan', '%', 'higher', 'last', 1, false, false, 'sdm', 'Pekerja lulus modul tahunan ÷ total pekerja × 100', 'LMS', 95.0, 100, 98.5, 100, .99, $il],
            ['L3.1', 'L3', 'Lapangan dengan pemantauan produksi real-time', '%', 'higher', 'last', 0, false, false, 'ti', 'Lapangan terhubung ke pusat data operasi ÷ lapangan berproduksi × 100', 'Program digital oilfield', 55, 65, 67, 80, .76, $il],
            ['L3.2', 'L3', 'Ketersediaan sistem ERP & SCADA', '%', 'higher', 'average', 2, false, false, 'ti', 'Uptime ÷ waktu layanan × 100', 'Monitoring TI', 99.30, 99.50, 99.60, 99.70, .999, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'The Energy Building, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Chief Executive Officer', 'venue' => $venue, 'participants' => 'Direksi, kepala aset, Perencanaan Strategis & ERM',
                'summary' => "Produksi 156 mboepd (dalam panduan 155–160), listrik 4.371 GWh (di atas panduan 4.300).\nEBITDA AS\$1.264 juta stabil walau Brent turun ke AS\$69/bbl; marjin 53%.\nLaba bersih AS\$101 juta (27% target): AMMN, harga minyak, sumur kering Beluga, penurunan nilai.\nUtang bersih/EBITDA 2,0x; Moody's naik ke Ba3; dividen per saham +19%.\nTRIR 0,22; emisi Cakupan 1&2 −30% dan metana −40% vs 2019.",
                'decisions' => "1. Target 2026: produksi 167,5 mboepd (tengah panduan), EBITDA AS\$1,4 miliar, utang bersih/EBITDA 1,8x.\n2. Senoro 2A & Bualuang Fase I wajib onstream Q2 2026.\n3. Program sinergi Corridor & anggaran berbasis nol dilanjutkan.",
                'follow_up' => 'Perencanaan Strategis menurunkan KPI & target 2026 ke setiap aset di Ruci BSC paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta pemisahan analisis laba: operasi inti vs. kontribusi ekuitas AMMN vs. pos non-kas, agar keputusan tidak bias oleh satu angka laba bersih.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Chief Executive Officer', 'venue' => $venue, 'participants' => 'Direksi, kepala aset migas & listrik, kepala fungsi',
                'summary' => match ($m) {
                    1 => 'Produksi Januari 163 mboepd; kontribusi penuh Corridor 70% mulai terlihat.',
                    3 => 'Brent melonjak di atas AS$100/bbl karena geopolitik; harga realisasi minyak naik, gas harga tetap tidak terpengaruh.',
                    6 => 'Senoro 2A mundur ke Agustus (kendala kompresor); Bualuang Fase I onstream sesuai jadwal.',
                    7 => 'TRIR segmen listrik naik karena 2 insiden kontraktor di proyek ELB; sinergi Corridor 62% target.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Gunakan kelebihan kas dari harga tinggi untuk pelunasan obligasi lebih awal; tidak menambah belanja modal ekspansif.',
                    6 => 'Percepat pengiriman kompresor pengganti; laporkan revisi jadwal ke SKK Migas.',
                    7 => 'Safety stand-down di seluruh proyek listrik; audit manajemen kontraktor.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Chief Executive Officer', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Perencanaan Strategis & ERM',
                'summary' => $q === 1 ? 'Produksi Q1 164 mboepd; EBITDA 98% target; biaya kas AS$8,8/boe.' : 'Produksi H1 98% target karena Senoro 2A mundur; laba bersih pulih; utang bersih/EBITDA 1,9x.',
                'decisions' => $q === 1 ? 'Tetapkan pelacak sinergi Corridor per kategori (biaya, logistik, pemasaran gas).' : 'Proyek kunci wajib laporan S-curve mingguan sampai onstream.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Manajemen Risiko',
            'summary' => 'Komisaris menyoroti keterlambatan Senoro 2A, sinergi Corridor di bawah kasus bisnis, dan disiplin penggunaan kas saat harga tinggi.',
            'decisions' => 'Direksi melaporkan realisasi sinergi dan jadwal proyek kunci setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon uji stres arus kas pada Brent AS$60 dan AS$100 untuk tinjauan Q3, termasuk dampaknya pada rencana deleveraging.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Chief Executive Officer', 'venue' => $venue, 'participants' => 'Direksi, kepala aset migas & listrik, kepala fungsi',
            'summary' => 'Realisasi September sebagian masih draf; angka pemenuhan DCQ dikembalikan untuk koreksi nominasi.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Mengacu pada belanja modal 2025 di laporan tahunan (AS$402 juta migas, AS$35 juta listrik), dikonversi ±Rp16.400/AS$; rincian per item ilustratif.';

            return [
                'defs' => [
                    'corridor' => ['MEDC-25-01', 'Akuisisi 24% PSC Corridor & integrasi', 'Penambahan hak partisipasi menjadi 70% dan integrasi operasi Suban.', 'T3', 'bisdev', '2025-01-01', '2025-12-31', 'selesai', 5, 5, 3, 5, ['P5', 'C1'], 'Hak partisipasi Corridor 70%; volume & cadangan bertambah.'],
                    'natuna' => ['MEDC-25-02', 'Terubuk & Forel onstream + FPSO Marlin', 'Pengembangan South Natuna Sea Block B dan akuisisi kendali FPSO.', 'T1', 'migas', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 4, 5, ['C1', 'P2'], 'Produksi baru & perpanjangan Blok B melampaui 2028.'],
                    'ebt' => ['MEDC-25-03', 'COD PLTP Ijen 35 MW, PLTS Bali Timur 25 MWp & ELB Batam', 'Penyelesaian proyek listrik bersih dan ekspansi gas.', 'T4', 'listrik', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 4, ['C2'], 'EBT 26% kapasitas terpasang.'],
                    'liabilitas' => ['MEDC-25-04', 'Pengelolaan liabilitas & diversifikasi pendanaan', 'Buyback obligasi AS$533 juta, obligasi global AS$400 juta, obligasi berkelanjutan Rp3,5 T.', 'T2', 'cfo', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 1, ['F3', 'C3'], 'Profil jatuh tempo lebih panjang; Moody\'s Ba3.'],
                    'iso' => ['MEDC-25-05', 'Perluasan ISO 37001 & ISO 37002', 'Sistem manajemen anti-penyuapan dan whistleblowing.', 'T2', 'strategi', '2025-02-01', '2025-11-30', 'selesai', 3, 3, 2, 1, ['P4', 'L2'], '11 sertifikasi ISO 37001.'],
                ],
                'activities' => [
                    ['corridor', 'Due diligence & penandatanganan SPA', 'bisdev', '2025-01-01', '2025-05-31', 100], ['corridor', 'Integrasi operasi & pelaporan', 'migas', '2025-06-01', '2025-12-31', 100],
                    ['natuna', 'Commissioning Terubuk & Forel', 'migas', '2025-01-01', '2025-08-31', 100], ['natuna', 'Akuisisi kendali FPSO Marlin', 'bisdev', '2025-09-01', '2025-12-31', 100],
                    ['ebt', 'COD PLTP Ijen Unit 1', 'listrik', '2025-01-01', '2025-03-31', 100], ['ebt', 'COD PLTS Bali Timur', 'listrik', '2025-03-01', '2025-09-30', 100], ['ebt', 'Ekspansi ELB Batam 39 MW', 'listrik', '2025-04-01', '2025-12-31', 100],
                    ['liabilitas', 'Tender offer & buyback obligasi', 'cfo', '2025-01-01', '2025-09-30', 100], ['liabilitas', 'Penerbitan obligasi global & rupiah', 'cfo', '2025-03-01', '2025-11-30', 100],
                    ['iso', 'Audit sertifikasi unit baru', 'strategi', '2025-02-01', '2025-11-30', 100],
                ],
                'milestones' => [
                    ['corridor', 'Hak partisipasi 70% efektif', '2025-06-30', true], ['natuna', 'Forel first oil', '2025-06-30', true], ['ebt', 'PLTS Bali Timur COD', '2025-09-30', true], ['liabilitas', 'Peringkat Moody\'s Ba3', '2025-08-31', true],
                ],
                'budgets' => [
                    ['natuna', 'Pengembangan Terubuk & Forel', 'capex', 2_300_000_000_000, 2_210_000_000_000, $note, 'Kas internal'],
                    ['corridor', 'Revamping & sumur pengembangan Suban', 'capex', 1_500_000_000_000, 1_420_000_000_000, $note, 'Kas internal & pinjaman'],
                    ['ebt', 'Penyelesaian Ijen, Bali Timur & ELB', 'capex', 600_000_000_000, 574_000_000_000, $note, 'Pembiayaan proyek'],
                    ['liabilitas', 'Biaya transaksi pendanaan', 'opex', 60_000_000_000, 55_000_000_000, null],
                    ['iso', 'Audit & sertifikasi ISO', 'opex', 3_000_000_000, 2_800_000_000, null],
                ],
            ];
        }

        return [
            'defs' => [
                'senoro' => ['MEDC-26-01', 'Senoro Fase 2A beroperasi penuh', 'Kompresi & sumur tambahan untuk menjaga pasokan LNG & gas domestik.', 'T3', 'migas', '2026-01-01', '2026-06-30', 'berjalan', 5, 5, 4, 4, ['P2', 'C1'], 'Onstream Q2 2026 sesuai panduan.'],
                'bualuang' => ['MEDC-26-02', 'Bualuang Fase I (Thailand)', 'Platform & sumur baru; lisensi diperpanjang hingga 2035.', 'T3', 'migas', '2026-01-01', '2026-06-30', 'selesai', 4, 4, 3, 4, ['P2', 'C1'], 'Onstream Q2 2026.'],
                'sakakemang' => ['MEDC-26-03', 'Pengembangan Sakakemang & Sambar', 'FEED, kontrak EPCI, dan pemanfaatan fasilitas Corridor.', 'T3', 'migas', '2026-01-01', '2027-09-30', 'berjalan', 5, 4, 4, 5, ['P2', 'P5'], 'Produksi awal Q3 2027.'],
                'cendramas' => ['MEDC-26-04', 'Integrasi PSC Cendramas (Malaysia)', 'Pengalihan operatorship dari PETRONAS & integrasi 100 hari.', 'T3', 'bisdev', '2026-03-15', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['P5'], 'Operasi aman & stabil sebagai operator.'],
                'suban' => ['MEDC-26-05', 'Sumur tambahan Suban & produksi awal West Kalabau', 'Menahan laju penurunan alami Corridor dan memonetisasi penemuan Rimau.', 'T1', 'migas', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 3, 4, ['C1', 'P2'], 'Tambahan 6 mboepd.'],
                'geotermal' => ['MEDC-26-06', 'Pipeline panas bumi Bonjol & Samosir', 'Eksplorasi permukaan, perizinan, dan studi angin Sumbawa.', 'T4', 'listrik', '2026-02-01', '2027-06-30', 'berjalan', 4, 3, 4, 3, ['C2', 'P3'], 'Siap pengeboran eksplorasi 2027.'],
                'metana' => ['MEDC-26-07', 'Program pengurangan metana & flaring', 'LDAR, penggantian pneumatik, dan pemanfaatan gas suar.', 'T4', 'hse', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['P3'], 'Metana −42% vs 2019.'],
                'digital' => ['MEDC-26-08', 'Digital oilfield', 'Pusat data operasi, sensor lapangan, pemeliharaan prediktif.', 'T1', 'ti', '2026-02-01', '2027-03-31', 'berjalan', 4, 3, 3, 3, ['L3', 'P1'], '80% lapangan dipantau real-time.'],
                'talenta' => ['MEDC-26-09', 'Akademi petroteknikal & suksesi', 'Kurikulum teknis, rotasi lintas negara, talent review.', 'T4', 'sdm', '2026-01-01', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['L1', 'L2'], '75% posisi kunci punya suksesor siap.'],
                'zbb' => ['MEDC-26-10', 'Anggaran berbasis nol & pelacakan sinergi', 'Pengendalian biaya diskresioner & sinergi Corridor.', 'T2', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 2, 1, ['F2', 'P5'], 'Biaya kas ≤ AS$9/boe; sinergi AS$25 juta.'],
            ],
            'activities' => [
                ['senoro', 'Instalasi kompresor', 'migas', '2026-01-01', '2026-05-31', 85], ['senoro', 'Pengeboran sumur tambahan', 'migas', '2026-01-15', '2026-05-31', 100], ['senoro', 'Commissioning & start-up', 'migas', '2026-05-01', '2026-06-30', 40],
                ['bualuang', 'Instalasi platform', 'migas', '2026-01-01', '2026-04-30', 100], ['bualuang', 'Pengeboran & first oil', 'migas', '2026-03-01', '2026-06-30', 100],
                ['sakakemang', 'FEED & POD', 'migas', '2026-01-01', '2026-06-30', 100], ['sakakemang', 'Tender EPCI', 'migas', '2026-05-01', '2026-10-31', 70], ['sakakemang', 'Konstruksi & tie-in Corridor', 'migas', '2026-11-01', '2027-08-31', 0],
                ['cendramas', 'Transisi operatorship', 'bisdev', '2026-03-15', '2026-07-31', 100], ['cendramas', 'Harmonisasi sistem HSE & keuangan', 'hse', '2026-06-01', '2026-12-31', 55],
                ['suban', 'Pengeboran 3 sumur Suban', 'migas', '2026-01-15', '2026-09-30', 90], ['suban', 'Fasilitas produksi awal West Kalabau', 'migas', '2026-03-01', '2026-12-31', 60],
                ['geotermal', 'Survei geosains Bonjol & Samosir', 'listrik', '2026-02-01', '2026-09-30', 80], ['geotermal', 'Perizinan & AMDAL', 'listrik', '2026-04-01', '2027-03-31', 40], ['geotermal', 'Studi angin Sumbawa', 'listrik', '2026-06-01', '2026-12-31', 50],
                ['metana', 'Program LDAR di seluruh aset', 'hse', '2026-01-15', '2026-09-30', 100], ['metana', 'Penggantian pneumatik berventilasi', 'migas', '2026-03-01', '2026-12-31', 65],
                ['digital', 'Pusat data operasi Jakarta', 'ti', '2026-02-01', '2026-07-31', 100], ['digital', 'Sensor & konektivitas 12 lapangan', 'ti', '2026-04-01', '2026-12-31', 35], ['digital', 'Model pemeliharaan prediktif', 'ti', '2026-07-01', '2027-03-31', 10],
                ['talenta', 'Talent review & peta suksesi', 'sdm', '2026-01-01', '2026-04-30', 100], ['talenta', 'Akademi petroteknikal angkatan 1', 'sdm', '2026-03-01', '2026-12-31', 70],
                ['zbb', 'Anggaran berbasis nol 2026', 'cfo', '2026-01-01', '2026-02-28', 100], ['zbb', 'Pelacak sinergi bulanan', 'cfo', '2026-02-01', '2026-12-31', 70],
            ],
            'milestones' => [
                ['senoro', 'Senoro 2A onstream', '2026-06-30', false], ['bualuang', 'Bualuang Fase I first oil', '2026-06-15', true],
                ['sakakemang', 'POD disetujui', '2026-06-30', true], ['sakakemang', 'Kontrak EPCI ditandatangani', '2026-10-31', false],
                ['cendramas', 'Operatorship efektif', '2026-07-01', true], ['suban', '3 sumur Suban onstream', '2026-09-30', false],
                ['metana', 'LDAR selesai di seluruh aset', '2026-09-30', true], ['digital', 'Pusat data operasi live', '2026-07-31', true],
                ['talenta', 'Peta suksesi disetujui Direksi', '2026-04-30', true],
            ],
            'budgets' => [
                ['senoro', 'Kompresor & sumur Senoro 2A', 'capex', 1_150_000_000_000, 1_020_000_000_000, null, 'Kas internal'],
                ['bualuang', 'Platform & sumur Bualuang', 'capex', 980_000_000_000, 960_000_000_000, null, 'Kas internal'],
                ['sakakemang', 'FEED & pra-EPCI Sakakemang', 'capex', 650_000_000_000, 310_000_000_000, null, 'Kas internal & pinjaman'],
                ['cendramas', 'Biaya transisi & integrasi', 'opex', 45_000_000_000, 28_000_000_000, null],
                ['suban', 'Pengeboran Suban & fasilitas West Kalabau', 'capex', 820_000_000_000, 640_000_000_000, null, 'Kas internal'],
                ['geotermal', 'Survei & perizinan panas bumi', 'capex', 160_000_000_000, 70_000_000_000, null, 'Kas internal'],
                ['metana', 'LDAR & penggantian peralatan', 'capex', 120_000_000_000, 82_000_000_000, null, 'Kas internal'],
                ['digital', 'Sensor, jaringan & platform data', 'capex', 140_000_000_000, 52_000_000_000, null, 'Kas internal'],
                ['talenta', 'Akademi petroteknikal', 'opex', 25_000_000_000, 16_500_000_000, null],
                ['zbb', 'Konsultan efisiensi biaya', 'opex', 8_000_000_000, 6_100_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Laba bersih jauh di bawah target meski EBITDA stabil', 'Laba AS$101 juta vs target AS$380 juta: kontribusi AMMN turun ke AS$52 juta, harga minyak, sumur kering Beluga, dan penurunan nilai aset.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F2.1', null, 'komisaris', '2026-02-24',
                [['Pemisahan pelaporan laba inti vs ekuitas vs non-kas di Ruci BSC', 'cfo', '2026-03-31', 'selesai', 100], ['Gerbang keputusan eksplorasi yang lebih ketat', 'migas', '2026-06-30', 'selesai', 100]], ['2026-07-15', 'Format laporan laba baru dipakai sejak Q1 2026.']],
            ['Data emisi aset baru belum masuk inventaris', 'Emisi Terubuk & Forel bulan awal produksi dicatat manual.', 'audit', null, 'proses', 'sedang', null, 'P3.1', 'natuna', 'hse', '2025-10-20',
                [['Integrasi meter emisi ke inventaris GRK', 'hse', '2025-12-15', 'selesai', 100]], ['2025-12-20', 'Inventaris GRK mencakup seluruh aset operasi.']],
        ] : [
            ['Senoro 2A terlambat dari panduan Q2 2026', 'Kompresor pengganti terlambat dikirim; onstream bergeser ke Agustus–September.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P2.3', 'senoro', 'dirut', '2026-07-22',
                [['Percepat pengiriman kompresor', 'migas', '2026-08-15', 'selesai', 100], ['Laporan revisi jadwal ke SKK Migas', 'migas', '2026-07-31', 'selesai', 100], ['Commissioning & start-up', 'migas', '2026-10-31', 'berjalan', 40]], null],
            ['Sinergi integrasi Corridor di bawah kasus bisnis', 'Sinergi H1 AS$4,5 juta vs rencana AS$7,5 juta; penghematan logistik belum terealisasi.', 'timwas', 'boc', 'keuangan', 'tinggi', null, 'P5.2', 'zbb', 'komisaris', '2026-07-28',
                [['Pelacak sinergi per kategori & pemilik', 'cfo', '2026-08-31', 'selesai', 100], ['Konsolidasi kontrak logistik Sumatra Selatan', 'bisdev', '2026-11-30', 'berjalan', 45]], null],
            ['Insiden kontraktor di proyek listrik', 'Dua insiden tercatat di proyek ELB Batam pada Juli; TRIR segmen listrik naik.', 'internal', 'rm7', 'proses', 'tinggi', null, 'P1.1', null, 'hse', '2026-08-12',
                [['Safety stand-down seluruh proyek listrik', 'hse', '2026-08-20', 'selesai', 100], ['Audit manajemen keselamatan kontraktor', 'hse', '2026-10-31', 'berjalan', 70]], null],
            ['Digital oilfield tertinggal', 'Baru 67% lapangan terhubung; pengadaan sensor terlambat 2 bulan.', 'tinjauan', 'q2', 'sdm', 'sedang', null, 'L3.1', 'digital', 'ti', '2026-07-22',
                [['Kontrak kerangka pemasok sensor', 'ti', '2026-09-30', 'selesai', 100], ['Pemasangan gelombang 2 (6 lapangan)', 'ti', '2026-12-31', 'berjalan', 30]], null],
            ['Keluhan masyarakat terkait kebisingan fasilitas', 'Keluhan berulang di satu desa dekat stasiun kompresor; waktu penyelesaian > 30 hari.', 'pelanggan', 'rm8', 'pelanggan', 'sedang', null, 'C4.2', null, 'hse', '2026-09-12',
                [['Peredam kebisingan & pemantauan bersama warga', 'migas', '2026-11-30', 'berjalan', 50]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan revisi jadwal Senoro 2A', 'eksternal', 'senoro', 'P2', 'SKK Migas', 'Pergeseran onstream perlu persetujuan WP&B revisi.', 'migas', 'sedang', 'Komunikasi dini & bukti mitigasi.'],
                ['Kontrak EPCI Sakakemang', 'eksternal', 'sakakemang', 'P2', 'Kontraktor EPCI', 'Jadwal 2027 bergantung pada penandatanganan EPCI.', 'migas', 'tinggi', 'Daftar pendek 3 kontraktor; insentif jadwal.'],
                ['Pembeli gas Sakakemang', 'eksternal', 'sakakemang', 'C1', 'Pembeli gas domestik', 'Kontrak penjualan menentukan keekonomian proyek.', 'migas', 'tinggi', 'Negosiasi paralel dengan 2 pembeli.'],
                ['Konektivitas lapangan terpencil', 'internal', 'digital', 'L3', 'Infrastruktur jaringan TI', 'Sensor lapangan butuh tautan satelit/radio.', 'ti', 'sedang', 'Prioritaskan lapangan produksi terbesar.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Anggaran berbasis nol', 'sop', 'Setiap pos biaya dimulai dari nol dan dipertanggungjawabkan.', 'Tahunan', 'cfo', 'melembaga'],
                ['Rapat HSE mingguan lintas aset', 'rapat', 'Pelajaran insiden & tindakan pencegahan dibagikan ke seluruh aset.', 'Mingguan', 'hse', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan alokasi modal.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Gerbang keputusan proyek (stage gate)', 'sop', 'Proyek hanya maju bila imbal hasil & risiko lolos kriteria.', 'Per proyek', 'migas', 'berjalan'],
                ['Akademi petroteknikal', 'pelatihan', 'Kurikulum teknis & rotasi lintas negara.', 'Berkelanjutan', 'sdm', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
