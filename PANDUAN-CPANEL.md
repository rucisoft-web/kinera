# Panduan Instalasi Ruci BSC BLU di cPanel

Panduan ini untuk hosting cPanel biasa (shared hosting atau VPS dengan cPanel). Ada dua jalur: **dengan Terminal/SSH** (lebih mudah) dan **tanpa Terminal** (memakai File Manager dan Cron Jobs).

Contoh di panduan memakai akun cPanel `userku`, folder program `/home/userku/bsc-blu`, dan alamat `https://bsc.domainanda.com`. Sesuaikan dengan milik Anda.

## 1. Prasyarat hosting

| Komponen | Ketentuan | Tempat di cPanel |
|---|---|---|
| PHP | **8.3 atau lebih baru** | *MultiPHP Manager* (pilih domain › PHP 8.3) atau *Select PHP Version* (CloudLinux) |
| Ekstensi PHP | `intl`, `pdo_mysql`, `mbstring`, `zip`, `gd`, `fileinfo`, `openssl`, `bcmath`, `ctype`, `tokenizer`, `xml`, `dom`, `curl` | *Select PHP Version › Extensions* (centang yang belum aktif, terutama **intl** dan **zip**) |
| Database | MySQL 8 atau MariaDB 10.6+ | *MySQL Databases* |
| Cron | Wajib (pengingat dan antrian notifikasi) | *Cron Jobs* |
| SSL | Disarankan | *SSL/TLS Status* › Run AutoSSL |

Bila PHP 8.3 belum tersedia, minta penyedia hosting mengaktifkannya. Laravel 13 tidak berjalan di PHP 8.2 ke bawah.

## 2. Buat database

1. Buka **MySQL Databases**.
2. *Create New Database*: misalnya `bsc_blu` (cPanel menjadikannya `userku_bsc_blu`).
3. *Add New User*: misalnya `bsc` (menjadi `userku_bsc`), dengan kata sandi kuat. Catat kata sandinya.
4. *Add User To Database*: pilih user dan database tadi › centang **ALL PRIVILEGES** › Make Changes.

## 3. Taruh program di server

Program diletakkan **di luar `public_html`** (misalnya `/home/userku/bsc-blu`), supaya file `.env` dan kode tidak bisa dibuka dari browser.

### Jalur A: dengan Terminal (cPanel › Terminal, atau SSH)

```bash
cd ~
git clone https://github.com/rucisoft-web/bsc-blu.git
cd bsc-blu
composer install --no-dev --optimize-autoloader
```

Repo bersifat privat, jadi `git clone` meminta username GitHub dan *personal access token* (bukan kata sandi). Alternatif: *Git Version Control* di cPanel › Create › isi Clone URL.

Bila perintah `composer` tidak ada:

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

Bila `php -v` di Terminal bukan 8.3, panggil PHP 8.3 langsung, misalnya `/opt/cpanel/ea-php83/root/usr/bin/php` (cPanel EasyApache) atau `/opt/alt/php83/usr/bin/php` (CloudLinux).

### Jalur B: tanpa Terminal (File Manager)

1. Pakai paket **`bsc-blu-cpanel.zip`** (sudah berisi folder `vendor` versi produksi, tidak perlu Composer).
2. *File Manager* › buka `/home/userku` › **Upload** zip tersebut.
3. Klik kanan zip › **Extract** ke `/home/userku`. Hasilnya folder `/home/userku/bsc-blu`.
4. Hapus file zip setelah diekstrak.

## 4. Arahkan domain ke folder `public`

### Pilihan 1 (disarankan): subdomain atau addon domain

1. *Domains* (atau *Subdomains*) › **Create A New Domain**, misalnya `bsc.domainanda.com`.
2. Matikan centang *Share document root* bila ada, lalu isi **Document Root**: `bsc-blu/public`.
3. Simpan. Aktifkan SSL untuk domain itu di *SSL/TLS Status*.

Untuk domain yang sudah ada: *Domains* › Manage › ubah Document Root menjadi `bsc-blu/public`.

### Pilihan 2: domain utama yang document root-nya terkunci di `public_html`

1. Pindahkan (atau salin) **seluruh isi** `bsc-blu/public` ke `public_html`, termasuk file tersembunyi `.htaccess` (di File Manager: *Settings* › centang *Show Hidden Files*).
2. Edit `public_html/index.php`, ganti tiga baris yang berisi `__DIR__.'/../` menjadi:

```php
if (file_exists($maintenance = __DIR__.'/../bsc-blu/storage/framework/maintenance.php')) {
require __DIR__.'/../bsc-blu/vendor/autoload.php';
$app = require_once __DIR__.'/../bsc-blu/bootstrap/app.php';
```

3. Setiap kali memperbarui program, salin ulang isi `bsc-blu/public` ke `public_html` (kecuali `index.php` yang sudah diedit).

> Jangan mengekstrak seluruh program langsung ke `public_html`, karena `.env` (berisi kata sandi database) bisa terbuka dari browser.

## 5. Isi file `.env`

Di folder `bsc-blu`, salin `.env.example` menjadi `.env` (File Manager: klik kanan › Copy, beri nama `.env`), lalu edit:

```dotenv
APP_NAME="Ruci BSC BLU"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://bsc.domainanda.com
APP_TIMEZONE=Asia/Jakarta

LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=userku_bsc_blu
DB_USERNAME=userku_bsc
DB_PASSWORD="kata-sandi-database"

SESSION_SECURE_COOKIE=true

# email notifikasi (opsional; pakai akun email dari cPanel › Email Accounts)
MAIL_MAILER=smtp
MAIL_HOST=mail.domainanda.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=bsc@domainanda.com
MAIL_PASSWORD="kata-sandi-email"
MAIL_FROM_ADDRESS="bsc@domainanda.com"
BSCBLU_NOTIFY_MAIL=true

# data contoh (UIN Sunan Kalijaga, data demo): biarkan false untuk pemakaian sungguhan
BSCBLU_SEED_DEMO=false
# situs demo/promosi: tombol "Masuk sebagai Tamu" (hanya lihat), lihat bagian 8
BSCBLU_GUEST_LOGIN=false
```

Biarkan `APP_KEY=` kosong; nilainya diisi otomatis oleh `php artisan key:generate` di langkah 6 (baris ini harus ada, kalau tidak muncul error *No APP_KEY variable was found*).

Catatan: di cPanel `DB_HOST` hampir selalu `localhost`. Kata sandi yang mengandung `#` atau spasi harus diberi tanda kutip.

## 6. Kunci aplikasi, tabel, dan Super Admin

### Dengan Terminal

```bash
cd ~/bsc-blu
php artisan key:generate --force
php artisan migrate --seed --force
php artisan bscblu:create-admin
php artisan optimize
php artisan bscblu:doctor
```

`migrate --seed` di produksi hanya membuat izin, peran, dan pertanyaan kematangan. Satker BLU contoh hanya dimuat bila `BSCBLU_SEED_DEMO=true`.

### Tanpa Terminal (memakai Cron Jobs sekali jalan)

Buka **Cron Jobs**, tambahkan perintah berikut satu per satu dengan jadwal *Once Per Minute* (`* * * * *`). Tunggu 1–2 menit sampai berjalan, lalu **hapus** cron tersebut sebelum menambah yang berikutnya.

1. `cd /home/userku/bsc-blu && php artisan key:generate --force > storage/logs/setup-1.txt 2>&1`
2. `cd /home/userku/bsc-blu && php artisan migrate --seed --force > storage/logs/setup-2.txt 2>&1`
3. `cd /home/userku/bsc-blu && php artisan bscblu:create-admin --name="Super Admin" --email=admin@domainanda.com --password='SandiSementara2026' > storage/logs/setup-3.txt 2>&1`
4. `cd /home/userku/bsc-blu && php artisan optimize > storage/logs/setup-4.txt 2>&1`

Periksa hasil tiap langkah di File Manager: `bsc-blu/storage/logs/setup-*.txt`. Setelah login pertama, **ganti kata sandi Super Admin** di menu profil karena kata sandi sementara tadi sempat tersimpan di daftar cron.

Bila `php` di cron bukan versi 8.3, ganti `php` dengan path lengkap PHP 8.3 (lihat bagian 3).

## 7. Cron permanen (wajib)

Di **Cron Jobs**, tambahkan satu cron yang dibiarkan selamanya, jadwal *Once Per Minute*:

```
cd /home/userku/bsc-blu && php artisan schedule:run >> /dev/null 2>&1
```

Cron ini menjalankan pengingat harian (07.30 WIB), antrian notifikasi, dan pembersihan notifikasi lama. Tidak perlu `queue:work` terpisah.

## 8. Mulai memakai

1. Buka `https://bsc.domainanda.com/admin`, login sebagai Super Admin.
2. **Administrasi › Satker BLU** › tambah satker BLU (isi juga bagian *Identitas BLU & penandatangan Kontrak Kinerja*) dan perioda (misalnya Tahun 2026).
3. **Pilih Satker BLU & Perioda** › pilih satker tersebut.
4. **Administrasi › Import Data** › unduh template › isi di Excel › **Pratinjau** › **Proses Import**.
5. **Administrasi › Pengguna** › buat admin satker (peran *Admin Satker* untuk satker itu). Admin tersebut bisa menambah pengguna lain di satkernya.

### Memuat data demo (untuk presentasi)

Tersedia satu demo lengkap: **UINSUKA** (UIN Sunan Kalijaga Yogyakarta, BLU pendidikan tinggi di bawah Kementerian Agama).

> **Peringatan:** isinya DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada Laporan Keuangan BLU Audited TA 2025, Perjanjian Kinerja 2025, dan RSB 2020–2024 UIN Sunan Kalijaga; rincian triwulanan, Kontrak Kinerja, realisasi indikator layanan, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi UIN Sunan Kalijaga, Kementerian Agama, maupun Kementerian Keuangan. Peringatan ini tampil otomatis di setiap halaman dan setiap cetakan.

```bash
cd ~/bsc-blu
php artisan bscblu:demo UINSUKA
```

Perintah ini membuat satker dengan perioda 2025 (ditutup) dan 2026 (berjalan, proyeksi), lengkap dengan peta strategi, 25 Indikator Kinerja beserta Definisi Operasional, **Kontrak Kinerja** (awal, adendum 2025, usulan perubahan 2026), capaian & syarat insentif, inisiatif, ekosistem, tinjauan, temuan, dan kematangan, serta akun `uinsuka.*` (@contoh.local). Kata sandi akun tersebut dibuat acak dan ditampilkan di layar; untuk menentukannya sendiri tambahkan `--password=SandiAnda123`.

Bila satker itu sudah punya perioda 2025 atau 2026 (misalnya dari import Excel sebelumnya), perintah menolak berjalan. Tambahkan `--reset --force` untuk menghapus data kedua perioda itu dan menggantinya dengan data demo. Lewat cron sekali jalan (seperti bagian 6): `cd /home/userku/bsc-blu && php artisan bscblu:demo UINSUKA --force --password=SandiAnda123 > storage/logs/demo.txt 2>&1`, lalu jalankan `php artisan optimize` dengan cara yang sama.

Sebelum dipakai sungguhan, hapus semua data contoh dengan `php artisan bscblu:hapus-contoh --force`.

### Situs demo untuk pengunjung (akses Tamu)

Untuk situs promosi seperti bsc.rucisoft.id, tambahkan baris ini di `.env`, lalu jalankan `php artisan optimize`:

```
BSCBLU_GUEST_LOGIN=true
```

Alamat utama (`https://domain-anda/`) lalu menjadi **halaman depan** seperti situs promosi: kenapa perlu Ruci BSC BLU, lima fase, kartu **data demo tahun berjalan** (klik untuk langsung masuk sebagai Tamu), panduan per peran dengan ritme bulanan, workshop, dan tanya jawab. Halaman login juga menampilkan **pilihan satker BLU contoh tahun berjalan**, tombol **Masuk sebagai Tamu (lihat-lihat)**, serta tautan *Kenapa perlu Ruci BSC?* dan *Panduan Ruci BSC*. Bila akses Tamu mati, alamat utama langsung ke halaman login seperti biasa. Pengunjung masuk tanpa kata sandi, bisa membuka semua menu (Beranda, Pusat Kendali, Peta Strategi, Indikator Kinerja, Kontrak Kinerja, Capaian & Insentif, inisiatif, ekosistem, tinjauan, laporan) dan berganti ke perioda 2025 lewat kotak konteks di kiri atas.

Akun Tamu hanya bisa melihat: tombol Buat, Ubah, Hapus, Impor, dan Ekspor tidak muncul, halaman administrasi (pengguna, peran, log audit, profil) tertutup, dan setiap upaya menyimpan data ditolak oleh sistem. Pilihan satker/perioda disimpan per sesi, jadi pengunjung yang bersamaan tidak saling mengganggu. Akun `tamu@demo.rucisoft.id` dan perannya dibuat otomatis saat tombol pertama kali diklik (alamat bisa diganti dengan `BSCBLU_GUEST_EMAIL`). Untuk mematikan, ubah menjadi `BSCBLU_GUEST_LOGIN=false` lalu `php artisan optimize`.

Grup menu **Panduan** berisi **Kenali Ruci BSC**, **Panduan Pengguna** (langkah per peran dengan tangkapan layar), **Panduan Implementasi** (rencana proyek implementasi, bisa dicetak/simpan PDF), dan **Workshop Ruci BSC** (rundown dan materi workshop sehari; jadwal Minggu pertama setiap bulan tampil otomatis). Menu **Kenali Ruci BSC** menjelaskan Ruci BSC BLU untuk Pemimpin BLU, Pejabat Pengelola, dan Dewan Pengawas, lengkap dengan diagram, tangkapan layar, dan ajakan ikut Workshop. Bagikan tautan `https://domain-anda/kenali` kepada calon peserta: bila akses Tamu aktif, pembaca langsung masuk ke halaman itu tanpa login. Tombol pendaftaran dan kontak Workshop diatur di `.env`:

```
BSCBLU_WORKSHOP_URL=https://rucisoft.id/workshop
BSCBLU_WORKSHOP_CONTACT="WhatsApp 08xx-xxxx-xxxx · workshop@rucisoft.id"
# opsional: nama narasumber tamu edisi Dewan Pengawas & Pejabat Pengelola BLU
# BSCBLU_WORKSHOP_SPECIAL_SPEAKER="Nama Narasumber, jabatan"
```

## 9. Memperbarui program

Dengan Terminal:

```bash
cd ~/bsc-blu
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan up
```

Tanpa Terminal: upload zip versi baru, ekstrak menimpa folder `bsc-blu` (zip tidak berisi `.env`, dan isi folder `storage` seperti log tidak terhapus), lalu jalankan `php artisan migrate --force` dan `php artisan optimize` lewat cron sekali jalan seperti bagian 6. Bila memakai Pilihan 2 di bagian 4, salin ulang isi `public` ke `public_html`.

Selalu **backup database** dulu (*phpMyAdmin* › Export, atau *Backup* di cPanel).

## 10. Masalah umum

| Gejala | Solusi |
|---|---|
| Error 500 tanpa keterangan | Lihat `bsc-blu/storage/logs/laravel-*.log`. Untuk sementara boleh `APP_DEBUG=true`, lalu kembalikan ke `false`. |
| `Your PHP version … does not satisfy` | Ubah PHP domain ke 8.3 di *MultiPHP Manager*, dan pastikan cron/Terminal juga memakai PHP 8.3. |
| `Class "Collator" not found` | Aktifkan ekstensi **intl**. |
| Upload Excel gagal / `ZipArchive` | Aktifkan ekstensi **zip**. Bila file besar ditolak, naikkan `upload_max_filesize` dan `post_max_size` di *MultiPHP INI Editor* (misalnya 20M). |
| `SQLSTATE[HY000] [1045] Access denied` | Periksa `DB_DATABASE`/`DB_USERNAME` memakai awalan `userku_` dan user sudah ditambahkan ke database dengan ALL PRIVILEGES. |
| `No application encryption key` | Jalankan `php artisan key:generate --force` (bagian 6). |
| Halaman tampil tanpa gaya / 404 untuk `/css/...` | Document root belum ke `bsc-blu/public`, atau isi `public` belum disalin lengkap ke `public_html`. |
| Semua halaman selain beranda 404 | File `.htaccess` dari `public` belum ikut tersalin (aktifkan *Show Hidden Files*). |
| `Permission denied` di `storage` | Atur izin folder `storage` dan `bootstrap/cache` menjadi 755 (File Manager › Change Permissions). |
| `419 Page Expired` saat login | `APP_URL` harus sama persis dengan alamat yang dibuka (https dan subdomainnya). Bila belum memakai SSL, hapus `SESSION_SECURE_COOKIE=true`. |
| Pengingat tidak terkirim | Pastikan cron `schedule:run` (bagian 7) aktif; coba `php artisan bscblu:reminders`. |
| Perubahan `.env` tidak berlaku | Jalankan `php artisan optimize:clear` lalu `php artisan optimize`. |
