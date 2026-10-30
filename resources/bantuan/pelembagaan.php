<?php

return [
    'ringkas' => 'Mekanisme yang menjadikan manajemen strategis kebiasaan organisasi: rapat rutin, kebijakan, SOP, sistem, pelatihan, dan budaya.',
    'teori' => [
        'Strategi yang hanya bergantung pada figur pimpinan akan hilang ketika pimpinan berganti. <b>Pelembagaan</b> (<i>institutionalization</i>) memastikan praktik manajemen strategis tertanam dalam aturan, sistem, dan kebiasaan sehingga bertahan lintas periode kepemimpinan.',
        'Kaplan dan Norton menganjurkan adanya <b>Office of Strategy Management</b> dan siklus manajemen yang terdokumentasi. Di instansi, wujudnya bisa berupa peraturan rektor tentang manajemen kinerja, SOP pengisian realisasi, jadwal rapat tinjauan, dan pelatihan berkala.',
        'Setiap mekanisme melewati tahap <b>rencana</b>, <b>berjalan</b>, lalu <b>melembaga</b> ketika sudah konsisten dijalankan tanpa perlu didorong.',
    ],
    'berpikir' => [
        ['Praktik apa yang harus bertahan?', 'Misalnya rapat tinjauan bulanan, pengisian realisasi, dan revisi target berbasis data.'],
        ['Apa bentuk pelembagaannya?', 'Kebijakan, SOP, sistem, pelatihan, atau kebiasaan budaya?'],
        ['Seberapa sering dijalankan?', 'Tetapkan frekuensi dan kapan ditinjau.'],
        ['Apa buktinya sudah melembaga?', 'Dokumen, notulen rutin, atau data penggunaan sistem.'],
    ],
    'kolom' => [
        ['Mekanisme', 'Nama mekanisme.'],
        ['Jenis', 'Rapat rutin, Kebijakan, SOP, Sistem, Pelatihan, atau Budaya.'],
        ['Frekuensi', 'Seberapa sering mekanisme dijalankan.'],
        ['Uraian', 'Penjelasan cara kerja.'],
        ['Mulai / Tinjauan berikutnya', 'Kapan mulai dan kapan dievaluasi lagi.'],
        ['Bukti', 'Dokumen atau data pendukung.'],
        ['Status', 'Rencana, Berjalan, atau Melembaga.'],
    ],
    'contoh' => [
        'kolom' => ['Mekanisme', 'Jenis', 'Frekuensi', 'Status'],
        'baris' => [
            ['Peraturan Rektor tentang sistem manajemen kinerja berbasis BSC', 'Kebijakan', 'Ditinjau tahunan', 'Berjalan'],
            ['Rapat tinjauan kinerja pimpinan', 'Rapat rutin', 'Bulanan', 'Melembaga'],
            ['SOP pengisian dan verifikasi realisasi IK', 'SOP', 'Bulanan', 'Berjalan'],
            ['Pelatihan pengelola kinerja unit', 'Pelatihan', 'Semesteran', 'Rencana'],
        ],
    ],
    'hindari' => [
        'Status "Melembaga" padahal baru dijalankan sekali.',
        'Tidak ada tanggal tinjauan berikutnya.',
        'Mekanisme bergantung pada satu orang.',
    ],
    'kaitan' => ['penilaian-kematangan' => 'ukur kemajuan pelembagaan', 'tinjauan-kinerja' => 'rapat rutin'],
];
