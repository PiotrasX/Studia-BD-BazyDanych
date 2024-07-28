<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Moje konto'])

<body>
    @include('shared.header')

    @include('shared.session-message')

    <main>
        <div class="container my-3">
            <div class="row">
                <div class="col-12 col-md-9 col-xl-10">
                    <h1 class="mb-3">Witaj, {{ $konto->dane->imie }} {{ $konto->dane->nazwisko }}</h1>
                    <p>Twój stan konta: {{ number_format($klient->stan_konta, 2, ',', ' ') }} zł</p>
                </div>
                <div class="col-12 col-md-3 col-xl-2">
                    <a href="{{ route('auth.edit') }}" class="btn btn-primary my-3 mt-md-0">Edytuj swoje dane</a>
                    <button type="button" class="btn btn-primary mt-2 mb-2 mt-md-0" data-bs-toggle="modal"
                        data-bs-target="#doladujKontoModal">Doładuj konto</button>
                    <div class="modal fade" id="doladujKontoModal" tabindex="-1"
                        aria-labelledby="doladujKontoModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('account.topUp') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="doladujKontoModalLabel">Doładuj swoje konto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kwota_doladowania" class="form-label">Kwota doładowania (0 - 100
                                                000)</label>
                                            <input type="number" class="form-control" id="kwota_doladowania"
                                                name="kwota_doladowania" min="0" max="100000" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Anuluj</button>
                                        <button type="submit" class="btn btn-primary">Doładuj</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="pojazdy" class="container my-5">
            <div class="row">
                <h1 class="col-12">Lista twoich pojazdów</h1>
            </div>
            <div class="row">
                @forelse ($pojazdy as $pojazd)
                    <div class="col-12 mb-3">
                        <div class="card bg-card-aqua">
                            <div class="row no-gutters">
                                <div class="col-12 col-lg-5 d-flex justify-content-center align-items-center">
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
                                        <div class="carousel slide w-100 m-3"
                                            id="vehicleCarousel{{ $pojazd->id_pojazdu }}">
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
                                <div class="col-12 col-sm-6 col-lg-3 col-xl-4 px-lg-3 px-xl-5">
                                    <div class="card-body mt-3">
                                        <h4 class="card-title mb-3">{{ $pojazd->cechyPojazdu->marka }}
                                            {{ $pojazd->cechyPojazdu->model }}</h4>
                                        <span class="card-text new-line">Nadwozie:
                                            {{ $pojazd->cechyPojazdu->nadwozie }}</span>
                                        <span class="card-text new-line">Rok produkcji:
                                            {{ $pojazd->rok_produkcji }}</span>
                                        <span class="card-text new-line">Przebieg:
                                            {{ number_format($pojazd->przebieg, 0, ',', ' ') }} km</span>
                                        <span class="card-text new-line">Cena:
                                            {{ number_format($pojazd->cena, 2, ',', ' ') }} zł</span>
                                        <span class="card-text new-line">Status pojazdu:
                                            {{ $pojazd->status_pojazdu }}</span>
                                        <a href="{{ route('vehicles.show', ['id' => $pojazd->id_pojazdu]) }}"
                                            class="btn btn-primary mt-3">Więcej szczegółów...</a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3 px-lg-3">
                                    <div class="card-body mt-3">
                                        @if ($pojazd->status_pojazdu === 'W bazie')
                                            <button type="button" class="btn btn-warning mt-1" data-bs-toggle="modal"
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
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
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
                                            <div><a href="{{ route('vehicles.edit', ['id' => $pojazd->id_pojazdu]) }}"
                                                    class="btn btn-warning mt-3">Edytuj dane</a></div>
                                            <form
                                                action="{{ route('vehicles.startSale', ['id' => $pojazd->id_pojazdu]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success mt-3">Wystaw
                                                    ogłoszenie</button>
                                            </form>
                                            <div><a href="" class="btn btn-success mt-3 disabled">Zakończ
                                                    ogłoszenie</a></div>
                                            <form
                                                action="{{ route('vehicles.delete', ['id' => $pojazd->id_pojazdu]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Czy na pewno chcesz usunąć ten pojazd z bazy?')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger mt-3">Usuń z
                                                    bazy</button>
                                            </form>
                                        @else
                                            <div><a href="" class="btn btn-warning mt-1 disabled">Wyślij do
                                                    serwisu</a></div>
                                            <div><a href="" class="btn btn-warning mt-3 disabled">Edytuj
                                                    dane</a></div>
                                            <div><a href="" class="btn btn-success mt-3 disabled">Wystaw
                                                    ogłoszenie</a></div>
                                            @if ($pojazd->status_pojazdu === 'Na sprzedaż')
                                                <form
                                                    action="{{ route('vehicles.endSale', ['id' => $pojazd->id_pojazdu]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success mt-3">Zakończ
                                                        ogłoszenie</button>
                                                </form>
                                            @else
                                                <div><a href="" class="btn btn-success mt-3 disabled">Zakończ
                                                        ogłoszenie</a></div>
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
                                                <div><a href="" class="btn btn-danger mt-3 disabled">Usuń z
                                                        bazy</a></div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-12">Aktualnie nie posiadasz żadnych dodanych pojazdów.</p>
                @endforelse
            </div>
        </div>

        <div class="container my-3">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3">Chcesz dodać nowy pojazd?</h1>
                    <p>Wypełnij formularz <a href="{{ route('vehicles.register') }}"
                            class="text-primary text-decoration-none">dodawania pojazdu</a> do bazy.</p>
                </div>
            </div>
        </div>
    </main>

    @include('shared.footer')
</body>

</html>
