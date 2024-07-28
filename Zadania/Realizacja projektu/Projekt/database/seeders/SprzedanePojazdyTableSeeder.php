<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SprzedanyPojazd;
use Carbon\Carbon;

class SprzedanePojazdyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            [
                'id_pojazdu' => 9,
                'id_kupujacego' => 8,
                'data_sprzedazy' => Carbon::create(2023, 4, 22)->format('d.m.Y'),
            ],
        ];

        foreach ($poczatkoweDane as $dane) {
            SprzedanyPojazd::firstOrCreate([
                'id_pojazdu' => $dane['id_pojazdu'],
                'id_kupujacego' => $dane['id_kupujacego'],
                'data_sprzedazy' => $dane['data_sprzedazy'],
            ], []);
        }
    }
}
