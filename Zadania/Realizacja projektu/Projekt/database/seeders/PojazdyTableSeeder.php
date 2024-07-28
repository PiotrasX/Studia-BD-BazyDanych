<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pojazd;

class PojazdyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            [
                'vin' => 'JM1BL1SF3A1278376',
                'id_cechy_pojazdu' => 50,
                'rok_produkcji' => 2010,
                'przebieg' => 126890,
                'pojemnosc_silnika' => 1800,
                'moc_silnika' => 140,
                'rodzaj_paliwa' => 'benzyna',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 5,
                'cena' => 35000.00,
                'id_wlasciciela' => 3,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => '1FMCU9EG1AKB10553',
                'id_cechy_pojazdu' => 165,
                'rok_produkcji' => 2012,
                'przebieg' => 89371,
                'pojemnosc_silnika' => 2000,
                'moc_silnika' => 150,
                'rodzaj_paliwa' => 'diesel',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 8,
                'cena' => 67000.00,
                'id_wlasciciela' => 2,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => 'JTKJF5C77E3006451',
                'id_cechy_pojazdu' => 180,
                'rok_produkcji' => 2015,
                'przebieg' => 234581,
                'pojemnosc_silnika' => 2200,
                'moc_silnika' => 140,
                'rodzaj_paliwa' => 'diesel',
                'liczba_drzwi' => 4,
                'liczba_miejsc' => 7,
                'cena' => 81500.00,
                'id_wlasciciela' => 2,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => '1B3LC56J68N136229',
                'id_cechy_pojazdu' => 252,
                'rok_produkcji' => 2009,
                'przebieg' => 265908,
                'pojemnosc_silnika' => 2000,
                'moc_silnika' => 114,
                'rodzaj_paliwa' => 'benzyna + gaz',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 4,
                'cena' => 74000.00,
                'id_wlasciciela' => 3,
                'status_pojazdu' => 'W serwisie'
            ],
            [
                'vin' => '3GCUKSEC1EG580856',
                'id_cechy_pojazdu' => 42,
                'rok_produkcji' => 2005,
                'przebieg' => 321876,
                'pojemnosc_silnika' => 1900,
                'moc_silnika' => 126,
                'rodzaj_paliwa' => 'benzyna + gaz',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 5,
                'cena' => 29900.00,
                'id_wlasciciela' => 7,
                'status_pojazdu' => 'W serwisie'
            ],
            [
                'vin' => '2G1WB55K569305562',
                'id_cechy_pojazdu' => 63,
                'rok_produkcji' => 2019,
                'przebieg' => 43707,
                'pojemnosc_silnika' => 3200,
                'moc_silnika' => 230,
                'rodzaj_paliwa' => 'diesel',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 4,
                'cena' => 143000.00,
                'id_wlasciciela' => 10,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => 'JM3KE4DY9E0335841',
                'id_cechy_pojazdu' => 309,
                'rok_produkcji' => 2012,
                'przebieg' => 156700,
                'pojemnosc_silnika' => 2600,
                'moc_silnika' => 198,
                'rodzaj_paliwa' => 'benzyna',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 5,
                'cena' => 98000.00,
                'id_wlasciciela' => 9,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => '4A4AP3AU1FE003755',
                'id_cechy_pojazdu' => 385,
                'rok_produkcji' => 2004,
                'przebieg' => 358980,
                'pojemnosc_silnika' => 1400,
                'moc_silnika' => 110,
                'rodzaj_paliwa' => 'diesel',
                'liczba_drzwi' => 4,
                'liczba_miejsc' => 5,
                'cena' => 34700.00,
                'id_wlasciciela' => 6,
                'status_pojazdu' => 'Na sprzedaż'
            ],
            [
                'vin' => '1J8GN28K29W598211',
                'id_cechy_pojazdu' => 201,
                'rok_produkcji' => 2006,
                'przebieg' => 324097,
                'pojemnosc_silnika' => 2300,
                'moc_silnika' => 162,
                'rodzaj_paliwa' => 'diesel + gaz',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 5,
                'cena' => 68500.00,
                'id_wlasciciela' => 5,
                'status_pojazdu' => 'Sprzedany'
            ],
            [
                'vin' => '4T1SK12E5RU870281',
                'id_cechy_pojazdu' => 125,
                'rok_produkcji' => 2017,
                'przebieg' => 40980,
                'pojemnosc_silnika' => 3000,
                'moc_silnika' => 240,
                'rodzaj_paliwa' => 'benzyna',
                'liczba_drzwi' => 2,
                'liczba_miejsc' => 2,
                'cena' => 109900.00,
                'id_wlasciciela' => 9,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => '2GE5RU8705809KBTY',
                'id_cechy_pojazdu' => 87,
                'rok_produkcji' => 2020,
                'przebieg' => 13800,
                'pojemnosc_silnika' => 3200,
                'moc_silnika' => 312,
                'rodzaj_paliwa' => 'diesel',
                'liczba_drzwi' => 2,
                'liczba_miejsc' => 2,
                'cena' => 99900.00,
                'id_wlasciciela' => 9,
                'status_pojazdu' => 'W bazie'
            ],
            [
                'vin' => '2HJYK16586H589000',
                'id_cechy_pojazdu' => 3,
                'rok_produkcji' => 2010,
                'przebieg' => 210700,
                'pojemnosc_silnika' => 1000,
                'moc_silnika' => 87,
                'rodzaj_paliwa' => 'benzyna + gaz',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 4,
                'cena' => 6700.00,
                'id_wlasciciela' => 4,
                'status_pojazdu' => 'Na sprzedaż'
            ],
            [
                'vin' => '1J8GN28K29W598211',
                'id_cechy_pojazdu' => 201,
                'rok_produkcji' => 2006,
                'przebieg' => 324097,
                'pojemnosc_silnika' => 2300,
                'moc_silnika' => 162,
                'rodzaj_paliwa' => 'diesel + gaz',
                'liczba_drzwi' => 5,
                'liczba_miejsc' => 5,
                'cena' => 68500.00,
                'id_wlasciciela' => 8,
                'status_pojazdu' => 'W bazie'
            ],
        ];

        foreach ($poczatkoweDane as $dane) {
            Pojazd::firstOrCreate([
                'vin' => $dane['vin'],
                'id_wlasciciela' => $dane['id_wlasciciela'],
            ], [
                'id_cechy_pojazdu' => $dane['id_cechy_pojazdu'],
                'rok_produkcji' => $dane['rok_produkcji'],
                'przebieg' => $dane['przebieg'],
                'pojemnosc_silnika' => $dane['pojemnosc_silnika'],
                'moc_silnika' => $dane['moc_silnika'],
                'rodzaj_paliwa' => $dane['rodzaj_paliwa'],
                'liczba_drzwi' => $dane['liczba_drzwi'],
                'liczba_miejsc' => $dane['liczba_miejsc'],
                'cena' => $dane['cena'],
                'status_pojazdu' => $dane['status_pojazdu'],
            ]);
        }
    }
}
