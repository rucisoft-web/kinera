# Ruci BSC Professional

Aplikasi **Balanced Scorecard multi-perusahaan** berbasis Laravel + MySQL, dikembangkan dari edisi DHASTRAM (ARAH GPCH).
Satu instalasi melayani banyak perusahaan; setiap perusahaan punya data, perioda, admin dan pengguna sendiri, dan datanya bisa diupload satu per satu lewat file Excel.

**Instalasi di Laragon → [PANDUAN-LARAGON.md](PANDUAN-LARAGON.md)** · **Instalasi di cPanel → [PANDUAN-CPANEL.md](PANDUAN-CPANEL.md)**

## Teknologi

| Lapisan | Komponen |
|---|---|
| Runtime | PHP 8.3+, **Laravel 13**, Livewire 4 |
| Antarmuka | **Filament v5** (panel `/admin`), stylesheet statis `public/css/arah.css` (tanpa Node.js) |
| Basis data | **MySQL 8 / MariaDB 10.6+** (SQLite untuk test) |
| Konteks | `DhastramContext` (sesi + tabel `user_contexts`) & global scope `BelongsToDhastramContext` menyaring setiap query per perusahaan/perioda |
| Excel | OpenSpout: import, template kosong, dan ekspor data perioda |
| Audit | `audit_logs` mencatat buat/ubah/hapus (`DHASTRAM_AUDIT_ENABLED`) |
| Jadwal | `rucibsc:reminders` harian (pengingat siklus input → verifikasi → persetujuan) |

## Multi-perusahaan

- **Super Admin** mengelola semua perusahaan, peran, dan semua akun.
- **Company Admin** mengelola pengguna dan penugasan peran **hanya di perusahaan yang sedang aktif**. Akun yang juga terdaftar di perusahaan lain hanya bisa diubah Super Admin (mencegah reset sandi lintas perusahaan).
- Peran diberikan per perusahaan (`role_user.company_id`); satu orang boleh punya peran di beberapa perusahaan dan berpindah lewat **Pilih Perusahaan & Perioda**.

## Import data perusahaan (menu Administrasi › Import Data)

1. Pilih perusahaan & perioda tujuan.
2. Unduh **template kosong** atau **ekspor data perioda saat ini**.
3. Isi sheet yang diperlukan: Jati Diri, Nilai Inti, Tema, Sasaran, Relasi Sasaran, KPI, Target, Realisasi, Inisiatif. Sheet yang tidak ada di file tidak diubah.
4. **Pratinjau** dulu (tidak menyimpan apa pun), lalu **Proses Import**.

Kode (sasaran, KPI, tema, inisiatif) menjadi kunci, jadi upload ulang memperbarui data, bukan menduplikasi. Baris yang salah dilewati dan dilaporkan dengan nomor barisnya. Angka boleh format Indonesia (`11.098.665`, `6,62`). Realisasi masuk sebagai **draf**, kecuali pengguna punya izin persetujuan dan mencentang "langsung setujui". Import terakhir sebuah perioda bisa **dibatalkan** (nilai sebelumnya dipulihkan). Izin: `import.manage`.

## Modul

- **Umum:** Beranda, Pusat Kendali Eksekutif, Alur Kerja, Peta Strategi, Laporan (+ Excel), Pilih Perusahaan & Perioda.
- **Ekosistem:** Pemindaian Lingkungan, PESTEL, Analisis Industri, Pasar & Pelanggan, Pesaing, Regulasi, Pemangku Kepentingan, Isu Strategis, SWOT, TOWS.
- **Jati Diri:** visi, misi, nilai inti, budaya, kapabilitas.
- **Kinerja:** Tema, Pilihan Strategis, Sasaran, Relasi Sasaran, KPI, Target, Realisasi (draf → terverifikasi → disetujui/terkunci, atau dikembalikan), Tinjauan Kinerja.
- **Perbaikan:** Portofolio & Inisiatif Strategis, Aktivitas, Milestone, Anggaran, Temuan, Tindakan Korektif.
- **Kesinambungan:** Kematangan Manajemen Strategi, Ketergantungan, Institusionalisasi.
- **Administrasi:** Perusahaan, Pengguna, Peran, Penugasan Peran, Import Data, Log Audit.

Skor KPI: polaritas naik = realisasi ÷ target × 100, turun = target ÷ realisasi × 100, maksimal 120; hijau ≥ 100, kuning ≥ 80. Agregasi jumlah, rata-rata, atau terakhir.

## Peran bawaan

`super-admin`, `company-admin`, `strategy-office`, `management`, `pimpinan`, `keuangan`, `kepala-unit`, `kpi-owner`, `initiative-pic`, `reviewer`, `pengawas`, `admin-sistem`, `editor-ekosistem`, `editor-jatidiri`, `pelaksana`, `viewer`. Isi izin peran berlaku global dan hanya diubah Super Admin.

## Data contoh

Di luar produksi (atau bila `RUCI_SEED_DEMO=true`) seeder memuat enam belas perusahaan contoh:

| Kode | Perusahaan | Isi |
|---|---|---|
| DFI | PT Diamond Food Indonesia Tbk | Demo lengkap: perioda 2025 (ditutup, angka tahunan dari Laporan Tahunan 2025) dan 2026 (berjalan, simulasi Jan–Sep). Peta strategi 4 tema, 15 sasaran, 39 KPI, 15 inisiatif, ekosistem, tinjauan, temuan, kematangan |
| HMN | PT Medikaloka Hermina Tbk | Demo lengkap jaringan rumah sakit: perioda 2025 (angka tahunan dari Laporan Tahunan 2025) dan 2026 (simulasi Jan–Sep). Peta strategi 4 tema, 16 sasaran, 45 KPI termasuk indikator mutu Kemenkes (INM), KRIS, klaim JKN, dokter spesialis |
| MEDC | PT Medco Energi Internasional Tbk | Demo lengkap energi (migas, listrik, tambang tembaga-emas): 2025 dan 2026, 15 sasaran, 44 KPI dalam US$ juta, mboepd, GWh, TRIR |
| PWON | PT Pakuwon Jati Tbk | Demo lengkap properti (mal, perkantoran, hotel, pengembangan): 2025 dan 2026, 15 sasaran, 44 KPI termasuk recurring income, okupansi, marketing sales |
| KAEF | PT Kimia Farma Tbk | Demo lengkap farmasi dalam masa penyehatan (produksi, distribusi, apotek, klinik, lab): 2025 dan 2026, 14 sasaran, 37 KPI termasuk restrukturisasi utang |
| SMGR | PT Semen Indonesia (Persero) Tbk | Demo lengkap semen & bahan bangunan: 2025 dan 2026, 14 sasaran, 39 KPI termasuk utilisasi, biaya energi, emisi CO₂ per ton |
| MAPI | PT Mitra Adiperkasa Tbk | Demo lengkap ritel gaya hidup (aktif, fashion, F&B, digital): 2025 dan 2026, 14 sasaran, 35 KPI termasuk SSSG, hari persediaan, omnichannel |
| GOTO | PT GoTo Gojek Tokopedia Tbk | Demo lengkap ekosistem digital (on-demand, e-commerce, fintech): 2025 dan 2026, 14 sasaran, 36 KPI termasuk GTV, EBITDA disesuaikan, keandalan |
| ASII | PT Astra International Tbk | Demo lengkap konglomerasi tujuh segmen: 2025 dan 2026, 14 sasaran, 38 KPI termasuk pangsa pasar mobil, alat berat, replanting sawit |
| BBCA | PT Bank Central Asia Tbk | Demo lengkap perbankan: 2025 dan 2026, 14 sasaran, 39 KPI termasuk CASA, NIM, LAR, transaksi digital |
| TLKM | PT Telkom Indonesia (Persero) Tbk | Demo lengkap telekomunikasi: 2025 dan 2026, 14 sasaran, 33 KPI termasuk FMC, data center, EBITDA margin |
| BIRD | PT Blue Bird Tbk | Demo lengkap transportasi darat (taksi, limosin, bus): 2025 dan 2026, 14 sasaran, 37 KPI termasuk utilisasi armada, armada listrik, keselamatan |
| BTPN | PT Bank SMBC Indonesia Tbk | Demo lengkap bank universal & induk konglomerasi (Jenius, BTPN Syariah, OTO, SOF): 2025 dan 2026, 14 sasaran, 37 KPI termasuk cost of credit, NPF Grup OTO, CASA |
| BMTP | PT Bank Mandiri Taspen | Demo lengkap bank pensiunan & UMKM (Bank Mantap): 2025 dan 2026 dengan target dari RBB, 14 sasaran, 36 KPI termasuk kredit pensiun, CASA, NPL mikro |
| NLP | PT Nusantara Logistik Prima (fiktif) | Perioda 2026 (Jan–Agu disetujui, Sep draf) + 2027 perencanaan |
| KSB | Klinik Sehat Bersama (fiktif) | Perioda 2026 |

Akun: Super Admin `admin@rucibsc.local` / `Ruci@BSC2026` (ubah lewat `RUCI_ADMIN_PASSWORD`). Akun contoh per perusahaan, kata sandi `Contoh-2026!`:
`dfi.admin@contoh.local`, `dfi.direktur@…`, `dfi.keuangan@…`, `dfi.komersial@…`, `dfi.rantaipasok@…`, `dfi.produksi@…`, `dfi.sdm@…`, `dfi.ti@…`, `dfi.strategi@…`, `dfi.komisaris@…`, `dfi.analis@…`; untuk Hermina `hmn.admin`, `hmn.direktur`, `hmn.keuangan`, `hmn.medis`, `hmn.operasional`, `hmn.pemasaran`, `hmn.pengembangan`, `hmn.sdm`, `hmn.ti`, `hmn.strategi`, `hmn.komisaris`, `hmn.analis` (@contoh.local); pola serupa untuk `medc.*`, `pwon.*`, `kaef.*`, `smgr.*`, `mapi.*`, `goto.*`, `asii.*`, `bbca.*`, `tlkm.*`, `bird.*`, `btpn.*`, `bmtp.*`, `nlp.*`, dan `ksb.*`.

**Peringatan data demo.** Semua data contoh adalah DATA DEMO untuk pembelajaran. Untuk DFI, HMN, MEDC, PWON, KAEF, SMGR, MAPI, GOTO, ASII, BBCA, TLKM, BIRD, BTPN, dan BMTP, angka 2025 secara garis besar mengacu pada Laporan Tahunan 2025 perusahaan terkait (kolom `companies.demo_source`); target, rincian bulanan, data 2026, dan sebagian indikator adalah simulasi. Peringatan ini tampil sebagai pita di setiap halaman perusahaan demo (`App\Support\DemoNotice`), di halaman login, dan di sheet Petunjuk template Excel.

Memuat satu demo di server yang sudah berjalan: `php artisan rucibsc:demo DFI|HMN|MEDC|PWON|KAEF|SMGR|MAPI|GOTO|ASII|BBCA|TLKM|BIRD|BTPN|BMTP` (Diamond Food, Hermina, Medco, Pakuwon, Kimia Farma, Semen Indonesia, MAP, GoTo, Astra, BCA, Telkom, Blue Bird, SMBC Indonesia, Mandiri Taspen). Kata sandi akun contoh dibuat acak dan ditampilkan (atau tentukan dengan `--password=`). Bila perusahaan itu sudah punya perioda 2025/2026, tambahkan `--reset` untuk menggantinya. Nama lama `rucibsc:demo-diamond` tetap bisa dipakai.

Situs demo/promosi: `RUCI_GUEST_LOGIN=true` menampilkan tombol **Masuk sebagai Tamu (lihat-lihat)** di halaman login (rute `/masuk-tamu`, menerima `?perusahaan=KODE` untuk membuka perusahaan contoh pada tahun berjalan dan `?ke=kenali|panduan|implementasi|workshop`). Rute `/` menjadi halaman depan promosi (`LandingController`, `resources/views/landing.blade.php`, `public/css/landing.css`) dengan daftar data demo dari `App\Support\DemoCatalog`; bila akses Tamu mati, `/` diarahkan ke `/admin`. Akun Tamu (peran `tamu`, global) hanya punya izin lihat tanpa log audit dan ekspor, konteksnya disimpan per sesi, dan `App\Support\GuestMode` menolak setiap penyimpanan/penghapusan model oleh Tamu sebagai pengaman terakhir.

Menu **Kenali Ruci BSC** (`/admin/kenali-ruci-bsc`, tautan berbagi `/kenali`) menjelaskan Ruci BSC untuk pimpinan dan mengajak ikut Workshop; tautan dan kontak Workshop lewat `RUCI_WORKSHOP_URL` dan `RUCI_WORKSHOP_CONTACT`. Tangkapan layarnya ada di `public/images/kenali/`. Grup menu **Panduan** juga berisi **Panduan Pengguna** (per peran, tangkapan layar di `public/images/panduan/`) dan **Panduan Implementasi** (prasyarat, lingkup, jadwal, sumber daya, organisasi proyek, mutu, komunikasi, risiko, pengadaan, integrasi; bisa dicetak ke PDF), serta **Workshop Ruci BSC** (rundown & materi workshop sehari, jadwal otomatis setiap Minggu pertama awal bulan, plus edisi khusus setengah hari untuk Komisaris dan Direksi BUMN; nama narasumber tamu lewat `RUCI_WORKSHOP_SPECIAL_SPEAKER`).

Sebelum dipakai sungguhan: `php artisan rucibsc:hapus-contoh` menghapus semua perusahaan dan akun contoh, lalu ganti sandi Super Admin.

## Perintah penting

| Perintah | Fungsi |
|---|---|
| `php artisan dhastram:doctor` | Cek PHP, ekstensi, database, izin, peran, data contoh |
| `php artisan dhastram:create-admin` | Membuat Super Admin baru |
| `php artisan db:seed --class=DhastramRolePermissionSeeder` | Memperbarui izin & peran (aman diulang) |
| `php artisan rucibsc:demo DFI\|HMN\|MEDC\|PWON\|KAEF\|SMGR\|MAPI\|GOTO\|ASII\|BBCA\|TLKM\|BIRD\|BTPN\|BMTP [--reset]` | Memuat satu demo lengkap (2025 + 2026) |
| `php artisan rucibsc:hapus-contoh` | Menghapus perusahaan & akun contoh |
| `php artisan rucibsc:reminders --date=2026-11-03` | Uji pengingat |
| `php vendor/bin/phpunit` | Menjalankan test otomatis |

## Produksi (cPanel / VPS)

Langkah lengkap untuk cPanel (dengan atau tanpa Terminal) ada di [PANDUAN-CPANEL.md](PANDUAN-CPANEL.md).


`APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`; document root ke `public/`; cron `* * * * * php /path/ruci-bsc/artisan schedule:run`.
Di produksi `DatabaseSeeder` hanya membuat izin, peran, dan pertanyaan kematangan. Buat Super Admin dengan `dhastram:create-admin`, tambahkan perusahaan, lalu upload datanya lewat Import Data.
Rilis: `composer install --no-dev --optimize-autoloader` → `php artisan migrate --force` → `php artisan optimize` → `php artisan dhastram:doctor`.
