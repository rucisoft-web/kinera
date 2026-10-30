<?php

return [
    'ringkas' => 'Daftar persoalan mendasar yang harus dijawab strategi, disaring dari hasil analisis lingkungan, dengan urgensi dan dampaknya.',
    'teori' => [
        'Isu strategis adalah pertanyaan kebijakan atau tantangan mendasar yang memengaruhi mandat, misi, nilai, layanan, pengguna, biaya, atau tata kelola instansi (Bryson). Isu strategis berbeda dari masalah operasional: isu strategis tidak selesai dengan memperbaiki prosedur, tetapi membutuhkan pilihan arah.',
        'Isu yang baik dirumuskan sebagai <b>pertanyaan atau tantangan yang dapat dijawab</b>, misalnya "Bagaimana meningkatkan kemandirian keuangan untuk menyiapkan PTN-BH?".',
        'Prioritas isu ditentukan dari <b>urgensi</b> (seberapa mendesak) dan <b>dampak</b> (seberapa besar akibatnya). Isu prioritas inilah yang dijawab oleh strategi TOWS, tema strategis, dan sasaran.',
    ],
    'berpikir' => [
        ['Kumpulkan bahan', 'Baca hasil PESTEL, Analisis Industri, Pemangku Kepentingan, dan SWOT.'],
        ['Cari benang merah', 'Temuan mana yang muncul berulang atau saling memperkuat?'],
        ['Rumuskan sebagai tantangan', 'Gunakan kalimat "Bagaimana ..." atau "Perlunya ..." agar terasa sebagai pekerjaan rumah.'],
        ['Uji apakah benar strategis', 'Apakah isu ini memengaruhi misi dan butuh keputusan pimpinan? Bila hanya prosedur, jangan masukkan.'],
        ['Prioritaskan', 'Nilai urgensi dan dampak. Batasi 5–8 isu prioritas.'],
    ],
    'kolom' => [
        ['Isu', 'Rumusan isu dalam satu kalimat tantangan.'],
        ['Uraian', 'Latar belakang, bukti, dan akibat bila tidak ditangani.'],
        ['Urgensi', 'Skala 1–5 seberapa mendesak isu ditangani.'],
        ['Dampak', 'Skala 1–5 besarnya akibat terhadap misi dan kinerja.'],
        ['Status', 'Terbuka, Ditangani, atau Selesai.'],
        ['Sumber', 'Analisis asal isu, misalnya "PESTEL – Politik" atau "SWOT".'],
        ['Pemangku kepentingan terkait', 'Pihak yang paling terdampak atau berkepentingan.'],
    ],
    'contoh' => [
        'kolom' => ['Isu', 'Urgensi', 'Dampak', 'Sumber'],
        'baris' => [
            ['Bagaimana meningkatkan kemandirian keuangan menuju PTN-BH?', '5', '5', 'PESTEL Politik, analisis keuangan BLU'],
            ['Bagaimana meningkatkan rekognisi internasional riset dan prodi?', '4', '5', 'Evaluasi Renstra, analisis pesaing'],
            ['Perlunya sistem informasi terintegrasi untuk tata kelola', '4', '4', 'Pemindaian internal'],
            ['Bagaimana menjaga keterjangkauan biaya di tengah melemahnya daya beli?', '3', '4', 'PESTEL Ekonomi'],
        ],
    ],
    'hindari' => [
        'Menulis isu sebagai solusi ("membangun gedung baru") dan bukan sebagai tantangan.',
        'Terlalu banyak isu sehingga tidak ada yang benar-benar diprioritaskan.',
        'Isu tidak dihubungkan ke strategi TOWS sehingga berhenti sebagai daftar.',
    ],
    'kaitan' => ['tows' => 'strategi yang menjawab isu', 'tema-strategis' => 'pengelompokan arah', 'sasaran-strategis' => 'sasaran yang menjawab isu'],
];
