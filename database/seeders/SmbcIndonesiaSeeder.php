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
 * Demo PT Bank SMBC Indonesia Tbk (kode saham BTPN) — bank universal anggota Grup SMBC, induk konglomerasi keuangan
 * dengan anak usaha BTPN Syariah, OTO Multiartha, dan Summit Oto Finance.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025 & laporan keuangan
 *   konsolidasian 2025 (laba, NII, kredit per segmen, DPK & CASA, rasio CAR/NPL/ROE/NIM/BOPO/CIR/LDR, Jenius, SDM).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI.
 * - Kasus belajar utama: pendapatan tumbuh, tetapi laba anjlok karena biaya kredit (CKPN) melonjak di anak usaha
 *   pembiayaan — strategi pemulihan kualitas aset di bawah peran baru sebagai induk konglomerasi (PIKK).
 */
class SmbcIndonesiaSeeder extends ShowcaseSeeder
{
    public const CODE = 'BTPN';

    public const LT = 'Laporan Tahunan 2025 PT Bank SMBC Indonesia Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Bank SMBC Indonesia Tbk', 'legal_name' => 'PT Bank SMBC Indonesia Tbk',
            'industry' => 'Perbankan universal: korporasi, ritel digital (Jenius), syariah, pembiayaan kendaraan',
            'address' => 'Menara SMBC, Jl. Dr. Ide Anak Agung Gde Agung Kav. 5.5–5.6, Kawasan Mega Kuningan', 'city' => 'Jakarta Selatan',
            'phone' => '+62 21 300 26 200', 'website' => 'https://www.smbci.com',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham BTPN). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025 dan laporan keuangan konsolidasian 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Pendapatan bank relatif merata; penyaluran kendaraan & transaksi ritel naik menjelang Lebaran dan akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.0, 7.8, 8.6, 8.3, 8.2, 8.2, 8.3, 8.3, 8.2, 8.5, 8.5, 9.1]),
            2026 => self::weights([1 => 8.0, 8.4, 8.6, 8.0, 8.2, 8.2, 8.3, 8.3, 8.2, 8.5, 8.4, 9.0]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['F1.3', 'F2.3', 'F3.1', 'F3.2', 'C1.3', 'C2.1', 'C2.2', 'C4.1', 'C4.3', 'P3.1', 'P4.1', 'L2.1', 'L3.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.1', 'L1.1'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['P1.2', 'Cost of credit September belum memasukkan CKPN Grup OTO setelah kalibrasi ulang model ECL. Mohon perbarui dengan angka konsolidasi final dari Manajemen Risiko.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P1.3'];
    }

    protected function maturityPattern(): array
    {
        return [3, 3, 3, 3, 2];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin SMBC Indonesia (contoh)', 'btpn.admin', 'Admin Sistem', 'Strategi & Perencanaan', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'btpn.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan (contoh)', 'btpn.keuangan', 'Direktur Keuangan', 'Keuangan & Perencanaan', ['keuangan']],
            'wholesale' => ['Direktur Wholesale & Korporasi (contoh)', 'btpn.wholesale', 'Direktur Wholesale, Komersial & Transaction Banking', 'Wholesale & Korporasi', ['kepala-unit']],
            'ritel' => ['Direktur Ritel & Jenius (contoh)', 'btpn.ritel', 'Direktur Retail Banking & Jenius', 'Ritel & Digital Banking', ['kepala-unit']],
            'grup' => ['Kepala Divisi Konglomerasi Keuangan (contoh)', 'btpn.grup', 'Kepala Divisi Konglomerasi Keuangan', 'Konglomerasi & Anak Usaha', ['kepala-unit']],
            'risiko' => ['Direktur Manajemen Risiko (contoh)', 'btpn.risiko', 'Direktur Manajemen Risiko', 'Manajemen Risiko', ['kepala-unit']],
            'ops' => ['Direktur Operasional & Teknologi (contoh)', 'btpn.operasi', 'Direktur Operasional & Teknologi', 'Operasional & Teknologi', ['kepala-unit']],
            'kepatuhan' => ['Direktur Kepatuhan (contoh)', 'btpn.kepatuhan', 'Direktur Kepatuhan', 'Kepatuhan & Hukum', ['kepala-unit']],
            'sdm' => ['Kepala Human Resources (contoh)', 'btpn.sdm', 'Head of Human Resources', 'Human Resources', ['kepala-unit', 'editor-jatidiri']],
            'strategi' => ['Strategi & Perencanaan (contoh)', 'btpn.strategi', 'Kepala Divisi Strategi & Perencanaan', 'Strategi & Perencanaan', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'btpn.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'btpn.analis', 'Performance Analyst', 'Strategi & Perencanaan', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi bank pilihan utama di Indonesia, yang dapat memberikan perubahan berarti dalam kehidupan jutaan orang, terutama dengan dukungan teknologi digital.',
            'mission' => "1. Menawarkan solusi dan layanan keuangan yang lengkap ke berbagai segmen ritel, mikro, UKM, dan bisnis korporasi di Indonesia.\n2. Memberikan kesempatan berharga bagi pertumbuhan profesional karyawan.\n3. Menciptakan nilai yang signifikan dan berkesinambungan bagi stakeholder termasuk masyarakat Indonesia.\n4. Memanfaatkan inovasi teknologi sebagai pembeda utama untuk memberikan kualitas dan pengalaman terbaik di kelasnya.\n5. Menjadi bank universal yang unik yang berkontribusi terhadap pertumbuhan perekonomian Indonesia.",
            'purpose' => 'Menciptakan pertumbuhan yang bermakna bagi jutaan orang melalui solusi keuangan yang inovatif, didukung jaringan global SMBC.',
            'tagline' => 'Bank universal dengan jaringan global SMBC',
            'brand_promise' => 'Solusi keuangan lengkap — korporasi, Jenius, Sinaya, syariah, dan pembiayaan kendaraan — dalam satu grup.',
            'value_proposition' => '6,1 juta nasabah bank, 3,8 juta nasabah BTPN Syariah, 1,3 juta nasabah OTO & SOF; Jenius 6,3 juta nasabah; jaringan global Sumitomo Mitsui Banking Corporation.',
            'culture_statement' => 'Nilai utama: Integrity, Customer First, Proactive & Innovative, Speed & Quality, Synergy.',
            'history' => "1958: berdiri sebagai Bank Pegawai Pensiunan Militer (Bapemil).\n1985: menjadi PT Bank Tabungan Pensiunan Nasional.\n2008: tercatat di Bursa Efek Indonesia (BTPN).\n2011: meluncurkan program pemberdayaan Daya.\n2016: meluncurkan Jenius.\n2019: merger dengan PT Bank Sumitomo Mitsui Indonesia; menjadi PT Bank BTPN Tbk.\n2024: berganti nama menjadi PT Bank SMBC Indonesia Tbk; mengonsolidasikan Grup OTO.\n2025: ditunjuk OJK sebagai Perusahaan Induk Konglomerasi Keuangan (PIKK) Grup SMBC.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Integrity', 'Integritas', 'Sebagai profesional selalu berlaku secara tulus dengan standar etika yang tinggi.', "Menolak gratifikasi\nMelaporkan pelanggaran"],
            ['Customer First', 'Nasabah yang utama', 'Selalu melihat dari perspektif nasabah untuk memberikan nilai lebih.', "Merancang produk dari kebutuhan nasabah\nMenuntaskan pengaduan"],
            ['Proactive & Innovative', 'Proaktif & inovatif', 'Berani bertindak dan berinovasi tanpa henti.', "Mengusulkan perbaikan proses\nMencoba fitur baru Jenius"],
            ['Speed & Quality', 'Cepat & berkualitas', 'Kecepatan dan kualitas keputusan serta layanan sebagai pembeda.', "Keputusan kredit tepat waktu\nTepat sejak awal"],
            ['Synergy', 'Sinergi', 'Berkolaborasi sebagai satu tim dalam konglomerasi keuangan SMBC Indonesia.', "Kolaborasi bank–anak usaha\nBerbagi pengetahuan"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Jaringan global & nasabah Jepang', 'Kompetensi', 'Dukungan SMBC; Corporate Banking Japanese Rp30,5 T.', 5, 5, true, 'Perluas ke rantai pasok nasabah Jepang.'],
            ['Platform digital Jenius', 'Sumber daya teknologi', '6,3 juta nasabah; transaksi in-app Rp287 T.', 4, 5, true, 'Monetisasi lewat kredit & wealth.'],
            ['Permodalan kuat', 'Sumber daya keuangan', 'CAR 29,3%; LCR 229%.', 5, 5, true, 'Gunakan untuk pertumbuhan berkualitas.'],
            ['Manajemen risiko kredit konsumer & pembiayaan', 'Sistem', 'Biaya kredit melonjak di Grup OTO.', 2 + $lvl, 4, false, 'Model skor, early warning, penagihan.'],
            ['Tata kelola konglomerasi', 'Sistem', 'Peran PIKK baru sejak pertengahan 2025.', 2 + $lvl, 4, false, 'Kerangka risiko & kepatuhan terintegrasi.'],
            ['Pendanaan murah (CASA)', 'Sistem', 'CASA 41%; LDR 140% bergantung pinjaman.', 2 + $lvl, 4, false, 'Jenius & cash management.'],
            ['Efisiensi operasional', 'Sistem', 'CIR 54,3%.', 3, 4, false, 'Digitalisasi & process excellence.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi ekonomi & perbankan 2025', '2026-01-20', 'eksternal', self::LT.' — Tinjauan Industri Perbankan',
                'Ekonomi tumbuh ±5%; BI Rate 4,75% di akhir 2025; daya beli melemah; penjualan kendaraan turun; ketidakpastian tarif AS & geopolitik.',
                'Risiko kredit konsumer & pembiayaan kendaraan naik; korporasi tetap tumbuh.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Pendapatan operasional Rp18,4 T (+6%); NII Rp15,9 T (+5%); NIM 7,0%; CKPN Rp8,0 T (2024: Rp3,9 T); laba diatribusikan Rp506 M (2024: Rp2,8 T); CAR 29,3%; NPL 2,59%; BOPO 97,7%; CASA 41%.',
                'Pendapatan sehat, laba tergerus biaya kredit — prioritas 2026 adalah kualitas aset.'],
            ['Arah 2026', '2026-02-05', 'internal', self::LT.' — Prospek Usaha',
                'Fokus 2026: kredit tumbuh dengan efisiensi biaya kredit & NPL terjaga; basis ritel lewat Jenius & wealth; CASA & fee (forex, trade, cash management); sinergi anak usaha & Grup SMBC; TI, keamanan siber, dan produktivitas SDM.',
                'Target 2026 dalam demo ini disusun dari arah tersebut (simulasi).'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['ekonomi', 'Daya beli & penjualan kendaraan melemah', 'Kualitas piutang pembiayaan OTO & SOF tertekan.', 'ancaman', 5, 4, 'stabil', 'Seleksi debitur & penagihan lebih ketat.'],
            ['ekonomi', 'Penurunan suku bunga', 'Biaya dana turun (4,4% → 4,1%), yield kredit juga turun.', 'peluang', 3, 4, 'naik', 'Jaga NIM lewat CASA.'],
            ['politik', 'Tarif resiprokal AS & geopolitik', 'Nasabah eksportir & rantai pasok terdampak.', 'ancaman', 3, 3, 'naik', 'Trade finance & lindung nilai valas.'],
            ['sosial', 'Nasabah digital savvy', 'Pengguna mengharapkan bank sepenuhnya di aplikasi.', 'peluang', 4, 5, 'naik', 'Jenius sebagai platform ritel.'],
            ['sosial', 'Penipuan & rekayasa sosial', 'Modus penipuan terhadap nasabah digital meningkat.', 'ancaman', 4, 4, 'naik', 'Deteksi fraud & edukasi.'],
            ['teknologi', 'Analitik & AI risiko kredit', 'Penilaian risiko lebih presisi.', 'peluang', 4, 4, 'naik', 'Model skor & early warning.'],
            ['lingkungan', 'Keuangan berkelanjutan', 'Pembiayaan hijau sindikasi & transisi.', 'peluang', 3, 4, 'naik', 'Green loan bersama Grup SMBC.'],
            ['hukum', 'Konglomerasi keuangan (PIKK)', 'Tata kelola, risiko, dan permodalan terintegrasi.', 'ancaman', 4, 5, 'naik', 'Kerangka PIKK di seluruh entitas.'],
            ['hukum', 'Ketahanan siber & PDP', 'POJK keamanan siber & UU PDP.', 'ancaman', 4, 4, 'naik', 'XDR, WAF, program PDP.'],
            ['hukum', 'Rasio Pembiayaan Inklusif Makroprudensial', 'Kewajiban RPIM.', 'peluang', 2, 4, 'stabil', 'RPIM 30,8%.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Bank BUMN & bank swasta besar', 'Persaingan dana murah & kredit korporasi.', 4, 'Keunggulan nasabah Jepang & wholesale.'],
            ['kekuatan_pembeli', 'Nasabah korporasi besar', 'Daya tawar tinggi untuk suku bunga.', 4, 'Solusi trade & cash management.'],
            ['kekuatan_pemasok', 'Deposan & kreditur', 'LDR 140% butuh pinjaman & obligasi.', 4, 'Naikkan CASA, kurangi ketergantungan pinjaman.'],
            ['pendatang_baru', 'Bank digital', 'Bunga tabungan tinggi & akuisisi cepat.', 4, 'Jenius: fitur & ekosistem.'],
            ['produk_pengganti', 'Paylater & fintech lending', 'Kredit kecil beralih ke platform.', 3, 'Kredit Jenius & kemitraan.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Korporasi & komersial (termasuk Jepang)', 'Kredit', 'Rp105,4 T (+6,5%); Japanese Rp30,5 T, Wholesale Rp68,0 T.', 57, 'naik', true, 'Pembiayaan besar, trade, cash management.', 'Sinergi jaringan global SMBC.'],
            ['Pembiayaan kendaraan (OTO & SOF)', 'Kredit', 'Piutang Rp30,4 T (+3,2%).', 16, 'stabil', true, 'Proses cepat di dealer.', 'Kualitas debitur & penagihan.'],
            ['Pensiun & mikro', 'Kredit', 'Pensiun Rp20,1 T (−8,5%); mikro Rp1,7 T.', 12, 'turun', false, 'Cicilan terjangkau.', 'Transformasi bisnis pensiun.'],
            ['UKM, Jenius & syariah', 'Kredit', 'UKM Rp13,5 T; Jenius Rp3,6 T (+11,3%); syariah Rp10,4 T.', 15, 'naik', true, 'Akses digital & pemberdayaan.', 'Jenius & BTPN Syariah.'],
            ['Nasabah dana ritel & korporasi', 'Dana', 'DPK Rp131,0 T (+8%); CASA 41%.', 0, 'naik', true, 'Rekening transaksi & tabungan digital.', 'Jenius & cash management.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — kredit berdasarkan segmen']);
        }

        foreach ([
            ['PT Bank SMBC Indonesia Tbk', 'Bank swasta', 1, 18439, 5.8, 2.7, true, 'Jaringan SMBC, Jenius, NIM 7,0%.', 'Biaya kredit Grup OTO; CASA rendah.'],
            ['Bank swasta besar', 'Bank swasta', 2, null, null, null, false, 'CASA tinggi & efisiensi.', 'Kurang kuat di nasabah Jepang.'],
            ['Bank asing & joint venture lain', 'Bank asing', 3, null, null, null, false, 'Jaringan global.', 'Ritel terbatas.'],
            ['Bank digital', 'Digital', 4, null, null, null, false, 'Pengalaman aplikasi.', 'Skala & profitabilitas.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan operasional dalam Rp miliar; marjin = laba diatribusikan ÷ pendapatan operasional).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Konglomerasi keuangan (PIKK)', 'OJK', 'POJK Konglomerasi Keuangan', 'Tata kelola', '2025-06-30', 'Tata kelola, manajemen risiko, kepatuhan, dan permodalan terintegrasi untuk KK SMBC.', 'Seluruh entitas grup.', $year === 2025 ? 'sebagian' : 'patuh', 'grup', $year === 2025 ? '2026-06-30' : null],
            ['Kewajiban penyediaan modal minimum', 'OJK', 'POJK KPMM & Basel III', 'Permodalan', null, 'CAR minimum sesuai profil risiko + buffer.', 'Ekspansi kredit.', 'patuh', 'cfo', null],
            ['Likuiditas (LCR & NSFR)', 'OJK', 'POJK LCR & NSFR', 'Likuiditas', null, 'LCR 229%; NSFR 123%.', 'Stabilitas pendanaan.', 'patuh', 'risiko', null],
            ['Rasio Pembiayaan Inklusif Makroprudensial', 'Bank Indonesia', 'PBI RPIM', 'Kredit', null, 'Porsi pembiayaan inklusif.', 'Penyaluran UMKM.', 'patuh', 'ritel', null],
            ['Ketahanan & keamanan siber bank', 'OJK', 'POJK tentang ketahanan & keamanan siber bank umum', 'Teknologi', null, 'Tata kelola TI, pengujian, pelaporan insiden.', 'Kepercayaan nasabah Jenius.', 'patuh', 'ops', null],
            ['APU-PPT', 'OJK & PPATK', 'POJK APU-PPT', 'Kepatuhan', null, 'Pengenalan nasabah & transaksi mencurigakan.', 'Sanksi & reputasi.', 'patuh', 'kepatuhan', null],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data nasabah lintas entitas grup.', 'Sanksi & kepercayaan.', 'sebagian', 'ops', '2026-12-31'],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'smbc' => ['Pemegang saham pengendali (SMBC) & publik', 'internal', 'Pemilik', 5, 5, 'Laba pulih & tata kelola grup.', 'RUPS, rapat gabungan, paparan publik.', 'Corporate Secretary'],
            'korporasi' => ['Nasabah korporasi & Jepang', 'eksternal', 'Nasabah', 5, 5, 'Kredit, trade, cash management.', 'Relationship manager.', 'Wholesale Banking'],
            'ritel' => ['Nasabah Jenius & Sinaya', 'eksternal', 'Nasabah', 4, 5, 'Aplikasi andal, aman, bermanfaat.', 'Survei & media sosial.', 'Ritel & Digital'],
            'anak' => ['Anak usaha (BTPN Syariah, OTO, SOF)', 'internal', 'Grup', 4, 5, 'Dukungan modal, risiko, sinergi.', 'Komite konglomerasi.', 'Konglomerasi Keuangan'],
            'ojk' => ['OJK, Bank Indonesia & LPS', 'eksternal', 'Regulator', 5, 5, 'Kesehatan bank & tata kelola konglomerasi.', 'Pelaporan & exit meeting.', 'Kepatuhan'],
            'karyawan' => ['Karyawan (6.139 bank; 29.411 grup)', 'internal', 'SDM', 3, 5, 'Karier & kepastian di masa transformasi.', 'Employee engagement survey.', 'Human Resources'],
            'masyarakat' => ['Masyarakat & penerima program Daya', 'eksternal', 'Komunitas', 2, 4, 'Pemberdayaan UMKM & literasi.', 'Program Daya.', 'Daya'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'ckpn' => ['Biaya kredit melonjak di Grup OTO', 'CKPN Rp8,0 T (2024: Rp3,9 T); segmen pembiayaan rugi Rp2,4 T.', 'keuangan', 5, 5, 'risiko', ['smbc', 'anak', 'ojk']],
            'laba' => ['Laba turun tajam', 'Laba diatribusikan Rp506 M (−82%); ROE 1,1%; BOPO 97,7%.', 'keuangan', 5, 5, 'cfo', ['smbc']],
            'casa' => ['Ketergantungan pada dana mahal', 'CASA 41%; LDR 140% ditopang pinjaman & obligasi.', 'keuangan', 4, 4, 'ritel', ['smbc']],
            'pikk' => ['Peran baru sebagai induk konglomerasi', 'Kerangka risiko & kepatuhan terintegrasi belum matang di semua entitas.', 'proses', 4, 4, 'grup', ['anak', 'ojk']],
            'pensiun' => ['Bisnis pensiun menyusut', 'Kredit pensiun −8,5%.', 'pelanggan', 3, 3, 'ritel', ['ritel']],
            'digital' => ['Monetisasi Jenius', 'Nasabah 6,3 juta, kredit Jenius baru Rp3,6 T.', 'pelanggan', 3, 4, 'ritel', ['ritel']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Dukungan & jaringan global SMBC', 'Nasabah Jepang, pendanaan, keahlian.', 0.14, 4],
            ['S', 'Permodalan & likuiditas kuat', 'CAR 29,3%; LCR 229%.', 0.12, 4],
            ['S', 'NIM tinggi', 'NIM 7,0% dari portofolio ritel & pembiayaan.', 0.08, 4],
            ['S', 'Jenius & program Daya', '6,3 juta nasabah; merek dikenal.', 0.08, 3],
            ['W', 'Biaya kredit tinggi', 'CKPN Rp8,0 T; BOPO 97,7%.', 0.14, 1],
            ['W', 'CASA rendah', '41% dari DPK.', 0.08, 2],
            ['W', 'Efisiensi', 'CIR 54,3%.', 0.06, 2],
            ['O', 'Sinergi konglomerasi', 'Satu grup: bank, syariah, pembiayaan.', 0.08, 3],
            ['O', 'Trade & cash management', 'Trade finance +51%.', 0.06, 4],
            ['O', 'Pembiayaan hijau', 'Green loan bersama SMBC.', 0.04, 3],
            ['T', 'Daya beli & otomotif lemah', 'Kualitas piutang kendaraan.', 0.06, 2],
            ['T', 'Bank digital', 'Persaingan nasabah muda.', 0.03, 3],
            ['T', 'Fraud & serangan siber', 'Kepercayaan nasabah digital.', 0.03, 3],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Wholesale & rantai pasok nasabah Jepang', 'Manfaatkan jaringan SMBC untuk kredit korporasi berkualitas.', 4, ['laba']],
            ['SO', 'Jenius sebagai mesin CASA & wealth', 'Dana murah & fee dari basis digital.', 4, ['casa', 'digital']],
            ['WO', 'Kerangka risiko terintegrasi PIKK', 'Standar penilaian, CKPN, dan penagihan yang sama di seluruh entitas.', 5, ['ckpn', 'pikk']],
            ['WT', 'Program pemulihan kualitas aset Grup OTO', 'Seleksi debitur, penagihan, dan restrukturisasi portofolio.', 5, ['ckpn', 'laba']],
            ['WT', 'Efisiensi & process excellence', 'Turunkan CIR lewat digitalisasi.', 3, ['laba']],
            ['ST', 'Transformasi bisnis pensiun & mikro', 'Fokus pada segmen yang menguntungkan.', 2, ['pensiun']],
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
            'T1' => ['Pemulihan Laba & Kualitas Aset', 'Biaya kredit turun, laba pulih, permodalan terjaga.', '#C8102E'],
            'T2' => ['Wholesale & Jaringan Global SMBC', 'Korporasi, nasabah Jepang, trade, cash management.', '#004831'],
            'T3' => ['Ritel Digital: Jenius & Wealth', 'CASA, nasabah ritel, fee, kredit digital.', '#00A0DF'],
            'T4' => ['Konglomerasi, Insan & Operational Excellence', 'Tata kelola PIKK, TI & siber, talenta.', '#7A5AA6'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Pertumbuhan selektif di pembiayaan kendaraan', 'operasi', 'Kualitas debitur di atas volume selama pemulihan.'],
            ['T2', 'Satu tim wholesale bersama SMBC', 'saluran', 'Nasabah Jepang & rantai pasoknya sebagai keunggulan unik.'],
            ['T3', 'Jenius sebagai platform ritel utama', 'produk', 'Akuisisi, tabungan, kredit, dan investasi dalam satu aplikasi.'],
            ['T4', 'Kerangka PIKK satu standar', 'operasi', 'Risiko, kepatuhan, dan data terintegrasi di seluruh entitas.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T1', 'Laba pulih berkelanjutan', 'Laba diatribusikan Rp2,6 T; NII & fee tumbuh.', 'dirut', 3],
            'F2' => ['keuangan', 'T1', 'Imbal hasil & efisiensi membaik', 'ROE, BOPO, CIR kembali ke jalur.', 'cfo', 2],
            'F3' => ['keuangan', 'T1', 'Permodalan & likuiditas kuat', 'CAR terjaga; LDR turun.', 'cfo', 1.5],
            'C1' => ['pelanggan', 'T3', 'Dana murah & basis nasabah ritel', 'CASA & nasabah Jenius naik.', 'ritel', 2],
            'C2' => ['pelanggan', 'T2', 'Mitra utama nasabah korporasi', 'Kredit, trade, cash management tumbuh.', 'wholesale', 2],
            'C3' => ['pelanggan', 'T3', 'Ritel & UKM digital tumbuh', 'Transaksi & kredit Jenius, UKM.', 'ritel', 1.5],
            'C4' => ['pelanggan', 'T4', 'Sinergi grup memberi nilai', 'Laba anak usaha & keuangan inklusif.', 'grup', 1.5],
            'P1' => ['proses', 'T1', 'Kualitas aset & biaya kredit terkendali', 'NPL, cost of credit, NPF Grup OTO.', 'risiko', 2.5],
            'P2' => ['proses', 'T4', 'Operational excellence', 'Proses digital & keputusan kredit cepat.', 'ops', 1.5],
            'P3' => ['proses', 'T4', 'Layanan digital andal & aman', 'Ketersediaan Jenius; fraud ditangani cepat.', 'ops', 1.5],
            'P4' => ['proses', 'T4', 'Tata kelola konglomerasi & kepatuhan', 'Kerangka PIKK; temuan audit tuntas.', 'kepatuhan', 1.5],
            'L1' => ['sdm', 'T4', 'Talenta kompeten & produktif', 'Pelatihan & retensi.', 'sdm', 1],
            'L2' => ['sdm', 'T4', 'Budaya berkinerja tinggi', 'Engagement & KPI berjenjang.', 'sdm', 1],
            'L3' => ['sdm', 'T1', 'Data & analitik risiko', 'Model risiko di produksi; transaksi digital.', 'ops', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P2', 2], ['L2', 'L1', 2], ['L3', 'P1', 3], ['L3', 'P3', 2],
            ['P1', 'F1', 3], ['P1', 'F2', 3], ['P2', 'C3', 2], ['P2', 'F2', 2], ['P3', 'C1', 2], ['P4', 'P1', 2], ['P4', 'C4', 2],
            ['C1', 'F1', 2], ['C1', 'F3', 2], ['C2', 'F1', 3], ['C3', 'F1', 2], ['C4', 'F1', 2],
            ['F1', 'F2', 3], ['F3', 'F2', 1],
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
            ['F1.1', 'F1', 'Laba bersih diatribusikan kepada pemilik', 'Rp miliar', 'higher', 'sum', 0, true, true, 'cfo', 'Laba tahun berjalan yang diatribusikan kepada pemilik entitas induk', 'Laporan keuangan konsolidasian', 2813, 3100, 506, 2600, .97, $lt],
            ['F1.2', 'F1', 'Pendapatan bunga bersih', 'Rp miliar', 'higher', 'sum', 0, true, false, 'cfo', 'Pendapatan bunga − beban bunga', 'Laporan laba rugi', 15208, 16000, 15912, 16800, .99, $lt],
            ['F1.3', 'F1', 'Pendapatan operasional lainnya (fee)', 'Rp miliar', 'higher', 'sum', 0, true, false, 'wholesale', 'Komisi bancassurance, investasi, kartu kredit, trade, forex', 'Laporan laba rugi', 2221, 2400, 2527, 2800, 1.0, $lt],
            ['F2.1', 'F2', 'Imbal hasil ekuitas (ROE)', '%', 'higher', 'average', 1, true, true, 'cfo', 'Laba ÷ rata-rata ekuitas × 100', 'Rasio keuangan', 6.8, 7.5, 1.1, 4.5, .9, $lt],
            ['F2.2', 'F2', 'BOPO', '%', 'lower', 'average', 1, true, false, 'cfo', 'Beban operasional ÷ pendapatan operasional × 100', 'Rasio keuangan', 83.2, 82.0, 97.7, 88.0, 1.03, $lt],
            ['F2.3', 'F2', 'Marjin bunga bersih (NIM)', '%', 'higher', 'average', 1, true, false, 'cfo', 'Pendapatan bunga bersih ÷ rata-rata aset produktif × 100', 'Rasio keuangan', 7.1, 7.1, 7.0, 7.0, .99, $lt],
            ['F2.4', 'F2', 'Cost to income ratio (CIR)', '%', 'lower', 'average', 1, true, false, 'cfo', 'Beban operasional di luar CKPN ÷ pendapatan operasional × 100', 'Rasio keuangan', 53.9, 52.5, 54.3, 52.5, 1.02, $lt],
            ['F3.1', 'F3', 'Rasio kecukupan modal (CAR)', '%', 'higher', 'last', 1, true, true, 'cfo', 'Modal ÷ ATMR × 100', 'Laporan KPMM', 30.0, 25.0, 29.3, 27.0, 1.0, $lt],
            ['F3.2', 'F3', 'Loan to deposit ratio (LDR)', '%', 'lower', 'last', 1, true, false, 'cfo', 'Kredit ÷ dana pihak ketiga × 100 (tanpa pinjaman kepada bank)', 'Rasio keuangan', 147.0, 142.0, 140.3, 135.0, .99, $lt],
            ['C1.1', 'C1', 'Dana giro & tabungan (CASA)', 'Rp miliar', 'higher', 'last', 0, true, true, 'ritel', 'Saldo giro + tabungan (rasio CASA × DPK)', 'Neraca', 46100, 50000, 53710, 60000, .97, $lt],
            ['C1.2', 'C1', 'Rasio CASA', '%', 'higher', 'last', 1, true, false, 'ritel', 'CASA ÷ DPK × 100', 'Rasio keuangan', 38.0, 40.0, 41.0, 44.0, .97, $lt],
            ['C1.3', 'C1', 'Dana pihak ketiga', 'Rp miliar', 'higher', 'last', 0, true, false, 'ritel', 'Simpanan nasabah termasuk dana syirkah temporer', 'Neraca', 121318, 128000, 131001, 140000, .99, $lt],
            ['C1.4', 'C1', 'Nasabah Jenius', 'juta', 'higher', 'last', 1, false, false, 'ritel', 'Nasabah Jenius aktif terdaftar', 'Data Jenius', 5.9, 6.5, 6.3, 7.0, .96, $lt],
            ['C2.1', 'C2', 'Kredit korporasi & komersial', 'Rp miliar', 'higher', 'last', 0, true, true, 'wholesale', 'Saldo kredit Corporate Banking Japanese, Wholesale, Commercial', 'Laporan segmen', 98896, 104000, 105366, 113000, .99, $lt],
            ['C2.2', 'C2', 'Realisasi trade finance', 'Rp miliar', 'higher', 'sum', 0, true, false, 'wholesale', 'Realisasi trade finance seluruh segmen korporasi', 'Laporan segmen', 6826, 8000, 10286, 12000, 1.03, $lt],
            ['C2.3', 'C2', 'Dana cash management korporasi', 'Rp miliar', 'higher', 'last', 0, true, false, 'wholesale', 'DPK segmen korporasi dalam cash management', 'Laporan segmen', 62619, 66000, 69905, 76000, .97, $lt],
            ['C3.1', 'C3', 'Volume transaksi in-app Jenius', 'Rp miliar', 'higher', 'sum', 0, false, false, 'ritel', 'Nilai transaksi di aplikasi Jenius', 'Data Jenius', 274600, 295000, 287000, 315000, .96, $lt],
            ['C3.2', 'C3', 'Kredit Jenius', 'Rp miliar', 'higher', 'last', 0, true, false, 'ritel', 'Saldo kredit Jenius (Flexi Cash, kartu kredit, paylater)', 'Laporan segmen', 3198, 3500, 3558, 4100, .95, $lt],
            ['C3.3', 'C3', 'Kredit UKM', 'Rp miliar', 'higher', 'last', 0, true, false, 'ritel', 'Saldo kredit usaha kecil & menengah', 'Laporan segmen', 14362, 15000, 13488, 14500, .86, $lt],
            ['C4.1', 'C4', 'Laba bersih BTPN Syariah', 'Rp miliar', 'higher', 'sum', 0, true, false, 'grup', 'Laba bersih konsolidasi PT Bank BTPN Syariah Tbk', 'Laporan anak usaha', 1061, 1150, 1201, 1300, .99, $lt],
            ['C4.2', 'C4', 'Piutang pembiayaan OTO & SOF', 'Rp miliar', 'higher', 'last', 0, true, false, 'grup', 'Saldo piutang pembiayaan PT Oto Multiartha & PT Summit Oto Finance', 'Laporan segmen', 29433, 31500, 30377, 31000, .94, $lt],
            ['C4.3', 'C4', 'Rasio Pembiayaan Inklusif Makroprudensial (RPIM)', '%', 'higher', 'last', 1, false, false, 'ritel', 'Pembiayaan inklusif ÷ total kredit × 100', 'Laporan RPIM', 29.0, 30.0, 30.8, 31.0, 1.0, $lt],
            ['P1.1', 'P1', 'Kredit bermasalah (NPL gross)', '%', 'lower', 'last', 2, true, true, 'risiko', 'Kredit kolektibilitas 3–5 (termasuk piutang pembiayaan) ÷ total × 100', 'Laporan kualitas aset', 2.50, 2.40, 2.59, 2.40, 1.04, $lt],
            ['P1.2', 'P1', 'Cost of credit', '%', 'lower', 'average', 2, true, true, 'risiko', 'Beban CKPN ÷ rata-rata kredit × 100', 'Laporan keuangan', 2.31, 2.50, 4.41, 2.80, 1.12, $lt],
            ['P1.3', 'P1', 'NPF piutang pembiayaan Grup OTO', '%', 'lower', 'last', 2, true, true, 'grup', 'Piutang pembiayaan bermasalah OTO & SOF ÷ total piutang × 100', 'Laporan anak usaha (ilustrasi)', 2.60, 2.50, 3.40, 2.70, 1.35, $il],
            ['P2.1', 'P2', 'Proses end-to-end terdigitalisasi', '%', 'higher', 'last', 0, false, false, 'ops', 'Proses inti dengan alur digital penuh ÷ total proses inti × 100', 'Process Excellence Office', 40, 50, 52, 65, .9, $il],
            ['P2.2', 'P2', 'Waktu keputusan kredit UKM', 'hari', 'lower', 'average', 1, false, false, 'ritel', 'Rata-rata hari kerja dari berkas lengkap sampai keputusan', 'Sistem kredit', 9.0, 8.0, 8.4, 6.0, 1.15, $il],
            ['P3.1', 'P3', 'Ketersediaan layanan Jenius', '%', 'higher', 'average', 2, false, false, 'ops', 'Waktu layanan tersedia ÷ total waktu × 100', 'Pemantauan TI', 99.85, 99.90, 99.91, 99.95, .9995, $il],
            ['P3.2', 'P3', 'Laporan fraud nasabah ditangani < 24 jam', '%', 'higher', 'average', 0, false, false, 'ops', 'Laporan fraud diblokir/ditindak < 24 jam ÷ total × 100', 'Fraud Management', 82, 88, 89, 94, .97, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'kepatuhan', 'Temuan SKAI/eksternal/OJK selesai sesuai tenggat ÷ jatuh tempo × 100', 'SKAI', 84, 90, 91, 95, .98, $il],
            ['P4.2', 'P4', 'Entitas anak dengan kerangka risiko terintegrasi', '%', 'higher', 'last', 0, false, false, 'grup', 'Entitas anggota KK SMBC yang menerapkan kebijakan risiko PIKK ÷ total × 100', 'Divisi Konglomerasi Keuangan', 25, 50, 50, 100, .9, $il],
            ['L1.1', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 0, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan bank', 'Human Resources', 37.7, 38.0, 38.1, 40.0, .93, $lt],
            ['L1.2', 'L1', 'Turnover karyawan', '%', 'lower', 'last', 1, false, false, 'sdm', 'Karyawan keluar ÷ rata-rata karyawan × 100', 'HRIS', 9.0, 9.0, 8.6, 8.5, 1.02, $il],
            ['L2.1', 'L2', 'Employee engagement', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei keterikatan karyawan (skala 100)', 'Employee engagement survey', 80, 82, 81, 83, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan KPI turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 60, 75, 78, 90, .9, $il],
            ['L3.1', 'L3', 'Model analitik risiko di produksi', 'unit', 'higher', 'sum', 0, false, false, 'risiko', 'Model skor, early warning, dan penagihan yang dipakai dalam keputusan', 'Data & Analytics', 6, 10, 9, 15, .82, $il],
            ['L3.2', 'L3', 'Porsi transaksi melalui kanal digital', '%', 'higher', 'average', 1, false, false, 'ops', 'Transaksi digital ÷ total transaksi × 100', 'Data transaksi', 88.0, 90.0, 91.0, 93.0, 1.0, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Menara SMBC, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, pimpinan anak usaha, Strategi & Perencanaan',
                'summary' => "Pendapatan operasional Rp18,4 T (+6%); NII Rp15,9 T; NIM 7,0%.\nCKPN Rp8,0 T (2024: Rp3,9 T), terutama di Grup OTO.\nLaba diatribusikan Rp506 M (16% dari target); ROE 1,1%; BOPO 97,7%.\nKredit Rp185,4 T (+3%); CASA 41%; CAR 29,3%; NPL 2,59%.\nPenunjukan sebagai PIKK oleh OJK (pertengahan 2025).",
                'decisions' => "1. 2026 adalah tahun pemulihan: target laba Rp2,6 T dengan cost of credit ≤ 2,8%.\n2. Program pemulihan kualitas aset Grup OTO dipimpin Direktur Manajemen Risiko.\n3. KPI korporat diturunkan ke divisi & anak usaha melalui Ruci BSC.",
                'follow_up' => 'Strategi & Perencanaan menurunkan KPI 2026 ke setiap divisi dan anak usaha paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta analisis akar masalah lonjakan CKPN di Grup OTO: apakah karena kondisi ekonomi, standar seleksi debitur, atau model pencadangan.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, pimpinan anak usaha, Strategi & Perencanaan',
                'summary' => match ($m) {
                    2 => 'Penyaluran kendaraan menjelang Lebaran naik; standar seleksi debitur baru mulai berlaku.',
                    3 => 'Lebaran (20 Maret): transaksi Jenius memuncak; layanan stabil.',
                    5 => 'NPF Grup OTO belum turun; tunggakan 30+ hari masih tinggi di segmen roda dua.',
                    6 => 'Semester I: laba 96% target; cost of credit 2,9%; CASA 42,5%.',
                    7 => 'Modus penipuan melalui tautan palsu meningkat.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    2 => 'Pantau kualitas pencairan baru per dealer setiap minggu.',
                    3 => 'Pertahankan kapasitas server puncak sampai akhir April.',
                    5 => 'Tambah tim penagihan lapangan SOF & kerja sama agen penagihan bersertifikat.',
                    6 => 'Percepat model early warning lintas entitas.',
                    7 => 'Kampanye edukasi anti penipuan & pemblokiran otomatis.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), Strategi & Perencanaan',
                'summary' => $q === 1 ? 'Laba Q1 sesuai target; cost of credit turun ke 3,0%; NPF Grup OTO 3,5%.' : 'Laba H1 96% target; NPF Grup OTO 3,6% vs target 2,7%; waktu keputusan kredit UKM 7,4 hari.',
                'decisions' => $q === 1 ? 'Seleksi debitur OTO & SOF diperketat di wilayah dengan tunggakan tertinggi.' : 'NPF Grup OTO menjadi KPI kritis; laporan mingguan ke Direksi & Komite Pemantau Risiko.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Presiden Komisaris', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti NPF Grup OTO yang belum turun, kesiapan kerangka risiko PIKK di semua entitas, dan modus penipuan baru.',
            'decisions' => 'Direksi melaporkan NPF Grup OTO, cost of credit, dan kemajuan kerangka PIKK setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon dipisahkan: berapa bagian NPF dari pencairan lama (sebelum standar baru) dan berapa dari pencairan 2026. Itu menentukan apakah strategi seleksi sudah bekerja.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, kepala divisi, pimpinan anak usaha, Strategi & Perencanaan',
            'summary' => 'Realisasi September sebagian masih draf; cost of credit dikembalikan menunggu CKPN konsolidasi final.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; tidak diungkap per program di laporan tahunan.';

            return [
                'defs' => [
                    'pikk' => ['BTPN-25-01', 'Persiapan peran PIKK', 'Struktur tata kelola, risiko, dan kepatuhan konglomerasi KK SMBC.', 'T4', 'grup', '2025-01-01', '2025-12-31', 'selesai', 5, 4, 3, 2, ['P4'], 'Penunjukan PIKK oleh OJK pertengahan 2025.'],
                    'jenius' => ['BTPN-25-02', 'Inovasi Jenius', 'Kartu kredit tambahan, Maxi Saver, Bisniskit, investasi di aplikasi.', 'T3', 'ritel', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 3, 3, ['C1', 'C3'], 'Nasabah 6,3 juta; DPK Jenius Rp32,8 T.'],
                    'cabang' => ['BTPN-25-03', 'Transformasi kantor cabang', 'Penataan jaringan & merek cabang SMBC Indonesia.', 'T4', 'ops', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 2, 3, ['P2'], '221 kantor cabang & cabang pembantu.'],
                    'trade' => ['BTPN-25-04', 'Trade & cash management korporasi', 'Solusi trade bersama jaringan SMBC.', 'T2', 'wholesale', '2025-01-01', '2025-12-31', 'selesai', 4, 3, 2, 2, ['C2'], 'Trade finance Rp10,3 T (+51%).'],
                    'ckpn' => ['BTPN-25-05', 'Tinjauan portofolio intensif', 'Peninjauan portofolio & tambahan pencadangan di bank dan Grup OTO.', 'T1', 'risiko', '2025-07-01', '2025-12-31', 'selesai', 5, 5, 3, 2, ['P1'], 'CKPN Rp8,0 T dibukukan.'],
                ],
                'activities' => [
                    ['pikk', 'Kebijakan tata kelola terintegrasi', 'grup', '2025-01-01', '2025-06-30', 100], ['pikk', 'Komite konglomerasi', 'grup', '2025-07-01', '2025-12-31', 100],
                    ['jenius', 'Rilis fitur Jenius', 'ritel', '2025-01-01', '2025-12-31', 100],
                    ['cabang', 'Penataan merek cabang', 'ops', '2025-01-01', '2025-12-31', 100],
                    ['trade', 'Paket trade nasabah Jepang', 'wholesale', '2025-01-01', '2025-12-31', 100],
                    ['ckpn', 'Peninjauan portofolio Grup OTO', 'risiko', '2025-07-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['pikk', 'Persetujuan OJK sebagai PIKK', '2025-06-30', true], ['jenius', 'Kartu kredit tambahan Jenius', '2025-06-30', true], ['ckpn', 'Pencadangan tambahan dibukukan', '2025-12-31', true],
                ],
                'budgets' => [
                    ['pikk', 'Konsultan & sistem tata kelola', 'opex', 25_000_000_000, 22_500_000_000, $note],
                    ['jenius', 'Pengembangan aplikasi', 'capex', 300_000_000_000, 284_000_000_000, $note, 'Kas internal'],
                    ['cabang', 'Renovasi & merek cabang', 'capex', 120_000_000_000, 108_000_000_000, $note, 'Kas internal'],
                    ['trade', 'Platform trade', 'capex', 40_000_000_000, 35_000_000_000, $note, 'Kas internal'],
                    ['ckpn', 'Tinjauan portofolio independen', 'opex', 10_000_000_000, 9_200_000_000, $note],
                ],
            ];
        }

        return [
            'defs' => [
                'pemulihan' => ['BTPN-26-01', 'Pemulihan kualitas aset Grup OTO', 'Seleksi debitur, penagihan, restrukturisasi, dan penjualan agunan.', 'T1', 'risiko', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 2, ['P1', 'F1'], 'NPF Grup OTO 2,7%.'],
                'ews' => ['BTPN-26-02', 'Model early warning lintas entitas', 'Satu model peringatan dini untuk bank, OTO, SOF.', 'T1', 'risiko', '2026-02-01', '2026-10-31', 'berjalan', 4, 4, 3, 2, ['L3', 'P1'], '15 model risiko di produksi.'],
                'pikk' => ['BTPN-26-03', 'Kerangka risiko & kepatuhan terintegrasi PIKK', 'Kebijakan, limit, dan pelaporan konglomerasi di semua entitas.', 'T4', 'grup', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 2, ['P4'], '100% entitas terintegrasi.'],
                'casa' => ['BTPN-26-04', 'Jenius untuk CASA & wealth', 'Tabungan harian, payroll, investasi di aplikasi.', 'T3', 'ritel', '2026-01-01', '2026-12-31', 'berjalan', 4, 4, 3, 2, ['C1', 'F1'], 'Rasio CASA 44%.'],
                'kredit_jenius' => ['BTPN-26-05', 'Kredit digital Jenius', 'Creditbility, Flexi Cash, kartu kredit.', 'T3', 'ritel', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 2, ['C3'], 'Kredit Jenius Rp4,1 T.'],
                'wholesale' => ['BTPN-26-06', 'Rantai pasok nasabah Jepang', 'Pembiayaan pemasok & distributor nasabah korporasi Jepang.', 'T2', 'wholesale', '2026-01-01', '2026-12-31', 'berjalan', 4, 3, 2, 2, ['C2'], 'Kredit korporasi Rp113 T.'],
                'ukm' => ['BTPN-26-07', 'Percepatan kredit UKM', 'Skor otomatis & dokumen digital.', 'T3', 'ritel', '2026-02-01', '2026-12-31', 'berjalan', 3, 4, 3, 2, ['P2', 'C3'], 'Keputusan 6 hari.'],
                'fraud' => ['BTPN-26-08', 'Keamanan siber & anti penipuan', 'XDR, WAF, analitik perilaku, edukasi nasabah.', 'T4', 'ops', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 2, ['P3'], '94% laporan fraud ditangani < 24 jam.'],
                'efisiensi' => ['BTPN-26-09', 'Process excellence & otomasi', 'Digitalisasi proses inti dan penataan biaya.', 'T4', 'ops', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 3, ['P2', 'F2'], 'CIR 52,5%.'],
                'talenta' => ['BTPN-26-10', 'Talenta & KPI berjenjang', 'Akademi risiko, rotasi lintas entitas, KPI turunan Peta Strategi.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 3, 3, 2, 1, ['L1', 'L2'], '90% KPI terhubung.'],
            ],
            'activities' => [
                ['pemulihan', 'Standar seleksi debitur baru OTO & SOF', 'grup', '2026-01-01', '2026-03-31', 100], ['pemulihan', 'Penguatan penagihan lapangan', 'grup', '2026-05-01', '2026-12-31', 55],
                ['pemulihan', 'Restrukturisasi & penjualan agunan', 'risiko', '2026-03-01', '2026-12-31', 45],
                ['ews', 'Model early warning lintas entitas', 'risiko', '2026-02-01', '2026-10-31', 70],
                ['pikk', 'Kebijakan risiko konglomerasi', 'grup', '2026-01-01', '2026-06-30', 100], ['pikk', 'Integrasi pelaporan anak usaha', 'grup', '2026-04-01', '2026-12-31', 55],
                ['casa', 'Kampanye tabungan harian & payroll', 'ritel', '2026-01-01', '2026-12-31', 70],
                ['kredit_jenius', 'Perluasan Creditbility', 'ritel', '2026-02-01', '2026-12-31', 60],
                ['wholesale', 'Program pemasok nasabah Jepang', 'wholesale', '2026-01-01', '2026-12-31', 70],
                ['ukm', 'Skor otomatis UKM', 'risiko', '2026-02-01', '2026-06-30', 100], ['ukm', 'Dokumen digital UKM', 'ritel', '2026-05-01', '2026-12-31', 45],
                ['fraud', 'Analitik perilaku & pemblokiran otomatis', 'ops', '2026-01-01', '2026-08-31', 100],
                ['efisiensi', 'Otomasi proses back office', 'ops', '2026-01-15', '2026-12-31', 60],
                ['talenta', 'KPI individu turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100], ['talenta', 'Akademi risiko kredit', 'sdm', '2026-03-01', '2026-12-31', 55],
            ],
            'milestones' => [
                ['pemulihan', 'Standar seleksi baru berlaku', '2026-03-31', true], ['pikk', 'Kebijakan risiko konglomerasi disahkan', '2026-06-30', true], ['ukm', 'Skor UKM live', '2026-06-30', true],
                ['fraud', 'Pemblokiran otomatis live', '2026-08-31', true], ['ews', 'Model early warning live', '2026-10-31', false], ['talenta', 'KPI individu disetujui', '2026-04-30', true],
            ],
            'budgets' => [
                ['pemulihan', 'Penagihan & pemulihan agunan', 'opex', 150_000_000_000, 92_000_000_000, null],
                ['ews', 'Platform analitik risiko', 'capex', 70_000_000_000, 46_000_000_000, null, 'Kas internal'],
                ['pikk', 'Sistem pelaporan konglomerasi', 'capex', 45_000_000_000, 27_000_000_000, null, 'Kas internal'],
                ['casa', 'Kampanye & insentif tabungan', 'opex', 120_000_000_000, 84_000_000_000, null],
                ['kredit_jenius', 'Pengembangan kredit digital', 'capex', 60_000_000_000, 38_000_000_000, null, 'Kas internal'],
                ['wholesale', 'Solusi rantai pasok', 'opex', 30_000_000_000, 20_000_000_000, null],
                ['ukm', 'Sistem kredit UKM', 'capex', 35_000_000_000, 24_000_000_000, null, 'Kas internal'],
                ['fraud', 'Keamanan siber & fraud', 'capex', 110_000_000_000, 79_000_000_000, null, 'Kas internal'],
                ['efisiensi', 'Otomasi proses', 'capex', 50_000_000_000, 31_000_000_000, null, 'Kas internal'],
                ['talenta', 'Akademi & program KPI', 'opex', 18_000_000_000, 11_000_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Laba jauh di bawah target', 'Laba diatribusikan Rp506 M vs target Rp3,1 T; CKPN naik dua kali lipat, terutama di Grup OTO.', 'tinjauan', 'tahunan', 'keuangan', 'kritis', null, 'F1.1', 'ckpn', 'komisaris', '2026-02-24',
                [['Program pemulihan kualitas aset Grup OTO 2026', 'risiko', '2026-01-31', 'selesai', 100]], ['2026-02-28', 'Program pemulihan disetujui Direksi & Komite Pemantau Risiko.']],
            ['Kredit UKM menurun', 'Kredit UKM Rp13,5 T vs target Rp15 T (−6%).', 'tinjauan', 'tahunan', 'pelanggan', 'sedang', null, 'C3.3', null, 'ritel', '2026-02-24',
                [['Percepatan proses kredit UKM', 'ritel', '2026-12-31', 'berjalan', 60]], null],
        ] : [
            ['NPF Grup OTO belum turun', 'NPF 3,6% per Juni vs target 2,7%; pencairan sebelum 2026 mendominasi tunggakan.', 'timwas', 'boc', 'proses', 'kritis', null, 'P1.3', 'pemulihan', 'komisaris', '2026-07-28',
                [['Pisahkan analisis vintage pencairan lama vs 2026', 'risiko', '2026-08-31', 'selesai', 100], ['Tambah kapasitas penagihan lapangan SOF', 'grup', '2026-12-31', 'berjalan', 55]], null],
            ['Waktu keputusan kredit UKM masih panjang', '7,4 hari vs target 6 hari; dokumen digital belum diterima semua notaris.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'P2.2', 'ukm', 'ritel', '2026-07-22',
                [['Kerja sama notaris rekanan untuk dokumen elektronik', 'ritel', '2026-10-31', 'berjalan', 60]], null],
            ['Modus penipuan tautan palsu meningkat', 'Laporan fraud nasabah Jenius naik 25% di Juli.', 'pelanggan', 'rm7', 'proses', 'tinggi', null, 'P3.2', 'fraud', 'ops', '2026-08-12',
                [['Pemblokiran otomatis tautan & rekening tujuan', 'ops', '2026-08-31', 'selesai', 100], ['Kampanye edukasi nasabah', 'ritel', '2026-12-31', 'berjalan', 70]], null],
            ['Model early warning tertinggal', '7 model di produksi vs rencana 10 per Agustus; data anak usaha belum seragam.', 'internal', 'rm8', 'sdm', 'sedang', null, 'L3.1', 'ews', 'risiko', '2026-09-12',
                [['Standar data kredit lintas entitas', 'grup', '2026-11-30', 'berjalan', 40]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Data kredit anak usaha', 'internal', 'ews', 'P1', 'OTO, SOF, BTPN Syariah', 'Model early warning butuh data seragam.', 'grup', 'tinggi', 'Standar data & jadwal pengiriman harian.'],
                ['Agen penagihan bersertifikat', 'eksternal', 'pemulihan', 'P1', 'Mitra penagihan', 'Kapasitas penagihan lapangan.', 'grup', 'tinggi', 'Kontrak berbasis kinerja & audit kepatuhan.'],
                ['Penerimaan dokumen elektronik', 'eksternal', 'ukm', 'P2', 'Notaris & instansi pertanahan', 'Proses agunan butuh dukungan pihak ketiga.', 'ritel', 'sedang', 'Notaris rekanan.'],
                ['Jaringan & sistem Grup SMBC', 'eksternal', 'wholesale', 'C2', 'SMBC Jepang & regional', 'Rujukan nasabah Jepang & produk global.', 'wholesale', 'sedang', 'Komite sinergi bulanan.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Komite Manajemen Risiko Terintegrasi', 'rapat', 'Risiko konglomerasi KK SMBC.', 'Bulanan', 'risiko', $year === 2025 ? 'rencana' : 'melembaga'],
                ['ALCO (Asset Liability Committee)', 'rapat', 'Suku bunga, likuiditas, struktur pendanaan.', 'Mingguan', 'cfo', 'melembaga'],
                ['Komite kredit', 'rapat', 'Persetujuan & pemantauan kredit.', 'Mingguan', 'risiko', 'melembaga'],
                ['Rapat gabungan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan kepatuhan.', 'Kuartalan', 'dirut', 'melembaga'],
                ['Nilai utama SMBC Indonesia', 'budaya', 'Integrity, Customer First, Proactive & Innovative, Speed & Quality, Synergy.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
