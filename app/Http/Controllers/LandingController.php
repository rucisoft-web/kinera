<?php

namespace App\Http\Controllers;

use App\Support\DemoCatalog;
use App\Support\GuestMode;
use Illuminate\Support\Facades\Cache;

/** Halaman depan situs demo/promosi: kenapa perlu Kinera, data contoh tahun berjalan, dan panduan. */
class LandingController extends Controller
{
    public function __invoke()
    {
        if (! GuestMode::enabled()) {
            return redirect('/admin');
        }

        return view('landing', [
            'cases' => Cache::remember('arah.landing.cases', 600, fn () => DemoCatalog::installed()),
            'cycle' => config('bsc.cycle'),
            'workshopUrl' => \App\Filament\Pages\WorkshopKinera::registerUrl(),
            'whatsapp' => config('bsc.contact_whatsapp'),
            'email' => config('bsc.contact_email'),
        ]);
    }
}
