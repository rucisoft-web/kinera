<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ruci BSC BLU — edisi Badan Layanan Umum (Kontrak Kinerja Pemimpin BLU, PER-6/PB/2025)
    |--------------------------------------------------------------------------
    | BSCBLU_TERMS=blu   -> istilah BLU (perspektif sektor publik: Pemangku Kepentingan, Proses Layanan Internal, ...)
    | BSCBLU_TERMS=umum  -> istilah umum bahasa Indonesia (Ekosistem, Jati Diri, ...)
    */
    'terms' => env('BSCBLU_TERMS', 'blu'),
    'brand_name' => env('BSCBLU_BRAND_NAME', 'Ruci BSC BLU'),
    'brand_tagline' => env('BSCBLU_BRAND_TAGLINE', 'Balanced Scorecard & Kontrak Kinerja Pemimpin BLU'),

    // Dibaca lewat config() supaya tetap berlaku setelah `php artisan optimize` (config cache) di produksi
    'notify_mail' => (bool) env('BSCBLU_NOTIFY_MAIL', false),
    'mfa_required' => (bool) env('BSCBLU_MFA_REQUIRED', false),
    'seed_demo' => (bool) env('BSCBLU_SEED_DEMO', false),
    'demo_admin_password' => env('BSCBLU_ADMIN_PASSWORD', 'Ruci@BSC2026'),
    'demo_enabled' => (bool) env('BSCBLU_DEMO_ENABLED', false),
    // Situs demo/promosi: tombol "Masuk sebagai Tamu" (hanya lihat) di halaman login
    'guest_login' => (bool) env('BSCBLU_GUEST_LOGIN', false),
    'guest_email' => env('BSCBLU_GUEST_EMAIL', 'tamu@demo.rucisoft.id'),
    // Ajakan Workshop di menu "Kenali Ruci BSC": tautan pendaftaran & kontak (mis. WhatsApp/email)
    'workshop_url' => env('BSCBLU_WORKSHOP_URL', 'https://rucisoft.id'),
    'workshop_contact' => env('BSCBLU_WORKSHOP_CONTACT'),
    'workshop_special_speaker' => env('BSCBLU_WORKSHOP_SPECIAL_SPEAKER'),
    'audit_enabled' => (bool) env('BSCBLU_AUDIT_ENABLED', true),

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
        'blu' => [
            'brahman' => 'Lingkungan Strategis', 'atman' => 'Jati Diri BLU', 'karma_phala' => 'Kinerja & Kontrak',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Pemangku Kepentingan',
            'proses' => 'Proses Layanan Internal', 'sdm' => 'Pembelajaran & Pertumbuhan',
            'app' => 'Ruci BSC BLU', 'method' => 'Manajemen Kinerja BLU',
        ],
        'umum' => [
            'brahman' => 'Ekosistem', 'atman' => 'Jati Diri', 'karma_phala' => 'Kinerja',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Kepuasan Pelanggan',
            'proses' => 'Proses Internal', 'sdm' => 'SDM & Pembelajaran',
            'app' => 'Ruci BSC', 'method' => 'Manajemen Strategi',
        ],
    ],
];
