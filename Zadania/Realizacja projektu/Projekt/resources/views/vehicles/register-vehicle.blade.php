<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Dodaj nowy pojazd'])

@php
    use App\Models\CechyPojazdu;
    $marki = CechyPojazdu::select('marka')->distinct()->orderBy('marka', 'asc')->pluck('marka');
    $modele = CechyPojazdu::select('model')->distinct()->orderBy('model', 'asc')->pluck('model');
    $nadwozia = CechyPojazdu::select('nadwozie')->distinct()->orderBy('nadwozie', 'asc')->pluck('nadwozie');
@endphp

<body>
    @include('shared.header')

    <main>
        <div class="container my-3">
            <div class="row mb-4 text-center">
                <h1>Dodawanie nowego pojazdu</h1>
            </div>
            @include('shared.validation-error')
            <div class="row d-flex justify-content-center">
                <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <form method="POST" action="{{ route('vehicles.register.registerValidate') }}"
                        class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="vin" class="form-label">VIN</label>
                            <input id="vin" name="vin" type="text"
                                class="form-control {{ $errors->has('vin') ? 'is-invalid' : '' }}"
                                value="{{ old('vin') }}">
                            @if ($errors->has('vin') && empty(old('vin')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="marka" class="form-label">Marka</label>
                            <div class="btn-group btn-group-toggle input-group mb-3" data-toggle="buttons">
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'text' ? 'active' : '' }}">
                                    <input type="radio" name="marka_input_type" id="marka_text" value="text"
                                        {{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'text' ? 'checked' : '' }}>
                                    Wpisz
                                </label>
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'select' ? 'active' : '' }}">
                                    <input type="radio" name="marka_input_type" id="marka_select" value="select"
                                        {{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'select' ? 'checked' : '' }}>
                                    Wybierz
                                </label>
                            </div>
                            <input id="marka_input" name="marka" type="text"
                                class="form-control {{ $errors->has('marka') ? 'is-invalid' : '' }}"
                                value="{{ old('marka') }}"
                                style="{{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'select' ? 'display: none;' : 'display: block;' }}">
                            <select id="marka_select_option" name="marka_select"
                                class="form-select form-control {{ $errors->has('marka_select') ? 'is-invalid' : '' }}"
                                style="{{ old('marka_input_type', $selectedInputTypeMarkaRegister) === 'text' ? 'display: none;' : 'display: block;' }}">
                                <option value="">Wybierz markę</option>
                                @foreach ($marki as $marka)
                                    <option value="{{ $marka }}"
                                        {{ old('marka_select') === $marka ? 'selected' : '' }}>{{ $marka }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('marka') && empty(old('marka')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                            @if ($errors->has('marka_select'))
                                <div class="invalid-feedback">Wybierz to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="model" class="form-label">Model</label>
                            <div class="btn-group btn-group-toggle input-group mb-3" data-toggle="buttons">
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('model_input_type', $selectedInputTypeModelRegister) === 'text' ? 'active' : '' }}">
                                    <input type="radio" name="model_input_type" id="model_text" value="text"
                                        {{ old('model_input_type', $selectedInputTypeModelRegister) === 'text' ? 'checked' : '' }}>
                                    Wpisz
                                </label>
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('model_input_type', $selectedInputTypeModelRegister) === 'select' ? 'active' : '' }}">
                                    <input type="radio" name="model_input_type" id="model_select" value="select"
                                        {{ old('model_input_type', $selectedInputTypeModelRegister) === 'select' ? 'checked' : '' }}>
                                    Wybierz
                                </label>
                            </div>
                            <input id="model_input" name="model" type="text"
                                class="form-control {{ $errors->has('model') ? 'is-invalid' : '' }}"
                                value="{{ old('model') }}"
                                style="{{ old('model_input_type', $selectedInputTypeModelRegister) === 'select' ? 'display: none;' : 'display: block;' }}">
                            <select id="model_select_option" name="model_select"
                                class="form-select form-control {{ $errors->has('model_select') ? 'is-invalid' : '' }}"
                                style="{{ old('model_input_type', $selectedInputTypeModelRegister) === 'text' ? 'display: none;' : 'display: block;' }}">
                                <option value="">Wybierz model</option>
                                @foreach ($modele as $model)
                                    <option value="{{ $model }}"
                                        {{ old('model_select') === $model ? 'selected' : '' }}>{{ $model }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('model') && empty(old('model')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                            @if ($errors->has('model_select'))
                                <div class="invalid-feedback">Wybierz to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="nadwozie" class="form-label">Nadwozie</label>
                            <div class="btn-group btn-group-toggle input-group mb-3" data-toggle="buttons">
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'text' ? 'active' : '' }}">
                                    <input type="radio" name="nadwozie_input_type" id="nadwozie_text" value="text"
                                        {{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'text' ? 'checked' : '' }}>
                                    Wpisz
                                </label>
                                <label
                                    class="btn btn-secondary custom-toggle {{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'select' ? 'active' : '' }}">
                                    <input type="radio" name="nadwozie_input_type" id="nadwozie_select" value="select"
                                        {{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'select' ? 'checked' : '' }}>
                                    Wybierz
                                </label>
                            </div>
                            <input id="nadwozie_input" name="nadwozie" type="text"
                                class="form-control {{ $errors->has('nadwozie') ? 'is-invalid' : '' }}"
                                value="{{ old('nadwozie') }}"
                                style="{{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'select' ? 'display: none;' : 'display: block;' }}">
                            <select id="nadwozie_select_option" name="nadwozie_select"
                                class="form-select form-control {{ $errors->has('nadwozie_select') ? 'is-invalid' : '' }}"
                                style="{{ old('nadwozie_input_type', $selectedInputTypeNadwozieRegister) === 'text' ? 'display: none;' : 'display: block;' }}">
                                <option value="">Wybierz nadwozie</option>
                                @foreach ($nadwozia as $nadwozie)
                                    <option value="{{ $nadwozie }}"
                                        {{ old('nadwozie_select') === $nadwozie ? 'selected' : '' }}>
                                        {{ $nadwozie }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('nadwozie') && empty(old('nadwozie')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                            @if ($errors->has('nadwozie_select'))
                                <div class="invalid-feedback">Wybierz to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="rok_produkcji" class="form-label">Rok produkcji</label>
                            <input id="rok_produkcji" name="rok_produkcji" type="text"
                                class="form-control {{ $errors->has('rok_produkcji') ? 'is-invalid' : '' }}"
                                value="{{ old('rok_produkcji') }}">
                            @if ($errors->has('rok_produkcji') && empty(old('rok_produkcji')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="przebieg" class="form-label">Przebieg</label>
                            <div class="input-group mb-2">
                                <input id="przebieg" name="przebieg" type="text"
                                    class="form-control {{ $errors->has('przebieg') ? 'is-invalid' : '' }}"
                                    value="{{ old('przebieg') }}">
                                <span class="input-group-text">km </span>
                                @if ($errors->has('przebieg') && empty(old('przebieg')))
                                    <div class="invalid-feedback">Uzupełnij to pole!</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="pojemnosc_silnika" class="form-label">Pojemność silnika</label>
                            <div class="input-group mb-2">
                                <input id="pojemnosc_silnika" name="pojemnosc_silnika" type="text"
                                    class="form-control {{ $errors->has('pojemnosc_silnika') ? 'is-invalid' : '' }}"
                                    value="{{ old('pojemnosc_silnika') }}">
                                <span class="input-group-text">cm³</span>
                                @if ($errors->has('pojemnosc_silnika') && empty(old('pojemnosc_silnika')))
                                    <div class="invalid-feedback">Uzupełnij to pole!</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="moc_silnika" class="form-label">Moc silnika</label>
                            <div class="input-group mb-2">
                                <input id="moc_silnika" name="moc_silnika" type="text"
                                    class="form-control {{ $errors->has('moc_silnika') ? 'is-invalid' : '' }}"
                                    value="{{ old('moc_silnika') }}">
                                <span class="input-group-text">KM </span>
                                @if ($errors->has('moc_silnika') && empty(old('moc_silnika')))
                                    <div class="invalid-feedback">Uzupełnij to pole!</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="rodzaj_paliwa" class="form-label">Rodzaj paliwa</label>
                            <select id="rodzaj_paliwa" name="rodzaj_paliwa"
                                class="form-select form-control {{ $errors->has('rodzaj_paliwa') ? 'is-invalid' : '' }}">
                                <option value="">Wybierz rodzaj paliwa</option>
                                <option value="benzyna" {{ old('rodzaj_paliwa') === 'benzyna' ? 'selected' : '' }}>
                                    benzyna</option>
                                <option value="benzyna + gaz"
                                    {{ old('rodzaj_paliwa') === 'benzyna + gaz' ? 'selected' : '' }}>benzyna + gaz
                                </option>
                                <option value="diesel" {{ old('rodzaj_paliwa') === 'diesel' ? 'selected' : '' }}>
                                    diesel</option>
                                <option value="diesel + gaz"
                                    {{ old('rodzaj_paliwa') === 'diesel + gaz' ? 'selected' : '' }}>diesel + gaz
                                </option>
                            </select>
                            @if ($errors->has('rodzaj_paliwa'))
                                <div class="invalid-feedback">Wybierz to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="liczba_drzwi" class="form-label">Liczba drzwi</label>
                            <input id="liczba_drzwi" name="liczba_drzwi" type="text"
                                class="form-control {{ $errors->has('liczba_drzwi') ? 'is-invalid' : '' }}"
                                value="{{ old('liczba_drzwi') }}">
                            @if ($errors->has('liczba_drzwi') && empty(old('liczba_drzwi')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="liczba_miejsc" class="form-label">Liczba miejsc</label>
                            <input id="liczba_miejsc" name="liczba_miejsc" type="text"
                                class="form-control {{ $errors->has('liczba_miejsc') ? 'is-invalid' : '' }}"
                                value="{{ old('liczba_miejsc') }}">
                            @if ($errors->has('liczba_miejsc') && empty(old('liczba_miejsc')))
                                <div class="invalid-feedback">Uzupełnij to pole!</div>
                            @endif
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="cena" class="form-label">Cena</label>
                            <div class="input-group mb-2">
                                <input id="cena" name="cena" type="text"
                                    class="form-control {{ $errors->has('cena') ? 'is-invalid' : '' }}"
                                    value="{{ old('cena') }}">
                                <span class="input-group-text">PLN</span>
                                @if ($errors->has('cena') && empty(old('cena')))
                                    <div class="invalid-feedback">Uzupełnij to pole!</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3 card card-p-bg-yellow">
                            <label for="zdjecia_pojazdow" class="form-label">Zdjęcia pojazdu (maksymalnie 3):</label>
                            <div class="input-group mb-2">
                                <input name="zdjecia_pojazdow_nowe[]" type="file"
                                    class="form-control {{ $errors->has('zdjecia_pojazdow_nowe') ? 'is-invalid' : '' }} {{ $errors->has('zdjecia_pojazdow_nowe.*') ? 'is-invalid' : '' }}"
                                    id="zdjecia_pojazdow" multiple enctype="multipart/form-data"
                                    accept=".jpg, .jpeg, .png">
                                <span class="input-group-text">IMG</span>
                            </div>
                        </div>
                        <div class="text-center mt-4 mb-3">
                            <input class="btn btn-primary" type="submit" value="Dodaj pojazd">
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
    document.getElementById('marka_text').addEventListener('change', function() {
        document.getElementById('marka_input').style.display = 'block';
        document.getElementById('marka_select_option').style.display = 'none';
    });
    document.getElementById('marka_select').addEventListener('change', function() {
        document.getElementById('marka_input').style.display = 'none';
        document.getElementById('marka_select_option').style.display = 'block';
    });

    document.getElementById('model_text').addEventListener('change', function() {
        document.getElementById('model_input').style.display = 'block';
        document.getElementById('model_select_option').style.display = 'none';
    });
    document.getElementById('model_select').addEventListener('change', function() {
        document.getElementById('model_input').style.display = 'none';
        document.getElementById('model_select_option').style.display = 'block';
    });

    document.getElementById('nadwozie_text').addEventListener('change', function() {
        document.getElementById('nadwozie_input').style.display = 'block';
        document.getElementById('nadwozie_select_option').style.display = 'none';
    });
    document.getElementById('nadwozie_select').addEventListener('change', function() {
        document.getElementById('nadwozie_input').style.display = 'none';
        document.getElementById('nadwozie_select_option').style.display = 'block';
    });
</script>
