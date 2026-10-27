<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/** Mencatat setiap buat/ubah/hapus ke audit_logs (jika audit_enabled). */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn ($m) => $m->writeAudit('dibuat', [], $m->auditAttributes($m->getAttributes())));
        static::updated(function ($m) {
            $changes = $m->auditAttributes($m->getChanges());
            unset($changes['updated_at']);
            if ($changes === []) {
                return;
            }
            $old = array_intersect_key($m->auditAttributes($m->getOriginal()), $changes);
            $m->writeAudit('diubah', $old, $changes);
        });
        static::deleted(fn ($m) => $m->writeAudit('dihapus', $m->auditAttributes($m->getOriginal()), []));
    }

    protected function auditAttributes(array $attrs): array
    {
        foreach (['password', 'remember_token', 'app_authentication_secret', 'app_authentication_recovery_codes'] as $k) {
            if (array_key_exists($k, $attrs)) {
                $attrs[$k] = '***';
            }
        }

        return $attrs;
    }

    public function writeAudit(string $event, array $old, array $new): void
    {
        if (! config('dhastram.audit_enabled') || app()->runningUnitTests() && ! Auth::check()) {
            return;
        }
        if (app()->runningInConsole() && ! Auth::check()) {
            return; // seeder & perintah konsol tidak dicatat
        }
        AuditLog::create([
            'user_id' => Auth::id(),
            'company_id' => $this->company_id ?? null,
            'event' => $event,
            'auditable_type' => static::class,
            'auditable_id' => $this->getKey(),
            'auditable_label' => method_exists($this, 'auditLabel') ? $this->auditLabel() : ($this->code ?? $this->title ?? $this->name ?? null),
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
            'url' => mb_substr((string) Request::fullUrl(), 0, 500),
            'ip_address' => Request::ip(),
            'user_agent' => mb_substr((string) Request::userAgent(), 0, 500),
            'created_at' => now(),
        ]);
    }
}
