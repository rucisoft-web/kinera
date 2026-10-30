<?php

return [
    'ringkas' => 'Sasaran yang ingin dicapai dalam empat perspektif Balanced Scorecard, lengkap dengan tema dan bobotnya. Sasaran adalah kotak-kotak pada Peta Strategi.',
    'teori' => [
        'Sasaran strategis adalah pernyataan singkat tentang apa yang harus dicapai agar strategi berhasil. Dalam Balanced Scorecard sektor publik, sasaran disusun dalam empat perspektif: <b>Pemangku Kepentingan</b> di puncak (hasil bagi masyarakat dan pengguna layanan), <b>Keuangan</b> sebagai penopang (pengelolaan sumber daya yang sehat), <b>Proses Layanan Internal</b> (apa yang harus unggul dikerjakan), dan <b>Pembelajaran & Pertumbuhan</b> (SDM, sistem informasi, dan budaya).',
        'Sasaran yang baik dimulai dengan kata kerja hasil, cukup spesifik, dan bisa diukur dengan 1–3 indikator. Total sasaran yang lazim 10–20 untuk tingkat institusi.',
        'Bobot sasaran dalam perspektif menunjukkan kepentingan relatifnya dan dipakai untuk menghitung skor perspektif dan skor strategis.',
    ],
    'berpikir' => [
        ['Mulai dari atas', 'Apa hasil yang ingin dirasakan pemangku kepentingan? Ini menjadi sasaran perspektif Pemangku Kepentingan.'],
        ['Turun ke penopang', 'Sumber daya keuangan apa yang diperlukan? Proses apa yang harus unggul? Kemampuan apa yang harus dibangun?'],
        ['Rumuskan dengan kata kerja hasil', 'Contoh: "Meningkatnya kualitas lulusan yang berdaya saing", bukan "Pelatihan dosen".'],
        ['Hindari terlalu banyak sasaran', 'Gabungkan sasaran yang mirip; satu perspektif cukup 2–5 sasaran.'],
        ['Tetapkan bobot', 'Bobot dalam satu perspektif sebaiknya berjumlah 100.'],
    ],
    'kolom' => [
        ['Perspektif', 'Pemangku Kepentingan, Keuangan, Proses Layanan Internal, atau Pembelajaran & Pertumbuhan.'],
        ['Sasaran', 'Rumusan sasaran dengan kata kerja hasil.'],
        ['Tema', 'Tema strategis yang menaungi sasaran ini.'],
        ['Bobot dalam perspektif', 'Kepentingan relatif dalam perspektifnya (total satu perspektif = 100).'],
        ['Uraian', 'Maksud sasaran dan cakupannya.'],
        ['Urutan', 'Urutan tampilan di Peta Strategi.'],
    ],
    'contoh' => [
        'kolom' => ['Perspektif', 'Sasaran', 'Bobot'],
        'baris' => [
            ['Pemangku Kepentingan', 'Meningkatnya kualitas lulusan yang berdaya saing global', '35'],
            ['Pemangku Kepentingan', 'Meningkatnya rekognisi riset dan inovasi', '30'],
            ['Keuangan', 'Meningkatnya kemandirian dan kesehatan keuangan BLU', '60'],
            ['Proses Layanan Internal', 'Terwujudnya tata kelola yang akuntabel dan terintegrasi', '40'],
            ['Pembelajaran & Pertumbuhan', 'Meningkatnya kualitas dan kualifikasi SDM', '50'],
        ],
    ],
    'hindari' => [
        'Sasaran ditulis sebagai kegiatan, bukan hasil.',
        'Menaruh sasaran Keuangan di puncak peta. Untuk instansi pemerintah, Pemangku Kepentingan di puncak.',
        'Sasaran tanpa indikator.',
        'Bobot dalam satu perspektif tidak berjumlah 100.',
    ],
    'kaitan' => ['relasi-sasaran' => 'panah sebab-akibat', 'indikator-kinerja' => 'ukur setiap sasaran', 'inisiatif' => 'program pendorong'],
];
