<x-filament-panels::page>
<div class="arah">
    <div class="arah-grid arah-grid-2">
        <div class="arah-card">
            <h3>1. Unduh template</h3>
            <p class="arah-muted" style="margin:.3rem 0 .8rem">
                Data diisi untuk <strong>{{ $company?->name ?? '-' }}</strong>, perioda <strong>{{ $period?->name ?? '-' }}</strong>.
                Ganti lewat <a href="{{ \App\Filament\Pages\PilihKonteks::getUrl() }}" style="text-decoration:underline">Pilih Perusahaan &amp; Perioda</a>.
            </p>
            <div style="display:flex; flex-wrap:wrap; gap:.5rem">
                <button type="button" class="arah-btn arah-btn-primary" wire:click="downloadTemplate">⤓ Template kosong</button>
                <button type="button" class="arah-btn" wire:click="downloadCurrent" @disabled(! $period)>⤓ Data perioda ini (untuk diubah)</button>
            </div>
            <p class="arah-muted" style="margin-top:.8rem">
                Sheet: Jati Diri, Nilai Inti, Tema, Sasaran, Relasi Sasaran, KPI, Target, Realisasi, Inisiatif. Petunjuk kolom ada di sheet pertama.
                Kode sasaran/KPI menjadi kunci, jadi upload ulang memperbarui data yang sama.
            </p>
        </div>

        <div class="arah-card">
            <h3>2. Upload &amp; periksa</h3>
            <div style="margin:.6rem 0">
                <input type="file" wire:model="file" accept=".xlsx" style="font-size:.85rem">
                <div wire:loading wire:target="file" class="arah-muted">Mengunggah…</div>
                @error('file') <div style="color:#D32F2F; font-size:.85rem; margin-top:.3rem">{{ $message }}</div> @enderror
            </div>
            @if ($canApprove)
                <label style="display:flex; gap:.5rem; align-items:flex-start; font-size:.85rem; margin-bottom:.8rem">
                    <input type="checkbox" wire:model="approveActuals" style="margin-top:.2rem">
                    <span>Setujui langsung realisasi yang diupload (untuk data historis yang sudah diverifikasi). Bila tidak dicentang, realisasi masuk sebagai draf.</span>
                </label>
            @endif
            <div style="display:flex; flex-wrap:wrap; gap:.5rem">
                <button type="button" class="arah-btn" wire:click="previewImport" wire:loading.attr="disabled">Pratinjau</button>
                <button type="button" class="arah-btn arah-btn-primary" wire:click="processImport" wire:loading.attr="disabled"
                    wire:confirm="Proses import ke {{ $company?->code }} · {{ $period?->name }}?">Proses Import</button>
                <span wire:loading wire:target="previewImport,processImport" class="arah-muted">Memproses…</span>
            </div>
        </div>
    </div>

    @if ($preview)
        <div class="arah-card" style="margin-top:1rem">
            <h3>
                {{ $preview['dry'] ? 'Hasil pratinjau (belum disimpan)' : 'Hasil import' }}
                <span class="arah-badge arah-hijau">{{ $preview['ok'] }} baris OK</span>
                @if ($preview['failed'])<span class="arah-badge arah-merah">{{ $preview['failed'] }} baris gagal</span>@endif
            </h3>
            <div class="arah-scroll">
                <table class="arah-table">
                    <thead><tr><th>Sheet</th><th class="num">Baru</th><th class="num">Diubah</th><th class="num">Tetap</th><th class="num">Gagal</th></tr></thead>
                    <tbody>
                    @forelse ($preview['summary'] as $sheet => $s)
                        <tr><td>{{ $sheet }}</td><td class="num">{{ $s['dibuat'] }}</td><td class="num">{{ $s['diubah'] }}</td><td class="num">{{ $s['sama'] }}</td>
                            <td class="num">@if ($s['gagal'])<span class="arah-badge arah-merah">{{ $s['gagal'] }}</span>@else 0 @endif</td></tr>
                    @empty
                        <tr><td colspan="5" class="arah-muted">Tidak ada baris data yang dibaca.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($preview['issues'])
                <h3 style="margin-top:1rem">Masalah per baris ({{ $preview['issues_total'] }})</h3>
                <div class="arah-scroll" style="max-height:24rem">
                    <table class="arah-table">
                        <thead><tr><th>Sheet</th><th class="num">Baris</th><th>Jenis</th><th>Keterangan</th></tr></thead>
                        <tbody>
                        @foreach ($preview['issues'] as $i)
                            <tr><td>{{ $i['sheet'] }}</td><td class="num">{{ $i['baris'] ?? '-' }}</td>
                                <td><span class="arah-badge {{ $i['level'] === 'error' ? 'arah-merah' : 'arah-kuning' }}">{{ $i['level'] }}</span></td>
                                <td>{{ $i['pesan'] }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <div class="arah-card" style="margin-top:1rem">
        <h3>Riwayat import {{ $company?->code }} · {{ $period?->name }}</h3>
        <div class="arah-scroll">
            <table class="arah-table">
                <thead><tr><th>Waktu</th><th>File</th><th>Oleh</th><th class="num">OK</th><th class="num">Gagal</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse ($history as $h)
                    <tr>
                        <td>{{ $h->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $h->file_name }}</td>
                        <td>{{ $h->user?->name ?? 'sistem' }}</td>
                        <td class="num">{{ $h->rows_ok }}</td>
                        <td class="num">{{ $h->rows_failed }}</td>
                        <td><span class="arah-badge {{ $h->rolled_back_at ? 'arah-abu' : 'arah-hijau' }}">{{ \App\Models\DataImport::STATUSES[$h->status] ?? $h->status }}</span></td>
                        <td>
                            @if ($h->canRollback())
                                <button type="button" class="arah-btn" wire:click="rollback({{ $h->id }})"
                                    wire:confirm="Batalkan import {{ $h->file_name }}? Data yang dibuat akan dihapus dan data yang diubah dikembalikan.">↶ Batalkan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="arah-muted">Belum ada import untuk perioda ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-filament-panels::page>
