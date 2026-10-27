<?php

namespace Database\Seeders;

use App\Models\MaturityQuestion;
use Illuminate\Database\Seeder;

/** Kuesioner kematangan manajemen strategi (5 fase x 5 pertanyaan, skala 1-5). */
class MaturityQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $q = [
            'brahman' => [
                ['Pemindaian lingkungan', 'Organisasi memindai lingkungan eksternal (PESTEL, industri, pasar) secara berkala dan terdokumentasi.'],
                ['Analisis pesaing', 'Data pesaing (pangsa pasar, harga, kinerja, ulasan) dipantau rutin dan dipakai untuk keputusan harga & penjualan.'],
                ['Pemahaman pelanggan', 'Segmen pelanggan, kebutuhan, dan kontribusinya dianalisis dengan data.'],
                ['Isu strategis', 'Isu strategis diprioritaskan (urgensi × dampak) dan memiliki penanggung jawab.'],
                ['Regulasi & pemangku kepentingan', 'Kewajiban regulasi dan harapan pemangku kepentingan dipetakan dan dipantau kepatuhannya.'],
            ],
            'atman' => [
                ['Visi & misi', 'Visi dan misi tertulis, disetujui pemilik, dan dipahami kepala unit.'],
                ['Nilai inti', 'Nilai inti diterjemahkan menjadi perilaku kerja yang dapat diamati.'],
                ['Kapabilitas', 'Kapabilitas inti dan kesenjangannya teridentifikasi beserta rencana penutupan.'],
                ['Proposisi nilai', 'Proposisi nilai perusahaan jelas dan konsisten dikomunikasikan.'],
                ['Keselarasan budaya', 'Budaya kerja mendukung strategi (komunikasi dua arah, disiplin, kerja tim).'],
            ],
            'karma_phala' => [
                ['Sasaran strategis', 'Sasaran strategis disusun dalam 4 perspektif dengan hubungan sebab-akibat (peta strategi).'],
                ['KPI & target', 'Setiap sasaran memiliki KPI terukur dengan target bulanan dan pemilik.'],
                ['Realisasi tepat waktu', 'Realisasi KPI diinput tepat waktu dan diverifikasi dengan sumber data (sistem operasional, laporan keuangan).'],
                ['Tinjauan kinerja', 'Tinjauan kinerja rutin (mingguan/bulanan) menghasilkan keputusan dan tindak lanjut.'],
                ['Pelaporan keuangan', 'Laporan laba rugi, arus kas, dan neraca tersedia setiap bulan.'],
            ],
            'punarbhawa' => [
                ['Portofolio inisiatif', 'Inisiatif strategis dipilih berdasarkan nilai dan upaya, terkait dengan sasaran.'],
                ['Pemantauan progres', 'Progres aktivitas dan milestone inisiatif diperbarui minimal mingguan.'],
                ['Anggaran inisiatif', 'Anggaran inisiatif direncanakan dan realisasinya dicatat.'],
                ['Temuan & koreksi', 'Temuan dari tinjauan ditindaklanjuti dengan tindakan korektif bertenggat dan PIC.'],
                ['Penutupan temuan', 'Temuan ditutup hanya setelah tindakan korektif selesai dan terbukti efektif.'],
            ],
            'moksa' => [
                ['Pelembagaan ritme', 'Ritme manajemen (laporan mingguan, rapat bulanan, rapat pengawas) berjalan konsisten.'],
                ['Kebijakan & SOP', 'Praktik baik dibakukan menjadi kebijakan/SOP dan disosialisasikan.'],
                ['Sistem pendukung', 'Sistem (aplikasi operasional, Ruci BSC) dipakai sebagai alat operasional, bukan sekadar laporan.'],
                ['Pembelajaran organisasi', 'Pelajaran dari keberhasilan/kegagalan didokumentasikan dan dipakai untuk perioda berikutnya.'],
                ['Ketergantungan & risiko', 'Ketergantungan strategis (dana, vendor, pemilik) dipetakan beserta mitigasinya.'],
            ],
        ];
        $sort = 0;
        foreach ($q as $phase => $items) {
            foreach ($items as [$dim, $text]) {
                MaturityQuestion::updateOrCreate(['phase' => $phase, 'dimension' => $dim], ['question' => $text, 'weight' => 1, 'sort' => ++$sort,
                    'guidance' => '1 = belum ada; 2 = ada tetapi tidak rutin; 3 = terdokumentasi & berjalan; 4 = terukur & dievaluasi; 5 = optimal & menjadi budaya']);
            }
        }
    }
}
