<?php

return [
    'ringkas' => 'Penilaian berkala tingkat kematangan manajemen strategis instansi, dari ad hoc sampai melembaga, untuk menentukan prioritas pembenahan.',
    'teori' => [
        'Model kematangan (<i>maturity model</i>) menilai seberapa sistematis sebuah praktik dijalankan, biasanya dalam lima tingkat: awal (ad hoc), terulang, terdefinisi, terkelola, dan teroptimasi. Konsep ini dipopulerkan oleh CMMI dan banyak dipakai untuk menilai praktik manajemen kinerja.',
        'Untuk manajemen strategis, aspek yang dinilai meliputi kepemimpinan, perumusan strategi, penerjemahan ke scorecard, penyelarasan unit, perencanaan dan anggaran, pemantauan, serta pembelajaran dan adaptasi.',
        'Penilaian dilakukan setidaknya setahun sekali dengan jujur. Hasilnya menunjukkan aspek mana yang paling perlu diperkuat.',
    ],
    'berpikir' => [
        ['Siapa yang menilai?', 'Libatkan pimpinan, unit perencanaan, dan pengawas agar penilaian tidak bias.'],
        ['Apa buktinya?', 'Setiap jawaban sebaiknya didukung dokumen atau praktik nyata.'],
        ['Di mana kelemahan terbesar?', 'Fokus pada aspek dengan skor terendah.'],
        ['Apa langkah berikutnya?', 'Tulis rencana perbaikan di catatan atau sebagai inisiatif.'],
    ],
    'kolom' => [
        ['Judul penilaian', 'Misalnya "Penilaian kematangan manajemen strategis 2026".'],
        ['Tanggal', 'Tanggal penilaian.'],
        ['Jawaban per pertanyaan', 'Di bagian bawah formulir: pilih tingkat yang paling sesuai kondisi nyata untuk setiap pertanyaan.'],
        ['Skor keseluruhan', 'Dihitung otomatis dari jawaban.'],
        ['Catatan', 'Kesimpulan dan rencana perbaikan.'],
    ],
    'contoh' => [
        'kolom' => ['Aspek', 'Tingkat 2025', 'Tingkat 2026', 'Catatan'],
        'baris' => [
            ['Perumusan strategi', '3', '4', 'Renstra disusun dengan analisis lingkungan lengkap'],
            ['Penerjemahan ke scorecard', '2', '3', 'IK sudah punya definisi operasional'],
            ['Pemantauan dan tinjauan', '2', '3', 'Rapat tinjauan rutin triwulanan'],
            ['Pembelajaran dan adaptasi', '1', '2', 'Belum ada uji hipotesis strategi tahunan'],
        ],
    ],
    'hindari' => [
        'Menilai berdasarkan niat, bukan praktik yang sudah berjalan.',
        'Menilai sendiri tanpa bukti.',
        'Hasil penilaian tidak diikuti rencana perbaikan.',
    ],
    'kaitan' => ['pelembagaan' => 'menguatkan praktik', 'inisiatif' => 'program perbaikan'],
];
