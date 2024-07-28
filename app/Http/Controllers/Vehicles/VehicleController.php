<?php

namespace App\Http\Controllers\Vehicles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pojazd;
use App\Models\ZdjeciePojazdu;
use App\Models\Klient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    // session()->flash('success', 'Operacja przebiegła pomyślnie.');
    // session()->flash('error', 'Operacja nie powiodła się.');

    public function redirectToHome()
    {
        return redirect()->route('index');
    }

    // public function purchase(Request $request, $id)
    // {
    //     $konto = Auth::user();
    //     if ($konto->typ_konta === 'pracownik') {
    //         return  redirect()->route('index')->with('error', 'Nie znaleziono powiązanego konta.');
    //     }
    //
    //     $klient = Klient::where('id_konta', $konto->id_konta)->first();
    //     if ($klient) {
    //         $pojazd = Pojazd::find($id);
    //
    //         if ($pojazd && $pojazd->id_wlasciciela !== $klient->id_klienta) {
    //             $wystawionyPojazd = WystawionyPojazdSprzedaz::where('id_pojazdu', $pojazd->id_pojazdu)
    //             ->orderBy('data_wystawienia', 'desc')->orderBy('id_ogloszenia', 'desc')->whereNull('data_zakonczenia')->first();
    //
    //             if ($wystawionyPojazd) {
    //                 if ($klient->stan_konta >= $pojazd->cena) {
    //                     $klient->stan_konta -= $pojazd->cena;
    //                     $klient->save();
    //
    //                     $pojazd->status_pojazdu = 'Sprzedany';
    //                     $pojazd->save();
    //
    //                     $nowyPojazd = Pojazd::create([
    //                         'vin' => $pojazd->vin,
    //                         'id_cechy_pojazdu' => $pojazd->id_cechy_pojazdu,
    //                         'rok_produkcji' => $pojazd->rok_produkcji,
    //                         'przebieg' => $pojazd->przebieg,
    //                         'pojemnosc_silnika' => $pojazd->pojemnosc_silnika,
    //                         'moc_silnika' => $pojazd->moc_silnika,
    //                         'rodzaj_paliwa' => $pojazd->rodzaj_paliwa,
    //                         'liczba_drzwi' => $pojazd->liczba_drzwi,
    //                         'liczba_miejsc' => $pojazd->liczba_miejsc,
    //                         'cena' => $pojazd->cena,
    //                         'id_wlasciciela' => $klient->id_klienta,
    //                         'status_pojazdu' => 'W bazie',
    //                     ]);
    //
    //                     foreach ($pojazd->zdjecia as $zdjecie) {
    //                         ZdjeciePojazdu::create([
    //                             'id_pojazdu' => $nowyPojazd->id_pojazdu,
    //                             'nazwa_zdjecia' => $zdjecie->nazwa_zdjecia,
    //                         ]);
    //                     }
    //
    //                     SprzedanyPojazd::create([
    //                         'id_pojazdu' => $pojazd->id_pojazdu,
    //                         'id_kupujacego' => $klient->id_klienta,
    //                         'data_sprzedazy' => Carbon::now(),
    //                     ]);
    //
    //                     $wystawionyPojazd->update([
    //                         'status_ogloszenia' => 'Zakończone',
    //                         'data_zakonczenia' => Carbon::now(),
    //                     ]);
    //
    //                     return redirect()->back()->with('success', 'Pojazd został pomyślnie zakupiony.');
    //                 } else {
    //                     return redirect()->back()->with('error', 'Nie masz wystarczających środków na koncie.');
    //                 }
    //             } else {
    //                 return redirect()->back()->with('error', 'Nie znaleziono wpisu wystawionego pojazdu.');
    //             }
    //         } else {
    //             return redirect()->back()->with('error', 'Nie znaleziono pojazdu lub pojazd jest już twoją własnością.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Nie znaleziono powiązanego konta klienta.');
    //     }
    // }

    public function purchase(Request $request, $id)
    {
        $IDkonta = Auth::id();

        if ($IDkonta === null) {
            return redirect()->back()->with('error', 'Musisz być zalogowany.');
        }

        $wynikSQL = DB::select("SELECT zakup_pojazdu(?, ?) AS wiadomosc", [$id, $IDkonta]); // Funkcja SQL
        $wiadomosc = $wynikSQL[0]->wiadomosc;

        if ($wiadomosc == 'Pojazd został pomyślnie zakupiony.') {
            return redirect()->back()->with('success', $wiadomosc);
        } else {
            return redirect()->back()->with('error', $wiadomosc);
        }
    }

    // public function sendToService(Request $request, $id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         $pracownik = Pracownik::inRandomOrder()->first();
    //         if (!$pracownik) {
    //             return redirect()->back()->with('error', 'Nie znaleziono dostępnych pracowników.');
    //         }
    //
    //         $pojazd->status_pojazdu = 'W serwisie';
    //         $pojazd->save();
    //
    //         SerwisowanyPojazd::create([
    //             'id_pracownika' => $pracownik->id_pracownika,
    //             'id_pojazdu' => $pojazd->id_pojazdu,
    //             'opis_usterki' => $request->input('opis_usterki'),
    //             'data_poczatku_serwisu' => Carbon::now(),
    //             'status_serwisu' => 'W trakcie',
    //             'data_konca_serwisu' => null,
    //         ]);
    //
    //         return redirect()->back()->with('success', 'Pojazd wysłany do serwisu pomyślnie.');
    //     } else {
    //         return redirect()->back()->with('error', 'Wystąpił problem, spróbuj ponownie później.');
    //     }
    // }

    public function sendToService(Request $request, $id)
    {
        $wynikSQL = DB::select("SELECT wyslij_do_serwisu(?, ?) AS wiadomosc", [$id, $request->input('opis_usterki')]); // Funkcja SQL
        $wiadomosc = $wynikSQL[0]->wiadomosc;

        if ($wiadomosc == 'Pojazd wysłany do serwisu pomyślnie.') {
            return redirect()->back()->with('success', $wiadomosc);
        } else {
            return redirect()->back()->with('error', $wiadomosc);
        }
    }

    // public function endService($id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         $konto = Auth::user();
    //         $pracownik = Pracownik::where('id_konta', $konto->id_konta)->first();
    //
    //         $serwisowanyPojazd = SerwisowanyPojazd::where('id_pojazdu', $pojazd->id_pojazdu)
    //         ->where('id_pracownika', $pracownik->id_pracownika)->orderBy('data_poczatku_serwisu', 'desc')
    //         ->orderBy('id_serwisu', 'desc')->whereNull('data_konca_serwisu')->first();
    //
    //         if ($serwisowanyPojazd) {
    //             $pojazd->status_pojazdu = 'W bazie';
    //             $pojazd->save();
    //
    //             $serwisowanyPojazd->update([
    //                 'status_serwisu' => 'Zakończony',
    //                 'data_konca_serwisu' => Carbon::now(),
    //             ]);
    //
    //             return redirect()->back()->with('success', 'Serwis pojazdu zakończony pomyślnie.');
    //         } else {
    //             return redirect()->back()->with('error', 'Nie znaleziono wpisu serwisowanego pojazdu.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Wystąpił problem, spróbuj ponownie później.');
    //     }
    // }

    public function endService($id)
    {
        $IDkonta = Auth::id();

        if ($IDkonta === null) {
            return redirect()->back()->with('error', 'Musisz być zalogowany.');
        }

        $wynikSQL = DB::select("SELECT zakoncz_serwis(?, ?) AS wiadomosc", [$id, $IDkonta]); // FunkcjaSQL
        $wiadomosc = $wynikSQL[0]->wiadomosc;

        if ($wiadomosc === 'Serwis pojazdu zakończony pomyślnie.') {
            return redirect()->back()->with('success', $wiadomosc);
        } else {
            return redirect()->back()->with('error', $wiadomosc);
        }
    }

    // public function startSale($id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         $pojazd->status_pojazdu = 'Na sprzedaż';
    //         $pojazd->save();
    //
    //         WystawionyPojazdSprzedaz::create([
    //             'id_pojazdu' => $pojazd->id_pojazdu,
    //             'data_wystawienia' => Carbon::now(),
    //             'status_ogloszenia' => 'W trakcie',
    //             'data_zakonczenia' => null,
    //         ]);
    //
    //         return redirect()->back()->with('success', 'Pojazd wystawiony na sprzedaż pomyślnie.');
    //     } else {
    //         return redirect()->back()->with('error', 'Wystąpił problem, spróbuj ponownie później.');
    //     }
    // }

    public function startSale($id)
    {
        $wynikSQL = DB::select("SELECT wystaw_pojazd_na_sprzedaz(?) AS wiadomosc", [$id]); // Funkcja SQL
        $wiadomosc = $wynikSQL[0]->wiadomosc;

        if ($wiadomosc === 'Pojazd wystawiony na sprzedaż pomyślnie.') {
            return redirect()->back()->with('success', $wiadomosc);
        } else {
            return redirect()->back()->with('error', $wiadomosc);
        }
    }

    // public function endSale($id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         $wystawionyPojazd = WystawionyPojazdSprzedaz::where('id_pojazdu', $pojazd->id_pojazdu)
    //         ->orderBy('data_wystawienia', 'desc')->orderBy('id_ogloszenia', 'desc')->whereNull('data_zakonczenia')->first();
    //
    //         if ($wystawionyPojazd) {
    //             $pojazd->status_pojazdu = 'W bazie';
    //             $pojazd->save();
    //
    //             $wystawionyPojazd->update([
    //                 'status_ogloszenia' => 'Zakończone',
    //                 'data_zakonczenia' => Carbon::now(),
    //             ]);
    //
    //             return redirect()->back()->with('success', 'Sprzedaż pojazdu zakończona pomyślnie.');
    //         } else {
    //             return redirect()->back()->with('error', 'Nie znaleziono wpisu wystawionego pojazdu.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Wystąpił problem, spróbuj ponownie później.');
    //     }
    // }

    public function endSale($id)
    {
        $wynikSQL = DB::select("SELECT zakoncz_sprzedaz(?) AS wiadomosc", [$id]); // Funkcja SQL
        $wiadomosc = $wynikSQL[0]->wiadomosc;

        if ($wiadomosc === 'Sprzedaż pojazdu zakończona pomyślnie.') {
            return redirect()->back()->with('success', $wiadomosc);
        } else {
            return redirect()->back()->with('error', $wiadomosc);
        }
    }

    // public function delete($id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         SprzedanyPojazd::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         SerwisowanyPojazd::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         WystawionyPojazdSprzedaz::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         ZdjeciePojazdu::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         $pojazd->delete();
    //
    //         return redirect()->route('account')->with('success', 'Pojazd został pomyślnie usunięty.');
    //     } else {
    //         return redirect()->route('account')->with('error', 'Nie znaleziono pojazdu do usunięcia.');
    //     }
    // }

    private function deleteVehiclePhotos($IDpojazdu)
    {
        $zdjecia = ZdjeciePojazdu::where('id_pojazdu', $IDpojazdu)->get();
        $sciezkiZdjec = [];
        foreach ($zdjecia as $zdjecie) {
            $sciezkiZdjec[] = 'public/img/vehicles/' . $zdjecie->nazwa_zdjecia;
            $zdjecie->delete();
        }
        return $sciezkiZdjec;
    }

    // public function delete($id)
    // {
    //     $pojazd = Pojazd::find($id);
    //     if ($pojazd) {
    //         $sciezkiZdjec = $this->deleteVehiclePhotos($id);
    //
    //         SprzedanyPojazd::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         SerwisowanyPojazd::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         WystawionyPojazdSprzedaz::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         ZdjeciePojazdu::where('id_pojazdu', $pojazd->id_pojazdu)->delete();
    //         $pojazd->delete();
    //
    //         foreach ($sciezkiZdjec as $sciezka) {
    //             Storage::delete($sciezka);
    //         }
    //
    //         return redirect()->route('account')->with('success', 'Pojazd został pomyślnie usunięty.');
    //     } else {
    //         return redirect()->route('account')->with('error', 'Nie znaleziono pojazdu do usunięcia.');
    //     }
    // }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $sciezkiZdjec = $this->deleteVehiclePhotos($id);

            $wynikSQL = DB::select("SELECT usun_pojazd(?) AS wiadomosc", [$id]); // Funkcja SQL
            $wiadomosc = $wynikSQL[0]->wiadomosc;

            if ($wiadomosc === 'Pojazd został pomyślnie usunięty.') {
                foreach ($sciezkiZdjec as $sciezka) {
                    Storage::delete($sciezka);
                }

                DB::commit();
                return redirect()->route('account')->with('success', $wiadomosc);
            } else {
                DB::rollback();
                return redirect()->route('account')->with('error', $wiadomosc);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('account')->with('error', 'Przepraszamy, wystąpił problem z usuwaniem pojazdu, spróbuj ponownie później.');
        }
    }

    public function edit(Request $request)
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
            return new ZdjeciePojazdu(['id_zdjecia' => $zdjecie['id_zdjecia'], 'id_pojazdu' => $zdjecie['id_pojazdu'], 'nazwa_zdjecia' => $zdjecie['nazwa_zdjecia']]);
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

        $selectedInputTypeMarka = $request->session()->get('selectedInputTypeMarka', 'text');
        $selectedInputTypeModel = $request->session()->get('selectedInputTypeModel', 'text');
        $selectedInputTypeNadwozie = $request->session()->get('selectedInputTypeNadwozie', 'text');

        if ($czyWlasciciel) {
            return view('vehicles.edit-vehicle', compact('pojazd', 'selectedInputTypeMarka', 'selectedInputTypeModel', 'selectedInputTypeNadwozie'));
        } else {
            return redirect()->route('index');
        }
    }

    public function register(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('index');
        }

        $selectedInputTypeMarkaRegister = $request->session()->get('selectedInputTypeMarkaRegister', 'text');
        $selectedInputTypeModelRegister = $request->session()->get('selectedInputTypeModelRegister', 'text');
        $selectedInputTypeNadwozieRegister = $request->session()->get('selectedInputTypeNadwozieRegister', 'text');

        return view('vehicles.register-vehicle', compact('selectedInputTypeMarkaRegister', 'selectedInputTypeModelRegister', 'selectedInputTypeNadwozieRegister'));
    }

    private function getValidationRules($markaInputType, $modelInputType, $nadwozieInputType, $maxZdjecia = 3)
    {
        $rules = [
            'vin' => 'required|string|min:17|max:17',
        ];

        if ($markaInputType === 'text') {
            $rules['marka'] = 'required|string|max:255';
        } else {
            $rules['marka_select'] = 'required|string|exists:cechy_pojazdu,marka';
        }

        if ($modelInputType === 'text') {
            $rules['model'] = 'required|string|max:255';
        } else {
            $rules['model_select'] = 'required|string|exists:cechy_pojazdu,model';
        }

        if ($nadwozieInputType === 'text') {
            $rules['nadwozie'] = 'required|string|max:255';
        } else {
            $rules['nadwozie_select'] = 'required|string|exists:cechy_pojazdu,nadwozie';
        }

        $newRules = [
            'rok_produkcji' => 'required|integer|min:1950|max:' . now()->year,
            'przebieg' => 'required|integer|min:0|max:10000000',
            'pojemnosc_silnika' => 'required|integer|min:1|max:100000',
            'moc_silnika' => 'required|integer|min:1|max:5000',
            'rodzaj_paliwa' => 'required|in:benzyna,benzyna + gaz,diesel,diesel + gaz',
            'liczba_drzwi' => 'required|integer|min:1|max:9',
            'liczba_miejsc' => 'required|integer|min:1|max:99',
            'cena' => 'required|numeric|min:1|max:50000000.00',
            'zdjecia_pojazdow_nowe.*' => 'file|mimes:jpg,jpeg,png|max:10240',
            'zdjecia_pojazdow_nowe' => 'array|max:' . $maxZdjecia,
        ];

        return array_merge($rules, $newRules);
    }

    private function storeVehicleDataInSession(Request $request, $markaInputType, $modelInputType, $nadwozieInputType)
    {
        $request->session()->put('selectedInputTypeMarkaRegister', $markaInputType);
        $request->session()->put('selectedInputTypeModelRegister', $modelInputType);
        $request->session()->put('selectedInputTypeNadwozieRegister', $nadwozieInputType);
    }

    private function getValidationMessages($maxZdjecia = 3)
    {
        return [
            'vin.required' => 'Pole \'VIN\' nie może być puste.',
            'vin.string' => 'Pole \'VIN\' musi być ciągiem znaków.',
            'vin.min' => 'Pole \'VIN\' musi zawierać 17 znaków.',
            'vin.max' => 'Pole \'VIN\' musi zawierać 17 znaków.',

            'marka.required' => 'Pole \'Marka\' nie może być puste.',
            'marka.string' => 'Pole \'Marka\' musi być ciągiem znaków.',
            'marka.max' => 'Pole \'Marka\' może zawierać maksymalnie 255 znaków.',

            'marka_select.required' => 'Pole \'Marka\' nie może być puste.',
            'marka_select.string' => 'Pole \'Marka\' musi być ciągiem znaków.',
            'marka_select.exists' => 'Wybrana marka jest nieprawidłowa.',

            'model.required' => 'Pole \'Model\' nie może być puste.',
            'model.string' => 'Pole \'Model\' musi być ciągiem znaków.',
            'model.max' => 'Pole \'Model\' może zawierać maksymalnie 255 znaków.',

            'model_select.required' => 'Pole \'Model\' nie może być puste.',
            'model_select.string' => 'Pole \'Model\' musi być ciągiem znaków.',
            'model_select.exists' => 'Wybrany model jest nieprawidłowy.',

            'nadwozie.required' => 'Pole \'Nadwozie\' nie może być puste.',
            'nadwozie.string' => 'Pole \'Nadwozie\' musi być ciągiem znaków.',
            'nadwozie.max' => 'Pole \'Nadwozie\' może zawierać maksymalnie 255 znaków.',

            'nadwozie_select.required' => 'Pole \'Nadwozie\' nie może być puste.',
            'nadwozie_select.string' => 'Pole \'Nadwozie\' musi być ciągiem znaków.',
            'nadwozie_select.exists' => 'Wybrane nadwozie jest nieprawidłowe.',

            'rok_produkcji.required' => 'Pole \'Rok produkcji\' nie może być puste.',
            'rok_produkcji.integer' => 'Pole \'Rok produkcji\' musi być liczbą całkowitą.',
            'rok_produkcji.min' => 'Pole \'Rok produkcji\' musi wynosić minimalnie 1950.',
            'rok_produkcji.max' => 'Pole \'Rok produkcji\' może wynosić maksymalnie ' . now()->year . '.',

            'przebieg.required' => 'Pole \'Przebieg\' nie może być puste.',
            'przebieg.integer' => 'Pole \'Przebieg\' musi być liczbą całkowitą.',
            'przebieg.min' => 'Pole \'Przebieg\' musi wynosić minimalnie 0.',
            'przebieg.max' => 'Pole \'Przebieg\' może wynosić maksymalnie 10 000 000.',

            'pojemnosc_silnika.required' => 'Pole \'Pojemność silnika\' nie może być puste.',
            'pojemnosc_silnika.integer' => 'Pole \'Pojemność silnika\' musi być liczbą całkowitą.',
            'pojemnosc_silnika.min' => 'Pole \'Pojemność silnika\' musi wynosić minimalnie 1.',
            'pojemnosc_silnika.max' => 'Pole \'Pojemność silnika\' może wynosić maksymalnie 100 000.',

            'moc_silnika.required' => 'Pole \'Moc silnika\' nie może być puste.',
            'moc_silnika.integer' => 'Pole \'Moc silnika\' musi być liczbą całkowitą.',
            'moc_silnika.min' => 'Pole \'Moc silnika\' musi wynosić minimalnie 1.',
            'moc_silnika.max' => 'Pole \'Moc silnika\' może wynosić maksymalnie 5 000.',

            'rodzaj_paliwa.required' => 'Pole \'Rodzaj paliwa\' nie może być puste.',
            'rodzaj_paliwa.in' => 'Pole \'Rodzaj paliwa\' musi mieć wybraną prawidłową wartość.',

            'liczba_drzwi.required' => 'Pole \'Liczba drzwi\' nie może być puste.',
            'liczba_drzwi.integer' => 'Pole \'Liczba drzwi\' musi być liczbą całkowitą.',
            'liczba_drzwi.min' => 'Pole \'Liczba drzwi\' musi wynosić minimalnie 1.',
            'liczba_drzwi.max' => 'Pole \'Liczba drzwi\' może wynosić maksymalnie 9.',

            'liczba_miejsc.required' => 'Pole \'Liczba miejsc\' nie może być puste.',
            'liczba_miejsc.integer' => 'Pole \'Liczba miejsc\' musi być liczbą całkowitą.',
            'liczba_miejsc.min' => 'Pole \'Liczba miejsc\' musi wynosić minimalnie 1.',
            'liczba_miejsc.max' => 'Pole \'Liczba miejsc\' może wynosić maksymalnie 99.',

            'cena.required' => 'Pole \'Cena\' nie może być puste.',
            'cena.numeric' => 'Pole \'Cena\' musi być liczbą.',
            'cena.min' => 'Pole \'Cena\' musi wynosić minimalnie 1.',
            'cena.max' => 'Pole \'Cena\' może wynosić maksymalnie 50 000 000.',

            'zdjecia_pojazdow_nowe.*.file' => 'Pole \'Zdjęcia pojazdu\' musi zawierać tylko pliki.',
            'zdjecia_pojazdow_nowe.*.mimes' => 'Pole \'Zdjęcia pojazdu\' musi zawierać zdjęcia z rozszerzeniem .jpg, .jpeg lub .png.',
            'zdjecia_pojazdow_nowe.*.max' => 'Maksymalny rozmiar jednego zdjęcia w polu \'Zdjęcia pojazdu\' może wynosić maksymalnie 10MB.',
            'zdjecia_pojazdow_nowe.max' => 'Pole \'Zdjęcia pojazdu\' może zawierać maksymalnie ' . $maxZdjecia . ' nowe zdjęcia.',
        ];
    }

    private function handleVehiclePhotos($request, $pojazd)
    {
        if ($request->has('zdjecia_pojazdow_usun')) {
            foreach ($request->input('zdjecia_pojazdow_usun') as $id_zdjecia) {
                $zdjecie = ZdjeciePojazdu::findOrFail($id_zdjecia);
                Storage::delete('public/img/vehicles/' . $zdjecie->nazwa_zdjecia);
                $zdjecie->delete();
            }
        }

        if ($request->hasFile('zdjecia_pojazdow_nowe')) {
            foreach ($request->file('zdjecia_pojazdow_nowe') as $plik) {
                $oryginalnaNazwa = $plik->getClientOriginalName();
                $sciezkaZdjecia = $plik->storeAs('public/img/vehicles', $oryginalnaNazwa);

                // ZdjeciePojazdu::create([
                //     'id_pojazdu' => $pojazd->id_pojazdu,
                //     'nazwa_zdjecia' => $oryginalnaNazwa
                // ]);
                DB::select("SELECT dodaj_zdjecie_pojazdu(?, ?)", [$pojazd->id_pojazdu, $oryginalnaNazwa]); // Funkcja SQL
            }
        }
    }

    public function editValidate(Request $request)
    {
        $markaInputType = $request->input('marka_input_type');
        $modelInputType = $request->input('model_input_type');
        $nadwozieInputType = $request->input('nadwozie_input_type');

        $this->storeVehicleDataInSession($request, $markaInputType, $modelInputType, $nadwozieInputType);

        $zdjeciaPojazdowUsun = $request->input('zdjecia_pojazdow_usun', []);
        $request->session()->put('zdjeciaPojazdowUsun', $zdjeciaPojazdowUsun);

        $IDpojazdu = $request->input('IDpojazdu');

        // $pojazd = Pojazd::with('zdjecia')->findOrFail($IDpojazdu);
        $pojazdSQL = DB::select("SELECT * FROM pobierz_dane_pojazdu_plus_cechy_zdjecia(?)", [$IDpojazdu]); // Funkcja SQL

        if (empty($pojazdSQL)) {
            return redirect()->route('index')->with('error', 'Nie znaleziono pojazdu.');
        }

        $pojazd = new Pojazd(json_decode($pojazdSQL[0]->pojazd_data, true));
        $pojazd->setRelation('zdjecia', collect(json_decode($pojazdSQL[0]->zdjecia_pojazdu_data, true))->map(function ($zdjecie) {
            return new ZdjeciePojazdu(['id_zdjecia' => $zdjecie['id_zdjecia'], 'id_pojazdu' => $zdjecie['id_pojazdu'], 'nazwa_zdjecia' => $zdjecie['nazwa_zdjecia']]);
        }));

        $maxZdjecia = 3 - $pojazd->zdjecia->count();
        $usunieteZdjeciaLiczba = count($request->input('zdjecia_pojazdow_usun', []));
        $maxZdjecia += $usunieteZdjeciaLiczba;

        $rules = $this->getValidationRules($markaInputType, $modelInputType, $nadwozieInputType, $maxZdjecia);
        $validatedData = $request->validate($rules, $this->getValidationMessages($maxZdjecia));

        DB::beginTransaction();
        try {
            $marka = $request->input($markaInputType === 'text' ? 'marka' : 'marka_select');
            $model = $request->input($modelInputType === 'text' ? 'model' : 'model_select');
            $nadwozie = $request->input($nadwozieInputType === 'text' ? 'nadwozie' : 'nadwozie_select');

            // $cechyPojazdu = CechyPojazdu::firstOrCreate(
            //     ['marka' => $marka, 'model' => $model, 'nadwozie' => $nadwozie]
            // );
            //
            // $pojazd = Pojazd::findOrFail($IDpojazdu);
            // $pojazd->update([
            //     'vin' => $validatedData['vin'],
            //     'rok_produkcji' => $validatedData['rok_produkcji'],
            //     'przebieg' => $validatedData['przebieg'],
            //     'pojemnosc_silnika' => $validatedData['pojemnosc_silnika'],
            //     'moc_silnika' => $validatedData['moc_silnika'],
            //     'rodzaj_paliwa' => $validatedData['rodzaj_paliwa'],
            //     'liczba_drzwi' => $validatedData['liczba_drzwi'],
            //     'liczba_miejsc' => $validatedData['liczba_miejsc'],
            //     'cena' => $validatedData['cena'],
            //     'id_cechy_pojazdu' => $cechyPojazdu->id_cechy_pojazdu,
            // ]);
            $wynikSQL = DB::select("SELECT aktualizuj_dane_pojazdu(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS wiadomosc", [
                $request->input('IDpojazdu'),
                $validatedData['vin'],
                $marka,
                $model,
                $nadwozie,
                $validatedData['rok_produkcji'],
                $validatedData['przebieg'],
                $validatedData['pojemnosc_silnika'],
                $validatedData['moc_silnika'],
                $validatedData['rodzaj_paliwa'],
                $validatedData['liczba_drzwi'],
                $validatedData['liczba_miejsc'],
                $validatedData['cena']
            ]); // Funkcja SQL

            $this->handleVehiclePhotos($request, $pojazd);

            DB::commit();
            return redirect()->route('vehicles.show', ['id' => $pojazd->id_pojazdu])->with('success', $wynikSQL[0]->wiadomosc);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Przepraszamy, wystąpił problem z edycją pojazdu, spróbuj ponownie później.');
        }
    }

    public function registerValidate(Request $request)
    {
        $markaInputType = $request->input('marka_input_type');
        $modelInputType = $request->input('model_input_type');
        $nadwozieInputType = $request->input('nadwozie_input_type');

        $this->storeVehicleDataInSession($request, $markaInputType, $modelInputType, $nadwozieInputType);

        $rules = $this->getValidationRules($markaInputType, $modelInputType, $nadwozieInputType);
        $validatedData = $request->validate($rules, $this->getValidationMessages());

        DB::beginTransaction();
        try {
            $marka = $request->input($markaInputType === 'text' ? 'marka' : 'marka_select');
            $model = $request->input($modelInputType === 'text' ? 'model' : 'model_select');
            $nadwozie = $request->input($nadwozieInputType === 'text' ? 'nadwozie' : 'nadwozie_select');

            $konto = Auth::user();

            // $klient = Klient::where('id_konta', $konto->id_konta)->first();
            $klientSQL = DB::select("SELECT * FROM pobierz_dane_klienta(?)", [$konto->id_konta]);

            if (empty($klientSQL)) {
                return redirect()->route('index')->with('error', 'Nie znaleziono klienta.');
            }

            $klient = new Klient(json_decode($klientSQL[0]->klient_data, true));

            // $cechyPojazdu = CechyPojazdu::firstOrCreate(
            //     ['marka' => $marka, 'model' => $model, 'nadwozie' => $nadwozie]
            // );
            //
            // $pojazd = Pojazd::create([
            //     'vin' => $validatedData['vin'],
            //     'rok_produkcji' => $validatedData['rok_produkcji'],
            //     'przebieg' => $validatedData['przebieg'],
            //     'pojemnosc_silnika' => $validatedData['pojemnosc_silnika'],
            //     'moc_silnika' => $validatedData['moc_silnika'],
            //     'rodzaj_paliwa' => $validatedData['rodzaj_paliwa'],
            //     'liczba_drzwi' => $validatedData['liczba_drzwi'],
            //     'liczba_miejsc' => $validatedData['liczba_miejsc'],
            //     'cena' => $validatedData['cena'],
            //     'id_cechy_pojazdu' => $cechyPojazdu->id_cechy_pojazdu,
            //     'id_wlasciciela' => $klient->id_klienta,
            //     'status_pojazdu' => 'W bazie',
            // ]);
            $wynikSQL = DB::select("SELECT dodaj_nowy_pojazd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS wiadomosc", [
                $validatedData['vin'],
                $marka,
                $model,
                $nadwozie,
                $validatedData['rok_produkcji'],
                $validatedData['przebieg'],
                $validatedData['pojemnosc_silnika'],
                $validatedData['moc_silnika'],
                $validatedData['rodzaj_paliwa'],
                $validatedData['liczba_drzwi'],
                $validatedData['liczba_miejsc'],
                $validatedData['cena'],
                $klient->id_klienta
            ]); // Funkcja SQL

            $IDpojazdu = DB::getPdo()->lastInsertId();

            // $pojazd = Pojazd::with('zdjecia')->findOrFail($IDpojazdu);
            $pojazdSQL = DB::select("SELECT * FROM pobierz_dane_pojazdu_plus_cechy_zdjecia(?)", [$IDpojazdu]); // Funkcja SQL

            if (empty($pojazdSQL)) {
                return redirect()->route('index')->with('error', 'Nie znaleziono pojazdu.');
            }

            $pojazd = new Pojazd(json_decode($pojazdSQL[0]->pojazd_data, true));
            $pojazd->setRelation('zdjecia', collect(json_decode($pojazdSQL[0]->zdjecia_pojazdu_data, true))->map(function ($zdjecie) {
                return new ZdjeciePojazdu(['id_zdjecia' => $zdjecie['id_zdjecia'], 'id_pojazdu' => $zdjecie['id_pojazdu'], 'nazwa_zdjecia' => $zdjecie['nazwa_zdjecia']]);
            }));

            $this->handleVehiclePhotos($request, $pojazd);

            DB::commit();
            return redirect()->route('vehicles.show', ['id' => $pojazd->id_pojazdu])->with('success', $wynikSQL[0]->wiadomosc);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Przepraszamy, wystąpił problem z dodawaniem pojazdu, spróbuj ponownie później.');
        }
    }
}
