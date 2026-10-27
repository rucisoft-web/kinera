<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Support\DemoCatalog;
use App\Support\BscContext;
use App\Support\GuestMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Masuk sebagai Tamu (hanya lihat) dengan satu klik dari halaman depan atau login situs demo.
 * ?perusahaan=KODE membuka satker BLU contoh tertentu pada tahun berjalan;
 * ?ke=kenali|panduan|implementasi|workshop langsung ke halaman panduan tersebut.
 */
class GuestLoginController extends Controller
{
    protected const PAGES = [
        'kenali' => \App\Filament\Pages\KenaliRuciBsc::class,
        'panduan' => \App\Filament\Pages\PanduanPengguna::class,
        'implementasi' => \App\Filament\Pages\PanduanImplementasi::class,
        'workshop' => \App\Filament\Pages\WorkshopRuciBsc::class,
    ];

    public function __invoke(Request $request)
    {
        abort_unless(GuestMode::enabled(), 404);

        if (Auth::check()) {
            Auth::logout();
        }
        Auth::login(GuestMode::user());
        $request->session()->regenerate();

        $context = app(BscContext::class);
        $code = strtoupper((string) $request->query('perusahaan', ''));
        $company = $context->withoutScope(fn () => $code !== ''
            ? Company::where('code', $code)->where('is_demo', true)->first()
            : null) ?? GuestMode::startCompany();

        if ($company && ($periodId = $context->withoutScope(fn () => DemoCatalog::startPeriodId($company->id)))) {
            $context->switch($company->id, $periodId);
        }

        $page = self::PAGES[(string) $request->query('ke')] ?? null;

        return $page ? redirect($page::getUrl()) : redirect('/admin');
    }
}
