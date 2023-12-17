<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <meta property="og:title" content="AdsUpp">
    <meta property="og:description" content="Publicar nunca fue tan facil">
    <link rel="shortcut icon" href="/images/logo_cuadrado.ico">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <meta http-equiv="Content-Security-Policy" content="script-src 'none'"> --}}

    <!-- CSRF Token -->


    <title>{{ config('app.name', 'AdsUpp') }}</title>

    <link rel='manifest' href='/manifest.json'>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="apple-touch-icon" sizes="512x512" href="icons/adsupp512.png">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <meta name="theme-color" content="#dd5757">
    <meta name="apple-mobile-web-app-status-bar-style" content="Black-translucent">

    <link rel="preload" href="/images/logo2.jpg" as="image">
    <link rel="prefetch" href="https://sdk.mercadopago.com/js/v2" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 20
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">

            <div class="container">
                @if (request()->route()->uri !== '/')
                    <a href="{{ url()->previous() }}" class="" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                            <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z" />
                        </svg>
                    </a>
                @else
                    <div style="width: 30px;">&nbsp;</div>
                @endif
                <a class="navbar-brand m-auto" href="/">
                    <img src="/images/logo2.jpg" alt="Logo adsupp" width="101px" height="48px">
                </a>
                <button class="navbar-toggler rounded btn-dark rounded-circle py-2 px-2 bg-primary text-white"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                    aria-controls="offcanvasWithBothOptions" aria-label="{{ __('Toggle navigation') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest
                    @else
                        <ul class="navbar-nav me-auto">
                            @can('client-list')
                                <li>
                                    <a class="dropdown-item" href="{{ route('sale.index') }}">Mi publicaciones</a>
                                </li>
                            @endcan
                        </ul>
                    @endguest
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Iniciar sesion</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Registrarme</a>
                                </li>
                            @endif
                        @else
                            @can('admin-list')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown2" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Mantenimientos
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{{ route('sale.index') }}">Por aprobar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('sale.create') }}">Cargar Campaña</a></li>
                                        <li><a class="dropdown-item" href="{{ route('grilla') }}">Programacion</a></li>
                                        {{-- <li><a class="dropdown-item" href="{{ route('screen.index') }}">Pantallas</a></li> --}}
                                        <li><a class="dropdown-item" href="{{ route('pagos.index') }}">Pagos</a></li>
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a></li>
                                        <li><a class="dropdown-item" href="{{ route('clients.index') }}">Clientes</a></li>


                                    </ul>
                                </li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown2" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item"
                                            href="{{ route('users.profile', Auth::user()->id) }}">Perfil</a></li>
                                    <li><a class="dropdown-item" href="https://adsupp.com/preguntas-frecuentes">Preguntas
                                            Frecuentes</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar sesion
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>


                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        {{-- @dd(re?quest()->route()->uri) --}}
        @if (preg_match('/p1\/\{id\}/', request()->route()->uri))
            <main class="container">
            @else
                <main class="container mt-4">
        @endif

        <div class="row">
            @yield('content')
        </div>

        </main>
    </div>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false"
        id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title text-center" id="offcanvasExampleLabel">Cuenta</h4>
            <button data-bs-dismiss="offcanvas" aria-label="Close" class="btn rounded rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path
                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                </svg>
            </button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav ms-auto">

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar sesion</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Crear Cuenta</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://adsupp.com/preguntas-frecuentes">Preguntas
                            Frecuentes FAQ'S</a>
                    </li>
                @else
                    @can('admin-list')
                        <li class="nav-item"><a class="nav-link" href="{{ route('sale.create') }}">Cargar Campaña</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('sale.index') }}">Por aprobar</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('grilla') }}">Programacion</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pagos.index') }}">Pagos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}">Clientes</li>
                    @endcan
                    @can('client-list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.profile', Auth::user()->id) }}">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sale.index') }}">Mi publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" target="_blank" href="https://adsupp.com/preguntas-frecuentes">Preguntas
                                Frecuentes FAQ'S</a>
                        </li>
                    @endcan

                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            Cerrar sesion
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>


                @endguest
            </ul>
        </div>
    </div>
    <!-- end sidebar -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('js')

    <!-- Nav tabs -->

</body>

</html>
