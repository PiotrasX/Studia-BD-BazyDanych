<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Konto;
use Illuminate\Support\Facades\Hash;

class KontaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            ['login' => 'konto1', 'haslo' => Hash::make('haslo1'), 'typ_konta' => 'klient', 'id_danych' => 1],
            ['login' => 'konto2', 'haslo' => Hash::make('haslo2'), 'typ_konta' => 'klient', 'id_danych' => 2],
            ['login' => 'konto3', 'haslo' => Hash::make('haslo3'), 'typ_konta' => 'klient', 'id_danych' => 3],
            ['login' => 'konto4', 'haslo' => Hash::make('haslo4'), 'typ_konta' => 'klient', 'id_danych' => 4],
            ['login' => 'konto5', 'haslo' => Hash::make('haslo5'), 'typ_konta' => 'pracownik', 'id_danych' => 5],
            ['login' => 'konto6', 'haslo' => Hash::make('haslo6'), 'typ_konta' => 'pracownik', 'id_danych' => 6],
            ['login' => 'konto7', 'haslo' => Hash::make('haslo7'), 'typ_konta' => 'klient', 'id_danych' => 7],
            ['login' => 'konto8', 'haslo' => Hash::make('haslo8'), 'typ_konta' => 'klient', 'id_danych' => 8],
            ['login' => 'konto9', 'haslo' => Hash::make('haslo9'), 'typ_konta' => 'klient', 'id_danych' => 9],
            ['login' => 'konto10', 'haslo' => Hash::make('haslo10'), 'typ_konta' => 'klient', 'id_danych' => 10],
            ['login' => 'konto11', 'haslo' => Hash::make('haslo11'), 'typ_konta' => 'klient', 'id_danych' => 11],
            ['login' => 'konto12', 'haslo' => Hash::make('haslo12'), 'typ_konta' => 'pracownik', 'id_danych' => 12],
            ['login' => 'konto13', 'haslo' => Hash::make('haslo13'), 'typ_konta' => 'klient', 'id_danych' => 13],
            ['login' => 'konto14', 'haslo' => Hash::make('haslo14'), 'typ_konta' => 'pracownik', 'id_danych' => 14],
        ];

        foreach ($poczatkoweDane as $dane) {
            Konto::firstOrCreate([
                'login' => $dane['login'],
            ], [
                'haslo' => $dane['haslo'],
                'typ_konta' => $dane['typ_konta'],
                'id_danych' => $dane['id_danych'],
            ]);
        }
    }
}
