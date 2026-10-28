<x-filament-panels::page>
<div class="arah">
    <div class="arah-card">
        <h3>Susun Draf Rencana Strategis lima tahunan dari isian Kinera</h3>
        <p class="arah-muted">Kinera merangkai isian Lingkungan Strategis, Jati Diri, strategi, sasaran, IKU, dan inisiatif menjadi dokumen Renstra (Bab I–VI dan lampiran). Target tahun berikutnya dan pagu indikatif diusulkan otomatis dari tren, lalu ditelaah dan ditetapkan pimpinan.</p>
        <div style="display:flex; flex-wrap:wrap; gap:.6rem; align-items:center; margin-top:.8rem">
            <label>Periode Renstra
                <select wire:model.live="mulai" class="arah-btn" style="margin-left:.3rem">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($y === $start)>{{ $y }}–{{ $y + 4 }}</option>
                    @endforeach
                </select>
            </label>
            <a class="arah-btn arah-btn-primary" href="{{ $printUrl }}" target="_blank">🖶 Buka &amp; simpan PDF</a>
            <a class="arah-btn" href="{{ $wordUrl }}">⬇ Unduh Word</a>
        </div>
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>Kelengkapan bahan</h3>
        <table class="arah-table">
            <tbody>
            @foreach ($checks as [$label, $ok])
                <tr><td style="width:2rem">{{ $ok ? '✅' : '⚠️' }}</td><td>{{ $label }}</td><td class="arah-muted">{{ $ok ? 'siap' : 'lengkapi agar bab terkait tidak kosong' }}</td></tr>
            @endforeach
            </tbody>
        </table>
        <p class="arah-muted" style="margin-top:.6rem">Isi draf saat ini: {{ $d['objectives']->count() }} sasaran, {{ $d['kpiRows']->count() }} IKU, {{ $d['initiatives']->count() }} program, pagu {{ $d['periodYear'] }} {{ \App\Support\Fmt::rp($d['funding']->sum('base')) }}.</p>
    </div>
</div>
</x-filament-panels::page>
