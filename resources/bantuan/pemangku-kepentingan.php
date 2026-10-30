<?php

return [
    'ringkas' => 'Pemetaan pihak yang berkepentingan terhadap instansi: seberapa besar pengaruh dan kepentingannya, apa harapannya, dan bagaimana melibatkannya.',
    'teori' => [
        'Pemangku kepentingan adalah individu atau kelompok yang memengaruhi atau dipengaruhi oleh pencapaian tujuan instansi. Dalam Balanced Scorecard sektor publik, perspektif <b>Pemangku Kepentingan</b> berada di puncak peta strategi karena misi instansi pemerintah adalah melayani mereka.',
        'Alat yang umum dipakai adalah <b>matriks pengaruh–kepentingan</b> (Mendelow). Pihak dengan pengaruh dan kepentingan tinggi <i>dikelola erat</i>; pengaruh tinggi tetapi kepentingan rendah <i>dijaga kepuasannya</i>; kepentingan tinggi tetapi pengaruh rendah <i>diberi informasi</i>; keduanya rendah cukup <i>dipantau</i>.',
        'Harapan yang dicatat di sini menjadi bahan untuk merumuskan sasaran dan indikator kepuasan.',
    ],
    'berpikir' => [
        ['Siapa saja pihaknya?', 'Daftar pihak internal (pimpinan, dosen, tenaga kependidikan, mahasiswa) dan eksternal (kementerian, DJPb, Dewan Pengawas, alumni, mitra, masyarakat).'],
        ['Seberapa besar pengaruhnya?', 'Bisakah mereka mengubah anggaran, kebijakan, atau reputasi kita?'],
        ['Seberapa besar kepentingannya?', 'Seberapa besar mereka terdampak oleh kinerja kita?'],
        ['Apa yang mereka harapkan?', 'Tulis harapan dalam bahasa mereka, sebaiknya dari survei atau forum resmi.'],
        ['Bagaimana melibatkannya?', 'Pilih cara: rapat berkala, laporan, forum konsultasi, atau survei.'],
    ],
    'kolom' => [
        ['Nama', 'Nama pihak atau kelompok, misalnya "Mahasiswa S1" atau "Dewan Pengawas BLU".'],
        ['Jenis', 'Internal atau Eksternal.'],
        ['Kategori', 'Pengelompokan bebas, misalnya Regulator, Pengguna layanan, Mitra.'],
        ['Pengaruh', 'Skala 1–5 kemampuan pihak ini memengaruhi keputusan dan sumber daya.'],
        ['Kepentingan', 'Skala 1–5 seberapa besar pihak ini terdampak kinerja instansi.'],
        ['Harapan', 'Apa yang diharapkan pihak ini dari instansi.'],
        ['Strategi keterlibatan', 'Cara melibatkan dan berkomunikasi dengan pihak ini.'],
        ['Kontak / PIC internal', 'Unit atau jabatan yang menjadi penghubung (tanpa data pribadi).'],
    ],
    'contoh' => [
        'kolom' => ['Pihak', 'Pengaruh', 'Kepentingan', 'Harapan', 'Keterlibatan'],
        'baris' => [
            ['Kementerian Agama', '5', '4', 'Capaian IKU, tata kelola akuntabel', 'Laporan kinerja dan rapat koordinasi'],
            ['Dewan Pengawas BLU', '4', '5', 'Kinerja keuangan sehat dan patuh', 'Rapat triwulanan dan akses dasbor'],
            ['Mahasiswa', '3', '5', 'Layanan akademik cepat, biaya wajar', 'Survei kepuasan dan forum dialog'],
            ['Pengguna lulusan', '3', '4', 'Lulusan kompeten dan beretika', 'Tracer study dan forum industri'],
        ],
    ],
    'hindari' => [
        'Melupakan pihak internal seperti dosen dan tenaga kependidikan.',
        'Harapan ditulis berdasarkan asumsi, bukan hasil mendengar langsung.',
        'Tidak ada tindak lanjut setelah pemetaan.',
    ],
    'kaitan' => ['pasar-pelanggan' => 'segmen pengguna layanan', 'sasaran-strategis' => 'perspektif Pemangku Kepentingan', 'isu-strategis' => 'harapan yang belum terpenuhi'],
];
