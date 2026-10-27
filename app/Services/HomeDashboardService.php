<?php

namespace App\Services;

use App\Models\CorrectiveAction;
use App\Models\InitiativeActivity;
use App\Models\InitiativeMilestone;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\User;
use App\Support\DhastramContext;

/** Daftar "tugas saya" untuk Beranda, sesuai peran dan kepemilikan. */
class HomeDashboardService
{
    public function __construct(protected KpiApprovalService $approval, protected DhastramContext $ctx) {}

    public function tasks(User $user): array
    {
        $month = $this->ctx->lastClosedMonth();
        $manageAll = $user->managesAllData();

        $missing = collect();
        if ($month > 0 && $user->hasPermission('kpi.input_actual')) {
            $kpis = Kpi::with('actuals')->where('is_active', true)
                ->when(! $manageAll, fn ($q) => $q->where('owner_id', $user->id))
                ->orderBy('code')->get();
            $missing = $kpis->filter(fn ($k) => $k->isDueIn($month) && ! $k->actuals->firstWhere('month', $month));
        }
        $returned = KpiActual::with('kpi')->where('status', 'dikembalikan')
            ->when(! $manageAll, fn ($q) => $q->whereHas('kpi', fn ($k) => $k->where('owner_id', $user->id)))->get();
        $toVerify = $user->hasPermission('kpi.verify_finance')
            ? KpiActual::with('kpi')->where('status', 'draf')->whereHas('kpi', fn ($q) => $q->where('is_financial', true))->orderBy('month')->get() : collect();
        $toApprove = $user->hasPermission('kpi.approve_actual')
            ? KpiActual::with('kpi')->get()->filter(fn ($a) => $this->approval->canApprove($user, $a))->sortBy('month') : collect();

        $activities = InitiativeActivity::with('initiative')->where('status', '!=', 'selesai')
            ->when(! $manageAll, fn ($q) => $q->where(fn ($w) => $w->where('owner_id', $user->id)->orWhereHas('initiative', fn ($i) => $i->where('owner_id', $user->id))))
            ->whereNotNull('due_date')->where('due_date', '<=', today()->addDays(14))->orderBy('due_date')->limit(15)->get();
        $milestones = InitiativeMilestone::with('initiative')->where('is_achieved', false)
            ->when(! $manageAll, fn ($q) => $q->where(fn ($w) => $w->where('owner_id', $user->id)->orWhereHas('initiative', fn ($i) => $i->where('owner_id', $user->id))))
            ->where('due_date', '<=', today()->addDays(30))->orderBy('due_date')->limit(10)->get();
        $actions = CorrectiveAction::with('finding')->where('status', '!=', 'selesai')
            ->when(! $manageAll, fn ($q) => $q->where('owner_id', $user->id))
            ->orderBy('due_date')->limit(15)->get();

        return compact('month', 'missing', 'returned', 'toVerify', 'toApprove', 'activities', 'milestones', 'actions');
    }
}
