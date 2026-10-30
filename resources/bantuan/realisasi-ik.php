<?php

return [
    'ringkas' => 'Pengisian capaian bulanan IK oleh unit pemilik, lengkap dengan bukti, lalu diverifikasi dan disetujui.',
    'teori' => [
        'Realisasi adalah angka capaian nyata yang dibandingkan dengan target. Kinera menghitung <b>skor</b> otomatis dengan memperhitungkan polaritas: skor ≥ 100 hijau (tercapai), 80–99 kuning (perlu perhatian), di bawah 80 merah (kritis).',
        'Agar angka dipercaya, realisasi melalui alur <b>isi → verifikasi → setujui</b>. Unit pemilik mengisi dan melampirkan bukti; bagian keuangan memverifikasi IK keuangan; pimpinan menyetujui. Realisasi yang tidak sesuai bisa <b>dikembalikan</b> dengan catatan.',
        'Prinsip utamanya: angka harus bisa ditelusuri ke sumber data yang disebut dalam definisi operasional IK.',
    ],
    'berpikir' => [
        ['Apakah sudah waktunya diisi?', 'Isi sesuai jadwal pelaporan bulanan atau triwulanan IK.'],
        ['Dari mana angkanya?', 'Ambil dari sumber data resmi sesuai definisi operasional, bukan perkiraan.'],
        ['Apakah sudah final?', 'Bila masih sementara, aktifkan tanda proyeksi agar pimpinan tahu.'],
        ['Apa buktinya?', 'Lampirkan dokumen atau tangkapan data dan tulis sumbernya.'],
        ['Mengapa meleset?', 'Bila merah atau kuning, jelaskan penyebab dan rencana perbaikannya di catatan.'],
    ],
    'kolom' => [
        ['IK', 'Indikator yang diisi.'],
        ['Bulan', 'Bulan realisasi.'],
        ['Target bulan ini', 'Ditampilkan otomatis sebagai pembanding.'],
        ['Nilai realisasi', 'Angka capaian sesuai satuan IK.'],
        ['Masih proyeksi', 'Aktifkan bila angka belum final.'],
        ['Bukti / sumber angka', 'Sumber data dan cara perhitungan singkat.'],
        ['Lampiran bukti', 'Unggah dokumen pendukung.'],
        ['Catatan', 'Penjelasan capaian, terutama bila meleset.'],
        ['Status dan jejak', 'Terisi otomatis: siapa mengisi, memverifikasi, menyetujui, dan catatan pengembalian.'],
    ],
    'contoh' => [
        'kolom' => ['IK', 'Bulan', 'Target', 'Realisasi', 'Bukti', 'Catatan'],
        'baris' => [
            ['Realisasi PNBP (Rp juta)', 'September', '18.000', '19.250', 'Laporan realisasi pendapatan BLU', 'Pembayaran UKT semester gasal tepat waktu'],
            ['Persentase dosen bergelar doktor', 'September', '62', '61,5', 'Data kepegawaian per 30 September', 'Dua dosen menunda ujian disertasi'],
            ['Publikasi internasional bereputasi', 'September', '35', '22', 'Daftar artikel terindeks', 'Merah: klinik publikasi dipercepat bulan depan'],
        ],
    ],
    'hindari' => [
        'Mengisi tanpa bukti sehingga dikembalikan saat verifikasi.',
        'Mengisi angka kumulatif untuk IK yang agregasinya Jumlah (seharusnya angka bulan itu saja).',
        'Catatan kosong untuk capaian merah.',
    ],
    'kaitan' => ['target-ik' => 'pembanding', 'temuan' => 'capaian merah berulang', 'tinjauan-kinerja' => 'dibahas di rapat'],
];
