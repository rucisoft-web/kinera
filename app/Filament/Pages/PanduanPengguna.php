<?php

namespace App\Filament\Pages;

use App\Support\Terms;
use Filament\Pages\Page;

/** Panduan pemakaian Kinera per peran, dengan tangkapan layar. */
class PanduanPengguna extends Page
{
    protected static ?string $title = 'Panduan Pengguna';

    protected static ?string $navigationLabel = 'Panduan Pengguna';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'panduan-pengguna';

    protected string $view = 'filament.pages.panduan-pengguna';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected function getViewData(): array
    {
        $c = config('bsc.cycle');
        $eko = Terms::get('brahman');
        $jati = Terms::get('atman');

        return ['roles' => [
            'semua' => [
                'label' => 'Semua pengguna',
                'who' => 'Dasar-dasar yang perlu dikuasai setiap pengguna sebelum masuk ke tugas perannya.',
                'rhythm' => [],
                'steps' => [
                    ['Masuk dan mengganti kata sandi', 'Buka alamat Kinera instansi Anda, masukkan email dan kata sandi dari Admin Sistem. Pada login pertama Anda diminta mengganti kata sandi (minimal 10 karakter, huruf dan angka). Ganti kata sandi atau nomor telepon kapan saja lewat menu Profil di pojok kanan atas.', null],
                    ['Memilih instansi dan perioda', 'Kotak di kiri atas (misalnya "UINSUKA · Tahun 2026") menunjukkan instansi dan perioda yang sedang Anda lihat. Klik kotak itu untuk berpindah. Semua menu, angka, dan laporan selalu mengikuti pilihan ini. Perioda berwarna hijau sedang berjalan; perioda kuning sudah ditutup.', 'p:adm-konteks'],
                    ['Membaca Beranda', 'Beranda adalah halaman pertama setiap orang: skor strategis, jumlah IK merah, inisiatif terlambat, temuan terbuka, dan bagian "Tugas saya" yang berisi realisasi yang harus diisi, persetujuan yang menunggu, aktivitas jatuh tempo, serta tindakan korektif milik Anda.', 'p:kepala-beranda'],
                    ['Memahami warna status', 'Hijau berarti skor ≥ 100 (target tercapai), kuning 80–99 (perlu perhatian), merah di bawah 80 (kritis), abu-abu belum ada realisasi. Skor dihitung otomatis dari realisasi dibanding target, memperhitungkan arah IK (makin besar atau makin kecil makin baik).', null],
                    ['Membaca Peta Strategi', 'Menu Peta Strategi menampilkan semua sasaran dalam empat perspektif beserta panah sebab-akibatnya. Warna kartu mengikuti skor terbaru. Klik kartu untuk melihat IK di balik sasaran itu.', 'k:peta-strategi'],
                    ['Notifikasi dan pencarian', 'Ikon lonceng di kanan atas berisi pengingat (misalnya realisasi belum diisi, realisasi dikembalikan). Kotak "Cari" menemukan IK, sasaran, atau inisiatif dengan cepat.', null],
                ],
            ],
            'komisaris' => [
                'label' => 'Pengawas (APIP / Dewan Pengawas)',
                'who' => 'Peran Pengawas: melihat seluruh data, memberi catatan pengawasan pada tinjauan, dan membuat temuan. Tidak mengubah angka kinerja.',
                'rhythm' => ['Bulanan: membaca Pusat Kendali setelah tanggal '.$c['approve_day'], 'Kuartalan: rapat gabungan pimpinan & pengawas', 'Kapan saja: catatan pengawasan dan temuan'],
                'steps' => [
                    ['Memantau Pusat Kendali Eksekutif', 'Satu layar berisi skor strategis s.d. bulan terakhir, status semua IK, skor per perspektif, inisiatif terlambat, temuan terbuka, dan tren bulanan. Mulailah dari angka merah: klik untuk melihat sasaran dan IK penyebabnya.', 'k:pusat-kendali'],
                    ['Membaca tinjauan kinerja', 'Menu Tinjauan Kinerja berisi notulen rapat manajemen bulanan dan rapat pengawas: ringkasan, keputusan, tindak lanjut, jumlah temuan, dan jumlah catatan pengawas.', 'p:kom-tinjauan'],
                    ['Memberi catatan pengawasan', 'Buka sebuah tinjauan, pilih tab "Catatan pengawasan", lalu tambah catatan. Catatan tersimpan atas nama Anda dan terlihat oleh pimpinan pada tinjauan tersebut.', null],
                    ['Membuat temuan pengawasan', 'Bila menemukan masalah yang perlu ditindaklanjuti, buat temuan di menu Temuan (atau tab "Temuan dari tinjauan ini"). Tetapkan tingkat dan PIC; pimpinan yang akan menyusun tindakan korektif dan menutup temuan.', 'p:dir-temuan'],
                    ['Mengunduh laporan', 'Menu Laporan menyajikan kartu skor IK bulanan & YTD, capaian per perspektif, status inisiatif & anggaran, serta temuan & tindakan korektif. Gunakan tombol Unduh Excel atau Cetak untuk bahan rapat.', 'k:laporan'],
                ],
            ],
            'direksi' => [
                'label' => 'Pimpinan instansi',
                'who' => 'Peran Pimpinan: memimpin siklus kinerja, menyetujui realisasi, memutuskan tindakan korektif, dan menutup temuan.',
                'rhythm' => ['Harian/mingguan: Beranda dan Pusat Kendali', 'Tanggal '.$c['approve_day'].': menyetujui realisasi bulan lalu', 'Tanggal '.$c['meeting_day'].': rapat manajemen bulanan', 'Kuartalan: tinjau portofolio inisiatif'],
                'steps' => [
                    ['Menyetujui realisasi IK', 'Buka Realisasi IK. Ikon gembok berarti Setujui: angka dikunci dan skor dihitung ulang. Ikon panah balik berarti Kembalikan ke penanggung jawab IK dengan alasan. Untuk banyak baris sekaligus, centang lalu pilih "Setujui terpilih". IK yang wajib verifikasi Keuangan baru bisa disetujui setelah diverifikasi Keuangan.', 'p:dir-setujui'],
                    ['Memimpin rapat dari Pusat Kendali', 'Buka Pusat Kendali di layar rapat. Bahas sasaran merah dan kuning, inisiatif terlambat, dan temuan terbuka. Keputusan dicatat sebagai Tinjauan Kinerja (judul, peserta, ringkasan, keputusan & arahan, tindak lanjut).', 'k:pusat-kendali'],
                    ['Mengelola portofolio inisiatif', 'Portofolio Inisiatif memetakan inisiatif menurut nilai dan upaya: Quick Win, Prioritas Strategis, Pengisi, dan Tinjau Ulang. Perhatikan label "terlambat" dan serapan anggaran untuk memutuskan percepatan atau penghentian.', 'k:portofolio'],
                    ['Menindaklanjuti dan menutup temuan', 'Di menu Temuan, pastikan setiap temuan punya tindakan korektif, PIC, dan tenggat. Setelah tindakan selesai dan terbukti, gunakan aksi "Tutup temuan" dengan catatan penutupan.', 'p:dir-temuan'],
                    ['Koreksi angka yang sudah terkunci', 'Hanya Pimpinan yang dapat membuka kembali realisasi yang sudah disetujui. Setiap koreksi tercatat di log audit, sehingga APIP dan pengawas dapat menelusurinya.', null],
                    ['Menandatangani & memantau Perjanjian/Kontrak Kinerja', 'Menu Kontrak Kinerja berisi perjanjian kinerja tahunan, yaitu kontrak awal dan setiap perubahan/adendum (versi). Buka versi yang berlaku, gunakan menu Cetak untuk Kontrak Kinerja (Lampiran I), Berita Acara (Lampiran II), dan Surat Pernyataan (Lampiran IV). Khusus instansi berpola BLU, halaman Capaian & Insentif menunjukkan capaian per triwulan, indikator di bawah 65%, uji syarat insentif Pasal 14 ayat (4), dan tenggat PER-6/PB/2025.', null],
                ],
            ],
            'strategi' => [
                'label' => 'Kantor Strategi',
                'who' => 'Peran Strategy Office / Corporate Planning: menyusun dan memelihara perangkat strategi, mengoordinasikan siklus bulanan, dan menyiapkan bahan rapat.',
                'rhythm' => ['Awal perioda: menyusun '.$eko.', '.$jati.', sasaran, IK, target, inisiatif', 'Tanggal '.$c['reminder_day'].'–'.$c['meeting_day'].': memantau kelengkapan siklus', 'Kuartalan: memperbarui isu strategis dan risiko', 'Akhir tahun: penilaian kematangan dan perioda baru'],
                'steps' => [
                    ['Membaca lingkungan ('.$eko.')', 'Isi PESTEL, kekuatan industri, segmen pasar, pesaing, regulasi, isu strategis, lalu SWOT berbobot dan strategi TOWS. Inilah dasar pemilihan tema dan sasaran.', 'p:so-swot'],
                    ['Menyusun tema dan sasaran strategis', 'Buat tema strategis, lalu sasaran per perspektif dengan penanggung jawab dan bobot. Hubungkan sasaran lewat menu Relasi Sasaran agar panah sebab-akibat muncul di Peta Strategi.', 'p:so-sasaran'],
                    ['Mendefinisikan IK', 'Setiap IK punya nama, sasaran, satuan, arah (makin besar/kecil makin baik), cara agregasi YTD, frekuensi, pemilik, baseline, target tahunan, rumus, dan sumber data. Tandai IK yang wajib verifikasi Keuangan agar wajib diverifikasi Keuangan.', 'p:so-kpi'],
                    ['Menetapkan target bulanan', 'Menu Target IK berisi target per bulan. Untuk IK kuartalan atau semesteran cukup isi bulan jatuh temponya (Maret, Juni, dst.).', 'p:so-target'],
                    ['Mengisi data sekaligus lewat Excel', 'Menu Import Data menyediakan template Excel untuk seluruh perangkat BSC. Unduh template atau data perioda ini, isi atau ubah, unggah, periksa dengan Pratinjau, lalu Proses Import. Riwayat import tercatat.', 'p:so-impor'],
                    ['Mengawal siklus bulanan', 'Menu Alur Kerja menunjukkan berapa realisasi sudah terisi, terverifikasi, dan disetujui, serta yang dikembalikan atau terlambat. Ingatkan unit yang tertinggal sebelum rapat.', 'k:alur-kerja'],
                    ['Menyusun Perjanjian/Kontrak Kinerja & perubahannya', 'Lengkapi setiap Indikator Kinerja dengan aspek (layanan/keuangan/penugasan), status wajib, capaian maksimum, dan Definisi Operasional (definisi, rumus, sumber data, cara pengambilan data, metode pengujian, dokumen pendukung). Lalu buka Kontrak Kinerja → Susun Kontrak / Perubahan: potret indikator dan target trajektori diambil otomatis. Usulan dikirim paling lambat hari kerja terakhir Desember; perubahan paling lambat 30 September dengan dasar Pasal 19 ayat (1). Indikator yang targetnya diturunkan otomatis dibatasi capaian maksimum 100%.', null],
                ],
            ],
            'keuangan' => [
                'label' => 'Keuangan',
                'who' => 'Peran Keuangan: memverifikasi angka IK yang wajib verifikasi Keuangan terhadap laporan keuangan dan mengelola realisasi anggaran inisiatif.',
                'rhythm' => ['Tanggal '.$c['input_day'].': mengisi IK yang wajib verifikasi Keuangan miliknya', 'Tanggal '.$c['verify_day'].': memverifikasi semua IK yang wajib verifikasi Keuangan', 'Bulanan: memperbarui realisasi anggaran inisiatif'],
                'steps' => [
                    ['Memverifikasi realisasi IK yang wajib verifikasi Keuangan', 'Di Realisasi IK, kolom "Keu." menandai IK yang wajib verifikasi Keuangan. Cocokkan nilainya dengan laporan keuangan, lalu klik Verifikasi (boleh dengan catatan) atau Kembalikan bila angka keliru. Status berubah menjadi Terverifikasi dan siap disetujui Pimpinan. Gunakan "Verifikasi terpilih" untuk banyak baris.', 'p:keu-verifikasi'],
                    ['Memperbarui anggaran inisiatif', 'Menu Anggaran Inisiatif memuat rencana dan realisasi biaya per pos (capex/opex) dan sumber dana. Serapan anggaran muncul di Portofolio Inisiatif dan laporan.', 'p:keu-anggaran'],
                    ['Menandai angka proyeksi', 'Bila angka bulan berjalan masih proyeksi (belum final), biarkan tanda "Proyeksi" aktif agar Pimpinan tahu angka itu belum berasal dari dokumen sumber.', null],
                    ['Memeriksa syarat insentif keuangan', 'Di Capaian & Insentif, pastikan indikator bertanda Target PNBP, Surplus EBITDA, dan Opini laporan keuangan sudah terisi dan terverifikasi. Ketiganya menjadi syarat huruf d, e, dan f Pasal 14 ayat (4) PER-6/PB/2025.', null],
                ],
            ],
            'unit' => [
                'label' => 'Kepala Unit & PIC',
                'who' => 'Peran Kepala Unit, Penanggung jawab IK, PIC Inisiatif, dan Pelaksana: mengisi realisasi IK miliknya, memperbarui progres inisiatif, dan menjalankan tindakan korektif.',
                'rhythm' => ['Mingguan: memperbarui progres aktivitas dan milestone', 'Tanggal '.$c['input_day'].': mengisi realisasi semua IK bulan lalu', 'Sesuai tenggat: menyelesaikan tindakan korektif'],
                'steps' => [
                    ['Melihat tugas di Beranda', 'Bagian "Tugas saya" menampilkan IK yang realisasinya belum diisi (beserta batas tanggal), realisasi yang dikembalikan untuk diperbaiki, aktivitas inisiatif jatuh tempo, serta tindakan korektif dan milestone milik Anda.', 'p:kepala-beranda'],
                    ['Mengisi realisasi IK', 'Klik IK dari Beranda atau buka Realisasi IK → Buat. Pilih IK dan bulan; target bulan itu tampil otomatis. Isi nilai realisasi, bukti/sumber angka, lampiran bila ada, dan catatan penjelasan bila meleset dari target.', 'p:kepala-input'],
                    ['Memperbaiki realisasi yang dikembalikan', 'Realisasi yang dikembalikan tampil merah di Beranda beserta alasannya. Buka, perbaiki nilai atau bukti, lalu simpan; statusnya kembali menunggu verifikasi/persetujuan.', null],
                    ['Memperbarui progres inisiatif', 'Di Aktivitas Inisiatif (atau di halaman inisiatif), ubah persentase progres dan status aktivitas Anda. Progres inisiatif dihitung otomatis: 70% dari aktivitas dan 30% dari milestone. Inisiatif dianggap terlambat bila progres tertinggal lebih dari 10 poin dari waktu berjalan.', 'p:kepala-aktivitas'],
                    ['Menjalankan tindakan korektif', 'Menu Tindakan Korektif berisi tindakan dari temuan rapat, lengkap dengan PIC dan tenggat. Perbarui progres hingga 100% dan status Selesai, sertakan bukti di keterangan.', 'p:kepala-korektif'],
                ],
            ],
            'admin' => [
                'label' => 'Admin Sistem',
                'who' => 'Peran Admin Sistem / Admin Instansi: mengelola akun pengguna, peran, instansi dan perioda, serta memantau log audit.',
                'rhythm' => ['Saat ada karyawan baru/mutasi: akun dan peran', 'Awal tahun: membuka perioda baru', 'Bulanan: memeriksa log audit dan akun nonaktif'],
                'steps' => [
                    ['Membuat dan menonaktifkan pengguna', 'Menu Pengguna: isi nama, email, jabatan, dan unit. Pengguna baru wajib mengganti kata sandi saat login pertama. Gunakan aksi "Reset sandi" bila pengguna lupa. Nonaktifkan akun karyawan yang keluar; jangan dihapus agar jejak audit tetap utuh.', 'p:adm-users'],
                    ['Menugaskan peran', 'Menu Penugasan Peran menghubungkan pengguna dengan peran di instansi tertentu (Pimpinan, Keuangan, Kepala Unit, Pengawas, dan lainnya). Satu orang boleh memiliki beberapa peran, termasuk di beberapa instansi dalam grup.', 'p:adm-peran'],
                    ['Mengelola instansi dan perioda', 'Menu Instansi memuat data instansi dan perioda analisisnya. Tandai satu perioda sebagai aktif (berjalan); perioda yang sudah selesai diubah menjadi ditutup.', 'p:adm-konteks'],
                    ['Memantau log audit', 'Log Audit mencatat siapa mengubah apa dan kapan, termasuk nilai sebelum dan sesudahnya. Gunakan untuk menelusuri koreksi angka setelah disetujui.', null],
                ],
            ],
        ]];
    }
}
