<?php

return [
    'ringkas' => 'Daftar hal di luar kendali langsung inisiatif atau sasaran yang menentukan keberhasilannya, beserta tingkat risiko dan mitigasinya.',
    'teori' => [
        'Banyak strategi gagal bukan karena salah rancang, tetapi karena bergantung pada pihak atau prasyarat yang tidak dikelola: persetujuan kementerian, pencairan dana, ketersediaan sistem, atau unit lain. Manajemen ketergantungan memastikan prasyarat tersebut terlihat dan dipantau.',
        'Ketergantungan dapat bersifat <b>internal</b> (antar-unit atau antar-inisiatif) atau <b>eksternal</b> (regulator, mitra, pemasok). Masing-masing dinilai risikonya dan diberi mitigasi.',
    ],
    'berpikir' => [
        ['Apa yang harus terjadi lebih dulu?', 'Untuk setiap inisiatif prioritas, tanyakan prasyarat apa yang dibutuhkan.'],
        ['Siapa yang mengendalikannya?', 'Unit lain, kementerian, mitra, atau pemasok?'],
        ['Seberapa besar risikonya?', 'Bila tidak terpenuhi, seberapa parah akibatnya?'],
        ['Apa rencana cadangannya?', 'Tulis mitigasi dan pantau statusnya.'],
    ],
    'kolom' => [
        ['Ketergantungan', 'Hal yang menjadi prasyarat.'],
        ['Jenis', 'Internal atau Eksternal.'],
        ['Bergantung pada', 'Pihak atau sistem yang mengendalikan.'],
        ['Inisiatif / Sasaran', 'Yang terdampak bila ketergantungan tidak terpenuhi.'],
        ['Uraian', 'Penjelasan.'],
        ['Risiko', 'Rendah, Sedang, atau Tinggi.'],
        ['Mitigasi', 'Langkah pengamanan.'],
        ['Status', 'Aktif atau Teratasi.'],
    ],
    'contoh' => [
        'kolom' => ['Ketergantungan', 'Jenis', 'Bergantung pada', 'Risiko', 'Mitigasi'],
        'baris' => [
            ['Pencairan SBSN untuk Kampus 2', 'Eksternal', 'Kementerian Keuangan dan Kemenag', 'Tinggi', 'Penyiapan dokumen readiness criteria lebih awal'],
            ['Integrasi data SDM ke sistem terpadu', 'Internal', 'Bagian kepegawaian', 'Sedang', 'Tim data lintas unit dan tenggat bersama'],
            ['Persetujuan perubahan statuta', 'Eksternal', 'Kementerian Agama', 'Tinggi', 'Komunikasi rutin dan kajian akademik lengkap'],
        ],
    ],
    'hindari' => [
        'Hanya mencatat ketergantungan eksternal dan melupakan antar-unit.',
        'Tidak ada mitigasi untuk risiko tinggi.',
        'Status tidak diperbarui setelah ketergantungan teratasi.',
    ],
    'kaitan' => ['inisiatif' => 'yang terdampak', 'analisis-regulasi' => 'prasyarat regulasi'],
];
