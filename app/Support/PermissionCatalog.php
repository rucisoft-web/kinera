<?php

namespace App\Support;

/** Daftar izin (slug) Kinera: 25 izin inti + izin tambahan (termasuk import.manage). */
class PermissionCatalog
{
    public const PERMISSIONS = [
        // system
        'system.admin' => ['system', 'Administrasi sistem (instansi, pengguna, peran)'],
        'audit.view' => ['system', 'Melihat log audit'],
        'context.switch' => ['system', 'Memilih instansi & perioda'],
        'report.view' => ['system', 'Melihat laporan'],
        'report.export' => ['system', 'Mengunduh/ekspor laporan'],
        // brahman / atman
        'brahman.view' => ['brahman', 'Melihat Ekosistem'],
        'brahman.edit' => ['brahman', 'Mengubah Ekosistem'],
        'atman.view' => ['atman', 'Melihat Jati Diri'],
        'atman.edit' => ['atman', 'Mengubah Jati Diri'],
        // strategy
        'strategy.view' => ['strategy', 'Melihat tema, pilihan & sasaran strategis'],
        'strategy.edit' => ['strategy', 'Mengubah tema, pilihan & sasaran strategis'],
        'strategy.map' => ['strategy', 'Melihat peta strategi'],
        // kpi
        'kpi.view' => ['kpi', 'Melihat IK, target & realisasi'],
        'kpi.edit' => ['kpi', 'Mengubah IK & target'],
        'kpi.input_actual' => ['kpi', 'Mengisi realisasi IK (miliknya)'],
        'kpi.approve_actual' => ['kpi', 'Menyetujui realisasi IK'],
        'kpi.verify_finance' => ['kpi', 'Memverifikasi realisasi IK yang wajib verifikasi Keuangan (baru)'],
        // initiative
        'initiative.view' => ['initiative', 'Melihat inisiatif strategis'],
        'initiative.edit' => ['initiative', 'Mengubah inisiatif strategis'],
        'initiative.update_progress' => ['initiative', 'Memperbarui progres aktivitas & milestone (miliknya)'],
        'initiative.budget' => ['initiative', 'Mencatat realisasi anggaran inisiatif (baru)'],
        // review / finding
        'review.view' => ['review', 'Melihat tinjauan kinerja'],
        'review.edit' => ['review', 'Mengubah tinjauan kinerja'],
        'review.note' => ['review', 'Menambah catatan pengawasan pada tinjauan (baru)'],
        'finding.view' => ['finding', 'Melihat temuan'],
        'finding.create' => ['finding', 'Membuat temuan baru (baru)'],
        'finding.edit' => ['finding', 'Mengubah temuan & tindakan korektif (miliknya)'],
        'finding.close' => ['finding', 'Menutup temuan'],
        // maturity / dashboard
        'maturity.view' => ['maturity', 'Melihat Kesinambungan'],
        'maturity.assess' => ['maturity', 'Menilai kematangan & mengubah Kesinambungan'],
        'dashboard.view' => ['dashboard', 'Melihat Beranda & Pusat Kendali'],
        // pengelola
        'import.manage' => ['system', 'Mengupload workbook Excel untuk mengisi data instansi (baru)'],
        'data.manage_all' => ['system', 'Mengelola semua data tanpa batas "miliknya" (pengelola)'],
    ];
}
