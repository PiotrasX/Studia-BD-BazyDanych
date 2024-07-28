<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SerwisowanyPojazd;
use Carbon\Carbon;

class SerwisowanePojazdyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            [
                'id_pracownika' => 1,
                'id_pojazdu' => 4,
                'opis_usterki' => 'Zbita przednia szyba.',
                'data_poczatku_serwisu' => Carbon::create(2023, 4, 19)->format('d.m.Y'),
                'status_serwisu' => 'W trakcie',
                'data_konca_serwisu' => null
            ],
            [
                'id_pracownika' => 3,
                'id_pojazdu' => 5,
                'opis_usterki' => 'Skrzynia biegów mi nie działa. Proszę o szybką naprawę!!!',
                'data_poczatku_serwisu' => Carbon::create(2023, 4, 19)->format('d.m.Y'),
                'status_serwisu' => 'W trakcie',
                'data_konca_serwisu' => null
            ],
            [
                'id_pracownika' => 3,
                'id_pojazdu' => 6,
                'opis_usterki' => 'Proszę o wymianę świateł mijania.',
                'data_poczatku_serwisu' => Carbon::create(2023, 4, 20)->format('d.m.Y'),
                'status_serwisu' => 'Zakończony',
                'data_konca_serwisu' => Carbon::create(2023, 4, 24)->format('d.m.Y'),
            ],
            [
                'id_pracownika' => 4,
                'id_pojazdu' => 7,
                'opis_usterki' => 'Proszę o uzupełnienie płynu hamulcowego i chłodniczego.',
                'data_poczatku_serwisu' => Carbon::create(2023, 4, 21)->format('d.m.Y'),
                'status_serwisu' => 'Zakończony',
                'data_konca_serwisu' => Carbon::create(2023, 4, 23)->format('d.m.Y'),
            ]
        ];

        foreach ($poczatkoweDane as $dane) {
            SerwisowanyPojazd::firstOrCreate([
                'id_pojazdu' => $dane['id_pojazdu'],
                'status_serwisu' => $dane['status_serwisu'],
                'data_poczatku_serwisu' => $dane['data_poczatku_serwisu'],
                'data_konca_serwisu' => $dane['data_konca_serwisu'],
            ], [
                'id_pracownika' => $dane['id_pracownika'],
                'opis_usterki' => $dane['opis_usterki'],
            ]);
        }
    }
}
