<?php

return [
    'ringkas' => 'Rincian pekerjaan dalam sebuah inisiatif, dengan jadwal, bobot, progres, dan catatan perkembangan.',
    'teori' => [
        'Struktur rincian kerja (<i>work breakdown structure</i>) memecah inisiatif menjadi aktivitas yang bisa ditugaskan dan dipantau. Aktivitas yang baik cukup kecil untuk dikerjakan satu unit dalam beberapa minggu atau bulan.',
        'Setiap aktivitas punya <b>bobot</b> yang mencerminkan porsi usahanya. Progres inisiatif dihitung dari rata-rata tertimbang progres aktivitas (dan milestone), sehingga bobot yang tepat membuat angka progres jujur.',
        'Aktivitas yang melewati tenggat dengan progres di bawah 100% tampil sebagai terlambat di Beranda dan Pusat Kendali.',
    ],
    'berpikir' => [
        ['Apa saja langkah besarnya?', 'Pecah inisiatif menjadi 4–10 aktivitas berurutan.'],
        ['Berapa bobot tiap aktivitas?', 'Aktivitas yang menyerap usaha lebih besar diberi bobot lebih besar; total bobot sebaiknya 100.'],
        ['Kapan mulai dan selesai?', 'Pastikan tenggat realistis dan berada dalam jadwal inisiatif.'],
        ['Bagaimana memperbarui progres?', 'Perbarui progres dan catatan perkembangan setiap bulan.'],
    ],
    'kolom' => [
        ['Inisiatif', 'Inisiatif induk.'],
        ['Aktivitas', 'Nama pekerjaan dengan kata kerja.'],
        ['Mulai / Tenggat', 'Jadwal aktivitas.'],
        ['Bobot', 'Porsi usaha aktivitas dalam inisiatif.'],
        ['Progres (%)', 'Persentase penyelesaian saat ini.'],
        ['Catatan perkembangan', 'Kemajuan terakhir dan kendala.'],
    ],
    'contoh' => [
        'kolom' => ['Aktivitas', 'Tenggat', 'Bobot', 'Progres', 'Catatan'],
        'baris' => [
            ['Pemetaan kesiapan 5 prodi terhadap standar internasional', 'Mar 2026', '15', '100%', 'Selesai, 2 prodi paling siap'],
            ['Penyusunan dokumen evaluasi diri', 'Jun 2026', '30', '70%', 'Menunggu data alumni'],
            ['Pendampingan oleh asesor eksternal', 'Sep 2026', '25', '40%', 'Jadwal kunjungan disepakati'],
            ['Pengajuan dan visitasi', 'Des 2026', '30', '0%', 'Belum dimulai'],
        ],
    ],
    'hindari' => [
        'Aktivitas terlalu besar sehingga progres lama tidak bergerak.',
        'Bobot sama rata padahal usahanya jauh berbeda.',
        'Progres tidak diperbarui sehingga inisiatif tampak terlambat.',
    ],
    'kaitan' => ['inisiatif' => 'induk aktivitas', 'milestone-inisiatif' => 'titik kendali hasil'],
];
