<?php

namespace App\Http\Middleware;

use App\Support\DhastramContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Menyiapkan konteks perusahaan & perioda; menolak akun nonaktif. */
class SetDhastramContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && ! $user->is_active) {
            Auth::logout();

            return redirect()->route('filament.admin.auth.login');
        }
        $ctx = app(DhastramContext::class);
        if ($user && $ctx->companyId() && $ctx->periodId()) {
            session(['dhastram.company_id' => $ctx->companyId(), 'dhastram.period_id' => $ctx->periodId()]);
        }

        return $next($request);
    }
}
