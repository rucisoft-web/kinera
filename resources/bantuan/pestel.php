<?php

return [
    'ringkas' => 'Daftar faktor makro Politik, Ekonomi, Sosial, Teknologi, Lingkungan, dan Hukum yang memengaruhi instansi, lengkap dengan dampak, kemungkinan, dan respons.',
    'teori' => [
        'PESTEL adalah kerangka untuk memeriksa lingkungan makro, yaitu kekuatan besar di luar kendali instansi yang tetap harus diantisipasi: <b>P</b>olitik, <b>E</b>konomi, <b>S</b>osial, <b>T</b>eknologi, <b>E</b>nvironment (lingkungan), dan <b>L</b>egal (hukum).',
        'Setiap faktor dinilai dengan dua ukuran: <b>tingkat dampak</b> (seberapa besar pengaruhnya bila terjadi, 1–5) dan <b>kemungkinan</b> (seberapa besar peluang terjadinya, 1–5). Perkaliannya menjadi <b>skor</b> 1–25. Skor 16 ke atas berwarna merah dan wajib dibahas pimpinan; 9–15 kuning dan dipantau.',
        'Faktor yang sama bisa menjadi peluang atau ancaman tergantung posisi instansi. Kebijakan PTN-BH, misalnya, adalah peluang bagi kampus yang siap dan ancaman bagi kampus yang tata kelolanya belum rapi.',
    ],
    'berpikir' => [
        ['Telusuri satu per satu huruf PESTEL', 'Untuk setiap huruf, tanyakan: perubahan apa dalam 3–5 tahun ke depan yang akan memengaruhi mahasiswa, dosen, pendanaan, atau layanan kita?'],
        ['Tulis sebagai perubahan, bukan topik', 'Bukan "teknologi", tetapi "AI generatif mengubah cara mahasiswa belajar dan menulis tugas".'],
        ['Tentukan sifatnya', 'Apakah perubahan itu membuka peluang atau menimbulkan ancaman bagi instansi kita saat ini?'],
        ['Nilai dampak dan kemungkinan secara jujur', 'Gunakan data atau pendapat beberapa orang. Jangan memberi nilai 5 untuk semua faktor.'],
        ['Rumuskan respons', 'Apa yang akan dilakukan? Respons inilah yang nanti menjadi bahan SWOT, isu strategis, atau inisiatif.'],
    ],
    'kolom' => [
        ['Faktor', 'Pilih satu huruf PESTEL: Politik, Ekonomi, Sosial, Teknologi, Lingkungan, atau Hukum.'],
        ['Sifat', '<b>Peluang</b> bila menguntungkan bila dimanfaatkan; <b>Ancaman</b> bila merugikan bila dibiarkan.'],
        ['Judul', 'Kalimat singkat yang menggambarkan perubahan, maksimal satu baris.'],
        ['Uraian', 'Penjelasan, data pendukung, dan bagaimana faktor itu menyentuh instansi.'],
        ['Tingkat dampak', '1 = sangat kecil, 3 = sedang, 5 = sangat besar terhadap pencapaian sasaran.'],
        ['Kemungkinan', '1 = jarang terjadi, 3 = mungkin, 5 = hampir pasti atau sudah terjadi.'],
        ['Tren', 'Arah perkembangan faktor: Naik, Stabil, atau Turun.'],
        ['Sumber', 'Asal informasi, misalnya "BPS 2025", "Permendikbudristek 53/2023", "survei calon mahasiswa".'],
        ['Respons strategis', 'Tindakan yang direncanakan untuk memanfaatkan peluang atau meredam ancaman.'],
    ],
    'contoh' => [
        'kolom' => ['Faktor', 'Judul', 'Sifat', 'Dampak', 'Kemungkinan', 'Respons strategis'],
        'baris' => [
            ['Politik', 'Arah kebijakan transformasi PTKIN menjadi PTN-BH', 'Peluang', '5', '4', 'Siapkan peta jalan tata kelola dan kemandirian keuangan.'],
            ['Ekonomi', 'Daya beli keluarga mahasiswa melemah', 'Ancaman', '4', '3', 'Perluas beasiswa dan skema UKT berjenjang yang tepat sasaran.'],
            ['Sosial', 'Generasi Z memilih prodi berdasarkan prospek karier', 'Peluang', '4', '5', 'Tonjolkan profil lulusan dan kemitraan industri di promosi.'],
            ['Teknologi', 'AI generatif mengubah praktik belajar dan penilaian', 'Ancaman', '4', '5', 'Kebijakan etika AI dan redesain asesmen berbasis proyek.'],
            ['Lingkungan', 'Tuntutan kampus hijau dan efisiensi energi', 'Peluang', '3', '4', 'Program green campus dan pemeringkatan UI GreenMetric.'],
            ['Hukum', 'Kewajiban akreditasi internasional prodi unggulan', 'Ancaman', '4', '3', 'Pendampingan akreditasi internasional bertahap.'],
        ],
    ],
    'hindari' => [
        'Menulis topik umum ("ekonomi") tanpa menjelaskan perubahan yang terjadi.',
        'Mencampur faktor internal (misalnya "dosen kurang") ke PESTEL. Faktor internal masuk Kapabilitas atau SWOT bagian S/W.',
        'Semua faktor diberi nilai 5 sehingga prioritas tidak terlihat.',
        'Kolom respons dikosongkan, padahal itulah jembatan ke strategi.',
    ],
    'kaitan' => ['swot' => 'faktor berskor tinggi menjadi O atau T', 'isu-strategis' => 'ancaman merah menjadi isu', 'analisis-regulasi' => 'faktor hukum dirinci'],
];
