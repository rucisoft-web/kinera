<?php

return [
    'ringkas' => 'Indikator Kinerja (IK/IKU) yang mengukur setiap sasaran: satuan, polaritas, baseline, target tahunan, trajektori, dan definisi operasional.',
    'teori' => [
        'Indikator kinerja mengubah sasaran menjadi angka yang bisa dipantau. Balanced Scorecard membedakan <b>indikator hasil</b> (<i>lag</i>, misalnya persentase lulusan bekerja) dan <b>indikator pendorong</b> (<i>lead</i>, misalnya persentase prodi dengan kurikulum OBE). Kombinasi keduanya membuat pimpinan bisa bertindak sebelum hasil akhir terlambat diketahui.',
        'Indikator yang baik memenuhi kriteria SMART: spesifik, terukur, dapat dicapai, relevan, dan berbatas waktu. Setiap indikator wajib punya <b>definisi operasional</b>: batasan, formula, sumber data, cara pengambilan data, dan metode verifikasi.',
        '<b>Polaritas</b> menentukan cara skor dihitung: "makin besar makin baik" (misalnya jumlah publikasi) atau "makin kecil makin baik" (misalnya masa tunggu lulusan). <b>Agregasi</b> menentukan cara angka bulanan dihitung menjadi capaian tahun berjalan: dijumlah, dirata-rata, atau memakai nilai terakhir.',
    ],
    'berpikir' => [
        ['Apa bukti bahwa sasaran tercapai?', 'Untuk setiap sasaran, cari 1–3 ukuran yang paling meyakinkan.'],
        ['Hasil atau pendorong?', 'Lengkapi indikator hasil dengan sedikitnya satu indikator pendorong.'],
        ['Apakah datanya tersedia?', 'Pastikan sumber data jelas dan bisa dikumpulkan secara rutin.'],
        ['Berapa targetnya?', 'Tetapkan baseline dari realisasi tahun lalu, lalu target tahunan sesuai Renstra.'],
        ['Seberapa sering diukur?', 'Pilih trajektori bulanan, triwulanan, semesteran, atau tahunan sesuai ketersediaan data.'],
        ['Siapa pemiliknya?', 'Setiap IK harus punya unit pemilik yang mengisi realisasi.'],
    ],
    'kolom' => [
        ['Nama Indikator Kinerja', 'Nama ringkas yang jelas, misalnya "Persentase lulusan yang bekerja dalam 6 bulan".'],
        ['Sasaran', 'Sasaran strategis yang diukur.'],
        ['Satuan', 'Persen, orang, Rp juta, bulan, indeks, dan sebagainya.'],
        ['Polaritas', 'Makin besar makin baik, atau makin kecil makin baik.'],
        ['Agregasi kumulatif', 'Jumlah untuk angka yang bertambah (misalnya PNBP), Rata-rata untuk persentase bulanan, Nilai terakhir untuk indeks atau status.'],
        ['Bobot dalam sasaran', 'Kepentingan relatif IK di sasarannya (total satu sasaran = 100).'],
        ['Baseline', 'Realisasi tahun lalu sebagai titik awal.'],
        ['Target tahunan', 'Target sesuai Renstra atau Kontrak Kinerja.'],
        ['Trajektori', 'Frekuensi pengukuran: Bulanan, Triwulanan, Semesteran, atau Tahunan.'],
        ['Wajib verifikasi Keuangan', 'Aktifkan untuk IK keuangan agar realisasi diverifikasi bagian keuangan sebelum disetujui.'],
        ['IK kritis', 'Aktifkan bila IK ini dipantau langsung pimpinan.'],
        ['Bagian Kontrak Kinerja BLU', 'Aspek, mandatory, capaian maksimum, dan penanda syarat insentif untuk IK yang dikontrakkan.'],
        ['Definisi operasional', 'Batasan indikator, formula, sumber data, cara pengambilan data, metode pengujian, dan dokumen pendukung.'],
    ],
    'contoh' => [
        'kolom' => ['Sasaran', 'Indikator', 'Satuan', 'Polaritas', 'Agregasi', 'Target'],
        'baris' => [
            ['Kualitas lulusan', 'Persentase lulusan bekerja/berwirausaha dalam 6 bulan', '%', 'Makin besar', 'Nilai terakhir', '80'],
            ['Kualitas lulusan', 'Rata-rata masa tunggu kerja lulusan', 'bulan', 'Makin kecil', 'Nilai terakhir', '4'],
            ['Kemandirian keuangan', 'Realisasi PNBP', 'Rp juta', 'Makin besar', 'Jumlah', '220.654'],
            ['Rekognisi riset', 'Jumlah publikasi internasional bereputasi', 'artikel', 'Makin besar', 'Jumlah', '450'],
            ['Kualitas SDM', 'Persentase dosen bergelar doktor', '%', 'Makin besar', 'Nilai terakhir', '65'],
        ],
    ],
    'hindari' => [
        'Indikator ditulis sebagai kegiatan ("terlaksananya pelatihan").',
        'Polaritas salah sehingga capaian yang buruk tampil hijau.',
        'Agregasi "Jumlah" dipakai untuk persentase sehingga capaian tahun berjalan menjadi ratusan persen.',
        'Definisi operasional kosong sehingga angka diperdebatkan saat evaluasi.',
    ],
    'kaitan' => ['target-ik' => 'target bulanan', 'realisasi-ik' => 'pengisian capaian', 'kontrak-kinerja' => 'IK yang dikontrakkan'],
];
