<?php

return [
    'ringkas' => 'Rincian pagu dan realisasi anggaran per inisiatif, dengan kategori dan sumber dana, untuk memantau serapan.',
    'teori' => [
        'Strategi tanpa anggaran hanya angan-angan. Kaplan dan Norton menyarankan anggaran strategis (<i>STRATEX</i>) dipisahkan dari anggaran operasional, agar dana untuk inisiatif strategis terlindungi dan dapat dipantau.',
        'Di BLU, anggaran inisiatif bersumber dari RBA (PNBP), rupiah murni, SBSN, atau hibah. Membandingkan <b>pagu</b> dan <b>realisasi</b> menunjukkan serapan; membandingkan serapan dengan progres menunjukkan efisiensi.',
    ],
    'berpikir' => [
        ['Apa saja kebutuhan biayanya?', 'Rinci per item: honor, perjalanan, belanja modal, jasa konsultan.'],
        ['Dari mana dananya?', 'Tentukan sumber dana agar sesuai aturan penggunaan.'],
        ['Kapan dibelanjakan?', 'Catat tanggal realisasi saat dana terpakai.'],
        ['Apakah serapan sejalan dengan progres?', 'Serapan tinggi dengan progres rendah adalah tanda bahaya.'],
    ],
    'kolom' => [
        ['Inisiatif', 'Inisiatif yang dibiayai.'],
        ['Item anggaran', 'Uraian belanja.'],
        ['Kategori', 'Jenis belanja, misalnya Barang, Jasa, Modal, atau Pegawai.'],
        ['Pagu / Realisasi', 'Nilai anggaran yang direncanakan dan yang sudah terpakai.'],
        ['Sumber dana', 'PNBP/BLU, Rupiah Murni, SBSN, Hibah, atau lainnya.'],
        ['Tanggal realisasi', 'Tanggal pembayaran atau pertanggungjawaban.'],
        ['Catatan', 'Keterangan tambahan.'],
    ],
    'contoh' => [
        'kolom' => ['Item', 'Kategori', 'Sumber dana', 'Pagu (Rp juta)', 'Realisasi'],
        'baris' => [
            ['Biaya asesmen akreditasi internasional', 'Jasa', 'PNBP/BLU', '1.500', '600'],
            ['Lisensi dan implementasi sistem informasi', 'Modal', 'PNBP/BLU', '3.200', '2.100'],
            ['Pembangunan gedung kuliah Kampus 2', 'Modal', 'SBSN', '45.000', '18.000'],
        ],
    ],
    'hindari' => [
        'Anggaran tidak dirinci sehingga serapan sulit dianalisis.',
        'Realisasi tidak diperbarui hingga akhir tahun.',
        'Sumber dana tidak dicatat sehingga sulit menyusun laporan.',
    ],
    'kaitan' => ['inisiatif' => 'induk anggaran', 'tinjauan-kinerja' => 'pembahasan serapan'],
];
