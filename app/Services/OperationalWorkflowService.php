<?php

namespace App\Services;

use App\Models\CorrectiveAction;
use App\Models\InitiativeActivity;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\PerformanceReview;
use App\Support\BscContext;
use App\Support\Terms;

/** Panduan alur kerja: ritme harian/mingguan/bulanan dan status siklus bulan berjalan. */
class OperationalWorkflowService
{
    public function __construct(protected BscContext $ctx) {}

    public function rhythm(): array
    {
        $c = config('bsc.cycle');

        return [
            ['ritme' => 'Harian', 'kegiatan' => 'Cek Beranda dan Pusat Kendali; perbarui aktivitas inisiatif yang bergerak', 'pelaku' => 'Pimpinan, kepala unit', 'batas' => '—'],
            ['ritme' => 'Mingguan', 'kegiatan' => 'Perbarui progres aktivitas & milestone; isi realisasi IK yang dipantau mingguan', 'pelaku' => 'PIC inisiatif, penanggung jawab IK', 'batas' => 'Senin 10.00, sebelum weekly report'],
            ['ritme' => 'Mingguan', 'kegiatan' => 'Catat laporan mingguan pimpinan sebagai Tinjauan Kinerja; buat temuan dan tindakan korektif baru', 'pelaku' => 'Pimpinan / Koordinator Manajemen Strategi', 'batas' => 'Senin'],
            ['ritme' => 'Bulanan', 'kegiatan' => 'Input realisasi semua IK bulan lalu', 'pelaku' => 'Penanggung jawab IK', 'batas' => 'tanggal '.$c['input_day']],
            ['ritme' => 'Bulanan', 'kegiatan' => 'Verifikasi angka keuangan dengan laporan keuangan', 'pelaku' => 'Keuangan', 'batas' => 'tanggal '.$c['verify_day']],
            ['ritme' => 'Bulanan', 'kegiatan' => 'Setujui realisasi; IK terkunci', 'pelaku' => 'Pimpinan', 'batas' => 'tanggal '.$c['approve_day']],
            ['ritme' => 'Bulanan', 'kegiatan' => 'Rapat manajemen: tinjau sasaran merah/kuning, tutup temuan selesai, ekspor laporan', 'pelaku' => 'Pimpinan, semua kepala unit', 'batas' => 'tanggal '.$c['meeting_day']],
            ['ritme' => 'Bulanan / kuartalan', 'kegiatan' => 'Laporan ke Dewan Pengawas; catatan pengawasan dan temuan pengawas', 'pelaku' => 'Ketua Dewan Pengawas', 'batas' => 'setelah rapat manajemen'],
            ['ritme' => 'Kuartalan', 'kegiatan' => 'Tinjau portofolio inisiatif (kuadran Tinjau Ulang), perbarui isu Ekosistem dan risiko', 'pelaku' => 'Pimpinan, Koordinator Manajemen Strategi', 'batas' => 'akhir kuartal'],
            ['ritme' => 'Tahunan', 'kegiatan' => 'Penilaian kematangan; susun perioda baru: '.Terms::get('brahman').' → '.Terms::get('atman').' → sasaran, target, inisiatif', 'pelaku' => 'Pimpinan, semua kepala unit, pengawas', 'batas' => 'Nov–Des'],
        ];
    }

    /** Status siklus bulanan untuk bulan realisasi terakhir. */
    public function cycle(): array
    {
        $month = $this->ctx->lastClosedMonth();
        $kpiCount = Kpi::where('is_active', true)->count();
        $actuals = $month ? KpiActual::where('month', $month)->get() : collect();
        $financial = Kpi::where('is_active', true)->where('is_financial', true)->pluck('id');
        $finActuals = $actuals->whereIn('kpi_id', $financial);

        return [
            'month' => $month,
            'month_label' => $month ? Terms::months()[$month] : '-',
            'steps' => [
                ['label' => 'Realisasi terisi', 'done' => $actuals->count(), 'total' => $kpiCount],
                ['label' => 'IK yang wajib verifikasi Keuangan terverifikasi', 'done' => $finActuals->whereIn('status', ['terverifikasi', 'disetujui'])->count(), 'total' => $financial->count()],
                ['label' => 'Realisasi disetujui Pimpinan', 'done' => $actuals->where('status', 'disetujui')->count(), 'total' => $kpiCount],
            ],
            'returned' => $actuals->where('status', 'dikembalikan')->count(),
            'reviews_this_month' => PerformanceReview::whereYear('review_date', now()->year)->whereMonth('review_date', now()->month)->count(),
            'overdue_actions' => CorrectiveAction::where('status', '!=', 'selesai')->whereDate('due_date', '<', today())->count(),
            'overdue_activities' => InitiativeActivity::where('status', 'terlambat')->count(),
        ];
    }
}
