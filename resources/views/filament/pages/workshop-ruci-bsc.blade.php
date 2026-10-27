@php
    use App\Filament\Pages;

    $speaker = config('dhastram.workshop_special_speaker') ?: 'narasumber tamu, praktisi tata kelola dan pencegahan korupsi';

    $rundown = [
        ['07.30', '08.00', 'Registrasi & kopi pagi', 'Pembagian kelompok dan akses demo Ruci BSC', 'break'],
        ['08.00', '08.20', 'Pembukaan', 'Perkenalan dan harapan peserta', 'open'],
        ['08.20', '09.20', 'Sesi 1 · Mengapa strategi gagal dijalankan', 'Konsep Balanced Scorecard dan lima fase Ruci BSC', 'talk'],
        ['09.20', '09.35', 'Rehat pagi', '', 'break'],
        ['09.35', '10.45', 'Sesi 2 · Membaca lingkungan & jati diri', 'PESTEL, SWOT, TOWS, tema strategis · latihan kelompok', 'work'],
        ['10.45', '12.00', 'Sesi 3 · Menyusun Peta Strategi', 'Sasaran 4 perspektif dan hubungan sebab-akibat · latihan kelompok', 'work'],
        ['12.00', '13.00', 'Ishoma', '', 'break'],
        ['13.00', '14.15', 'Sesi 4 · IK dan target yang baik', 'SMART, kamus Indikator Kinerja, target bulanan, skor · latihan kelompok', 'work'],
        ['14.15', '15.00', 'Sesi 5 · Dari strategi ke aksi', 'Inisiatif, anggaran, portofolio nilai–upaya · latihan', 'work'],
        ['15.00', '15.15', 'Rehat sore', '', 'break'],
        ['15.15', '16.00', 'Sesi 6 · Praktik Ruci BSC', 'Pusat Kendali dan simulasi rapat bulanan di laptop', 'app'],
        ['16.00', '16.30', 'Presentasi & penutupan', 'Hasil kelompok, rencana aksi 30 hari, sertifikat', 'open'],
    ];
    $kinds = ['talk' => ['Paparan', '#1F4E8C'], 'work' => ['Latihan kelompok', '#2A8C9E'], 'app' => ['Praktik aplikasi', '#2E7D4F'], 'open' => ['Pleno', '#C88A12'], 'break' => ['Rehat', '#8A99AE']];

    $sessions = [
        ['1', 'Mengapa strategi gagal dijalankan', '60 menit', [
            'Empat hambatan pelaksanaan strategi (Kaplan & Norton): visi, manusia, sumber daya, manajemen.',
            'Balanced Scorecard sebagai sistem manajemen strategi, bukan sekadar daftar IK.',
            'Empat perspektif: Keuangan, Kepuasan Pelanggan, Proses Internal, SDM & Pembelajaran.',
            'Lima fase Ruci BSC: Ekosistem, Jati Diri, Kinerja, Perbaikan, Kesinambungan.',
        ], 'Diskusi: hambatan mana yang paling terasa di satker BLU Anda?', [['Kenali Ruci BSC', Pages\KenaliRuciBsc::getUrl()]]],
        ['2', 'Membaca lingkungan & jati diri', '70 menit', [
            'PESTEL, kekuatan industri, segmen pasar, pesaing, dan regulasi.',
            'SWOT berbobot dan strategi TOWS (SO, ST, WO, WT) sampai isu strategis.',
            'Visi, misi, nilai inti, kapabilitas; dari isu strategis ke 3–5 tema.',
        ], 'Kelompok mengisi SWOT dan memilih 3 tema strategis untuk satker BLU sendiri atau studi kasus UIN Sunan Kalijaga (data demo).', [['SWOT', url('/admin/swot-items')], ['TOWS', url('/admin/tows-strategies')]]],
        ['3', 'Menyusun Peta Strategi', '75 menit', [
            'Sasaran yang baik: kata kerja + hasil, satu gagasan per sasaran.',
            'Menyusun dari bawah (SDM) ke atas (Keuangan), lalu menarik panah sebab-akibat.',
            'Contoh: Peta Strategi UIN Sunan Kalijaga (kemandirian keuangan BLU, mutu akademik, kemitraan) — data demo.',
        ], 'Menyusun 8–12 sasaran di kertas plano dengan kartu tempel, lalu galeri antarkelompok.', [['Peta Strategi', Pages\PetaStrategi::getUrl()]]],
        ['4', 'IK dan target yang baik', '75 menit', [
            'Uji SMART; ukuran hasil (lagging) dan pendorong (leading); 2–4 IK per sasaran.',
            'Kamus Indikator Kinerja: definisi, rumus, satuan, arah, agregasi YTD, frekuensi, pemilik, sumber data.',
            'Baseline, target tahunan dan trajektori triwulanan; Definisi Operasional (rumus, sumber data, metode pengujian, dokumen pendukung); capaian maksimum; ambang 65% syarat insentif.',
        ], 'Menulis dua IK untuk setiap sasaran hasil Sesi 3 di lembar kamus Indikator Kinerja.', [['IK', url('/admin/kpis')], ['Target IK', url('/admin/kpi-targets')]]],
        ['5', 'Dari strategi ke aksi', '45 menit', [
            'Inisiatif strategis: tujuan, PIC, anggaran, aktivitas, milestone.',
            'Portofolio nilai–upaya: Quick Win, Prioritas Strategis, Pengisi, Tinjau Ulang.',
            'Progres inisiatif (70% aktivitas, 30% milestone) dan tanda terlambat.',
        ], 'Memilih 3 inisiatif dan menempatkannya di matriks nilai–upaya.', [['Portofolio Inisiatif', Pages\PortofolioInisiatif::getUrl()]]],
        ['6', 'Praktik Ruci BSC & simulasi rapat', '45 menit', [
            'Masuk sebagai Tamu ke satker BLU contoh (data demo): Kontrak Kinerja, Capaian & Insentif, cetak Lampiran I/II/IV/VI.',
            'Membaca Pusat Kendali, Peta Strategi, Portofolio Inisiatif, dan Laporan.',
            'Siklus: tanggal 3 pengingat, 5 input, 7 verifikasi, 8 persetujuan, 10 rapat; laporan capaian ≤ 15 hari kerja setelah triwulan.',
        ], 'Kelompok berperan sebagai Pejabat Pengelola: membahas dua sasaran merah, lalu menetapkan temuan dan tindakan korektif.', [['Pusat Kendali', Pages\PusatKendali::getUrl()], ['Panduan Pengguna', Pages\PanduanPengguna::getUrl()]]],
    ];

    $gains = [
        ['Pemahaman utuh', 'Manajemen strategi berbasis Balanced Scorecard: empat perspektif, sebab-akibat, siklus bulanan.'],
        ['Draf Peta Strategi', '8–12 sasaran dalam empat perspektif, bisa memakai satker BLU Anda sendiri.'],
        ['Contoh kamus Indikator Kinerja', 'Dua IK per sasaran lengkap dengan definisi, rumus, pemilik, baseline, dan target.'],
        ['Daftar inisiatif prioritas', 'Dipetakan menurut nilai dan upaya, siap dibahas di kantor.'],
        ['Pengalaman memakai Ruci BSC', 'Pusat Kendali, Peta Strategi, realisasi IK, inisiatif, dan simulasi rapat.'],
        ['Paket materi', 'Salinan paparan (PDF), lembar kerja, template Excel Ruci BSC, akses Panduan.'],
        ['Rencana aksi 30 hari', 'Langkah konkret memulai BSC di satker BLU masing-masing.'],
        ['Sertifikat', 'Sertifikat keikutsertaan Workshop Ruci BSC.'],
    ];

    // Edisi khusus setengah hari untuk Dewan Pengawas dan Pejabat Pengelola BLU, dengan narasumber tamu tata kelola.
    $special = [
        ['07.30', '08.00', 'Registrasi & kopi pagi', 'Akses demo Ruci BSC dan pembagian meja (Dewan Pengawas dan Pejabat Pengelola dicampur)', 'break'],
        ['08.00', '08.15', 'Pembukaan', 'Tujuan forum: dari laporan kinerja ke pengawasan yang terukur', 'open'],
        ['08.15', '09.15', 'Sesi tamu · Tata kelola dan pencegahan', 'Mengapa kinerja harus terukur dan tercatat · '.$speaker, 'talk'],
        ['09.15', '09.45', 'Sesi 2 · BSC sebagai alat pengawasan', 'Dari Renstra, RBA, dan Kontrak Kinerja Pemimpin BLU ke Peta Strategi; peran Dewan Pengawas dan Pejabat Pengelola', 'talk'],
        ['09.45', '10.00', 'Rehat', '', 'break'],
        ['10.00', '11.15', 'Sesi 3 · Studi kasus UIN Sunan Kalijaga', 'Membaca peta strategi, IK di bawah 65%, syarat insentif, dan temuan; menyusun pertanyaan pengawasan', 'work'],
        ['11.15', '12.00', 'Sesi 4 · Simulasi rapat Dewan Pengawas–Pejabat Pengelola', 'Pusat Kendali di layar: tanya-jawab, tindakan korektif, tindak lanjut', 'app'],
        ['12.00', '12.30', 'Dialog & penutupan', 'Tanya-jawab dengan narasumber, komitmen pengawasan 90 hari', 'open'],
        ['12.30', '13.30', 'Makan siang & ramah tamah', '', 'break'],
    ];

    $specialSessions = [
        ['T', 'Tata kelola dan pencegahan', '60 menit · narasumber tamu', [
            'Pengalaman narasumber: di mana tata kelola biasanya bocor, dan tanda-tanda awalnya.',
            'Kinerja yang terukur dan tercatat sebagai bentuk pencegahan, bukan sekadar pelaporan.',
            'Jejak keputusan: siapa menetapkan target, siapa memverifikasi angka, siapa menyetujui.',
            'Sesi ini berisi pandangan narasumber, bukan paparan produk.',
        ], 'Refleksi singkat: satu keputusan penting tahun ini yang jejaknya sulit ditelusuri.', []],
        ['2', 'BSC sebagai alat pengawasan', '30 menit', [
            'Menurunkan Renstra, RBA, dan Indikator Kinerja Kontrak Kinerja ke sasaran dan IK empat perspektif.',
            'Pembagian peran: Pejabat Pengelola menjalankan dan melaporkan, Dewan Pengawas mengawasi dan memberi nasihat.',
            'Siklus bulanan Ruci BSC: input, verifikasi, persetujuan, rapat, temuan, tindak lanjut, jejak audit.',
        ], 'Memetakan tiga IK Kontrak Kinerja satker BLU sendiri ke perspektif BSC.', [['Peta Strategi', Pages\PetaStrategi::getUrl()]]],
        ['3', 'Studi kasus UIN Sunan Kalijaga (data demo)', '75 menit', [
            'Konteks: PNBP 99,48% dari target dan pendapatan kerja sama di bawah 65% menggugurkan syarat insentif 2025.',
            'Membaca Peta Strategi, Capaian & Insentif, dan usulan perubahan Kontrak Kinerja 2026.',
            'Membedakan gejala (angka merah) dari akar masalah (proses, SDM, sistem).',
        ], 'Meja Dewan Pengawas menyusun lima pertanyaan pengawasan; meja Pejabat Pengelola menyiapkan jawaban dan tindakan korektif.', [['Pusat Kendali', Pages\PusatKendali::getUrl()], ['Temuan', url('/admin/findings')]]],
        ['4', 'Simulasi rapat Dewan Pengawas–Pejabat Pengelola', '45 menit', [
            'Rapat 30 menit dengan Pusat Kendali di layar: hanya membahas yang merah dan kuning.',
            'Mengubah pertanyaan menjadi temuan, PIC, dan tenggat yang tercatat.',
            'Menutup rapat dengan ringkasan keputusan yang bisa ditelusuri bulan berikutnya.',
        ], 'Bergantian peran Dewan Pengawas dan Pejabat Pengelola, lalu membandingkan kualitas tindak lanjutnya.', [['Laporan', url('/admin/laporan')]]],
    ];
@endphp
<x-filament-panels::page>
<div class="arah kenali">
    <section class="kenali-workshop ws-hero">
        <div>
            <span class="kenali-eyebrow">Workshop sehari · setiap Minggu pertama awal bulan</span>
            <h2>Mengenal Ruci BSC: dari strategi menjadi rencana aksi dalam satu hari</h2>
            <p>Kelas praktis 08.00–16.30 WIB untuk Pemimpin BLU, Pejabat Pengelola, Dewan Pengawas, dan tim perencanaan. Peserta belajar konsep Balanced Scorecard, menyusun Peta Strategi dan IK dalam kelompok, lalu langsung mencoba Ruci BSC BLU dengan studi kasus data demo UIN Sunan Kalijaga, termasuk menyusun Kontrak Kinerja dan membaca syarat insentif PER-6/PB/2025.</p>
            <div class="kenali-actions">
                @if ($workshopUrl)
                    <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop</a>
                @endif
                <a href="#rundown" class="arah-btn kenali-btn-lg">Lihat rundown</a>
                <a href="#edisi-blu" class="arah-btn kenali-btn-lg">Edisi Dewan Pengawas &amp; Pejabat Pengelola BLU</a>
            </div>
            @if ($workshopContact)<div class="kenali-contact">Informasi &amp; pendaftaran: <b>{{ $workshopContact }}</b></div>@endif
        </div>
        <div class="ws-dates">
            <b>Jadwal berikutnya</b>
            <ul>
                @foreach ($dates as $i => $d)
                    <li class="{{ $i === 0 ? 'is-next' : '' }}"><span>{{ $d->locale('id')->translatedFormat('l') }}</span>{{ $d->locale('id')->translatedFormat('j F Y') }}</li>
                @endforeach
            </ul>
            <small>08.00–16.30 WIB · registrasi mulai 07.30 · 20–30 peserta</small>
        </div>
    </section>

    <section class="kenali-section">
        <h2>Yang Anda peroleh</h2>
        <p class="kenali-lead">Pulang dengan pemahaman konsep sekaligus hasil kerja yang bisa langsung dibawa ke kantor.</p>
        <div class="arah-grid arah-grid-4">
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
        <div class="arah-card ws-rundown">
            @foreach ($rundown as [$from, $to, $title, $desc, $kind])
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
    </section>

    <section class="kenali-section">
        <h2>Materi per sesi</h2>
        <p class="kenali-lead">Setiap sesi memadukan paparan singkat, latihan kelompok, dan contoh nyata di Ruci BSC. Tautan di bawah setiap sesi membuka menu yang dipakai saat praktik.</p>
        <div class="arah-grid arah-grid-2">
            @foreach ($sessions as [$no, $title, $dur, $points, $exercise, $links])
                <div class="arah-card ws-session">
                    <div class="ws-session-head"><span class="ws-session-no">{{ $no }}</span><div><b>{{ $title }}</b><span>{{ $dur }}</span></div></div>
                    <ul>@foreach ($points as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                    <div class="ws-exercise"><b>Latihan:</b> {{ $exercise }}</div>
                    <div class="ws-links">@foreach ($links as [$label, $href])<a href="{{ $href }}" class="arah-btn">{{ $label }} →</a>@endforeach</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="kenali-section ws-special" id="edisi-blu">
        <span class="kenali-eyebrow">Edisi khusus · setengah hari · jadwal sesuai kesepakatan</span>
        <h2>Dari Laporan ke Pengawasan: Dewan Pengawas dan Pejabat Pengelola Mengawal Kinerja dengan Balanced Scorecard</h2>
        <p class="kenali-lead">Forum terbatas 15–25 peserta untuk Dewan Pengawas, Pemimpin BLU, Pejabat Pengelola, Komite Audit, dan SPI satker BLU. Dibuka oleh {{ $speaker }}, lalu dilanjutkan praktik pengawasan kinerja dengan studi kasus UIN Sunan Kalijaga (data demo).</p>

        <div class="arah-card ws-rundown">
            @foreach ($special as [$from, $to, $title, $desc, $kind])
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

        <div class="arah-grid arah-grid-2" style="margin-top:1rem">
            @foreach ($specialSessions as [$no, $title, $dur, $points, $exercise, $links])
                <div class="arah-card ws-session">
                    <div class="ws-session-head"><span class="ws-session-no">{{ $no }}</span><div><b>{{ $title }}</b><span>{{ $dur }}</span></div></div>
                    <ul>@foreach ($points as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                    <div class="ws-exercise"><b>Latihan:</b> {{ $exercise }}</div>
                    @if ($links)<div class="ws-links">@foreach ($links as [$label, $href])<a href="{{ $href }}" class="arah-btn">{{ $label }} →</a>@endforeach</div>@endif
                </div>
            @endforeach
        </div>

        <div class="arah-grid arah-grid-3" style="margin-top:1rem">
            <div class="arah-card"><h3>Hasil untuk peserta</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Daftar pertanyaan pengawasan kinerja, contoh pemetaan IK kontrak manajemen ke BSC, format rapat bulanan 30 menit, dan komitmen pengawasan 90 hari.</div></div>
            <div class="arah-card"><h3>Format</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Setengah hari (08.00–12.30 WIB) di kantor peserta atau tempat netral. Bisa diselenggarakan tertutup (in-house) untuk satu Dewan Pengawas dan Pejabat Pengelola.</div></div>
            <div class="arah-card"><h3>Prinsip</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Forum belajar, bukan forum pengadaan. Narasumber tamu tidak terkait dengan penjualan aplikasi. Setiap pengadaan mengikuti prosedur resmi satker BLU.</div></div>
        </div>
    </section>

    <section class="kenali-section">
        <h2>Persiapan peserta</h2>
        <div class="arah-grid arah-grid-3">
            <div class="arah-card"><h3>Siapa yang cocok ikut</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Pemilik usaha, Dewan Pengawas, Pejabat Pengelola, manajer perencanaan/strategi, kepala divisi, dan konsultan. Paling efektif bila 2–3 orang dari satker BLU yang sama hadir bersama.</div></div>
            <div class="arah-card"><h3>Yang perlu dibawa</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Laptop atau tablet dengan peramban. Bila ingin berlatih dengan satker BLU sendiri, bawa visi-misi dan rencana kerja tahun ini.</div></div>
            <div class="arah-card"><h3>Setelah workshop</h3><div class="arah-muted" style="font-size:.85rem; line-height:1.6">Dalam tiga hari peserta menerima materi PDF, foto hasil kelompok, template Excel Ruci BSC, sertifikat, dan tawaran sesi konsultasi implementasi (lihat <a href="{{ Pages\PanduanImplementasi::getUrl() }}" style="color:var(--k-taum); font-weight:600">Panduan Implementasi</a>).</div></div>
        </div>
    </section>
</div>
</x-filament-panels::page>
