# Ruci BSC BLU

Edisi **Badan Layanan Umum** dari Ruci BSC Professional: aplikasi **Balanced Scorecard multi-satker BLU** berbasis Laravel + MySQL,
ditambah **Kontrak Kinerja Pemimpin BLU** menurut **PER-6/PB/2025** (Pedoman Penyusunan Kontrak Kinerja dan Penetapan Persetujuan Capaian Kinerja Pemimpin BLU).
Satu instalasi melayani banyak satker BLU; setiap satker punya data, perioda, admin dan pengguna sendiri, dan datanya bisa diupload lewat file Excel.

## Fitur khusus BLU (PER-6/PB/2025)

| Ketentuan | Di aplikasi |
|---|---|
| Pasal 5: Indikator Kinerja, trajektori, target, aspek layanan & keuangan (+ kebijakan/penugasan), Definisi Operasional | Menu **Indikator Kinerja**: aspek, wajib (mandatory), capaian maksimum, peran insentif (PNBP/EBITDA/opini), Definisi Operasional (definisi, rumus, sumber data, cara pengambilan data, metode pengujian, dokumen pendukung); trajektori bawaan triwulanan |
| Pasal 6–9: usulan (hari kerja terakhir Desember), kesepakatan & Berita Acara (25 Januari), penandatanganan (31 Januari) | Menu **Kontrak Kinerja**: versi, status draf → diusulkan → disepakati → ditandatangani; potret indikator & target trajektori; cetak **Lampiran I** (Kontrak Kinerja) dan **Lampiran II** (Berita Acara) |
| Pasal 10: laporan capaian ≤ 15 hari kerja setelah periode trajektori | Halaman **Capaian & Insentif**: perhitungan per triwulan (format **Lampiran VI**), kalender tenggat dengan status tepat waktu/terlambat/lewat tenggat |
| Pasal 14 ayat (4): syarat insentif a–h | Uji otomatis: total capaian > target, indikator wajib tercapai, tidak ada IK < 65% (`DHASTRAM_SCORE_FLOOR`), PNBP tercapai, surplus EBITDA, opini WTP, bebas kasus hukum, persyaratan lain; cetak **Lampiran IV** (surat pernyataan) |
| Pasal 19 & 21: perubahan (dasar huruf a/b/c, usul ≤ 30 September, tanda tangan ≤ 1 Desember) dan adendum 2025 | Versi perubahan/adendum menandai IK baru, diubah, dan **target turun**; IK yang targetnya turun dibatasi capaian maksimum 100% (Pasal 19 ayat 8); versi lama otomatis berstatus "digantikan" |

Kode inti: `App\Services\BluContractService`, `App\Models\PerformanceContract`, `App\Filament\Pages\CapaianKinerjaBlu`, `App\Http\Controllers\BluPrintController`, `resources/views/blu/`. Istilah tampilan (perspektif, menu) diatur kamus `terms.blu` di `config/dhastram.php` (`DHASTRAM_TERMS=blu`).

**Instalasi di Laragon → [PANDUAN-LARAGON.md](PANDUAN-LARAGON.md)** · **Instalasi di cPanel → [PANDUAN-CPANEL.md](PANDUAN-CPANEL.md)**

## Teknologi

| Lapisan | Komponen |
|---|---|
| Runtime | PHP 8.3+, **Laravel 13**, Livewire 4 |
| Antarmuka | **Filament v5** (panel `/admin`), stylesheet statis `public/css/arah.css` (tanpa Node.js) |
| Basis data | **MySQL 8 / MariaDB 10.6+** (SQLite untuk test) |
| Konteks | `DhastramContext` (sesi + tabel `user_contexts`) & global scope `BelongsToDhastramContext` menyaring setiap query per satker BLU/perioda |
| Excel | OpenSpout: import, template kosong, dan ekspor data perioda |
| Audit | `audit_logs` mencatat buat/ubah/hapus (`DHASTRAM_AUDIT_ENABLED`) |
| Jadwal | `rucibsc:reminders` harian (pengingat siklus input → verifikasi → persetujuan) |

## Multi-satker BLU

- **Super Admin** mengelola semua satker BLU, peran, dan semua akun.
- **Company Admin** mengelola pengguna dan penugasan peran **hanya di satker BLU yang sedang aktif**. Akun yang juga terdaftar di satker BLU lain hanya bisa diubah Super Admin (mencegah reset sandi lintas satker BLU).
- Peran diberikan per satker BLU (`role_user.company_id`); satu orang boleh punya peran di beberapa satker BLU dan berpindah lewat **Pilih Satker BLU & Perioda**.

## Import data satker BLU (menu Administrasi › Import Data)

1. Pilih satker BLU & perioda tujuan.
2. Unduh **template kosong** atau **ekspor data perioda saat ini**.
3. Isi sheet yang diperlukan: Jati Diri, Nilai Inti, Tema, Sasaran, Relasi Sasaran, KPI (Indikator Kinerja), Target, Realisasi, Inisiatif. Sheet yang tidak ada di file tidak diubah.
4. **Pratinjau** dulu (tidak menyimpan apa pun), lalu **Proses Import**.

Kode (sasaran, Indikator Kinerja, tema, inisiatif) menjadi kunci, jadi upload ulang memperbarui data, bukan menduplikasi. Baris yang salah dilewati dan dilaporkan dengan nomor barisnya. Angka boleh format Indonesia (`11.098.665`, `6,62`). Realisasi masuk sebagai **draf**, kecuali pengguna punya izin persetujuan dan mencentang "langsung setujui". Import terakhir sebuah perioda bisa **dibatalkan** (nilai sebelumnya dipulihkan). Izin: `import.manage`.

## Modul

- **Umum:** Beranda, Pusat Kendali Eksekutif, Alur Kerja, Peta Strategi, Laporan (+ Excel), Pilih Satker BLU & Perioda.
- **Ekosistem:** Pemindaian Lingkungan, PESTEL, Analisis Industri, Pasar & Pelanggan, Pesaing, Regulasi, Pemangku Kepentingan, Isu Strategis, SWOT, TOWS.
- **Jati Diri:** visi, misi, nilai inti, budaya, kapabilitas.
- **Kinerja:** **Kontrak Kinerja**, **Capaian & Insentif**, Tema, Pilihan Strategis, Sasaran, Relasi Sasaran, Indikator Kinerja, Target, Realisasi (draf → terverifikasi → disetujui/terkunci, atau dikembalikan), Tinjauan Kinerja.
- **Perbaikan:** Portofolio & Inisiatif Strategis, Aktivitas, Milestone, Anggaran, Temuan, Tindakan Korektif.
- **Kesinambungan:** Kematangan Manajemen Strategi, Ketergantungan, Institusionalisasi.
- **Administrasi:** Satker BLU, Pengguna, Peran, Penugasan Peran, Import Data, Log Audit.

Capaian IK: polaritas naik = realisasi ÷ target × 100, turun = target ÷ realisasi × 100, dibatasi capaian maksimum per IK (bawaan 120; 100 bila target diturunkan lewat perubahan kontrak); hijau ≥ 100, kuning ≥ 80. Agregasi jumlah, rata-rata, atau terakhir.

## Peran bawaan

`super-admin`, `company-admin`, `strategy-office`, `management`, `pimpinan`, `keuangan`, `kepala-unit`, `kpi-owner`, `initiative-pic`, `reviewer`, `pengawas`, `admin-sistem`, `editor-ekosistem`, `editor-jatidiri`, `pelaksana`, `viewer`. Isi izin peran berlaku global dan hanya diubah Super Admin.

## Data contoh

Di luar produksi (atau bila `RUCI_SEED_DEMO=true`) seeder memuat satu satker BLU contoh:

| Kode | Satker BLU | Isi |
|---|---|---|
| UINSUKA | UIN Sunan Kalijaga Yogyakarta (BLU pendidikan, Pembina Teknis Ditjen Pendidikan Islam Kemenag) | Perioda 2025 (ditutup): angka keuangan dari LK BLU Audited TA 2025 (PNBP Rp219,5 M = 99,48% target, EBITDA ±Rp71,9 M, opini WTP), target layanan dari Perjanjian Kinerja 2025, gambaran kelembagaan dari RSB 2020–2024; Kontrak Kinerja awal + adendum Pasal 21. Perioda 2026 (berjalan): proyeksi Triwulan I–III, Kontrak Kinerja 2026 dan usulan perubahan (Pasal 19 ayat 1 huruf c). 12 sasaran, 25 Indikator Kinerja (13 layanan, 10 keuangan, 2 penugasan), 6 inisiatif |

Akun: Super Admin `admin@rucibsc.local` / `Ruci@BSC2026` (ubah lewat `RUCI_ADMIN_PASSWORD`). Akun contoh, kata sandi `Contoh-2026!`:
`uinsuka.rektor` (Pemimpin BLU), `uinsuka.wr2` (keuangan), `uinsuka.wr1`, `uinsuka.wr3`, `uinsuka.lppm`, `uinsuka.lpm`, `uinsuka.bisnis`, `uinsuka.sdm`, `uinsuka.ti`, `uinsuka.perencanaan` (strategy office), `uinsuka.dewas` (Dewan Pengawas), `uinsuka.analis`, `uinsuka.admin` (@contoh.local). Nama dan NIP pejabat sebenarnya sengaja tidak dipakai; semua penandatangan bertanda "(contoh)".

**Peringatan data demo.** Data contoh adalah DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada dokumen publik UIN Sunan Kalijaga (kolom `companies.demo_source`); rincian triwulanan, Kontrak Kinerja, realisasi indikator layanan, dan seluruh data 2026 (proyeksi) adalah simulasi, bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan. Peringatan tampil sebagai pita di setiap halaman (`App\Support\DemoNotice`), di halaman depan & login, di setiap cetakan Kontrak Kinerja (tanda air DATA DEMO), dan di sheet Petunjuk template Excel.

Memuat demo di server yang sudah berjalan: `php artisan rucibsc:demo UINSUKA` (alias `rucibsc:demo-uin`). Kata sandi akun contoh dibuat acak dan ditampilkan (atau tentukan dengan `--password=`). Bila satker itu sudah punya perioda 2025/2026, tambahkan `--reset`.

Situs demo/promosi: `RUCI_GUEST_LOGIN=true` menampilkan tombol **Masuk sebagai Tamu (lihat-lihat)** di halaman login (rute `/masuk-tamu`, menerima `?perusahaan=KODE` dan `?ke=kenali|panduan|implementasi|workshop`). Rute `/` menjadi halaman depan promosi (`LandingController`, `resources/views/landing.blade.php`) dengan daftar data demo dari `App\Support\DemoCatalog`; bila akses Tamu mati, `/` diarahkan ke `/admin`. Akun Tamu hanya punya izin lihat.

Sebelum dipakai sungguhan: `php artisan rucibsc:hapus-contoh` menghapus semua satker BLU dan akun contoh, lalu ganti sandi Super Admin.

## Perintah penting

| Perintah | Fungsi |
|---|---|
| `php artisan dhastram:doctor` | Cek PHP, ekstensi, database, izin, peran, data contoh |
| `php artisan dhastram:create-admin` | Membuat Super Admin baru |
| `php artisan db:seed --class=DhastramRolePermissionSeeder` | Memperbarui izin & peran (aman diulang) |
| `php artisan rucibsc:demo UINSUKA [--reset]` | Memuat demo UIN Sunan Kalijaga (2025 + proyeksi 2026) |
| `php artisan rucibsc:hapus-contoh` | Menghapus satker BLU & akun contoh |
| `php artisan rucibsc:reminders --date=2026-11-03` | Uji pengingat |
| `php vendor/bin/phpunit` | Menjalankan test otomatis |

## Produksi (cPanel / VPS)

Langkah lengkap untuk cPanel (dengan atau tanpa Terminal) ada di [PANDUAN-CPANEL.md](PANDUAN-CPANEL.md).


`APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`; document root ke `public/`; cron `* * * * * php /path/ruci-bsc/artisan schedule:run`.
Di produksi `DatabaseSeeder` hanya membuat izin, peran, dan pertanyaan kematangan. Buat Super Admin dengan `dhastram:create-admin`, tambahkan satker BLU (isi identitas BLU & penandatangan), lalu upload datanya lewat Import Data.
Rilis: `composer install --no-dev --optimize-autoloader` → `php artisan migrate --force` → `php artisan optimize` → `php artisan dhastram:doctor`.
