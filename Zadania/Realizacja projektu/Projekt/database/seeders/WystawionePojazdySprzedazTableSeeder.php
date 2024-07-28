<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WystawionyPojazdSprzedaz;
use Carbon\Carbon;

class WystawionePojazdySprzedazTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            [
                'id_pojazdu' => 8,
                'data_wystawienia' => Carbon::create(2023, 4, 7)->format('d.m.Y'),
                'status_ogloszenia' => 'W trakcie',
                'data_zakonczenia' => null
            ],
            [
                'id_pojazdu' => 9,
                'data_wystawienia' => Carbon::create(2023, 4, 8)->format('d.m.Y'),
                'status_ogloszenia' => 'Zakończone',
                'data_zakonczenia' => Carbon::create(2023, 4, 22)->format('d.m.Y'),
            ],
            [
                'id_pojazdu' => 11,
                'data_wystawienia' => Carbon::create(2023, 4, 10)->format('d.m.Y'),
                'status_ogloszenia' => 'Zakończone',
                'data_zakonczenia' => Carbon::create(2023, 4, 15)->format('d.m.Y'),
            ],
            [
                'id_pojazdu' => 12,
                'data_wystawienia' => Carbon::create(2023, 4, 11)->format('d.m.Y'),
                'status_ogloszenia' => 'W trakcie',
                'data_zakonczenia' => null
            ],
        ];

        foreach ($poczatkoweDane as $dane) {
            WystawionyPojazdSprzedaz::firstOrCreate([
                'id_pojazdu' => $dane['id_pojazdu'],
                'status_ogloszenia' => $dane['status_ogloszenia'],
                'data_wystawienia' => $dane['data_wystawienia'],
                'data_zakonczenia' => $dane['data_zakonczenia'],
            ], []);
        }
    }
}
