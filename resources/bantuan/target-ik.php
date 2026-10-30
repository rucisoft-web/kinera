<?php

return [
    'ringkas' => 'Target per bulan atau per periode untuk setiap IK, sehingga capaian bisa dipantau sepanjang tahun dan tidak hanya di akhir tahun.',
    'teori' => [
        'Target tahunan perlu dipecah menjadi <b>trajektori</b>: target per bulan, triwulan, atau semester. Tanpa trajektori, pimpinan baru tahu sebuah IK gagal ketika tahun sudah berakhir.',
        'Pola trajektori mengikuti sifat kegiatan. PNBP UKT, misalnya, menumpuk pada bulan pembayaran semester; publikasi cenderung meningkat di akhir tahun; persentase kepuasan relatif stabil. Target bulanan yang realistis mengikuti pola musiman tersebut.',
        'Untuk IK dengan agregasi <b>Jumlah</b>, target bulanan dijumlah menjadi target tahunan. Untuk agregasi <b>Rata-rata</b> atau <b>Nilai terakhir</b>, target bulanan adalah tingkat yang ingin dicapai pada bulan itu.',
    ],
    'berpikir' => [
        ['Lihat pola tahun lalu', 'Bagaimana realisasi bulanan tahun lalu menyebar? Gunakan sebagai dasar pola.'],
        ['Sesuaikan dengan kalender kegiatan', 'Kapan pembayaran UKT, wisuda, penerimaan mahasiswa baru, dan pencairan hibah?'],
        ['Periksa jumlahnya', 'Untuk agregasi Jumlah, total target bulanan harus sama dengan target tahunan.'],
        ['Catat alasan penyesuaian', 'Bila target bulan tertentu diubah, tulis alasannya di kolom catatan.'],
    ],
    'kolom' => [
        ['IK', 'Indikator yang ditargetkan.'],
        ['Bulan', 'Bulan target. Untuk IK triwulanan isi Maret, Juni, September, dan Desember.'],
        ['Target', 'Nilai target pada bulan tersebut.'],
        ['Catatan', 'Alasan pola atau perubahan target.'],
    ],
    'contoh' => [
        'kolom' => ['IK', 'Agregasi', 'Jan', 'Feb', 'Mar', 'Catatan'],
        'baris' => [
            ['Realisasi PNBP (Rp juta)', 'Jumlah', '28.000', '24.000', '12.000', 'Puncak pembayaran UKT semester genap'],
            ['Persentase dosen bergelar doktor', 'Nilai terakhir', '60', '60', '61', 'Naik setelah kelulusan studi lanjut'],
            ['Indeks kepuasan layanan akademik', 'Rata-rata', '3,40', '3,40', '3,45', 'Survei bulanan'],
        ],
    ],
    'hindari' => [
        'Membagi target tahunan rata per 12 bulan tanpa melihat pola musiman.',
        'Total target bulanan tidak sama dengan target tahunan untuk agregasi Jumlah.',
        'Mengisi target di bulan yang bukan bulan pelaporan untuk IK triwulanan.',
    ],
    'kaitan' => ['indikator-kinerja' => 'target tahunan dan trajektori', 'realisasi-ik' => 'dibandingkan dengan realisasi'],
];
