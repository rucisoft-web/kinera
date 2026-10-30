<?php

return [
    'ringkas' => 'Masalah atau penyimpangan yang ditemukan dari tinjauan, pengawasan, audit, atau pengaduan, yang wajib ditindaklanjuti sampai ditutup.',
    'teori' => [
        'Temuan adalah titik masuk siklus perbaikan berkelanjutan (<b>PDCA</b>: Plan–Do–Check–Act). Temuan mencatat kondisi yang tidak sesuai harapan, penyebabnya, dan akibatnya, lalu memicu tindakan korektif.',
        'Rumusan temuan yang baik mengikuti pola audit: <b>kondisi</b> (apa yang terjadi), <b>kriteria</b> (seharusnya bagaimana), <b>sebab</b>, dan <b>akibat</b>. Tingkat temuan (rendah, sedang, tinggi, kritis) menentukan prioritas penanganan.',
        'Temuan bisa dikaitkan ke IK, inisiatif, atau tinjauan asal, sehingga jejaknya utuh dari masalah sampai penyelesaian.',
    ],
    'berpikir' => [
        ['Apa kondisinya?', 'Tulis fakta yang ditemukan, sebaiknya dengan angka.'],
        ['Seharusnya bagaimana?', 'Sebut kriteria: target IK, SOP, atau regulasi.'],
        ['Mengapa terjadi?', 'Cari akar masalah, bukan gejalanya.'],
        ['Seberapa serius?', 'Tentukan tingkat berdasarkan dampaknya terhadap sasaran dan kepatuhan.'],
        ['Siapa yang menindaklanjuti?', 'Setelah disimpan, buat tindakan korektif dengan PIC dan tenggat.'],
    ],
    'kolom' => [
        ['Temuan', 'Judul singkat masalah.'],
        ['Tingkat', 'Rendah, Sedang, Tinggi, atau Kritis.'],
        ['Uraian', 'Kondisi, kriteria, sebab, dan akibat.'],
        ['Sumber', 'Tinjauan kinerja, pengawas, audit, pengaduan masyarakat, atau internal.'],
        ['Tinjauan asal / IK / Inisiatif terkait', 'Kaitkan ke sumber data agar mudah ditelusuri.'],
        ['Status', 'Terbuka, Dalam tindak lanjut, Siap ditutup, atau Ditutup (otomatis mengikuti alur).'],
    ],
    'contoh' => [
        'kolom' => ['Temuan', 'Tingkat', 'Sumber', 'Terkait'],
        'baris' => [
            ['Publikasi bereputasi tertinggal 35% dari target triwulan III', 'Tinggi', 'Tinjauan kinerja', 'IK publikasi internasional'],
            ['Piutang kerja sama melewati 90 hari', 'Sedang', 'Pengawas (Dewan Pengawas)', 'IK PNBP'],
            ['Aset tanah Kampus 2 belum bersertifikat seluruhnya', 'Tinggi', 'Audit', 'Inisiatif Kampus 2'],
        ],
    ],
    'hindari' => [
        'Temuan ditulis sebagai rekomendasi, bukan sebagai kondisi.',
        'Tidak ada tindakan korektif sehingga temuan terbuka bertahun-tahun.',
        'Semua temuan diberi tingkat kritis.',
    ],
    'kaitan' => ['tindakan-korektif' => 'perbaikan atas temuan', 'tinjauan-kinerja' => 'asal temuan', 'realisasi-ik' => 'bukti capaian'],
];
