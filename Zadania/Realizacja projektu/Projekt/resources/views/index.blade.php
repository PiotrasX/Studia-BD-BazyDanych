<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
@include('shared.head', ['pageTitle' => 'Strona główna'])

<body>
    @include('shared.header')

    @include('shared.session-message')

    <main>
        @if (Auth::check())
            <div class="container my-3">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-3">Witaj, {{ $konto->dane->imie }} {{ $konto->dane->nazwisko }}</h1>
                        @if ($konto->typ_konta === 'klient')
                            <p>Twój stan konta: {{ number_format($klient->stan_konta, 2, ',', ' ') }} zł</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (Auth::check())
            <div id="pojazdy" class="container my-5 mb-3">
            @else
                <div id="pojazdy" class="container my-3">
        @endif
        <div class="row">
            @if ($czyAdmin)
                <h1 class="col-12">Lista pojazdów w bazie danych</h1>
            @else
                <h1 class="col-12">Lista pojazdów na sprzedaż</h1>
            @endif
        </div>
        <div class="row">
            @forelse ($pojazdy as $pojazd)
                <div class="col-12 mb-3">
                    <div class="card bg-card-white">
                        <div class="row no-gutters">
                            <div class="col-12 col-md-7 col-xl-8 d-flex justify-content-center align-items-center">
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
                            <div class="col-12 col-md-5 col-xl-4 px-md-1 px-lg-3 px-xl-5">
                                <div class="card-body mt-3">
                                    <h4 class="card-title mb-3">{{ $pojazd->cechyPojazdu->marka }}
                                        {{ $pojazd->cechyPojazdu->model }}</h4>
                                    <span class="card-text new-line">Nadwozie:
                                        {{ $pojazd->cechyPojazdu->nadwozie }}</span>
                                    <span class="card-text new-line">Rok produkcji: {{ $pojazd->rok_produkcji }}</span>
                                    <span class="card-text new-line">Przebieg:
                                        {{ number_format($pojazd->przebieg, 0, ',', ' ') }} km</span>
                                    <span class="card-text new-line">Cena:
                                        {{ number_format($pojazd->cena, 2, ',', ' ') }} zł</span>
                                    <span class="card-text new-line">Status pojazdu:
                                        {{ $pojazd->status_pojazdu }}</span>
                                    <a href="{{ route('vehicles.show', ['id' => $pojazd->id_pojazdu]) }}"
                                        class="btn btn-primary mt-3">Więcej szczegółów...</a>
                                    @if ($klient && $konto->typ_konta === 'klient' && $pojazd->id_wlasciciela !== $klient->id_klienta)
                                        <form action="{{ route('vehicles.purchase', $pojazd->id_pojazdu) }}"
                                            method="POST"
                                            onsubmit="return confirm('Czy na pewno chcesz zakupić ten pojazd?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success mt-3">Kup pojazd</button>
                                        </form>
                                    @endif
                                    @if ($pracownik && $konto->typ_konta === 'pracownik')
                                        @php
                                            $serwisowanyPojazd = \App\Models\SerwisowanyPojazd::where(
                                                'id_pojazdu',
                                                $pojazd->id_pojazdu,
                                            )
                                                ->where('id_pracownika', $pracownik->id_pracownika)
                                                ->orderBy('data_poczatku_serwisu', 'desc')
                                                ->orderBy('id_serwisu', 'desc')
                                                ->whereNull('data_konca_serwisu')
                                                ->first();
                                        @endphp
                                        @if ($serwisowanyPojazd)
                                            <form action="{{ route('vehicles.endService', $pojazd->id_pojazdu) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-warning mt-3">Zakończ
                                                    serwis</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @if ($czyAdmin)
                    <p class="col-12">Aktualnie nie ma żadnych pojazdów w bazie danych.</p>
                @else
                    <p class="col-12">Aktualnie nie ma żadnych pojazdów na sprzedaż.</p>
                @endif
            @endforelse
        </div>
        </div>
    </main>

    @include('shared.footer')
</body>

</html>
