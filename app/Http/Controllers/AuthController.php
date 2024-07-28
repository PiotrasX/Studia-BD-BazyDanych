<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Konto;
use App\Models\Klient;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('auth.login');
    }

    private function validateLoginData(Request $request)
    {
        return $request->validate([
            'login' => 'required|string|min:4|max:255',
            'haslo' => 'required|string|min:4|max:255',
        ], [
            'login.required' => 'Pole \'Login\' nie może być puste.',
            'login.string' => 'Pole \'Login\' musi być ciągiem znaków.',
            'login.min' => 'Pole \'Login\' musi zawierać co najmniej 4 znaki.',
            'login.max' => 'Pole \'Login\' może zawierać maksymalnie 255 znaków.',
            'haslo.required' => 'Pole \'Hasło\' nie może być puste.',
            'haslo.string' => 'Pole \'Hasło\' musi być ciągiem znaków.',
            'haslo.min' => 'Pole \'Hasło\' musi zawierać co najmniej 4 znaki.',
            'haslo.max' => 'Pole \'Hasło\' może zawierać maksymalnie 255 znaków.',
        ]);
    }

    public function loginAuthenticate(Request $request)
    {
        $credentials = $this->validateLoginData($request);

        if (Auth::attempt(['login' => $credentials['login'], 'password' => $credentials['haslo']])) {
            $request->session()->regenerate();
            return redirect()->route('index')->with('success', 'Zalogowano pomyślnie.');
        } else {
            return back()->withErrors([
                'blad_logowania' => 'Podany \'Login\' lub \'Hasło\' są nieprawidłowe.',
            ])->withInput($request->only('login', 'haslo'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    public function redirectToHome()
    {
        return redirect()->route('index');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('auth.register');
    }

    public function validateRegisterData(Request $request)
    {
        return $request->validate([
            // Dane użytkownika
            'imie' => 'required|string|max:255',
            'nazwisko' => 'required|string|max:255',
            'numer_telefonu' => 'required|min:7|max:15|regex:/^\+?\d{7,15}$/',
            'email' => 'required|string|email|max:255|unique:dane,email',
            'ulica' => 'required|string|max:255',
            'numer_domu' => 'required|string|max:255',
            'kod_pocztowy' => 'required|string|max:255',
            'miejscowosc' => 'required|string|max:255',
            'kraj' => 'required|string|exists:kraje,nazwa',

            // Dane konta
            'login' => 'required|string|min:4|max:255|unique:konta,login',
            'haslo' => 'required|string|min:4|max:255|confirmed',
        ], [
            'imie.required' => 'Pole \'Imię\' nie może być puste.',
            'imie.string' => 'Pole \'Imię\' musi być ciągiem znaków.',
            'imie.max' => 'Pole \'Imię\' może zawierać maksymalnie 255 znaków.',

            'nazwisko.required' => 'Pole \'Nazwisko\' nie może być puste.',
            'nazwisko.string' => 'Pole \'Nazwisko\' musi być ciągiem znaków.',
            'nazwisko.max' => 'Pole \'Nazwisko\' może zawierać maksymalnie 255 znaków.',

            'numer_telefonu.required' => 'Pole \'Numer telefonu\' nie może być puste.',
            'numer_telefonu.min' => 'Pole \'Numer telefonu\' musi zawierać co najmniej 7 znaków.',
            'numer_telefonu.max' => 'Pole \'Numer telefonu\' może zawierać maksymalnie 15 znaków.',
            'numer_telefonu.regex' => 'Pole \'Numer telefonu\' jest nieprawidłowe.',

            'email.required' => 'Pole \'Email\' nie może być puste.',
            'email.string' => 'Pole \'Email\' musi być ciągiem znaków.',
            'email.email' => 'Pole \'Email\' musi być prawidłowym adresem email.',
            'email.max' => 'Pole \'Email\' może zawierać maksymalnie 255 znaków.',
            'email.unique' => 'Podany adres email jest już zajęty.',

            'ulica.required' => 'Pole \'Ulica\' nie może być puste.',
            'ulica.string' => 'Pole \'Ulica\' musi być ciągiem znaków.',
            'ulica.max' => 'Pole \'Ulica\' może zawierać maksymalnie 255 znaków.',

            'numer_domu.required' => 'Pole \'Numer domu\' nie może być puste.',
            'numer_domu.string' => 'Pole \'Numer domu\' musi być ciągiem znaków.',
            'numer_domu.max' => 'Pole \'Numer domu\' może zawierać maksymalnie 255 znaków.',

            'kod_pocztowy.required' => 'Pole \'Kod pocztowy\' nie może być puste.',
            'kod_pocztowy.string' => 'Pole \'Kod pocztowy\' musi być ciągiem znaków.',
            'kod_pocztowy.max' => 'Pole \'Kod pocztowy\' może zawierać maksymalnie 255 znaków.',

            'miejscowosc.required' => 'Pole \'Miejscowość\' nie może być puste.',
            'miejscowosc.string' => 'Pole \'Miejscowość\' musi być ciągiem znaków.',
            'miejscowosc.max' => 'Pole \'Miejscowość\' może zawierać maksymalnie 255 znaków.',

            'kraj.required' => 'Pole \'Kraj\' nie może być puste.',
            'kraj.string' => 'Pole \'Kraj\' musi być ciągiem znaków.',
            'kraj.exists' => 'Wybrany kraj jest nieprawidłowy.',

            'login.required' => 'Pole \'Login\' nie może być puste.',
            'login.string' => 'Pole \'Login\' musi być ciągiem znaków.',
            'login.min' => 'Pole \'Login\' musi zawierać co najmniej 4 znaki.',
            'login.max' => 'Pole \'Login\' może zawierać maksymalnie 255 znaków.',
            'login.unique' => 'Podany login jest już zajęty.',

            'haslo.required' => 'Pole \'Hasło\' nie może być puste.',
            'haslo.string' => 'Pole \'Hasło\' musi być ciągiem znaków.',
            'haslo.min' => 'Pole \'Hasło\' musi zawierać co najmniej 4 znaki.',
            'haslo.max' => 'Pole \'Hasło\' może zawierać maksymalnie 255 znaków.',
            'haslo.confirmed' => 'Podane hasła nie są identyczne.',
        ]);
    }

    public function registerValidate(Request $request)
    {
        $validatedData = $this->validateRegisterData($request);

        try {
            // DB::beginTransaction();
            //
            // $dane = Dane::create([
            //     'imie' => $validatedData['imie'],
            //     'nazwisko' => $validatedData['nazwisko'],
            //     'numer_telefonu' => $validatedData['numer_telefonu'],
            //     'email' => $validatedData['email'],
            //     'ulica' => $validatedData['ulica'],
            //     'numer_domu' => $validatedData['numer_domu'],
            //     'kod_pocztowy' => $validatedData['kod_pocztowy'],
            //     'miejscowosc' => $validatedData['miejscowosc'],
            //     'kraj' => $validatedData['kraj'],
            // ]);
            //
            // $konto = Konto::create([
            //     'login' => $validatedData['login'],
            //     'haslo' => Hash::make($validatedData['haslo']),
            //     'typ_konta' => 'klient',
            //     'id_danych' => $dane->id_danych,
            // ]);
            //
            // $klient = Klient::create([
            //     'id_konta' => $konto->id_konta,
            //     'stan_konta' => 0,
            // ]);
            //
            // DB::commit();
            $hashedHaslo = Hash::make($validatedData['haslo']);
            $wynikSQL = DB::select("SELECT zarejestruj_uzytkownika(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS wiadomosc", [
                $validatedData['imie'],
                $validatedData['nazwisko'],
                $validatedData['numer_telefonu'],
                $validatedData['email'],
                $validatedData['ulica'],
                $validatedData['numer_domu'],
                $validatedData['kod_pocztowy'],
                $validatedData['miejscowosc'],
                $validatedData['kraj'],
                $validatedData['login'],
                $hashedHaslo
            ]); // Funkcja SQL

            $wiadomosc = $wynikSQL[0]->wiadomosc;

            if ($wiadomosc === 'Zarejestrowano konto pomyślnie.') {
                $konto = Konto::where('login', $validatedData['login'])->first();
                Auth::login($konto);

                return redirect()->route('index')->with('success', $wiadomosc);
            } else {
                return back()->withErrors($wiadomosc);
            }
        } catch (\Exception $e) {
            // DB::rollback();
            return back()->withErrors('Przepraszamy, wystąpił problem z rejestracją, spróbuj ponownie później.');
        }
    }

    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('index');
        }

        $konto = Auth::user();
        $dane = $konto->dane;

        // $klient = Klient::where('id_konta', $konto->id_konta)->first();
        $klientSQL = DB::select("SELECT * FROM pobierz_dane_klienta(?)", [$konto->id_konta]); // Funkcja SQL

        if (empty($klientSQL)) {
            return redirect()->route('index')->with('error', 'Nie znaleziono klienta.');
        }

        $klient = Klient::find(json_decode($klientSQL[0]->klient_data, true)['id_klienta']);

        return view('auth.edit', compact('konto', 'dane', 'klient'));
    }

    public function editValidate(Request $request)
    {
        $rules = [
            'imie' => 'required|string|max:255',
            'nazwisko' => 'required|string|max:255',
            'numer_telefonu' => 'required|min:7|max:15|regex:/^\+?\d{7,15}$/',
            'ulica' => 'required|string|max:255',
            'numer_domu' => 'required|string|max:255',
            'kod_pocztowy' => 'required|string|max:255',
            'miejscowosc' => 'required|string|max:255',
            'kraj' => 'required|string|exists:kraje,nazwa',
        ];

        if ($request->has('zmienPasswordCheckbox')) {
            $rules['haslo'] = 'required|string|min:4|max:255|confirmed';
        }

        $validatedData = $request->validate($rules, [
            'imie.required' => 'Pole \'Imię\' nie może być puste.',
            'imie.string' => 'Pole \'Imię\' musi być ciągiem znaków.',
            'imie.max' => 'Pole \'Imię\' może zawierać maksymalnie 255 znaków.',

            'nazwisko.required' => 'Pole \'Nazwisko\' nie może być puste.',
            'nazwisko.string' => 'Pole \'Nazwisko\' musi być ciągiem znaków.',
            'nazwisko.max' => 'Pole \'Nazwisko\' może zawierać maksymalnie 255 znaków.',

            'numer_telefonu.required' => 'Pole \'Numer telefonu\' nie może być puste.',
            'numer_telefonu.min' => 'Pole \'Numer telefonu\' musi zawierać co najmniej 7 znaków.',
            'numer_telefonu.max' => 'Pole \'Numer telefonu\' może zawierać maksymalnie 15 znaków.',
            'numer_telefonu.regex' => 'Pole \'Numer telefonu\' jest nieprawidłowe.',

            'ulica.required' => 'Pole \'Ulica\' nie może być puste.',
            'ulica.string' => 'Pole \'Ulica\' musi być ciągiem znaków.',
            'ulica.max' => 'Pole \'Ulica\' może zawierać maksymalnie 255 znaków.',

            'numer_domu.required' => 'Pole \'Numer domu\' nie może być puste.',
            'numer_domu.string' => 'Pole \'Numer domu\' musi być ciągiem znaków.',
            'numer_domu.max' => 'Pole \'Numer domu\' może zawierać maksymalnie 255 znaków.',

            'kod_pocztowy.required' => 'Pole \'Kod pocztowy\' nie może być puste.',
            'kod_pocztowy.string' => 'Pole \'Kod pocztowy\' musi być ciągiem znaków.',
            'kod_pocztowy.max' => 'Pole \'Kod pocztowy\' może zawierać maksymalnie 255 znaków.',

            'miejscowosc.required' => 'Pole \'Miejscowość\' nie może być puste.',
            'miejscowosc.string' => 'Pole \'Miejscowość\' musi być ciągiem znaków.',
            'miejscowosc.max' => 'Pole \'Miejscowość\' może zawierać maksymalnie 255 znaków.',

            'kraj.required' => 'Pole \'Kraj\' nie może być puste.',
            'kraj.string' => 'Pole \'Kraj\' musi być ciągiem znaków.',
            'kraj.exists' => 'Wybrany kraj jest nieprawidłowy.',

            'haslo.required' => 'Pole \'Hasło\' nie może być puste.',
            'haslo.string' => 'Pole \'Hasło\' musi być ciągiem znaków.',
            'haslo.min' => 'Pole \'Hasło\' musi zawierać co najmniej 4 znaki.',
            'haslo.max' => 'Pole \'Hasło\' może zawierać maksymalnie 255 znaków.',
            'haslo.confirmed' => 'Podane hasła nie są identyczne.',
        ]);

        try {
            // DB::beginTransaction();
            //
            // $dane = $konto->dane;
            //
            // $dane->update([
            //     'imie' => $validatedData['imie'],
            //     'nazwisko' => $validatedData['nazwisko'],
            //     'numer_telefonu' => $validatedData['numer_telefonu'],
            //     'ulica' => $validatedData['ulica'],
            //     'numer_domu' => $validatedData['numer_domu'],
            //     'kod_pocztowy' => $validatedData['kod_pocztowy'],
            //     'miejscowosc' => $validatedData['miejscowosc'],
            //     'kraj' => $validatedData['kraj'],
            // ]);
            //
            // if ($request->has('zmienPasswordCheckbox')) {
            //     DB::table('konta')
            //     ->where('id_konta', $konto->id_konta)
            //     ->update(['haslo' => Hash::make($validatedData['haslo'])]);
            // }
            //
            // DB::commit();
            $konto = Auth::user();
            $zmienPassword = $request->has('zmienPasswordCheckbox');
            $hashedHaslo = '';
            if ($zmienPassword === true) {
                $hashedHaslo = Hash::make($validatedData['haslo']);
            }
            $wynikSQL = DB::select("SELECT aktualizuj_dane_uzytkownika(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS wiadomosc", [
                $konto->id_konta,
                $validatedData['imie'],
                $validatedData['nazwisko'],
                $validatedData['numer_telefonu'],
                $validatedData['ulica'],
                $validatedData['numer_domu'],
                $validatedData['kod_pocztowy'],
                $validatedData['miejscowosc'],
                $validatedData['kraj'],
                $hashedHaslo,
                $zmienPassword
            ]); // Funkcja SQL

            $wiadomosc = $wynikSQL[0]->wiadomosc;

            if ($wiadomosc === 'Dane zaktualizowano pomyślnie.') {
                return redirect()->route('account')->with('success', $wiadomosc);
            } else {
                return back()->withErrors($wiadomosc);
            }
        } catch (\Exception $e) {
            // DB::rollback();
            return back()->withErrors('Przepraszamy, wystąpił problem z edycją, spróbuj ponownie później.');
        }
    }
}
