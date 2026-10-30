<?php

return [
    'ringkas' => 'Hubungan sebab-akibat antar-sasaran yang tampil sebagai panah di Peta Strategi.',
    'teori' => [
        'Peta strategi bukan sekadar daftar sasaran, tetapi <b>hipotesis sebab-akibat</b>: bila SDM meningkat, proses membaik; bila proses membaik, layanan dan hasil bagi pemangku kepentingan meningkat. Kaplan dan Norton menyebut rantai ini sebagai "cerita strategi".',
        'Panah selalu mengarah dari <b>sasaran penyebab</b> (biasanya di perspektif lebih bawah) ke <b>sasaran akibat</b> (di perspektif lebih atas). Di sektor publik, Keuangan menopang Proses dan Pemangku Kepentingan, sehingga panah dari Keuangan juga mengarah ke atas.',
        'Relasi yang diuji dengan data (misalnya korelasi capaian indikator) membantu pimpinan menilai apakah hipotesis strateginya benar.',
    ],
    'berpikir' => [
        ['Mulai dari sasaran puncak', 'Tanyakan: sasaran apa yang harus berhasil lebih dulu agar sasaran ini tercapai?'],
        ['Hubungkan satu tingkat demi satu tingkat', 'Hindari panah yang melompati banyak perspektif tanpa alasan.'],
        ['Batasi jumlah panah', 'Pilih hubungan yang paling kuat agar peta tetap terbaca.'],
        ['Nilai kekuatan hubungan', 'Seberapa yakin hubungan ini berlaku?'],
    ],
    'kolom' => [
        ['Sasaran penyebab', 'Sasaran pendorong (asal panah).'],
        ['Sasaran akibat', 'Sasaran yang dipengaruhi (ujung panah).'],
        ['Kekuatan hubungan', 'Skala keyakinan bahwa hubungan ini kuat.'],
        ['Catatan', 'Logika atau bukti hubungan sebab-akibat.'],
    ],
    'contoh' => [
        'kolom' => ['Penyebab', 'Akibat', 'Logika'],
        'baris' => [
            ['Meningkatnya kualitas dan kualifikasi SDM', 'Meningkatnya mutu pembelajaran', 'Dosen yang kompeten meningkatkan mutu proses belajar.'],
            ['Meningkatnya mutu pembelajaran', 'Meningkatnya kualitas lulusan', 'Proses belajar bermutu menghasilkan lulusan yang kompeten.'],
            ['Meningkatnya kemandirian keuangan BLU', 'Terwujudnya tata kelola terintegrasi', 'Dana mandiri membiayai sistem informasi dan perbaikan layanan.'],
            ['Meningkatnya produktivitas riset', 'Meningkatnya rekognisi riset dan inovasi', 'Publikasi bereputasi mendorong rekognisi internasional.'],
        ],
    ],
    'hindari' => [
        'Panah terbalik, dari akibat ke penyebab.',
        'Semua sasaran dihubungkan ke semua sasaran.',
        'Relasi tanpa logika yang bisa dijelaskan.',
    ],
    'kaitan' => ['sasaran-strategis' => 'kotak pada peta', 'indikator-kinerja' => 'menguji hipotesis dengan data'],
];
