<?php

return [
    'ringkas' => 'Rangkuman Kekuatan, Kelemahan, Peluang, dan Ancaman dengan bobot dan rating, sebagai dasar matriks IFAS–EFAS dan strategi TOWS.',
    'teori' => [
        'SWOT merangkum posisi instansi: <b>Strengths</b> dan <b>Weaknesses</b> adalah faktor internal yang dapat dikendalikan, sedangkan <b>Opportunities</b> dan <b>Threats</b> adalah faktor eksternal. SWOT yang baik bersumber dari analisis sebelumnya, bukan dari curah pendapat semata.',
        'Setiap butir diberi <b>bobot</b> (0–1, kepentingan relatif; jumlah bobot dalam satu kelompok internal atau eksternal = 1) dan <b>rating</b> (1–4, seberapa baik instansi saat ini menghadapinya). Bobot × rating menghasilkan skor. Total skor internal (IFAS) dan eksternal (EFAS) menunjukkan posisi strategis instansi dalam kuadran.',
        'SWOT bukan tujuan akhir. Butir-butir ini dipasangkan pada layar <b>Strategi TOWS</b> untuk menghasilkan pilihan strategi.',
    ],
    'berpikir' => [
        ['Ambil dari analisis yang sudah ada', 'Peluang dan ancaman dari PESTEL dan Analisis Industri; kekuatan dan kelemahan dari Kapabilitas, evaluasi Renstra, dan perbandingan pesaing.'],
        ['Tulis dengan bukti', 'Bukan "SDM bagus", tetapi "62% dosen bergelar doktor, tertinggi di antara PTKIN wilayah".'],
        ['Saring yang penting', 'Pilih 5–8 butir per kelompok.'],
        ['Beri bobot', 'Butir yang paling menentukan keberhasilan diberi bobot terbesar. Pastikan total bobot S+W = 1 dan O+T = 1.'],
        ['Beri rating', '4 = sangat baik ditangani/dimanfaatkan, 1 = sangat lemah.'],
    ],
    'kolom' => [
        ['Jenis', 'Kekuatan (S), Kelemahan (W), Peluang (O), atau Ancaman (T).'],
        ['Urutan', 'Nomor urut tampilan dalam kelompoknya.'],
        ['Butir', 'Pernyataan singkat dan spesifik.'],
        ['Bukti / uraian', 'Data atau fakta pendukung.'],
        ['Bobot (0–1)', 'Kepentingan relatif. Total bobot faktor internal = 1 dan total faktor eksternal = 1.'],
        ['Rating (1–4)', 'Seberapa baik kondisi atau respons instansi saat ini.'],
        ['Sumber', 'Analisis atau dokumen asal butir ini.'],
    ],
    'contoh' => [
        'kolom' => ['Jenis', 'Butir', 'Bobot', 'Rating', 'Skor'],
        'baris' => [
            ['S', 'Akreditasi institusi Unggul dan mayoritas prodi Unggul', '0,20', '4', '0,80'],
            ['S', 'Paradigma integrasi-interkoneksi keilmuan yang khas', '0,15', '3', '0,45'],
            ['W', 'Pendapatan usaha non-UKT masih kecil', '0,15', '2', '0,30'],
            ['W', 'Publikasi internasional bereputasi di bawah pembanding', '0,15', '1', '0,15'],
            ['O', 'Arah kebijakan transformasi menjadi PTN-BH', '0,20', '3', '0,60'],
            ['T', 'Persaingan dengan PTN dan PTS besar di wilayah', '0,15', '2', '0,30'],
        ],
    ],
    'contoh_pengantar' => 'Potongan contoh. Dalam praktik, setiap kelompok berisi 5–8 butir dan bobot faktor internal maupun eksternal masing-masing berjumlah 1.',
    'hindari' => [
        'Memasukkan faktor eksternal ke S/W atau faktor internal ke O/T.',
        'Butir terlalu umum dan tidak bisa diukur.',
        'Total bobot tidak sama dengan 1.',
        'Berhenti di SWOT tanpa menyusun strategi TOWS.',
    ],
    'kaitan' => ['tows' => 'pasangkan S-W dengan O-T', 'isu-strategis' => 'kelemahan dan ancaman kritis', 'kapabilitas' => 'bukti kekuatan internal'],
];
