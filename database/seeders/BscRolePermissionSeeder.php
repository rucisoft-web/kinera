<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Support\PermissionCatalog;
use Illuminate\Database\Seeder;

/** Izin & peran sistem Kinera + peran per jabatan (aman dijalankan berulang). */
class BscRolePermissionSeeder extends Seeder
{
    public const PERMISSIONS = PermissionCatalog::PERMISSIONS;

    public function run(): void
    {
        foreach (self::PERMISSIONS as $slug => [$group, $name]) {
            Permission::updateOrCreate(['slug' => $slug], ['group' => $group, 'name' => $name]);
        }
        $all = array_keys(self::PERMISSIONS);
        $views = array_values(array_filter($all, fn ($p) => str_ends_with($p, '.view')));
        $except = fn (array $ex) => array_values(array_diff($all, $ex));

        $roles = [
            // ---- peran sistem bawaan Kinera
            'super-admin' => ['Super Admin', 'Konsultan / vendor Kinera — semua izin', $all],
            'company-admin' => ['Admin Satker', 'Admin satker BLU klien: semua izin di satkernya, termasuk pengguna & penugasan peran', $all],
            'strategy-office' => ['Strategy Office', 'Koordinator Manajemen Strategi: semua kecuali system.admin, kpi.approve_actual dan kpi.verify_finance',
                $except(['system.admin', 'kpi.approve_actual', 'kpi.verify_finance'])],
            'management' => ['Management', 'Dasbor, laporan + ekspor, lihat strategi/peta/KPI/inisiatif/tinjauan/temuan/kematangan',
                ['dashboard.view', 'report.view', 'report.export', 'strategy.view', 'strategy.map', 'kpi.view', 'initiative.view', 'review.view', 'finding.view', 'maturity.view', 'context.switch']],
            'kpi-owner' => ['Penanggung jawab IK', 'Mengisi realisasi Indikator Kinerja miliknya', ['dashboard.view', 'kpi.view', 'kpi.input_actual', 'context.switch']],
            'initiative-pic' => ['Initiative PIC', 'Memperbarui progres inisiatif miliknya (+ melihat temuan)', ['dashboard.view', 'initiative.view', 'initiative.update_progress', 'finding.view', 'context.switch']],
            'reviewer' => ['Reviewer', 'Tinjauan kinerja & temuan', ['dashboard.view', 'review.view', 'review.edit', 'finding.view', 'maturity.view', 'context.switch']],
            'viewer' => ['Viewer', 'Baca-saja', array_merge($views, ['strategy.map', 'context.switch'])],
            'tamu' => ['Tamu (demo)', 'Pengunjung situs demo: hanya melihat, tanpa log audit & ekspor', array_merge(array_diff($views, ['audit.view']), ['strategy.map', 'context.switch'])],
            // ---- peran per jabatan (dipakai di setiap satker BLU)
            'admin-sistem' => ['Admin Sistem', 'IT / EDP: akun pengguna, peran, reset kata sandi',
                array_merge(['system.admin', 'audit.view', 'dashboard.view', 'report.view', 'context.switch', 'strategy.map', 'initiative.update_progress'], $views)],
            'pengawas' => ['Pengawas', 'Dewan Pengawas BLU: baca semua, catatan pengawasan & membuat temuan',
                array_merge($views, ['strategy.map', 'report.view', 'report.export', 'audit.view', 'review.note', 'finding.create', 'context.switch'])],
            'pimpinan' => ['Pimpinan', 'Direktur / General Manager: semua kecuali system.admin; menyetujui realisasi & menutup temuan', $except(['system.admin'])],
            'keuangan' => ['Keuangan', 'Kepala Keuangan / Chief Accountant: KPI keuangan, verifikasi, realisasi anggaran',
                ['dashboard.view', 'report.view', 'report.export', 'strategy.view', 'strategy.map', 'kpi.view', 'kpi.input_actual', 'kpi.verify_finance',
                    'initiative.view', 'initiative.budget', 'initiative.update_progress', 'review.view', 'finding.view', 'finding.edit', 'context.switch']],
            'kepala-unit' => ['Kepala Unit', 'Kepala departemen/divisi: input realisasi KPI & progres inisiatif miliknya, tindakan korektif miliknya',
                ['dashboard.view', 'strategy.view', 'strategy.map', 'brahman.view', 'atman.view', 'kpi.view', 'kpi.input_actual', 'initiative.view',
                    'initiative.update_progress', 'review.view', 'finding.view', 'finding.edit', 'maturity.view', 'report.view', 'context.switch']],
            'editor-ekosistem' => ['Editor Ekosistem', 'Tambahan: mengubah data Ekosistem (pasar, pesaing, isu)', ['brahman.view', 'brahman.edit']],
            'editor-jatidiri' => ['Editor Jati Diri', 'Tambahan: mengubah Jati Diri (kapabilitas, nilai inti)', ['atman.view', 'atman.edit']],
            'pelaksana' => ['Pelaksana', 'Supervisor/staf: input data atas nama unit',
                ['dashboard.view', 'kpi.view', 'kpi.input_actual', 'initiative.view', 'initiative.update_progress', 'context.switch']],
        ];
        foreach ($roles as $slug => [$name, $desc, $perms]) {
            $role = Role::updateOrCreate(['slug' => $slug], ['name' => $name, 'description' => $desc, 'is_system' => true]);
            $role->permissions()->sync(Permission::whereIn('slug', $perms)->pluck('id'));
        }
    }
}
