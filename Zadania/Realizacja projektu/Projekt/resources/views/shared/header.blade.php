<header class="mb-5">
    <nav class="navbar navbar-expand-lg bg-color-gray">
        <div class="container-fluid">
            <a class="navbar-brand">Komis samochodowy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('index')) active @endif"
                            href="{{ route('index') }}">Strona główna</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @if (Auth::check())
                        @if (Auth::user()->typ_konta === 'klient')
                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('account')) active @endif"
                                    href="{{ route('account') }}">Moje konto</a>
                            </li>
                        @endif
                        @if (Auth::user()->typ_konta === 'pracownik')
                            <li class="nav-item">
                                <a class="nav-link @if (request()->routeIs('vehicles.showService')) active @endif"
                                    href="{{ route('vehicles.showService') }}">Serwisowane pojazdy</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                href="#">Wyloguj się...</a>
                            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link @if (request()->routeIs('auth.login')) active @endif"
                                href="{{ route('auth.login') }}">Zaloguj się...</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (request()->routeIs('auth.register')) active @endif"
                                href="{{ route('auth.register') }}">Zarejestruj się...</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
