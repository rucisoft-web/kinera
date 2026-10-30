<?php

return [
    'ringkas' => 'Langkah perbaikan atas temuan, dengan tenggat, progres, dan bukti hasil, sampai temuan dapat ditutup.',
    'teori' => [
        'Tindakan korektif menghilangkan penyebab masalah agar tidak berulang, berbeda dari koreksi sesaat yang hanya membereskan gejalanya. Pendekatan ini berasal dari sistem manajemen mutu (ISO 9001) dan praktik tindak lanjut hasil pemeriksaan.',
        'Tindakan yang baik bersifat <b>spesifik, punya penanggung jawab, bertenggat, dan bisa dibuktikan</b>. Temuan baru boleh ditutup bila seluruh tindakan korektif selesai dan buktinya diterima.',
    ],
    'berpikir' => [
        ['Apa akar masalahnya?', 'Baca uraian temuan dan pastikan tindakan menyasar penyebab.'],
        ['Tindakan apa yang paling efektif?', 'Pilih tindakan yang bisa mencegah masalah terulang.'],
        ['Kapan selesai?', 'Tetapkan tenggat realistis sesuai tingkat temuan.'],
        ['Bagaimana membuktikannya?', 'Tentukan bukti: SK, SOP baru, laporan, atau data capaian yang membaik.'],
    ],
    'kolom' => [
        ['Temuan', 'Temuan yang ditindaklanjuti.'],
        ['Tindakan korektif', 'Uraian singkat tindakan.'],
        ['Tenggat', 'Tanggal target selesai.'],
        ['Progres (%)', 'Persentase penyelesaian.'],
        ['Uraian', 'Rincian langkah dan penanggung jawab.'],
        ['Hasil / bukti', 'Bukti penyelesaian dan dampaknya.'],
    ],
    'contoh' => [
        'kolom' => ['Temuan', 'Tindakan', 'Tenggat', 'Progres'],
        'baris' => [
            ['Publikasi tertinggal dari target', 'Klinik publikasi bulanan dan insentif artikel Q1–Q2', 'Des 2026', '60%'],
            ['Piutang kerja sama > 90 hari', 'Penagihan berjenjang dan klausul denda di perjanjian baru', 'Nov 2026', '80%'],
            ['Aset belum bersertifikat', 'Pengajuan sertifikasi ke BPN bersama KPKNL', 'Mar 2027', '30%'],
        ],
    ],
    'hindari' => [
        'Tindakan terlalu umum ("meningkatkan koordinasi").',
        'Progres 100% tanpa bukti hasil.',
        'Tenggat lewat tanpa pembaruan.',
    ],
    'kaitan' => ['temuan' => 'masalah yang diperbaiki', 'pelembagaan' => 'jadikan kebiasaan bila berhasil'],
];
