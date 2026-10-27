<?php

namespace App\Console\Commands;

use App\Models\AnalysisPeriod;
use App\Models\CorrectiveAction;
use App\Models\InitiativeMilestone;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\User;
use App\Notifications\ArahNotification;
use App\Support\DhastramContext;
use App\Support\Terms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

/**
 * Notifikasi terjadwal (harian 07.30 WIB):
 * - tanggal 3: pengingat input realisasi bulan lalu ke pemilik KPI yang belum mengisi
 * - tanggal 6: permintaan verifikasi ke Keuangan; tanggal 8: permintaan persetujuan ke Pimpinan
 * - milestone jatuh tempo 7 hari lagi ke PIC
 * - tindakan korektif lewat tenggat ke PIC (setiap Senin)
 */
class SendReminders extends Command
{
    protected $signature = 'rucibsc:reminders {--date= : Simulasikan tanggal (Y-m-d)}';

    protected $description = 'Kirim pengingat siklus Ruci BSC (realisasi, verifikasi, persetujuan, milestone, tindakan korektif)';

    public function handle(DhastramContext $ctx): int
    {
        $today = $this->option('date') ? \Carbon\Carbon::parse($this->option('date')) : today();
        $cycle = config('dhastram.cycle');
        $sent = 0;
        foreach (AnalysisPeriod::where('status', 'berjalan')->get() as $period) {
            $ctx->set($period->company_id, $period->id);
            $month = $today->year > $period->year ? 12 : $today->month - 1;
            if ($month < 1) {
                continue;
            }
            $monthName = Terms::months()[$month];

            if ($today->day === $cycle['reminder_day']) {
                $kpis = Kpi::with(['owner', 'actuals' => fn ($q) => $q->where('month', $month)])->where('is_active', true)->get()->filter(fn ($k) => $k->isDueIn($month) && $k->actuals->isEmpty() && $k->owner);
                foreach ($kpis->groupBy('owner_id') as $list) {
                    $list->first()->owner->notify(new ArahNotification(
                        'Pengingat input realisasi '.$monthName,
                        $list->count().' KPI belum diisi: '.$list->pluck('short_code')->join(', ').'. Batas tanggal '.$cycle['input_day'].'.',
                        url('/admin'), 'warning'));
                    $sent++;
                }
            }
            if ($today->day === $cycle['input_day'] + 1) {
                $n = KpiActual::where('month', $month)->where('status', 'draf')->whereHas('kpi', fn ($q) => $q->where('is_financial', true))->count();
                if ($n) {
                    $sent += $this->toPermission('kpi.verify_finance', $period->company_id, 'Verifikasi realisasi keuangan '.$monthName, $n.' realisasi KPI keuangan menunggu verifikasi (batas tanggal '.$cycle['verify_day'].').');
                }
            }
            if ($today->day === $cycle['approve_day']) {
                $n = KpiActual::where('month', $month)->whereIn('status', ['draf', 'terverifikasi'])->count();
                if ($n) {
                    $sent += $this->toPermission('kpi.approve_actual', $period->company_id, 'Persetujuan realisasi '.$monthName, $n.' realisasi menunggu persetujuan Pimpinan hari ini.');
                }
            }
            foreach (InitiativeMilestone::with(['initiative.owner', 'owner'])->where('is_achieved', false)->whereDate('due_date', $today->copy()->addDays(7))->get() as $m) {
                if ($to = $m->owner ?? $m->initiative?->owner) {
                    $to->notify(new ArahNotification('Milestone jatuh tempo 7 hari lagi', $m->name.' ('.$m->initiative?->short_code.') — '.$m->due_date->format('d/m/Y'), url('/admin/initiative-milestones/'.$m->id.'/edit'), 'warning'));
                    $sent++;
                }
            }
            if ($today->isMonday()) {
                foreach (CorrectiveAction::with(['owner', 'finding'])->where('status', '!=', 'selesai')->whereDate('due_date', '<', $today)->get()->groupBy('owner_id') as $list) {
                    if ($to = $list->first()->owner) {
                        $to->notify(new ArahNotification('Tindakan korektif lewat tenggat', $list->count().' tindakan: '.$list->pluck('title')->take(3)->join('; '), url('/admin/corrective-actions?tableFilters[mine][isActive]=true'), 'danger'));
                        $sent++;
                    }
                }
            }
        }
        $ctx->clear();
        $this->info($sent.' notifikasi dikirim untuk '.$today->format('d/m/Y').'.');

        return self::SUCCESS;
    }

    protected function toPermission(string $permission, int $companyId, string $title, string $body): int
    {
        $users = User::where('is_active', true)->get()->filter(fn ($u) => ! $u->isSuperAdmin() && $u->hasPermission($permission, $companyId));
        Notification::send($users, new ArahNotification($title, $body, url('/admin/kpi-actuals'), 'info'));

        return $users->count();
    }
}
