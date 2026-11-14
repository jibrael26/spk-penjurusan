<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Criteria::exists()) {
            $this->call(CriteriaQuestionSeeder::class);
        }

        $criteriaId = Criteria::firstOrFail()->id;

        // Data demo sengaja dibuat ringkas dan seimbang: 6 pertanyaan pada setiap fase.
        Question::query()->delete();

        $questions = [
            // Fase 1: satu pertanyaan untuk setiap dimensi RIASEC.
            [
                'fase' => 1, 'kode' => 'R1',
                'teks' => 'Saya tertarik menggunakan alat atau mesin untuk menyelesaikan pekerjaan.',
            ],
            [
                'fase' => 1, 'kode' => 'I1',
                'teks' => 'Saya senang mencari tahu penyebab suatu masalah dan menemukan solusinya.',
            ],
            [
                'fase' => 1, 'kode' => 'A1',
                'teks' => 'Saya senang membuat karya atau tampilan yang berasal dari ide saya sendiri.',
            ],
            [
                'fase' => 1, 'kode' => 'S1',
                'teks' => 'Saya senang membantu orang lain memahami sesuatu yang belum mereka mengerti.',
            ],
            [
                'fase' => 1, 'kode' => 'E1',
                'teks' => 'Saya tertarik memimpin atau mengatur kegiatan dalam sebuah kelompok.',
            ],
            [
                'fase' => 1, 'kode' => 'C1',
                'teks' => 'Saya senang menyusun data, angka, atau dokumen secara teratur.',
            ],

            // Fase 2-4: satu pertanyaan untuk setiap indikator CHC per fase.
            [
                'fase' => 2, 'kode' => 'Gf1',
                'teks' => 'Pola angka berikut adalah 2, 4, 8, 16, ... Angka berikutnya adalah?',
                'opsi' => [1 => '20', 2 => '24', 3 => '32', 4 => '36'], 'kunci' => 3,
            ],
            [
                'fase' => 2, 'kode' => 'Gc1',
                'teks' => 'Apa arti kata "efisien" dalam konteks pekerjaan?',
                'opsi' => [1 => 'Bekerja tanpa tujuan', 2 => 'Bekerja dengan waktu dan sumber daya secara optimal', 3 => 'Bekerja paling lama', 4 => 'Bekerja tanpa aturan'], 'kunci' => 2,
            ],
            [
                'fase' => 2, 'kode' => 'Gv1',
                'teks' => 'Pola simbol berikut adalah ▲ ■ ▲ ■ ▲ ... Simbol berikutnya adalah?',
                'opsi' => [1 => '▲', 2 => '■', 3 => '●', 4 => '◆'], 'kunci' => 2,
            ],
            [
                'fase' => 2, 'kode' => 'Gq1',
                'teks' => 'Sebuah barang seharga Rp80.000 mendapat potongan Rp10.000. Harga akhirnya adalah?',
                'opsi' => [1 => 'Rp60.000', 2 => 'Rp70.000', 3 => 'Rp75.000', 4 => 'Rp90.000'], 'kunci' => 2,
            ],
            [
                'fase' => 2, 'kode' => 'Gwm1',
                'teks' => 'Perhatikan urutan 7 - 2 - 9 - 4 - 6. Angka pada posisi ke-4 adalah?',
                'opsi' => [1 => '2', 2 => '4', 3 => '6', 4 => '9'], 'kunci' => 2,
            ],
            [
                'fase' => 2, 'kode' => 'Gs1',
                'teks' => 'Pasangan angka yang berbeda adalah: 48371|48371, 72645|72654, 39182|39182, 58421|58421.',
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'], 'kunci' => 2,
            ],

            [
                'fase' => 3, 'kode' => 'Gf2',
                'teks' => 'Jika 3 + 5 = 16, 4 + 6 = 20, dan 5 + 7 = 24, maka 6 + 8 = ...',
                'opsi' => [1 => '26', 2 => '28', 3 => '30', 4 => '32'], 'kunci' => 2,
            ],
            [
                'fase' => 3, 'kode' => 'Gc2',
                'teks' => 'Istilah "prioritas" paling tepat berarti...',
                'opsi' => [1 => 'Hal yang tidak perlu dilakukan', 2 => 'Hal yang didahulukan karena penting', 3 => 'Hal yang selalu terakhir', 4 => 'Hal tanpa tujuan'], 'kunci' => 2,
            ],
            [
                'fase' => 3, 'kode' => 'Gv2',
                'teks' => 'Arah bergerak ↑ → ↓ ← ↑ → ... Arah berikutnya adalah?',
                'opsi' => [1 => '↑', 2 => '→', 3 => '↓', 4 => '←'], 'kunci' => 3,
            ],
            [
                'fase' => 3, 'kode' => 'Gq2',
                'teks' => 'Kendaraan menempuh 120 km dalam 2 jam. Kecepatan rata-ratanya adalah?',
                'opsi' => [1 => '40 km/jam', 2 => '50 km/jam', 3 => '60 km/jam', 4 => '80 km/jam'], 'kunci' => 3,
            ],
            [
                'fase' => 3, 'kode' => 'Gwm2',
                'teks' => 'Perhatikan urutan B - 7 - K - 3 - M - 9. Huruf tepat setelah angka 7 adalah?',
                'opsi' => [1 => 'B', 2 => 'K', 3 => 'M', 4 => 'Tidak ada'], 'kunci' => 2,
            ],
            [
                'fase' => 3, 'kode' => 'Gs2',
                'teks' => 'Pasangan angka yang berbeda adalah: 01923|01953, 89578|89578, 26568|26568, 89790|89790.',
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'], 'kunci' => 1,
            ],

            [
                'fase' => 4, 'kode' => 'Gf3',
                'teks' => 'Sebuah mesin menghasilkan 10 produk dalam 5 menit. Dalam 15 menit, mesin menghasilkan?',
                'opsi' => [1 => '20 produk', 2 => '25 produk', 3 => '30 produk', 4 => '35 produk'], 'kunci' => 3,
            ],
            [
                'fase' => 4, 'kode' => 'Gc3',
                'teks' => 'Informasi yang dapat dipercaya sebaiknya didukung oleh...',
                'opsi' => [1 => 'Rumor', 2 => 'Bukti atau sumber yang jelas', 3 => 'Tebakan', 4 => 'Pendapat tanpa alasan'], 'kunci' => 2,
            ],
            [
                'fase' => 4, 'kode' => 'Gv3',
                'teks' => 'Sebuah tanda berada di sisi atas kotak. Jika kotak diputar hingga sisi atas menjadi bawah, tanda berada di...',
                'opsi' => [1 => 'Atas', 2 => 'Bawah', 3 => 'Kiri', 4 => 'Kanan'], 'kunci' => 2,
            ],
            [
                'fase' => 4, 'kode' => 'Gq3',
                'teks' => 'Jika 5 buku berharga Rp50.000, harga 8 buku dengan harga satuan sama adalah?',
                'opsi' => [1 => 'Rp70.000', 2 => 'Rp75.000', 3 => 'Rp80.000', 4 => 'Rp85.000'], 'kunci' => 3,
            ],
            [
                'fase' => 4, 'kode' => 'Gwm3',
                'teks' => 'Perhatikan urutan 8 - 3 - 5 - 1 - 9. Angka sebelum angka 1 adalah?',
                'opsi' => [1 => '3', 2 => '5', 3 => '8', 4 => '9'], 'kunci' => 2,
            ],
            [
                'fase' => 4, 'kode' => 'Gs3',
                'teks' => 'Pasangan angka yang berbeda adalah: 13423|13423, 74272|74272, 84392|84392, 40567|48567.',
                'opsi' => [1 => 'Baris 1', 2 => 'Baris 2', 3 => 'Baris 3', 4 => 'Baris 4'], 'kunci' => 4,
            ],
        ];

        foreach ($questions as $question) {
            Question::create([
                'criteria_id' => $criteriaId,
                'teks_pertanyaan' => $question['teks'],
                'fase' => $question['fase'],
                'tipe_opsi' => 'text',
                'opsi_jawaban' => $question['opsi'] ?? null,
                'bobot' => $question['fase'] === 4 ? 12 : null,
                'kode_indikator' => $question['kode'],
                'kunci_jawaban' => isset($question['kunci']) ? (string) $question['kunci'] : null,
            ]);
        }

        $this->command?->info('24 pertanyaan demo berhasil dibuat: 6 pertanyaan per fase.');
    }
}
