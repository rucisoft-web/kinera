<?php

return [
    'ringkas' => 'Strategi yang lahir dari memasangkan butir SWOT: SO, WO, ST, dan WT, lengkap dengan prioritas dan isu strategis yang dijawab.',
    'teori' => [
        'Matriks TOWS (Weihrich) mengubah SWOT menjadi strategi dengan memasangkan faktor internal dan eksternal:',
        '<b>SO</b> memakai kekuatan untuk meraih peluang (agresif). <b>WO</b> memperbaiki kelemahan dengan memanfaatkan peluang (turn-around). <b>ST</b> memakai kekuatan untuk menangkal ancaman (diversifikasi). <b>WT</b> meminimalkan kelemahan sekaligus menghindari ancaman (defensif).',
        'Hasil TOWS adalah daftar strategi alternatif. Pimpinan memilih sebagian sebagai strategi terpilih yang kemudian diturunkan menjadi tema, sasaran, dan inisiatif.',
    ],
    'berpikir' => [
        ['Letakkan SWOT berdampingan', 'Buka layar SWOT dan baca kekuatan, kelemahan, peluang, dan ancaman prioritas.'],
        ['Pasangkan secara sengaja', 'Contoh: S1 akreditasi unggul + O1 kebijakan PTN-BH = strategi SO.'],
        ['Tulis sebagai tindakan', 'Mulai dengan kata kerja: "Memperkuat", "Mengembangkan", "Membangun".'],
        ['Hubungkan ke isu strategis', 'Setiap strategi sebaiknya menjawab sedikitnya satu isu.'],
        ['Tentukan status', 'Tandai mana yang diusulkan, dipilih, atau ditolak setelah dibahas pimpinan.'],
    ],
    'kolom' => [
        ['Kuadran', 'SO, WO, ST, atau WT.'],
        ['Prioritas', 'Urutan kepentingan (1 = paling tinggi).'],
        ['Strategi', 'Rumusan strategi dengan kata kerja.'],
        ['Uraian', 'Butir SWOT yang dipasangkan dan cara menjalankannya.'],
        ['Status', 'Diusulkan, Dipilih, atau Ditolak.'],
        ['Isu strategis yang dijawab', 'Pilih isu dari layar Isu Strategis.'],
    ],
    'contoh' => [
        'kolom' => ['Kuadran', 'Strategi', 'Pasangan SWOT', 'Status'],
        'baris' => [
            ['SO', 'Mempercepat transformasi kelembagaan menuju PTN-BH berbasis akreditasi unggul', 'S1 + O1', 'Dipilih'],
            ['WO', 'Mengembangkan unit bisnis dan pemanfaatan aset untuk menambah PNBP', 'W1 + O1', 'Dipilih'],
            ['ST', 'Menonjolkan integrasi keilmuan sebagai pembeda dari PTN/PTS besar', 'S2 + T1', 'Dipilih'],
            ['WT', 'Membangun pusat publikasi dan kolaborasi riset internasional', 'W2 + T1', 'Diusulkan'],
        ],
    ],
    'hindari' => [
        'Strategi tidak berasal dari pasangan SWOT yang jelas.',
        'Semua strategi berstatus "Dipilih" sehingga tidak ada fokus.',
        'Strategi ditulis sebagai tujuan ("PNBP naik") bukan sebagai cara.',
    ],
    'kaitan' => ['tema-strategis' => 'kelompokkan strategi terpilih', 'pilihan-strategis' => 'keputusan arah', 'inisiatif' => 'wujudkan menjadi program'],
];
