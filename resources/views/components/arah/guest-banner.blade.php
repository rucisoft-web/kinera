@if (\App\Support\GuestMode::isGuest())
    <div class="arah-guest-banner">
        <strong>Mode Tamu — hanya lihat.</strong>
        Silakan menjelajah semua menu, atau mulai dari <a href="{{ \App\Filament\Pages\KenaliRuciBsc::getUrl() }}" style="text-decoration:underline;font-weight:600">Kenali Ruci BSC</a>. Ganti perusahaan contoh lewat kotak konteks di kiri atas.
    </div>
@endif
