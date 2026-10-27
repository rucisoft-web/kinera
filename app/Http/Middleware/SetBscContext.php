<?php

namespace App\Http\Middleware;

use App\Support\BscContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Menyiapkan konteks satker BLU & perioda; menolak akun nonaktif. */
class SetBscContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && ! $user->is_active) {
            Auth::logout();

            return redirect()->route('filament.admin.auth.login');
        }
        $ctx = app(BscContext::class);
        if ($user && $ctx->companyId() && $ctx->periodId()) {
            session(['bsc.company_id' => $ctx->companyId(), 'bsc.period_id' => $ctx->periodId()]);
        }

        return $next($request);
    }
}
