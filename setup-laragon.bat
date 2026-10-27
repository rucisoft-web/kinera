@echo off
REM ============================================================
REM  Ruci BSC Professional - instalasi otomatis di Laragon (Windows)
REM  Jalankan dari Terminal Laragon:  cd C:\laragon\www\bsc-blu  lalu  setup-laragon.bat
REM ============================================================
setlocal
cd /d "%~dp0"
echo.
echo [1/7] Memeriksa PHP...
php -r "exit(version_compare(PHP_VERSION,'8.3.0','>=')?0:1);"
if errorlevel 1 (
  echo   PHP harus versi 8.3 atau lebih baru. Laragon: Menu ^> PHP ^> Version.
  php -v
  goto :fail
)
php -r "foreach(['intl','pdo_mysql','mbstring','zip','gd','fileinfo','openssl'] as $e) if(!extension_loaded($e)) {echo '  Ekstensi belum aktif: '.$e.PHP_EOL; $x=1;} exit($x??0);"
if errorlevel 1 (
  echo   Aktifkan ekstensi di Laragon: Menu ^> PHP ^> Extensions, lalu jalankan ulang.
  goto :fail
)

echo [2/7] Menyiapkan file .env...
if not exist .env copy .env.example .env >nul

if not exist vendor\autoload.php (
  echo [2b] Folder vendor tidak ada - menjalankan composer install...
  call composer install --no-interaction
  if errorlevel 1 goto :fail
)
php artisan key:generate --force

echo [3/7] Membuat database bsc_blu (MySQL root tanpa sandi)...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS bsc_blu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
  echo   Gagal membuat database. Pastikan MySQL Laragon sudah Start, atau buat manual lewat HeidiSQL.
  goto :fail
)

echo [4/7] Membuat tabel dan memuat data contoh (Diamond Food, NLP, KSB)...
set BSCBLU_SEED_DEMO=true
php artisan migrate:fresh --seed --force
if errorlevel 1 goto :fail

echo [5/7] Menyalin aset Filament...
php artisan filament:assets

echo [6/7] Membersihkan cache...
php artisan optimize:clear

echo [7/7] Pemeriksaan konfigurasi...
php artisan bscblu:doctor

echo.
echo ============================================================
echo  SELESAI. Buka  http://bsc-blu.test/admin
echo  (klik Reload di Laragon bila virtual host belum aktif)
echo  Super admin  : admin@bscblu.local / Ruci@BSC2026
echo  Akun contoh  : dfi.admin@contoh.local dll. (sandi Contoh-2026!)
echo ============================================================
goto :eof

:fail
echo.
echo Instalasi berhenti. Lihat PANDUAN-LARAGON.md bagian "Masalah umum".
exit /b 1
