<?php

return [
    'ringkas' => 'Notulen rapat tinjauan kinerja: ringkasan pembahasan, keputusan, dan tindak lanjut, termasuk catatan pengawas.',
    'teori' => [
        'Strategi hanya hidup bila ditinjau secara berkala. Kaplan dan Norton menganjurkan siklus rapat: <b>tinjauan operasional</b> (mingguan atau bulanan), <b>tinjauan strategi</b> (bulanan atau triwulanan) untuk membahas capaian scorecard, dan <b>uji serta adaptasi strategi</b> (tahunan) untuk menguji apakah hipotesis strategi masih benar.',
        'Rapat tinjauan yang efektif dimulai dari angka merah dan kuning, membahas penyebab, lalu menghasilkan keputusan dengan penanggung jawab dan tenggat.',
        'Di instansi BLU, rapat dengan Dewan Pengawas atau APIP juga dicatat di sini, sehingga catatan pengawasan tersimpan bersama data kinerjanya.',
    ],
    'berpikir' => [
        ['Apa yang ditinjau?', 'Tentukan periodenya dan bahan utamanya: Pusat Kendali, laporan IK, inisiatif terlambat.'],
        ['Mulai dari yang merah', 'Bahas IK dan inisiatif kritis lebih dulu.'],
        ['Apa keputusannya?', 'Tulis keputusan yang jelas, bukan sekadar diskusi.'],
        ['Siapa melakukan apa, kapan?', 'Setiap tindak lanjut punya PIC dan tenggat; masalah serius dicatat sebagai temuan.'],
    ],
    'kolom' => [
        ['Judul', 'Nama rapat, misalnya "Rapat Tinjauan Kinerja Triwulan III 2026".'],
        ['Jenis', 'Laporan mingguan, rapat manajemen, rapat pengawas, tinjauan triwulanan, atau tahunan.'],
        ['Tanggal / Perioda / Tempat', 'Waktu rapat, periode yang ditinjau, dan lokasi.'],
        ['Pimpinan rapat dan Peserta', 'Jabatan pimpinan rapat dan peserta (cukup jabatan).'],
        ['Ringkasan / uraian', 'Pokok pembahasan dan angka penting.'],
        ['Keputusan & arahan', 'Keputusan yang diambil.'],
        ['Tindak lanjut', 'Daftar tindakan, PIC, dan tenggat.'],
    ],
    'contoh' => [
        'kolom' => ['Rapat', 'Jenis', 'Keputusan utama'],
        'baris' => [
            ['Tinjauan Kinerja Triwulan III 2026', 'Tinjauan Triwulanan', 'Percepat klinik publikasi; realokasi anggaran ke hibah kolaborasi'],
            ['Rapat Manajemen September 2026', 'Rapat Manajemen', 'Bagian keuangan mengejar piutang kerja sama sebelum Oktober'],
            ['Rapat Dewan Pengawas Triwulan III', 'Rapat pengawas', 'Dewas meminta rencana mitigasi penurunan pendapatan usaha'],
        ],
    ],
    'hindari' => [
        'Notulen hanya berisi daftar hadir tanpa keputusan.',
        'Tindak lanjut tanpa PIC dan tenggat.',
        'Masalah serius tidak dicatat sebagai temuan sehingga tidak dipantau.',
    ],
    'kaitan' => ['temuan' => 'masalah yang harus ditindaklanjuti', 'realisasi-ik' => 'bahan rapat', 'inisiatif' => 'status program'],
];
