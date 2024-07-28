<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Logowanie do aplikacji'])

<body>
    @include('shared.header')

    <main>
        <div class="container my-3">
            <div class="row mb-4 text-center">
                <h1>Zaloguj się</h1>
            </div>
            @include('shared.validation-error')
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <form method="POST" action="{{ route('auth.login.loginAuthenticate') }}" class="needs-validation"
                        novalidate>
                        @csrf
                        <div class="form-group mb-3 card card-p-bg-blue">
                            <label for="login" class="form-label">Login</label>
                            <input id="login" name="login" type="text"
                                class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }} {{ $errors->has('blad_logowania') ? 'is-invalid' : '' }}"
                                value="{{ old('login') }}">
                            @if ($errors->has('login') && empty(old('login')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-blue">
                            <label for="haslo" class="form-label">Hasło</label>
                            <input id="haslo" name="haslo" type="password"
                                class="form-control {{ $errors->has('haslo') ? 'is-invalid' : '' }} {{ $errors->has('blad_logowania') ? 'is-invalid' : '' }}">
                            @if ($errors->has('haslo') && empty(old('haslo')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="text-center mt-4 mb-3">
                            <input class="btn btn-primary" type="submit" value="Zaloguj się">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('shared.footer')
</body>

</html>
