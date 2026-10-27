@if (\App\Support\GuestMode::enabled())
    @php($cases = \Illuminate\Support\Facades\Cache::remember('arah.landing.cases', 600, fn () => \App\Support\DemoCatalog::installed()))
    <div class="arah-guest-login">
        <div class="arah-guest-or"><span>atau coba data demo</span></div>
        <form method="GET" action="{{ route('arah.guest') }}" class="arah-guest-pick">
            <label for="arah-guest-company">Pilih perusahaan contoh, tahun berjalan</label>
            <select id="arah-guest-company" name="perusahaan">
                @foreach ($cases as $c)
                    <option value="{{ $c['code'] }}">{{ $c['name'] }} ({{ $c['code'] }}) · {{ $c['year'] ?? '-' }}</option>
                @endforeach
            </select>
            <button type="submit" class="arah-guest-btn">Masuk sebagai Tamu (lihat-lihat)</button>
        </form>
        <div class="arah-guest-links">
            <a href="{{ url('/#kenapa') }}">Kenapa perlu Ruci BSC?</a>
            <a href="{{ url('/#panduan') }}">Panduan Ruci BSC</a>
            <a href="{{ url('/#data-contoh') }}">Semua data contoh</a>
        </div>
        <p class="arah-demo-note"><b>Data demo:</b> disusun untuk pembelajaran dan secara garis besar mengacu pada laporan tahunan masing-masing perusahaan. Target dan data tahun berjalan adalah simulasi, bukan data resmi perusahaan. Mode Tamu hanya melihat, data tidak bisa diubah.</p>
    </div>
@endif
