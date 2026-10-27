<?php

use App\Http\Controllers\ReportExportController;
use Illuminate\Support\Facades\Route;

// Halaman depan situs demo (RUCI_GUEST_LOGIN=true); selain itu langsung ke panel
Route::middleware('web')->get('/', \App\Http\Controllers\LandingController::class)->name('arah.landing');

Route::middleware(['web', 'auth', \App\Http\Middleware\SetBscContext::class])
    ->get('/admin/laporan/ekspor/{report}', ReportExportController::class)
    ->name('arah.report.export');

// Edisi BLU: cetak Kontrak Kinerja & lampiran PER-6/PB/2025
Route::middleware(['web', 'auth', \App\Http\Middleware\SetBscContext::class])
    ->get('/admin/kontrak-kinerja/cetak/{doc}/{contract}', \App\Http\Controllers\BluPrintController::class)
    ->name('blu.print');

// Situs demo: masuk sebagai Tamu (hanya lihat), aktif bila RUCI_GUEST_LOGIN=true
Route::middleware(['web', 'throttle:20,1'])
    ->get('/masuk-tamu', \App\Http\Controllers\GuestLoginController::class)
    ->name('arah.guest');

// Tautan yang bisa dibagikan ke calon peserta Workshop: langsung ke menu "Kenali Ruci BSC"
Route::middleware('web')->get('/kenali', function () {
    $page = \App\Filament\Pages\KenaliRuciBsc::getUrl();
    if (auth()->check()) {
        return redirect($page);
    }
    if (\App\Support\GuestMode::enabled()) {
        return redirect()->route('arah.guest', ['ke' => 'kenali']);
    }
    session()->put('url.intended', $page);

    return redirect()->route('filament.admin.auth.login');
})->name('arah.kenali');
