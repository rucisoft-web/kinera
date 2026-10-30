@php
    use App\Models\Kpi;
    use App\Support\Fmt;
    $ind = collect($contract->indicators ?? []);
    $changes = ['baru' => ['IK baru', 'arah-biru'], 'diubah' => ['Diubah', 'arah-kuning'], 'target_turun' => ['Target turun · maks 100%', 'arah-merah']];
@endphp
<div class="arah">
    @if ($ind->isEmpty())
        <div class="arah-empty">Belum ada Indikator Kinerja. Isi menu Indikator Kinerja lalu klik “Ambil target terbaru”.</div>
    @else
        <div class="arah-muted" style="margin-bottom:.5rem">
            {{ $ind->count() }} Indikator Kinerja: {{ $ind->where('aspect', 'layanan')->count() }} aspek layanan, {{ $ind->where('aspect', 'keuangan')->count() }} aspek keuangan{{ $ind->where('aspect', 'penugasan')->count() ? ', '.$ind->where('aspect', 'penugasan')->count().' kebijakan/penugasan' : '' }}.
            Target trajektori kumulatif s.d. akhir triwulan (Lampiran I, petunjuk nomor 14).
        </div>
        <div class="arah-scroll">
        <table class="arah-table">
            <thead><tr><th>No</th><th>Indikator Kinerja</th><th>Aspek</th><th>Satuan</th><th class="num">TW I</th><th class="num">TW II</th><th class="num">TW III</th><th class="num">TW IV</th><th class="num">Maks.</th>@if ($contract->version > 0)<th>Perubahan</th>@endif</tr></thead>
            <tbody>
            @foreach ($ind as $n => $i)
                <tr>
                    <td>{{ $n + 1 }}</td>
                    <td style="min-width:18rem"><strong>{{ $i['code'] }}</strong> {{ $i['name'] }}
                        @if ($i['mandatory'] ?? false)<span class="arah-badge arah-biru" title="Wajib dicapai (Pasal 14 ayat 4 huruf b)">wajib</span>@endif
                        <br><span class="arah-muted">{{ ($i['direction'] ?? 'higher') === 'lower' ? '↓ makin kecil makin baik' : '↑ makin besar makin baik' }} · trajektori {{ strtolower(Kpi::FREQUENCIES[$i['frequency']] ?? $i['frequency']) }}</span></td>
                    <td>{{ Kpi::ASPECTS[$i['aspect']] ?? $i['aspect'] }}</td>
                    <td>{{ $i['unit'] }}</td>
                    @foreach ([3, 6, 9, 12] as $q)
                        <td class="num">{{ Fmt::num($i['targets'][$q] ?? null, $i['decimals'] ?? 0) }}</td>
                    @endforeach
                    <td class="num">{{ Fmt::num($i['max'] ?? null, 0) }}%</td>
                    @if ($contract->version > 0)
                        <td>@if ($c = $changes[$i['change'] ?? ''] ?? null)<span class="arah-badge {{ $c[1] }}">{{ $c[0] }}</span>@if (isset($i['previous_annual']) && $i['previous_annual'] !== null)<br><span class="arah-muted">semula {{ Fmt::num($i['previous_annual'], $i['decimals'] ?? 0) }}</span>@endif @else <span class="arah-muted">tetap</span>@endif</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <details style="margin-top:1rem">
            <summary style="cursor:pointer; font-weight:600">Definisi Operasional Indikator Kinerja ({{ $ind->filter(fn ($i) => filled($i['definition'] ?? null))->count() }} dari {{ $ind->count() }} terisi)</summary>
            @foreach ($ind as $i)
                <div class="arah-card" style="margin-top:.6rem">
                    <strong>{{ $i['code'] }} · {{ $i['name'] }}</strong> <span class="arah-muted">({{ Kpi::ASPECTS[$i['aspect']] ?? $i['aspect'] }}; disusun bersama {{ Kpi::DO_PARTNERS[$i['aspect']] ?? '-' }})</span>
                    <dl class="impl-kv" style="margin-top:.4rem">
                        <dt>Definisi/batasan</dt><dd>{{ $i['definition'] ?: '—' }}</dd>
                        <dt>Rumus</dt><dd>{{ $i['formula'] ?: '—' }}</dd>
                        <dt>Sumber data</dt><dd>{{ $i['source'] ?: '—' }}</dd>
                        <dt>Pengambilan data capaian</dt><dd>{{ $i['collection'] ?: '—' }}</dd>
                        <dt>Metode pengujian</dt><dd>{{ $i['test'] ?: '—' }}</dd>
                        <dt>Dokumen pendukung</dt><dd>{{ $i['documents'] ?: '—' }}</dd>
                    </dl>
                </div>
            @endforeach
        </details>
    @endif
</div>
