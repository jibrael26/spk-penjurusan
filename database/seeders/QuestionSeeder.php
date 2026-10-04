<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Criteria; // Pastikan model Criteria di-import

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan ada Kriteria Default (Opsional, sesuaikan dengan ID kriteria di tabel Anda)
        // Jika soal-soal ini berlaku untuk semua jurusan (tes umum), Anda mungkin
        // perlu mengatur criteria_id secara dinamis atau menggunakan ID tertentu.
        // Untuk contoh ini, kita asumsikan ID kriteria pertama adalah 1.
        $defaultCriteriaId = 1; 
        
        $kriteriaPertama = Criteria::first();
        if ($kriteriaPertama) {
            $defaultCriteriaId = $kriteriaPertama->id;
        }

        // ==========================================
        // DATA INSTRUMEN MINAT (RIASEC) - FASE 1
        // ==========================================
        $minatQuestions = [
            // Realistic (R)
            ['kode' => 'R1', 'teks' => 'Saya tertarik menggunakan peralatan atau mesin untuk menyelesaikan suatu pekerjaan.'],
            ['kode' => 'R2', 'teks' => 'Saya tertarik memperbaiki benda atau peralatan yang mengalami kerusakan.'],
            ['kode' => 'R3', 'teks' => 'Saya senang melakukan kegiatan praktik daripada hanya membaca penjelasan.'],
            ['kode' => 'R4', 'teks' => 'Saya tertarik mempelajari cara kerja kendaraan atau mesin.'],
            ['kode' => 'R5', 'teks' => 'Saya tertarik melakukan kegiatan yang menggunakan keterampilan tangan dan peralatan.'],
            ['kode' => 'R6', 'teks' => 'Saya tertarik melakukan kegiatan yang berhubungan dengan tanaman, lahan, atau lingkungan fisik.'],
            
            // Investigative (I)
            ['kode' => 'I1', 'teks' => 'Saya tertarik mencari tahu penyebab ketika suatu alat atau sistem tidak bekerja dengan benar.'],
            ['kode' => 'I2', 'teks' => 'Saya senang memecahkan masalah dengan menggunakan logika.'],
            ['kode' => 'I3', 'teks' => 'Saya tertarik melakukan percobaan untuk mengetahui bagaimana sesuatu bekerja.'],
            ['kode' => 'I4', 'teks' => 'Saya senang membandingkan informasi sebelum mengambil kesimpulan.'],
            ['kode' => 'I5', 'teks' => 'Saya tertarik menganalisis suatu masalah secara mendalam.'],
            ['kode' => 'I6', 'teks' => 'Saya senang mencari cara yang lebih efektif untuk menyelesaikan suatu masalah.'],
            
            // Artistic (A)
            ['kode' => 'A1', 'teks' => 'Saya senang membuat atau menciptakan sesuatu berdasarkan ide saya sendiri.'],
            ['kode' => 'A2', 'teks' => 'Saya tertarik membuat desain atau tampilan visual menggunakan komputer.'],
            ['kode' => 'A3', 'teks' => 'Saya senang mencari cara yang kreatif ketika mengerjakan suatu tugas.'],
            ['kode' => 'A4', 'teks' => 'Saya tertarik mengembangkan ide baru yang berbeda dari cara yang biasa digunakan.'],
            ['kode' => 'A5', 'teks' => 'Saya senang mengekspresikan ide melalui gambar, desain, tulisan, atau bentuk karya lainnya.'],
            ['kode' => 'A6', 'teks' => 'Saya tertarik membuat tampilan suatu produk agar terlihat menarik.'],
            
            // Social (S)
            ['kode' => 'S1', 'teks' => 'Saya senang membantu orang lain memahami sesuatu yang belum mereka mengerti.'],
            ['kode' => 'S2', 'teks' => 'Saya tertarik mengajari orang lain suatu keterampilan yang saya kuasai.'],
            ['kode' => 'S3', 'teks' => 'Saya senang bekerja sama dengan orang lain dalam menyelesaikan tugas.'],
            ['kode' => 'S4', 'teks' => 'Saya tertarik mendengarkan dan membantu orang lain ketika mereka mengalami kesulitan.'],
            ['kode' => 'S5', 'teks' => 'Saya nyaman berkomunikasi dengan orang lain dalam kegiatan kelompok.'],
            ['kode' => 'S6', 'teks' => 'Saya tertarik terlibat dalam kegiatan yang memberikan manfaat bagi orang lain.'],
            
            // Enterprising (E)
            ['kode' => 'E1', 'teks' => 'Saya tertarik menjadi pemimpin dalam suatu kelompok.'],
            ['kode' => 'E2', 'teks' => 'Saya senang mengatur pembagian tugas dalam sebuah kelompok.'],
            ['kode' => 'E3', 'teks' => 'Saya tertarik menyampaikan ide kepada orang lain dan meyakinkan mereka.'],
            ['kode' => 'E4', 'teks' => 'Saya senang mengambil keputusan ketika bekerja dalam kelompok.'],
            ['kode' => 'E5', 'teks' => 'Saya tertarik mengelola suatu kegiatan atau usaha.'],
            ['kode' => 'E6', 'teks' => 'Saya tertarik mencoba membuat atau mengembangkan suatu usaha.'],
            
            // Conventional (C)
            ['kode' => 'C1', 'teks' => 'Saya senang menyusun data atau informasi secara teratur.'],
            ['kode' => 'C2', 'teks' => 'Saya tertarik melakukan pekerjaan yang berhubungan dengan angka.'],
            ['kode' => 'C3', 'teks' => 'Saya senang mencatat dan memeriksa informasi agar tidak terjadi kesalahan.'],
            ['kode' => 'C4', 'teks' => 'Saya nyaman mengikuti prosedur atau aturan kerja yang telah ditentukan.'],
            ['kode' => 'C5', 'teks' => 'Saya senang mengelompokkan dokumen atau informasi berdasarkan kategori tertentu.'],
            ['kode' => 'C6', 'teks' => 'Saya tertarik melakukan pekerjaan administrasi dan pencatatan.'],
        ];

        foreach ($minatQuestions as $q) {
            Question::create([
                'criteria_id'     => $defaultCriteriaId, // Sesuaikan jika perlu mengikat ke jurusan tertentu
                'teks_pertanyaan' => $q['teks'],
                'fase'            => 1, // Fase 1 = Angket Likert
                'kode_indikator'  => $q['kode'],
                'tipe_opsi'       => 'text',
                'opsi_jawaban'    => null, // Fase 1 biasanya menggunakan skala Likert hardcoded di view
                'kunci_jawaban'   => null,
            ]);
        }

        // ==========================================
        // DATA INSTRUMEN BAKAT (CHC) - FASE 2
        // ==========================================
        $bakatQuestions = [
            // Fluid Reasoning (Gf)
            [
                'kode' => 'Gf1',
                'teks' => "Perhatikan pola berikut:\n2 → 4 → 8 → 16 → ?\nAngka yang tepat untuk melanjutkan pola tersebut adalah...",
                'opsi' => [1 => '20', 2 => '24', 3 => '32', 4 => '36'],
                'kunci' => 3 // Kunci C (Index 3)
            ],
            [
                'kode' => 'Gf2',
                'teks' => "Jika:\n3 + 5 = 16\n4 + 6 = 20\n5 + 7 = 24\nMaka:\n6 + 8 = ...",
                'opsi' => [1 => '26', 2 => '28', 3 => '30', 4 => '32'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gf3',
                'teks' => "Semua kendaraan A menggunakan bahan bakar X.\nSebagian kendaraan B merupakan kendaraan A.\nKesimpulan yang paling tepat adalah...",
                'opsi' => [
                    1 => 'Semua kendaraan B menggunakan bahan bakar X',
                    2 => 'Sebagian kendaraan B menggunakan bahan bakar X',
                    3 => 'Tidak ada kendaraan B yang menggunakan bahan bakar X',
                    4 => 'Semua kendaraan yang menggunakan bahan bakar X adalah kendaraan B'
                ],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gf4',
                'teks' => "Sebuah mesin menghasilkan 10 produk dalam 5 menit dengan kecepatan tetap. Berapa produk yang dihasilkan dalam 15 menit?",
                'opsi' => [1 => '20', 2 => '25', 3 => '30', 4 => '35'],
                'kunci' => 3 // Kunci C
            ],
            [
                'kode' => 'Gf5',
                'teks' => "Perhatikan urutan:\n▲ ● ▲ ● ▲ ?\nSimbol yang tepat untuk melanjutkan pola adalah...",
                'opsi' => [1 => '▲', 2 => '●', 3 => '■', 4 => '◆'],
                'kunci' => 2 // Kunci B
            ],

            // Comprehension-Knowledge (Gc)
            [
                'kode' => 'Gc1',
                'teks' => 'Apa arti kata "efisien" dalam konteks pekerjaan?',
                'opsi' => [
                    1 => 'Menggunakan sumber daya secara tepat untuk memperoleh hasil yang diharapkan',
                    2 => 'Melakukan pekerjaan tanpa aturan',
                    3 => 'Menghasilkan pekerjaan sebanyak mungkin tanpa memperhatikan sumber daya',
                    4 => 'Menghindari penggunaan alat'
                ],
                'kunci' => 1 // Kunci A
            ],
            [
                'kode' => 'Gc2',
                'teks' => "Perhatikan kalimat:\n\"Tanaman membutuhkan air, cahaya, dan unsur hara untuk mendukung pertumbuhannya.\"\nInformasi utama dari kalimat tersebut adalah...",
                'opsi' => [
                    1 => 'Tanaman hanya membutuhkan air',
                    2 => 'Pertumbuhan tanaman dipengaruhi beberapa kebutuhan',
                    3 => 'Cahaya tidak diperlukan tanaman',
                    4 => 'Unsur hara tidak berhubungan dengan tanaman'
                ],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gc3',
                'teks' => 'Apa fungsi utama sebuah laporan dalam kegiatan organisasi?',
                'opsi' => [
                    1 => 'Menyampaikan informasi mengenai suatu kegiatan atau hasil pekerjaan',
                    2 => 'Menggantikan seluruh pekerjaan anggota organisasi',
                    3 => 'Menghapus data kegiatan',
                    4 => 'Mengurangi jumlah kegiatan'
                ],
                'kunci' => 1 // Kunci A
            ],
            [
                'kode' => 'Gc4',
                'teks' => 'Jika seseorang membaca petunjuk penggunaan suatu alat sebelum mengoperasikannya, tujuan utamanya adalah...',
                'opsi' => [
                    1 => 'Mempercepat kerusakan alat',
                    2 => 'Mengetahui cara penggunaan yang tepat',
                    3 => 'Menghindari semua pekerjaan praktik',
                    4 => 'Mengubah fungsi alat'
                ],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gc5',
                'teks' => 'Istilah "prioritas" paling tepat berarti...',
                'opsi' => [
                    1 => 'Sesuatu yang tidak perlu dilakukan',
                    2 => 'Sesuatu yang harus didahulukan berdasarkan kepentingannya',
                    3 => 'Sesuatu yang selalu dilakukan terakhir',
                    4 => 'Sesuatu yang tidak memiliki tujuan'
                ],
                'kunci' => 2 // Kunci B
            ],

            // Visual Processing (Gv)
            [
                'kode' => 'Gv1',
                'teks' => "Sebuah pola terdiri dari:\n↑ → ↓ ← ↑ → ?\nArah berikutnya adalah...",
                'opsi' => [1 => '↑', 2 => '→', 3 => '↓', 4 => '←'],
                'kunci' => 3 // Kunci C
            ],
            [
                'kode' => 'Gv2',
                'teks' => "Sebuah objek memiliki bentuk:\n▲ ■ ▲ ■ ▲ ?\nBentuk yang tepat untuk melanjutkan pola adalah...",
                'opsi' => [1 => '▲', 2 => '■', 3 => '●', 4 => '◆'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gv3',
                'teks' => 'Sebuah kotak memiliki tanda X di sisi atasnya. Jika kotak tersebut diputar sehingga sisi atas berpindah ke posisi bawah, di manakah posisi tanda X?',
                'opsi' => [1 => 'Atas', 2 => 'Bawah', 3 => 'Kiri', 4 => 'Kanan'],
                'kunci' => 2 // Kunci B
            ],

            // Quantitative Knowledge (Gq)
            [
                'kode' => 'Gq1',
                'teks' => 'Sebuah barang memiliki harga Rp80.000 dan mendapat potongan Rp10.000. Harga setelah potongan adalah...',
                'opsi' => [1 => 'Rp60.000', 2 => 'Rp70.000', 3 => 'Rp75.000', 4 => 'Rp90.000'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gq2',
                'teks' => 'Sebuah kendaraan menempuh jarak 120 km dalam waktu 2 jam. Kecepatan rata-ratanya adalah...',
                'opsi' => [1 => '40 km/jam', 2 => '50 km/jam', 3 => '60 km/jam', 4 => '80 km/jam'],
                'kunci' => 3 // Kunci C
            ],
            [
                'kode' => 'Gq3',
                'teks' => 'Jika 5 buku berharga Rp50.000, maka harga 8 buku dengan harga per buku yang sama adalah...',
                'opsi' => [1 => 'Rp70.000', 2 => 'Rp75.000', 3 => 'Rp80.000', 4 => 'Rp85.000'],
                'kunci' => 3 // Kunci C
            ],
            [
                'kode' => 'Gq4',
                'teks' => 'Sebuah kebun berbentuk persegi panjang memiliki panjang 20 meter dan lebar 10 meter. Luas kebun tersebut adalah...',
                'opsi' => [1 => '100 m²', 2 => '150 m²', 3 => '200 m²', 4 => '300 m²'],
                'kunci' => 3 // Kunci C
            ],
            [
                'kode' => 'Gq5',
                'teks' => 'Sebuah perusahaan memperoleh pendapatan Rp2.000.000 dan mengeluarkan biaya Rp1.500.000. Selisih pendapatan dan biaya adalah...',
                'opsi' => [1 => 'Rp300.000', 2 => 'Rp400.000', 3 => 'Rp500.000', 4 => 'Rp600.000'],
                'kunci' => 3 // Kunci C
            ],

            // Working Memory (Gwm)
            [
                'kode' => 'Gwm1',
                'teks' => "Menampilkan kepada siswa selama beberapa detik:\n7 – 2 – 9 – 4 – 6\nKemudian ditutup/dihilangkan urutan tersebut.\nPertanyaan: Angka apakah yang berada pada posisi ke-4?",
                'opsi' => [1 => '2', 2 => '4', 3 => '6', 4 => '9'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gwm2',
                'teks' => "Menampilkan:\nB – 7 – K – 3 – M – 9\nKemudian dihilangkan.\nPertanyaan: Huruf yang berada tepat setelah angka 7 adalah...",
                'opsi' => [1 => 'B', 2 => 'K', 3 => 'M', 4 => 'Tidak ada'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gwm3',
                'teks' => "Tampilkan:\n8 – 3 – 5 – 1 – 9\nSetelah ditutup, tanyakan: Angka berapakah yang berada sebelum angka 1?",
                'opsi' => [1 => '3', 2 => '5', 3 => '8', 4 => '9'],
                'kunci' => 2 // Kunci B
            ],

            // Processing Speed (Gs)
            [
                'kode' => 'Gs1',
                'teks' => "Perhatikan pasangan berikut:\nBaris 1: 48371 | 48371\nBaris 2: 72645 | 72654\nBaris 3: 39182 | 39182\nBaris 4: 58421 | 58421\nInstruksi: Tentukan pasangan yang berbeda.",
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'],
                'kunci' => 2 // Kunci B
            ],
            [
                'kode' => 'Gs2',
                'teks' => "Perhatikan pasangan berikut:\nBaris 1: 01923 | 01953\nBaris 2: 89578 | 89578\nBaris 3: 26568 | 26568\nBaris 4: 89790 | 89790\nInstruksi: Tentukan pasangan yang berbeda.",
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'],
                'kunci' => 1 // Kunci A
            ],
            [
                'kode' => 'Gs3',
                'teks' => "Perhatikan pasangan berikut:\nBaris 1: 13423 | 13423\nBaris 2: 74272 | 74272\nBaris 3: 84392 | 84392\nBaris 4: 40567 | 48567\nInstruksi: Tentukan pasangan yang berbeda.",
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'],
                'kunci' => 4 // Kunci D
            ]
        ];

        foreach ($bakatQuestions as $q) {
            Question::create([
                'criteria_id'     => $defaultCriteriaId,
                'teks_pertanyaan' => $q['teks'],
                'fase'            => 2, // Fase 2 = Pilihan Ganda
                'kode_indikator'  => $q['kode'],
                'tipe_opsi'       => 'text',
                'opsi_jawaban'    => $q['opsi'], // Array akan di-cast ke JSON oleh Model
                'kunci_jawaban'   => (string) $q['kunci'],
            ]);
        }

        $this->command->info('Data Bank Soal berhasil di-seed secara instan!');
    }
}