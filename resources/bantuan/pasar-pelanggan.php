<?php

return [
    'ringkas' => 'Segmentasi pengguna layanan: siapa saja yang dilayani, seberapa besar kontribusinya, apa kebutuhannya, dan segmen mana yang menjadi sasaran.',
    'teori' => [
        'Segmentasi membagi pasar menjadi kelompok pengguna dengan kebutuhan serupa. Strategi yang baik tidak melayani semua orang dengan cara yang sama, tetapi memilih <b>segmen sasaran</b> dan merancang proposisi nilai yang tepat untuk tiap segmen.',
        'Dalam Balanced Scorecard sektor publik, segmen inilah yang menjadi isi perspektif <b>Pemangku Kepentingan</b>: siapa yang harus puas dan apa yang mereka harapkan dari kita.',
        'Untuk perguruan tinggi BLU, segmen tidak hanya mahasiswa reguler. Ada mahasiswa pascasarjana, mahasiswa internasional, pengguna jasa laboratorium, mitra kerja sama, penyewa aset, dan penerima layanan pengabdian.',
    ],
    'berpikir' => [
        ['Siapa saja yang kita layani?', 'Daftar semua kelompok pengguna, termasuk yang memberi pendapatan PNBP non-UKT.'],
        ['Seberapa penting tiap segmen?', 'Lihat jumlah, porsi pendapatan, dan pertumbuhannya.'],
        ['Apa yang mereka butuhkan?', 'Tulis kebutuhan dari sudut pandang pengguna, bukan dari sudut pandang kampus.'],
        ['Segmen mana yang kita prioritaskan?', 'Tandai segmen sasaran yang akan mendapat perhatian khusus dalam strategi.'],
        ['Bagaimana cara melayaninya?', 'Rumuskan strategi singkat untuk setiap segmen sasaran.'],
    ],
    'kolom' => [
        ['Segmen', 'Nama segmen, misalnya "Mahasiswa S1 reguler".'],
        ['Kelompok', 'Pengelompokan segmen, misalnya Pendidikan, Riset & Layanan, atau Bisnis/Aset.'],
        ['Pertumbuhan', 'Arah perkembangan segmen: Naik, Stabil, atau Turun.'],
        ['Porsi pendapatan (%)', 'Persentase kontribusi segmen terhadap total pendapatan BLU.'],
        ['Kontribusi pendapatan (Rp juta)', 'Nilai rupiah kontribusi pada tahun data.'],
        ['Segmen sasaran', 'Aktifkan bila segmen ini diprioritaskan dalam Renstra.'],
        ['Uraian & contoh akun', 'Gambaran segmen dan contoh pengguna nyata (tanpa data pribadi).'],
        ['Kebutuhan pelanggan', 'Apa yang dicari segmen ini dari instansi.'],
        ['Strategi', 'Cara instansi melayani dan menumbuhkan segmen ini.'],
        ['Sumber data', 'Asal angka, misalnya laporan keuangan BLU atau data PMB.'],
    ],
    'contoh' => [
        'kolom' => ['Segmen', 'Porsi', 'Tren', 'Kebutuhan', 'Strategi'],
        'baris' => [
            ['Mahasiswa S1 reguler', '68%', 'Naik', 'Kurikulum relevan, biaya terjangkau, prospek kerja', 'Kurikulum OBE dan pusat karier aktif'],
            ['Mahasiswa pascasarjana', '12%', 'Naik', 'Jadwal fleksibel, pembimbing bermutu, publikasi', 'Kelas hibrida dan klinik publikasi'],
            ['Mitra kerja sama & hibah', '9%', 'Stabil', 'Hasil riset terapan dan pelaporan tepat waktu', 'Kantor kerja sama satu pintu'],
            ['Pengguna aset & jasa', '6%', 'Naik', 'Fasilitas siap pakai dan layanan cepat', 'Unit bisnis dengan standar layanan'],
        ],
    ],
    'hindari' => [
        'Hanya menulis satu segmen "mahasiswa" sehingga strategi tidak tajam.',
        'Menandai semua segmen sebagai sasaran.',
        'Kebutuhan ditulis dari sudut pandang kampus ("meningkatkan PNBP") dan bukan dari sudut pandang pengguna.',
    ],
    'kaitan' => ['pemangku-kepentingan' => 'harapan pihak berkepentingan', 'sasaran-strategis' => 'perspektif Pemangku Kepentingan', 'indikator-kinerja' => 'ukur kepuasan dan pertumbuhan segmen'],
];
