# Panduan Instalasi Kinera di Laragon

Lokasi program: **`C:\laragon\www\kinera`** · Alamat: **http://kinera.test/admin**

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
git clone https://github.com/rucisoft-web/kinera.git
```

Klik **Reload** pada Laragon. Laragon otomatis membuat virtual host `kinera.test` yang mengarah ke folder `public`.

## 3. Instalasi

### Cara A (disarankan): skrip otomatis

Buka **Terminal** Laragon, lalu:

```bat
cd C:\laragon\www\kinera
setup-laragon.bat
```

Skrip melakukan: cek PHP & ekstensi → `.env` → `composer install` (bila perlu) → `key:generate` → membuat database `kinera` (utf8mb4) → `migrate:fresh --seed` dengan data contoh → `filament:assets` → `optimize:clear` → `kinera:doctor`.

### Cara B: manual

```bat
cd C:\laragon\www\kinera
copy .env.example .env
composer install
php artisan key:generate
mysql -u root -e "CREATE DATABASE kinera CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate --seed
php artisan filament:assets
php artisan optimize:clear
php artisan kinera:doctor
```

Data contoh dimuat bila `APP_ENV` bukan `production` atau `KINERA_SEED_DEMO=true` di `.env`.

> Menjalankan ulang Cara A mengembalikan database ke data contoh (**semua perubahan hilang**).

## 4. Masuk ke aplikasi

Buka **http://kinera.test/admin**

| Akun | Email | Kata sandi |
|---|---|---|
| Super Admin | `admin@kinera.local` | `Kinera@2026` (atau nilai `KINERA_ADMIN_PASSWORD`) |
| Admin Satker UIN Sunan Kalijaga (contoh) | `uinsuka.admin@contoh.local` | `Contoh-2026!` |
| Rektor / Pemimpin BLU (contoh) | `uinsuka.rektor@contoh.local` | `Contoh-2026!` |
| Admin NLP / KSB (fiktif) | `nlp.admin@contoh.local`, `ksb.admin@contoh.local` | `Contoh-2026!` |

Daftar lengkap akun contoh ada di README.md. Kata sandi baru minimal 10 karakter, mengandung huruf dan angka. Login dibatasi 5 percobaan per menit.

## 5. Menambah satker BLU sungguhan

1. Login sebagai Super Admin › **Administrasi › Satker BLU** › tambah satker BLU (lengkapi identitas BLU & penandatangan), lalu buat perioda (misalnya Tahun 2026).
2. **Pilih Satker BLU & Perioda** › pilih satker baru.
3. **Administrasi › Import Data** › unduh template › isi di Excel › **Pratinjau** › **Proses Import**.
4. Tambahkan admin satker di **Pengguna** dengan peran *Admin Satker* untuk satker itu. Admin tersebut bisa menambah pengguna lain di satkernya sendiri.

Bila data contoh tidak dibutuhkan lagi: `php artisan kinera:hapus-contoh`.

## 6. Pengingat otomatis (opsional di komputer lokal)

- Saat uji coba: `php artisan schedule:work` (biarkan jendela terbuka).
- Permanen di Windows: Task Scheduler › Create Task › Trigger tiap 1 menit › Action: `C:\laragon\bin\php\php-8.3.x\php.exe` dengan argumen `C:\laragon\www\kinera\artisan schedule:run`.
- Uji manual: `php artisan kinera:reminders --date=2026-11-03`.

## 7. Perintah penting

| Perintah | Fungsi |
|---|---|
| `php artisan kinera:doctor` | Pemeriksaan PHP, ekstensi, database, izin & peran |
| `php artisan kinera:create-admin` | Membuat Super Admin baru |
| `php artisan db:seed --class=BscRolePermissionSeeder` | Memperbarui izin & peran (aman diulang) |
| `php artisan kinera:hapus-contoh` | Menghapus satker BLU & akun contoh |
| `php artisan kinera:reminders` | Kirim pengingat siklus hari ini |

## 8. Masalah umum

| Gejala | Solusi |
|---|---|
| `Your PHP version (8.1…) does not satisfy…` / halaman putih | Ganti PHP ke 8.3+ (lihat Prasyarat). |
| `Class "Collator" not found` / error `intl` | Aktifkan ekstensi **intl**, restart Laragon. |
| Upload Excel gagal / error `ZipArchive` | Aktifkan ekstensi **zip**. |
| `SQLSTATE[HY000] [1049] Unknown database 'kinera'` | Buat database dulu (langkah 3). |
| `SQLSTATE[HY000] [2002]` | MySQL belum jalan — klik **Start All**. |
| `kinera.test` tidak terbuka | Klik **Reload** di Laragon; pastikan *Menu › Preferences › Auto virtual hosts* aktif. Alternatif: `php artisan serve` lalu buka http://127.0.0.1:8000/admin. |
| Tampilan tanpa gaya | Jalankan `php artisan filament:assets`. |
| `419 Page Expired` | Hapus cookie situs / `php artisan optimize:clear`. |
