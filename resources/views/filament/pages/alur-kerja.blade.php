@php use App\Support\Terms; @endphp
<x-filament-panels::page>
<div class="arah">
    <div class="arah-grid arah-grid-3">
        <div class="arah-card arah-span-2">
            <h3>Siklus bulanan — realisasi {{ $cycle['month_label'] }}</h3>
            <div class="arah-steps">
                @foreach ($cycle['steps'] as $s)
                    @php $pct = $s['total'] ? round($s['done'] / $s['total'] * 100) : 0; @endphp
                    <div class="arah-step">
                        <span>{{ $s['label'] }}</span>
                        <div style="display:flex; align-items:center; gap:.6rem">
                            <div class="arah-bar" style="flex:1"><span style="width:{{ $pct }}%; background:{{ $pct >= 100 ? '#2E7D4F' : ($pct >= 50 ? '#C88A12' : '#D32F2F') }}"></span></div>
                            <strong style="min-width:5rem; text-align:right">{{ $s['done'] }}/{{ $s['total'] }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="arah-muted" style="margin-top:.8rem">Dikembalikan: {{ $cycle['returned'] }} · Tindakan korektif lewat tenggat: {{ $cycle['overdue_actions'] }} · Aktivitas terlambat: {{ $cycle['overdue_activities'] }} · Tinjauan bulan ini: {{ $cycle['reviews_this_month'] }}</div>
        </div>
        <div class="arah-card">
            <h3>Alur persetujuan realisasi IK</h3>
            <ol style="margin:0; padding-left:1.1rem; font-size:.82rem; line-height:1.55">
                <li>Penanggung jawab IK mengisi nilai & bukti — status <span class="arah-badge arah-abu">draf</span></li>
                <li>IK yang wajib verifikasi Keuangan: Keuangan <span class="arah-badge arah-biru">verifikasi</span> atau mengembalikan dengan catatan</li>
                <li>Pimpinan <span class="arah-badge arah-hijau">setujui</span> — approved_by & approved_at terisi, baris terkunci</li>
                <li>Skor & status hijau/kuning/merah dihitung ulang, masuk Pusat Kendali & laporan</li>
                <li>Koreksi setelah terkunci hanya oleh Pimpinan dan tercatat di log audit</li>
            </ol>
        </div>
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>Ritme kerja Ruci BSC</h3>
        <div class="arah-kamasan"></div>
        <div class="arah-scroll">
        <table class="arah-table">
            <thead><tr><th>Ritme</th><th>Kegiatan di Ruci BSC</th><th>Pelaku</th><th>Batas waktu</th></tr></thead>
            <tbody>
            @foreach ($rhythm as $r)
                <tr><td><span class="arah-badge arah-biru">{{ $r['ritme'] }}</span></td><td>{{ $r['kegiatan'] }}</td><td>{{ $r['pelaku'] }}</td><td>{{ $r['batas'] }}</td></tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <div class="arah-grid arah-grid-5" style="margin-top:1rem">
        @foreach ([['brahman', 'Membaca lingkungan: PESTEL, industri, pasar, pesaing, regulasi, isu strategis, SWOT/TOWS'], ['atman', 'Visi, misi, nilai inti, kapabilitas organisasi'], ['karma_phala', 'Tema, sasaran, IK, target, realisasi, tinjauan kinerja'], ['punarbhawa', 'Inisiatif, anggaran, aktivitas, milestone, temuan, tindakan korektif'], ['moksa', 'Kematangan, ketergantungan strategis, pelembagaan']] as $i => [$k, $desc])
            <div class="arah-card">
                <div class="arah-muted">Fase {{ $i + 1 }}</div>
                <h3 style="margin-top:.2rem">{{ Terms::get($k) }}</h3>
                <div style="font-size:.8rem">{{ $desc }}</div>
            </div>
        @endforeach
    </div>
</div>
</x-filament-panels::page>
