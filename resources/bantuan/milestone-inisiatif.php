<?php

return [
    'ringkas' => 'Titik kendali hasil dalam inisiatif: keluaran penting yang harus tercapai pada tanggal tertentu, lengkap dengan buktinya.',
    'teori' => [
        'Milestone adalah peristiwa atau keluaran penting yang menandai kemajuan, misalnya "SK tim terbit" atau "Dokumen evaluasi diri diserahkan". Berbeda dari aktivitas yang menggambarkan pekerjaan, milestone menggambarkan <b>hasil yang bisa diperiksa</b>: tercapai atau belum.',
        'Milestone membantu pimpinan memantau inisiatif tanpa harus membaca rincian aktivitas. Milestone yang terlewat adalah sinyal dini bahwa inisiatif berisiko.',
    ],
    'berpikir' => [
        ['Apa bukti kemajuan yang paling meyakinkan?', 'Pilih 3–6 keluaran kunci sepanjang umur inisiatif.'],
        ['Kapan harus tercapai?', 'Tetapkan tanggal yang mengikat.'],
        ['Apa buktinya?', 'Dokumen, SK, berita acara, atau tautan yang menunjukkan milestone tercapai.'],
    ],
    'kolom' => [
        ['Inisiatif', 'Inisiatif induk.'],
        ['Milestone', 'Keluaran yang harus tercapai.'],
        ['Tenggat', 'Tanggal target tercapai.'],
        ['Bobot', 'Porsi milestone dalam progres inisiatif.'],
        ['Tercapai / Tanggal tercapai', 'Centang dan isi tanggal ketika tercapai.'],
        ['Bukti', 'Dokumen atau keterangan bukti.'],
    ],
    'contoh' => [
        'kolom' => ['Milestone', 'Tenggat', 'Tercapai', 'Bukti'],
        'baris' => [
            ['SK tim percepatan akreditasi internasional terbit', 'Feb 2026', 'Ya', 'SK Rektor'],
            ['Dokumen evaluasi diri 5 prodi lengkap', 'Jul 2026', 'Belum', '-'],
            ['Visitasi asesor internasional', 'Nov 2026', 'Belum', '-'],
        ],
    ],
    'hindari' => [
        'Milestone berupa kegiatan ("rapat koordinasi") dan bukan keluaran.',
        'Ditandai tercapai tanpa bukti.',
    ],
    'kaitan' => ['inisiatif' => 'induk milestone', 'aktivitas-inisiatif' => 'pekerjaan menuju milestone'],
];
