<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ZdjeciePojazdu;

class ZdjeciaPojazdowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            ['id_pojazdu' => 1, 'nazwa_zdjecia' => 'Volkswagen_ID.4_1.png'],
            ['id_pojazdu' => 1, 'nazwa_zdjecia' => 'Volkswagen_ID.4_2.png'],
            ['id_pojazdu' => 1, 'nazwa_zdjecia' => 'Volkswagen_ID.4_3.png'],
            ['id_pojazdu' => 2, 'nazwa_zdjecia' => 'Renault_Kangoo_1.png'],
            ['id_pojazdu' => 2, 'nazwa_zdjecia' => 'Renault_Kangoo_2.png'],
            ['id_pojazdu' => 2, 'nazwa_zdjecia' => 'Renault_Kangoo_3.png'],
            ['id_pojazdu' => 3, 'nazwa_zdjecia' => 'Opel_Zafira_1.png'],
            ['id_pojazdu' => 3, 'nazwa_zdjecia' => 'Opel_Zafira_2.png'],
            ['id_pojazdu' => 3, 'nazwa_zdjecia' => 'Opel_Zafira_3.png'],
            ['id_pojazdu' => 4, 'nazwa_zdjecia' => 'Suzuki_GrandVitara_1.png'],
            ['id_pojazdu' => 4, 'nazwa_zdjecia' => 'Suzuki_GrandVitara_2.png'],
            ['id_pojazdu' => 4, 'nazwa_zdjecia' => 'Suzuki_GrandVitara_3.png'],
            ['id_pojazdu' => 5, 'nazwa_zdjecia' => 'Volkswagen_Golf_1.png'],
            ['id_pojazdu' => 5, 'nazwa_zdjecia' => 'Volkswagen_Golf_2.png'],
            ['id_pojazdu' => 5, 'nazwa_zdjecia' => 'Volkswagen_Golf_3.png'],
            ['id_pojazdu' => 6, 'nazwa_zdjecia' => 'BMW_3Series_1.png'],
            ['id_pojazdu' => 6, 'nazwa_zdjecia' => 'BMW_3Series_2.png'],
            ['id_pojazdu' => 6, 'nazwa_zdjecia' => 'BMW_3Series_3.png'],
            ['id_pojazdu' => 7, 'nazwa_zdjecia' => 'Jeep_GrandCherokee_1.png'],
            ['id_pojazdu' => 7, 'nazwa_zdjecia' => 'Jeep_GrandCherokee_2.png'],
            ['id_pojazdu' => 7, 'nazwa_zdjecia' => 'Jeep_GrandCherokee_3.png'],
            ['id_pojazdu' => 8, 'nazwa_zdjecia' => 'Citroen_C3_1.png'],
            ['id_pojazdu' => 8, 'nazwa_zdjecia' => 'Citroen_C3_2.png'],
            ['id_pojazdu' => 8, 'nazwa_zdjecia' => 'Citroen_C3_3.png'],
            ['id_pojazdu' => 9, 'nazwa_zdjecia' => 'Iveco_Daily4x4_1.png'],
            ['id_pojazdu' => 9, 'nazwa_zdjecia' => 'Iveco_Daily4x4_2.png'],
            ['id_pojazdu' => 9, 'nazwa_zdjecia' => 'Iveco_Daily4x4_3.png'],
            ['id_pojazdu' => 10, 'nazwa_zdjecia' => 'Abarth_595C_1.png'],
            ['id_pojazdu' => 10, 'nazwa_zdjecia' => 'Abarth_595C_2.png'],
            ['id_pojazdu' => 10, 'nazwa_zdjecia' => 'Abarth_595C_3.png'],
            ['id_pojazdu' => 11, 'nazwa_zdjecia' => 'Tesla_Cybertruck_1.png'],
            ['id_pojazdu' => 11, 'nazwa_zdjecia' => 'Tesla_Cybertruck_2.png'],
            ['id_pojazdu' => 11, 'nazwa_zdjecia' => 'Tesla_Cybertruck_3.png'],
            ['id_pojazdu' => 12, 'nazwa_zdjecia' => 'Toyota_Yaris_1.png'],
            ['id_pojazdu' => 12, 'nazwa_zdjecia' => 'Toyota_Yaris_2.png'],
            ['id_pojazdu' => 12, 'nazwa_zdjecia' => 'Toyota_Yaris_3.png'],
            ['id_pojazdu' => 13, 'nazwa_zdjecia' => 'Iveco_Daily4x4_1.png'],
            ['id_pojazdu' => 13, 'nazwa_zdjecia' => 'Iveco_Daily4x4_2.png'],
            ['id_pojazdu' => 13, 'nazwa_zdjecia' => 'Iveco_Daily4x4_3.png'],
        ];

        foreach ($poczatkoweDane as $dane) {
            ZdjeciePojazdu::firstOrCreate([
                'id_pojazdu' => $dane['id_pojazdu'],
                'nazwa_zdjecia' => $dane['nazwa_zdjecia'],
            ], []);
        }
    }
}
