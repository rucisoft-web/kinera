<x-filament-panels::page>
<div class="arah">
    <div class="arah-card">
        <h3>Susun Draf Rencana Kerja dari isian Kinera</h3>
        <p class="arah-muted">Sasaran, IKU, target, inisiatif, dan anggaran diambil dari Kinera. Lampirkan dokumen pendukung (misalnya RKAT, arahan pimpinan, hasil evaluasi tahun lalu), lalu {{ $ai ? 'Claude' : 'Kinera' }} menyusun narasi, rincian kegiatan, jadwal triwulan, dan risiko. Hasilnya bisa disimpan sebagai PDF atau diunduh sebagai Word untuk disunting.</p>
        <p style="margin-top:.5rem">
            @if ($ai)
                <span class="arah-badge arah-hijau">Claude aktif</span>
                <span class="arah-muted">Data isian dan lampiran dikirim ke Anthropic untuk diproses. Jangan melampirkan data pribadi yang tidak diperlukan.</span>
            @else
                <span class="arah-badge arah-abu">Mode contoh</span>
                <span class="arah-muted">Kunci API Anthropic belum dipasang (ANTHROPIC_API_KEY), jadi draf dirangkai dari isian Kinera dengan kalimat baku tanpa AI. Lampiran dicatat tetapi belum dibaca.</span>
            @endif
        </p>

        @if ($canGenerate)
        <form wire:submit="generate" style="display:grid; gap:.7rem; margin-top:1rem">
            <label>Tahun rencana
                <select wire:model="year" class="arah-btn" style="margin-left:.3rem">
                    @foreach ($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                </select>
            </label>
            <div>
                <label>Lampiran (PDF, DOCX, TXT, MD, CSV · maks. 5 file, 10 MB per file)</label><br>
                <input type="file" wire:model="files" multiple accept=".pdf,.docx,.txt,.md,.csv" style="font-size:.85rem; margin-top:.3rem">
                <div wire:loading wire:target="files" class="arah-muted">Mengunggah…</div>
                @foreach ($files as $i => $file)
                    <div class="arah-muted">📎 {{ $file->getClientOriginalName() }} <button type="button" wire:click="removeFile({{ $i }})" class="arah-btn" style="padding:0 .4rem">hapus</button></div>
                @endforeach
                @error('files') <div style="color:#b42318">{{ $message }}</div> @enderror
                @error('files.*') <div style="color:#b42318">{{ $message }}</div> @enderror
            </div>
            <label>Arahan tambahan (opsional)
                <textarea wire:model="instructions" rows="3" style="width:100%; margin-top:.3rem; border:1px solid #ccc; border-radius:6px; padding:.5rem" placeholder="Contoh: fokus pada akreditasi internasional dan efisiensi belanja operasional 10%."></textarea>
            </label>
            <div>
                <button type="submit" class="arah-btn arah-btn-primary" wire:loading.attr="disabled" wire:target="generate">✨ Susun Rencana Kerja</button>
                <span wire:loading wire:target="generate" class="arah-muted">Sedang menyusun{{ $ai ? ', biasanya 1–3 menit' : '' }}…</span>
            </div>
        </form>
        @else
            <p class="arah-muted" style="margin-top:.8rem">Penyusunan draf butuh izin ekspor laporan (report.export). Anda tetap dapat membuka draf yang sudah ada.</p>
        @endif
    </div>

    <div class="arah-card" style="margin-top:1rem">
        <h3>Draf tersimpan</h3>
        <table class="arah-table">
            <thead><tr><th>Dokumen</th><th>Cara</th><th>Disusun</th><th></th></tr></thead>
            <tbody>
            @forelse ($drafts as $d)
                <tr>
                    <td>{{ $d->title }}@if ($d->attachments)<div class="arah-muted">📎 {{ collect($d->attachments)->pluck('name')->implode(', ') }}</div>@endif</td>
                    <td>{{ $d->mode === 'ai' ? 'Claude' : 'Contoh' }}@if ($d->status === 'gagal')<div style="color:#b42318">Gagal: {{ $d->error }}</div>@endif</td>
                    <td class="arah-muted">{{ $d->created_at->locale('id')->translatedFormat('j M Y H:i') }}<br>{{ $d->creator?->name }}</td>
                    <td style="white-space:nowrap">
                        @if ($d->status === 'selesai')
                            <a class="arah-btn arah-btn-primary" style="color:#fff" href="{{ route('workplan.print', $d) }}" target="_blank">🖶 PDF</a>
                            <a class="arah-btn" href="{{ route('workplan.print', ['draft' => $d, 'format' => 'word']) }}">⬇ Word</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="arah-muted">Belum ada draf.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-filament-panels::page>
