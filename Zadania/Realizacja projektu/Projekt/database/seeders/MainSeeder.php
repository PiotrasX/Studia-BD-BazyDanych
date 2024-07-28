<?php

namespace Database\Seeders;

use App\Models\CechyPojazdu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DaneTableSeeder::class,
            KontaTableSeeder::class,
            KlienciTableSeeder::class,
            PracownicyTableSeeder::class,
            CechyPojazdu::class,
            PojazdyTableSeeder::class,
            SerwisowanePojazdyTableSeeder::class,
            WystawionePojazdySprzedazTableSeeder::class,
            SprzedanePojazdyTableSeeder::class,
            KrajeTableSeeder::class,
            ZdjeciaPojazdowSeeder::class
        ]);
    }
}
