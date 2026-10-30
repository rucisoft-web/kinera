<?php

// Modul ajar Strategic Leadership Lab: satu entri per sesi, urutan dan judul sama dengan rundown workshop (resources/views/filament/pages/workshop-kinera.blade.php).
// Tautan layar memakai jalur relatif /admin/...; kunci 'bantuan' merujuk resources/bantuan/{kunci}.php.

return [
    [
        'no' => '1', 'hari' => 'Hari 1', 'waktu' => '09.00–10.15', 'durasi' => '75 menit', 'jenis' => 'Paparan',
        'judul' => 'Balanced Scorecard untuk sektor publik',
        'tujuan' => [
            'Menjelaskan empat hambatan pelaksanaan strategi dan kaitannya dengan pengalaman instansi.',
            'Membedakan Balanced Scorecard sebagai sistem manajemen strategi dari sekadar daftar indikator.',
            'Menyusun urutan empat perspektif sektor publik dan logika sebab-akibatnya.',
        ],
        'konsep' => [
            ['Empat hambatan strategi', 'Kaplan & Norton mencatat hambatan visi (strategi tidak dipahami), orang (insentif tidak terkait strategi), sumber daya (anggaran lepas dari strategi), dan manajemen (rapat membahas operasional, bukan strategi).'],
            ['BSC sebagai sistem', 'BSC menerjemahkan strategi menjadi sasaran, ukuran, target, dan inisiatif, lalu mengikatnya dengan siklus anggaran, pelaporan, dan rapat tinjauan.'],
            ['Perspektif sektor publik', 'Pemangku Kepentingan di puncak karena misi instansi adalah layanan publik. Keuangan menjadi penopang, diikuti Proses Layanan Internal dan Pembelajaran & Pertumbuhan.'],
            ['Hubungan sebab-akibat', 'Sasaran disusun sebagai rantai hipotesis: SDM dan sistem yang kuat memperbaiki proses, proses yang baik memberi layanan dan hasil bagi pemangku kepentingan.'],
        ],
        'kasus' => 'Buka Peta Strategi demo UIN Sunan Kalijaga. Perhatikan 14 sasaran dari 8 Sasaran Program Renstra 2025–2029 yang tersusun dalam empat perspektif, dengan Pemangku Kepentingan di puncak. Telusuri satu panah dari perspektif Pembelajaran sampai Pemangku Kepentingan, lalu baca IK di balik sasaran yang dilalui.',
        'layar' => [['Kenali Kinera', '/admin/kenali-kinera'], ['Peta Strategi', '/admin/peta-strategi']],
        'lembar' => [
            'Tuliskan tiga gejala yang menunjukkan Renstra instansi Anda belum "hidup" (misalnya capaian baru diketahui saat LKj).',
            'Petakan setiap gejala ke salah satu dari empat hambatan strategi.',
            'Sebutkan satu sasaran untuk setiap perspektif pada instansi Anda, lalu gambar panah sebab-akibatnya.',
        ],
        'diskusi' => [
            'Hambatan mana yang paling terasa di instansi Anda, dan apa buktinya?',
            'Mengapa perspektif Keuangan tidak diletakkan di puncak pada instansi pemerintah?',
            'Apa beda Renstra yang "ditetapkan" dengan Renstra yang "dikelola"?',
        ],
        'kuis' => [
            ['Pada peta strategi instansi pemerintah, perspektif apa yang ditempatkan di puncak?', ['Keuangan', 'Pemangku Kepentingan', 'Proses Layanan Internal', 'Pembelajaran & Pertumbuhan'], 1, 'Misi instansi pemerintah adalah melayani pemangku kepentingan, sehingga hasil bagi mereka menjadi tujuan akhir. Keuangan berperan sebagai penopang.'],
            ['"Rapat pimpinan lebih banyak membahas kegiatan daripada capaian sasaran" termasuk hambatan apa?', ['Hambatan visi', 'Hambatan orang', 'Hambatan sumber daya', 'Hambatan manajemen'], 3, 'Hambatan manajemen terjadi ketika forum pimpinan tidak meninjau strategi secara rutin.'],
            ['Pernyataan yang paling tepat tentang Balanced Scorecard adalah ...', ['Daftar indikator kinerja tahunan', 'Sistem untuk menerjemahkan dan mengelola strategi', 'Format laporan keuangan BLU', 'Metode audit kinerja'], 1, 'BSC bukan sekadar daftar indikator; ia menghubungkan strategi dengan sasaran, ukuran, target, inisiatif, anggaran, dan tinjauan.'],
        ],
        'bantuan' => ['sasaran-strategis', 'relasi-sasaran'],
    ],
    [
        'no' => '2', 'hari' => 'Hari 1', 'waktu' => '10.30–12.00', 'durasi' => '90 menit', 'jenis' => 'Paparan',
        'judul' => 'Anatomi scorecard: sasaran, ukuran, target, dan inisiatif',
        'tujuan' => [
            'Menguraikan satu baris scorecard: sasaran, ukuran, target, dan inisiatif.',
            'Membedakan ukuran hasil (lag) dan pendorong (lead), serta memilih 1–2 IKU per sasaran.',
            'Membaca scorecard di Kinera dari Peta Strategi sampai IK dan inisiatif.',
        ],
        'konsep' => [
            ['Satu baris scorecard', 'Sasaran (apa yang ingin dicapai) → ukuran (bagaimana mengetahui keberhasilan) → target (seberapa baik dan kapan) → inisiatif (apa yang dikerjakan untuk mencapainya).'],
            ['Ukuran hasil (lag)', 'Mengukur capaian akhir, misalnya persentase lulusan bekerja. Penting, tetapi baru diketahui belakangan.'],
            ['Ukuran pendorong (lead)', 'Mengukur hal yang bergerak lebih dulu dan memengaruhi hasil, misalnya persentase prodi dengan kurikulum berbasis capaian. Memberi sinyal dini.'],
            ['Sedikit tetapi tajam', 'Satu sasaran cukup 1–2 IKU. Terlalu banyak IK membuat pimpinan kehilangan fokus.'],
        ],
        'kasus' => 'Buka layar Indikator Kinerja dan saring kolom Jenis. Bandingkan IK hasil pada sasaran Pemangku Kepentingan dengan IK pendorong pada sasaran Proses dan Pembelajaran. Lalu buka Pusat Kendali untuk melihat bagaimana capaian IK, sasaran, dan inisiatif terlambat disajikan untuk pimpinan.',
        'layar' => [['Indikator Kinerja', '/admin/kpis'], ['Pusat Kendali', '/admin/pusat-kendali'], ['Peta Strategi', '/admin/peta-strategi']],
        'lembar' => [
            'Pilih dua sasaran dari Peta Strategi demo.',
            'Untuk setiap sasaran, tulis satu ukuran hasil dan satu ukuran pendorong.',
            'Lengkapi target tahun ini dan satu inisiatif yang mendorong ukuran tersebut.',
            'Di akun latihan, buka salah satu IK lalu isi kolom "Jenis indikator" sesuai hasil diskusi.',
        ],
        'diskusi' => [
            'IK apa di instansi Anda yang sebenarnya kegiatan, bukan ukuran hasil?',
            'Ukuran pendorong apa yang bisa memberi peringatan dini sebelum IKU utama meleset?',
            'Bagaimana memastikan setiap inisiatif benar-benar mendorong sasaran tertentu?',
        ],
        'kuis' => [
            ['Manakah yang merupakan ukuran pendorong (lead) untuk sasaran "meningkatnya kualitas lulusan"?', ['Persentase lulusan bekerja dalam 6 bulan', 'Rata-rata masa tunggu kerja', 'Persentase mahasiswa yang mengikuti magang bersertifikat', 'Indeks kepuasan pengguna lulusan'], 2, 'Partisipasi magang terjadi lebih dulu dan memengaruhi kesiapan kerja; tiga pilihan lain adalah ukuran hasil.'],
            ['Urutan satu baris scorecard yang benar adalah ...', ['Inisiatif → target → ukuran → sasaran', 'Sasaran → ukuran → target → inisiatif', 'Target → sasaran → inisiatif → ukuran', 'Ukuran → inisiatif → sasaran → target'], 1, 'Sasaran menentukan apa yang diukur, lalu target, lalu inisiatif untuk mencapainya.'],
            ['"Terlaksananya pelatihan dosen" sebaiknya diperlakukan sebagai ...', ['IKU sasaran SDM', 'Kegiatan dalam inisiatif', 'Target tahunan', 'Perspektif'], 1, 'Pelatihan adalah kegiatan. Ukurannya bisa berupa peningkatan kompetensi atau persentase dosen tersertifikasi.'],
        ],
        'bantuan' => ['indikator-kinerja', 'inisiatif'],
    ],
    [
        'no' => '3', 'hari' => 'Hari 1', 'waktu' => '13.00–14.45', 'durasi' => '105 menit', 'jenis' => 'Latihan kelompok',
        'judul' => 'Diagnosis kinerja dan penyusunan sasaran strategis',
        'tujuan' => [
            'Membaca dokumen strategi dan capaian tahun lalu untuk menemukan kesenjangan kinerja.',
            'Menyusun SWOT berbobot dan strategi TOWS sampai isu strategis dan tema.',
            'Merumuskan 8–12 sasaran strategis dengan kalimat hasil.',
        ],
        'konsep' => [
            ['Diagnosis', 'Bandingkan target dan realisasi tahun lalu, temuan audit, dan hasil survei untuk melihat di mana kinerja tertinggal dan mengapa.'],
            ['SWOT berbobot', 'Faktor internal (S/W) dan eksternal (O/T) diberi bobot dan rating sehingga prioritasnya terukur.'],
            ['TOWS', 'Memasangkan S-W dengan O-T menghasilkan strategi SO, WO, ST, dan WT.'],
            ['Isu dan tema strategis', 'Isu adalah tantangan mendasar yang harus dijawab; tema adalah pilar besar yang mengelompokkan sasaran.'],
            ['Sasaran yang baik', 'Kata kerja + hasil, satu gagasan per sasaran, bisa diukur.'],
        ],
        'kasus' => 'Gunakan Renstra UIN Sunan Kalijaga 2025–2029 pada demo. Baca PESTEL, SWOT, dan TOWS yang sudah ada, lalu lihat bagaimana strategi terpilih menjadi Isu Strategis dan Tema Strategis. Bandingkan capaian 2025 (perioda ditutup) dengan target untuk menemukan kesenjangan.',
        'layar' => [['PESTEL', '/admin/pestel-analyses'], ['SWOT', '/admin/swot-items'], ['TOWS', '/admin/tows-strategies'], ['Isu Strategis', '/admin/strategic-issues'], ['Laporan', '/admin/laporan']],
        'lembar' => [
            'Di akun latihan kelompok, tambahkan tiga faktor PESTEL baru yang relevan untuk 2026–2029.',
            'Tambahkan dua butir S, W, O, dan T beserta bobot dan rating.',
            'Susun dua strategi TOWS dan hubungkan masing-masing ke isu strategis.',
            'Rumuskan 8–12 sasaran strategis dan kelompokkan ke dalam tema.',
        ],
        'diskusi' => [
            'Kesenjangan kinerja apa yang paling besar dari capaian tahun lalu?',
            'Apakah isu strategis Anda benar-benar tantangan mendasar atau hanya masalah operasional?',
            'Strategi TOWS mana yang paling realistis dalam tiga tahun?',
        ],
        'kuis' => [
            ['"Generasi Z memilih prodi berdasarkan prospek karier" termasuk butir SWOT apa?', ['Kekuatan', 'Kelemahan', 'Peluang', 'Ancaman'], 2, 'Faktor eksternal yang bisa dimanfaatkan termasuk Peluang. Bisa juga Ancaman bila instansi tidak siap; yang pasti bukan faktor internal.'],
            ['Strategi yang memakai kekuatan untuk menangkal ancaman adalah kuadran ...', ['SO', 'WO', 'ST', 'WT'], 2, 'ST = Strengths × Threats.'],
            ['Rumusan sasaran yang paling baik adalah ...', ['Pembangunan gedung kuliah', 'Meningkatnya kualitas lulusan yang berdaya saing', 'Rapat koordinasi akreditasi', 'Anggaran Rp 10 miliar'], 1, 'Sasaran menyatakan hasil yang ingin dicapai, bukan kegiatan atau sumber daya.'],
        ],
        'bantuan' => ['pestel', 'swot', 'tows', 'isu-strategis', 'tema-strategis'],
    ],
    [
        'no' => '4', 'hari' => 'Hari 1', 'waktu' => '15.00–16.30', 'durasi' => '90 menit', 'jenis' => 'Latihan kelompok',
        'judul' => 'Menyusun Peta Strategi dan Indikator Kinerja',
        'tujuan' => [
            'Menempatkan sasaran pada empat perspektif dan menarik panah sebab-akibat.',
            'Memilih 2–4 IK per sasaran yang memenuhi kriteria SMART, dengan campuran hasil dan pendorong.',
            'Menjelaskan penyelarasan IKU institusi ke IK unit kerja (cascading).',
        ],
        'konsep' => [
            ['Peta strategi', 'Gambar satu halaman yang menceritakan strategi: sasaran dalam empat perspektif dan panah sebab-akibat.'],
            ['Uji SMART', 'Spesifik, terukur, dapat dicapai, relevan, berbatas waktu.'],
            ['Campuran lead–lag', 'Setiap sasaran idealnya memiliki IK hasil dan pendorong agar masalah terlihat lebih awal.'],
            ['Cascading', 'IKU institusi diturunkan menjadi IK fakultas dan unit yang berkontribusi langsung, sehingga setiap unit tahu sumbangannya.'],
        ],
        'kasus' => 'Buka Peta Strategi demo dan perhatikan keterangan "IK (hasil · pendorong)" di setiap kartu. Temukan sasaran yang hanya punya IK hasil. Buka Relasi Sasaran untuk melihat bagaimana panah disimpan.',
        'layar' => [['Peta Strategi', '/admin/peta-strategi'], ['Sasaran Strategis', '/admin/strategic-objectives'], ['Relasi Sasaran', '/admin/objective-relations'], ['Indikator Kinerja', '/admin/kpis']],
        'lembar' => [
            'Gambar Peta Strategi kelompok di kertas plano: 8–12 sasaran dalam empat perspektif.',
            'Tarik panah sebab-akibat dari bawah ke atas; setiap panah harus bisa dijelaskan dalam satu kalimat.',
            'Masukkan sasaran dan relasinya ke akun latihan, lalu buka Peta Strategi untuk melihat hasilnya.',
            'Tambahkan 2–4 IK per sasaran, tandai jenisnya (hasil/pendorong), dan tetapkan pemiliknya.',
            'Galeri antarkelompok: kelompok lain memberi satu masukan untuk setiap peta.',
        ],
        'diskusi' => [
            'Panah mana di peta Anda yang paling lemah logikanya?',
            'Sasaran mana yang belum punya IK pendorong?',
            'Unit mana yang paling menentukan IKU institusi, dan bagaimana IK-nya diturunkan?',
        ],
        'kuis' => [
            ['Arah panah pada peta strategi yang benar adalah ...', ['Dari sasaran akibat ke sasaran penyebab', 'Dari sasaran penyebab ke sasaran akibat', 'Selalu dari Keuangan ke perspektif lain', 'Tidak perlu arah'], 1, 'Panah menunjukkan hipotesis: penyebab mendorong akibat.'],
            ['Jumlah IK yang dianjurkan per sasaran dalam sesi ini adalah ...', ['1', '2–4', '5–7', 'Sebanyak mungkin'], 1, 'Cukup 2–4 IK agar sasaran terukur tanpa membuat scorecard terlalu berat.'],
            ['Cascading berarti ...', ['Menyalin IKU yang sama ke semua unit', 'Menurunkan IKU menjadi IK unit yang berkontribusi langsung', 'Menghapus IK unit', 'Mengganti IKU setiap triwulan'], 1, 'Unit punya IK sendiri yang menyumbang ke IKU institusi, bukan salinan yang sama.'],
        ],
        'bantuan' => ['sasaran-strategis', 'relasi-sasaran', 'indikator-kinerja'],
    ],
    [
        'no' => '5', 'hari' => 'Hari 2', 'waktu' => '08.00–10.15', 'durasi' => '120 menit', 'jenis' => 'Latihan kelompok',
        'judul' => 'Definisi operasional, target, dan trajektori triwulanan',
        'tujuan' => [
            'Menyusun definisi operasional IK: batasan, rumus, satuan, sumber data, metode pengujian, dan dokumen pendukung.',
            'Menetapkan baseline, target tahunan, dan trajektori triwulanan yang realistis.',
            'Menentukan pemilik IK, frekuensi pengukuran, polaritas, dan agregasi tahun berjalan.',
        ],
        'konsep' => [
            ['Definisi operasional', 'Kesepakatan tertulis cara menghitung IK agar angka tidak diperdebatkan saat evaluasi.'],
            ['Baseline dan target', 'Baseline adalah realisasi tahun lalu; target menunjukkan lompatan yang diharapkan sesuai Renstra.'],
            ['Trajektori', 'Pecahan target per bulan atau triwulan yang mengikuti pola musiman kegiatan.'],
            ['Polaritas dan agregasi', 'Polaritas menentukan arah baik (naik/turun); agregasi menentukan cara menghitung capaian tahun berjalan (jumlah, rata-rata, nilai terakhir).'],
        ],
        'kasus' => 'Buka IK "Persentase peningkatan jumlah mahasiswa (IKSP.6.2)" atau IK PNBP di demo. Bandingkan definisi operasional, sumber data, dan metode pengujiannya. Lalu buka Target IK untuk melihat pola target bulanan PNBP yang mengikuti jadwal pembayaran UKT.',
        'layar' => [['Indikator Kinerja', '/admin/kpis'], ['Target IK', '/admin/kpi-targets']],
        'lembar' => [
            'Pilih tiga IK hasil kerja kelompok kemarin.',
            'Lengkapi definisi operasional setiap IK di akun latihan (batasan, formula, sumber data, pengambilan data, metode pengujian, dokumen pendukung).',
            'Isi baseline dan target tahunan, lalu pilih trajektori, polaritas, dan agregasi.',
            'Isi target triwulanan di layar Target IK dan periksa apakah jumlahnya konsisten dengan target tahunan.',
        ],
        'diskusi' => [
            'IK mana yang paling sering diperdebatkan angkanya di instansi Anda, dan mengapa?',
            'Bagaimana menetapkan target yang menantang tetapi realistis?',
            'Data apa yang belum tersedia secara rutin untuk mengukur IK Anda?',
        ],
        'kuis' => [
            ['Untuk IK "Realisasi PNBP" yang dilaporkan bulanan, agregasi yang tepat adalah ...', ['Jumlah (akumulasi)', 'Rata-rata', 'Nilai terakhir', 'Tidak perlu agregasi'], 0, 'Pendapatan bertambah setiap bulan, sehingga capaian tahun berjalan adalah jumlahnya.'],
            ['IK "Rata-rata masa tunggu kerja lulusan" memiliki polaritas ...', ['Makin besar makin baik', 'Makin kecil makin baik', 'Tetap', 'Tidak ada'], 1, 'Masa tunggu yang lebih singkat berarti kinerja lebih baik.'],
            ['Unsur yang TIDAK termasuk definisi operasional adalah ...', ['Formula perhitungan', 'Sumber data', 'Metode pengujian', 'Foto kegiatan pimpinan'], 3, 'Definisi operasional mengatur cara mengukur, bukan dokumentasi kegiatan.'],
        ],
        'bantuan' => ['indikator-kinerja', 'target-ik'],
    ],
    [
        'no' => '6', 'hari' => 'Hari 2', 'waktu' => '10.30–12.00', 'durasi' => '90 menit', 'jenis' => 'Praktik aplikasi',
        'judul' => 'Simulasi penetapan dan perubahan target',
        'tujuan' => [
            'Menyepakati scorecard pimpinan dari Peta Strategi dan IK.',
            'Mengajukan perubahan target dengan alasan yang sah dan jejak versi yang tercatat.',
            'Memahami format Kontrak Kinerja BLU: aspek layanan dan keuangan, capaian maksimum, dan syarat insentif.',
        ],
        'konsep' => [
            ['Kesepakatan target', 'Target ditetapkan bersama antara pimpinan dan pemilik IK, bukan ditentukan sepihak.'],
            ['Perubahan target', 'Target hanya diubah karena alasan sah (perubahan kebijakan, kondisi luar biasa), melalui adendum dengan berita acara.'],
            ['Kontrak Kinerja BLU', 'Kesepakatan pemimpin BLU dengan Ditjen Perbendaharaan yang menjadi dasar penilaian dan insentif.'],
        ],
        'kasus' => 'Buka Kontrak Kinerja BLU demo: kontrak awal dan adendum 2025, kontrak 2026, dan usulan perubahan target. Perhatikan alasan perubahan IK C1.1 dan jejak versinya. Lalu buka Capaian & Insentif untuk melihat dampak capaian terhadap syarat insentif.',
        'layar' => [['Kontrak Kinerja BLU', '/admin/performance-contracts'], ['Capaian & Insentif', '/admin/capaian-kinerja-blu']],
        'lembar' => [
            'Di akun latihan, pilih 6–10 IK yang akan menjadi scorecard pimpinan.',
            'Pastikan setiap IK punya aspek (layanan/keuangan), bobot, target, dan capaian maksimum.',
            'Buat satu usulan perubahan target dengan dasar dan alasan perubahan.',
            'Cetak atau unduh dokumen kesepakatan target dan diskusikan dengan kelompok lain.',
        ],
        'diskusi' => [
            'Siapa yang seharusnya terlibat dalam menyepakati target di instansi Anda?',
            'Kapan perubahan target dianggap sah, dan kapan itu "memindahkan tiang gawang"?',
            'Bagaimana menghubungkan capaian target dengan remunerasi secara adil?',
        ],
        'kuis' => [
            ['Kontrak Kinerja BLU ditandatangani oleh pemimpin BLU dan ...', ['Rektor PTN lain', 'Direktur Jenderal Perbendaharaan', 'Ketua DPR', 'Inspektorat'], 1, 'Kontrak Kinerja BLU disepakati dengan Ditjen Perbendaharaan, Kementerian Keuangan.'],
            ['Alasan yang sah untuk mengubah target adalah ...', ['Capaian triwulan pertama rendah', 'Perubahan kebijakan yang berdampak signifikan', 'Pergantian pejabat', 'Permintaan unit pemilik IK'], 1, 'Perubahan target memerlukan dasar yang sah dan kajian, bukan sekadar capaian yang rendah.'],
            ['Perubahan Kontrak Kinerja dituangkan dalam ...', ['Adendum', 'Nota dinas', 'Pengumuman', 'Notulen rapat unit'], 0, 'Perubahan kontrak dituangkan dalam adendum dengan berita acara pembahasan.'],
        ],
        'bantuan' => ['kontrak-kinerja', 'indikator-kinerja'],
    ],
    [
        'no' => '7', 'hari' => 'Hari 2', 'waktu' => '13.00–14.30', 'durasi' => '90 menit', 'jenis' => 'Praktik aplikasi',
        'judul' => 'Praktik aplikasi Kinera',
        'tujuan' => [
            'Menjalankan alur realisasi: unit mengisi, keuangan memverifikasi, pimpinan menyetujui.',
            'Membaca capaian per IK, sasaran, dan perspektif yang dihitung otomatis.',
            'Menggunakan Pusat Kendali dan daftar indikator berisiko sebagai bahan rapat pimpinan.',
        ],
        'konsep' => [
            ['Alur tiga lapis', 'Pemisahan peran pengisi, verifikator, dan penyetuju menjaga angka tetap dapat dipercaya.'],
            ['Skor dan warna', 'Skor ≥ 100 hijau, 80–99 kuning, di bawah 80 merah, memperhitungkan polaritas IK.'],
            ['Bukti', 'Setiap realisasi disertai sumber angka dan lampiran sesuai definisi operasional.'],
        ],
        'kasus' => 'Di demo, buka Alur Kerja untuk melihat realisasi September 2026 yang belum diisi dan yang dikembalikan. Buka Pusat Kendali dan cari IK merah, lalu telusuri sasaran dan inisiatif terkait.',
        'layar' => [['Alur Kerja', '/admin/alur-kerja'], ['Realisasi IK', '/admin/kpi-actuals'], ['Pusat Kendali', '/admin/pusat-kendali']],
        'lembar' => [
            'Bagi peran dalam kelompok: kepala unit (lat..ti / lat..lppm), keuangan (lat..wr2), pimpinan (lat..rektor).',
            'Kepala unit mengisi realisasi satu triwulan untuk dua IK beserta bukti.',
            'Keuangan memverifikasi IK keuangan; kembalikan satu realisasi dengan catatan.',
            'Kepala unit memperbaiki, lalu pimpinan menyetujui.',
            'Buka Pusat Kendali dan catat perubahan skor setelah persetujuan.',
        ],
        'diskusi' => [
            'Di titik mana alur realisasi di instansi Anda biasanya tersendat?',
            'Bukti seperti apa yang cukup untuk memverifikasi sebuah angka?',
            'Bagaimana pimpinan sebaiknya memakai Pusat Kendali dalam rapat?',
        ],
        'kuis' => [
            ['Urutan alur realisasi yang benar di Kinera adalah ...', ['Disetujui → diverifikasi → diisi', 'Diisi → diverifikasi → disetujui', 'Diverifikasi → diisi → disetujui', 'Diisi → disetujui → diverifikasi'], 1, 'Unit mengisi, keuangan memverifikasi IK keuangan, pimpinan menyetujui.'],
            ['IK dengan skor 85 ditampilkan berwarna ...', ['Hijau', 'Kuning', 'Merah', 'Abu-abu'], 1, '80–99 berarti perlu perhatian (kuning).'],
            ['Realisasi yang angkanya belum final sebaiknya ...', ['Tidak diisi sama sekali', 'Diisi dan ditandai proyeksi', 'Diisi dengan target', 'Diisi angka tahun lalu'], 1, 'Tanda proyeksi memberi tahu pimpinan bahwa angka masih sementara.'],
        ],
        'bantuan' => ['realisasi-ik'],
    ],
    [
        'no' => '8', 'hari' => 'Hari 2', 'waktu' => '14.30–15.30', 'durasi' => '60 menit', 'jenis' => 'Praktik aplikasi',
        'judul' => 'Inisiatif strategis, temuan, dan tindak lanjut',
        'tujuan' => [
            'Menyusun inisiatif dengan PIC, anggaran, aktivitas, dan milestone, serta membaca portofolio nilai–upaya.',
            'Mencatat temuan APIP, auditor, dan pengawas beserta tindakan korektif dan tenggat.',
            'Memimpin rapat tinjauan yang fokus pada yang merah dan kuning.',
        ],
        'konsep' => [
            ['Portofolio inisiatif', 'Inisiatif dipetakan menurut nilai dan upaya: Quick Win, Prioritas Strategis, Pengisi, dan Tinjau Ulang.'],
            ['Temuan dan tindakan korektif', 'Temuan mencatat kondisi, kriteria, sebab, dan akibat; tindakan korektif menghilangkan penyebabnya.'],
            ['Rapat tinjauan strategi', 'Mulai dari indikator merah, bahas penyebab, putuskan tindakan dengan PIC dan tenggat.'],
        ],
        'kasus' => 'Buka Portofolio Inisiatif demo dan lihat sembilan inisiatif 2026 di empat kuadran. Buka Tinjauan Kinerja Triwulan III dan temuan yang berasal dari rapat itu, lalu ikuti tindakan korektifnya.',
        'layar' => [['Portofolio Inisiatif', '/admin/portofolio-inisiatif'], ['Inisiatif Strategis', '/admin/strategic-initiatives'], ['Tinjauan Kinerja', '/admin/performance-reviews'], ['Temuan', '/admin/findings']],
        'lembar' => [
            'Simulasi rapat (15 menit): pimpinan memimpin, pengawas (lat..dewas) memberi catatan.',
            'Catat rapat sebagai Tinjauan Kinerja di akun latihan, lengkap dengan keputusan.',
            'Buat satu temuan dari IK merah dan satu tindakan korektif dengan tenggat.',
            'Tambahkan satu inisiatif baru dengan dua aktivitas dan satu milestone.',
        ],
        'diskusi' => [
            'Inisiatif mana di instansi Anda yang sebaiknya dihentikan karena tidak mendorong sasaran?',
            'Mengapa banyak temuan tidak kunjung ditutup?',
            'Apa aturan rapat yang membuat tinjauan strategi efektif?',
        ],
        'kuis' => [
            ['Inisiatif bernilai tinggi dengan upaya rendah masuk kuadran ...', ['Tinjau Ulang', 'Pengisi', 'Quick Win', 'Prioritas Strategis'], 2, 'Nilai tinggi dan upaya rendah adalah Quick Win: kerjakan segera.'],
            ['Tindakan korektif yang baik menyasar ...', ['Gejala masalah', 'Akar penyebab', 'Pihak yang disalahkan', 'Jadwal rapat'], 1, 'Tindakan korektif menghilangkan penyebab agar masalah tidak berulang.'],
            ['Rapat tinjauan strategi sebaiknya dimulai dari ...', ['Laporan kegiatan semua unit', 'IK dan inisiatif yang merah atau kuning', 'Paparan anggaran tahun depan', 'Daftar hadir'], 1, 'Waktu pimpinan dipakai untuk hal yang perlu keputusan.'],
        ],
        'bantuan' => ['inisiatif', 'temuan', 'tindakan-korektif', 'tinjauan-kinerja'],
    ],
    [
        'no' => '9', 'hari' => 'Hari 2', 'waktu' => '15.30–16.30', 'durasi' => '60 menit', 'jenis' => 'Pleno',
        'judul' => 'Rencana aksi 90 hari, penutupan, dan sertifikat',
        'tujuan' => [
            'Menyusun rencana aksi 90 hari untuk memulai manajemen strategis berbasis BSC di instansi sendiri.',
            'Mempresentasikan hasil kelompok dan menerima masukan.',
        ],
        'konsep' => [
            ['Mulai kecil, tetapi rutin', 'Lebih baik 10 IK yang ditinjau setiap bulan daripada 60 IK yang hanya dilaporkan tahunan.'],
            ['Tiga bulan pertama', 'Bulan 1: sepakati peta dan IK; bulan 2: lengkapi definisi operasional dan target; bulan 3: jalankan siklus realisasi dan rapat tinjauan pertama.'],
        ],
        'kasus' => 'Gunakan Panduan Implementasi sebagai acuan tahapan, peran, dan gerbang mutu implementasi.',
        'layar' => [['Panduan Implementasi', '/admin/panduan-implementasi'], ['Rencana Kerja (AI)', '/admin/rencana-kerja']],
        'lembar' => [
            'Tulis tiga hasil yang ingin dicapai dalam 90 hari.',
            'Untuk setiap hasil, tentukan langkah, PIC, dan tanggal.',
            'Tentukan forum rapat tinjauan pertama dan siapa yang hadir.',
            'Presentasikan dalam 5 menit per kelompok.',
        ],
        'diskusi' => [
            'Apa satu keputusan pimpinan yang harus diambil minggu depan agar rencana aksi berjalan?',
            'Siapa yang menjadi "pemilik proses" manajemen strategis di instansi Anda?',
        ],
        'kuis' => [
            ['Kunci keberhasilan 90 hari pertama implementasi BSC adalah ...', ['Membeli aplikasi termahal', 'Menyusun 100 IK sekaligus', 'Siklus realisasi dan rapat tinjauan yang rutin', 'Menunggu Renstra berikutnya'], 2, 'Ritme rutin membuat strategi hidup; jumlah IK dan aplikasi hanya alat.'],
        ],
        'bantuan' => ['pelembagaan', 'penilaian-kematangan'],
    ],
];
