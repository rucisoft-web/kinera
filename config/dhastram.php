<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ruci BSC Professional (edisi DHASTRAM multi-perusahaan)
    |--------------------------------------------------------------------------
    | DHASTRAM_TERMS=umum  -> istilah umum bahasa Indonesia (Ekosistem, Jati Diri, ...)
    | DHASTRAM_TERMS=dharma -> istilah asli DHASTRAM (Brahman, Atman, ...)
    */
    'terms' => env('DHASTRAM_TERMS', 'umum'),
    'brand_name' => env('DHASTRAM_BRAND_NAME', 'Ruci BSC Professional'),
    'brand_tagline' => env('DHASTRAM_BRAND_TAGLINE', 'Balanced Scorecard Multi-Perusahaan'),

    // Dibaca lewat config() supaya tetap berlaku setelah `php artisan optimize` (config cache) di produksi
    'notify_mail' => (bool) env('ARAH_NOTIFY_MAIL', false),
    'mfa_required' => (bool) env('ARAH_MFA_REQUIRED', false),
    'seed_demo' => (bool) env('RUCI_SEED_DEMO', false),
    'demo_admin_password' => env('RUCI_ADMIN_PASSWORD', 'Ruci@BSC2026'),
    'demo_enabled' => (bool) env('DHASTRAM_DEMO_ENABLED', false),
    // Situs demo/promosi: tombol "Masuk sebagai Tamu" (hanya lihat) di halaman login
    'guest_login' => (bool) env('RUCI_GUEST_LOGIN', false),
    'guest_email' => env('RUCI_GUEST_EMAIL', 'tamu@demo.rucisoft.id'),
    // Ajakan Workshop di menu "Kenali Ruci BSC": tautan pendaftaran & kontak (mis. WhatsApp/email)
    'workshop_url' => env('RUCI_WORKSHOP_URL', 'https://rucisoft.id'),
    'workshop_contact' => env('RUCI_WORKSHOP_CONTACT'),
    'workshop_special_speaker' => env('RUCI_WORKSHOP_SPECIAL_SPEAKER'),
    'audit_enabled' => (bool) env('DHASTRAM_AUDIT_ENABLED', true),

    // Ambang status KPI (skor dalam persen)
    'score_green' => 100,
    'score_yellow' => 80,
    'score_cap' => 120,

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
        'umum' => [
            'brahman' => 'Ekosistem', 'atman' => 'Jati Diri', 'karma_phala' => 'Kinerja',
            'punarbhawa' => 'Perbaikan', 'moksa' => 'Kesinambungan',
            'keuangan' => 'Keuangan', 'pelanggan' => 'Kepuasan Pelanggan',
            'proses' => 'Proses Internal', 'sdm' => 'SDM & Pembelajaran',
            'app' => 'Ruci BSC', 'method' => 'Manajemen Strategi',
        ],
        'dharma' => [
            'brahman' => 'Brahman', 'atman' => 'Atman', 'karma_phala' => 'Karma Phala',
            'punarbhawa' => 'Punarbhawa', 'moksa' => 'Moksa',
            'keuangan' => 'Artha', 'pelanggan' => 'Kama',
            'proses' => 'Dharma', 'sdm' => 'Swadharma',
            'app' => 'DHASTRAM', 'method' => 'DSM',
        ],
    ],
];
