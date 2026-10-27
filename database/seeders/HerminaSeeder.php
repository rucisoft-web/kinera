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
 * Demo lengkap PT Medikaloka Hermina Tbk (kode HMN) — jaringan rumah sakit swasta nasional.
 *
 * - Perioda 2025 (ditutup): angka tahunan dari Laporan Tahunan 2025 (keuangan, operasional, SDM, porsi JKN);
 *   target 2025 dan rincian bulanan bersifat ilustrasi dengan total yang sama dengan laporan tahunan.
 * - Perioda 2026 (berjalan, bawaan): target & realisasi Jan–Sep 2026 adalah SIMULASI untuk demo.
 * - Peta strategi rumah sakit: 4 tema, 16 sasaran, 45 KPI (termasuk Indikator Nasional Mutu Kemenkes), relasi sebab-akibat.
 *   Misi Hermina sendiri menyebut Balanced Scorecard sebagai tolok ukur kepuasan pemangku kepentingan.
 */
class HerminaSeeder extends ShowcaseSeeder
{
    public const CODE = 'HMN';

    public const LT = 'Laporan Tahunan 2025 PT Medikaloka Hermina Tbk';

    /** Bobot musiman kunjungan & pendapatan (Lebaran akhir Maret 2025 & 20 Maret 2026 menurunkan kunjungan elektif; musim hujan & DBD menaikkan Jan–Feb & Des). */
    public const SEASON = [
        2025 => [1 => .083, .080, .079, .078, .084, .083, .085, .086, .084, .087, .085, .086],
        2026 => [1 => .084, .081, .074, .082, .084, .083, .085, .086, .084, .087, .085, .085],
    ];

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Medikaloka Hermina Tbk', 'legal_name' => 'PT Medikaloka Hermina Tbk',
            'industry' => 'Layanan kesehatan (jaringan rumah sakit)',
            'address' => 'Jl. Raya Jatinegara Barat No. 126', 'city' => 'Jakarta Timur',
            'phone' => '+62 21 3970 2255', 'website' => 'https://www.herminahospitals.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham HEAL). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    protected function season(): array
    {
        return self::SEASON;
    }

    protected function onTrack2026(): array
    {
        return ['C1.3', 'C2.1', 'C2.3', 'C3.3', 'C4.1', 'F2.2', 'F3.2', 'L1.2', 'L2.2', 'L3.1', 'L4.1', 'P1.1', 'P1.3', 'P2.2', 'P2.4'];
    }

    protected function missingSeptember(): array
    {
        return ['C3.3', 'L2.2'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['C3.1', 'Angka September masih menghitung pasien yang beralih dari asuransi swasta ke JKN di tengah episode rawat. Mohon dipisahkan per penjamin akhir dan lampirkan rekap SIMRS per RS.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P4.2'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 3, 2, 3];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Hermina (contoh)', 'hmn.admin', 'Admin Sistem', 'Corporate Planning', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'hmn.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'hmn.keuangan', 'Direktur Keuangan', 'Keuangan', ['keuangan']],
            'medis' => ['Direktur Pelayanan Medis & Mutu (contoh)', 'hmn.medis', 'Direktur Pelayanan Medis', 'Pelayanan Medis & Mutu', ['kepala-unit']],
            'operasional' => ['Direktur Operasional (contoh)', 'hmn.operasional', 'Direktur Operasional', 'Operasional Rumah Sakit', ['kepala-unit']],
            'komersial' => ['Kepala Pemasaran & Kemitraan (contoh)', 'hmn.pemasaran', 'Head of Marketing & Partnership', 'Pemasaran & Kemitraan', ['kepala-unit']],
            'pengembangan' => ['Kepala Pengembangan Jaringan (contoh)', 'hmn.pengembangan', 'Head of Network Development', 'Pengembangan Bisnis', ['kepala-unit']],
            'sdm' => ['Direktur SDM (contoh)', 'hmn.sdm', 'Direktur SDM', 'SDM & Hermina Learning Center', ['kepala-unit', 'editor-jatidiri']],
            'ti' => ['Kepala TI & Digital (contoh)', 'hmn.ti', 'Chief Digital Officer', 'Teknologi Informasi', ['kepala-unit']],
            'strategi' => ['Perencanaan Strategis (contoh)', 'hmn.strategi', 'Corporate Planning & Risk Manager', 'Perencanaan Strategis & Manajemen Risiko', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'hmn.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Data Mutu (contoh)', 'hmn.analis', 'Quality Data Analyst', 'Pelayanan Medis & Mutu', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Mewujudkan Perseroan yang memiliki jaringan rumah sakit yang tumbuh, sehat dan berumur panjang.',
            'mission' => "1. Melakukan upaya berkelanjutan untuk meningkatkan kinerja jaringan rumah sakit melalui penambahan jenis dan jumlah pelayanan rumah sakit.\n2. Menjalankan pengelolaan Perseroan secara profesional dengan tata kelola yang baik (GCG), untuk mencapai efisiensi dan efektivitas optimal dengan rasio keuangan yang sehat dan penerapan tanggung jawab sosial dan lingkungan.\n3. Melakukan proses kaderisasi untuk menghasilkan eksekutif yang profesional dan loyal dengan mewariskan tata kelola terkini dan nilai kepada generasi selanjutnya.\n4. Memberikan kepuasan kepada semua pemangku kepentingan secara seimbang dengan tolok ukur Balanced Scorecard.",
            'purpose' => 'Menghadirkan layanan kesehatan bermutu, aman, dan terjangkau yang dipercaya keluarga Indonesia, dari layanan ibu & anak hingga layanan unggulan.',
            'tagline' => 'Tumbuh, sehat, dan berumur panjang',
            'brand_promise' => 'Kepercayaan (TRUST): pelayanan yang aman, ramah, dan konsisten di seluruh jaringan Hermina.',
            'value_proposition' => 'Jaringan 52 rumah sakit di 16 provinsi dengan 8.666 tempat tidur, melayani pasien JKN dan non-JKN; keunggulan historis layanan ibu & anak, diperluas ke layanan unggulan (onkologi, fertilitas, stroke), didukung aplikasi Halo Hermina dan Hermina Learning Center.',
            'culture_statement' => 'Nilai inti TRUST (Kepercayaan), ditopang Komitmen, Keterbukaan, dan Kompetensi; patient safety dengan budaya tidak saling menyalahkan (non-blaming culture).',
            'history' => "1985: rumah sakit bersalin pertama di Jatinegara, Jakarta (25 tempat tidur).\n1991: ditingkatkan menjadi Rumah Sakit Ibu dan Anak.\n1999: berubah dari organisasi nirlaba menjadi PT Medikaloka Hermina.\n2014: RS Hermina Palembang, rumah sakit pertama di luar Jawa.\n2018: saham HEAL tercatat di Bursa Efek Indonesia (16 Mei).\n2025: 52 rumah sakit, 8.666 tempat tidur, 9,7 juta pasien; RS Hermina Salatiga beroperasi akhir 2025.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Kepercayaan', 'TRUST', 'Nilai inti: kepercayaan menjadi fondasi hubungan dengan pasien, tenaga medis, dan seluruh pemangku kepentingan.', "Menyampaikan informasi klinis secara jujur kepada pasien\nMelaporkan realisasi KPI dan insiden apa adanya"],
            ['Komitmen', 'Commitment', 'Mengutamakan kepentingan dan kepuasan pelanggan, sasaran, patient safety dengan budaya tidak saling menyalahkan, serta mutu dan produktivitas.', "Melaporkan insiden keselamatan pasien tanpa takut disalahkan\nBekerja sesuai SOP dan saling membantu, tidak bekerja sendiri-sendiri"],
            ['Keterbukaan', 'Openness', 'Manajemen menerapkan prinsip keterbukaan dan transparansi; setiap individu berkomunikasi jujur dan terbuka pada perubahan.', "Membahas sasaran merah secara terbuka di rapat bulanan\nMenerima umpan balik pasien sebagai bahan perbaikan"],
            ['Kompetensi', 'Competence', 'Karyawan kompeten dan loyal adalah aset utama; terus belajar, membina bawahan, dan bekerja efektif, efisien, serta teliti.', "Menjaga sertifikasi kompetensi tetap berlaku\nMenjadi pembimbing di program RS pendidikan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Jaringan rumah sakit nasional', 'Sumber daya fisik', '52 RS di 16 provinsi, 8.666 tempat tidur operasional (2025).', 4, 5, true, 'Pembukaan RS Hermina Badung (Bali) & perluasan kapasitas RS eksisting.'],
            ['Keunggulan layanan ibu & anak', 'Kompetensi', 'Akar sejarah Hermina sejak 1985; 95 dokter spesialis anak full timer dan 500 part timer.', 5, 5, true, 'Pertahankan sebagai pembeda merek di seluruh jaringan.'],
            ['Kemitraan pembayar (JKN, asuransi, korporasi)', 'Kompetensi', 'Mitra BPJS Kesehatan di seluruh jaringan; kerja sama dengan asuransi swasta & korporasi.', 4, 5, true, 'Diversifikasi pembayar; paket korporat & respons ketentuan co-payment.'],
            ['SDM medis & Hermina Learning Center', 'Kompetensi', '574 dokter spesialis full timer & 3.157 part timer; RS pendidikan di Depok, Pasteur, Arcamanik, Ubaya.', 3, 5, true, 'Rekrutmen & retensi spesialis/subspesialis lewat kerja sama universitas.'],
            ['Pusat keunggulan (onkologi, fertilitas, stroke)', 'Kompetensi', 'Layanan unggulan & bernilai tambah, termasuk Robotic Rehabilitation di RS Hermina Kemayoran.', 2 + $lvl, 4, false, 'Peta jalan center of excellence 2026–2027.'],
            ['SIMRS, Halo Hermina & data', 'Sistem', 'Sistem informasi RS, aplikasi pasien Halo Hermina, telekonsultasi, otomasi administrasi.', 2 + $lvl, 4, false, 'SIMRS gelombang 2, e-klaim otomatis, Halo Hermina 2.0.'],
            ['Tata kelola & pengadaan terpusat (PT MLM)', 'Sistem', 'Fungsi manajemen, pengadaan, dan operatorship lewat PT Medika Loka Manajemen; program Mitra Member.', 3, 4, false, 'Konsolidasi pengadaan obat & alkes; 3 MoU operatorship menuju 2027.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    /** @return array<string,StrategicIssue> */
    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi makro Indonesia 2025', '2026-01-20', 'eksternal', self::LT.' — Laporan Dewan Komisaris',
                'BPS: ekonomi tumbuh 5,11% (Q4 5,39% yoy), inflasi Desember 2,92%. Daya beli relatif terjaga dan menopang kebutuhan layanan kesehatan.',
                'Permintaan layanan tetap tumbuh; jaga keterjangkauan untuk segmen menengah.'],
            ['Cakupan JKN mendekati semesta', '2026-01-20', 'eksternal', self::LT.' — Laporan Dewan Komisaris',
                'DJSN: kepesertaan JKN 282,5 juta jiwa (99,3% penduduk) per 31 Oktober 2025. Fokus program bergeser ke keaktifan peserta dan mutu layanan.',
                'JKN tetap menjadi "mesin volume"; efisiensi & mutu menentukan margin.'],
            ['Kelas Rawat Inap Standar (KRIS)', '2026-01-25', 'eksternal', self::LT.' — Laporan Dewan Komisaris',
                'Penerapan KRIS menyeluruh bagi RS mitra BPJS Kesehatan paling lambat 30 Juni 2026, menunggu ketentuan turunan sebagai pedoman teknis.',
                'Renovasi ruang rawat inap & penyesuaian kapasitas sebelum tenggat.'],
            ['Pandangan industri 2026', '2026-02-05', 'eksternal', self::LT.' — Laporan Direksi',
                'BI memprakirakan ekonomi 2026 tumbuh 4,9%–5,7%; anggaran kesehatan RAPBN 2026 Rp244 triliun; PEFINDO menilai outlook sektor kesehatan stabil dengan pertumbuhan pendapatan mid-single digit 2025–2027.',
                'Target pertumbuhan pendapatan ±10% dengan perbaikan margin.'],
            ['Co-payment asuransi kesehatan swasta', '2026-02-05', 'eksternal', self::LT.' — Laporan Direksi',
                'Ketentuan co-pay 10% untuk klaim asuransi kesehatan swasta diberitakan berlaku Januari 2026 dan dapat mengubah perilaku pasien serta pola pembayaran.',
                'Paket layanan & edukasi pasien asuransi; pantau dampak ke kunjungan non-JKN.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Kinerja',
                'Pendapatan Rp7.133,5 M (+6,2%), EBITDA Rp1.948 M (margin 27,3%), laba neto Rp549,0 M (2024: Rp689 M). BOR 69,8% (2024: 72,9%) karena tambahan kapasitas. Capex Rp1.787 M.',
                'Prioritas 2026: utilisasi kapasitas baru, disiplin biaya, dan konversi kas.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Prioritas anggaran kesehatan nasional', 'Anggaran kesehatan 2026 Rp244 triliun untuk perluasan akses & mutu layanan.', 'peluang', 4, 4, 'naik', 'Perkuat kapasitas di wilayah dengan kebutuhan tinggi.'],
            ['politik', 'Kebijakan tarif & kendali klaim JKN', 'Dinamika tarif dan pengendalian klaim JKN berpotensi menekan margin bila tidak diimbangi produktivitas.', 'ancaman', 5, 4, 'naik', 'Efisiensi proses, kepatuhan koding, e-klaim otomatis.'],
            ['ekonomi', 'Pertumbuhan ekonomi & kelas menengah', 'Ekonomi tumbuh ±5%; permintaan layanan berkualitas & preventif meningkat.', 'peluang', 4, 4, 'naik', 'Paket medical check-up & layanan bernilai tambah.'],
            ['ekonomi', 'Kenaikan biaya tenaga kerja, obat & alat kesehatan', 'Biaya tenaga kerja naik dan sebagian alkes/obat impor; margin laba bruto turun ke 33,9% (2024: 36,2%).', 'ancaman', 5, 5, 'naik', 'Pengadaan terpusat via PT MLM & formularium standar.'],
            ['sosial', 'Transisi demografi & penyakit kronis', 'Beban penyakit kronis (kanker, stroke, jantung) dan kesadaran kesehatan meningkat.', 'peluang', 5, 5, 'naik', 'Pusat keunggulan onkologi & stroke.'],
            ['sosial', 'Ekspektasi pasien: cepat, nyaman, terintegrasi', 'Pasien membandingkan pengalaman layanan dengan layanan digital sehari-hari.', 'peluang', 4, 5, 'naik', 'Halo Hermina 2.0, waktu tunggu lebih pendek.'],
            ['teknologi', 'Telemedis & aplikasi kesehatan', 'Konsultasi daring dan pemesanan digital menjadi kebiasaan.', 'peluang', 4, 4, 'naik', 'Telekonsultasi terintegrasi dengan jadwal dokter & rekam medis.'],
            ['teknologi', 'Serangan siber pada rumah sakit', 'Data rekam medis bernilai tinggi; gangguan SIMRS menghentikan layanan.', 'ancaman', 5, 3, 'naik', 'SOC, enkripsi, uji pemulihan bencana.'],
            ['lingkungan', 'Pengelolaan limbah medis B3', 'Volume limbah medis naik seiring pertumbuhan pasien; ketentuan pengelolaan ketat.', 'ancaman', 4, 3, 'stabil', 'Kontrak pengolah berizin & audit rutin.'],
            ['lingkungan', 'Efisiensi energi & Rencana Aksi Keberlanjutan', 'RAKB lima tahun: patient safety, efisiensi energi, limbah medis, pemberdayaan tenaga kesehatan.', 'peluang', 3, 4, 'naik', 'Program energi & limbah dengan target terukur.'],
            ['hukum', 'Kewajiban KRIS', 'RS mitra BPJS Kesehatan wajib memenuhi standar KRIS (12 kriteria) paling lambat 30 Juni 2026.', 'ancaman', 5, 5, 'naik', 'Renovasi bertahap & penyesuaian kapasitas.'],
            ['hukum', 'UU Kesehatan & RS pendidikan', 'UU No. 17/2023 membuka jalur pendidikan dokter spesialis berbasis rumah sakit.', 'peluang', 4, 4, 'naik', 'Perluas RS pendidikan bersama universitas mitra.'],
            ['hukum', 'Ketentuan co-payment asuransi swasta', 'Ketentuan OJK tentang produk asuransi kesehatan dapat mengubah pola kunjungan pasien asuransi.', 'ancaman', 3, 3, 'stabil', 'Pantau implementasi & siapkan paket layanan.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Persaingan jaringan RS swasta & RS pemerintah', 'Jaringan RS swasta nasional berekspansi ke kota tingkat dua; RS pemerintah meningkatkan mutu & kapasitas.', 4, 'Diferensiasi lewat layanan ibu & anak, pusat keunggulan, dan pengalaman pasien.'],
            ['kekuatan_pembeli', 'BPJS Kesehatan sebagai pembayar dominan', 'JKN 72,2% kunjungan rawat jalan & 79,0% rawat inap; tarif INA-CBG ditetapkan pembayar.', 5, 'Efisiensi biaya per kasus & diversifikasi ke asuransi/korporat.'],
            ['kekuatan_pemasok', 'Kelangkaan dokter spesialis & pemasok obat/alkes', 'Dokter spesialis/subspesialis terbatas, terutama di luar Jawa; harga alkes impor sensitif kurs.', 5, 'RS pendidikan, skema retensi, pengadaan terpusat.'],
            ['pendatang_baru', 'Grup RS baru & investor kesehatan', 'Investor baru masuk lewat akuisisi RS daerah dan KEK kesehatan.', 3, 'Percepat ekspansi terukur & model operatorship.'],
            ['produk_pengganti', 'Klinik utama, telemedis & berobat ke luar negeri', 'Klinik spesialis, layanan telemedis, dan wisata medis menggantikan sebagian kunjungan rawat jalan.', 3, 'Telekonsultasi Hermina & layanan unggulan setara regional.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Pasien JKN (BPJS Kesehatan)', 'Pembayar publik', 'Peserta JKN di seluruh jaringan; 72,2% kunjungan rawat jalan & 79,0% pasien rawat inap (2025).', 75, 'stabil', true, 'Akses cepat, antrean pendek, mutu setara pasien lain.', 'Efisiensi alur layanan & kepatuhan KRIS.'],
            ['Pasien asuransi kesehatan swasta', 'Asuransi', 'Pemegang polis asuransi kesehatan komersial.', 10, 'naik', true, 'Proses penjaminan cepat, kamar sesuai polis, dokter pilihan.', 'Kerja sama penjaminan langsung & paket layanan.'],
            ['Karyawan perusahaan (korporasi)', 'Korporasi', 'Kontrak layanan kesehatan & medical check-up karyawan.', 9, 'naik', true, 'Tagihan terkonsolidasi, MCU terjadwal, laporan kesehatan.', 'Paket korporat & account manager.'],
            ['Pasien umum (bayar sendiri)', 'Pribadi', 'Pasien membayar sendiri, termasuk persalinan & layanan elektif.', 5, 'stabil', true, 'Harga paket jelas, layanan ramah keluarga.', 'Paket persalinan & program loyalitas.'],
            ['Layanan unggulan & rujukan', 'Layanan', 'Onkologi, fertilitas (bayi tabung), stroke center, rehabilitasi robotik.', 1, 'naik', true, 'Dokter subspesialis & teknologi terkini.', 'Pusat keunggulan di RS hub.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => 'Porsi JKN dari laporan tahunan; rincian segmen non-JKN ilustratif untuk demo']);
        }

        foreach ([
            ['PT Medikaloka Hermina Tbk', 'Jaringan RS swasta nasional', 1, 7133.5, 6.2, 7.7, true, '52 RS di 16 provinsi, kuat di layanan ibu & anak dan pasien JKN, biaya per kasus efisien.', 'Porsi pasien non-JKN relatif kecil; margin tertekan biaya tenaga kerja & obat.'],
            ['Jaringan RS swasta premium nasional (mis. Siloam, Mayapada)', 'Jaringan RS premium', 2, null, null, null, false, 'Merek premium, layanan subspesialis & teknologi tinggi.', 'Harga lebih tinggi, porsi JKN berbeda.'],
            ['Jaringan RS keluarga & ibu-anak (mis. Mitra Keluarga)', 'Jaringan RS keluarga', 3, null, null, null, false, 'Merek keluarga kuat di Jabodetabek & Jawa Timur.', 'Jangkauan geografis lebih sempit.'],
            ['Jaringan RS menengah fokus JKN (mis. Primaya)', 'Jaringan RS menengah', 4, null, null, null, false, 'Ekspansi agresif di kota tingkat dua.', 'Skala & kedalaman layanan unggulan masih berkembang.'],
            ['RS pemerintah & BUMN (RSUD, RS vertikal, IHC)', 'RS pemerintah', 5, null, null, null, false, 'Kapasitas besar, rujukan nasional, dukungan anggaran.', 'Antrean panjang & birokrasi.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Contoh pembanding kualitatif untuk demo; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Kelas Rawat Inap Standar (KRIS)', 'Kementerian Kesehatan & BPJS Kesehatan', 'Perpres No. 59/2024 (perubahan ketiga Perpres 82/2018 tentang Jaminan Kesehatan)', 'Pelayanan JKN', '2024-05-08', 'Ruang rawat inap RS mitra BPJS wajib memenuhi 12 kriteria KRIS.', 'Tempat tidur yang belum sesuai berisiko tidak dapat melayani pasien JKN.', 'sebagian', 'operasional', '2026-06-30'],
            ['Undang-Undang Kesehatan', 'Kementerian Kesehatan', 'UU No. 17/2023 tentang Kesehatan', 'Pelayanan kesehatan', '2023-08-08', 'Perizinan RS, tenaga medis, RS pendidikan, dan hak pasien.', 'Dasar perizinan seluruh jaringan & program RS pendidikan.', 'patuh', 'medis', null],
            ['Akreditasi rumah sakit', 'Kementerian Kesehatan (lembaga independen pelaksana akreditasi)', 'Permenkes No. 12/2020 tentang Akreditasi RS', 'Mutu', null, 'Seluruh RS wajib terakreditasi dan dinilai ulang berkala.', 'Akreditasi menjadi syarat kerja sama BPJS Kesehatan.', $year === 2025 ? 'patuh' : 'sebagian', 'medis', $year === 2025 ? null : '2026-12-31'],
            ['Indikator Nasional Mutu (INM)', 'Kementerian Kesehatan', 'Permenkes No. 30/2022', 'Mutu', null, 'Pelaporan indikator mutu: kebersihan tangan, identifikasi pasien, waktu tunggu rawat jalan, kepuasan pasien, dll.', 'Pelaporan bulanan ke Kemenkes.', 'patuh', 'medis', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Rekam medis & data pasien di SIMRS dan Halo Hermina termasuk data spesifik.', 'Sanksi administratif & risiko reputasi.', 'sebagian', 'ti', '2026-12-31'],
            ['Pengelolaan limbah B3 medis', 'Kementerian Lingkungan Hidup', 'PP No. 22/2021 tentang Penyelenggaraan Perlindungan & Pengelolaan LH', 'Lingkungan', null, 'Limbah medis wajib dikelola pihak berizin dengan manifes elektronik.', 'Kepatuhan izin lingkungan seluruh RS.', 'patuh', 'operasional', null],
            ['Tata kelola perusahaan terbuka', 'OJK', 'POJK No. 21/2015 & POJK 51/2017 (keuangan berkelanjutan)', 'Pasar modal', null, 'Penerapan GCG, laporan tahunan & laporan keberlanjutan.', 'Kepatuhan pelaporan emiten HEAL.', 'patuh', 'cfo', null],
            ['Ketentuan produk asuransi kesehatan (co-payment)', 'OJK', 'Ketentuan OJK tentang produk asuransi kesehatan (rujukan Laporan Tahunan 2025)', 'Pembayar', null, 'Pembagian risiko (co-payment) pada klaim asuransi kesehatan swasta.', 'Memengaruhi perilaku pasien asuransi.', 'dipantau', 'komersial', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pasien' => ['Pasien & keluarga', 'eksternal', 'Pelanggan', 4, 5, 'Layanan aman, cepat, ramah, biaya jelas.', 'Survei kepuasan, Halo Hermina, layanan pengaduan.', 'Customer Experience'],
            'bpjs' => ['BPJS Kesehatan', 'eksternal', 'Pembayar', 5, 5, 'Kepatuhan KRIS & klaim, mutu layanan peserta JKN.', 'Rapat koordinasi berkala, verifikasi klaim.', 'Unit Penjaminan'],
            'asuransi' => ['Asuransi swasta & korporasi', 'eksternal', 'Pembayar', 4, 4, 'Penjaminan cepat, biaya wajar, laporan utilisasi.', 'Account management & paket korporat.', 'Pemasaran & Kemitraan'],
            'dokter' => ['Dokter spesialis & subspesialis', 'internal', 'Mitra medis', 5, 5, 'Fasilitas memadai, jadwal fleksibel, remunerasi adil, pengembangan karier.', 'Komite medik, forum dokter, skema retensi.', 'Pelayanan Medis'],
            'karyawan' => ['Karyawan & tenaga kesehatan (18.730 orang)', 'internal', 'SDM', 4, 5, 'Pengembangan kompetensi, keselamatan kerja, kesejahteraan.', 'Hermina Learning Center, survei keterlibatan.', 'SDM'],
            'pemegang_saham' => ['Pemegang saham & investor (HEAL)', 'internal', 'Pemilik', 5, 4, 'Pertumbuhan laba, dividen, tata kelola.', 'RUPS, paparan publik, laporan tahunan.', 'Sekretaris Perusahaan'],
            'regulator' => ['Regulator (Kemenkes, Dinkes, OJK, BEI)', 'eksternal', 'Regulator', 5, 3, 'Perizinan, akreditasi, INM, pelaporan emiten.', 'Kepatuhan proaktif & pelaporan tepat waktu.', 'Legal & Kepatuhan'],
            'akreditasi' => ['Lembaga akreditasi RS', 'eksternal', 'Regulator', 4, 3, 'Standar akreditasi terpenuhi di setiap RS.', 'Survei simulasi & pendampingan.', 'Komite Mutu'],
            'pemasok' => ['Pemasok obat, alkes & mitra KSO', 'eksternal', 'Pemasok', 3, 4, 'Volume stabil, pembayaran tepat waktu.', 'Program Mitra Member (PT MLM).', 'Pengadaan'],
            'universitas' => ['Universitas & institusi pendidikan', 'eksternal', 'Mitra', 3, 4, 'Wahana pendidikan spesialis & tenaga kesehatan.', 'Perjanjian kerja sama RS pendidikan.', 'SDM'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'laba' => ['Laba neto turun meski pendapatan naik', 'Laba neto Rp549,0 M (2024: Rp689 M); margin laba bruto 33,9% (2024: 36,2%) karena biaya tenaga kerja & obat naik.', 'keuangan', 5, 5, 'cfo', ['pemegang_saham']],
            'kris' => ['Kesiapan KRIS sebelum 30 Juni 2026', 'Sebagian ruang rawat inap belum memenuhi 12 kriteria KRIS; renovasi mengurangi kapasitas sementara.', 'proses', 5, 5, 'operasional', ['bpjs', 'regulator']],
            'jkn' => ['Ketergantungan pada pembayar JKN', 'JKN 72,2% rawat jalan & 79,0% rawat inap; tarif ditetapkan pembayar.', 'pelanggan', 4, 5, 'komersial', ['bpjs', 'asuransi']],
            'dokter' => ['Ketersediaan dokter spesialis/subspesialis', 'Ekspansi & layanan unggulan membutuhkan spesialis, terutama di luar Jawa.', 'sdm', 5, 5, 'sdm', ['dokter', 'universitas']],
            'utilisasi' => ['Utilisasi kapasitas baru', 'BOR turun ke 69,8% karena tambahan 414 tempat tidur dan RS baru dalam masa ramp-up.', 'proses', 4, 4, 'operasional', ['pasien', 'pemegang_saham']],
            'digital' => ['Adopsi layanan digital pasien', 'Pemesanan via Halo Hermina & telekonsultasi belum menjadi kebiasaan mayoritas pasien.', 'pelanggan', 3, 4, 'ti', ['pasien']],
            'data' => ['Keamanan data pasien', 'Transformasi digital memperluas permukaan serangan; UU PDP berlaku penuh.', 'proses', 4, 5, 'ti', ['pasien', 'regulator']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Jaringan RS nasional yang luas', '52 RS, 16 provinsi, 8.666 tempat tidur.', 0.14, 4],
            ['S', 'Merek tepercaya layanan ibu & anak', 'Sejarah 40 tahun sejak 1985.', 0.12, 4],
            ['S', 'Arus kas operasi kuat', 'Kas operasi Rp1.138 M; pinjaman/EBITDA 1,7x.', 0.10, 3],
            ['S', 'Seluruh RS terakreditasi', 'Akreditasi Paripurna di jaringan; Salatiga dinilai 2026.', 0.08, 4],
            ['W', 'Margin & laba menurun', 'Margin laba bersih 7,7% (2024: 10,2%).', 0.12, 2],
            ['W', 'Porsi pasien non-JKN kecil', 'Non-JKN 27,8% rawat jalan & 21,0% rawat inap.', 0.10, 2],
            ['W', 'Adopsi digital pasien masih rendah', 'Halo Hermina belum menjadi kanal utama pemesanan.', 0.06, 2],
            ['O', 'Penyakit kronis & layanan unggulan', 'Permintaan onkologi, stroke, fertilitas meningkat.', 0.10, 3],
            ['O', 'Prioritas anggaran kesehatan', 'Rp244 triliun pada 2026.', 0.06, 3],
            ['O', 'RS pendidikan menurut UU Kesehatan', 'Jalur baru pemenuhan dokter spesialis.', 0.06, 3],
            ['T', 'Kendali tarif & klaim JKN', 'Tekanan margin per kasus.', 0.10, 2],
            ['T', 'Kelangkaan dokter spesialis', 'Persaingan merekrut spesialis antar jaringan RS.', 0.08, 2],
            ['T', 'Kewajiban KRIS', 'Capex renovasi & potensi pengurangan tempat tidur.', 0.06, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    /** @param array<string,StrategicIssue> $issues */
    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Pusat keunggulan di RS hub jaringan', 'Gunakan skala jaringan untuk rujukan onkologi, stroke, fertilitas.', 5, ['laba']],
            ['SO', 'Ekspansi terukur ke wilayah kebutuhan tinggi', 'RS Hermina Badung (Bali) & perluasan kapasitas RS eksisting.', 4, ['utilisasi']],
            ['WO', 'Diversifikasi pembayar ke asuransi & korporat', 'Paket korporat, kerja sama asuransi, program loyalitas.', 5, ['jkn']],
            ['WO', 'Halo Hermina 2.0 & telekonsultasi', 'Pemesanan, pembayaran, dan konsultasi dalam satu aplikasi.', 4, ['digital']],
            ['ST', 'RS pendidikan & retensi dokter spesialis', 'Kerja sama universitas untuk pasokan spesialis jangka panjang.', 5, ['dokter']],
            ['WT', 'Program efisiensi biaya & pengadaan terpusat', 'Konsolidasi pengadaan obat/alkes via PT MLM dan formularium standar.', 5, ['laba']],
            ['WT', 'Kepatuhan KRIS bertahap tanpa kehilangan kapasitas', 'Renovasi bergiliran & konversi kamar.', 5, ['kris']],
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
            'T1' => ['Pertumbuhan Jaringan & Utilisasi', 'Tumbuh sehat: ekspansi terukur, utilisasi kapasitas, diversifikasi pembayar.', '#1F4E8C'],
            'T2' => ['Keunggulan Klinis & Keselamatan Pasien', 'Mutu klinis, patient safety, dan pusat keunggulan.', '#2A8C9E'],
            'T3' => ['Transformasi Digital & Pengalaman Pasien', 'SIMRS, Halo Hermina, telekonsultasi, otomasi klaim.', '#5C5AA8'],
            'T4' => ['SDM Medis Unggul & Tata Kelola', 'Rekrutmen & retensi spesialis, Hermina Learning Center, budaya TRUST, GCG.', '#C88A12'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Ekspansi jaringan & kapasitas tempat tidur secara terukur', 'pasar', 'Prioritas Direksi 2026; RS Badung & perluasan RS eksisting.'],
            ['T1', 'Menaikkan porsi pasien asuransi swasta & korporat', 'pasar', 'Non-JKN 27,8% rawat jalan; prioritas 2026.'],
            ['T2', 'Pusat keunggulan onkologi, fertilitas & stroke', 'produk', 'Fokus layanan unggulan dalam Laporan Direksi.'],
            ['T2', 'Wasdal mutu klinis di seluruh RS', 'operasi', 'Pengawasan & pengendalian mutu sebagai fokus implementasi 2025.'],
            ['T3', 'Halo Hermina sebagai pintu masuk utama pasien', 'saluran', 'Akses & retensi pasien.'],
            ['T3', 'Otomasi klaim & administrasi', 'operasi', 'Efisiensi siklus pendapatan JKN.'],
            ['T4', 'RS pendidikan bersama universitas mitra', 'organisasi', '32 Sp-1 & 2 Sp-2 direkrut 2025 lewat kerja sama pendidikan.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Pertumbuhan pendapatan yang sehat', 'Pendapatan tumbuh ±10% dari volume, layanan unggulan & bauran pembayar.', 'cfo', 3],
            'F2' => ['keuangan', 'T1', 'Memulihkan profitabilitas', 'EBITDA & laba neto naik lewat efisiensi biaya dan utilisasi.', 'cfo', 3],
            'F3' => ['keuangan', 'T1', 'Struktur keuangan sehat & disiplin investasi', 'ROE naik, leverage terkendali, kas operasi kuat.', 'cfo', 2],
            'C1' => ['pelanggan', 'T1', 'Memperluas akses layanan kesehatan', 'Lebih banyak RS, tempat tidur, dan pasien terlayani.', 'pengembangan', 2],
            'C2' => ['pelanggan', 'T2', 'Pasien puas & loyal', 'Kepuasan dan rekomendasi pasien tinggi, keluhan rendah.', 'medis', 2],
            'C3' => ['pelanggan', 'T1', 'Diversifikasi pembayar', 'Porsi pasien asuransi swasta & korporat meningkat.', 'komersial', 2],
            'C4' => ['pelanggan', 'T2', 'Layanan unggulan menjadi pilihan rujukan', 'Pusat keunggulan onkologi, fertilitas, stroke tumbuh.', 'medis', 1.5],
            'P1' => ['proses', 'T2', 'Mutu klinis & keselamatan pasien', 'INM terpenuhi, KTD & infeksi rendah, clinical pathway dipatuhi.', 'medis', 2],
            'P2' => ['proses', 'T1', 'Utilisasi & alur layanan efisien', 'BOR optimal, ALOS terkendali, waktu tunggu pendek.', 'operasional', 2],
            'P3' => ['proses', 'T3', 'Siklus pendapatan & klaim efisien', 'Klaim JKN tepat waktu, piutang sehat.', 'cfo', 1.5],
            'P4' => ['proses', 'T2', 'Kepatuhan akreditasi & KRIS', 'Seluruh RS Paripurna dan ruang rawat inap sesuai KRIS.', 'operasional', 1.5],
            'P5' => ['proses', 'T3', 'Layanan digital terintegrasi', 'Pemesanan, pembayaran, dan konsultasi lewat Halo Hermina.', 'ti', 1],
            'L1' => ['sdm', 'T4', 'Ketersediaan & retensi dokter spesialis', 'Spesialis full timer cukup di setiap RS, turnover rendah.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Kompetensi SDM & RS pendidikan', 'Hermina Learning Center & sertifikasi kompetensi.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Budaya TRUST & keterlibatan karyawan', 'Keterlibatan tinggi, budaya patient safety tanpa saling menyalahkan.', 'sdm', 1],
            'L4' => ['sdm', 'T3', 'SIMRS andal & data pasien aman', 'Sistem tersedia dan kerentanan ditutup cepat.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L1', 'P2', 2], ['L2', 'P1', 3], ['L2', 'P4', 2], ['L3', 'P1', 2], ['L4', 'P5', 3], ['L4', 'P3', 2],
            ['P1', 'C2', 3], ['P1', 'C4', 2], ['P2', 'C1', 2], ['P2', 'C2', 2], ['P3', 'F3', 3], ['P4', 'C2', 2], ['P4', 'C1', 2], ['P5', 'C2', 2], ['P5', 'C3', 2],
            ['C1', 'F1', 3], ['C2', 'F1', 2], ['C3', 'F1', 2], ['C3', 'F2', 3], ['C4', 'F1', 2],
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
            ['F1.1', 'F1', 'Pendapatan neto', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Pendapatan neto konsolidasian', 'Laporan keuangan konsolidasian', 6717179, 7450000, 7133538, 7850000, .975, $lt],
            ['F1.2', 'F1', 'Pertumbuhan pendapatan (YoY, kumulatif)', '%', 'higher', 'last', 2, true, false, 'cfo', 'Pendapatan YTD ÷ pendapatan YTD tahun lalu − 1', 'Laporan keuangan', 16.13, 10.90, 6.20, 10.00, .88, $lt],
            ['F1.3', 'F1', 'Pendapatan non-rumah sakit', 'Rp juta', 'higher', 'sum', 0, true, false, 'pengembangan', 'Imbalan jasa, KSO alat kesehatan, jasa manajemen, pelatihan', 'Catatan pendapatan neto', 209652, 225000, 213713, 240000, .97, $lt],
            ['F2.1', 'F2', 'EBITDA', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba sebelum bunga, pajak, depresiasi & amortisasi', 'Laporan laba rugi', 1908000, 2080000, 1948000, 2120000, .965, $lt],
            ['F2.2', 'F2', 'Margin EBITDA', '%', 'higher', 'average', 1, true, true, 'cfo', 'EBITDA ÷ pendapatan neto × 100', 'Rasio keuangan', 28.4, 28.0, 27.3, 27.0, .99, $lt],
            ['F2.3', 'F2', 'Laba neto tahun berjalan', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Laba neto konsolidasian', 'Laporan laba rugi', 689000, 760000, 549000, 640000, .95, $lt],
            ['F3.1', 'F3', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'last', 1, true, false, 'cfo', 'Laba neto ÷ total ekuitas × 100 (disetahunkan)', 'Rasio keuangan', 11.7, 12.0, 7.6, 8.5, .95, $lt],
            ['F3.2', 'F3', 'Total pinjaman terhadap EBITDA', 'kali', 'lower', 'last', 1, true, false, 'cfo', 'Total pinjaman ÷ EBITDA 12 bulan', 'Rasio keuangan', 1.7, 1.8, 1.7, 1.6, 1.03, $lt],
            ['F3.3', 'F3', 'Kas neto dari aktivitas operasi', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas operasi', 'Laporan arus kas', 1138000, 1250000, 1138000, 1300000, .92, $lt],
            ['C1.1', 'C1', 'Rumah sakit beroperasi', 'unit', 'higher', 'last', 0, false, false, 'pengembangan', 'RS dengan izin operasional aktif', 'Data operasional', 51, 53, 52, 53, .985, $lt],
            ['C1.2', 'C1', 'Tempat tidur operasional', 'unit', 'higher', 'last', 0, false, false, 'pengembangan', 'Tempat tidur siap pakai di seluruh RS', 'Data operasional', 8252, 8800, 8666, 9100, .985, $lt],
            ['C1.3', 'C1', 'Jumlah pasien (rawat jalan + rawat inap)', 'ribu', 'higher', 'sum', 0, false, true, 'operasional', 'Kunjungan rawat jalan + pasien masuk rawat inap', 'SIMRS', 9518, 9900, 9713, 10200, .99, $lt],
            ['C1.4', 'C1', 'Pasien masuk rawat inap', 'ribu', 'higher', 'sum', 1, false, false, 'operasional', 'Pasien masuk rawat inap', 'SIMRS', 711.0, 745.0, 726.3, 765.0, .975, $lt],
            ['C2.1', 'C2', 'Indeks kepuasan pasien', 'skor', 'higher', 'last', 1, false, true, 'medis', 'Survei kepuasan pasien semesteran (skala 100; INM ≥ 76,61)', 'Survei pihak ketiga', 84.1, 85.0, 85.6, 86.5, 1.0, $il, 'semesteran'],
            ['C2.2', 'C2', 'Net Promoter Score pasien', 'skor', 'higher', 'average', 0, false, false, 'komersial', '% promotor − % detraktor (survei pasca-layanan)', 'Halo Hermina & survei SMS', 48, 50, 51, 55, .96, $il],
            ['C2.3', 'C2', 'Keluhan pasien per 10.000 kunjungan', 'rasio', 'lower', 'average', 2, false, false, 'medis', 'Keluhan tercatat ÷ kunjungan × 10.000', 'Unit pengaduan', 4.10, 3.80, 3.75, 3.40, 1.04, $il],
            ['C3.1', 'C3', 'Porsi kunjungan rawat jalan non-JKN', '%', 'higher', 'average', 1, false, false, 'komersial', 'Kunjungan non-JKN ÷ total kunjungan rawat jalan × 100', 'SIMRS', 28.5, 29.5, 27.8, 30.0, .97, $lt],
            ['C3.2', 'C3', 'Porsi pasien rawat inap non-JKN', '%', 'higher', 'average', 1, false, false, 'komersial', 'Pasien non-JKN ÷ total pasien rawat inap × 100', 'SIMRS', 21.8, 22.5, 21.0, 23.0, .955, $lt],
            ['C3.3', 'C3', 'Mitra asuransi & korporasi aktif', 'unit', 'higher', 'last', 0, false, false, 'komersial', 'Perjanjian kerja sama aktif dengan transaksi 3 bulan terakhir', 'Unit kemitraan', 1150, 1220, 1240, 1350, 1.0, $il],
            ['C4.1', 'C4', 'Pasien layanan unggulan (onkologi, fertilitas, stroke)', 'orang', 'higher', 'sum', 0, false, false, 'medis', 'Pasien unik layanan pusat keunggulan', 'SIMRS', 41200, 46000, 46500, 54000, 1.03, $il],
            ['C4.2', 'C4', 'Siklus program bayi tabung', 'kasus', 'higher', 'sum', 0, false, false, 'medis', 'Siklus IVF dimulai', 'Klinik fertilitas', 1320, 1450, 1480, 1700, .94, $il],
            ['P1.1', 'P1', 'Kejadian tidak diharapkan (KTD) per 1.000 pasien rawat inap', 'rasio', 'lower', 'average', 2, false, true, 'medis', 'KTD terlapor ÷ pasien rawat inap × 1.000', 'Sistem pelaporan insiden', 0.42, 0.40, 0.38, 0.33, 1.06, $il],
            ['P1.2', 'P1', 'Kepatuhan kebersihan tangan', '%', 'higher', 'average', 1, false, true, 'medis', 'Peluang kebersihan tangan dilakukan ÷ peluang diamati × 100 (INM ≥ 85%)', 'Komite PPI', 86.5, 88.0, 88.4, 90.0, .985, $il],
            ['P1.3', 'P1', 'Kepatuhan clinical pathway', '%', 'higher', 'average', 1, false, false, 'medis', 'Kasus sesuai clinical pathway ÷ kasus diaudit × 100 (INM ≥ 80%)', 'Komite medik', 84.0, 85.0, 86.5, 88.0, 1.0, $il],
            ['P1.4', 'P1', 'Angka infeksi daerah operasi (IDO)', '%', 'lower', 'average', 2, false, false, 'medis', 'Kasus IDO ÷ operasi bersih × 100', 'Komite PPI', 0.90, 0.82, 0.80, 0.70, 1.06, $il],
            ['P2.1', 'P2', 'Tingkat okupansi tempat tidur (BOR)', '%', 'higher', 'average', 1, false, true, 'operasional', 'Hari rawat ÷ (tempat tidur × hari) × 100', 'SIMRS', 72.9, 72.0, 69.8, 71.5, .985, $lt],
            ['P2.2', 'P2', 'Rata-rata lama rawat inap (ALOS)', 'hari', 'lower', 'average', 1, false, false, 'operasional', 'Hari rawat ÷ pasien keluar', 'SIMRS', 3.2, 3.1, 3.1, 3.1, 1.0, $lt],
            ['P2.3', 'P2', 'Waktu tunggu rawat jalan', 'menit', 'lower', 'average', 0, false, false, 'operasional', 'Rata-rata menit dari registrasi sampai dilayani dokter (INM ≤ 60 menit)', 'SIMRS antrean', 52, 48, 47, 42, 1.07, $il],
            ['P2.4', 'P2', 'Seksio sesarea emergensi ≤ 30 menit', '%', 'higher', 'average', 1, false, false, 'medis', 'Kasus SC emergensi ≤ 30 menit ÷ total kasus × 100 (INM ≥ 80%)', 'Kamar operasi', 88.0, 90.0, 91.2, 92.0, 1.0, $il],
            ['P3.1', 'P3', 'Hari penagihan piutang (DSO)', 'hari', 'lower', 'last', 1, true, false, 'cfo', 'Piutang usaha neto ÷ pendapatan × 365', 'Rasio keuangan', 63.2, 62.0, 67.2, 63.0, 1.04, $lt],
            ['P3.2', 'P3', 'Piutang jatuh tempo > 60 hari', '%', 'lower', 'last', 1, true, false, 'cfo', 'Piutang > 60 hari ÷ piutang usaha neto × 100', 'Umur piutang', 5.5, 5.0, 6.1, 5.0, 1.09, $lt],
            ['P3.3', 'P3', 'Klaim JKN pending & dispute', '%', 'lower', 'average', 1, true, true, 'cfo', 'Nilai klaim pending + dispute ÷ klaim diajukan × 100', 'Unit penjaminan', 4.2, 3.7, 3.6, 3.0, 1.3, $il],
            ['P4.1', 'P4', 'RS terakreditasi Paripurna', '%', 'higher', 'last', 1, false, true, 'medis', 'RS Paripurna ÷ RS beroperasi × 100', 'Komite mutu', 100, 100, 98.1, 100, .985, $lt],
            ['P4.2', 'P4', 'Tempat tidur rawat inap sesuai 12 kriteria KRIS', '%', 'higher', 'last', 1, false, false, 'operasional', 'TT memenuhi KRIS ÷ TT rawat inap JKN × 100', 'Tim KRIS', 45, 80, 78, 100, .90, $il],
            ['P5.1', 'P5', 'Janji temu rawat jalan melalui Halo Hermina', '%', 'higher', 'average', 1, false, false, 'ti', 'Janji temu via aplikasi ÷ total kunjungan rawat jalan × 100', 'Halo Hermina', 18.0, 25.0, 24.2, 35.0, .74, $il],
            ['P5.2', 'P5', 'Sesi telekonsultasi', 'kasus', 'higher', 'sum', 0, false, false, 'ti', 'Sesi konsultasi daring selesai', 'Halo Hermina', 38000, 50000, 52000, 70000, .90, $il],
            ['L1.1', 'L1', 'Dokter spesialis full timer', 'orang', 'higher', 'last', 0, false, false, 'sdm', 'Dokter spesialis berstatus full timer', 'HRIS', 541, 580, 574, 620, .975, $lt],
            ['L1.2', 'L1', 'Rekrutmen dokter spesialis & subspesialis baru', 'orang', 'higher', 'sum', 0, false, false, 'sdm', 'Sp-1 & Sp-2 baru bergabung', 'HRIS', 29, 33, 34, 40, 1.0, $lt],
            ['L1.3', 'L1', 'Turnover dokter spesialis full timer', '%', 'lower', 'sum', 2, false, false, 'sdm', 'Spesialis FT keluar ÷ rata-rata spesialis FT × 100', 'HRIS', 6.8, 6.2, 6.1, 5.5, 1.12, $il],
            ['L2.1', 'L2', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan Hermina Learning Center ÷ jumlah karyawan', 'HRIS', 20.0, 22.0, 22.4, 24.0, .97, $il],
            ['L2.2', 'L2', 'Tenaga kesehatan dengan sertifikat kompetensi wajib berlaku', '%', 'higher', 'last', 1, false, false, 'sdm', 'Nakes dengan BLS/ACLS/PPI dll. berlaku ÷ total nakes × 100', 'HRIS', 88.0, 90.0, 91.0, 95.0, .99, $il],
            ['L2.3', 'L2', 'Pendapatan per karyawan', 'Rp juta', 'higher', 'sum', 1, false, false, 'sdm', 'Pendapatan ÷ jumlah karyawan (18.730 per akhir 2025)', 'HRIS & keuangan', 372.0, 395.0, 380.9, 405.0, .975, $lt],
            ['L3.1', 'L3', 'Indeks keterlibatan karyawan', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterlibatan semesteran (skala 100)', 'Survei karyawan', 76, 78, 78, 80, 1.0, $il, 'semesteran'],
            ['L4.1', 'L4', 'Ketersediaan SIMRS', '%', 'higher', 'average', 2, false, true, 'ti', 'Uptime ÷ waktu layanan × 100', 'Monitoring TI', 99.40, 99.50, 99.62, 99.70, .999, $il],
            ['L4.2', 'L4', 'Kerentanan kritis tertutup ≤ 30 hari', '%', 'higher', 'average', 0, false, false, 'ti', 'Kerentanan kritis ditutup ≤ 30 hari ÷ total ditemukan × 100', 'Keamanan TI', 82, 88, 90, 95, .98, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => 'Hermina Tower, Kemayoran', 'participants' => 'Direksi, direktur RS regional, Perencanaan Strategis',
                'summary' => "Pendapatan Rp7.133,5 M (+6,2%; 95,8% target), EBITDA Rp1.948 M (margin 27,3%), laba neto Rp549,0 M (72% target).\n52 RS, 8.666 TT; 9,7 juta pasien; BOR 69,8% karena kapasitas baru belum terisi.\nMargin laba bruto turun ke 33,9% akibat biaya tenaga kerja & obat.\nRekrutmen 32 Sp-1 & 2 Sp-2 lewat kerja sama universitas.",
                'decisions' => "1. Target 2026: pendapatan Rp7,85 T, EBITDA Rp2,12 T, laba neto Rp640 M.\n2. Program efisiensi biaya & pengadaan terpusat via PT MLM.\n3. Prioritas: KRIS sebelum 30 Juni 2026, RS Badung, pusat keunggulan, Halo Hermina 2.0, diversifikasi pembayar.",
                'follow_up' => 'Perencanaan Strategis menurunkan KPI & target 2026 ke setiap RS di Ruci BSC paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta pemantauan bulanan atas margin laba bruto, BOR RS baru, dan kesiapan KRIS per RS.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => 'Hermina Tower, Kemayoran', 'participants' => 'Direksi, direktur RS regional, kepala divisi',
                'summary' => match ($m) {
                    1 => 'Kunjungan Januari tinggi karena musim hujan & DBD; BOR 73,1%.',
                    3 => 'Lebaran (20 Maret) menurunkan kunjungan elektif 9%; IGD tetap tinggi.',
                    6 => 'Tenggat KRIS 30 Juni: 86% tempat tidur sesuai; sisa di 7 RS masih renovasi. Semester I pendapatan 97,4% target.',
                    7 => 'Turnover dokter spesialis di 2 RS luar Jawa; adopsi Halo Hermina 26% (target 35%).',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Geser jadwal operasi elektif ke April; tambah jaga IGD selama libur.',
                    6 => 'Laporkan rencana penyelesaian KRIS ke BPJS & Dinkes; percepat renovasi gelombang 2.',
                    7 => 'Paket retensi spesialis luar Jawa; kampanye Halo Hermina di poliklinik.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => 'Hermina Tower, Kemayoran', 'participants' => 'Direksi, Dewan Komisaris (pengamat), Perencanaan Strategis',
                'summary' => $q === 1 ? 'Pendapatan Q1 97,6% target; margin EBITDA 27,1%; BOR 70,4%.' : 'Pendapatan H1 97,4% target; laba neto 95%; klaim JKN pending 4,5% (target 3,0%); Halo Hermina tertinggal.',
                'decisions' => $q === 1 ? 'Negosiasi harga obat & alkes terpusat lewat Mitra Member.' : 'Bentuk satgas klaim JKN; target pending ≤ 3% pada Desember.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => 'Hermina Tower, Kemayoran', 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Strategi & Manajemen Risiko',
            'summary' => 'Komisaris menyoroti KRIS yang belum tuntas, pemulihan margin, dan keamanan data pasien.',
            'decisions' => 'Direksi melaporkan progres KRIS per RS setiap bulan sampai 100%.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon analisis dampak KRIS terhadap kapasitas tempat tidur dan pendapatan rawat inap per RS untuk tinjauan Q3.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => 'Hermina Tower, Kemayoran', 'participants' => 'Direksi, direktur RS regional, kepala divisi',
            'summary' => 'Realisasi September sebagian masih draf; porsi rawat jalan non-JKN dikembalikan untuk koreksi data.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Bagian dari belanja modal 2025 Rp1.787 M (laporan tahunan); rincian per item ilustratif.';

            return [
                'defs' => [
                    'salatiga' => ['HMN-25-01', 'Pembangunan RS Hermina Salatiga', 'Konstruksi, perizinan, dan persiapan operasional RS baru.', 'T1', 'pengembangan', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 5, ['C1', 'F1'], 'RS ke-52 beroperasi akhir 2025.'],
                    'hlc' => ['HMN-25-02', 'Hermina Learning Center & RS pendidikan', 'Kerja sama dengan UI, Unpad, Unair, Unhas, dan lainnya.', 'T4', 'sdm', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['L1', 'L2'], '32 Sp-1, 2 Sp-2, dan 11 tenaga medis lain direkrut.'],
                    'simrs' => ['HMN-25-03', 'SIMRS terintegrasi gelombang 1', 'Implementasi sistem informasi RS di RS prioritas.', 'T3', 'ti', '2025-02-01', '2025-12-15', 'selesai', 4, 4, 3, 3, ['L4', 'P3'], 'SIMRS terintegrasi di RS prioritas.'],
                    'kemayoran' => ['HMN-25-04', 'Layanan bernilai tambah RS Hermina Kemayoran', 'Robotic Rehabilitation, Wellness Clinic, dan Sport Center.', 'T2', 'medis', '2025-03-01', '2025-12-31', 'selesai', 3, 3, 2, 3, ['C4'], 'Diferensiasi layanan & pengalaman pasien.'],
                    'kris1' => ['HMN-25-05', 'Kesiapan KRIS tahap 1', 'Gap assessment 12 kriteria & renovasi RS prioritas.', 'T2', 'operasional', '2025-04-01', '2025-12-31', 'selesai', 5, 5, 3, 4, ['P4'], '78% tempat tidur sesuai KRIS.'],
                ],
                'activities' => [
                    ['salatiga', 'Konstruksi & MEP', 'pengembangan', '2025-01-01', '2025-09-30', 100], ['salatiga', 'Perizinan & rekrutmen', 'pengembangan', '2025-07-01', '2025-12-15', 100],
                    ['hlc', 'Perjanjian kerja sama universitas', 'sdm', '2025-01-01', '2025-06-30', 100], ['hlc', 'Program pelatihan klinis & manajerial', 'sdm', '2025-02-01', '2025-12-31', 100],
                    ['simrs', 'Implementasi SIMRS RS prioritas', 'ti', '2025-02-01', '2025-12-15', 100],
                    ['kemayoran', 'Robotic Rehabilitation & Wellness Clinic', 'medis', '2025-03-01', '2025-12-31', 100],
                    ['kris1', 'Gap assessment & renovasi gelombang 1', 'operasional', '2025-04-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['salatiga', 'RS Hermina Salatiga beroperasi', '2025-12-15', true], ['hlc', 'RS pendidikan berjalan di 4 RS', '2025-12-31', true], ['kris1', 'Gap assessment 52 RS selesai', '2025-06-30', true],
                ],
                'budgets' => [
                    ['salatiga', 'Konstruksi & alat medis RS Salatiga', 'capex', 350_000_000_000, 338_000_000_000, $note, 'Kas internal & pinjaman bank'],
                    ['kris1', 'Renovasi ruang rawat inap gelombang 1', 'capex', 60_000_000_000, 48_500_000_000, $note, 'Kas internal'],
                    ['simrs', 'Lisensi & implementasi SIMRS', 'capex', 35_000_000_000, 33_200_000_000, $note, 'Kas internal'],
                    ['kemayoran', 'Peralatan rehabilitasi robotik', 'capex', 85_000_000_000, 80_400_000_000, $note, 'Kas internal'],
                    ['hlc', 'Program pendidikan & pelatihan', 'opex', 20_000_000_000, 19_100_000_000, null],
                ],
            ];
        }

        return [
            'defs' => [
                'badung' => ['HMN-26-01', 'Pembukaan RS Hermina Badung (Bali)', 'Penyelesaian pembangunan, alat medis, perizinan, dan rekrutmen SDM RS ke-53.', 'T1', 'pengembangan', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 4, 5, ['C1', 'F1'], 'RS ke-53 beroperasi Q4 2026.'],
                'kris' => ['HMN-26-02', 'Pemenuhan KRIS di seluruh RS mitra BPJS', 'Renovasi ruang rawat inap gelombang 2 & penyesuaian sistem.', 'T2', 'operasional', '2026-01-01', '2026-06-30', 'berjalan', 5, 5, 4, 4, ['P4', 'C1'], '100% tempat tidur JKN sesuai 12 kriteria KRIS.'],
                'app' => ['HMN-26-03', 'Halo Hermina 2.0', 'Jadwal dokter real-time, pembayaran digital, e-resep, telekonsultasi terintegrasi.', 'T3', 'ti', '2026-02-01', '2026-11-30', 'berjalan', 4, 4, 3, 3, ['P5', 'C2'], '35% janji temu rawat jalan via aplikasi.'],
                'coe' => ['HMN-26-04', 'Pusat keunggulan onkologi, fertilitas & stroke', 'Stroke center, layanan kemoterapi, dan klinik fertilitas di RS hub.', 'T2', 'medis', '2026-01-15', '2027-06-30', 'berjalan', 5, 4, 4, 5, ['C4', 'F1'], '54 ribu pasien layanan unggulan per tahun.'],
                'payer' => ['HMN-26-05', 'Akselerasi pasien asuransi & korporat', 'Paket korporat, kerja sama asuransi baru, respons co-payment, program loyalitas.', 'T1', 'komersial', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C3', 'F2'], 'Porsi rawat jalan non-JKN 30%.'],
                'dokter' => ['HMN-26-06', 'Rekrutmen & retensi dokter spesialis', 'RS pendidikan, skema remunerasi, fellowship subspesialis, penempatan RS baru.', 'T4', 'sdm', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 3, 3, ['L1', 'L2'], '620 spesialis full timer; turnover ≤ 5,5%.'],
                'klaim' => ['HMN-26-07', 'SIMRS gelombang 2 & e-klaim otomatis', 'SIMRS di 20 RS tambahan; bridging V-Claim & koding otomatis.', 'T3', 'ti', '2026-02-01', '2026-12-31', 'berjalan', 4, 4, 3, 4, ['P3', 'L4'], 'Klaim pending ≤ 3%; DSO ≤ 63 hari.'],
                'cyber' => ['HMN-26-08', 'Keamanan siber & pelindungan data pasien', 'Kebijakan PDP, enkripsi, MFA, SOC, uji pemulihan bencana.', 'T3', 'ti', '2026-03-01', '2026-12-31', 'berjalan', 4, 4, 3, 3, ['L4'], 'Patuh UU PDP; tidak ada insiden kebocoran data.'],
                'efisiensi' => ['HMN-26-09', 'Efisiensi biaya & pengadaan terpusat (PT MLM)', 'Mitra Member, formularium standar, efisiensi energi RS.', 'T1', 'cfo', '2026-01-15', '2026-12-31', 'berjalan', 5, 5, 2, 2, ['F2', 'P2'], 'Margin laba bruto kembali ≥ 34%.'],
                'rakb' => ['HMN-26-10', 'Rencana Aksi Keberlanjutan: energi & limbah medis', 'Baseline emisi & limbah, target lima tahun, pelaporan terukur.', 'T4', 'operasional', '2026-09-01', '2027-08-31', 'direncanakan', 3, 2, 2, 2, ['P4'], 'Baseline & target RAKB disetujui Direksi.'],
            ],
            'activities' => [
                ['badung', 'Konstruksi & MEP', 'pengembangan', '2026-01-01', '2026-06-30', 100], ['badung', 'Pengadaan & instalasi alat medis', 'pengembangan', '2026-03-01', '2026-08-31', 100],
                ['badung', 'Rekrutmen & orientasi SDM', 'sdm', '2026-05-01', '2026-10-31', 95], ['badung', 'Izin operasional & soft opening', 'pengembangan', '2026-09-01', '2026-12-31', 60],
                ['kris', 'Gap assessment ulang 52 RS', 'operasional', '2026-01-01', '2026-02-28', 100], ['kris', 'Renovasi gelombang 2 (24 RS)', 'operasional', '2026-02-01', '2026-05-31', 100],
                ['kris', 'Renovasi gelombang 3 (7 RS)', 'operasional', '2026-04-01', '2026-06-30', 70], ['kris', 'Penyesuaian tarif kamar & SIMRS', 'ti', '2026-03-01', '2026-06-30', 75],
                ['app', 'Jadwal dokter real-time & antrean daring', 'ti', '2026-02-01', '2026-05-31', 100], ['app', 'Pembayaran digital & e-resep', 'ti', '2026-04-01', '2026-09-30', 60],
                ['app', 'Telekonsultasi terintegrasi rekam medis', 'ti', '2026-05-01', '2026-10-31', 50], ['app', 'Kampanye adopsi di poliklinik', 'komersial', '2026-06-01', '2026-11-30', 30],
                ['coe', 'Stroke center RS Kemayoran & Depok', 'medis', '2026-01-15', '2026-06-30', 100], ['coe', 'Layanan kemoterapi di 3 RS', 'medis', '2026-03-01', '2026-12-31', 60],
                ['coe', 'Klinik fertilitas RS Bekasi', 'medis', '2026-06-01', '2027-03-31', 30], ['coe', 'Akreditasi layanan unggulan', 'medis', '2027-01-01', '2027-06-30', 0],
                ['payer', 'Paket korporat & medical check-up', 'komersial', '2026-01-01', '2026-04-30', 100], ['payer', 'Kerja sama asuransi baru', 'komersial', '2026-02-01', '2026-12-31', 85],
                ['payer', 'Paket & edukasi pasien terkait co-payment', 'komersial', '2026-01-15', '2026-09-30', 100], ['payer', 'Program loyalitas & kemitraan e-commerce', 'komersial', '2026-03-01', '2026-12-31', 75],
                ['dokter', 'Rekrutmen lewat RS pendidikan', 'sdm', '2026-01-01', '2026-12-31', 85], ['dokter', 'Skema remunerasi & retensi', 'sdm', '2026-02-01', '2026-08-31', 100],
                ['dokter', 'Fellowship subspesialis', 'sdm', '2026-03-01', '2026-12-31', 70], ['dokter', 'Penempatan dokter RS Badung', 'sdm', '2026-06-01', '2026-11-30', 90],
                ['klaim', 'SIMRS 10 RS tahap 1', 'ti', '2026-02-01', '2026-06-30', 100], ['klaim', 'Bridging V-Claim & koding otomatis', 'ti', '2026-03-01', '2026-10-31', 70],
                ['klaim', 'Dasbor klaim pending per RS', 'cfo', '2026-04-01', '2026-08-31', 100], ['klaim', 'SIMRS 10 RS tahap 2', 'ti', '2026-07-01', '2026-12-31', 60],
                ['cyber', 'Kebijakan PDP & penunjukan DPO', 'ti', '2026-03-01', '2026-04-30', 100], ['cyber', 'Enkripsi & MFA', 'ti', '2026-04-01', '2026-08-31', 100],
                ['cyber', 'SOC terkelola 24/7', 'ti', '2026-05-01', '2026-10-31', 70], ['cyber', 'Uji pemulihan bencana SIMRS', 'ti', '2026-09-01', '2026-12-31', 40],
                ['efisiensi', 'Konsolidasi pengadaan obat via Mitra Member', 'cfo', '2026-01-15', '2026-09-30', 100], ['efisiensi', 'Standardisasi formularium', 'medis', '2026-02-01', '2026-10-31', 85],
                ['efisiensi', 'Efisiensi energi (chiller & LED)', 'operasional', '2026-03-01', '2026-12-31', 70],
                ['rakb', 'Baseline emisi & limbah medis', 'operasional', '2026-09-01', '2026-12-31', 15],
            ],
            'milestones' => [
                ['badung', 'Konstruksi selesai', '2026-06-30', true], ['badung', 'Izin operasional terbit', '2026-10-15', true], ['badung', 'Soft opening', '2026-12-15', false],
                ['kris', '100% TT sesuai KRIS', '2026-06-30', false], ['kris', 'Laporan kepatuhan ke BPJS', '2026-07-15', true],
                ['app', 'Pembayaran digital live', '2026-09-30', false], ['coe', 'Stroke center beroperasi', '2026-06-30', true],
                ['klaim', 'Bridging V-Claim live 10 RS', '2026-08-31', true], ['cyber', 'DPO ditunjuk', '2026-04-30', true],
                ['efisiensi', 'Kontrak pengadaan terpusat 200 SKU obat', '2026-06-30', true], ['dokter', 'Remunerasi baru berlaku', '2026-08-31', true],
            ],
            'budgets' => [
                ['badung', 'Konstruksi RS Badung', 'capex', 420_000_000_000, 352_000_000_000, null, 'Kas internal & pinjaman bank'],
                ['badung', 'Alat medis RS Badung', 'capex', 160_000_000_000, 121_000_000_000, null, 'Kas internal & pinjaman bank'],
                ['kris', 'Renovasi ruang rawat inap', 'capex', 95_000_000_000, 82_500_000_000, null, 'Kas internal'],
                ['app', 'Pengembangan Halo Hermina 2.0', 'capex', 18_000_000_000, 9_400_000_000, null, 'Kas internal'],
                ['coe', 'Peralatan stroke center, kemoterapi & fertilitas', 'capex', 210_000_000_000, 96_000_000_000, null, 'Kas internal & pinjaman bank'],
                ['payer', 'Pemasaran korporat & asuransi', 'opex', 6_000_000_000, 4_300_000_000, null],
                ['dokter', 'Insentif retensi & fellowship', 'opex', 24_000_000_000, 17_200_000_000, null],
                ['klaim', 'SIMRS 20 RS & bridging', 'capex', 42_000_000_000, 26_500_000_000, null, 'Kas internal'],
                ['cyber', 'SOC terkelola & DRP', 'opex', 15_000_000_000, 9_100_000_000, null],
                ['efisiensi', 'Konsultan pengadaan & energi', 'opex', 3_000_000_000, 2_400_000_000, null],
                ['rakb', 'Kajian baseline RAKB', 'opex', 2_000_000_000, 0, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Laba neto turun 20% meski pendapatan naik', 'Laba neto Rp549,0 M vs Rp689 M (2024); margin laba bruto 33,9% vs 36,2%.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', 140000000000, 'F2.3', null, 'komisaris', '2026-02-24',
                [['Program efisiensi biaya & pengadaan terpusat 2026', 'cfo', '2026-12-31', 'berjalan', 60]], null],
            ['Format pelaporan insiden keselamatan pasien belum seragam', 'Beberapa RS masih melaporkan insiden secara manual sehingga angka KTD tidak dapat dibandingkan.', 'audit', null, 'proses', 'sedang', null, 'P1.1', 'simrs', 'medis', '2025-08-20',
                [['Pelaporan insiden elektronik di SIMRS', 'ti', '2025-11-15', 'selesai', 100]], ['2025-11-28', 'Pelaporan insiden elektronik seragam di seluruh RS.']],
        ] : [
            ['KRIS belum 100% pada tenggat 30 Juni 2026', '86% tempat tidur JKN sesuai 12 kriteria; 7 RS masih renovasi.', 'timwas', 'boc', 'proses', 'kritis', null, 'P4.2', 'kris', 'komisaris', '2026-07-28',
                [['Laporan rencana penyelesaian ke BPJS & Dinkes', 'operasional', '2026-07-15', 'selesai', 100], ['Percepatan renovasi gelombang 3', 'operasional', '2026-11-30', 'berjalan', 70]], null],
            ['Adopsi Halo Hermina di bawah target', 'Janji temu via aplikasi 26% vs target 35%; pembayaran digital belum live.', 'tinjauan', 'q2', 'pelanggan', 'tinggi', null, 'P5.1', 'app', 'dirut', '2026-07-22',
                [['Kampanye adopsi di poliklinik', 'komersial', '2026-11-30', 'berjalan', 30], ['Prioritaskan rilis pembayaran digital', 'ti', '2026-10-31', 'berjalan', 60]], null],
            ['Klaim JKN pending & dispute meningkat', 'Pending + dispute 4,5% nilai klaim pada Q2 (sekitar Rp18,5 M) karena koding & kelengkapan berkas.', 'audit', null, 'keuangan', 'tinggi', 18500000000, 'P3.3', 'klaim', 'cfo', '2026-08-12',
                [['Satgas klaim JKN & pelatihan koder', 'cfo', '2026-09-15', 'selesai', 100], ['Koding otomatis di SIMRS', 'ti', '2026-10-31', 'berjalan', 70]], null],
            ['Kepatuhan kebersihan tangan 4 RS di bawah 85%', 'Audit PPI Mei 2026: 4 RS di bawah standar INM.', 'audit', null, 'proses', 'sedang', null, 'P1.2', null, 'medis', '2026-06-05',
                [['Audit mendadak & umpan balik harian', 'medis', '2026-07-31', 'selesai', 100], ['Tambah dispenser handrub di ruang rawat', 'operasional', '2026-07-15', 'selesai', 100]], null],
            ['Turnover dokter spesialis di RS luar Jawa', '3 spesialis full timer keluar dari 2 RS luar Jawa dalam satu kuartal.', 'internal', 'rm7', 'sdm', 'sedang', null, 'L1.3', 'dokter', 'sdm', '2026-08-12',
                [['Paket retensi spesialis luar Jawa', 'sdm', '2026-10-31', 'berjalan', 50]], null],
            ['Keluhan waktu tunggu farmasi rawat jalan', 'Keluhan terbanyak Q2 adalah antrean farmasi > 45 menit di RS dengan kunjungan tinggi.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'P2.3', 'app', 'medis', '2026-07-22',
                [['E-resep & penyiapan obat sebelum pasien tiba', 'ti', '2026-10-31', 'berjalan', 60]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Juknis KRIS dari Kemenkes & BPJS', 'eksternal', 'kris', 'P4', 'Kementerian Kesehatan & BPJS Kesehatan', 'Ketentuan teknis turunan menentukan bentuk akhir renovasi.', 'operasional', 'tinggi', 'Desain mengikuti kriteria paling ketat; koordinasi berkala.'],
                ['Izin operasional RS Badung', 'eksternal', 'badung', 'C1', 'Pemerintah Kabupaten Badung & Dinkes', 'Soft opening bergantung pada izin operasional.', 'pengembangan', 'tinggi', 'Pendampingan perizinan & simulasi akreditasi lebih awal.'],
                ['Bridging V-Claim BPJS', 'eksternal', 'klaim', 'P3', 'BPJS Kesehatan', 'Koding otomatis membutuhkan integrasi API V-Claim.', 'ti', 'sedang', 'Uji integrasi bertahap per RS.'],
                ['Dokter spesialis untuk RS Badung', 'internal', 'badung', 'L1', 'Program rekrutmen dokter spesialis', 'RS baru membutuhkan minimal 4 spesialis dasar full timer.', 'sdm', 'tinggi', 'Rotasi sementara dari RS Bali & Jawa Timur.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat Komite Mutu & Keselamatan Pasien', 'rapat', 'Membahas INM, insiden keselamatan, dan rencana perbaikan per RS.', 'Bulanan', 'medis', 'melembaga'],
                ['Pengawasan & Pengendalian (Wasdal) mutu klinis', 'sop', 'Audit klinis berkala & umpan balik ke komite medik.', 'Bulanan', 'medis', 'berjalan'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Tinjauan kinerja & risiko bersama Komisaris dan komite.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Hermina Learning Center', 'pelatihan', 'Kurikulum klinis & manajerial terstruktur untuk seluruh RS.', 'Berkelanjutan', 'sdm', 'melembaga'],
                ['Budaya patient safety tanpa saling menyalahkan', 'budaya', 'Penghargaan RS dengan pelaporan insiden terbaik setiap kuartal.', 'Kuartalan', 'medis', $year === 2025 ? 'rencana' : 'berjalan'],
            ],
        ];
    }
}
