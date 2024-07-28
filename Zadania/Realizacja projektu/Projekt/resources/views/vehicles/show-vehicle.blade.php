<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Szczegóły pojazdu'])

<body>
    @include('shared.header')

    @include('shared.session-message')

    <main>
        <div id="pojazd" class="container my-3">
            <div class="row">
                <h1 class="col-12">Szczegółowe informacje o pojeździe {{ $pojazd->cechyPojazdu->marka }}
                    {{ $pojazd->cechyPojazdu->model }}</h1>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-card-white">
                        <div class="row no-gutters">
                            <div class="col-12 col-lg-7 col-xxl-8 d-flex justify-content-center align-items-center">
                                @if ($pojazd->zdjecia->isEmpty())
                                    <div class="w-100 m-3">
                                        <img src="{{ asset('storage/img/brak_zdjecia.png') }}" class="card-img"
                                            alt="Domyślne zdjęcie">
                                    </div>
                                @elseif($pojazd->zdjecia->count() == 1)
                                    <div class="w-100 m-3">
                                        <img src="{{ asset('storage/img/vehicles/' . $pojazd->zdjecia->first()->nazwa_zdjecia) }}"
                                            class="card-img" alt="Zdjęcie pojazdu marki {{ $pojazd->marka }}">
                                    </div>
                                @else
                                    <div class="carousel slide w-100 m-3" id="vehicleCarousel{{ $pojazd->id_pojazdu }}">
                                        <div class="carousel-inner">
                                            @foreach ($pojazd->zdjecia as $index => $zdjecie)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/img/vehicles/' . $zdjecie->nazwa_zdjecia) }}"
                                                        class="card-img"
                                                        alt="Zdjęcie pojazdu marki {{ $pojazd->marka }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev"
                                            href="#vehicleCarousel{{ $pojazd->id_pojazdu }}" role="button"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Poprzednie</span>
                                        </a>
                                        <a class="carousel-control-next"
                                            href="#vehicleCarousel{{ $pojazd->id_pojazdu }}" role="button"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Następne</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 col-lg-5 col-xxl-4 px-lg-3 px-xl-5">
                                <div class="card-body mt-3">
                                    <h4 class="card-title mb-3">VIN: {{ $pojazd->vin }}</h4>
                                    <span class="card-text new-line">Nadwozie:
                                        {{ $pojazd->cechyPojazdu->nadwozie }}</span>
                                    <span class="card-text new-line">Rok produkcji: {{ $pojazd->rok_produkcji }}</span>
                                    <span class="card-text new-line">Przebieg:
                                        {{ number_format($pojazd->przebieg, 0, ',', ' ') }} km</span>
                                    <span class="card-text new-line">Pojemność silnika:
                                        {{ $pojazd->pojemnosc_silnika }} cm³</span>
                                    <span class="card-text new-line">Moc silnika: {{ $pojazd->moc_silnika }} KM</span>
                                    <span class="card-text new-line">Rodzaj paliwa: {{ $pojazd->rodzaj_paliwa }}</span>
                                    <span class="card-text new-line">Liczba drzwi: {{ $pojazd->liczba_drzwi }}</span>
                                    <span class="card-text new-line">Liczba miejsc: {{ $pojazd->liczba_miejsc }}</span>
                                    <span class="card-text new-line">Cena:
                                        {{ number_format($pojazd->cena, 2, ',', ' ') }} zł</span>
                                    <span class="card-text new-line">Status pojazdu:
                                        {{ $pojazd->status_pojazdu }}</span>
                                </div>
                            </div>
                            @if ($czyWlasciciel)
                                <div class="col-12">
                                    <div class="card-body d-flex flex-wrap">
                                        @if ($pojazd->status_pojazdu === 'W bazie')
                                            <button type="button" class="btn btn-warning me-3 mt-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#sendToServiceModal-{{ $pojazd->id_pojazdu }}">Wyślij
                                                do serwisu</button>
                                            <div class="modal fade" id="sendToServiceModal-{{ $pojazd->id_pojazdu }}"
                                                tabindex="-1"
                                                aria-labelledby="sendToServiceModalLabel-{{ $pojazd->id_pojazdu }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('vehicles.sendToService', ['id' => $pojazd->id_pojazdu]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="sendToServiceModalLabel">
                                                                    Wyślij do serwisu</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="opis_usterki" class="form-label">Opis
                                                                        usterki</label>
                                                                    <textarea class="form-control" id="opis_usterki" name="opis_usterki" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Anuluj</button>
                                                                <button type="submit"
                                                                    class="btn btn-warning">Wyślij</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('vehicles.edit', ['id' => $pojazd->id_pojazdu]) }}"
                                                class="btn btn-warning me-3 mt-3">Edytuj dane</a>
                                            <form
                                                action="{{ route('vehicles.startSale', ['id' => $pojazd->id_pojazdu]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success me-3 mt-3">Wystaw
                                                    ogłoszenie</button>
                                            </form>
                                            <a href="" class="btn btn-success me-3 mt-3 disabled">Zakończ
                                                ogłoszenie</a>
                                            <form
                                                action="{{ route('vehicles.delete', ['id' => $pojazd->id_pojazdu]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Czy na pewno chcesz usunąć ten pojazd z bazy?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger mt-3">Usuń z
                                                    bazy</button>
                                            </form>
                                        @else
                                            <a href="" class="btn btn-warning me-3 mt-3 disabled">Wyślij do
                                                serwisu</a>
                                            <a href="" class="btn btn-warning me-3 mt-3 disabled">Edytuj
                                                dane</a>
                                            <a href="" class="btn btn-success me-3 mt-3 disabled">Wystaw
                                                ogłoszenie</a>
                                            @if ($pojazd->status_pojazdu === 'Na sprzedaż')
                                                <form
                                                    action="{{ route('vehicles.endSale', ['id' => $pojazd->id_pojazdu]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success me-3 mt-3">Zakończ
                                                        ogłoszenie</button>
                                                </form>
                                            @else
                                                <a href="" class="btn btn-success me-3 mt-3 disabled">Zakończ
                                                    ogłoszenie</a>
                                            @endif
                                            @if ($pojazd->status_pojazdu === 'Sprzedany')
                                                <form
                                                    action="{{ route('vehicles.delete', ['id' => $pojazd->id_pojazdu]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Czy na pewno chcesz usunąć ten pojazd z bazy?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger mt-3">Usuń z
                                                        bazy</button>
                                                </form>
                                            @else
                                                <a href="" class="btn btn-danger mt-3 disabled">Usuń z bazy</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('shared.footer')
</body>

</html>
