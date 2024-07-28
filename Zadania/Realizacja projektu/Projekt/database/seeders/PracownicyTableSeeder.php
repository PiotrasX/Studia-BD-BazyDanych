<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pracownik;

class PracownicyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            ['id_konta' => 5, 'stanowisko' => "koordynator"],
            ['id_konta' => 6, 'stanowisko' => "admin"],
            ['id_konta' => 12, 'stanowisko' => "koordynator"],
            ['id_konta' => 14, 'stanowisko' => "koordynator"],
        ];

        foreach ($poczatkoweDane as $dane) {
            Pracownik::firstOrCreate([
                'id_konta' => $dane['id_konta'],
            ], [
                'stanowisko' => $dane['stanowisko'],
            ]);
        }
    }
}
