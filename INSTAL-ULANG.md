# Instal Ulang Kinera via Terminal (data bersih + demo UIN Sunan Kalijaga)

Panduan ini mengganti program lama dengan versi baru dari `kinera-cpanel.zip`, **mengosongkan seluruh database**, lalu memuat satu data demo: **UIN Sunan Kalijaga Yogyakarta** yang disusun dari Renstra 2025–2029.

> **Perhatian:** `migrate:fresh` menghapus SEMUA tabel dan data (instansi, pengguna, realisasi, log). Lakukan backup dulu. Folder `vendor` sudah ada di dalam zip, jadi `composer install` tidak perlu dijalankan.

Contoh di bawah memakai folder program `~/kinera`. Ganti bila folder Anda berbeda.

## 1. Backup

```bash
cd ~
cp kinera/.env ~/env-kinera-backup
mysqldump -u USER_DB -p NAMA_DB > ~/kinera-backup-$(date +%F).sql
```

Atau pakai *phpMyAdmin* › Export.

## 2. Upload dan ekstrak zip

Upload `kinera-cpanel.zip` ke folder home (`~`) lewat File Manager, lalu di Terminal:

```bash
cd ~
mv kinera kinera-lama              # simpan program lama
unzip -q kinera-cpanel.zip         # menghasilkan folder ~/kinera
cp ~/env-kinera-backup kinera/.env # pakai .env yang lama
cp -r kinera-lama/storage/app/public/. kinera/storage/app/public/ 2>/dev/null || true
```

## 3. Kosongkan database dan pasang ulang

```bash
cd ~/kinera
php artisan down
php artisan migrate:fresh --seed --force
php artisan kinera:create-admin
php artisan kinera:demo UINSUKA --force --password='Contoh-2026!'
php artisan storage:link
php artisan optimize
php artisan up
php artisan kinera:doctor
```

- `migrate:fresh --seed` menghapus semua tabel lalu membuat ulang tabel, peran, izin, dan pertanyaan kematangan.
- `kinera:create-admin` menanyakan nama, email, dan kata sandi Super Admin.
- `kinera:demo UINSUKA` memuat demo UIN Sunan Kalijaga: perioda 2025 (ditutup) dan 2026 (berjalan).
- Bila `php -v` bukan PHP 8.3, panggil PHP 8.3 langsung, misalnya `/opt/cpanel/ea-php83/root/usr/bin/php artisan ...`.
- Bila domain memakai Pilihan 2 (document root terkunci di `public_html`), salin ulang isi `kinera/public` ke `public_html` seperti di PANDUAN-CPANEL.md bagian 4.

## 4. Cek hasil

Buka `https://domain-anda/admin` lalu login sebagai Super Admin atau akun demo berikut (kata sandi `Contoh-2026!`):

| Akun | Peran |
|---|---|
| `uinsuka.rektor@contoh.local` | Rektor / Pemimpin BLU (penyetuju) |
| `uinsuka.wr2@contoh.local` | Wakil Rektor II (verifikator keuangan) |
| `uinsuka.perencanaan@contoh.local` | Bagian Perencanaan (strategy office) |
| `uinsuka.dewas@contoh.local` | Dewan Pengawas |
| `uinsuka.wr1`, `uinsuka.wr3`, `uinsuka.lppm`, `uinsuka.lpm`, `uinsuka.bisnis`, `uinsuka.sdm`, `uinsuka.ti`, `uinsuka.analis`, `uinsuka.admin` (@contoh.local) | Kepala unit, pelaksana, admin instansi |

Isi demo yang seharusnya tampil:

- **Peta Strategi:** 14 sasaran dalam empat perspektif (Pemangku Kepentingan di puncak, Keuangan sebagai penopang) dari 8 Sasaran Program Renstra.
- **Indikator Kinerja:** 39 IK (IKSP/IKSK Renstra plus indikator keuangan BLU), target 2026 sesuai Matriks Kinerja, realisasi Januari–September dengan status hijau, kuning, dan merah.
- **Inisiatif:** 9 inisiatif 2026 dari 7 strategi Renstra (transformasi akademik, kelembagaan menuju PTNBH, Kampus 2 Pajangan, SDM, riset, kewirausahaan, kemitraan), sehingga keempat kuadran Portofolio Inisiatif terisi.
- **Kontrak Kinerja BLU:** kontrak awal & adendum 2025, kontrak 2026, dan usulan perubahan target; Capaian & Insentif.
- **Lingkungan strategis:** pindai lingkungan, PESTEL, lima kekuatan, SWOT/TOWS, pemangku kepentingan, dan 13 regulasi (termasuk 8 rancangan dari Kerangka Regulasi Renstra).
- **Perbaikan & kesinambungan:** temuan, tindakan korektif, tinjauan kinerja, penilaian kematangan, ketergantungan, dan pelembagaan.
- **Dokumen:** Draf Renstra, Laporan, dua contoh Rencana Kerja (2026 & 2027), riwayat Import Data, dan jejak Log Audit.

Peringatan **DATA DEMO** tampil di setiap halaman dan cetakan: data hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak, bukan data resmi UIN Sunan Kalijaga.

## 5. Bersihkan

Setelah semuanya berjalan:

```bash
rm -rf ~/kinera-lama ~/kinera-cpanel.zip
```

Sebelum dipakai untuk data sungguhan, hapus data demo dengan `php artisan kinera:hapus-contoh --force`.
