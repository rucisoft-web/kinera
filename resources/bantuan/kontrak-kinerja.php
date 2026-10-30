<?php

return [
    'ringkas' => 'Dokumen Kontrak Kinerja BLU antara pimpinan dan Dirjen Perbendaharaan: versi, status, tahapan pembahasan, dan persyaratan insentif.',
    'teori' => [
        'Kontrak Kinerja adalah kesepakatan target kinerja antara pemimpin BLU dan Kementerian Keuangan (Ditjen Perbendaharaan) yang menjadi dasar penilaian kinerja dan pemberian insentif atau remunerasi. Ketentuannya diatur dalam peraturan Dirjen Perbendaharaan tentang Kontrak Kinerja BLU.',
        'Kontrak memuat indikator dari aspek keuangan dan layanan, lengkap dengan bobot, target, definisi operasional, dan capaian maksimum. Indikator tersebut dikelola di layar <b>Indikator Kinerja</b> (bagian Kontrak Kinerja BLU); layar ini mengelola dokumennya.',
        'Kontrak bisa berubah melalui <b>adendum</b> bila ada perubahan kebijakan atau kondisi luar biasa, dengan dasar perubahan dan berita acara pembahasan.',
    ],
    'berpikir' => [
        ['Versi mana yang berlaku?', 'Kontrak awal atau adendum? Pastikan hanya satu versi berlaku per tahun.'],
        ['Indikator apa yang dikontrakkan?', 'Pastikan IK yang ditandai sebagai IK Kontrak Kinerja sudah lengkap definisi dan targetnya.'],
        ['Apa dasar perubahannya?', 'Untuk adendum, tulis alasan dan hasil kajian secara jelas.'],
        ['Di tahap mana prosesnya?', 'Usulan, pembahasan (berita acara), atau sudah ditandatangani.'],
        ['Apakah syarat insentif terpenuhi?', 'Periksa kebebasan kasus hukum dan persyaratan lain dari Pembina Teknis dan Keuangan.'],
    ],
    'kolom' => [
        ['Versi / Jenis', 'Kontrak awal atau adendum, dengan nomor versi.'],
        ['Status', 'Tahap dokumen: draf, diusulkan, dibahas, atau ditandatangani.'],
        ['Nomor Kontrak Kinerja', 'Nomor resmi dokumen.'],
        ['Dasar perubahan / Alasan', 'Untuk adendum: dasar dan hasil kajian perubahan.'],
        ['Surat persetujuan Pembina Teknis', 'Nomor surat persetujuan dari kementerian teknis.'],
        ['Tanggal usulan, pembahasan, dan nomor BA', 'Jejak proses dengan Ditjen Perbendaharaan.'],
        ['Tempat dan tanggal tanda tangan', 'Diisi setelah kontrak ditandatangani pimpinan dan Dirjen.'],
        ['Persyaratan insentif', 'Centang bila bebas kasus hukum dan persyaratan lain terpenuhi; tambahkan keterangan.'],
    ],
    'contoh' => [
        'kolom' => ['Versi', 'Jenis', 'Status', 'Catatan'],
        'baris' => [
            ['2025 v1', 'Kontrak awal', 'Ditandatangani', 'Target PNBP dan IK layanan sesuai RBA 2025'],
            ['2025 v2', 'Adendum', 'Ditandatangani', 'Penyesuaian target pendapatan karena perubahan kebijakan tarif'],
            ['2026 v1', 'Kontrak awal', 'Ditandatangani', 'Mengacu Renstra 2025–2029 tahun kedua'],
            ['2026 v2', 'Usulan perubahan', 'Diusulkan', 'Target IK layanan disesuaikan dengan hasil kajian'],
        ],
    ],
    'hindari' => [
        'Lebih dari satu versi berstatus berlaku pada tahun yang sama.',
        'Adendum tanpa dasar perubahan dan berita acara.',
        'IK kontrak belum punya definisi operasional dan formula.',
    ],
    'kaitan' => ['indikator-kinerja' => 'IK yang dikontrakkan', 'target-ik' => 'target bulanan', 'realisasi-ik' => 'capaian untuk insentif'],
];
