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
 * Demo PT Blue Bird Tbk (kode BIRD) — operator transportasi darat: taksi, sewa, shuttle, logistik.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan & Keberlanjutan 2025
 *   (pendapatan & laba per segmen taksi/non-taksi, marjin, arus kas, rasio, armada, karyawan).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: perusahaan mapan yang bertahan dari disrupsi ride-hailing — produktivitas armada,
 *   diversifikasi non-taksi, digital (MyBluebird), dan transisi kendaraan listrik yang terukur.
 */
class BlueBirdSeeder extends ShowcaseSeeder
{
    public const CODE = 'BIRD';

    public const LT = 'Laporan Tahunan 2025 PT Blue Bird Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Blue Bird Tbk', 'legal_name' => 'PT Blue Bird Tbk',
            'industry' => 'Transportasi darat: taksi, sewa mobil & bus, shuttle, logistik, mobil bekas',
            'address' => 'Jl. Mampang Prapatan Raya No. 60', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 794 4444', 'website' => 'https://www.bluebirdgroup.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham BIRD). Angka 2025 secara garis besar mengacu pada Laporan Tahunan & Keberlanjutan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Mudik Lebaran & libur sekolah menaikkan shuttle/sewa; Desember naik karena liburan akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.0, 7.6, 8.3, 8.7, 8.2, 8.4, 8.6, 8.3, 8.1, 8.4, 8.4, 9.0]),
            2026 => self::weights([1 => 8.0, 7.9, 8.9, 8.1, 8.2, 8.4, 8.6, 8.3, 8.1, 8.4, 8.4, 9.0]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.4', 'F3.1', 'F3.3', 'C1.2', 'C3.2', 'C4.1', 'P1.1', 'P1.3', 'P2.1', 'P3.1', 'P3.2', 'L1.1', 'L2.1', 'L3.1'];
    }

    protected function missingSeptember(): array
    {
        return ['C3.3', 'L1.3'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P1.2', 'Pendapatan per armada per hari September belum mengeluarkan armada yang masuk bengkel lebih dari 7 hari. Mohon hitung ulang dengan armada siap operasi.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P4.1'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 3, 2, 4];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Bluebird (contoh)', 'bird.admin', 'Admin Sistem', 'Corporate Strategy', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'bird.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'bird.keuangan', 'Direktur Keuangan', 'Keuangan', ['keuangan']],
            'taksi' => ['Direktur Operasional Taksi (contoh)', 'bird.taksi', 'Direktur Operasional', 'Operasional Taksi', ['kepala-unit']],
            'nontaksi' => ['Direktur Bisnis Non-Taksi (contoh)', 'bird.nontaksi', 'Direktur Bisnis Non-Taksi', 'Sewa, Shuttle & Logistik', ['kepala-unit']],
            'komersial' => ['Direktur Pemasaran & Komersial (contoh)', 'bird.komersial', 'Direktur Pemasaran & Komersial', 'Pemasaran & Corporate Solutions', ['kepala-unit']],
            'armada' => ['Head of Fleet & Maintenance (contoh)', 'bird.armada', 'Head of Fleet & Maintenance', 'Armada & Perawatan', ['kepala-unit']],
            'digital' => ['Chief Digital Officer (contoh)', 'bird.digital', 'Chief Digital Officer', 'Digital & Data', ['kepala-unit']],
            'esg' => ['Head of Sustainability & HSE (contoh)', 'bird.esg', 'Head of Sustainability & HSE', 'Keberlanjutan & K3', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'bird.sdm', 'Direktur Human Capital', 'Human Capital & Pengemudi', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Corporate Strategy (contoh)', 'bird.strategi', 'Head of Corporate Strategy', 'Corporate Strategy', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'bird.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'bird.analis', 'Performance Analyst', 'Corporate Strategy', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi perusahaan yang mampu bertahan dan mengedepankan kualitas untuk memastikan kesejahteraan yang berkelanjutan bagi para pemangku kepentingan.',
            'mission' => 'Tercapainya kepuasan pelanggan dan mengembangkan serta mempertahankan diri sebagai pemimpin pasar di setiap kategori yang kita masuki. Dalam transportasi darat, kita menyediakan layanan yang handal dan berkualitas tinggi dengan penggunaan setiap sumber daya yang efisien dan kita melakukannya sebagai satu tim yang utuh.',
            'purpose' => 'Menghadirkan perjalanan yang ANDAL — aman, nyaman, mudah, dan personal — sekaligus menyejahterakan pengemudi dan menjaga lingkungan.',
            'tagline' => 'Layanan ANDAL untuk setiap perjalanan',
            'brand_promise' => 'Aman, nyaman, mudah, dan personal (ANDAL) — dari taksi sampai shuttle antarkota.',
            'value_proposition' => '26.070 armada; taksi Bluebird, Silverbird & Goldenbird; sewa mobil & bus (Bigbird); Cititrans; Bluebird Kirim; BirdMobil; MyBluebird; armada listrik sejak 2019.',
            'culture_statement' => 'Nilai: Peduli, Integritas, Pelayanan Prima, Pola Pikir Berkembang.',
            'history' => "1972: Bluebird Group berdiri dengan beberapa armada taksi.\n2001: Perseroan mulai beroperasi.\n2011: aplikasi pemesanan taksi pertama.\n2014: tercatat di Bursa Efek Indonesia (BIRD).\n2019: IoT taximeter & taksi listrik e-Bluebird.\n2025: armada 26.070 unit; Cititrans +80 rute; ekspansi ke Solo; layanan bus & taksi listrik di IKN.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Peduli', 'Caring', 'Peduli pada pelanggan, rekan kerja, dan lingkungan.', "Membantu penumpang lansia & disabilitas\nMenjaga kebersihan armada"],
            ['Integritas', 'Integrity', 'Jujur dan dapat dipercaya.', "Argo sesuai, barang tertinggal dikembalikan\nMelaporkan apa adanya"],
            ['Pelayanan Prima', 'Service excellence', 'Layanan ANDAL di setiap perjalanan.', "Tepat waktu & sopan\nMenyelesaikan keluhan dalam 24 jam"],
            ['Pola Pikir Berkembang', 'Growth mindset', 'Belajar dan beradaptasi dengan perubahan.', "Mencoba layanan & teknologi baru\nBelajar dari data perjalanan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Merek tepercaya untuk keamanan', 'Kompetensi', 'Lebih dari 50 tahun; identik dengan taksi aman.', 5, 5, true, 'Pertahankan standar ANDAL.'],
            ['Pengelolaan armada skala besar', 'Sistem', '26.070 unit; perawatan, peremajaan, lelang.', 4, 5, true, 'Pemeliharaan prediktif.'],
            ['Pengemudi terlatih', 'Kompetensi', 'Pengemudi direkrut & dilatih sendiri.', 4, 5, true, 'BlueLife: kesejahteraan & perumahan.'],
            ['Kanal digital MyBluebird', 'Sistem', 'Multi-produk & multi-pembayaran.', 3 + $lvl, 5, false, 'Personalisasi & langganan korporat.'],
            ['Portofolio non-taksi', 'Kompetensi', 'Sewa, Cititrans, logistik, BirdMobil, BirdAds.', 3, 4, false, 'Target non-taksi hingga 30% pendapatan.'],
            ['Kesiapan kendaraan listrik', 'Kompetensi', 'Armada EV sejak 2019; infrastruktur pengisian terbatas.', 2 + $lvl, 4, false, 'Elektrifikasi terukur.'],
            ['Manajemen kinerja berbasis KPI', 'Sistem', 'KPI pool & pengemudi belum terhubung ke KPI korporat.', 2 + $lvl, 4, false, 'Cascading KPI melalui Ruci BSC.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Industri mobilitas 2025', '2026-01-20', 'eksternal', self::LT.' — Analisis & Pembahasan Manajemen',
                'Permintaan mobilitas konstruktif; persaingan tinggi dari ride-hailing dan pemain kendaraan listrik baru; biaya kendaraan & suku cadang dipengaruhi kurs.',
                'Diferensiasi layanan dan produktivitas armada menjadi kunci.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan Rp5,71 T (+13,2%); laba usaha Rp687 M (+8,4%); laba bersih Rp643 M (+8,6%). Taksi Rp3,99 T (+11,6%; laba bersih +19%); non-taksi tumbuh ±18%. Armada 26.070 unit (+7,7%); capex Rp2 T (rencana awal Rp1,5 T).',
                'Pertumbuhan sehat; marjin sedikit turun karena investasi armada & ekspansi.'],
            ['Prospek 2026', '2026-02-05', 'eksternal', self::LT.' — Prospek Usaha',
                'Prioritas: produktivitas armada & disiplin operasional, elektrifikasi terukur (unit, infrastruktur, biaya), capex selektif, likuiditas prudent; risiko kurs & biaya impor.',
                'Target 2026 dalam demo ini mengikuti arah prospek tersebut.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Insentif & regulasi kendaraan listrik', 'Insentif pajak EV; kebijakan transportasi di IKN.', 'peluang', 3, 4, 'naik', 'Elektrifikasi terukur.'],
            ['politik', 'Regulasi tarif taksi & angkutan daring', 'Batas tarif & kesetaraan aturan dengan ride-hailing.', 'ancaman', 4, 3, 'stabil', 'Dialog regulator & asosiasi.'],
            ['ekonomi', 'Kurs & harga kendaraan', 'Kendaraan & suku cadang terpapar kurs.', 'ancaman', 4, 4, 'naik', 'Capex selektif & lindung nilai.'],
            ['ekonomi', 'Pariwisata & perjalanan bisnis', 'Bandara & pariwisata pulih.', 'peluang', 4, 4, 'naik', 'Taksi bandara & sewa.'],
            ['sosial', 'Preferensi keamanan & kenyamanan', 'Penumpang keluarga & korporat mengutamakan keamanan.', 'peluang', 4, 4, 'stabil', 'Standar ANDAL.'],
            ['sosial', 'Kesejahteraan pengemudi', 'Pengemudi membandingkan pendapatan dengan platform lain.', 'ancaman', 4, 4, 'naik', 'BlueLife & program rumah.'],
            ['teknologi', 'Aplikasi ride-hailing', 'Pemesanan daring mendominasi.', 'ancaman', 5, 5, 'stabil', 'MyBluebird & kemitraan platform.'],
            ['teknologi', 'IoT & telematika armada', 'Data perjalanan untuk efisiensi & keselamatan.', 'peluang', 3, 4, 'naik', 'IoT taximeter.'],
            ['lingkungan', 'Emisi transportasi perkotaan', 'Target pengurangan emisi & limbah 50% pada 2030.', 'ancaman', 3, 4, 'naik', 'BlueSky: EV & PLTS pool.'],
            ['hukum', 'Pelindungan data penumpang', 'UU PDP berlaku penuh.', 'ancaman', 3, 3, 'naik', 'Tata kelola data MyBluebird.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Ride-hailing & taksi listrik baru', 'Harga promosi & armada baru.', 5, 'Keamanan, kualitas, dan korporat.'],
            ['kekuatan_pembeli', 'Penumpang & klien korporat', 'Mudah membandingkan harga di aplikasi.', 4, 'Langganan korporat & loyalitas.'],
            ['kekuatan_pemasok', 'Produsen kendaraan & pemasok BBM', 'Harga kendaraan & BBM menentukan biaya.', 3, 'Kontrak volume & EV.'],
            ['pendatang_baru', 'Operator taksi listrik', 'Pemain baru dengan armada EV.', 4, 'Elektrifikasi & merek tepercaya.'],
            ['produk_pengganti', 'Transportasi publik & kendaraan pribadi', 'MRT, LRT, KRL, motor pribadi.', 3, 'Layanan first/last mile & bandara.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Taksi reguler & eksekutif', 'Taksi', 'Pendapatan Rp3,99 T (+11,6%).', 70, 'naik', true, 'Aman, bersih, tepat waktu.', 'Produktivitas armada & digital.'],
            ['Sewa mobil, bus & corporate solutions', 'Non-taksi', 'Klien korporat & acara.', 15, 'naik', true, 'Kendaraan & pengemudi andal.', 'Kontrak jangka panjang.'],
            ['Shuttle antarkota (Cititrans)', 'Non-taksi', '+80 rute pada 2025.', 8, 'naik', true, 'Jadwal tepat & nyaman.', 'Perluas rute.'],
            ['Logistik, mobil bekas & iklan', 'Non-taksi', 'Bluebird Kirim, BirdMobil, BirdAds.', 7, 'naik', true, 'Cepat & tepercaya.', 'Monetisasi aset armada.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — informasi segmen (porsi non-taksi ilustratif)']);
        }

        foreach ([
            ['PT Blue Bird Tbk', 'Operator transportasi', 1, 5705, 13.2, 11.3, true, 'Merek tepercaya, armada & pengemudi sendiri.', 'Ketergantungan pada taksi; biaya armada tinggi.'],
            ['Platform ride-hailing', 'Platform', 2, null, null, null, false, 'Skala aplikasi & harga.', 'Kualitas pengemudi bervariasi.'],
            ['Operator taksi listrik baru', 'Taksi', 3, null, null, null, false, 'Armada EV baru.', 'Jaringan & merek baru.'],
            ['Operator taksi lokal', 'Taksi', 4, null, null, null, false, 'Harga & kedekatan lokal.', 'Teknologi terbatas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Penyelenggaraan angkutan orang (taksi & sewa)', 'Kementerian Perhubungan & Pemda', 'Permenhub tentang angkutan orang tidak dalam trayek', 'Operasi', null, 'Izin operasi, tarif, kuota armada.', 'Kelangsungan usaha.', 'patuh', 'taksi', null],
            ['Angkutan antarkota (AKAP)', 'Kementerian Perhubungan', 'Ketentuan angkutan AKAP', 'Operasi', null, 'Izin trayek Cititrans Busline.', 'Ekspansi rute.', 'patuh', 'nontaksi', null],
            ['Uji berkala kendaraan (KIR)', 'Dinas Perhubungan', 'Ketentuan uji berkala', 'Keselamatan', null, 'Seluruh armada wajib lulus uji.', 'Keselamatan & izin.', 'patuh', 'armada', null],
            ['Insentif kendaraan listrik', 'Kementerian Keuangan & Perindustrian', 'PMK insentif PPN & PPnBM EV', 'Investasi', null, 'Syarat TKDN untuk insentif.', 'Biaya armada EV.', 'sebagian', 'esg', $year === 2025 ? null : '2026-12-31'],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data penumpang MyBluebird.', 'Sanksi & reputasi.', 'sebagian', 'digital', '2026-12-31'],
            ['Pengungkapan keberlanjutan', 'OJK', 'POJK No. 51/2017', 'Tata kelola', null, 'Laporan keberlanjutan & target 2030.', 'Kepercayaan investor.', 'patuh', 'esg', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham pendiri & publik', 'internal', 'Pemilik', 5, 5, 'Pertumbuhan laba & dividen.', 'RUPS & paparan publik.', 'Investor Relations'],
            'penumpang' => ['Penumpang ritel & wisatawan', 'eksternal', 'Pelanggan', 3, 5, 'Aman, nyaman, mudah.', 'MyBluebird & survei.', 'Customer Experience'],
            'korporat' => ['Klien korporat & instansi', 'eksternal', 'Pelanggan', 4, 4, 'Layanan andal & tagihan rapi.', 'Account management.', 'Corporate Solutions'],
            'pengemudi' => ['Pengemudi (mitra & karyawan)', 'internal', 'SDM', 4, 5, 'Pendapatan layak, rumah, keselamatan.', 'Forum pengemudi & BlueLife.', 'Human Capital'],
            'regulator' => ['Kemenhub, Dishub & Pemda', 'eksternal', 'Regulator', 5, 3, 'Kepatuhan & keselamatan.', 'Koordinasi rutin.', 'Corporate Affairs'],
            'mitra' => ['Pengelola bandara, mal, hotel & platform mitra', 'eksternal', 'Mitra', 3, 4, 'Antrean & layanan tertib.', 'Kerja sama lokasi.', 'Komersial'],
            'atpm' => ['Produsen kendaraan & penyedia pengisian daya', 'eksternal', 'Pemasok', 3, 4, 'Volume pembelian & kemitraan EV.', 'Kontrak multi-tahun.', 'Fleet'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'persaingan' => ['Persaingan ride-hailing & taksi listrik', 'Harga promosi pesaing menekan permintaan pada jam tertentu.', 'pelanggan', 5, 5, 'komersial', ['penumpang']],
            'marjin' => ['Marjin turun meski pendapatan naik', 'Marjin usaha 12,0% (12,6%); beban usaha naik 13%.', 'keuangan', 4, 4, 'cfo', ['pemegang_saham']],
            'capex' => ['Capex & leverage naik', 'Capex Rp2 T vs rencana Rp1,5 T; DER 52,6% (40,8%).', 'keuangan', 4, 4, 'cfo', ['pemegang_saham']],
            'ev' => ['Elektrifikasi armada', 'Infrastruktur pengisian & biaya EV belum ideal.', 'proses', 4, 4, 'esg', ['atpm', 'regulator']],
            'pengemudi' => ['Retensi & kesejahteraan pengemudi', 'Pengemudi berpindah ke platform lain.', 'sdm', 4, 5, 'sdm', ['pengemudi']],
            'digital' => ['Pemesanan digital belum dominan', 'Sebagian perjalanan masih dari pangkalan & jalan.', 'pelanggan', 3, 4, 'digital', ['penumpang']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Merek tepercaya & keamanan', 'Lebih dari 50 tahun.', 0.15, 4],
            ['S', 'Armada & pengemudi sendiri', 'Standar layanan konsisten.', 0.12, 4],
            ['S', 'Neraca sehat', 'Rasio lancar 1,55x; arus kas operasi Rp1,26 T.', 0.08, 3],
            ['S', 'Portofolio non-taksi tumbuh', 'Sewa, shuttle, logistik.', 0.06, 3],
            ['W', 'Biaya armada tinggi', 'Capex Rp2 T; depresiasi besar.', 0.10, 2],
            ['W', 'Kanal digital belum dominan', 'MyBluebird kalah populer dari platform besar.', 0.08, 2],
            ['W', 'Marjin turun', 'Marjin usaha 12,0%.', 0.05, 2],
            ['O', 'Pariwisata & perjalanan bisnis', 'Bandara & hotel.', 0.08, 3],
            ['O', 'Kota baru (IKN, Solo)', 'Ekspansi wilayah.', 0.05, 3],
            ['O', 'EV & insentif', 'Biaya energi lebih rendah.', 0.05, 2],
            ['T', 'Ride-hailing & taksi listrik baru', 'Persaingan harga.', 0.10, 2],
            ['T', 'Kurs & harga kendaraan', 'Capex naik.', 0.05, 2],
            ['T', 'Regulasi tarif', 'Ketidaksetaraan aturan.', 0.03, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Taksi bandara, hotel & korporat premium', 'Merek tepercaya untuk segmen yang menghargai keamanan.', 4, ['persaingan']],
            ['SO', 'Ekspansi wilayah & Cititrans', 'Kota baru & rute antarkota.', 3, ['marjin']],
            ['WO', 'MyBluebird & kemitraan platform', 'Tingkatkan pemesanan digital.', 4, ['digital', 'persaingan']],
            ['WO', 'Elektrifikasi terukur', 'EV di rute & kota dengan infrastruktur siap.', 3, ['ev']],
            ['ST', 'Produktivitas armada', 'Utilisasi & pendapatan per armada naik untuk melawan harga murah pesaing.', 5, ['persaingan', 'marjin']],
            ['WT', 'Capex selektif & BlueLife', 'Disiplin modal; retensi pengemudi lewat kesejahteraan.', 4, ['capex', 'pengemudi']],
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
            'T1' => ['Layanan ANDAL & Digital', 'Aman, nyaman, mudah, personal; MyBluebird.', '#0057A8'],
            'T2' => ['Produktivitas Armada & Disiplin Operasi', 'Utilisasi, biaya per km, perawatan, capex selektif.', '#B22234'],
            'T3' => ['Diversifikasi Non-Taksi & Wilayah', 'Sewa, Cititrans, logistik, BirdMobil, kota baru.', '#E08A1E'],
            'T4' => ['BlueSky, BlueLife, BlueCorps', 'Elektrifikasi, kesejahteraan pengemudi, tata kelola.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Bersaing lewat keamanan & kualitas, bukan harga termurah', 'produk', 'Diferensiasi layanan dalam prospek 2026.'],
            ['T2', 'Capex selektif berdasarkan produktivitas pool', 'operasi', 'Capex 2025 melampaui rencana.'],
            ['T3', 'Non-taksi hingga 30% pendapatan', 'organisasi', 'Target manajemen.'],
            ['T4', 'EV hanya di lokasi dengan infrastruktur siap', 'operasi', 'Elektrifikasi terukur (unit, infrastruktur, biaya).'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T3', 'Pendapatan tumbuh dua digit', 'Pendapatan Rp6,3 T; non-taksi ±31%.', 'dirut', 2],
            'F2' => ['keuangan', 'T2', 'Laba & marjin terjaga', 'Laba ke pemilik Rp700 M; marjin usaha 12,5%.', 'cfo', 3],
            'F3' => ['keuangan', 'T2', 'Arus kas & struktur modal sehat', 'Arus kas operasi naik; DER terkendali.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T1', 'Penumpang puas & setia', 'Perjalanan naik; keluhan turun.', 'taksi', 2],
            'C2' => ['pelanggan', 'T1', 'Pemesanan digital', 'Porsi MyBluebird & mitra digital naik.', 'digital', 1.5],
            'C3' => ['pelanggan', 'T3', 'Bisnis non-taksi tumbuh', 'Cititrans, logistik, BirdMobil.', 'nontaksi', 1.5],
            'C4' => ['pelanggan', 'T3', 'Korporat & wilayah baru', 'Corporate solutions & kota ekspansi.', 'komersial', 1],
            'P1' => ['proses', 'T2', 'Produktivitas armada', 'Utilisasi & pendapatan per armada naik.', 'taksi', 2],
            'P2' => ['proses', 'T2', 'Biaya operasi efisien', 'BBM & perawatan terkendali; downtime turun.', 'armada', 1.5],
            'P3' => ['proses', 'T1', 'Keselamatan & kelaikan armada', 'Kecelakaan turun; inspeksi lulus.', 'esg', 1.5],
            'P4' => ['proses', 'T4', 'BlueSky: elektrifikasi & emisi', 'Armada listrik, PLTS pool, emisi turun.', 'esg', 1],
            'L1' => ['sdm', 'T4', 'BlueLife: pengemudi sejahtera & kompeten', 'Pelatihan, perumahan, retensi.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya & kinerja (BlueCorps)', 'Keterikatan & KPI terhubung.', 'sdm', 1],
            'L3' => ['sdm', 'T1', 'Data & IoT armada', 'Telematika di seluruh armada; monetisasi data.', 'digital', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P3', 3], ['L1', 'C1', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'C2', 2],
            ['P1', 'F2', 3], ['P1', 'C1', 2], ['P2', 'F2', 3], ['P3', 'C1', 2], ['P4', 'P2', 1],
            ['C1', 'F1', 3], ['C2', 'C1', 2], ['C3', 'F1', 3], ['C4', 'F1', 2],
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
            ['F1.1', 'F1', 'Pendapatan neto', 'Rp juta', 'higher', 'sum', 0, true, true, 'dirut', 'Pendapatan neto konsolidasian', 'Laporan keuangan konsolidasian', 5039947, 5600000, 5705306, 6300000, .97, $lt],
            ['F1.2', 'F1', 'Pendapatan segmen taksi', 'Rp juta', 'higher', 'sum', 0, true, false, 'taksi', 'Pendapatan taksi reguler & eksekutif', 'Laporan segmen', 3577987, 3900000, 3992956, 4300000, .96, $lt],
            ['F1.3', 'F1', 'Pendapatan segmen non-taksi', 'Rp juta', 'higher', 'sum', 0, true, false, 'nontaksi', 'Pendapatan neto − pendapatan taksi', 'Laporan segmen', 1461960, 1700000, 1712350, 2000000, .95, $lt],
            ['F1.4', 'F1', 'Porsi pendapatan non-taksi', '%', 'higher', 'average', 1, true, false, 'nontaksi', 'Pendapatan non-taksi ÷ pendapatan neto × 100', 'Laporan segmen', 29.0, 30.0, 30.0, 31.0, 1.0, $lt],
            ['F2.1', 'F2', 'Laba yang dapat diatribusikan ke pemilik', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba bersih bagian pemilik entitas induk', 'Laporan laba rugi', 585195, 640000, 635837, 700000, .96, $lt],
            ['F2.2', 'F2', 'Marjin laba usaha', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba usaha ÷ pendapatan × 100', 'Rasio keuangan', 12.6, 12.8, 12.0, 12.5, .97, $lt],
            ['F2.3', 'F2', 'Marjin laba kotor', '%', 'higher', 'average', 1, true, false, 'cfo', 'Laba bruto ÷ pendapatan × 100', 'Rasio keuangan', 32.3, 32.5, 31.8, 32.0, .99, $lt],
            ['F3.1', 'F3', 'Arus kas dari aktivitas operasi', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas bersih aktivitas operasi', 'Laporan arus kas', 1058660, 1150000, 1255919, 1350000, .95, $lt],
            ['F3.2', 'F3', 'Rasio liabilitas terhadap ekuitas (DER)', '%', 'lower', 'last', 1, true, false, 'cfo', 'Total liabilitas ÷ total ekuitas × 100', 'Rasio keuangan', 40.8, 50.0, 52.6, 55.0, 1.02, $lt],
            ['F3.3', 'F3', 'Hari penagihan piutang', 'hari', 'lower', 'average', 2, true, false, 'cfo', 'Piutang usaha ÷ pendapatan × 365', 'Rasio keuangan', 17.37, 16.00, 15.21, 15.00, 1.0, $lt],
            ['C1.1', 'C1', 'Perjalanan taksi', 'juta', 'higher', 'sum', 1, false, true, 'taksi', 'Jumlah perjalanan taksi berbayar', 'Sistem taximeter', 48.0, 52.0, 51.5, 55.0, .96, $il],
            ['C1.2', 'C1', 'Kepuasan penumpang (CSAT)', '%', 'higher', 'average', 0, false, false, 'taksi', 'Perjalanan dinilai puas ÷ perjalanan dinilai × 100', 'MyBluebird & survei', 88, 90, 90, 91, .99, $il],
            ['C1.3', 'C1', 'Keluhan per 10.000 perjalanan', 'rasio', 'lower', 'average', 2, false, false, 'taksi', 'Keluhan tercatat ÷ perjalanan × 10.000', 'Customer care', 1.80, 1.50, 1.60, 1.30, 1.12, $il],
            ['C2.1', 'C2', 'Porsi pemesanan via MyBluebird & mitra digital', '%', 'higher', 'average', 0, false, false, 'digital', 'Perjalanan dipesan digital ÷ total perjalanan × 100', 'Data pemesanan', 55, 60, 62, 68, .95, $il],
            ['C2.2', 'C2', 'Pengguna aktif bulanan MyBluebird', 'ribu', 'higher', 'average', 0, false, false, 'digital', 'Pengguna unik bertransaksi per bulan', 'Data aplikasi', 1500, 1800, 1850, 2200, .9, $il],
            ['C3.1', 'C3', 'Pendapatan shuttle antarkota (Cititrans)', 'Rp juta', 'higher', 'sum', 0, true, false, 'nontaksi', 'Pendapatan Cititrans', 'Laporan anak usaha (ilustrasi)', 380000, 450000, 470000, 560000, .93, $il],
            ['C3.2', 'C3', 'Rute shuttle beroperasi', 'unit', 'higher', 'last', 0, false, false, 'nontaksi', 'Rute Cititrans aktif', 'Data operasi', 120, 190, 200, 240, .97, $il],
            ['C3.3', 'C3', 'Kendaraan terjual melalui BirdMobil & balai lelang', 'unit', 'higher', 'sum', 0, false, false, 'nontaksi', 'Unit kendaraan bekas terjual', 'Data penjualan', 4500, 5000, 5100, 5800, .95, $il],
            ['C4.1', 'C4', 'Pendapatan sewa & corporate solutions', 'Rp juta', 'higher', 'sum', 0, true, false, 'komersial', 'Sewa mobil, bus, dan kontrak korporat', 'Laporan komersial (ilustrasi)', 520000, 580000, 600000, 680000, .97, $il],
            ['C4.2', 'C4', 'Armada di kota ekspansi (Solo, IKN, dll.)', 'unit', 'higher', 'last', 0, false, false, 'komersial', 'Armada beroperasi di kota baru', 'Data armada', 150, 400, 420, 700, .9, $il],
            ['P1.1', 'P1', 'Utilisasi armada', '%', 'higher', 'average', 0, false, true, 'taksi', 'Armada beroperasi ÷ armada siap operasi × 100', 'Sistem armada', 82, 85, 86, 88, .99, $il],
            ['P1.2', 'P1', 'Pendapatan taksi per armada per hari', 'Rp ribu', 'higher', 'average', 0, false, false, 'taksi', 'Pendapatan taksi ÷ armada siap operasi ÷ hari', 'Sistem armada', 440, 460, 458, 475, .97, $il],
            ['P1.3', 'P1', 'Armada beroperasi', 'unit', 'higher', 'last', 0, false, false, 'armada', 'Seluruh armada grup akhir perioda', 'Data armada', 24200, 25500, 26070, 27500, .98, $lt],
            ['P2.1', 'P2', 'Biaya BBM & perawatan terhadap pendapatan taksi', '%', 'lower', 'average', 1, false, false, 'armada', 'Biaya BBM & perawatan ÷ pendapatan taksi × 100', 'Akuntansi biaya', 24.0, 23.5, 23.0, 22.5, 1.0, $il],
            ['P2.2', 'P2', 'Downtime armada karena perawatan', '%', 'lower', 'average', 1, false, false, 'armada', 'Hari armada di bengkel ÷ total hari armada × 100', 'Sistem perawatan', 4.5, 4.0, 4.1, 3.5, 1.08, $il],
            ['P3.1', 'P3', 'Kecelakaan per 1 juta km', 'rasio', 'lower', 'average', 2, false, false, 'esg', 'Kecelakaan tercatat ÷ km tempuh × 1.000.000', 'Laporan K3', 0.40, 0.35, 0.33, 0.30, 1.0, $il],
            ['P3.2', 'P3', 'Kendaraan lulus inspeksi keselamatan', '%', 'higher', 'average', 1, false, false, 'armada', 'Kendaraan lulus inspeksi harian/berkala ÷ diperiksa × 100', 'Sistem inspeksi', 97.0, 98.0, 98.4, 99.0, .995, $il],
            ['P4.1', 'P4', 'Armada kendaraan listrik', 'unit', 'higher', 'last', 0, false, false, 'esg', 'e-Bluebird, e-Silverbird, bus & taksi listrik beroperasi', 'Data armada (ilustrasi)', 450, 700, 650, 1000, .75, $il],
            ['P4.2', 'P4', 'Pengurangan emisi karbon terhadap baseline', '%', 'higher', 'last', 1, false, false, 'esg', 'Emisi yang dihindari ÷ emisi baseline × 100 (target 50% pada 2030)', 'Laporan keberlanjutan', 20.0, 25.0, 30.2, 35.0, .95, $il],
            ['P4.3', 'P4', 'Listrik dari PLTS di pool', 'MWh', 'higher', 'sum', 0, false, false, 'esg', 'Produksi listrik PLTS di pool Jakarta & Bali', 'Laporan energi', 300, 600, 650, 1000, .9, $il],
            ['L1.1', 'L1', 'Pengemudi lulus pelatihan layanan & keselamatan', '%', 'higher', 'last', 0, false, false, 'sdm', 'Pengemudi aktif lulus pelatihan tahunan ÷ total × 100', 'Pusat pelatihan', 85, 90, 91, 95, .97, $il],
            ['L1.2', 'L1', 'Rumah untuk pengemudi & karyawan (kumulatif)', 'unit', 'higher', 'last', 0, false, false, 'sdm', 'Unit rumah tersalurkan dalam program hingga 8.000 unit', 'Program BlueLife (ilustrasi)', 500, 1500, 1400, 3000, .82, $il],
            ['L1.3', 'L1', 'Turnover pengemudi', '%', 'lower', 'last', 0, false, false, 'sdm', 'Pengemudi keluar ÷ rata-rata pengemudi × 100', 'HRIS', 22, 20, 21, 18, 1.08, $il],
            ['L2.1', 'L2', 'Indeks keterikatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterikatan (skala 100)', 'Survei karyawan', 76, 78, 79, 80, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 50, 75, 74, 95, .88, $il],
            ['L3.1', 'L3', 'Armada dengan IoT taximeter & telematika', '%', 'higher', 'last', 0, false, false, 'digital', 'Armada terhubung telematika ÷ total armada × 100', 'Sistem IoT', 90, 95, 96, 99, .99, $il],
            ['L3.2', 'L3', 'Pendapatan iklan armada (BirdAds)', 'Rp juta', 'higher', 'sum', 0, true, false, 'digital', 'Pendapatan iklan di armada & aplikasi', 'Laporan anak usaha (ilustrasi)', 25000, 32000, 30000, 40000, .9, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat Bluebird, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala pool, Corporate Strategy',
                'summary' => "Pendapatan Rp5,71 T (+13,2%; 102% target).\nLaba ke pemilik Rp636 M (+8,7%; 99% target); marjin usaha 12,0%.\nTaksi: laba bersih +19% berkat utilisasi armada digital & efisiensi BBM.\nArmada 26.070 unit; capex Rp2 T (rencana awal Rp1,5 T); DER 52,6%.\nCititrans +80 rute; ekspansi ke Solo.",
                'decisions' => "1. Target 2026: pendapatan Rp6,3 T, laba ke pemilik Rp700 M, non-taksi 31%.\n2. Prioritas: produktivitas armada, capex selektif, elektrifikasi terukur.\n3. KPI korporat diturunkan ke pool & unit bisnis melalui Ruci BSC.",
                'follow_up' => 'Corporate Strategy menurunkan KPI 2026 ke setiap pool & unit bisnis paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta setiap tambahan armada didukung data utilisasi pool, dan rencana EV disertai kepastian infrastruktur pengisian.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala pool, Corporate Strategy',
                'summary' => match ($m) {
                    1 => 'Hujan lebat & banjir menurunkan perjalanan di Jakarta.',
                    3 => 'Mudik Lebaran (20 Maret): Cititrans & sewa bus penuh.',
                    5 => 'Pemasok EV menunda pengiriman 150 unit.',
                    6 => 'Semester I: pendapatan 97% target; laba 96% target.',
                    7 => 'Libur sekolah: sewa & taksi bandara naik.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    1 => 'Alihkan armada ke wilayah yang tidak terdampak banjir.',
                    3 => 'Tambah armada cadangan untuk rute mudik.',
                    5 => 'Prioritaskan EV untuk pool dengan stasiun pengisian siap.',
                    6 => 'Tunda pembelian armada BBM yang belum mendesak.',
                    7 => 'Tambah pengemudi cadangan di bandara.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Corporate Strategy',
                'summary' => $q === 1 ? 'Pendapatan Q1 98% target; non-taksi 31%; utilisasi 86%.' : 'Pendapatan H1 97% target; armada listrik 560 unit (target 620).',
                'decisions' => $q === 1 ? 'Perluas taksi bandara di Bali & Surabaya.' : 'Kemitraan stasiun pengisian dipercepat; EV menjadi KPI kritis.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Komisaris Utama', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit',
            'summary' => 'Komisaris menyoroti elektrifikasi yang tertinggal, program rumah pengemudi, dan leverage.',
            'decisions' => 'Direksi melaporkan armada EV, utilisasi pool, dan DER setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon perbandingan biaya per km EV vs BBM berdasarkan data aktual pool, bukan asumsi pemasok.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala pool, Corporate Strategy',
            'summary' => 'Realisasi September sebagian masih draf; pendapatan per armada dikembalikan untuk dihitung ulang.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; realisasi capex 2025 sekitar Rp2 T untuk peremajaan & penambahan armada.';

            return [
                'defs' => [
                    'armada' => ['BIRD-25-01', 'Peremajaan & penambahan armada', 'Peremajaan armada tua & tambahan ±1.900 unit.', 'T2', 'armada', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 5, ['P1', 'F1'], 'Armada 26.070 unit.'],
                    'cititrans' => ['BIRD-25-02', 'Perluasan rute Cititrans', '+80 rute & layanan AKAP.', 'T3', 'nontaksi', '2025-01-01', '2025-12-31', 'selesai', 4, 3, 3, 3, ['C3'], '200 rute beroperasi.'],
                    'solo' => ['BIRD-25-03', 'Ekspansi ke Solo & IKN', 'Taksi di Solo; bus & taksi listrik di IKN.', 'T3', 'komersial', '2025-04-01', '2025-12-31', 'selesai', 3, 3, 3, 3, ['C4'], 'Armada kota baru 420 unit.'],
                    'mybluebird' => ['BIRD-25-04', 'MyBluebird multi-produk', 'Satu aplikasi untuk taksi, sewa, shuttle, kirim.', 'T1', 'digital', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C2'], 'Porsi pemesanan digital 62%.'],
                    'plts' => ['BIRD-25-05', 'PLTS di pool Jakarta & Bali', 'Panel surya untuk listrik pool & pengisian EV.', 'T4', 'esg', '2025-03-01', '2025-12-31', 'selesai', 2, 2, 2, 2, ['P4'], 'PLTS 650 MWh.'],
                ],
                'activities' => [
                    ['armada', 'Pengadaan & registrasi armada', 'armada', '2025-01-01', '2025-12-31', 100], ['armada', 'Lelang armada tua', 'nontaksi', '2025-02-01', '2025-12-31', 100],
                    ['cititrans', 'Pembukaan rute baru', 'nontaksi', '2025-01-01', '2025-12-31', 100],
                    ['solo', 'Pool & perizinan Solo', 'komersial', '2025-04-01', '2025-11-30', 100], ['solo', 'Operasi bus & taksi listrik IKN', 'komersial', '2025-06-01', '2025-12-31', 100],
                    ['mybluebird', 'Integrasi produk & pembayaran', 'digital', '2025-01-01', '2025-12-31', 100],
                    ['plts', 'Pemasangan panel surya', 'esg', '2025-03-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['armada', 'Armada menembus 26.000 unit', '2025-12-31', true], ['solo', 'Operasi di Solo dimulai', '2025-11-30', true], ['cititrans', '+80 rute', '2025-12-31', true],
                ],
                'budgets' => [
                    ['armada', 'Pembelian armada baru', 'capex', 1_500_000_000_000, 1_850_000_000_000, $note, 'Kas internal & pinjaman bank'],
                    ['cititrans', 'Armada & pool shuttle', 'capex', 90_000_000_000, 82_000_000_000, $note, 'Kas internal'],
                    ['solo', 'Pool & armada kota baru', 'capex', 60_000_000_000, 54_000_000_000, $note, 'Kas internal'],
                    ['mybluebird', 'Pengembangan aplikasi', 'capex', 25_000_000_000, 23_000_000_000, $note, 'Kas internal'],
                    ['plts', 'Panel surya pool', 'capex', 12_000_000_000, 11_200_000_000, $note, 'Kas internal'],
                ],
            ];
        }

        return [
            'defs' => [
                'produktivitas' => ['BIRD-26-01', 'Produktivitas armada berbasis data', 'Penempatan armada dinamis per jam & lokasi.', 'T2', 'taksi', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 2, 2, ['P1', 'F2'], 'Utilisasi 88%; Rp475 ribu/armada/hari.'],
                'capex' => ['BIRD-26-02', 'Capex selektif & peremajaan', 'Pembelian armada berdasarkan utilisasi pool.', 'T2', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 5, ['F3', 'P1'], 'DER ≤ 55%.'],
                'perawatan' => ['BIRD-26-03', 'Perawatan prediktif', 'Telematika untuk jadwal perawatan & downtime.', 'T2', 'armada', '2026-01-15', '2026-12-31', 'berjalan', 4, 3, 2, 2, ['P2', 'L3'], 'Downtime 3,5%.'],
                'mybluebird' => ['BIRD-26-04', 'MyBluebird: langganan & korporat', 'Langganan perjalanan & akun korporat di aplikasi.', 'T1', 'digital', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C2'], '2,2 juta pengguna aktif bulanan.'],
                'bandara' => ['BIRD-26-05', 'Taksi bandara & hotel premium', 'Layanan Silverbird/Goldenbird di bandara & hotel.', 'T1', 'komersial', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 2, ['C1', 'C4'], 'Pendapatan korporat Rp680 M.'],
                'cititrans' => ['BIRD-26-06', 'Cititrans & sewa bus', 'Rute antarkota baru & sewa bus pariwisata.', 'T3', 'nontaksi', '2026-01-01', '2026-12-31', 'berjalan', 4, 3, 3, 3, ['C3', 'F1'], '240 rute; Rp560 M.'],
                'kota' => ['BIRD-26-07', 'Ekspansi kota baru', 'Solo, IKN, dan dua kota lain.', 'T3', 'komersial', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['C4'], '700 armada di kota baru.'],
                'ev' => ['BIRD-26-08', 'Elektrifikasi terukur', 'EV di pool dengan stasiun pengisian siap.', 'T4', 'esg', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 4, 4, ['P4'], '1.000 armada listrik.'],
                'bluelife' => ['BIRD-26-09', 'BlueLife: rumah & kesejahteraan pengemudi', 'Program rumah hingga 8.000 unit; pelatihan & asuransi.', 'T4', 'sdm', '2026-01-01', '2027-12-31', 'berjalan', 4, 4, 3, 3, ['L1'], '3.000 unit rumah pada 2026.'],
                'kinerja' => ['BIRD-26-10', 'KPI pool & budaya BlueCorps', 'KPI pool & pengemudi turunan Peta Strategi.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 1, ['L2'], '95% KPI terhubung.'],
            ],
            'activities' => [
                ['produktivitas', 'Model penempatan armada per jam', 'digital', '2026-01-01', '2026-05-31', 100], ['produktivitas', 'Penerapan di seluruh pool', 'taksi', '2026-04-01', '2026-12-31', 65],
                ['capex', 'Kriteria pembelian berbasis utilisasi', 'cfo', '2026-01-01', '2026-02-28', 100], ['capex', 'Peremajaan armada tahap 2026', 'armada', '2026-03-01', '2026-12-31', 60],
                ['perawatan', 'Telematika untuk perawatan prediktif', 'armada', '2026-01-15', '2026-09-30', 80],
                ['mybluebird', 'Langganan perjalanan', 'digital', '2026-01-01', '2026-06-30', 100], ['mybluebird', 'Akun korporat di aplikasi', 'digital', '2026-04-01', '2026-12-31', 55],
                ['bandara', 'Kerja sama bandara Bali & Surabaya', 'komersial', '2026-01-15', '2026-07-31', 100],
                ['cititrans', 'Rute antarkota baru', 'nontaksi', '2026-01-01', '2026-12-31', 70],
                ['kota', 'Pool & izin dua kota baru', 'komersial', '2026-02-01', '2026-10-31', 60],
                ['ev', 'Kemitraan stasiun pengisian', 'esg', '2026-01-01', '2026-09-30', 65], ['ev', 'Penerimaan unit EV', 'armada', '2026-02-01', '2026-12-31', 50],
                ['bluelife', 'Penyaluran rumah tahap 2026', 'sdm', '2026-01-01', '2026-12-31', 45], ['bluelife', 'Asuransi & pelatihan pengemudi', 'sdm', '2026-01-15', '2026-12-31', 70],
                ['kinerja', 'KPI pool turunan Peta Strategi', 'strategi', '2026-01-15', '2026-03-31', 100], ['kinerja', 'Dasbor kinerja pool', 'digital', '2026-03-01', '2026-09-30', 85],
            ],
            'milestones' => [
                ['produktivitas', 'Model penempatan live', '2026-05-31', true], ['mybluebird', 'Langganan diluncurkan', '2026-06-30', true], ['ev', '800 armada listrik', '2026-08-31', false],
                ['bluelife', '2.000 unit rumah tersalurkan', '2026-06-30', false], ['kinerja', 'KPI pool disetujui', '2026-03-31', true],
            ],
            'budgets' => [
                ['produktivitas', 'Model data & dasbor', 'capex', 15_000_000_000, 11_000_000_000, null, 'Kas internal'],
                ['capex', 'Peremajaan armada', 'capex', 1_400_000_000_000, 860_000_000_000, null, 'Kas internal & pinjaman bank'],
                ['perawatan', 'Telematika & sistem perawatan', 'capex', 20_000_000_000, 14_500_000_000, null, 'Kas internal'],
                ['mybluebird', 'Pengembangan aplikasi', 'capex', 30_000_000_000, 21_000_000_000, null, 'Kas internal'],
                ['bandara', 'Kerja sama lokasi & armada premium', 'opex', 40_000_000_000, 27_000_000_000, null],
                ['cititrans', 'Armada & pool shuttle', 'capex', 120_000_000_000, 79_000_000_000, null, 'Kas internal'],
                ['kota', 'Pool & armada kota baru', 'capex', 90_000_000_000, 48_000_000_000, null, 'Kas internal'],
                ['ev', 'Armada listrik & pengisian', 'capex', 450_000_000_000, 205_000_000_000, null, 'Pinjaman hijau'],
                ['bluelife', 'Subsidi & fasilitas rumah', 'opex', 60_000_000_000, 27_000_000_000, null],
                ['kinerja', 'Program KPI & budaya', 'opex', 3_000_000_000, 2_100_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Capex melampaui rencana', 'Capex Rp2 T vs rencana awal Rp1,5 T; DER naik ke 52,6%.', 'tinjauan', 'tahunan', 'keuangan', 'sedang', 500000000000, 'F3.2', 'armada', 'komisaris', '2026-02-24',
                [['Kriteria pembelian armada berbasis utilisasi pool', 'cfo', '2026-02-28', 'selesai', 100]], ['2026-03-15', 'Kriteria capex berbasis utilisasi berlaku.']],
            ['Marjin usaha turun', 'Marjin usaha 12,0% (2024: 12,6%); beban usaha naik 13%.', 'tinjauan', 'tahunan', 'keuangan', 'sedang', null, 'F2.2', null, 'cfo', '2026-02-24',
                [['Program produktivitas armada 2026', 'taksi', '2026-12-31', 'berjalan', 65]], null],
        ] : [
            ['Elektrifikasi tertinggal', 'Armada listrik 560 unit per Juni vs target 620; pengiriman EV tertunda & stasiun pengisian terbatas.', 'timwas', 'boc', 'proses', 'kritis', null, 'P4.1', 'ev', 'komisaris', '2026-07-28',
                [['Kemitraan stasiun pengisian tambahan', 'esg', '2026-09-30', 'selesai', 100], ['Diversifikasi pemasok EV', 'armada', '2026-11-30', 'berjalan', 40]], null],
            ['Program rumah pengemudi lambat', '1.400 unit per Agustus vs rencana 2.000; proses KPR pengemudi panjang.', 'tinjauan', 'q2', 'sdm', 'tinggi', null, 'L1.2', 'bluelife', 'sdm', '2026-07-22',
                [['Kerja sama bank untuk KPR pengemudi', 'sdm', '2026-10-31', 'berjalan', 60]], null],
            ['Keluhan penumpang di atas target', '1,45 keluhan per 10.000 perjalanan vs target 1,30; sebagian karena keterlambatan saat banjir.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'C1.3', 'produktivitas', 'taksi', '2026-07-22',
                [['Notifikasi keterlambatan otomatis di aplikasi', 'digital', '2026-09-30', 'selesai', 100]], null],
            ['Downtime armada tinggi', 'Downtime 4,0% vs 3,5%; suku cadang impor terlambat.', 'internal', 'rm7', 'proses', 'sedang', null, 'P2.2', 'perawatan', 'armada', '2026-08-12',
                [['Stok pengaman suku cadang kritis', 'armada', '2026-10-31', 'berjalan', 60]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Pengiriman kendaraan listrik', 'eksternal', 'ev', 'P4', 'Produsen EV', 'Jadwal pengiriman menentukan target armada listrik.', 'armada', 'tinggi', 'Diversifikasi pemasok.'],
                ['Stasiun pengisian daya di pool & kota', 'eksternal', 'ev', 'P4', 'PLN & penyedia pengisian', 'EV butuh infrastruktur siap.', 'esg', 'tinggi', 'Kemitraan multi-operator & PLTS pool.'],
                ['Izin operasi di kota baru', 'eksternal', 'kota', 'C4', 'Pemda & Dishub', 'Kuota & izin taksi di kota baru.', 'komersial', 'sedang', 'Pendekatan dini ke pemda.'],
                ['Pembiayaan KPR pengemudi', 'eksternal', 'bluelife', 'L1', 'Bank mitra', 'Program rumah butuh skema KPR khusus.', 'sdm', 'sedang', 'Kerja sama bank & penjaminan perusahaan.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat produktivitas pool mingguan', 'rapat', 'Utilisasi, pendapatan per armada, dan keluhan per pool.', 'Mingguan', 'taksi', 'melembaga'],
                ['Komite capex armada', 'rapat', 'Pembelian armada berdasarkan data utilisasi.', 'Bulanan', 'cfo', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan keberlanjutan.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Inspeksi keselamatan harian', 'sop', 'Kendaraan diperiksa sebelum keluar pool.', 'Harian', 'armada', 'melembaga'],
                ['Nilai Peduli, Integritas, Pelayanan Prima, Pola Pikir Berkembang', 'budaya', 'Apresiasi pengemudi teladan & internalisasi nilai.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
