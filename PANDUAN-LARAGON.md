# Panduan Instalasi Ruci BSC BLU di Laragon

Lokasi program: **`C:\laragon\www\bsc-blu`** · Alamat: **http://bsc-blu.test/admin**

## 1. Prasyarat

| Komponen | Ketentuan | Cara di Laragon |
|---|---|---|
| PHP | **8.3 atau lebih baru** (64-bit, NTS) | Menu Laragon › PHP › Version. Bila belum ada 8.3, unduh *PHP 8.3 VS16 x64 Non Thread Safe* (zip) dari windows.php.net, ekstrak ke `C:\laragon\bin\php\php-8.3.x`, lalu pilih versinya. |
| Ekstensi PHP | `intl`, `pdo_mysql`, `mbstring`, `zip`, `gd`, `fileinfo`, `openssl`, `bcmath` | Menu › PHP › Extensions — centang yang belum aktif (terutama **intl** dan **zip**). |
| MySQL / MariaDB | MySQL 8 atau MariaDB 10.6+ | Bawaan Laragon. Klik **Start All**. User `root` tanpa kata sandi. |
| Composer | Wajib (folder `vendor` tidak ikut di repo) | Bawaan Laragon. |
| Node.js | Tidak dibutuhkan | Tampilan memakai stylesheet statis (`public/css/arah.css`). |

## 2. Ambil program

```bat
cd C:\laragon\www
git clone https://github.com/rucisoft-web/bsc-blu.git
```

Klik **Reload** pada Laragon. Laragon otomatis membuat virtual host `bsc-blu.test` yang mengarah ke folder `public`.

## 3. Instalasi

### Cara A (disarankan): skrip otomatis

Buka **Terminal** Laragon, lalu:

```bat
cd C:\laragon\www\bsc-blu
setup-laragon.bat
```

Skrip melakukan: cek PHP & ekstensi → `.env` → `composer install` (bila perlu) → `key:generate` → membuat database `ruci_bsc` (utf8mb4) → `migrate:fresh --seed` dengan data contoh → `filament:assets` → `optimize:clear` → `rucibsc:doctor`.

### Cara B: manual

```bat
cd C:\laragon\www\bsc-blu
copy .env.example .env
composer install
php artisan key:generate
mysql -u root -e "CREATE DATABASE ruci_bsc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate --seed
php artisan filament:assets
php artisan optimize:clear
php artisan rucibsc:doctor
```

Data contoh dimuat bila `APP_ENV` bukan `production` atau `RUCI_SEED_DEMO=true` di `.env`.

> Menjalankan ulang Cara A mengembalikan database ke data contoh (**semua perubahan hilang**).

## 4. Masuk ke aplikasi

Buka **http://bsc-blu.test/admin**

| Akun | Email | Kata sandi |
|---|---|---|
| Super Admin | `admin@rucibsc.local` | `Ruci@BSC2026` (atau nilai `RUCI_ADMIN_PASSWORD`) |
| Admin Satker UIN Sunan Kalijaga (contoh) | `uinsuka.admin@contoh.local` | `Contoh-2026!` |
| Rektor / Pemimpin BLU (contoh) | `uinsuka.rektor@contoh.local` | `Contoh-2026!` |
| Admin NLP / KSB (fiktif) | `nlp.admin@contoh.local`, `ksb.admin@contoh.local` | `Contoh-2026!` |

Daftar lengkap akun contoh ada di README.md. Kata sandi baru minimal 10 karakter, mengandung huruf dan angka. Login dibatasi 5 percobaan per menit.

## 5. Menambah satker BLU sungguhan

1. Login sebagai Super Admin › **Administrasi › Satker BLU** › tambah satker BLU (lengkapi identitas BLU & penandatangan), lalu buat perioda (misalnya Tahun 2026).
2. **Pilih Satker BLU & Perioda** › pilih satker baru.
3. **Administrasi › Import Data** › unduh template › isi di Excel › **Pratinjau** › **Proses Import**.
4. Tambahkan admin satker di **Pengguna** dengan peran *Admin Satker* untuk satker itu. Admin tersebut bisa menambah pengguna lain di satkernya sendiri.

Bila data contoh tidak dibutuhkan lagi: `php artisan rucibsc:hapus-contoh`.

## 6. Pengingat otomatis (opsional di komputer lokal)

- Saat uji coba: `php artisan schedule:work` (biarkan jendela terbuka).
- Permanen di Windows: Task Scheduler › Create Task › Trigger tiap 1 menit › Action: `C:\laragon\bin\php\php-8.3.x\php.exe` dengan argumen `C:\laragon\www\bsc-blu\artisan schedule:run`.
- Uji manual: `php artisan rucibsc:reminders --date=2026-11-03`.

## 7. Perintah penting

| Perintah | Fungsi |
|---|---|
| `php artisan rucibsc:doctor` | Pemeriksaan PHP, ekstensi, database, izin & peran |
| `php artisan rucibsc:create-admin` | Membuat Super Admin baru |
| `php artisan db:seed --class=BscRolePermissionSeeder` | Memperbarui izin & peran (aman diulang) |
| `php artisan rucibsc:hapus-contoh` | Menghapus satker BLU & akun contoh |
| `php artisan rucibsc:reminders` | Kirim pengingat siklus hari ini |

## 8. Masalah umum

| Gejala | Solusi |
|---|---|
| `Your PHP version (8.1…) does not satisfy…` / halaman putih | Ganti PHP ke 8.3+ (lihat Prasyarat). |
| `Class "Collator" not found` / error `intl` | Aktifkan ekstensi **intl**, restart Laragon. |
| Upload Excel gagal / error `ZipArchive` | Aktifkan ekstensi **zip**. |
| `SQLSTATE[HY000] [1049] Unknown database 'ruci_bsc'` | Buat database dulu (langkah 3). |
| `SQLSTATE[HY000] [2002]` | MySQL belum jalan — klik **Start All**. |
| `bsc-blu.test` tidak terbuka | Klik **Reload** di Laragon; pastikan *Menu › Preferences › Auto virtual hosts* aktif. Alternatif: `php artisan serve` lalu buka http://127.0.0.1:8000/admin. |
| Tampilan tanpa gaya | Jalankan `php artisan filament:assets`. |
| `419 Page Expired` | Hapus cookie situs / `php artisan optimize:clear`. |
