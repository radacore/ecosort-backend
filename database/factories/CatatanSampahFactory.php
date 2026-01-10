<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CatatanSampah;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use App\Models\JenisSampah;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CatatanSampah>
 */
class CatatanSampahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CatatanSampah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pastikan ada pengguna dan kecamatan
        $pengguna = Pengguna::inRandomOrder()->first();
        if (!$pengguna) {
            $pengguna = Pengguna::factory()->create();
        }
        
        // Pastikan pengguna memiliki kecamatan_id
        if (!$pengguna->kecamatan_id) {
            $kecamatan = Kecamatan::inRandomOrder()->first() ?? Kecamatan::factory()->create();
            $pengguna->update(['kecamatan_id' => $kecamatan->id]);
            $pengguna->refresh();
        }
        
        // Pastikan ada jenis sampah
        $jenisSampah = JenisSampah::inRandomOrder()->first() ?? JenisSampah::factory()->create();
        
        // Hitung berat berdasarkan volume dan faktor konversi
        $volume = $this->faker->randomFloat(2, 0.1, 10);
        $berat = $volume * $jenisSampah->faktor_konversi;

        return [
            'pengguna_id' => $pengguna->id,
            'kecamatan_id' => $pengguna->kecamatan_id,
            'jenis_terdeteksi' => $jenisSampah->nama,
            'volume_terdeteksi_liter' => $volume,
            'berat_kg' => $berat,
            'foto_path' => $this->faker->optional()->imageUrl(),
            'waktu_setoran' => $this->faker->dateTimeThisMonth(),
            'is_divalidasi' => $this->faker->boolean(70), // 70% divalidasi
            'points_diberikan' => $this->faker->numberBetween(5, 50),
        ];
    }
}
