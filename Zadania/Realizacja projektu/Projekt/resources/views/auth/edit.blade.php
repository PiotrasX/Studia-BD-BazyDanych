<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Edycja danych użytkownika'])

@php
    use App\Models\Kraj;
    $kraje = Kraj::all();
@endphp

<body>
    @include('shared.header')

    <main>
        <div class="container my-3">
            <div class="row mb-4 text-center">
                <h1>Edytuj dane</h1>
            </div>
            @include('shared.validation-error')
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <form method="POST" action="{{ route('auth.edit.editValidate') }}" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="row mt-4 mb-2 text-center">
                            <h3>Dane użytkownika</h3>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="imie" class="form-label">Imię</label>
                            <input id="imie" name="imie" type="text"
                                class="form-control {{ $errors->has('imie') ? 'is-invalid' : '' }}"
                                value="{{ old('imie', $dane->imie) }}">
                            @if ($errors->has('imie') && empty(old('imie')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="nazwisko" class="form-label">Nazwisko</label>
                            <input id="nazwisko" name="nazwisko" type="text"
                                class="form-control {{ $errors->has('nazwisko') ? 'is-invalid' : '' }}"
                                value="{{ old('nazwisko', $dane->nazwisko) }}">
                            @if ($errors->has('nazwisko') && empty(old('nazwisko')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="numer_telefonu" class="form-label">Numer telefonu</label>
                            <input id="numer_telefonu" name="numer_telefonu" type="text"
                                class="form-control {{ $errors->has('numer_telefonu') ? 'is-invalid' : '' }}"
                                value="{{ old('numer_telefonu', $dane->numer_telefonu) }}">
                            @if ($errors->has('numer_telefonu') && empty(old('numer_telefonu')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="text"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old($dane->email, $dane->email) }}" readonly disabled>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="ulica" class="form-label">Ulica</label>
                            <input id="ulica" name="ulica" type="text"
                                class="form-control {{ $errors->has('ulica') ? 'is-invalid' : '' }}"
                                value="{{ old('ulica', $dane->ulica) }}">
                            @if ($errors->has('ulica') && empty(old('ulica')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="numer_domu" class="form-label">Numer domu</label>
                            <input id="numer_domu" name="numer_domu" type="text"
                                class="form-control {{ $errors->has('numer_domu') ? 'is-invalid' : '' }}"
                                value="{{ old('numer_domu', $dane->numer_domu) }}">
                            @if ($errors->has('numer_domu') && empty(old('numer_domu')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="kod_pocztowy" class="form-label">Kod pocztowy</label>
                            <input id="kod_pocztowy" name="kod_pocztowy" type="text"
                                class="form-control {{ $errors->has('kod_pocztowy') ? 'is-invalid' : '' }}"
                                value="{{ old('kod_pocztowy', $dane->kod_pocztowy) }}">
                            @if ($errors->has('kod_pocztowy') && empty(old('kod_pocztowy')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="miejscowosc" class="form-label">Miejscowość</label>
                            <input id="miejscowosc" name="miejscowosc" type="text"
                                class="form-control {{ $errors->has('miejscowosc') ? 'is-invalid' : '' }}"
                                value="{{ old('miejscowosc', $dane->miejscowosc) }}">
                            @if ($errors->has('miejscowosc') && empty(old('miejscowosc')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-green">
                            <label for="kraj" class="form-label">Kraj</label>
                            <select id="kraj" name="kraj"
                                class="form-select form-control {{ $errors->has('kraj') ? 'is-invalid' : '' }}">
                                <option value="">Wybierz kraj</option>
                                @foreach ($kraje as $kraj)
                                    <option value="{{ $kraj->nazwa }}"
                                        {{ old('kraj', $dane->kraj) === $kraj->nazwa ? 'selected' : '' }}>
                                        {{ $kraj->nazwa }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('kraj'))
                                <div class="invalid-feedback">Wybierz to pole!</div>
                            @endif
                        </div>
                        <div class="row mt-5 mb-2 pt-2 text-center">
                            <h3>Dane konta</h3>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-blue">
                            <label for="login" class="form-label">Login</label>
                            <input id="login" name="login" type="text"
                                class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}"
                                value="{{ old($konto->login, $konto->login) }}" readonly disabled>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-blue">
                            <div class="form-check" style="margin-bottom: -5px">
                                <input class="form-check-input" type="checkbox" id="zmienPasswordCheckbox"
                                    name="zmienPasswordCheckbox">
                                <label class="form-check-label" for="zmienPasswordCheckbox">Zmień hasło</label>
                            </div>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-blue" id="passwordDiv" style="display: none;">
                            <label for="haslo" class="form-label">Hasło</label>
                            <input id="haslo" name="haslo" type="password"
                                class="form-control {{ $errors->has('haslo') ? 'is-invalid' : '' }}">
                            @if ($errors->has('haslo') && empty(old('haslo')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-blue" id="passwordConfirmationDiv"
                            style="display: none;">
                            <label for="haslo_confirmation" class="form-label">Potwierdź Hasło</label>
                            <input id="haslo_confirmation" name="haslo_confirmation" type="password"
                                class="form-control {{ $errors->has('haslo') ? 'is-invalid' : '' }}">
                            @if ($errors->has('haslo') && empty(old('haslo_confirmation')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="text-center mt-4 mb-3">
                            <input class="btn btn-primary" type="submit" value="Edytuj dane">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('shared.footer')
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordCheckbox = document.getElementById('zmienPasswordCheckbox');
        const passwordDiv = document.getElementById('passwordDiv');
        const passwordConfirmationDiv = document.getElementById('passwordConfirmationDiv');

        function togglePasswordVisibility(isChecked) {
            passwordDiv.style.display = isChecked ? 'block' : 'none';
            passwordConfirmationDiv.style.display = isChecked ? 'block' : 'none';
        }

        passwordCheckbox.addEventListener('change', function() {
            localStorage.setItem('zmienPasswordChecked', this.checked);
            togglePasswordVisibility(this.checked);
        });

        const isChecked = localStorage.getItem('zmienPasswordChecked') === 'true';
        passwordCheckbox.checked = isChecked;
        togglePasswordVisibility(isChecked);
    });
</script>
