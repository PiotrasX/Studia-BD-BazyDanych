<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pojazd;
use App\Models\Klient;
use App\Models\ZdjeciePojazdu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $konto = Auth::user();
        $IDkonta = Auth::id();
        $klient = null;
        $pracownik = null;
        $czyAdmin = false;
        $pojazdy = Pojazd::with('zdjecia', 'cechyPojazdu')->where('status_pojazdu', 'Na sprzedaż')->get();

        if (Auth::check()) {
            $czyAdmin = ($konto->typ_konta === 'pracownik');
            if ($czyAdmin) {
                // $pojazdy = Pojazd::with('zdjecia', 'cechyPojazdu')->get();
                $pojazdySQL = DB::select("SELECT * FROM pobierz_wszystkie_pojazdy_plus_cechy_zdjecia()"); // Funkcja SQL

                if (empty($pojazdySQL)) {
                    return redirect()->route('index');
                }

                $pojazdy = collect($pojazdySQL)->map(function ($item) {
                    $pojazd = new Pojazd(json_decode($item->pojazd_data, true));
                    $pojazd->setRelation('zdjecia', collect(json_decode($item->zdjecia_pojazdu_data, true))->map(function ($zdjecie) {
                        return new ZdjeciePojazdu(['nazwa_zdjecia' => $zdjecie['nazwa_zdjecia']]);
                    }));
                    return $pojazd;
                });

                // $pracownik = Pracownik::where('id_konta', $IDkonta)->first();
                $pracownikSQL = DB::select("SELECT * FROM pobierz_dane_pracownika(?)", [$IDkonta]); // Funkcja SQL

                if (empty($pracownikSQL)) {
                    return redirect()->route('index');
                }

                $pracownik = $pracownikSQL[0];
            } else {
                // $klient = Klient::where('id_konta', $konto->id_konta)->first();
                $klientSQL = DB::select("SELECT * FROM pobierz_dane_klienta(?)", [$konto->id_konta]); // Funkcja SQL

                if (empty($klientSQL)) {
                    return redirect()->route('index')->with('error', 'Nie znaleziono klienta.');
                }

                $klient = Klient::find(json_decode($klientSQL[0]->klient_data, true)['id_klienta']);
            }
        }

        return view('index', compact('pojazdy', 'czyAdmin', 'konto', 'klient', 'pracownik'));
    }
}
