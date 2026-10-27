@if (\App\Support\GuestMode::isGuest())
    <div class="arah-guest-banner">
        <strong>Mode Tamu — hanya lihat.</strong>
        Silakan menjelajah semua menu, atau mulai dari <a href="{{ \App\Filament\Pages\KenaliKinera::getUrl() }}" style="text-decoration:underline;font-weight:600">Kenali Kinera</a>. Ganti instansi contoh lewat kotak konteks di kiri atas.
    </div>
@endif
