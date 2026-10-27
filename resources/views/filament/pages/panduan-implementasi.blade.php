@php
    $toc = [
        'ringkasan' => 'Ringkasan proyek', 'prasyarat' => 'Prasyarat', 'lingkup' => 'Lingkup kegiatan', 'jadwal' => 'Jadwal',
        'sumberdaya' => 'Sumber daya', 'organisasi' => 'Organisasi proyek', 'mutu' => 'Manajemen mutu', 'komunikasi' => 'Manajemen komunikasi',
        'risiko' => 'Manajemen risiko', 'pengadaan' => 'Pengadaan', 'integrasi' => 'Integrasi & kunci sukses',
    ];
    $n = array_flip(array_keys($toc));
    $num = fn (string $k) => $n[$k] + 1;

    // Jadwal: 12 minggu implementasi + 3 bulan pendampingan
    $cols = array_merge(array_map(fn ($i) => 'M'.$i, range(1, 12)), ['B4', 'B5', 'B6']);
    $blue = '#1F4E8C'; $teal = '#2A8C9E'; $green = '#2E7D4F'; $gold = '#C88A12'; $red = '#D32F2F'; $gray = '#8A99AE';
    $gantt = [
        ['Fase 0 · Persiapan & mobilisasi', 'Kick-off, tim, rencana proyek', 1, 2, $blue],
        ['Fase 1 · Diagnosa', 'Kajian dokumen, wawancara, lingkungan', 2, 4, $blue],
        ['Fase 2 · Workshop strategi', 'Tema, sasaran, Peta Strategi', 4, 6, $teal],
        ['Fase 3 · KPI, target & inisiatif', 'Kamus KPI, target bulanan, portofolio', 6, 8, $teal],
        ['Fase 4 · Instalasi & data', 'Server, akun, peran, import Excel', 5, 9, $gold],
        ['Fase 5 · Pelatihan & UAT', 'Pelatihan per peran, uji terima', 9, 10, $gold],
        ['Fase 6 · Go-live', 'Siklus bulanan pertama', 11, 12, $green],
        ['Fase 7 · Pendampingan', '3 siklus bulanan & rapat tinjauan', 13, 15, $green],
        ['Manajemen proyek', 'Lintas fase: rapat, laporan, risiko', 1, 15, $gray],
    ];
    $milestones = [1 => 'M1 Kick-off', 6 => 'M2 Peta Strategi disahkan', 8 => 'M3 Kamus KPI & target disahkan', 9 => 'M4 Sistem siap UAT', 11 => 'M5 Go-live', 15 => 'M6 Serah terima'];

    $lvl = fn (string $l) => match ($l) { 'T' => 'arah-merah', 'S' => 'arah-kuning', default => 'arah-hijau' };
    $lvlName = ['T' => 'Tinggi', 'S' => 'Sedang', 'R' => 'Rendah'];
    $risks = [
        ['Komitmen Direksi menurun di tengah jalan', 'S', 'T', 'Sponsor dari Direktur Utama; Steering Committee bulanan; tampilkan hasil cepat (quick win) sejak minggu ke-6.', 'Sponsor proyek'],
        ['Rencana strategis belum jelas atau belum disepakati', 'S', 'T', 'Fase Diagnosa memetakan dokumen RJPP/RKAP; workshop strategi difasilitasi sampai Peta Strategi disahkan (M2).', 'Konsultan utama'],
        ['KPI terlalu banyak atau tidak terukur', 'T', 'S', 'Batasi 2–4 KPI per sasaran; uji SMART dan ketersediaan data sebelum M3; KPI tanpa sumber data ditunda.', 'Kantor Strategi'],
        ['Data historis tidak tersedia atau tidak konsisten', 'T', 'S', 'Inventarisasi data di Fase 1; baseline boleh estimasi bertanda; perbaikan sumber data menjadi inisiatif.', 'Keuangan & TI'],
        ['Pemilik KPI tidak disiplin mengisi realisasi', 'T', 'T', 'Pengingat otomatis tanggal 3; status kelengkapan dibahas di rapat; kepatuhan siklus menjadi KPI unit.', 'Direksi'],
        ['Penolakan karena dianggap alat penilaian individu', 'S', 'S', 'Komunikasikan BSC sebagai alat belajar dan koreksi; town hall; champion per divisi.', 'PM klien'],
        ['Personel kunci klien berganti', 'S', 'S', 'Dokumentasi di Ruci BSC; minimal dua orang terlatih per peran kunci; serah terima tertulis.', 'PM klien'],
        ['Kendala infrastruktur (server, jaringan, email)', 'R', 'S', 'Prasyarat teknis diperiksa di Fase 0; opsi hosting cloud; uji email & cron sebelum UAT.', 'TI klien'],
        ['Lingkup melebar (permintaan fitur/integrasi baru)', 'S', 'S', 'Kendali perubahan lewat formulir & persetujuan Steering Committee; tampung sebagai fase lanjutan.', 'PM Rucisoft'],
        ['Keamanan & kerahasiaan data strategis', 'R', 'T', 'Hak akses per peran, HTTPS, MFA untuk pimpinan, log audit, perjanjian kerahasiaan, backup harian.', 'TI klien'],
    ];
@endphp
<x-filament-panels::page>
<div class="arah impl">
    <aside class="impl-toc">
        <ol>
            @foreach ($toc as $id => $label)
                <li><a href="#{{ $id }}">{{ $label }}</a></li>
            @endforeach
        </ol>
        <button type="button" onclick="window.print()" class="arah-btn impl-print" style="margin-top:.8rem">🖶 Cetak / simpan PDF</button>
    </aside>

    <div class="impl-body">
        {{-- 1. Ringkasan --}}
        <section id="ringkasan">
            <h2><span class="n">{{ $num('ringkasan') }}.</span> Ringkasan proyek</h2>
            <p>Implementasi Ruci BSC bukan sekadar memasang perangkat lunak. Tujuannya adalah membuat perusahaan memiliki <b>strategi yang tertulis, terukur, dan dijalankan dalam ritme bulanan</b>: Peta Strategi yang disepakati, kamus KPI dengan target bulanan, portofolio inisiatif beranggaran, serta tata kelola tinjauan dari unit sampai Dewan Komisaris, semuanya berjalan di Ruci BSC.</p>
            <div class="impl-kv">
                <div class="arah-card arah-stat"><span class="label">Durasi implementasi</span><span class="value">12 minggu</span><span class="sub">ditambah 3 bulan pendampingan</span></div>
                <div class="arah-card arah-stat"><span class="label">Hasil utama</span><span class="value">6 milestone</span><span class="sub">dari kick-off sampai serah terima</span></div>
                <div class="arah-card arah-stat"><span class="label">Siklus bulanan</span><span class="value">3 siklus</span><span class="sub">didampingi sebelum mandiri</span></div>
                <div class="arah-card arah-stat"><span class="label">Skala acuan</span><span class="value">5–10 divisi</span><span class="sub">± 30–60 KPI perusahaan</span></div>
            </div>
            <p class="arah-muted" style="margin-top:.7rem">Angka durasi dan sumber daya di panduan ini adalah acuan untuk perusahaan berskala menengah. Untuk grup usaha dengan banyak anak perusahaan, proyek dapat dijalankan bertahap per perusahaan.</p>
        </section>

        {{-- 2. Prasyarat --}}
        <section id="prasyarat">
            <h2><span class="n">{{ $num('prasyarat') }}.</span> Prasyarat</h2>
            <div class="arah-grid arah-grid-3">
                <div class="arah-card">
                    <h3>Organisasi</h3>
                    <ul>
                        <li><b>Sponsor</b> dari Direktur Utama yang hadir di momen kunci.</li>
                        <li>Dokumen arah usaha: visi-misi, RJPP/rencana jangka panjang, RKAP/anggaran tahun berjalan.</li>
                        <li>Struktur organisasi dan daftar pejabat yang akan menjadi pemilik KPI.</li>
                        <li>Penunjukan <b>Project Manager</b> klien dan tim Kantor Strategi (SK/surat tugas).</li>
                        <li>Kesediaan waktu Direksi untuk 2–3 sesi workshop.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Data</h3>
                    <ul>
                        <li>Laporan keuangan 1–2 tahun terakhir (bulanan bila ada).</li>
                        <li>Laporan operasional dan pemasaran yang sudah rutin dibuat.</li>
                        <li>Daftar program/proyek berjalan beserta anggarannya.</li>
                        <li>Hasil survei pelanggan/karyawan, audit, atau penilaian risiko bila ada.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Teknis</h3>
                    <ul>
                        <li>Hosting cloud oleh Rucisoft, <b>atau</b> server perusahaan: PHP 8.3, MySQL 8/MariaDB 10.6+, 2 vCPU, 4 GB RAM, 40 GB SSD.</li>
                        <li>Domain/subdomain dengan sertifikat HTTPS.</li>
                        <li>Akun email SMTP untuk notifikasi, dan cron terjadwal.</li>
                        <li>Peramban modern di komputer dan ponsel pengguna.</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- 3. Lingkup --}}
        <section id="lingkup">
            <h2><span class="n">{{ $num('lingkup') }}.</span> Lingkup kegiatan</h2>
            <p>Lingkup dibagi menjadi delapan fase. Setiap fase memiliki keluaran yang jelas dan diperiksa melalui gerbang mutu (lihat bagian Manajemen mutu).</p>
            <div class="arah-card arah-scroll">
                <table class="arah-table impl-table">
                    <thead><tr><th>Fase</th><th>Kegiatan utama</th><th>Keluaran</th></tr></thead>
                    <tbody>
                        <tr><td><b>0 · Persiapan</b></td><td>Kick-off dengan Direksi, pembentukan tim, rencana proyek rinci, pemeriksaan prasyarat teknis.</td><td>Piagam proyek, rencana kerja, SK tim</td></tr>
                        <tr><td><b>1 · Diagnosa</b></td><td>Kajian dokumen strategi, wawancara Direksi & kepala divisi, analisis PESTEL, industri, pesaing, SWOT.</td><td>Laporan diagnosa, daftar isu strategis, data Ekosistem di Ruci BSC</td></tr>
                        <tr><td><b>2 · Workshop strategi</b></td><td>Penegasan visi-misi & nilai, TOWS, pemilihan tema, perumusan sasaran 4 perspektif dan hubungan sebab-akibat.</td><td>Peta Strategi disahkan Direksi (M2)</td></tr>
                        <tr><td><b>3 · KPI, target, inisiatif</b></td><td>Kamus KPI (definisi, rumus, sumber data, pemilik), baseline, target tahunan & bulanan, pemilihan inisiatif, anggaran, milestone.</td><td>Kamus KPI & target disahkan (M3), portofolio inisiatif</td></tr>
                        <tr><td><b>4 · Instalasi & data</b></td><td>Instalasi atau penyiapan hosting, konfigurasi perusahaan & perioda, akun & peran, import data melalui template Excel.</td><td>Ruci BSC berisi data perusahaan, siap UAT (M4)</td></tr>
                        <tr><td><b>5 · Pelatihan & UAT</b></td><td>Pelatihan per peran (Direksi, Komisaris, Kantor Strategi, Keuangan, Kepala Unit, Admin), uji terima pengguna dengan skenario siklus bulanan.</td><td>Berita acara UAT, daftar hadir & materi pelatihan</td></tr>
                        <tr><td><b>6 · Go-live</b></td><td>Realisasi bulan pertama diisi, diverifikasi, disetujui; rapat manajemen pertama dipimpin dari Pusat Kendali.</td><td>Siklus bulanan pertama selesai (M5)</td></tr>
                        <tr><td><b>7 · Pendampingan</b></td><td>Pendampingan tiga siklus bulanan, penyempurnaan KPI/target, rapat Dewan Komisaris, penilaian kematangan awal.</td><td>Laporan penutupan, serah terima (M6)</td></tr>
                    </tbody>
                </table>
            </div>
            <p style="margin-top:.7rem"><b>Di luar lingkup</b> (dapat menjadi fase lanjutan): integrasi otomatis dengan ERP/sistem lain, penyusunan ulang RJPP, desain sistem remunerasi berbasis KPI, dan kustomisasi fitur khusus.</p>
        </section>

        {{-- 4. Jadwal --}}
        <section id="jadwal">
            <h2><span class="n">{{ $num('jadwal') }}.</span> Jadwal</h2>
            <p>M = minggu ke-, B = bulan ke- (pendampingan). Fase 4 berjalan paralel dengan Fase 2–3 agar sistem siap begitu kamus KPI disahkan.</p>
            <div class="arah-card arah-scroll">
                <div class="gantt" style="--cols: {{ count($cols) }}">
                    <div class="gh" style="text-align:left">Fase</div>
                    @foreach ($cols as $c)<div class="gh">{{ $c }}</div>@endforeach
                    @foreach ($gantt as [$label, $sub, $from, $to, $color])
                        <div class="gl">{{ $label }}<small>{{ $sub }}</small></div>
                        @foreach ($cols as $i => $c)
                            @php $col = $i + 1; @endphp
                            <div class="gb">@if ($col >= $from && $col <= $to)<span style="background: {{ $color }}; {{ $col > $from ? 'left:0;border-top-left-radius:0;border-bottom-left-radius:0;' : '' }}{{ $col < $to ? 'right:0;border-top-right-radius:0;border-bottom-right-radius:0;' : '' }}"></span>@endif</div>
                        @endforeach
                    @endforeach
                    <div class="gl">Milestone</div>
                    @foreach ($cols as $i => $c)
                        <div class="gm" title="{{ $milestones[$i + 1] ?? '' }}">{{ isset($milestones[$i + 1]) ? '◆' : '' }}</div>
                    @endforeach
                </div>
            </div>
            <div class="arah-grid arah-grid-3" style="margin-top:.8rem">
                @foreach ($milestones as $w => $m)
                    <div class="arah-muted"><span style="color:var(--k-kencu)">◆</span> <b style="color:var(--k-tinta)">{{ $m }}</b> · {{ $cols[$w - 1] }}</div>
                @endforeach
            </div>
        </section>

        {{-- 5. Sumber daya --}}
        <section id="sumberdaya">
            <h2><span class="n">{{ $num('sumberdaya') }}.</span> Perkiraan sumber daya</h2>
            <div class="arah-grid arah-grid-2">
                <div class="arah-card arah-scroll">
                    <h3>Tim Rucisoft</h3>
                    <table class="arah-table impl-table">
                        <thead><tr><th>Peran</th><th class="num">Hari-orang</th></tr></thead>
                        <tbody>
                            <tr><td><b>Konsultan utama</b><br>fasilitator workshop, penjamin mutu metodologi</td><td class="num">25–30</td></tr>
                            <tr><td><b>Konsultan BSC</b><br>diagnosa, kamus KPI, inisiatif, pendampingan siklus</td><td class="num">35–45</td></tr>
                            <tr><td><b>Project Manager</b><br>rencana, laporan, risiko, kendali perubahan</td><td class="num">15–20</td></tr>
                            <tr><td><b>Implementer TI</b><br>instalasi, konfigurasi, import data, dukungan teknis</td><td class="num">10–15</td></tr>
                            <tr><td><b>Pelatih</b><br>pelatihan per peran dan materi</td><td class="num">5–8</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="arah-card arah-scroll">
                    <h3>Tim perusahaan</h3>
                    <table class="arah-table impl-table">
                        <thead><tr><th>Peran</th><th class="num">Alokasi waktu</th></tr></thead>
                        <tbody>
                            <tr><td><b>Sponsor (Direktur Utama)</b><br>keputusan, kick-off, pengesahan</td><td class="num">± 5%</td></tr>
                            <tr><td><b>Direksi lain</b><br>workshop, pengesahan sasaran & target</td><td class="num">± 10% di Fase 2–3</td></tr>
                            <tr><td><b>Project Manager klien</b><br>koordinasi internal, jadwal, dokumen</td><td class="num">40–50%</td></tr>
                            <tr><td><b>Kantor Strategi (2–3 orang)</b><br>penyusun dan pemilik proses BSC</td><td class="num">50–70%</td></tr>
                            <tr><td><b>BSC Champion per divisi</b><br>KPI & data divisi, penghubung unit</td><td class="num">10–20%</td></tr>
                            <tr><td><b>Keuangan</b><br>baseline, target & anggaran inisiatif</td><td class="num">15–20%</td></tr>
                            <tr><td><b>TI (1 orang)</b><br>server, email, akun, backup</td><td class="num">10–20%</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <p style="margin-top:.7rem"><b>Sarana:</b> ruang workshop untuk 15–30 orang dengan proyektor, ruang pelatihan dengan komputer/laptop peserta, akses internet, serta server atau langganan hosting (lihat Prasyarat).</p>
        </section>

        {{-- 6. Organisasi --}}
        <section id="organisasi">
            <h2><span class="n">{{ $num('organisasi') }}.</span> Struktur organisasi pelaksana proyek</h2>
            <div class="arah-card">
                <div class="org">
                    <div class="org-row"><div class="org-box r"><b>Steering Committee</b>Direktur Utama (ketua), Direksi, wakil Dewan Komisaris</div></div>
                    <div class="org-row"><div class="org-box r"><b>Sponsor Proyek</b>Direktur Utama / Direktur Perencanaan</div><div class="org-box y"><b>Penjamin Mutu</b>Konsultan utama Rucisoft</div></div>
                    <div class="org-row"><div class="org-box"><b>Project Manager Klien</b>Kepala Kantor Strategi / Corporate Planning</div><div class="org-box"><b>Project Manager Rucisoft</b>rencana, laporan, risiko</div></div>
                    <div class="org-row">
                        <div class="org-box g"><b>Tim Metodologi</b>Kantor Strategi + Konsultan BSC</div>
                        <div class="org-box g"><b>Tim Data & Keuangan</b>Keuangan, akuntansi, data operasional</div>
                        <div class="org-box g"><b>Tim Teknis</b>TI klien + Implementer Rucisoft</div>
                        <div class="org-box g"><b>BSC Champions</b>satu orang per divisi/unit</div>
                    </div>
                </div>
                <div class="impl-legend"><span><i style="background:#D32F2F"></i>pengarah & keputusan</span><span><i style="background:#1F4E8C"></i>pengelola proyek</span><span><i style="background:#C88A12"></i>penjamin mutu</span><span><i style="background:#2E7D4F"></i>tim pelaksana</span></div>
            </div>
            <div class="arah-card arah-scroll" style="margin-top:1rem">
                <h3>Matriks tanggung jawab (RACI)</h3>
                <table class="arah-table impl-table">
                    <thead><tr><th>Keluaran</th><th>Steering</th><th>PM Klien</th><th>Kantor Strategi</th><th>Konsultan</th><th>Champions</th><th>TI</th></tr></thead>
                    <tbody>
                        @foreach ([
                            ['Peta Strategi', 'A', 'C', 'R', 'R', 'C', '–'],
                            ['Kamus KPI & target', 'A', 'C', 'R', 'R', 'R', '–'],
                            ['Portofolio inisiatif & anggaran', 'A', 'C', 'R', 'C', 'R', '–'],
                            ['Instalasi & konfigurasi', 'I', 'A', 'C', 'C', '–', 'R'],
                            ['Import data', 'I', 'A', 'R', 'C', 'C', 'R'],
                            ['Pelatihan & UAT', 'I', 'A', 'R', 'R', 'R', 'C'],
                            ['Siklus bulanan & rapat', 'A', 'R', 'R', 'C', 'R', 'I'],
                        ] as $row)
                            <tr>@foreach ($row as $i => $cell)<td @if ($i) style="text-align:center" @endif>{!! $i ? '<b>'.$cell.'</b>' : e($cell) !!}</td>@endforeach</tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="arah-muted" style="margin-top:.5rem">R = pelaksana · A = penanggung jawab akhir/pengesah · C = dimintai masukan · I = diberi informasi</div>
            </div>
        </section>

        {{-- 7. Mutu --}}
        <section id="mutu">
            <h2><span class="n">{{ $num('mutu') }}.</span> Manajemen mutu</h2>
            <p>Mutu dijaga pada dua hal: <b>mutu isi strategi</b> (apakah sasaran dan KPI benar) dan <b>mutu pelaksanaan</b> (apakah sistem dan siklus berjalan). Setiap fase ditutup dengan gerbang mutu yang ditandatangani PM klien dan penjamin mutu.</p>
            <div class="arah-grid arah-grid-2">
                <div class="arah-card">
                    <h3>Standar mutu isi</h3>
                    <ul>
                        <li>Setiap sasaran berasal dari isu strategis/TOWS dan memiliki hubungan sebab-akibat yang jelas.</li>
                        <li>2–4 KPI per sasaran, campuran ukuran hasil (lagging) dan pendorong (leading).</li>
                        <li>KPI lolos uji <b>SMART</b>: spesifik, terukur, dapat dicapai, relevan, berbatas waktu.</li>
                        <li>Setiap KPI punya definisi, rumus, sumber data, frekuensi, pemilik, baseline, dan target.</li>
                        <li>Setiap inisiatif terhubung ke minimal satu sasaran, punya PIC, anggaran, dan milestone.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Ukuran mutu proyek</h3>
                    <ul>
                        <li>100% KPI terdefinisi lengkap di Ruci BSC saat M3.</li>
                        <li>≥ 90% realisasi terisi tepat waktu pada siklus ke-3.</li>
                        <li>100% KPI keuangan terverifikasi sebelum persetujuan.</li>
                        <li>≥ 85% peserta pelatihan lulus uji praktik perannya.</li>
                        <li>Nol temuan kritis terbuka pada UAT saat go-live.</li>
                    </ul>
                </div>
            </div>
            <div class="arah-card arah-scroll" style="margin-top:1rem">
                <h3>Gerbang mutu</h3>
                <table class="arah-table impl-table">
                    <thead><tr><th>Gerbang</th><th>Kriteria lulus</th><th>Disahkan oleh</th></tr></thead>
                    <tbody>
                        <tr><td><b>G1 · Akhir Diagnosa</b></td><td>Isu strategis dan SWOT disepakati; data yang tersedia dan kesenjangannya terpetakan.</td><td>PM klien, penjamin mutu</td></tr>
                        <tr><td><b>G2 · Peta Strategi</b></td><td>Sasaran 4 perspektif lengkap dengan relasi; dipresentasikan dan disetujui Direksi.</td><td>Steering Committee</td></tr>
                        <tr><td><b>G3 · Kamus KPI</b></td><td>Semua KPI lolos SMART, punya pemilik dan target bulanan; inisiatif beranggaran.</td><td>Steering Committee</td></tr>
                        <tr><td><b>G4 · Siap UAT</b></td><td>Data terimport tanpa galat; akun & peran sesuai matriks; email dan cron berfungsi.</td><td>PM klien, TI</td></tr>
                        <tr><td><b>G5 · Go-live</b></td><td>Skenario UAT lulus; pelatihan selesai; backup terjadwal; rencana dukungan disetujui.</td><td>Sponsor proyek</td></tr>
                        <tr><td><b>G6 · Serah terima</b></td><td>Tiga siklus bulanan berjalan; ukuran mutu proyek tercapai; tim internal mandiri.</td><td>Steering Committee</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 8. Komunikasi --}}
        <section id="komunikasi">
            <h2><span class="n">{{ $num('komunikasi') }}.</span> Manajemen komunikasi</h2>
            <p>Komunikasi proyek punya dua tujuan: menjaga keputusan tepat waktu, dan membangun pemahaman bahwa BSC adalah <b>alat belajar dan koreksi bersama</b>, bukan sekadar alat penilaian.</p>
            <div class="arah-card arah-scroll">
                <table class="arah-table impl-table">
                    <thead><tr><th>Komunikasi</th><th>Kepada</th><th>Frekuensi</th><th>Media</th><th>Penanggung jawab</th></tr></thead>
                    <tbody>
                        @foreach ([
                            ['Kick-off & penjelasan tujuan', 'Direksi, kepala divisi', 'Sekali (M1)', 'Rapat tatap muka', 'Sponsor'],
                            ['Rapat Steering Committee', 'Steering Committee', 'Bulanan + di setiap gerbang', 'Rapat, menu Kenali Ruci BSC', 'PM klien'],
                            ['Rapat kemajuan proyek', 'Tim inti', 'Mingguan', 'Rapat singkat 30–45 menit', 'PM Rucisoft'],
                            ['Laporan status proyek', 'Sponsor, Steering', 'Dua mingguan', 'Laporan 1 halaman', 'PM Rucisoft'],
                            ['Town hall pengenalan BSC', 'Seluruh pejabat & karyawan kunci', 'Sekali sebelum go-live', 'Pertemuan + Peta Strategi', 'Direktur Utama'],
                            ['Sosialisasi KPI ke unit', 'Pemilik KPI, staf unit', 'Fase 3', 'Rapat divisi oleh champion', 'BSC Champions'],
                            ['Pengumuman go-live & jadwal siklus', 'Semua pengguna', 'Sekali (M5)', 'Email, surat edaran', 'PM klien'],
                            ['Pengingat siklus bulanan', 'Pemilik KPI, Keuangan, Pimpinan', 'Bulanan', 'Notifikasi & email Ruci BSC', 'Otomatis'],
                            ['Laporan kinerja ke Komisaris', 'Dewan Komisaris', 'Bulanan/kuartalan', 'Pusat Kendali, Laporan', 'Direksi'],
                        ] as $row)
                            <tr>@foreach ($row as $i => $cell)<td>{!! $i ? e($cell) : '<b>'.e($cell).'</b>' !!}</td>@endforeach</tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 9. Risiko --}}
        <section id="risiko">
            <h2><span class="n">{{ $num('risiko') }}.</span> Manajemen risiko</h2>
            <p>Risiko diidentifikasi sejak kick-off, ditinjau dalam rapat kemajuan mingguan, dan risiko tinggi dilaporkan ke Steering Committee. Berikut daftar risiko yang paling sering muncul pada implementasi BSC.</p>
            <div class="arah-card arah-scroll">
                <table class="arah-table impl-table">
                    <thead><tr><th>Risiko</th><th>Kemungkinan</th><th>Dampak</th><th>Mitigasi</th><th>Pemilik</th></tr></thead>
                    <tbody>
                        @foreach ($risks as [$risk, $p, $d, $mit, $owner])
                            <tr>
                                <td><b>{{ $risk }}</b></td>
                                <td><span class="arah-badge {{ $lvl($p) }}">{{ $lvlName[$p] }}</span></td>
                                <td><span class="arah-badge {{ $lvl($d) }}">{{ $lvlName[$d] }}</span></td>
                                <td>{{ $mit }}</td>
                                <td style="white-space:nowrap">{{ $owner }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="arah-card" style="margin-top:1rem">
                <h3>Peta panas risiko</h3>
                @php
                    $cell = fn ($p, $d) => collect($risks)->keys()->filter(fn ($k) => $risks[$k][1] === $p && $risks[$k][2] === $d)->map(fn ($k) => 'R'.($k + 1))->join(', ');
                    $heat = ['R' => 1, 'S' => 2, 'T' => 3];
                @endphp
                <div class="heat">
                    <div class="hl"></div><div class="hl" style="text-align:center">Dampak rendah</div><div class="hl" style="text-align:center">Dampak sedang</div><div class="hl" style="text-align:center">Dampak tinggi</div>
                    @foreach (['T', 'S', 'R'] as $p)
                        <div class="hl">Kemungkinan {{ strtolower($lvlName[$p]) }}</div>
                        @foreach (['R', 'S', 'T'] as $d)
                            @php $score = $heat[$p] + $heat[$d]; @endphp
                            <div class="{{ $score >= 5 ? 'h3' : ($score >= 4 ? 'h2' : 'h1') }}"><b>{{ $cell($p, $d) ?: '–' }}</b></div>
                        @endforeach
                    @endforeach
                </div>
                <div class="arah-muted" style="margin-top:.5rem">R1–R10 mengikuti urutan tabel di atas.</div>
            </div>
        </section>

        {{-- 10. Pengadaan --}}
        <section id="pengadaan">
            <h2><span class="n">{{ $num('pengadaan') }}.</span> Pengadaan</h2>
            <p>Pengadaan mengikuti kebijakan pengadaan barang/jasa perusahaan (misalnya pedoman pengadaan BUMN/BUMD atau SOP internal). Komponen yang umumnya diadakan:</p>
            <div class="arah-card arah-scroll">
                <table class="arah-table impl-table">
                    <thead><tr><th>Komponen</th><th>Isi</th><th>Pilihan skema</th></tr></thead>
                    <tbody>
                        <tr><td><b>Perangkat lunak Ruci BSC</b></td><td>Hak pakai aplikasi untuk perusahaan/grup, pembaruan versi</td><td>Langganan tahunan (cloud) atau lisensi instalasi di server sendiri</td></tr>
                        <tr><td><b>Infrastruktur</b></td><td>Hosting/server, domain, sertifikat HTTPS, backup</td><td>Disediakan Rucisoft (cloud) atau oleh TI perusahaan</td></tr>
                        <tr><td><b>Jasa konsultansi & workshop</b></td><td>Diagnosa, workshop strategi, kamus KPI, inisiatif</td><td>Kontrak lumpsum berbasis keluaran per fase</td></tr>
                        <tr><td><b>Implementasi & pelatihan</b></td><td>Instalasi, konfigurasi, import data, pelatihan per peran, UAT</td><td>Satu paket dengan konsultansi</td></tr>
                        <tr><td><b>Pendampingan & dukungan</b></td><td>Pendampingan 3 siklus, dukungan teknis pasca go-live</td><td>Paket bulanan atau tahunan (SLA)</td></tr>
                        <tr><td><b>Opsional</b></td><td>Integrasi data dengan ERP/sistem lain, kustomisasi laporan</td><td>Kontrak terpisah setelah kebutuhan jelas</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="arah-grid arah-grid-2" style="margin-top:1rem">
                <div class="arah-card">
                    <h3>Dokumen pengadaan</h3>
                    <ul>
                        <li>Kerangka Acuan Kerja (KAK/TOR) dengan lingkup dan keluaran dari panduan ini.</li>
                        <li>Proposal teknis dan proposal biaya.</li>
                        <li>Kontrak dengan lampiran jadwal, milestone, dan perjanjian kerahasiaan data.</li>
                        <li>Berita Acara Serah Terima (BAST) per milestone.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Termin pembayaran (contoh)</h3>
                    <ul>
                        <li>Uang muka setelah kontrak dan kick-off (M1).</li>
                        <li>Termin setelah Peta Strategi disahkan (M2).</li>
                        <li>Termin setelah kamus KPI disahkan dan sistem siap UAT (M3–M4).</li>
                        <li>Termin setelah go-live (M5) dan pelunasan pada serah terima (M6).</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- 11. Integrasi --}}
        <section id="integrasi">
            <h2><span class="n">{{ $num('integrasi') }}.</span> Integrasi & kunci sukses</h2>
            <p>Semua bagian di atas disatukan dalam <b>satu rencana proyek terpadu</b> yang dipegang PM klien dan PM Rucisoft. Jadwal, sumber daya, mutu, komunikasi, risiko, dan pengadaan saling terkait: perubahan pada satu bagian selalu diperiksa dampaknya ke bagian lain sebelum disetujui.</p>
            <div class="arah-grid arah-grid-3">
                <div class="arah-card">
                    <h3>Kendali perubahan</h3>
                    <ul>
                        <li>Permintaan perubahan diajukan tertulis (apa, alasan, dampak).</li>
                        <li>PM menilai dampak ke jadwal, biaya, mutu, dan risiko.</li>
                        <li>Perubahan kecil disetujui PM klien; perubahan lingkup/jadwal oleh Steering Committee.</li>
                        <li>Semua perubahan dicatat dalam log perubahan proyek.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Daftar periksa go-live</h3>
                    <ul>
                        <li>Peta Strategi, KPI, target, dan inisiatif sudah di Ruci BSC.</li>
                        <li>Setiap pengguna punya akun dan peran sesuai matriks.</li>
                        <li>Jadwal siklus bulanan diumumkan dan tercantum di kalender rapat.</li>
                        <li>Backup harian dan email notifikasi teruji.</li>
                        <li>Kontak dukungan dan prosedur eskalasi diketahui pengguna.</li>
                    </ul>
                </div>
                <div class="arah-card">
                    <h3>Faktor kunci sukses</h3>
                    <ul>
                        <li><b>Direktur Utama memimpin</b> rapat bulanan dari Pusat Kendali.</li>
                        <li>KPI sedikit tetapi tajam; kualitas mengalahkan jumlah.</li>
                        <li>Disiplin tanggal siklus, bukan menunggu data sempurna.</li>
                        <li>Hasil rapat menjadi temuan dan tindakan korektif yang dipantau.</li>
                        <li>Kantor Strategi diberi mandat dan waktu yang cukup.</li>
                    </ul>
                </div>
            </div>
            <div class="arah-card" style="margin-top:1rem">
                <h3>Setelah serah terima</h3>
                <p style="margin:0">Kantor Strategi menjalankan siklus secara mandiri. Setiap akhir tahun dilakukan penilaian kematangan di Ruci BSC, pembaruan Ekosistem, dan penyusunan perioda baru (sasaran, target, inisiatif). Rucisoft tetap tersedia untuk dukungan teknis, pembaruan versi, dan penyegaran workshop.</p>
            </div>
            @if ($workshopUrl)
                <div class="kenali-workshop" style="margin-top:1.2rem; grid-template-columns: minmax(0,1fr)">
                    <div>
                        <span class="kenali-eyebrow">Mulai dari Workshop Ruci BSC</span>
                        <h2 style="display:block">Ingin menjalankan rencana ini di perusahaan Anda?</h2>
                        <p>Workshop Ruci BSC adalah langkah pertama: Direksi menyusun Peta Strategi bersama, lalu tim Rucisoft membantu menyiapkan rencana implementasi yang sesuai skala perusahaan.</p>
                        <div class="kenali-actions">
                            <a href="{{ $workshopUrl }}" target="_blank" rel="noopener" class="arah-btn arah-btn-primary kenali-btn-lg">Daftar Workshop Ruci BSC</a>
                        </div>
                        @if ($workshopContact)<div class="kenali-contact">Informasi &amp; pendaftaran: <b>{{ $workshopContact }}</b></div>@endif
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
</x-filament-panels::page>
