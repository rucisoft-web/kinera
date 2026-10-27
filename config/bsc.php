<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kinera — pengukuran kinerja instansi pemerintah (Balanced Scorecard, Perjanjian Kinerja, IKU, SAKIP),
    | termasuk PTN/PTKIN. Modul Kontrak Kinerja Pemimpin BLU (PER-6/PB/2025) untuk instansi berpola BLU.
    |--------------------------------------------------------------------------
    | KINERA_TERMS=instansi -> istilah sektor publik (bawaan): Pemangku Kepentingan, Proses Layanan Internal, ...
    | KINERA_TERMS=blu   -> sama, dengan sebutan khas BLU (Jati Diri BLU, Manajemen Kinerja BLU)
    | KINERA_TERMS=umum  -> istilah umum bahasa Indonesia (Ekosistem, Jati Diri, ...)
    */
    'terms' => env('KINERA_TERMS', 'instansi'),
    'brand_name' => env('KINERA_BRAND_NAME', 'Kinera'),
    'brand_tagline' => env('KINERA_BRAND_TAGLINE', 'Pengukuran Kinerja Instansi Pemerintah'),

    // Dibaca lewat config() supaya tetap berlaku setelah `php artisan optimize` (config cache) di produksi
    'notify_mail' => (bool) env('KINERA_NOTIFY_MAIL', false),
    'mfa_required' => (bool) env('KINERA_MFA_REQUIRED', false),
    'seed_demo' => (bool) env('KINERA_SEED_DEMO', false),
    'demo_admin_password' => env('KINERA_ADMIN_PASSWORD', 'Kinera@2026'),
    'demo_enabled' => (bool) env('KINERA_DEMO_ENABLED', false),
    // Situs demo/promosi: tombol "Masuk sebagai Tamu" (hanya lihat) di halaman login
    'guest_login' => (bool) env('KINERA_GUEST_LOGIN', false),
    'guest_email' => env('KINERA_GUEST_EMAIL', 'tamu@demo.rucisoft.id'),
    // Ajakan Workshop di menu "Kenali Kinera": tautan pendaftaran & kontak (mis. WhatsApp/email)
    // Kosongkan KINERA_WORKSHOP_URL agar tombol Daftar membuka WhatsApp KINERA_CONTACT_WHATSAPP
    'workshop_url' => env('KINERA_WORKSHOP_URL'),
    'workshop_contact' => env('KINERA_WORKSHOP_CONTACT'),
    // Tempat, tanggal, dan biaya Workshop 2 hari; bila kosong tampil "diumumkan menyusul"
    'workshop_place' => env('KINERA_WORKSHOP_TEMPAT'),
    'workshop_date' => env('KINERA_WORKSHOP_TANGGAL'),
    'workshop_fee' => env('KINERA_WORKSHOP_BIAYA'),
    // Kontak di halaman depan (tombol WhatsApp & email)
    'contact_whatsapp' => env('KINERA_CONTACT_WHATSAPP', '6281199892939'),
    'contact_email' => env('KINERA_CONTACT_EMAIL', 'rucisoft@gmail.com'),
    'audit_enabled' => (bool) env('KINERA_AUDIT_ENABLED', true),

    // Ambang status KPI (skor dalam persen)
    'score_green' => 100,
    'score_yellow' => 80,
    'score_cap' => 120,
    // Capaian di bawah ambang ini pada satu Indikator Kinerja menggugurkan syarat insentif (PER-6/PB/2025 Pasal 14 ayat 4 huruf c)
    'score_floor' => 65,

    // Tenggat PER-6/PB/2025 (bulan-tanggal); laporan capaian = 15 hari kerja setelah trajektori berakhir
    'blu' => [
        'regulation' => 'PER-6/PB/2025',
        'report_workdays' => 15,
        'agreement_deadline' => '01-25',     // Pasal 7 ayat (2): dibahas & disepakati
        'signing_deadline' => '01-31',       // Pasal 8 ayat (2) & Pasal 9 ayat (1)
        'change_proposal_deadline' => '09-30', // Pasal 19 ayat (2)
        'change_signing_deadline' => '12-01',  // Pasal 19 ayat (7)
        'incentive_request_deadline' => '06-30', // Pasal 14 ayat (5), tahun berikutnya
    ],

    // Bobot perspektif untuk skor strategis (jumlah = 100)
    'perspective_weights' => [
        'keuangan' => 30,
        'pelanggan' => 25,
        'proses' => 25,
        'sdm' => 20,
    ],

    // Jadwal siklus bulanan (tanggal pada bulan berikutnya)
    'cycle' => [
        'reminder_day' => 3,
        'input_day' => 5,
        'verify_day' => 7,
        'approve_day' => 8,
        'meeting_day' => 10,
    ],

    'dictionaries' => [
        'instansi' => [
            'brahman' => 'Lingkungan Strategis', 'atman' => 'Jati Diri Instansi', 'karma_phala' => 'Kinerja & Akuntabilitas',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Pemangku Kepentingan',
            'proses' => 'Proses Layanan Internal', 'sdm' => 'Pembelajaran & Pertumbuhan',
            'app' => 'Kinera', 'method' => 'Manajemen Kinerja Instansi Pemerintah',
        ],
        'blu' => [
            'brahman' => 'Lingkungan Strategis', 'atman' => 'Jati Diri BLU', 'karma_phala' => 'Kinerja & Kontrak',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Pemangku Kepentingan',
            'proses' => 'Proses Layanan Internal', 'sdm' => 'Pembelajaran & Pertumbuhan',
            'app' => 'Kinera', 'method' => 'Manajemen Kinerja BLU',
        ],
        'umum' => [
            'brahman' => 'Ekosistem', 'atman' => 'Jati Diri', 'karma_phala' => 'Kinerja',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Kepuasan Pelanggan',
            'proses' => 'Proses Internal', 'sdm' => 'SDM & Pembelajaran',
            'app' => 'Kinera', 'method' => 'Manajemen Strategi',
        ],
    ],
];
