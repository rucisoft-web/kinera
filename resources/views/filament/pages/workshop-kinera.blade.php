@php
    use App\Filament\Pages;

    // Sama dengan halaman 4 brosur (public/files/brosur-kinera-workshop-2-hari.pdf)
    $days = [
        ['Hari 1', 'Balanced Scorecard dan Peta Strategi instansi', [
            ['08.00', '08.30', 'Registrasi peserta', 'Pembagian kelompok dan akses demo Kinera', 'break'],
            ['08.30', '09.00', 'Pembukaan dan sambutan Ketua PSMPT UIN Sunan Kalijaga', 'Pusat Studi Manajemen Perguruan Tinggi membuka workshop dan memperkenalkan program kerja sama', 'open'],
            ['09.00', '10.15', 'Sesi 1 · Balanced Scorecard untuk sektor publik', 'Empat perspektif sektor publik dan hubungan sebab-akibat', 'talk'],
            ['10.15', '10.30', 'Rehat pagi', '', 'break'],
            ['10.30', '12.00', 'Sesi 2 · Anatomi scorecard: sasaran, ukuran, target, dan inisiatif', 'Ukuran hasil dan pendorong, 1–2 IKU per sasaran, dan inisiatif yang mendorongnya', 'talk'],
            ['12.00', '13.00', 'Ishoma', '', 'break'],
            ['13.00', '14.45', 'Sesi 3 · Diagnosis kinerja dan penyusunan sasaran strategis', 'Membaca dokumen strategi dan capaian tahun lalu · latihan kelompok', 'work'],
            ['14.45', '15.00', 'Rehat sore', '', 'break'],
            ['15.00', '16.30', 'Sesi 4 · Menyusun Peta Strategi dan Indikator Kinerja', 'Sasaran empat perspektif dan 2–4 IK per sasaran · latihan kelompok', 'work'],
        ]],
        ['Hari 2', 'Pengukuran kinerja dan implementasi dengan Kinera', [
            ['08.00', '10.15', 'Sesi 5 · Definisi operasional, target, dan trajektori triwulanan', 'Rumus, sumber data, metode pengujian, baseline, dan target · latihan kelompok', 'work'],
            ['10.15', '10.30', 'Rehat pagi', '', 'break'],
            ['10.30', '12.00', 'Sesi 6 · Simulasi penetapan dan perubahan target', 'Kesepakatan target pimpinan dan perubahannya di Kinera (termasuk Kontrak Kinerja BLU)', 'app'],
            ['12.00', '13.00', 'Ishoma', '', 'break'],
            ['13.00', '14.30', 'Sesi 7 · Praktik aplikasi Kinera', 'Alur realisasi, verifikasi, dan capaian kinerja', 'app'],
            ['14.30', '15.30', 'Sesi 8 · Inisiatif strategis, temuan, dan tindak lanjut', 'Simulasi rapat pimpinan bersama APIP dan pengawas', 'app'],
            ['15.30', '16.30', 'Rencana aksi 90 hari, penutupan, dan sertifikat', 'Presentasi hasil kelompok dan penyerahan sertifikat', 'open'],
        ]],
    ];
    $kinds = ['talk' => ['Paparan', '#1B2E5A'], 'work' => ['Latihan kelompok', '#3E6A8A'], 'app' => ['Praktik aplikasi', '#2E7D4F'], 'open' => ['Pleno', '#C2912F'], 'break' => ['Rehat', '#A3ADBD']];

    $sessions = [
        ['1', 'Hari 1', 'Balanced Scorecard untuk sektor publik', '75 menit', [
            'Mengapa strategi sulit dijalankan: empat hambatan pelaksanaan strategi (Kaplan & Norton).',
            'Balanced Scorecard sebagai sistem manajemen strategi, bukan sekadar daftar IK.',
            'Empat perspektif sektor publik dan hubungan sebab-akibat antarsasaran.',
        ], 'Diskusi: hambatan mana yang paling terasa di instansi Anda?', [['Kenali Kinera', Pages\KenaliKinera::getUrl()]]],
        ['2', 'Hari 1', 'Anatomi scorecard: sasaran, ukuran, target, dan inisiatif', '90 menit', [
            'Setiap sasaran punya satu baris scorecard: ukuran (IKU), target, dan inisiatif.',
            'Ukuran hasil (lagging) dan pendorong (leading); 1–2 IKU per sasaran.',
            'Membaca scorecard di Kinera: dari Peta Strategi ke IKU, target, dan inisiatif.',
        ], 'Kelompok melengkapi dua baris scorecard untuk program instansi sendiri.', [['Peta Strategi', Pages\PetaStrategi::getUrl()], ['Pusat Kendali', Pages\PusatKendali::getUrl()]]],
        ['3', 'Hari 1', 'Diagnosis kinerja dan sasaran strategis', '105 menit', [
            'Membaca dokumen strategi dan capaian tahun lalu untuk menemukan kesenjangan.',
            'SWOT dan strategi TOWS sampai isu strategis dan tema.',
            'Sasaran yang baik: kata kerja + hasil, satu gagasan per sasaran.',
        ], 'Kelompok merumuskan isu strategis dan 8–12 sasaran untuk instansi sendiri atau studi kasus UIN Sunan Kalijaga.', [['SWOT', url('/admin/swot-items')], ['TOWS', url('/admin/tows-strategies')]]],
        ['4', 'Hari 1', 'Peta Strategi dan Indikator Kinerja', '90 menit', [
            'Menyusun sasaran dari perspektif pembelajaran ke keuangan, lalu menarik panah sebab-akibat.',
            'Uji SMART; ukuran hasil (lagging) dan pendorong (leading); 2–4 IK per sasaran.',
            'Menyelaraskan IKU instansi dengan IK unit kerja di bawahnya (cascading).',
        ], 'Menyusun Peta Strategi di kertas plano, lalu galeri antarkelompok.', [['Peta Strategi', Pages\PetaStrategi::getUrl()], ['IK', url('/admin/kpis')]]],
        ['5', 'Hari 2', 'Definisi operasional, target, dan trajektori', '120 menit', [
            'Definisi operasional: rumus, satuan, sumber data, metode pengujian, dokumen pendukung.',
            'Baseline, target tahunan, dan trajektori triwulanan yang realistis.',
            'Pemilik IK, frekuensi pengukuran, dan agregasi tahun berjalan.',
        ], 'Melengkapi definisi operasional dan target triwulanan untuk IK hasil Hari 1.', [['Target IK', url('/admin/kpi-targets')]]],
        ['6', 'Hari 2', 'Simulasi penetapan dan perubahan target', '90 menit', [
            'Menyepakati scorecard pimpinan di Kinera dari Peta Strategi dan IK.',
            'Mengubah target dengan alasan yang sah; setiap versi tercatat.',
            'Mencetak dokumen kesepakatan target; instansi BLU memakai format Kontrak Kinerja.',
        ], 'Setiap kelompok menyepakati satu scorecard pimpinan lalu mengajukan satu perubahan target.', [['Kontrak Kinerja', url('/admin/performance-contracts')]]],
        ['7', 'Hari 2', 'Praktik aplikasi Kinera', '90 menit', [
            'Alur realisasi: unit mengisi, keuangan memverifikasi, pimpinan menyetujui.',
            'Capaian per IK dan per sasaran dihitung otomatis.',
            'Membaca Pusat Kendali dan indikator berisiko sebagai bahan rapat pimpinan.',
        ], 'Bermain peran per jabatan: mengisi, memverifikasi, dan menyetujui realisasi satu triwulan.', [['Alur Kerja', Pages\AlurKerja::getUrl()], ['Pusat Kendali', Pages\PusatKendali::getUrl()]]],
        ['8', 'Hari 2', 'Inisiatif, temuan, dan tindak lanjut', '60 menit', [
            'Inisiatif strategis: PIC, anggaran, aktivitas, dan milestone; portofolio nilai–upaya.',
            'Temuan APIP, auditor eksternal, dan pengawas beserta tindakan korektif dan tenggat.',
            'Rapat tinjauan yang hanya membahas yang merah dan kuning.',
        ], 'Simulasi rapat pimpinan bersama APIP dan pengawas, ditutup dengan daftar temuan dan PIC.', [['Portofolio Inisiatif', Pages\PortofolioInisiatif::getUrl()], ['Temuan', url('/admin/findings')]]],
    ];

    $gains = [
        ['Draf Peta Strategi', 'Sasaran empat perspektif dengan hubungan sebab-akibat, bisa memakai instansi Anda sendiri.'],
        ['Daftar IK dan definisi operasional', 'Rumus, sumber data, pemilik, baseline, dan trajektori triwulanan.'],
        ['Scorecard pimpinan dan targetnya', 'Disusun langsung di Kinera lengkap dengan riwayat perubahan; format Kontrak Kinerja PER-6/PB/2025 bagi instansi berpola BLU.'],
        ['Rencana aksi 90 hari', 'Langkah konkret memulai di instansi, ditambah materi PDF dan sertifikat.'],
        ['Akses Kinera 3 bulan', 'Akun di kinera.rucisoft.id selama 3 bulan untuk melanjutkan scorecard hasil workshop.'],
    ];
@endphp
<x-filament-panels::page>
<div class="arah kenali">
    <section class="kenali-workshop ws-hero">
        <div>
            <span class="kenali-eyebrow">Workshop 2 hari · bersama PSMPT UIN Sunan Kalijaga</span>
            <h2>Strategic Leadership Lab</h2>
            <p><b style="color:#E2C27A">Mengelola Renstra Perguruan Tinggi dengan Balanced Scorecard.</b></p>
            <p>Diselenggarakan bersama Pusat Studi Manajemen Perguruan Tinggi (PSMPT) UIN Sunan Kalijaga untuk pimpinan instansi pemerintah, termasuk perguruan tinggi negeri, APIP, serta tim perencanaan dan keuangan. Hari pertama membangun Balanced Scorecard dan Peta Strategi instansi; hari kedua melengkapi IKU dengan definisi operasional dan target, menyepakati scorecard pimpinan (termasuk Kontrak Kinerja bagi instansi berpola BLU), dan mempraktikkannya di Kinera dengan data demo UIN Sunan Kalijaga.</p>
            <div class="kenali-actions">
                <a href="{{ $registerUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop</a>
                <a href="#rundown" class="arah-btn kenali-btn-lg">Lihat rundown</a>
                <a href="{{ $brochureUrl }}" target="_blank" rel="noopener" class="arah-btn kenali-btn-lg">Unduh brosur (PDF)</a>
            </div>
            @if ($workshopContact)<div class="kenali-contact">Informasi &amp; pendaftaran: <b>{{ $workshopContact }}</b></div>@endif
        </div>
        <div class="ws-dates">
            <b>Pelaksanaan</b>
            <ul>
                <li><span>Waktu</span>2 hari · 08.00–16.30 WIB</li>
                <li><span>Tanggal</span>{{ $date ?: 'Diumumkan menyusul' }}</li>
                <li><span>Tempat</span>{{ $place ?: 'Diumumkan menyusul' }}</li>
                <li><span>Investasi</span>{{ $fee ?: 'Hubungi kami' }}</li>
            </ul>
            <small>WhatsApp {{ $whatsapp }} · {{ $email }}<br>Bisa juga diselenggarakan khusus (in-house) untuk satu instansi.</small>
        </div>
    </section>

    <section class="kenali-section">
        <h2>Yang dibawa pulang</h2>
        <p class="kenali-lead">Peserta pulang dengan hasil kerja yang bisa langsung dibahas di instansi.</p>
        <div class="arah-grid arah-grid-5">
            @foreach ($gains as $i => [$t, $d])
                <div class="arah-card ws-gain">
                    <div class="ws-gain-no">{{ $i + 1 }}</div>
                    <b>{{ $t }}</b>
                    <span>{{ $d }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="kenali-section" id="rundown">
        <h2>Rundown</h2>
        <div class="impl-legend" style="justify-content:flex-start; margin:0 0 .8rem">
            @foreach ($kinds as [$label, $color])<span><i style="background: {{ $color }}"></i>{{ $label }}</span>@endforeach
        </div>
        <div class="arah-grid arah-grid-2">
            @foreach ($days as [$day, $theme, $slots])
                <div class="arah-card ws-rundown">
                    <div class="ws-day"><span>{{ $day }}</span><b>{{ $theme }}</b></div>
                    @foreach ($slots as [$from, $to, $title, $desc, $kind])
                        <div class="ws-slot ws-{{ $kind }}">
                            <div class="ws-time">{{ $from }}<span>{{ $to }}</span></div>
                            <div class="ws-bar" style="background: {{ $kinds[$kind][1] }}"></div>
                            <div class="ws-what">
                                <b>{{ $title }}</b>
                                @if ($desc)<span>{{ $desc }}</span>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="kenali-section">
        <h2>Materi per sesi</h2>
        <p class="kenali-lead">Setiap sesi memadukan paparan singkat, latihan kelompok, dan contoh nyata di Kinera. Tautan di bawah setiap sesi membuka menu yang dipakai saat praktik.</p>
        <div class="arah-grid arah-grid-2">
            @foreach ($sessions as [$no, $day, $title, $dur, $points, $exercise, $links])
                <div class="arah-card ws-session">
                    <div class="ws-session-head"><span class="ws-session-no">{{ $no }}</span><div><b>{{ $title }}</b><span>{{ $day }} · {{ $dur }}</span></div></div>
                    <ul>@foreach ($points as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                    <div class="ws-exercise"><b>Latihan:</b> {{ $exercise }}</div>
                    <div class="ws-links">@foreach ($links as [$label, $href])<a href="{{ $href }}" class="arah-btn">{{ $label }} →</a>@endforeach</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="kenali-section">
        <h2>Persiapan peserta</h2>
        <div class="arah-grid arah-grid-3">
            <div class="arah-card"><h3>Siapa yang cocok ikut</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Pimpinan instansi pemerintah (K/L, pemerintah daerah, PTN/PTKIN, rumah sakit pemerintah), APIP, serta tim perencanaan dan keuangan. Paling efektif bila 3–5 orang dari instansi yang sama hadir bersama.</div></div>
            <div class="arah-card"><h3>Yang perlu dibawa</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Laptop dengan peramban. Bila ingin berlatih dengan instansi sendiri, bawa dokumen strategi dan daftar indikator kinerja instansi tahun berjalan.</div></div>
            <div class="arah-card"><h3>Setelah workshop</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Peserta menerima materi PDF, hasil kerja kelompok, template impor Excel, sertifikat, dan tawaran pendampingan implementasi (lihat <a href="{{ Pages\PanduanImplementasi::getUrl() }}" style="color:var(--k-taum); font-weight:600">Panduan Implementasi</a>).</div></div>
        </div>
    </section>
</div>
</x-filament-panels::page>
