<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;
use App\Models\Question;

class CriteriaQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. DATA KRITERIA (JURUSAN)
        // ==========================================
        $kriteria = [
            'ATPH' => Criteria::create(['kode_kriteria' => 'K01', 'nama_kriteria' => 'Agribisnis Tanaman Pangan dan Hortikultura (ATPH)']),
            'APHP' => Criteria::create(['kode_kriteria' => 'K02', 'nama_kriteria' => 'Agribisnis Pengolahan Hasil Pertanian (APHP)']),
            'AKL'  => Criteria::create(['kode_kriteria' => 'K03', 'nama_kriteria' => 'Akuntansi dan Keuangan Lembaga (AKL)']),
            'TKRO' => Criteria::create(['kode_kriteria' => 'K04', 'nama_kriteria' => 'Teknik Kendaraan Ringan Otomotif (TKRO)']),
            'TKJ'  => Criteria::create(['kode_kriteria' => 'K05', 'nama_kriteria' => 'Teknik Komputer dan Jaringan (TKJ)']),
        ];

        // ==========================================
        // 2. SOAL FASE 1: ANGKET LIKERT (Poin 1-5 ditangani di Frontend)
        // ==========================================
        $fase1Questions = [
            // Soal ATPH
            ['criteria_id' => $kriteria['ATPH']->id, 'teks_pertanyaan' => 'Saya lebih suka bekerja di lapangan terbuka dan berinteraksi dengan alam dibandingkan duduk di dalam ruangan.'],
            ['criteria_id' => $kriteria['ATPH']->id, 'teks_pertanyaan' => 'Saya tertarik mempelajari cara meningkatkan hasil panen perkebunan atau tanaman pangan.'],
            // Soal APHP
            ['criteria_id' => $kriteria['APHP']->id, 'teks_pertanyaan' => 'Saya sangat antusias bereksperimen mengubah bahan mentah pertanian menjadi produk makanan atau minuman baru yang bernilai jual.'],
            // Soal AKL
            ['criteria_id' => $kriteria['AKL']->id,  'teks_pertanyaan' => 'Saya adalah orang yang sangat teliti dalam mencatat setiap pemasukan dan pengeluaran keuangan pribadi.'],
            // Soal TKRO
            ['criteria_id' => $kriteria['TKRO']->id, 'teks_pertanyaan' => 'Saya merasa penasaran dan tertantang setiap kali melihat mesin kendaraan bermotor yang sedang dibongkar.'],
            // Soal TKJ
            ['criteria_id' => $kriteria['TKJ']->id,  'teks_pertanyaan' => 'Saya betah berlama-lama di depan komputer untuk mempelajari cara kerja software atau merakit komponen hardware.'],
        ];

        foreach ($fase1Questions as $q) {
            Question::create(array_merge($q, ['fase' => 1, 'tipe_opsi' => 'text']));
        }

        // ==========================================
        // 3. SOAL FASE 2: STUDI KASUS (Pilihan Ganda Berbobot)
        // Bobot: 1 (Sangat Tidak Relevan) s/d 5 (Sangat Relevan)
        // ==========================================
        
        // Kasus ATPH
        Question::create([
            'criteria_id' => $kriteria['ATPH']->id,
            'fase' => 2, 'tipe_opsi' => 'text',
            'teks_pertanyaan' => 'Jika Anda melihat lahan kosong di halaman belakang rumah atau sekolah, apa yang paling ingin Anda lakukan dengan lahan tersebut?',
            'opsi_jawaban' => [
                1 => 'Dibiarkan saja karena tidak ada hubungannya dengan saya.',
                2 => 'Dijadikan tempat meletakkan barang-barang bekas.',
                3 => 'Dibersihkan agar terlihat rapi dan tidak mengganggu pemandangan.',
                4 => 'Ditanami beberapa pohon hias agar terlihat rindang.',
                5 => 'Diolah dan ditanami bibit tanaman produktif seperti sayuran atau palawija.' // Bobot 5 untuk ATPH
            ],
        ]);

        // Kasus APHP
        Question::create([
            'criteria_id' => $kriteria['APHP']->id,
            'fase' => 2, 'tipe_opsi' => 'text',
            'teks_pertanyaan' => 'Saat musim panen raya, banyak buah-buahan yang harganya anjlok dan cepat busuk. Solusi apa yang paling menarik bagi Anda?',
            'opsi_jawaban' => [
                1 => 'Mengabaikannya karena itu risiko alam.',
                2 => 'Membeli buah tersebut sekadar untuk dikonsumsi sendiri.',
                3 => 'Membantu petani menjualnya ke pasar lain.',
                4 => 'Menyimpannya di lemari pendingin agar lebih awet.',
                5 => 'Mengolahnya menjadi selai, keripik, atau sirup, lalu mengemasnya dengan label yang menarik.' // Bobot 5 untuk APHP
            ],
        ]);

        // Kasus AKL
        Question::create([
            'criteria_id' => $kriteria['AKL']->id,
            'fase' => 2, 'tipe_opsi' => 'text',
            'teks_pertanyaan' => 'Anda terpilih menjadi bendahara di sebuah acara kepanitiaan sekolah. Bagaimana cara Anda mengelola dana kegiatan tersebut?',
            'opsi_jawaban' => [
                1 => 'Menyimpan uangnya di dompet dan mengingat-ingat saja jumlahnya.',
                2 => 'Mencatat pengeluaran besar saja agar tidak pusing.',
                3 => 'Mengumpulkan semua nota belanja tanpa membuat laporan tertulis.',
                4 => 'Membuat laporan sederhana di buku tulis setelah acara selesai.',
                5 => 'Membuat pembukuan detail (pemasukan, pengeluaran, saldo) secara rapi dan melampirkan seluruh bukti transaksi harian.' // Bobot 5 untuk AKL
            ],
        ]);

        // Kasus TKRO
        Question::create([
            'criteria_id' => $kriteria['TKRO']->id,
            'fase' => 2, 'tipe_opsi' => 'text',
            'teks_pertanyaan' => 'Ketika mengendarai motor, Anda mendengar suara kasar dari area mesin. Apa tindakan pertama yang Anda lakukan?',
            'opsi_jawaban' => [
                1 => 'Tetap mengendarainya sampai benar-benar mogok.',
                2 => 'Berhenti dan panik menunggu bantuan orang lewat.',
                3 => 'Langsung membawa motor ke bengkel tanpa ingin tahu penyebabnya.',
                4 => 'Mencari tahu penyebabnya di internet lalu membawanya ke mekanik.',
                5 => 'Mengecek sendiri komponen dasar (seperti oli, busi, atau rantai) dan mencoba menganalisis kerusakannya sebelum ke bengkel.' // Bobot 5 untuk TKRO
            ],
        ]);

        // Kasus TKJ
        Question::create([
            'criteria_id' => $kriteria['TKJ']->id,
            'fase' => 2, 'tipe_opsi' => 'text',
            'teks_pertanyaan' => 'Jaringan WiFi di rumah atau sekolah Anda tiba-tiba terputus padahal lampu indikator modem menyala. Apa yang Anda lakukan?',
            'opsi_jawaban' => [
                1 => 'Meninggalkannya dan memilih tidur atau melakukan hal lain.',
                2 => 'Menunggu orang lain atau teknisi datang memperbaikinya.',
                3 => 'Sekadar mematikan lalu menyalakan ulang (restart) modem.',
                4 => 'Mengecek kabel LAN yang terhubung dan memastikan indikator lampu spesifik menyala.',
                5 => 'Masuk ke alamat IP admin router melalui browser untuk mengecek konfigurasi jaringan dan status koneksi internet.' // Bobot 5 untuk TKJ
            ],
        ]);
    }
}