<?php

return [
    'ringkas' => 'Program atau proyek strategis yang mendorong pencapaian sasaran, dengan jadwal, hasil yang diharapkan, dampak, urgensi, risiko, dan tingkat anggaran.',
    'teori' => [
        'Inisiatif strategis adalah program berbatas waktu yang dirancang untuk menutup kesenjangan antara kinerja saat ini dan target. Kaplan dan Norton menegaskan bahwa inisiatif harus <b>terhubung ke sasaran</b>; program yang tidak mendukung sasaran mana pun sebaiknya dipertanyakan.',
        'Kinera memetakan inisiatif dalam <b>Portofolio Inisiatif</b> berdasarkan dampak dan kemudahan pelaksanaan, sehingga pimpinan bisa membedakan inisiatif prioritas, pengisi, cepat menang, dan yang perlu ditinjau ulang.',
        'Progres inisiatif dihitung otomatis dari bobot dan progres aktivitas serta milestone, dan anggarannya dipantau melalui layar Anggaran Inisiatif.',
    ],
    'berpikir' => [
        ['Sasaran apa yang didorong?', 'Pilih sasaran yang akan membaik bila inisiatif berhasil.'],
        ['Apa hasil akhirnya?', 'Tulis hasil yang bisa diperiksa, misalnya "5 prodi terakreditasi internasional".'],
        ['Seberapa besar dampak dan seberapa mendesak?', 'Nilai dampak dan urgensi secara jujur untuk penentuan prioritas.'],
        ['Apa risikonya?', 'Hambatan utama dan cara mengelolanya.'],
        ['Bagaimana memecahnya?', 'Setelah disimpan, tambahkan aktivitas, milestone, dan anggaran.'],
    ],
    'kolom' => [
        ['Inisiatif', 'Nama program dengan kata kerja, misalnya "Akselerasi akreditasi internasional prodi unggulan".'],
        ['Uraian', 'Latar belakang dan cakupan.'],
        ['Tema', 'Tema strategis induk.'],
        ['Sasaran yang didukung', 'Satu atau beberapa sasaran strategis.'],
        ['Mulai / Selesai', 'Jadwal pelaksanaan.'],
        ['Progres (otomatis)', 'Dihitung dari aktivitas dan milestone.'],
        ['Hasil yang diharapkan', 'Keluaran dan hasil yang dapat diperiksa.'],
        ['Dampak / Urgensi', 'Skala 1–5 untuk penentuan prioritas portofolio.'],
        ['Risiko', 'Risiko utama dan mitigasinya.'],
        ['Tingkat anggaran', 'Perkiraan besaran anggaran: rendah, sedang, atau tinggi.'],
    ],
    'contoh' => [
        'kolom' => ['Inisiatif', 'Sasaran', 'Hasil yang diharapkan', 'Dampak', 'Urgensi'],
        'baris' => [
            ['Akselerasi akreditasi internasional prodi unggulan', 'Kualitas lulusan', '5 prodi terakreditasi internasional pada 2027', '5', '4'],
            ['Sistem informasi terintegrasi akademik–keuangan–SDM', 'Tata kelola terintegrasi', 'Satu basis data dan dasbor pimpinan', '5', '5'],
            ['Pembangunan Kampus 2 tahap I', 'Kemandirian keuangan', 'Gedung kuliah bersama siap pakai', '4', '3'],
            ['Klinik publikasi dan hibah kolaborasi', 'Rekognisi riset', '450 artikel bereputasi per tahun', '4', '4'],
        ],
    ],
    'hindari' => [
        'Inisiatif tidak dihubungkan ke sasaran.',
        'Semua inisiatif diberi dampak dan urgensi 5.',
        'Inisiatif tanpa aktivitas dan milestone sehingga progres tidak bergerak.',
    ],
    'kaitan' => ['aktivitas-inisiatif' => 'pecah menjadi aktivitas', 'milestone-inisiatif' => 'titik kendali', 'anggaran-inisiatif' => 'pagu dan serapan'],
];
