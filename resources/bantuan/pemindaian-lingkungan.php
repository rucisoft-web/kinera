<?php

return [
    'ringkas' => 'Catatan hasil membaca lingkungan internal dan eksternal secara berkala: dokumen apa yang dibaca, apa temuannya, dan apa artinya bagi strategi.',
    'teori' => [
        'Pemindaian lingkungan (<i>environmental scanning</i>) adalah kegiatan rutin mengumpulkan dan menafsirkan informasi tentang perubahan di luar dan di dalam organisasi. Dalam manajemen strategis, pemindaian adalah pintu masuk sebelum analisis yang lebih tajam seperti PESTEL, Lima Kekuatan Porter, dan SWOT.',
        'Bagi perguruan tinggi, pemindaian eksternal mencakup kebijakan Kementerian Agama dan Kemendiktisaintek, tren pendaftar, kebutuhan dunia kerja, teknologi pembelajaran, dan kompetisi antar-PT. Pemindaian internal mencakup capaian Renstra lalu, hasil audit mutu, laporan keuangan BLU, survei kepuasan, dan kapasitas SDM.',
        'Hasil pemindaian yang baik tidak berhenti pada ringkasan, tetapi menyebut <b>implikasi strategis</b>: apa yang harus dipertahankan, diubah, atau dimulai.',
    ],
    'berpikir' => [
        ['Apa yang ingin kita pahami?', 'Tentukan pertanyaannya dulu, misalnya "mengapa pendaftar prodi umum turun?" atau "apa dampak PTN-BH bagi tata kelola kita?".'],
        ['Sumber apa yang paling bisa dipercaya?', 'Utamakan dokumen resmi dan data: Renstra, LKj, laporan keuangan audited, PDDikti, hasil akreditasi, survei, regulasi.'],
        ['Apa temuan utamanya?', 'Tulis 3–5 temuan berupa fakta atau kecenderungan, lengkap dengan angka bila ada.'],
        ['Lalu apa artinya bagi strategi?', 'Terjemahkan setiap temuan menjadi implikasi: peluang yang bisa diambil, risiko yang harus dikelola, atau keputusan yang perlu dibuat.'],
        ['Ke mana hasilnya dibawa?', 'Temuan rinci diturunkan ke layar PESTEL, Analisis Industri, Pasar & Pelanggan, atau langsung menjadi butir SWOT dan Isu Strategis.'],
    ],
    'kolom' => [
        ['Judul', 'Nama singkat pemindaian, misalnya "Kajian tren penerimaan mahasiswa baru 2021–2025".'],
        ['Tanggal', 'Tanggal pemindaian selesai disusun.'],
        ['Lingkup', '<b>Eksternal</b> bila yang dibaca adalah kondisi di luar instansi; <b>Internal</b> bila tentang kondisi di dalam instansi.'],
        ['Sumber dokumen', 'Dokumen atau data yang dibaca, misalnya "Renstra 2020–2024, LKj 2024, PDDikti".'],
        ['Ringkasan temuan', 'Fakta dan kecenderungan utama, sebaiknya dalam poin dengan angka.'],
        ['Implikasi strategis', 'Arti temuan bagi arah strategi. Bagian ini yang paling penting.'],
        ['Status', '<b>Aktif</b> bila masih dipakai sebagai dasar perencanaan; <b>Arsip</b> bila sudah diganti pemindaian yang lebih baru.'],
    ],
    'contoh' => [
        'kolom' => ['Judul', 'Lingkup', 'Temuan', 'Implikasi strategis'],
        'baris' => [
            ['Evaluasi capaian Renstra 2020–2024', 'Internal', '62% IKU tercapai; rekognisi internasional dan pendapatan usaha tertinggal.', 'Renstra baru memprioritaskan internasionalisasi dan diversifikasi PNBP.'],
            ['Tren penerimaan mahasiswa baru', 'Eksternal', 'Peminat prodi umum naik 8%/tahun, prodi keagamaan stagnan.', 'Penguatan prodi umum berciri integrasi keilmuan, promosi prodi keagamaan berbasis karier.'],
            ['Kesiapan transformasi PTN-BH', 'Internal', 'Sistem keuangan dan SDM belum terintegrasi; aset belum tersertifikasi seluruhnya.', 'Program tata kelola dan sistem informasi terpadu menjadi prasyarat.'],
        ],
    ],
    'hindari' => [
        'Menyalin isi dokumen tanpa menulis implikasinya.',
        'Hanya memindai faktor eksternal, padahal kelemahan internal sering menjadi penghambat utama.',
        'Tidak mencantumkan sumber sehingga temuan sulit diverifikasi saat rapat.',
    ],
    'kaitan' => ['pestel' => 'rinci faktor makro', 'analisis-industri' => 'peta persaingan', 'swot' => 'ringkas menjadi S-W-O-T'],
];
