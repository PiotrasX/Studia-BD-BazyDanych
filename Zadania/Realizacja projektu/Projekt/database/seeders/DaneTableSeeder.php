<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dane;

class DaneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poczatkoweDane = [
            [
                'imie' => 'Anna',
                'nazwisko' => 'Kowalska',
                'numer_telefonu' => '123456789',
                'email' => 'anna.kowalska@example.com',
                'ulica' => 'Mickiewicza',
                'numer_domu' => '12A',
                'kod_pocztowy' => '00-001',
                'miejscowosc' => 'Warszawa',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Jan',
                'nazwisko' => 'Nowak',
                'numer_telefonu' => '987654321',
                'email' => 'jan.nowak@example.com',
                'ulica' => 'Długa',
                'numer_domu' => '47B',
                'kod_pocztowy' => '00-002',
                'miejscowosc' => 'Kraków',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Marta',
                'nazwisko' => 'Wiśniewska',
                'numer_telefonu' => '123123123',
                'email' => 'marta.wisniewska@example.com',
                'ulica' => 'Krótka',
                'numer_domu' => '3C',
                'kod_pocztowy' => '00-003',
                'miejscowosc' => 'Łódź',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Karol',
                'nazwisko' => 'Lewandowski',
                'numer_telefonu' => '321321321',
                'email' => 'karol.lewandowski@example.com',
                'ulica' => 'Polna',
                'numer_domu' => '99',
                'kod_pocztowy' => '00-004',
                'miejscowosc' => 'Gdańsk',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Agnieszka',
                'nazwisko' => 'Kaczmarek',
                'numer_telefonu' => '456456456',
                'email' => 'agnieszka.kaczmarek@example.com',
                'ulica' => 'Zakątek',
                'numer_domu' => '15',
                'kod_pocztowy' => '00-005',
                'miejscowosc' => 'Poznań',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Robert',
                'nazwisko' => 'Kamiński',
                'numer_telefonu' => '654654654',
                'email' => 'robert.kaminski@example.com',
                'ulica' => 'Szeroka',
                'numer_domu' => '27',
                'kod_pocztowy' => '00-006',
                'miejscowosc' => 'Wrocław',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Magdalena',
                'nazwisko' => 'Zając',
                'numer_telefonu' => '789789789',
                'email' => 'magdalena.zajac@example.com',
                'ulica' => 'Leśna',
                'numer_domu' => '8',
                'kod_pocztowy' => '00-007',
                'miejscowosc' => 'Szczecin',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Piotr',
                'nazwisko' => 'Kowal',
                'numer_telefonu' => '987987987',
                'email' => 'piotr.kowal@example.com',
                'ulica' => 'Morska',
                'numer_domu' => '64',
                'kod_pocztowy' => '00-008',
                'miejscowosc' => 'Gdynia',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Dorota',
                'nazwisko' => 'Mazur',
                'numer_telefonu' => '111222333',
                'email' => 'dorota.mazur@example.com',
                'ulica' => 'Główna',
                'numer_domu' => '4',
                'kod_pocztowy' => '00-009',
                'miejscowosc' => 'Katowice',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Tomasz',
                'nazwisko' => 'Klimek',
                'numer_telefonu' => '333222111',
                'email' => 'tomasz.klimek@example.com',
                'ulica' => 'Parkowa',
                'numer_domu' => '10',
                'kod_pocztowy' => '00-010',
                'miejscowosc' => 'Rzeszów',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Michalina',
                'nazwisko' => 'Kiepska',
                'numer_telefonu' => '444555666',
                'email' => 'michalina.kiepska@example.com',
                'ulica' => 'Dębowa',
                'numer_domu' => '17E',
                'kod_pocztowy' => '00-007',
                'miejscowosc' => 'Szczecin',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Michał',
                'nazwisko' => 'Kępski',
                'numer_telefonu' => '666555444',
                'email' => 'm.kepski@example.com',
                'ulica' => 'Rejtana',
                'numer_domu' => '321',
                'kod_pocztowy' => '00-005',
                'miejscowosc' => 'Poznań',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Maksymilian',
                'nazwisko' => 'Przypadek',
                'numer_telefonu' => '777888999',
                'email' => 'maks.przypadek@example.com',
                'ulica' => 'Szkolna',
                'numer_domu' => '5',
                'kod_pocztowy' => '00-004',
                'miejscowosc' => 'Gdańsk',
                'kraj' => 'Polska',
            ],
            [
                'imie' => 'Anna',
                'nazwisko' => 'Mała',
                'numer_telefonu' => '999888777',
                'email' => 'mala.ania@example.com',
                'ulica' => '3 Maja',
                'numer_domu' => '86',
                'kod_pocztowy' => '00-005',
                'miejscowosc' => 'Poznań',
                'kraj' => 'Polska',
            ]
        ];

        foreach ($poczatkoweDane as $dane) {
            Dane::firstOrCreate([
                'email' => $dane['email'],
            ], [
                'imie' => $dane['imie'],
                'nazwisko' => $dane['nazwisko'],
                'numer_telefonu' => $dane['numer_telefonu'],
                'ulica' => $dane['ulica'],
                'numer_domu' => $dane['numer_domu'],
                'kod_pocztowy' => $dane['kod_pocztowy'],
                'miejscowosc' => $dane['miejscowosc'],
                'kraj' => $dane['kraj'],
            ]);
        }
    }
}
