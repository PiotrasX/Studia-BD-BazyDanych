<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Klient;
use App\Models\Pojazd;
use App\Models\ZdjeciePojazdu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function redirectToHome()
    {
        return redirect()->route('index');
    }

    public function account()
    {
        try {
            $konto = Auth::user();
            $dane = $konto->dane;

            // $klient = Klient::where('id_konta', $konto->id_konta)->first();
            $klientSQL = DB::select("SELECT * FROM pobierz_dane_klienta(?)", [$konto->id_konta]); // Funkcja SQL

            if (empty($klientSQL)) {
                return redirect()->route('index')->with('error', 'Nie znaleziono klienta.');
            }

            $klient = new Klient(json_decode($klientSQL[0]->klient_data, true));

            // $pojazdy = Pojazd::with('zdjecia')->with('cechyPojazdu')->where('id_wlasciciela', $klient->id_klienta)->get();
            $pojazdySQL = DB::select("SELECT * FROM pobierz_pojazdy_plus_cechy_zdjecia(?)", [$klient->id_klienta]); // Funkcja SQL

            $pojazdy = collect(json_decode($pojazdySQL[0]->pojazdy_data, true))->map(function ($pojazdData) {
                $pojazd = new Pojazd($pojazdData);
                $pojazd->setRelation('zdjecia', collect($pojazdData['zdjecia'])->map(function ($zdjecieData) {
                    return new ZdjeciePojazdu($zdjecieData);
                }));
                return $pojazd;
            });

            return view('my-account', compact('konto', 'dane', 'klient', 'pojazdy'));
        } catch (\Exception $e) {
            return redirect()->route('index');
        }
    }

    public function topUp(Request $request)
    {
        $konto = Auth::user();
        if ($konto->typ_konta === 'pracownik') {
            return  redirect()->route('index')->with('error', 'Nie znaleziono powiązanego konta.');
        }

        // $klient = Klient::where('id_konta', $konto->id_konta)->first();
        $klientSQL = DB::select("SELECT * FROM pobierz_dane_klienta(?)", [$konto->id_konta]); // Funkcja SQL

        if (empty($klientSQL)) {
            return redirect()->route('index')->with('error', 'Nie znaleziono klienta.');
        }

        $klient = Klient::find(json_decode($klientSQL[0]->klient_data, true)['id_klienta']);

        $request->validate([
            'kwota_doladowania' => 'required|numeric|min:0|max:100000',
        ]);

        if ($klient) {
            $klient->stan_konta += $request->input('kwota_doladowania');
            $klient->save();

            return redirect()->back()->with('success', 'Konto zostało pomyślnie doładowane.');
        } else {
            return redirect()->back()->with('error', 'Nie znaleziono powiązanego konta klienta.');
        }
    }
}
