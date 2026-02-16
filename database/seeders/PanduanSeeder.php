<?php

namespace Database\Seeders;

use App\Models\Panduan;
use Illuminate\Database\Seeder;

class PanduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $panduans = [
            [
                'judul' => 'Sampah Organik',
                'ikon' => '🍃',
                'deskripsi' => 'Sampah yang berasal dari makhluk hidup dan dapat terurai secara alami.',
                'konten' => [
                    'Sisa makanan (nasi, sayur, buah)',
                    'Daun kering dan ranting',
                    'Kulit buah dan sayur',
                    'Ampas kopi dan teh',
                    'Cangkang telur',
                    'Sisa potongan rumput',
                ],
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'judul' => 'Sampah Anorganik',
                'ikon' => '🫙',
                'deskripsi' => 'Sampah yang tidak mudah terurai secara alami dan memerlukan waktu lama untuk hancur.',
                'konten' => [
                    'Botol plastik dan gelas plastik',
                    'Kaleng minuman dan makanan',
                    'Kertas dan kardus',
                    'Kain dan tekstil bekas',
                    'Kaca dan pecahan kaca',
                    'Logam dan aluminium',
                    'Styrofoam dan gabus',
                ],
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'judul' => 'Sampah B3 (Berbahaya)',
                'ikon' => '⚠️',
                'deskripsi' => 'Sampah yang mengandung bahan berbahaya dan beracun, memerlukan penanganan khusus.',
                'konten' => [
                    'Baterai bekas',
                    'Lampu neon dan bohlam',
                    'Obat-obatan kedaluwarsa',
                    'Cat dan pelarut kimia',
                    'Pestisida dan insektisida',
                    'Elektronik bekas (e-waste)',
                ],
                'urutan' => 3,
                'is_active' => true,
            ],
            [
                'judul' => 'Cara Komposting',
                'ikon' => '🌱',
                'deskripsi' => 'Langkah-langkah mengolah sampah organik menjadi kompos yang berguna.',
                'konten' => [
                    'Siapkan wadah/lubang kompos',
                    'Masukkan sampah organik secara berlapis',
                    'Tambahkan tanah di antara lapisan',
                    'Siram dengan air secukupnya',
                    'Aduk setiap 1-2 minggu',
                    'Kompos siap dalam 2-3 bulan',
                ],
                'urutan' => 4,
                'is_active' => true,
            ],
            [
                'judul' => 'Reduce, Reuse, Recycle',
                'ikon' => '♻️',
                'deskripsi' => 'Prinsip 3R untuk mengurangi volume sampah dan menjaga kelestarian lingkungan.',
                'konten' => [
                    'Reduce: Kurangi penggunaan barang sekali pakai',
                    'Reduce: Bawa tas belanja sendiri',
                    'Reuse: Gunakan kembali botol minum',
                    'Reuse: Manfaatkan kembali kemasan bekas',
                    'Recycle: Pisahkan sampah yang bisa didaur ulang',
                    'Recycle: Setorkan ke bank sampah terdekat',
                ],
                'urutan' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($panduans as $panduan) {
            Panduan::create($panduan);
        }
    }
}
