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
 * Demo PT Kimia Farma (Persero) Tbk (kode KAEF) — BUMN farmasi terintegrasi hulu–hilir.
 *
 * - Perioda 2025 (ditutup): angka tahunan secara garis besar mengacu pada Laporan Tahunan 2025
 *   (penjualan per segmen, laba bruto, rugi bersih, arus kas, rasio, jaringan apotek/klinik/lab, karyawan).
 * - Perioda 2026 (berjalan): target & realisasi Jan–Sep 2026 SIMULASI untuk pembelajaran.
 * - Kasus belajar utama: strategi pemulihan (turnaround) — rugi dipersempit, restrukturisasi utang selesai,
 *   tetapi penjualan turun dan likuiditas masih ketat. Rugi dicatat sebagai KPI "lebih kecil lebih baik".
 */
class KimiaFarmaSeeder extends ShowcaseSeeder
{
    public const CODE = 'KAEF';

    public const LT = 'Laporan Tahunan 2025 PT Kimia Farma (Persero) Tbk';

    protected function companyAttributes(): array
    {
        return [
            'name' => 'PT Kimia Farma (Persero) Tbk', 'legal_name' => 'PT Kimia Farma (Persero) Tbk',
            'industry' => 'Farmasi & layanan kesehatan terintegrasi (manufaktur, distribusi, apotek, klinik, laboratorium)',
            'address' => 'Jl. Veteran No. 9', 'city' => 'Jakarta Pusat',
            'phone' => '+62 21 384 7709', 'website' => 'https://www.kimiafarma.co.id',
            'description' => 'DATA DEMO untuk pembelajaran (kode saham KAEF). Angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025; target, rincian bulanan, data 2026, dan indikator operasional adalah simulasi. Bukan data resmi perusahaan.',
        ];
    }

    protected function periodNote(int $year): string
    {
        return $year === 2025
            ? 'DATA DEMO. Realisasi tahunan mengacu pada Laporan Tahunan 2025; target dan rincian bulanan ilustratif.'
            : 'DATA DEMO — SIMULASI 2026: Jan–Agu disetujui, Sep dalam proses persetujuan.';
    }

    /** Lebaran menurunkan hari kerja pabrik & distribusi; Desember naik karena pengadaan pemerintah & akhir tahun. */
    protected function season(): array
    {
        return [
            2025 => self::weights([1 => 8.1, 7.8, 7.6, 8.2, 8.3, 8.2, 8.4, 8.4, 8.3, 8.5, 8.6, 9.6]),
            2026 => self::weights([1 => 8.1, 7.9, 7.5, 8.2, 8.3, 8.2, 8.4, 8.4, 8.3, 8.5, 8.6, 9.6]),
        ];
    }

    protected function onTrack2026(): array
    {
        return ['C1.1', 'C2.2', 'C3.2', 'C4.2', 'C4.3', 'F2.3', 'F2.4', 'P2.1', 'L1.1', 'F1.2', 'F1.3', 'F2.2', 'L1.2', 'L2.1', 'P1.2', 'P1.3', 'P3.2', 'P4.2'];
    }

    protected function missingSeptember(): array
    {
        return ['C4.1', 'L1.3'];
    }

    protected function returnedSeptember(): ?array
    {
        return ['F3.4', 'Hari persediaan September belum memasukkan stok konsinyasi di gudang KFTD. Mohon hitung ulang dengan saldo persediaan konsolidasi dan lampirkan rekonsiliasi per entitas.'];
    }

    protected function criticalIn2026(): array
    {
        return ['P3.1'];
    }

    protected function maturityPattern(): array
    {
        return [2, 3, 2, 3, 2];
    }

    protected function userDefs(): array
    {
        return [
            'admin' => ['Admin Kimia Farma (contoh)', 'kaef.admin', 'Admin Sistem', 'Corporate Strategy', ['company-admin']],
            'dirut' => ['Direktur Utama (contoh)', 'kaef.direktur', 'Direktur Utama', 'Direksi', ['pimpinan']],
            'cfo' => ['Direktur Keuangan & Manajemen Risiko (contoh)', 'kaef.keuangan', 'Direktur Keuangan & Manajemen Risiko', 'Keuangan & Manajemen Risiko', ['keuangan']],
            'produksi' => ['Direktur Produksi & Supply Chain (contoh)', 'kaef.produksi', 'Direktur Produksi & Supply Chain', 'Produksi & Rantai Pasok', ['kepala-unit']],
            'distribusi' => ['Direktur Utama Trading & Distribution (contoh)', 'kaef.distribusi', 'Presiden Direktur KFTD', 'Distribusi', ['kepala-unit']],
            'ritel' => ['Direktur Utama Kimia Farma Apotek (contoh)', 'kaef.apotek', 'Presiden Direktur KFA', 'Ritel Apotek & Klinik', ['kepala-unit']],
            'layanan' => ['Direktur Utama Kimia Farma Diagnostika (contoh)', 'kaef.diagnostika', 'Presiden Direktur KFD', 'Laboratorium & Layanan Kesehatan', ['kepala-unit']],
            'sdm' => ['Direktur Human Capital (contoh)', 'kaef.sdm', 'Direktur Human Capital', 'Human Capital', ['kepala-unit', 'editor-jatidiri']],
            'ti' => ['Kepala Transformasi Digital (contoh)', 'kaef.ti', 'Head of Digital Transformation', 'Teknologi Informasi', ['kepala-unit']],
            'strategi' => ['Corporate Strategy & PMO (contoh)', 'kaef.strategi', 'VP Corporate Strategy & Transformation Office', 'Strategi Korporat & PMO Transformasi', ['strategy-office', 'editor-ekosistem']],
            'komisaris' => ['Komisaris Independen (contoh)', 'kaef.komisaris', 'Komisaris Independen', 'Dewan Komisaris', ['pengawas']],
            'analis' => ['Analis Kinerja (contoh)', 'kaef.analis', 'Performance Analyst', 'Strategi Korporat & PMO Transformasi', ['pelaksana']],
        ];
    }

    // ------------------------------------------------------------------ jati diri

    protected function identity(int $year): void
    {
        AtmanProfile::create([
            'vision' => 'Menjadi perusahaan Healthcare pilihan utama yang terintegrasi dan menghasilkan nilai yang berkesinambungan.',
            'mission' => "1. Melakukan aktivitas usaha di bidang-bidang industri kimia dan farmasi, perdagangan dan jaringan distribusi, ritel farmasi dan layanan kesehatan serta optimalisasi aset.\n2. Mengelola perusahaan secara Good Corporate Governance dan Operational Excellence didukung oleh Sumber Daya Manusia (SDM) profesional.\n3. Memberikan nilai tambah dan manfaat bagi seluruh stakeholder.",
            'purpose' => 'Menyediakan obat dan layanan kesehatan yang terjangkau, tersedia, dan tepercaya bagi masyarakat Indonesia, dari bahan baku sampai layanan di apotek, klinik, dan laboratorium.',
            'tagline' => 'Pemulihan yang disiplin menuju ekosistem kesehatan terintegrasi',
            'brand_promise' => 'Obat bermutu dan layanan kesehatan yang mudah dijangkau di seluruh Indonesia.',
            'value_proposition' => 'Ekosistem hulu–hilir: 9 pabrik, bahan baku (KFSP, Sinkona), 45 distributor, 1.050 apotek, 386 klinik, 65 laboratorium medis, serta layanan stem cell bersertifikat CPOB bersama RSCM.',
            'culture_statement' => 'Nilai inti BUMN AKHLAK: Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif.',
            'history' => "1817: berawal dari NV Chemicalien Handel Rathkamp & Co., perusahaan farmasi pertama di Hindia Belanda.\n1971: menjadi Persero dengan nama PT Kimia Farma (Persero).\n2001: tercatat di Bursa Efek Indonesia (KAEF).\n2020: bergabung dalam Holding BUMN Farmasi.\n2024: transformasi bersama PMO Restrukturisasi Keuangan & Reorientasi Bisnis Kementerian BUMN.\n2025: restrukturisasi utang dengan 11 bank selesai (16 Desember); Plant Banjaran meraih INDI 4.0 Level 3.",
            'approved_by' => $this->id('dirut'), 'approved_at' => $year.'-01-15 10:00:00',
        ]);
        foreach ([
            ['Amanah', 'Trustworthy', 'Memegang teguh kepercayaan yang diberikan.', "Melaporkan stok, piutang, dan realisasi apa adanya\nMenepati komitmen pembayaran pasca-restrukturisasi"],
            ['Kompeten', 'Competent', 'Terus belajar dan mengembangkan kapabilitas.', "Menjaga sertifikasi CPOB & kompetensi apoteker\nBelajar dari audit dan inspeksi"],
            ['Harmonis', 'Harmonious', 'Saling peduli dan menghargai perbedaan.', "Pabrik, distribusi, dan apotek menyelesaikan masalah stok bersama\nMenghargai masukan pelanggan"],
            ['Loyal', 'Loyal', 'Berdedikasi dan mengutamakan kepentingan bangsa dan negara.', "Mendukung ketersediaan obat program pemerintah\nMenjaga nama baik perusahaan"],
            ['Adaptif', 'Adaptive', 'Terus berinovasi dan antusias menggerakkan ataupun menghadapi perubahan.', "Mencoba kanal digital baru\nMenyederhanakan proses yang tidak menambah nilai"],
            ['Kolaboratif', 'Collaborative', 'Membangun kerja sama yang sinergis.', "Sinergi Holding BUMN Farmasi\nKemitraan dengan rumah sakit (stem cell)"],
        ] as $i => [$name, $local, $desc, $beh]) {
            CoreValue::create(['name' => $name, 'local_name' => $local, 'description' => $desc, 'behaviors' => $beh, 'sort' => $i + 1]);
        }
        $lvl = $year === 2025 ? 0 : 1;
        foreach ([
            ['Ekosistem hulu–hilir terintegrasi', 'Sumber daya fisik', 'Pabrik, bahan baku, distribusi, apotek, klinik, laboratorium dalam satu grup.', 4, 5, true, 'Sinergi stok & layanan terpadu apotek–klinik–lab.'],
            ['Merek apotek nasional', 'Kompetensi', '1.050 apotek; merek dikenal luas sejak 1817.', 4, 5, true, 'Optimalisasi jaringan & pengalaman pelanggan.'],
            ['Fasilitas produksi bersertifikat CPOB', 'Sumber daya fisik', '9 pabrik; Banjaran INDI 4.0 Level 3; fasilitas stem cell CPOB di rumah sakit.', 3, 4, true, 'Rasionalisasi & peningkatan utilisasi.'],
            ['Pengelolaan kas & modal kerja', 'Sistem', 'Rasio lancar 0,56x; DER 4,18x; persediaan & piutang tinggi.', 2 + $lvl, 4, false, 'Disiplin modal kerja pasca-restrukturisasi.'],
            ['Visibilitas stok end-to-end', 'Sistem', 'Sistem inventori belum terintegrasi di semua entitas anak.', 2 + $lvl, 4, false, 'ERP & integrasi data stok 2026.'],
            ['Bahan baku obat dalam negeri', 'Kompetensi', 'KFSP & Sinkona mendukung TKDN; > 90% bahan baku nasional masih impor.', 2, 4, false, 'Perluas portofolio API lokal.'],
            ['Manajemen kinerja berbasis KPI', 'Sistem', 'Sistem manajemen kinerja diperkuat dan dikaitkan dengan kinerja korporat.', 2 + $lvl, 4, false, 'Cascading KPI sampai individu.'],
        ] as [$name, $cat, $desc, $cur, $tgt, $core, $gap]) {
            Capability::create(['name' => $name, 'category' => $cat, 'description' => $desc, 'current_level' => $cur, 'target_level' => $tgt, 'is_core' => $core, 'gap_action' => $gap]);
        }
    }

    // ------------------------------------------------------------------ ekosistem

    protected function ecosystem(int $year): array
    {
        foreach ([
            ['Kondisi ekonomi & industri farmasi 2025', '2026-01-20', 'eksternal', self::LT.' — Laporan Direksi',
                'Ekonomi Indonesia tumbuh ±5,1%; JKN tetap pendorong utama permintaan. Industri farmasi tertekan ketergantungan bahan baku impor, tekanan harga JKN & e-katalog, dan persaingan.',
                'Bukan sekadar mengejar volume; efisiensi, ketahanan rantai pasok, dan adaptasi layanan digital menentukan.'],
            ['Kinerja internal 2025', '2026-01-28', 'internal', self::LT.' — Ikhtisar Keuangan',
                'Penjualan neto Rp9,22 T (−7,2%); laba bruto Rp3,06 T (+4%; marjin 33,2%); rugi usaha Rp79,8 M (2024: Rp810,7 M); rugi bersih Rp443,4 M (2024: Rp1,21 T). Arus kas operasi Rp234 M; rasio lancar 0,56x; DER 4,18x.',
                'Arah perbaikan nyata, tetapi pendapatan & likuiditas masih menjadi pekerjaan rumah 2026.'],
            ['Restrukturisasi utang selesai', '2026-01-28', 'internal', self::LT.' — Peristiwa Penting',
                'Pada 16 Desember 2025, 11 kreditur perbankan menyetujui skema restrukturisasi utang Kimia Farma Group.',
                'Fondasi untuk menata arus kas; kepatuhan jadwal pembayaran menjadi syarat kepercayaan.'],
            ['Prospek 2026', '2026-02-05', 'eksternal', self::LT.' — Prospek Usaha',
                'Pasar farmasi nasional diproyeksikan > US$11–13 miliar dengan CAGR 5–7%; kelas menengah & usia harapan hidup mendorong permintaan; kebijakan kemandirian bahan baku obat.',
                'Peluang tumbuh dengan portofolio bermarjin baik dan layanan terintegrasi.'],
        ] as [$title, $date, $scope, $source, $summary, $impl]) {
            if ($year === 2025 && $date >= '2026-02-01') {
                continue;
            }
            EnvironmentScan::create(compact('title', 'scope', 'source', 'summary') + ['scan_date' => $year === 2025 ? '2025-01-20' : $date, 'implications' => $impl, 'status' => 'aktif', 'created_by' => $this->id('strategi')]);
        }

        foreach ([
            ['politik', 'Kemandirian industri farmasi & TKDN', 'Kebijakan mendorong bahan baku obat dalam negeri dan preferensi produk ber-TKDN.', 'peluang', 4, 4, 'naik', 'Perluas portofolio KFSP & Sinkona.'],
            ['politik', 'Pengadaan pemerintah (e-katalog)', 'Tekanan harga dalam pengadaan obat pemerintah.', 'ancaman', 5, 5, 'stabil', 'Bauran produk & efisiensi biaya per unit.'],
            ['ekonomi', 'Kurs & harga API impor', '> 90% bahan baku farmasi nasional impor; pelemahan rupiah menaikkan biaya.', 'ancaman', 5, 4, 'naik', 'Lindung nilai alami & sumber lokal.'],
            ['ekonomi', 'Pertumbuhan belanja kesehatan', 'Pasar farmasi tumbuh 5–7% per tahun.', 'peluang', 4, 4, 'naik', 'Fokus segmen bermarjin baik.'],
            ['sosial', 'Kelas menengah & usia harapan hidup', 'Permintaan obat kronis, consumer health, dan layanan preventif naik.', 'peluang', 4, 5, 'naik', 'Paket layanan apotek–klinik–lab.'],
            ['teknologi', 'Layanan kesehatan digital', 'Konsumen mengharapkan pemesanan obat & konsultasi daring yang cepat.', 'peluang', 4, 5, 'naik', 'Kimia Farma Mobile & omnichannel.'],
            ['teknologi', 'Terapi sel & bioteknologi', 'Stem cell membuka layanan bernilai tambah tinggi.', 'peluang', 3, 3, 'naik', 'Perluas kerja sama RS (RSCM, RSHS, Jawa Timur).'],
            ['lingkungan', 'Limbah farmasi & emisi pabrik', 'Pengelolaan limbah B3 & uji emisi wajib.', 'ancaman', 3, 3, 'stabil', 'Kepatuhan baku mutu & audit rutin.'],
            ['hukum', 'Regulasi BPOM & CPOB', 'Inspeksi CPOB & registrasi produk menentukan izin edar.', 'ancaman', 4, 3, 'stabil', 'Sistem mutu & kesiapan inspeksi.'],
            ['hukum', 'Kebijakan harga obat JKN', 'Tarif & harga obat JKN memengaruhi marjin.', 'ancaman', 4, 4, 'stabil', 'Efisiensi & portofolio.'],
        ] as [$factor, $title, $desc, $type, $impact, $like, $trend, $resp]) {
            PestelAnalysis::create(['factor' => $factor, 'title' => $title, 'description' => $desc, 'impact_type' => $type, 'impact_level' => $impact, 'likelihood' => $like, 'trend' => $trend, 'response' => $resp, 'source' => self::LT.' & analisis manajemen (ilustrasi)']);
        }

        foreach ([
            ['persaingan', 'Produsen farmasi nasional & multinasional', 'Persaingan ketat di obat generik, etikal, dan consumer health.', 5, 'Diferensiasi portofolio & efisiensi biaya.'],
            ['kekuatan_pembeli', 'Pembeli pemerintah & JKN', 'Pengadaan pemerintah menentukan harga secara terpusat.', 5, 'Kurangi ketergantungan; perkuat kanal ritel.'],
            ['kekuatan_pemasok', 'Pemasok API impor', 'Pemasok bahan baku terkonsentrasi di luar negeri.', 4, 'Diversifikasi pemasok & API lokal.'],
            ['pendatang_baru', 'Jaringan apotek modern & platform digital', 'Apotek modern & healthtech tumbuh cepat.', 4, 'Omnichannel & layanan terpadu.'],
            ['produk_pengganti', 'Obat herbal & suplemen', 'Sebagian konsumen beralih ke herbal/suplemen.', 2, 'Portofolio consumer health.'],
        ] as [$force, $title, $desc, $int, $impl]) {
            IndustryForce::create(['force' => $force, 'title' => $title, 'description' => $desc, 'intensity' => $int, 'implication' => $impl]);
        }

        foreach ([
            ['Distribusi ke apotek, RS & pemerintah', 'Distribusi', 'Penjualan segmen distribusi Rp4,33 T (2025).', 47, 'turun', true, 'Ketersediaan, ketepatan kirim, harga kompetitif.', 'Fill rate & visibilitas stok.'],
            ['Pelanggan ritel apotek & klinik', 'Ritel', 'Penjualan segmen ritel Rp3,36 T; 1.050 apotek, 386 klinik.', 36, 'turun', true, 'Obat tersedia, konsultasi apoteker, layanan cepat.', 'Layanan terpadu & digital.'],
            ['Produk manufaktur (etikal, generik, consumer health)', 'Manufaktur', 'Penjualan segmen manufaktur Rp729 M (+33%).', 8, 'naik', true, 'Mutu & harga.', 'Portofolio bermarjin baik.'],
            ['Laboratorium, stem cell & layanan lain', 'Lainnya', 'Segmen lainnya Rp804 M.', 9, 'stabil', true, 'Hasil cepat & akurat; terapi lanjutan.', 'Paket diagnostik & stem cell.'],
        ] as [$seg, $grp, $desc, $share, $growth, $target, $needs, $strat]) {
            MarketAnalysis::create(['segment' => $seg, 'segment_group' => $grp, 'description' => $desc, 'share_percent' => $share, 'growth' => $growth, 'is_target' => $target,
                'needs' => $needs, 'strategy' => $strat, 'source' => self::LT.' — penjualan per segmen usaha']);
        }

        foreach ([
            ['PT Kimia Farma (Persero) Tbk', 'BUMN farmasi terintegrasi', 1, 9223, -7.2, -4.8, true, 'Ekosistem hulu–hilir, merek apotek nasional, fasilitas stem cell CPOB.', 'Rugi bersih, likuiditas ketat, utilisasi pabrik rendah.'],
            ['Produsen farmasi swasta nasional terbesar', 'Farmasi swasta', 2, null, null, null, false, 'Portofolio consumer health kuat & efisien.', 'Jaringan ritel sendiri terbatas.'],
            ['Jaringan apotek modern nasional', 'Ritel farmasi', 3, null, null, null, false, 'Ekspansi gerai cepat & digital.', 'Tidak punya manufaktur sendiri.'],
            ['Platform healthtech & apotek daring', 'Digital', 4, null, null, null, false, 'Pengalaman digital & logistik cepat.', 'Bergantung pada mitra apotek.'],
        ] as [$name, $cat, $rank, $rev, $growth, $margin, $self, $str, $weak]) {
            Competitor::create(['name' => $name, 'category' => $cat, 'rank' => $rank, 'data_period' => $self ? 'FY2025 (laporan tahunan)' : 'Kualitatif',
                'revenue' => $rev, 'revenue_growth' => $growth, 'net_margin' => $margin, 'is_self' => $self, 'strengths' => $str, 'weaknesses' => $weak,
                'notes' => $self ? 'Angka dari '.self::LT.' (pendapatan dalam Rp miliar).' : 'Pembanding kualitatif untuk diskusi kelas; lengkapi dengan data publik terbaru.']);
        }

        foreach ([
            ['Cara Pembuatan Obat yang Baik (CPOB)', 'BPOM', 'Peraturan BPOM tentang CPOB', 'Mutu', null, 'Seluruh pabrik & fasilitas stem cell wajib bersertifikat CPOB.', 'Izin produksi & edar.', 'patuh', 'produksi', null],
            ['Cara Distribusi Obat yang Baik (CDOB)', 'BPOM', 'Peraturan BPOM tentang CDOB', 'Distribusi', null, 'Standar gudang & distribusi obat.', 'Izin PBF cabang.', 'patuh', 'distribusi', null],
            ['Tingkat Komponen Dalam Negeri (TKDN) farmasi', 'Kementerian Perindustrian', 'Permenperin tentang TKDN produk farmasi', 'Pengadaan', null, 'Nilai TKDN memengaruhi preferensi pengadaan pemerintah.', 'Daya saing di e-katalog.', 'sebagian', 'produksi', $year === 2025 ? null : '2026-12-31'],
            ['Tata kelola BUMN', 'Kementerian BUMN / Danantara', 'Peraturan Menteri BUMN tentang tata kelola & KPI BUMN', 'Tata kelola', null, 'Penilaian GCG, KPI Direksi, budaya AKHLAK.', 'Remunerasi & evaluasi Direksi.', 'patuh', 'strategi', null],
            ['Kewajiban perjanjian restrukturisasi', '11 kreditur perbankan', 'Perjanjian restrukturisasi utang 16 Desember 2025', 'Keuangan', '2025-12-16', 'Jadwal pembayaran & rasio keuangan yang disepakati.', 'Wanprestasi membatalkan manfaat restrukturisasi.', $year === 2025 ? 'patuh' : 'sebagian', 'cfo', $year === 2025 ? null : '2026-12-31'],
            ['Pelindungan data pribadi', 'Kementerian Komunikasi & Digital', 'UU No. 27/2022 (PDP)', 'Teknologi', '2024-10-17', 'Data pasien laboratorium, klinik, & Kimia Farma Mobile.', 'Sanksi & reputasi.', 'sebagian', 'ti', '2026-12-31'],
            ['Pengelolaan limbah B3 & baku mutu emisi', 'Kementerian Lingkungan Hidup', 'PP No. 22/2021', 'Lingkungan', null, 'Limbah farmasi & emisi pabrik/kendaraan.', 'Izin lingkungan pabrik.', 'patuh', 'produksi', null],
        ] as [$title, $issuer, $ref, $cat, $eff, $desc, $impact, $status, $owner, $due]) {
            Regulation::create(['title' => $title, 'issuer' => $issuer, 'reference_no' => $ref, 'category' => $cat, 'effective_date' => $eff, 'description' => $desc, 'impact' => $impact,
                'compliance_status' => $status, 'owner_id' => $this->id($owner), 'due_date' => $due]);
        }

        $sh = [];
        foreach ([
            'pemegang_saham' => ['Pemegang saham (negara/holding & publik)', 'internal', 'Pemilik', 5, 5, 'Kembali laba, nilai saham pulih, tata kelola.', 'RUPS, paparan publik, laporan ke holding.', 'Sekretaris Perusahaan'],
            'kreditur' => ['Kreditur perbankan (11 bank)', 'eksternal', 'Pemberi dana', 5, 5, 'Pembayaran sesuai jadwal restrukturisasi.', 'Laporan bulanan arus kas & covenant.', 'Treasury'],
            'pasien' => ['Pasien & pelanggan apotek/klinik/lab', 'eksternal', 'Pelanggan', 3, 5, 'Obat tersedia, layanan cepat & ramah.', 'Survei pelanggan & aplikasi.', 'Customer Experience'],
            'pemerintah' => ['Kementerian Kesehatan, BPJS & pengadaan pemerintah', 'eksternal', 'Pembeli & regulator', 5, 4, 'Ketersediaan obat program, harga wajar.', 'Koordinasi program & e-katalog.', 'Hubungan Pemerintah'],
            'bpom' => ['BPOM', 'eksternal', 'Regulator', 5, 3, 'Kepatuhan CPOB & CDOB.', 'Inspeksi & pelaporan.', 'Quality Assurance'],
            'pemasok' => ['Pemasok bahan baku (API) & kemasan', 'eksternal', 'Pemasok', 3, 4, 'Pembayaran tepat waktu, volume stabil.', 'Kontrak jangka panjang.', 'Pengadaan'],
            'karyawan' => ['Karyawan kelompok usaha (9.838 orang)', 'internal', 'SDM', 4, 5, 'Kepastian kerja, pengembangan, budaya AKHLAK.', 'Town hall transformasi & survei budaya.', 'Human Capital'],
            'rs_mitra' => ['Rumah sakit mitra (RSCM, RSHS, RS di Jawa Timur)', 'eksternal', 'Mitra', 3, 4, 'Layanan stem cell bermutu & aman.', 'Perjanjian kerja sama.', 'Layanan Kesehatan'],
        ] as $key => [$name, $type, $cat, $infl, $int, $exp, $strat, $cp]) {
            $sh[$key] = Stakeholder::create(['name' => $name, 'type' => $type, 'category' => $cat, 'influence' => $infl, 'interest' => $int, 'expectations' => $exp, 'engagement_strategy' => $strat, 'contact_person' => $cp]);
        }

        $issues = [];
        foreach ([
            'rugi' => ['Masih merugi meski membaik', 'Rugi bersih Rp443,4 M (2024: Rp1,21 T); beban keuangan Rp590 M menggerus perbaikan operasi.', 'keuangan', 5, 5, 'cfo', ['pemegang_saham', 'kreditur']],
            'likuiditas' => ['Likuiditas ketat', 'Rasio lancar 0,56x; kas akhir Rp377 M; DER 4,18x.', 'keuangan', 5, 5, 'cfo', ['kreditur', 'pemasok']],
            'penjualan' => ['Penjualan turun 7,2%', 'Distribusi −10% dan ritel −11%; sebagian karena reorientasi ke pendapatan berkualitas.', 'pelanggan', 4, 5, 'dirut', ['pasien', 'pemerintah']],
            'utilisasi' => ['Utilisasi pabrik & produktivitas rendah', 'Disorot Dewan Komisaris sebagai sumber tekanan biaya; 9 pabrik.', 'proses', 5, 4, 'produksi', ['pemegang_saham']],
            'api' => ['Ketergantungan bahan baku impor', '> 90% bahan baku farmasi nasional impor; terpapar kurs.', 'proses', 4, 4, 'produksi', ['pemasok']],
            'stok' => ['Visibilitas stok & persediaan tinggi', 'Persediaan dan kedaluwarsa menahan kas.', 'proses', 4, 4, 'distribusi', ['pasien']],
            'budaya' => ['Budaya kinerja & akuntabilitas', 'Manajemen kinerja harus terhubung ke target korporat.', 'sdm', 3, 4, 'sdm', ['karyawan']],
        ] as $key => [$title, $desc, $persp, $urg, $imp, $owner, $stks]) {
            $issues[$key] = StrategicIssue::create(['title' => $title, 'description' => $desc, 'perspective' => $persp, 'urgency' => $urg, 'impact' => $imp,
                'status' => 'ditangani', 'source' => self::LT.' & analisis manajemen', 'owner_id' => $this->id($owner)]);
            $issues[$key]->stakeholders()->sync(collect($stks)->map(fn ($s) => $sh[$s]->id));
        }

        $sort = 0;
        foreach ([
            ['S', 'Ekosistem hulu–hilir', 'Manufaktur, distribusi, apotek, klinik, lab.', 0.13, 3],
            ['S', 'Merek & jaringan apotek nasional', '1.050 apotek, 386 klinik, 65 lab.', 0.12, 4],
            ['S', 'Marjin bruto membaik', '33,2% (2024: 29,7%).', 0.08, 3],
            ['S', 'Fasilitas stem cell CPOB di RS', 'Satu-satunya di Indonesia.', 0.05, 4],
            ['W', 'Rugi & beban keuangan tinggi', 'Rugi bersih Rp443 M; beban keuangan Rp590 M.', 0.14, 1],
            ['W', 'Likuiditas & leverage', 'Rasio lancar 0,56x; DER 4,18x.', 0.12, 1],
            ['W', 'Utilisasi pabrik rendah', 'Biaya tetap per unit tinggi.', 0.08, 2],
            ['O', 'Pasar farmasi tumbuh 5–7%', 'JKN & kelas menengah.', 0.08, 3],
            ['O', 'Kemandirian bahan baku & TKDN', 'Preferensi produk lokal.', 0.06, 3],
            ['O', 'Layanan kesehatan digital', 'Omnichannel apotek.', 0.04, 2],
            ['T', 'Tekanan harga JKN & e-katalog', 'Marjin produk program tertekan.', 0.05, 2],
            ['T', 'Kurs & harga API impor', 'Biaya bahan baku naik.', 0.03, 2],
            ['T', 'Apotek modern & healthtech', 'Persaingan ritel.', 0.02, 2],
        ] as [$type, $title, $desc, $w, $r]) {
            SwotItem::create(['type' => $type, 'title' => $title, 'description' => $desc, 'weight' => $w, 'rating' => $r, 'sort' => ++$sort, 'source' => self::LT.' & analisis manajemen']);
        }

        return $issues;
    }

    protected function tows(array $issues): void
    {
        foreach ([
            ['SO', 'Layanan terpadu apotek–klinik–lab', 'Satu perjalanan pasien: konsultasi, pemeriksaan, obat, tindak lanjut.', 4, ['penjualan']],
            ['SO', 'Portofolio bermarjin baik & TKDN', 'Etikal, consumer health, dan produk ber-TKDN tinggi.', 4, ['rugi']],
            ['WO', 'Rasionalisasi pabrik & insourcing produk', 'Konsolidasi lini produksi untuk menaikkan utilisasi.', 5, ['utilisasi']],
            ['WO', 'Bahan baku dalam negeri (KFSP, Sinkona)', 'Kurangi impor API untuk produk prioritas.', 3, ['api']],
            ['ST', 'Omnichannel Kimia Farma Mobile', 'Lawan apotek modern & healthtech dengan jaringan fisik + digital.', 3, ['penjualan']],
            ['WT', 'Disiplin kas & modal kerja pasca-restrukturisasi', 'Persediaan turun, piutang tertagih, aset non-produktif dioptimalkan.', 5, ['likuiditas', 'stok']],
            ['WT', 'Manajemen kinerja & budaya AKHLAK', 'KPI individu terhubung ke Peta Strategi.', 3, ['budaya']],
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
            'T1' => ['Restrukturisasi Keuangan & Efisiensi', 'Kembali laba: kas, modal kerja, biaya, dan aset dikelola disiplin.', '#B22234'],
            'T2' => ['Penguatan Portofolio Produk & Layanan', 'Produk bermarjin baik, layanan klinik, diagnostik, dan stem cell.', '#1E6FA8'],
            'T3' => ['Sinergi Ekosistem Hulu–Hilir', 'Pabrik, bahan baku, distribusi, dan ritel bergerak sebagai satu sistem.', '#E08A1E'],
            'T4' => ['Transformasi Operasional, Digital & SDM', 'Digitalisasi rantai pasok & pelanggan; budaya AKHLAK & kinerja.', '#2F8F5B'],
        ] as $code => [$name, $desc, $color]) {
            $themes[$code] = StrategicTheme::create(['code' => $code, 'name' => $name, 'description' => $desc, 'color' => $color, 'sort' => count($themes) + 1]);
        }
        foreach ([
            ['T1', 'Kualitas pendapatan di atas volume', 'organisasi', 'Penurunan penjualan 2025 sebagian karena reorientasi ke pendapatan berkualitas.'],
            ['T2', 'Bergeser ke produk bermarjin lebih baik', 'produk', 'Strategi portofolio dalam Laporan Direksi 2025.'],
            ['T3', 'Integrasi apotek–klinik–laboratorium', 'saluran', 'Titik interaksi utama dengan pelanggan.'],
            ['T4', 'Digitalisasi rantai pasok & inventori', 'operasi', 'Visibilitas stok end-to-end.'],
        ] as [$th, $title, $type, $why]) {
            StrategicChoice::create(['strategic_theme_id' => $themes[$th]->id, 'title' => $title, 'choice_type' => $type, 'rationale' => $why, 'status' => 'dipilih']);
        }

        $obj = [];
        foreach ([
            'F1' => ['keuangan', 'T2', 'Pendapatan tumbuh kembali dengan kualitas lebih baik', 'Penjualan pulih ke ±Rp9,8 T dengan porsi produk bermarjin baik naik.', 'dirut', 2],
            'F2' => ['keuangan', 'T1', 'Kembali ke profitabilitas', 'Rugi bersih menyempit tajam; marjin bruto naik; beban usaha turun.', 'cfo', 3],
            'F3' => ['keuangan', 'T1', 'Likuiditas & struktur keuangan sehat', 'Arus kas operasi positif, rasio lancar naik, persediaan turun.', 'cfo', 3],
            'C1' => ['pelanggan', 'T3', 'Obat tersedia tepat waktu bagi mitra & pemerintah', 'Fill rate distribusi tinggi.', 'distribusi', 2],
            'C2' => ['pelanggan', 'T3', 'Jaringan apotek & klinik yang produktif', 'Jaringan optimal, penjualan per apotek naik.', 'ritel', 2],
            'C3' => ['pelanggan', 'T4', 'Pengalaman pelanggan & kanal digital', 'Kimia Farma Mobile & marketplace; pelanggan merekomendasikan.', 'ritel', 1],
            'C4' => ['pelanggan', 'T2', 'Layanan diagnostik & terapi bernilai tambah', 'Laboratorium & stem cell tumbuh.', 'layanan', 1.5],
            'P1' => ['proses', 'T1', 'Keunggulan operasi manufaktur', 'Utilisasi naik, mutu konsisten, kesiapan industri 4.0.', 'produksi', 2],
            'P2' => ['proses', 'T3', 'Rantai pasok tangguh & bahan baku lokal', 'Porsi bahan baku lokal naik; kedaluwarsa turun.', 'produksi', 1.5],
            'P3' => ['proses', 'T1', 'Optimalisasi aset & kepatuhan restrukturisasi', 'Aset non-produktif dimonetisasi; kewajiban restrukturisasi dipenuhi.', 'cfo', 2],
            'P4' => ['proses', 'T4', 'Tata kelola, mutu & kepatuhan', 'Temuan audit ditindaklanjuti; inspeksi CPOB tanpa temuan kritis.', 'strategi', 1],
            'L1' => ['sdm', 'T4', 'Organisasi ramping & produktif', 'Penjualan per karyawan naik; kompetensi terjaga.', 'sdm', 1.5],
            'L2' => ['sdm', 'T4', 'Budaya AKHLAK & manajemen kinerja', 'KPI individu terhubung ke KPI korporat.', 'sdm', 1],
            'L3' => ['sdm', 'T4', 'Sistem & data terintegrasi', 'ERP dan sistem inventori terhubung di seluruh entitas.', 'ti', 1],
        ] as $code => [$persp, $th, $title, $desc, $owner, $w]) {
            $obj[$code] = StrategicObjective::create(['code' => $code, 'perspective' => $persp, 'strategic_theme_id' => $themes[$th]->id, 'title' => $title,
                'description' => $desc, 'owner_id' => $this->id($owner), 'weight' => $w, 'sort' => count($obj) + 1]);
        }
        foreach ([
            ['L1', 'P1', 3], ['L2', 'P4', 2], ['L2', 'L1', 2], ['L3', 'P2', 3], ['L3', 'C3', 2],
            ['P1', 'C1', 3], ['P1', 'F2', 2], ['P2', 'C1', 3], ['P2', 'F3', 2], ['P3', 'F3', 3], ['P4', 'P1', 1],
            ['C1', 'F1', 3], ['C2', 'F1', 3], ['C3', 'C2', 2], ['C4', 'F1', 2],
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
            ['F1.1', 'F1', 'Penjualan neto konsolidasian', 'Rp juta', 'higher', 'sum', 0, true, true, 'dirut', 'Penjualan neto seluruh segmen', 'Laporan keuangan konsolidasian', 9938958, 10200000, 9223328, 9800000, .96, $lt],
            ['F1.2', 'F1', 'Penjualan segmen manufaktur', 'Rp juta', 'higher', 'sum', 0, true, false, 'produksi', 'Penjualan neto segmen manufaktur', 'Laporan segmen', 550246, 650000, 729482, 850000, .98, $lt],
            ['F1.3', 'F1', 'Porsi produk bermarjin baik dalam penjualan', '%', 'higher', 'average', 1, true, false, 'dirut', 'Penjualan etikal + consumer health + layanan ÷ penjualan × 100', 'Analisis portofolio', 35.0, 38.0, 39.0, 41.0, .98, $il],
            ['F2.1', 'F2', 'Rugi tahun berjalan', 'Rp juta', 'lower', 'sum', 0, true, true, 'cfo', 'Rugi tahun berjalan konsolidasian (angka positif = rugi; makin kecil makin baik)', 'Laporan laba rugi', 1208173, 600000, 443357, 150000, 1.15, $lt],
            ['F2.2', 'F2', 'Marjin laba kotor', '%', 'higher', 'average', 2, true, true, 'cfo', 'Laba bruto ÷ penjualan neto × 100', 'Rasio keuangan', 29.65, 32.00, 33.21, 34.50, .99, $lt],
            ['F2.3', 'F2', 'Beban usaha terhadap penjualan', '%', 'lower', 'average', 1, true, false, 'cfo', 'Beban usaha ÷ penjualan neto × 100', 'Laporan laba rugi', 38.1, 36.5, 35.9, 33.0, 1.04, $lt],
            ['F2.4', 'F2', 'Beban keuangan', 'Rp juta', 'lower', 'sum', 0, true, false, 'cfo', 'Beban bunga & keuangan lainnya', 'Laporan laba rugi', 641962, 600000, 590130, 520000, 1.03, $lt],
            ['F3.1', 'F3', 'Arus kas dari aktivitas operasi', 'Rp juta', 'higher', 'sum', 0, true, true, 'cfo', 'Arus kas bersih aktivitas operasi', 'Laporan arus kas', 153551, 300000, 234364, 450000, .78, $lt],
            ['F3.2', 'F3', 'Rasio lancar', 'kali', 'higher', 'last', 2, true, true, 'cfo', 'Aset lancar ÷ liabilitas jangka pendek', 'Rasio keuangan', 0.49, 0.60, 0.56, 0.75, .95, $lt],
            ['F3.3', 'F3', 'Rasio liabilitas terhadap ekuitas (DER)', 'kali', 'lower', 'last', 2, true, false, 'cfo', 'Total liabilitas ÷ total ekuitas', 'Rasio keuangan', 3.35, 3.50, 4.18, 3.80, 1.03, $lt],
            ['F3.4', 'F3', 'Hari persediaan (DIO)', 'hari', 'lower', 'last', 0, true, false, 'distribusi', 'Persediaan ÷ beban pokok penjualan × 365', 'Laporan keuangan', 150, 135, 140, 120, 1.05, $il],
            ['C1.1', 'C1', 'Tingkat pemenuhan pesanan distribusi (fill rate)', '%', 'higher', 'average', 1, false, true, 'distribusi', 'Baris pesanan terpenuhi lengkap ÷ baris pesanan × 100', 'Sistem distribusi', 88.0, 92.0, 90.5, 94.0, .98, $il],
            ['C1.2', 'C1', 'Penjualan segmen distribusi', 'Rp juta', 'higher', 'sum', 0, true, false, 'distribusi', 'Penjualan neto segmen distribusi', 'Laporan segmen', 4802268, 4900000, 4329153, 4600000, .95, $lt],
            ['C2.1', 'C2', 'Penjualan segmen ritel', 'Rp juta', 'higher', 'sum', 0, true, false, 'ritel', 'Penjualan neto segmen ritel (apotek, klinik)', 'Laporan segmen', 3786081, 3700000, 3360667, 3500000, .96, $lt],
            ['C2.2', 'C2', 'Apotek beroperasi', 'unit', 'higher', 'last', 0, false, false, 'ritel', 'Outlet apotek Kimia Farma aktif', 'Data jaringan', 1054, 1060, 1050, 1060, .995, $lt],
            ['C2.3', 'C2', 'Klinik kesehatan beroperasi', 'unit', 'higher', 'last', 0, false, false, 'ritel', 'Outlet klinik kesehatan aktif', 'Data jaringan', 356, 380, 386, 400, .98, $lt],
            ['C2.4', 'C2', 'Penjualan rata-rata per apotek per bulan', 'Rp juta', 'higher', 'average', 0, false, false, 'ritel', 'Penjualan ritel ÷ jumlah apotek ÷ 12', 'Laporan ritel', 274, 290, 267, 285, .97, $il],
            ['C3.1', 'C3', 'Porsi penjualan kanal digital', '%', 'higher', 'average', 1, false, false, 'ritel', 'Penjualan Kimia Farma Mobile + marketplace ÷ penjualan ritel × 100', 'Laporan omnichannel', 4.0, 6.0, 5.5, 8.0, .8, $il],
            ['C3.2', 'C3', 'Net Promoter Score pelanggan apotek', 'skor', 'higher', 'average', 0, false, false, 'ritel', '% promotor − % detraktor', 'Survei pelanggan', 42, 45, 46, 50, 1.0, $il],
            ['C4.1', 'C4', 'Pemeriksaan laboratorium', 'ribu', 'higher', 'sum', 0, false, false, 'layanan', 'Pemeriksaan laboratorium klinik (ribu tes)', 'Sistem laboratorium', 3100, 3300, 3250, 3500, .97, $il],
            ['C4.2', 'C4', 'Pasien terapi stem cell', 'orang', 'higher', 'sum', 0, false, false, 'layanan', 'Pasien menerima produk/terapi stem cell', 'Unit stem cell', 180, 240, 260, 340, 1.0, $il],
            ['C4.3', 'C4', 'Laboratorium medis beroperasi', 'unit', 'higher', 'last', 0, false, false, 'layanan', 'Outlet laboratorium medis aktif', 'Data jaringan', 66, 66, 65, 66, 1.0, $lt],
            ['P1.1', 'P1', 'Utilisasi kapasitas pabrik', '%', 'higher', 'average', 0, false, true, 'produksi', 'Output aktual ÷ kapasitas terpasang × 100 (9 pabrik)', 'Sistem produksi', 52, 60, 58, 68, .9, $il],
            ['P1.2', 'P1', 'Batch lulus QC pada pemeriksaan pertama', '%', 'higher', 'average', 1, false, false, 'produksi', 'Batch lulus tanpa rework ÷ batch diperiksa × 100', 'Quality Control', 97.0, 98.0, 97.8, 98.5, .995, $il],
            ['P1.3', 'P1', 'Pabrik dengan INDI 4.0 Level 3', 'unit', 'higher', 'last', 0, false, false, 'produksi', 'Pabrik dengan skor INDI 4.0 ≥ 3,0', 'Kementerian Perindustrian', 0, 1, 1, 2, 1.0, $lt],
            ['P2.1', 'P2', 'Porsi bahan baku dari sumber dalam negeri', '%', 'higher', 'average', 1, false, false, 'produksi', 'Nilai bahan baku lokal (termasuk KFSP & Sinkona) ÷ total × 100', 'Sistem pengadaan', 8.0, 10.0, 10.5, 12.0, .95, $il],
            ['P2.2', 'P2', 'Persediaan kedaluwarsa & rusak terhadap penjualan', '%', 'lower', 'average', 2, false, false, 'distribusi', 'Nilai stok kedaluwarsa/rusak ÷ penjualan × 100', 'Sistem inventori', 1.40, 1.20, 1.25, 1.00, 1.08, $il],
            ['P3.1', 'P3', 'Hasil optimalisasi aset non-produktif', 'Rp juta', 'higher', 'sum', 0, true, false, 'cfo', 'Kas dari penjualan/kerja sama pemanfaatan aset non-produktif', 'Laporan aset', 0, 250000, 180000, 400000, .7, $il],
            ['P3.2', 'P3', 'Kepatuhan jadwal pembayaran restrukturisasi', '%', 'higher', 'last', 0, true, true, 'cfo', 'Kewajiban dibayar tepat waktu ÷ kewajiban jatuh tempo × 100', 'Treasury', 100, 100, 100, 100, 1.0, $il],
            ['P4.1', 'P4', 'Temuan audit ditindaklanjuti tepat waktu', '%', 'higher', 'average', 0, false, false, 'strategi', 'Temuan SPI/eksternal selesai sesuai tenggat ÷ temuan jatuh tempo × 100', 'SPI', 70, 85, 82, 90, .92, $il],
            ['P4.2', 'P4', 'Inspeksi CPOB/CDOB tanpa temuan kritis', '%', 'higher', 'last', 0, false, false, 'produksi', 'Inspeksi tanpa temuan kritis ÷ total inspeksi × 100', 'Quality Assurance', 95, 100, 100, 100, 1.0, $il],
            ['L1.1', 'L1', 'Penjualan per karyawan', 'Rp juta', 'higher', 'sum', 1, false, false, 'sdm', 'Penjualan neto ÷ jumlah karyawan kelompok usaha', 'HRIS & keuangan', 981.2, 1000.0, 937.5, 1020.0, .96, $lt],
            ['L1.2', 'L1', 'Jumlah karyawan kelompok usaha', 'orang', 'lower', 'last', 0, false, false, 'sdm', 'Karyawan seluruh entitas grup akhir perioda', 'HRIS', 10129, 9900, 9838, 9600, 1.0, $lt],
            ['L1.3', 'L1', 'Jam pelatihan per karyawan', 'jam', 'higher', 'sum', 1, false, false, 'sdm', 'Total jam pelatihan ÷ jumlah karyawan', 'HRIS', 18.0, 20.0, 19.0, 22.0, .9, $il],
            ['L2.1', 'L2', 'Indeks budaya AKHLAK', 'skor', 'higher', 'last', 0, false, false, 'sdm', 'Survei budaya AKHLAK (skala 100)', 'Survei budaya', 72, 75, 76, 78, 1.0, $il, 'semesteran'],
            ['L2.2', 'L2', 'Karyawan dengan KPI individu terhubung ke KPI korporat', '%', 'higher', 'last', 0, false, false, 'sdm', 'Karyawan dengan kontrak kinerja turunan Peta Strategi ÷ total × 100', 'Sistem manajemen kinerja', 40, 70, 75, 95, .85, $il],
            ['L3.1', 'L3', 'Entitas dengan ERP & inventori terintegrasi', '%', 'higher', 'last', 0, false, false, 'ti', 'Entitas/anak usaha terhubung ke ERP grup ÷ total entitas × 100', 'Program ERP', 50, 70, 67, 90, .78, $il],
        ];
    }

    // ------------------------------------------------------------------ tinjauan kinerja

    protected function reviews(int $year): array
    {
        $r = [];
        $rec = $this->id('strategi');
        $venue = 'Kantor Pusat Kimia Farma, Jakarta';
        if ($year === 2025) {
            $r['tahunan'] = PerformanceReview::create([
                'title' => 'Tinjauan Kinerja Tahunan 2025', 'review_type' => 'tahunan', 'review_date' => '2026-02-24', 'period_label' => 'Tahun 2025',
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi entitas anak, PMO Transformasi',
                'summary' => "Penjualan neto Rp9,22 T (−7,2%; 90% target); distribusi & ritel turun.\nLaba bruto Rp3,06 T (+4%); marjin bruto 33,2%; beban usaha turun 13%.\nRugi usaha menyempit ke Rp79,8 M; rugi bersih Rp443,4 M (2024: Rp1,21 T).\nArus kas operasi Rp234 M (78% target); rasio lancar 0,56x.\nRestrukturisasi utang dengan 11 bank selesai 16 Desember 2025.",
                'decisions' => "1. Target 2026: penjualan Rp9,8 T, rugi ≤ Rp150 M, arus kas operasi Rp450 M, rasio lancar 0,75x.\n2. Prioritas: kepatuhan restrukturisasi, utilisasi pabrik, persediaan, portofolio bermarjin baik.\n3. KPI korporat diturunkan ke entitas anak dan individu melalui Ruci BSC.",
                'follow_up' => 'PMO Transformasi menurunkan KPI & target 2026 ke setiap entitas anak paling lambat 15 Januari.', 'status' => 'final', 'recorded_by' => $rec,
            ]);
            ReviewNote::create(['performance_review_id' => $r['tahunan']->id, 'user_id' => $this->id('komisaris'),
                'note' => 'Dewan Komisaris meminta pemantauan bulanan arus kas dan kewajiban restrukturisasi; perbaikan rugi harus ditopang perbaikan kas, bukan hanya pos akuntansi.']);

            return $r;
        }
        foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus'] as $m => $label) {
            $r['rm'.$m] = PerformanceReview::create([
                'title' => 'Rapat Manajemen Bulanan — '.$label.' 2026', 'review_type' => 'rapat_manajemen', 'review_date' => sprintf('2026-%02d-12', $m + 1),
                'period_label' => $label.' 2026', 'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi entitas anak, PMO Transformasi',
                'summary' => match ($m) {
                    3 => 'Lebaran (20 Maret) menurunkan hari produksi; penjualan apotek naik untuk obat bebas & vitamin.',
                    5 => 'Rencana konsolidasi lini produksi disetujui; utilisasi pabrik 59%.',
                    6 => 'Semester I: rugi menyempit 63% yoy; arus kas operasi baru 70% target karena piutang pemerintah.',
                    7 => 'Optimalisasi aset tertinggal: dua aset menunggu persetujuan pemegang saham.',
                    default => 'Tinjauan sasaran merah/kuning dan tindak lanjut keputusan bulan sebelumnya.',
                },
                'decisions' => match ($m) {
                    3 => 'Bangun stok pengaman sebelum Lebaran untuk 50 SKU prioritas.',
                    5 => 'Pindahkan produk dari dua pabrik berutilisasi rendah ke Banjaran & Semarang.',
                    6 => 'Satgas penagihan piutang pemerintah; target DSO turun 10 hari.',
                    7 => 'Ajukan paket optimalisasi aset ke RUPS-LB.',
                    default => 'Lanjutkan rencana aksi; pemilik KPI merah menyampaikan analisis akar masalah.',
                },
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        foreach ([[1, '2026-04-22', 'Kuartal I 2026'], [2, '2026-07-22', 'Kuartal II 2026']] as [$q, $date, $label]) {
            $r['q'.$q] = PerformanceReview::create([
                'title' => 'Tinjauan Kuartalan — '.$label, 'review_type' => 'kuartalan', 'review_date' => $date, 'period_label' => $label,
                'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, Dewan Komisaris (pengamat), PMO Transformasi',
                'summary' => $q === 1 ? 'Penjualan Q1 95% target; marjin bruto 34,1%; kewajiban restrukturisasi dibayar tepat waktu.' : 'Penjualan H1 96% target; rugi 88% dari batas; DIO 138 hari (target 128).',
                'decisions' => $q === 1 ? 'Kurangi SKU lambat bergerak 15%.' : 'Program penurunan persediaan: stop pembelian SKU dengan cover > 6 bulan.',
                'status' => 'final', 'recorded_by' => $rec,
            ]);
        }
        $r['boc'] = PerformanceReview::create([
            'title' => 'Rapat Gabungan Direksi & Dewan Komisaris — Q2 2026', 'review_type' => 'rapat_timwas', 'review_date' => '2026-07-28', 'period_label' => 'Semester I 2026',
            'chair' => 'Komisaris Utama', 'venue' => $venue, 'participants' => 'Dewan Komisaris, Direksi, Komite Audit, Komite Pemantau Risiko',
            'summary' => 'Komisaris menyoroti arus kas operasi, optimalisasi aset yang tertinggal, dan utilisasi pabrik.',
            'decisions' => 'Direksi melaporkan arus kas 13 minggu dan status optimalisasi aset setiap bulan melalui Ruci BSC.', 'status' => 'final', 'recorded_by' => $rec,
        ]);
        ReviewNote::create(['performance_review_id' => $r['boc']->id, 'user_id' => $this->id('komisaris'),
            'note' => 'Mohon rencana kontinjensi bila optimalisasi aset tidak tercapai tahun ini, termasuk dampaknya pada pemenuhan kewajiban restrukturisasi 2027.']);
        $r['rm9'] = PerformanceReview::create([
            'title' => 'Rapat Manajemen Bulanan — September 2026', 'review_type' => 'rapat_manajemen', 'review_date' => '2026-10-12', 'period_label' => 'September 2026',
            'chair' => 'Direktur Utama', 'venue' => $venue, 'participants' => 'Direksi, direksi entitas anak, PMO Transformasi',
            'summary' => 'Realisasi September sebagian masih draf; hari persediaan dikembalikan untuk rekonsiliasi stok konsinyasi.',
            'decisions' => 'Draf notulen — finalkan setelah seluruh realisasi September disetujui.', 'status' => 'draf', 'recorded_by' => $rec,
        ]);

        return $r;
    }

    // ------------------------------------------------------------------ inisiatif

    protected function initiativeData(int $year): array
    {
        if ($year === 2025) {
            $note = 'Angka ilustrasi; arus kas investasi 2025 tercatat Rp136 M di laporan tahunan.';

            return [
                'defs' => [
                    'restrukturisasi' => ['KAEF-25-01', 'Restrukturisasi utang dengan 11 bank', 'Negosiasi skema, persetujuan kreditur, dan penandatanganan perjanjian.', 'T1', 'cfo', '2025-01-01', '2025-12-16', 'selesai', 5, 5, 4, 1, ['F3', 'P3'], 'Restrukturisasi efektif 16 Desember 2025.'],
                    'efisiensi' => ['KAEF-25-02', 'Efisiensi beban usaha & penataan organisasi', 'Kendali belanja, penataan organisasi bertahap, percepatan perputaran persediaan.', 'T1', 'sdm', '2025-01-01', '2025-12-31', 'selesai', 5, 5, 3, 1, ['F2', 'L1'], 'Beban usaha turun 13%.'],
                    'jaringan' => ['KAEF-25-03', 'Optimalisasi jaringan apotek & klinik', 'Evaluasi kinerja outlet, relokasi, dan penambahan klinik.', 'T3', 'ritel', '2025-01-01', '2025-12-31', 'selesai', 4, 4, 2, 2, ['C2'], '386 klinik; apotek 1.050.'],
                    'indi' => ['KAEF-25-04', 'Kesiapan Industri 4.0 Plant Banjaran', 'Otomasi & digitalisasi lini produksi.', 'T4', 'produksi', '2025-02-01', '2025-12-30', 'selesai', 3, 3, 2, 3, ['P1'], 'INDI 4.0 Level 3 (skor 3,01).'],
                    'stemcell' => ['KAEF-25-05', 'Perluasan layanan stem cell', 'Kerja sama RSHS Bandung & RS di Jawa Timur bersama RSCM.', 'T2', 'layanan', '2025-01-01', '2025-12-31', 'selesai', 3, 3, 3, 3, ['C4'], 'Akses terapi stem cell lebih merata.'],
                ],
                'activities' => [
                    ['restrukturisasi', 'Penyusunan skema & proyeksi kas', 'cfo', '2025-01-01', '2025-05-31', 100], ['restrukturisasi', 'Negosiasi & persetujuan 11 kreditur', 'cfo', '2025-04-01', '2025-12-16', 100],
                    ['efisiensi', 'Kendali belanja diskresioner', 'cfo', '2025-01-01', '2025-12-31', 100], ['efisiensi', 'Penataan organisasi tahap 1', 'sdm', '2025-03-01', '2025-12-31', 100],
                    ['jaringan', 'Evaluasi kinerja outlet', 'ritel', '2025-01-01', '2025-06-30', 100], ['jaringan', 'Penambahan klinik di apotek terpilih', 'ritel', '2025-04-01', '2025-12-31', 100],
                    ['indi', 'Digitalisasi lini & asesmen INDI 4.0', 'produksi', '2025-02-01', '2025-12-30', 100],
                    ['stemcell', 'Kerja sama RSHS & RS Jawa Timur', 'layanan', '2025-01-01', '2025-12-31', 100],
                ],
                'milestones' => [
                    ['restrukturisasi', 'Restrukturisasi efektif', '2025-12-16', true], ['indi', 'Sertifikat INDI 4.0 Level 3', '2025-12-30', true], ['stemcell', 'Perjanjian kerja sama RSHS', '2025-06-30', true],
                ],
                'budgets' => [
                    ['restrukturisasi', 'Penasihat keuangan & hukum', 'opex', 18_000_000_000, 16_400_000_000, null],
                    ['efisiensi', 'Program penataan organisasi', 'opex', 60_000_000_000, 52_000_000_000, null],
                    ['jaringan', 'Relokasi apotek & pembukaan klinik', 'capex', 45_000_000_000, 38_500_000_000, $note, 'Kas internal'],
                    ['indi', 'Otomasi lini produksi Banjaran', 'capex', 30_000_000_000, 27_800_000_000, $note, 'Kas internal'],
                    ['stemcell', 'Peralatan unit stem cell', 'capex', 20_000_000_000, 17_900_000_000, $note, 'Kas internal'],
                ],
            ];
        }

        return [
            'defs' => [
                'covenant' => ['KAEF-26-01', 'Pengelolaan kas & kewajiban pasca-restrukturisasi', 'Proyeksi kas 13 minggu, pemantauan covenant, pelaporan ke kreditur.', 'T1', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 1, ['F3', 'P3'], 'Seluruh kewajiban dibayar tepat waktu.'],
                'pabrik' => ['KAEF-26-02', 'Rasionalisasi pabrik & peningkatan utilisasi', 'Konsolidasi lini produksi ke pabrik unggulan.', 'T1', 'produksi', '2026-02-01', '2026-12-31', 'berjalan', 5, 4, 4, 3, ['P1', 'F2'], 'Utilisasi 68%.'],
                'api' => ['KAEF-26-03', 'Bahan baku obat dalam negeri (KFSP & Sinkona)', 'Substitusi API impor untuk produk prioritas.', 'T3', 'produksi', '2026-01-15', '2027-06-30', 'berjalan', 4, 3, 3, 3, ['P2'], 'Porsi bahan baku lokal 12%.'],
                'portofolio' => ['KAEF-26-04', 'Portofolio produk bermarjin baik', 'Etikal, consumer health, dan pemangkasan SKU lambat.', 'T2', 'dirut', '2026-01-01', '2026-12-31', 'berjalan', 5, 4, 3, 2, ['F1', 'F2'], 'Porsi produk bermarjin baik 41%.'],
                'terpadu' => ['KAEF-26-05', 'Layanan terpadu apotek–klinik–lab', 'Rujukan internal, paket pemeriksaan, tindak lanjut terapi.', 'T3', 'ritel', '2026-02-01', '2026-12-31', 'berjalan', 4, 4, 2, 2, ['C2', 'C4'], 'Penjualan per apotek Rp285 juta/bulan.'],
                'omni' => ['KAEF-26-06', 'Omnichannel Kimia Farma Mobile', 'Pesan antar, resep digital, integrasi stok apotek.', 'T4', 'ti', '2026-02-01', '2026-12-31', 'berjalan', 3, 3, 3, 2, ['C3'], 'Porsi kanal digital 8%.'],
                'erp' => ['KAEF-26-07', 'ERP grup & visibilitas stok end-to-end', 'Satu sistem inventori dari pabrik ke apotek.', 'T4', 'ti', '2026-01-15', '2027-03-31', 'berjalan', 5, 4, 4, 3, ['L3', 'P2'], '90% entitas terintegrasi.'],
                'aset' => ['KAEF-26-08', 'Optimalisasi aset non-produktif', 'Penjualan/kerja sama pemanfaatan tanah & bangunan yang tidak dipakai.', 'T1', 'cfo', '2026-01-01', '2026-12-31', 'berjalan', 5, 5, 4, 1, ['P3', 'F3'], 'Kas Rp400 M dari optimalisasi aset.'],
                'stemcell' => ['KAEF-26-09', 'Peningkatan skala layanan stem cell', 'Kapasitas produksi sel & kerja sama RS baru.', 'T2', 'layanan', '2026-03-01', '2026-12-31', 'berjalan', 3, 3, 3, 3, ['C4'], '340 pasien per tahun.'],
                'kinerja' => ['KAEF-26-10', 'Manajemen kinerja & budaya AKHLAK', 'Kontrak kinerja individu turunan Peta Strategi; agen budaya.', 'T4', 'sdm', '2026-01-15', '2026-12-31', 'berjalan', 4, 4, 2, 1, ['L2', 'L1'], '95% karyawan punya KPI terhubung.'],
            ],
            'activities' => [
                ['covenant', 'Proyeksi kas 13 minggu', 'cfo', '2026-01-01', '2026-02-28', 100], ['covenant', 'Pelaporan bulanan ke kreditur', 'cfo', '2026-01-01', '2026-12-31', 75],
                ['pabrik', 'Studi konsolidasi lini', 'produksi', '2026-02-01', '2026-04-30', 100], ['pabrik', 'Transfer produk ke Banjaran & Semarang', 'produksi', '2026-05-01', '2026-11-30', 55], ['pabrik', 'Registrasi variasi ke BPOM', 'produksi', '2026-05-01', '2026-10-31', 70],
                ['api', 'Pemilihan 10 API prioritas', 'produksi', '2026-01-15', '2026-04-30', 100], ['api', 'Uji kesetaraan & registrasi', 'produksi', '2026-04-01', '2027-03-31', 35],
                ['portofolio', 'Pemangkasan SKU lambat bergerak', 'dirut', '2026-01-01', '2026-06-30', 100], ['portofolio', 'Peluncuran produk consumer health', 'dirut', '2026-03-01', '2026-12-31', 60],
                ['terpadu', 'Paket pemeriksaan di klinik apotek', 'layanan', '2026-02-01', '2026-07-31', 100], ['terpadu', 'Rujukan internal klinik–lab–apotek', 'ritel', '2026-04-01', '2026-12-31', 60],
                ['omni', 'Resep digital & pesan antar', 'ti', '2026-02-01', '2026-08-31', 90], ['omni', 'Integrasi stok apotek ke aplikasi', 'ti', '2026-05-01', '2026-12-31', 45],
                ['erp', 'Desain proses & pemilihan sistem', 'ti', '2026-01-15', '2026-04-30', 100], ['erp', 'Go-live KFTD & KFA', 'ti', '2026-05-01', '2026-10-31', 60], ['erp', 'Go-live entitas lain', 'ti', '2026-10-01', '2027-03-31', 0],
                ['aset', 'Inventarisasi & penilaian aset', 'cfo', '2026-01-01', '2026-03-31', 100], ['aset', 'Persetujuan pemegang saham', 'cfo', '2026-04-01', '2026-09-30', 40], ['aset', 'Transaksi aset', 'cfo', '2026-07-01', '2026-12-31', 25],
                ['stemcell', 'Peningkatan kapasitas produksi sel', 'layanan', '2026-03-01', '2026-09-30', 80], ['stemcell', 'Kerja sama RS baru', 'layanan', '2026-05-01', '2026-12-31', 50],
                ['kinerja', 'Kontrak kinerja turunan Peta Strategi', 'sdm', '2026-01-15', '2026-04-30', 100], ['kinerja', 'Agen budaya AKHLAK di setiap unit', 'sdm', '2026-03-01', '2026-12-31', 70],
            ],
            'milestones' => [
                ['covenant', 'Pembayaran kuartal II tepat waktu', '2026-06-30', true], ['pabrik', 'Lini pertama dipindahkan', '2026-07-31', true], ['pabrik', 'Satu pabrik berhenti produksi', '2026-11-30', false],
                ['erp', 'ERP live di KFTD', '2026-08-31', false], ['aset', 'Persetujuan RUPS-LB', '2026-08-31', false], ['portofolio', 'SKU dipangkas 15%', '2026-06-30', true],
                ['kinerja', 'Kontrak kinerja Direksi–VP ditandatangani', '2026-02-28', true],
            ],
            'budgets' => [
                ['covenant', 'Sistem treasury & penasihat', 'opex', 5_000_000_000, 3_600_000_000, null],
                ['pabrik', 'Transfer teknologi & validasi', 'capex', 70_000_000_000, 34_000_000_000, null, 'Kas internal'],
                ['api', 'Pengembangan & registrasi API', 'capex', 40_000_000_000, 13_500_000_000, null, 'Kas internal'],
                ['portofolio', 'Peluncuran produk', 'opex', 30_000_000_000, 21_000_000_000, null],
                ['terpadu', 'Peralatan klinik & lab', 'capex', 25_000_000_000, 18_200_000_000, null, 'Kas internal'],
                ['omni', 'Pengembangan aplikasi', 'capex', 12_000_000_000, 7_800_000_000, null, 'Kas internal'],
                ['erp', 'Lisensi & implementasi ERP', 'capex', 60_000_000_000, 31_000_000_000, null, 'Kas internal'],
                ['aset', 'Penilai & konsultan transaksi', 'opex', 4_000_000_000, 2_100_000_000, null],
                ['stemcell', 'Kapasitas produksi sel', 'capex', 15_000_000_000, 10_500_000_000, null, 'Kas internal'],
                ['kinerja', 'Program budaya & kinerja', 'opex', 3_000_000_000, 2_200_000_000, null],
            ],
        ];
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findingData(int $year): array
    {
        return $year === 2025 ? [
            ['Penjualan 10% di bawah target', 'Penjualan Rp9,22 T vs target Rp10,2 T; distribusi & ritel turun dua digit.', 'tinjauan', 'tahunan', 'pelanggan', 'tinggi', 977000000000, 'F1.1', null, 'komisaris', '2026-02-24',
                [['Program portofolio bermarjin baik 2026', 'dirut', '2026-12-31', 'berjalan', 70]], null],
            ['Arus kas operasi di bawah rencana', 'Arus kas operasi Rp234 M vs Rp300 M; piutang & persediaan menahan kas.', 'tinjauan', 'tahunan', 'keuangan', 'tinggi', null, 'F3.1', 'restrukturisasi', 'cfo', '2026-02-24',
                [['Proyeksi kas 13 minggu & satgas penagihan', 'cfo', '2026-03-31', 'selesai', 100]], ['2026-04-15', 'Proyeksi kas 13 minggu berjalan rutin.']],
        ] : [
            ['Optimalisasi aset tertinggal', 'Baru Rp96 M dari rencana Rp250 M per Agustus; dua aset menunggu persetujuan pemegang saham.', 'timwas', 'boc', 'keuangan', 'kritis', null, 'P3.1', 'aset', 'komisaris', '2026-07-28',
                [['Ajukan paket aset ke RUPS-LB', 'cfo', '2026-08-31', 'berjalan', 60], ['Alternatif kerja sama pemanfaatan aset', 'cfo', '2026-11-30', 'berjalan', 30]], null],
            ['Persediaan masih tinggi', 'DIO 138 hari vs target 128 hari; SKU lambat bergerak di gudang cabang.', 'tinjauan', 'q2', 'proses', 'tinggi', null, 'F3.4', 'erp', 'distribusi', '2026-07-22',
                [['Stop pembelian SKU dengan cover > 6 bulan', 'distribusi', '2026-08-15', 'selesai', 100], ['Program obral SKU mendekati kedaluwarsa', 'ritel', '2026-10-31', 'berjalan', 70]], null],
            ['Integrasi ERP terlambat', 'Go-live KFTD mundur dari Agustus karena migrasi data master produk.', 'internal', 'rm8', 'sdm', 'sedang', null, 'L3.1', 'erp', 'ti', '2026-09-12',
                [['Pembersihan data master produk', 'ti', '2026-10-15', 'berjalan', 80]], null],
            ['Arus kas operasi H1 di bawah target', 'Piutang pemerintah tertunda menahan kas 30% di bawah rencana.', 'tinjauan', 'q2', 'keuangan', 'tinggi', null, 'F3.1', 'covenant', 'cfo', '2026-07-22',
                [['Satgas penagihan piutang pemerintah', 'cfo', '2026-09-30', 'selesai', 100], ['Anjak piutang selektif', 'cfo', '2026-11-30', 'berjalan', 40]], null],
            ['Penjualan kanal digital di bawah target', 'Porsi digital 5,8% vs 8%; stok aplikasi tidak sinkron dengan apotek.', 'pelanggan', 'q2', 'pelanggan', 'sedang', null, 'C3.1', 'omni', 'ritel', '2026-07-22',
                [['Integrasi stok apotek ke aplikasi', 'ti', '2026-12-31', 'berjalan', 45]], null],
        ];
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuityData(int $year): array
    {
        return [
            'dependencies' => $year === 2025 ? [] : [
                ['Persetujuan RUPS-LB atas optimalisasi aset', 'eksternal', 'aset', 'P3', 'Pemegang saham', 'Transaksi aset material membutuhkan persetujuan.', 'cfo', 'tinggi', 'Kajian independen & komunikasi dini dengan pemegang saham.'],
                ['Persetujuan variasi BPOM untuk transfer produk', 'eksternal', 'pabrik', 'P1', 'BPOM', 'Produk yang dipindah pabrik butuh registrasi variasi.', 'produksi', 'sedang', 'Prioritaskan produk dengan dossier lengkap.'],
                ['Data master produk untuk ERP', 'internal', 'erp', 'L3', 'Seluruh entitas anak', 'Kode produk berbeda antar entitas.', 'ti', 'tinggi', 'Satu tim data master lintas entitas.'],
                ['Pembayaran piutang pemerintah', 'eksternal', 'covenant', 'F3', 'Instansi & fasilitas kesehatan pemerintah', 'Kas operasi bergantung pada pembayaran tepat waktu.', 'cfo', 'tinggi', 'Satgas penagihan & anjak piutang selektif.'],
            ],
            'institutionalizations' => [
                ['Siklus KPI bulanan di Ruci BSC', 'sistem', 'Input tanggal 5 → verifikasi keuangan tanggal 7 → persetujuan Direksi tanggal 8 → rapat manajemen tanggal 12.', 'Bulanan', 'strategi', $year === 2025 ? 'rencana' : 'melembaga'],
                ['Rapat arus kas mingguan', 'rapat', 'Proyeksi kas 13 minggu, penagihan, dan pembayaran prioritas.', 'Mingguan', 'cfo', $year === 2025 ? 'berjalan' : 'melembaga'],
                ['PMO Transformasi', 'rapat', 'Pemantauan inisiatif transformasi lintas entitas.', 'Dua mingguan', 'strategi', 'melembaga'],
                ['Tinjauan kuartalan dengan Dewan Komisaris', 'rapat', 'Kinerja, risiko, dan kepatuhan restrukturisasi.', 'Kuartalan', 'dirut', 'berjalan'],
                ['Sales & Operations Planning (S&OP)', 'sop', 'Rencana permintaan, produksi, dan stok disepakati bulanan.', 'Bulanan', 'produksi', $year === 2025 ? 'rencana' : 'berjalan'],
                ['Budaya AKHLAK', 'budaya', 'Agen budaya, apresiasi, dan internalisasi nilai.', 'Berkelanjutan', 'sdm', 'melembaga'],
            ],
        ];
    }
}
