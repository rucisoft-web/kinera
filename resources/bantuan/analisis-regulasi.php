<?php

return [
    'ringkas' => 'Daftar regulasi dan kewajiban yang mengikat instansi, beserta dampaknya, status kepatuhan, dan tenggatnya.',
    'teori' => [
        'Instansi pemerintah bekerja dalam kerangka hukum yang ketat. Strategi yang bagus bisa gagal bila melanggar atau terlambat memenuhi regulasi. Karena itu analisis regulasi menjadi bagian dari lingkungan strategis, melengkapi faktor <b>Hukum</b> pada PESTEL.',
        'Bagi PTKIN berpola BLU, regulasi utama antara lain UU Pendidikan Tinggi, PP tentang BLU, PMK tentang tarif dan remunerasi, PER Dirjen Perbendaharaan tentang Kontrak Kinerja, Standar Nasional Pendidikan Tinggi, ketentuan akreditasi, dan regulasi internal (statuta, peraturan rektor).',
        'Renstra juga memuat <b>kerangka regulasi</b>: rancangan peraturan yang perlu disusun agar strategi dapat dijalankan. Rancangan itu bisa dicatat di sini dengan status "Belum patuh" atau "Dipantau".',
    ],
    'berpikir' => [
        ['Regulasi apa yang mengatur pekerjaan kita?', 'Mulai dari regulasi yang memengaruhi pendanaan, akademik, SDM, dan aset.'],
        ['Apa dampaknya bagi instansi?', 'Kewajiban baru, peluang kelonggaran, atau perubahan cara kerja?'],
        ['Seberapa patuh kita saat ini?', 'Jujur menilai: patuh penuh, sebagian, belum, atau masih dipantau.'],
        ['Kapan tenggatnya?', 'Tulis tanggal batas pemenuhan agar bisa dipantau.'],
        ['Siapa yang menindaklanjuti?', 'Bila belum patuh, buat inisiatif atau temuan agar ada penanggung jawab.'],
    ],
    'kolom' => [
        ['Regulasi / kewajiban', 'Nama regulasi atau kewajiban yang ringkas.'],
        ['Penerbit / instansi', 'Lembaga yang menerbitkan, misalnya Kemenag, Kemenkeu, atau Rektor.'],
        ['Dasar hukum', 'Nomor dan tahun regulasi.'],
        ['Kategori', 'Bidang yang diatur, misalnya Keuangan, Akademik, SDM, atau Tata kelola.'],
        ['Berlaku sejak', 'Tanggal mulai berlaku.'],
        ['Uraian', 'Pokok pengaturan yang relevan.'],
        ['Dampak bagi instansi', 'Konsekuensi bagi strategi dan operasional.'],
        ['Status kepatuhan', 'Patuh, Sebagian, Belum patuh, atau Dipantau.'],
        ['Tenggat', 'Batas waktu pemenuhan, bila ada.'],
    ],
    'contoh' => [
        'kolom' => ['Regulasi', 'Dasar hukum', 'Dampak', 'Status'],
        'baris' => [
            ['Kontrak Kinerja BLU', 'PER Dirjen Perbendaharaan tentang Kontrak Kinerja', 'Target kinerja menjadi dasar remunerasi pimpinan', 'Patuh'],
            ['Standar Nasional Pendidikan Tinggi', 'Permendikbudristek 53/2023', 'Penyesuaian kurikulum, beban SKS, dan tugas akhir', 'Sebagian'],
            ['Akreditasi internasional prodi', 'Kebijakan internal Renstra', 'Biaya dan kesiapan dokumen prodi unggulan', 'Dipantau'],
            ['Rancangan peraturan tentang unit bisnis', 'Kerangka regulasi Renstra 2025–2029', 'Dasar hukum pengelolaan aset produktif', 'Belum patuh'],
        ],
    ],
    'hindari' => [
        'Mencatat regulasi tanpa menulis dampaknya bagi instansi.',
        'Status "Patuh" tanpa bukti yang bisa ditunjukkan saat audit.',
        'Tidak mengisi tenggat sehingga kewajiban terlewat.',
    ],
    'kaitan' => ['pestel' => 'faktor Hukum', 'isu-strategis' => 'ketidakpatuhan berisiko tinggi', 'kontrak-kinerja' => 'kewajiban Kontrak Kinerja BLU'],
];
