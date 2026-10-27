<?php

namespace App\Services;

use App\Models\KpiActual;
use App\Models\User;
use App\Notifications\ArahNotification;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

/**
 * Alur: penanggung jawab IK isi (draf) -> Keuangan verifikasi (IK yang wajib verifikasi Keuangan) -> Pimpinan setujui (terkunci).
 * Kembalikan: verifikator/penyetuju mengembalikan dengan catatan.
 */
class KpiApprovalService
{
    public function canInput(User $user, $kpi): bool
    {
        if (! $user->hasPermission('kpi.input_actual')) {
            return false;
        }

        return $user->managesAllData() || $kpi->isOwnedBy($user);
    }

    public function canEdit(User $user, KpiActual $a): bool
    {
        if ($a->isLocked()) {
            return $user->hasPermission('kpi.approve_actual'); // koreksi setelah terkunci hanya Pimpinan (tercatat di audit)
        }

        return $this->canInput($user, $a->kpi);
    }

    public function canVerify(User $user, KpiActual $a): bool
    {
        return $a->kpi?->is_financial && in_array($a->status, ['draf'], true) && $user->hasPermission('kpi.verify_finance');
    }

    public function canApprove(User $user, KpiActual $a): bool
    {
        if (! $user->hasPermission('kpi.approve_actual') || $a->status === 'disetujui' || $a->status === 'dikembalikan') {
            return false;
        }

        return ! $a->kpi?->is_financial || $a->status === 'terverifikasi';
    }

    public function canReturn(User $user, KpiActual $a): bool
    {
        if ($a->status === 'dikembalikan' || $a->status === 'disetujui') {
            return false;
        }

        return $user->hasPermission('kpi.approve_actual') || ($a->kpi?->is_financial && $user->hasPermission('kpi.verify_finance'));
    }

    public function verify(User $user, KpiActual $a, ?string $note = null): void
    {
        throw_unless($this->canVerify($user, $a), new RuntimeException('Tidak berwenang memverifikasi realisasi ini.'));
        $a->update(['status' => 'terverifikasi', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_note' => $note]);
        $this->notifyApprovers($a, 'Realisasi siap disetujui');
    }

    public function approve(User $user, KpiActual $a): void
    {
        throw_unless($this->canApprove($user, $a), new RuntimeException('Tidak berwenang menyetujui realisasi ini.'));
        $a->update(['status' => 'disetujui', 'approved_by' => $user->id, 'approved_at' => now()]);
    }

    public function return(User $user, KpiActual $a, string $note): void
    {
        throw_unless($this->canReturn($user, $a), new RuntimeException('Tidak berwenang mengembalikan realisasi ini.'));
        $a->update(['status' => 'dikembalikan', 'returned_by' => $user->id, 'returned_at' => now(), 'return_note' => $note]);
        if ($owner = $a->kpi?->owner) {
            $owner->notify(new ArahNotification(
                'Realisasi IK dikembalikan',
                ($a->kpi->short_code ?? '').' '.$a->kpi->name.' bulan '.$a->month.': '.$note,
                url('/admin/kpi-actuals/'.$a->id.'/edit'),
                'danger',
            ));
        }
    }

    /** Setelah pemilik memperbaiki realisasi yang dikembalikan, status kembali ke draf. */
    public function resubmit(User $user, KpiActual $a): void
    {
        $a->update(['status' => 'draf', 'submitted_by' => $user->id, 'submitted_at' => now()]);
    }

    public function notifyApprovers(KpiActual $a, string $title): void
    {
        $users = User::where('is_active', true)->get()->filter(fn (User $u) => ! $u->isSuperAdmin() && $u->hasPermission('kpi.approve_actual', $a->company_id));
        Notification::send($users, new ArahNotification(
            $title,
            ($a->kpi->short_code ?? '').' '.$a->kpi->name.' bulan '.$a->month,
            url('/admin/kpi-actuals/'.$a->id),
            'info',
        ));
    }

    public function notifyVerifiers(KpiActual $a): void
    {
        $users = User::where('is_active', true)->get()->filter(fn (User $u) => ! $u->isSuperAdmin() && $u->hasPermission('kpi.verify_finance', $a->company_id));
        Notification::send($users, new ArahNotification(
            'Permintaan verifikasi realisasi keuangan',
            ($a->kpi->short_code ?? '').' '.$a->kpi->name.' bulan '.$a->month,
            url('/admin/kpi-actuals/'.$a->id),
            'warning',
        ));
    }
}
