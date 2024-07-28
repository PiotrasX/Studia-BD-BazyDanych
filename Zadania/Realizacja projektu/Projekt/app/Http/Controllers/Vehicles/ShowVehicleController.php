<?php

namespace App\Http\Controllers\Vehicles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pojazd;
use App\Models\ZdjeciePojazdu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowVehicleController extends Controller
{
    public function show(Request $request)
    {
        $IDpojazdu = $request->query('id');
        $IDkonta = Auth::id();

        // $pojazd = Pojazd::with(['cechyPojazdu', 'zdjecia'])->findOrFail($IDpojazdu);
        $pojazdSQL = DB::select("SELECT * FROM pobierz_dane_pojazdu_plus_cechy_zdjecia(?)", [$IDpojazdu]); // Funkcja SQL

        if (empty($pojazdSQL)) {
            return redirect()->route('index')->with('error', 'Nie znaleziono pojazdu.');
        }

        $pojazd = new Pojazd(json_decode($pojazdSQL[0]->pojazd_data, true));
        $pojazd->setRelation('zdjecia', collect(json_decode($pojazdSQL[0]->zdjecia_pojazdu_data, true))->map(function ($zdjecie) {
            return new ZdjeciePojazdu(['nazwa_zdjecia' => $zdjecie['nazwa_zdjecia']]);
        }));

        // if (Auth::check()) {
        //     $konto = Auth::user();
        //     if ($konto->typ_konta === 'pracownik') {
        //         $czyWlasciciel = false;
        //     } else {
        //         $klient = Klient::where('id_konta', $konto->id_konta)->first();
        //         $czyWlasciciel = ($klient->id_klienta == $pojazd->id_wlasciciela);
        //     }
        // } else {
        //     $czyWlasciciel = false;
        // }
        $czyWlasciciel = DB::selectOne("SELECT sprawdz_wlasciciela_pojazdu(?, ?) AS czy_wlasciciel", [$IDpojazdu, $IDkonta])->czy_wlasciciel; // Funkcja SQL

        if ($czyWlasciciel || $pojazd?->status_pojazdu === 'Na sprzedaż' || Auth::user()?->typ_konta === 'pracownik') {
            return view('vehicles.show-vehicle', compact('pojazd', 'czyWlasciciel'));
        } else {
            return redirect()->route('index');
        }
    }

    public function showService(Request $request)
    {
        $IDkonta = Auth::id();

        // $pracownik = Pracownik::where('id_konta', $IDkonta)->first();
        $pracownikSQL = DB::select("SELECT * FROM pobierz_dane_pracownika(?)", [$IDkonta]); // Funkcja SQL

        if (empty($pracownikSQL)) {
            return redirect()->route('index');
        }

        $pracownik = $pracownikSQL[0];

        if ($pracownik->czy_pracownik === false) {
            return redirect()->route('index');
        }

        // $pojazdy = Pojazd::with('zdjecia', 'cechyPojazdu')->where('status_pojazdu', 'W serwisie')->get();
        $pojazdySQL = DB::select("SELECT * FROM pobierz_pojazdy_plus_cechy_zdjecia_w_serwisie()"); // Funkcja SQL

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

        // if (Auth::check()) {
        //     $czyAdmin = (Auth::user()?->typ_konta === 'pracownik');
        //     if ($czyAdmin) {
        //         return view('vehicles.show-vehicle-service', compact('pojazdy', 'pracownik'));
        //     } else {
        //         return redirect()->route('index');
        //     }
        // }
        return view('vehicles.show-vehicle-service', compact('pojazdy', 'pracownik'));
    }
}
