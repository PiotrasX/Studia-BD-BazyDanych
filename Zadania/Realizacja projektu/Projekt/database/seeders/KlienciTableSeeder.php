<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Klient;

class KlienciTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            ['id_konta' => 1, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 2, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 3, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 4, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 7, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 8, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 9, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 10, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 11, 'stan_konta' => rand(0, 15000000) / 100],
            ['id_konta' => 13, 'stan_konta' => rand(0, 15000000) / 100],
        ];

        foreach ($poczatkoweDane as $dane) {
            Klient::firstOrCreate([
                'id_konta' => $dane['id_konta'],
            ], [
                'stan_konta' => $dane['stan_konta'],
            ]);
        }
    }
}
