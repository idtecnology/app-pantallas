<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


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
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'POS') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest
                    @else
                        <ul class="navbar-nav me-auto">
                            @can('admin-list')
                                <li>
                                    <a class="dropdown-item" href="{{ route('sale.create') }}">Cargar multimedia</a>
                                </li>
                            @endcan
                            @can('client-list')
                                <li>
                                    <a class="dropdown-item" href="{{ route('sale.index') }}">Mi publicaciones</a>
                                </li>
                            @endcan
                            {{-- @can('client-list')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Multimedia
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{{ route('sale.index') }}">Mi Multimedia</a></li>
                                        <li><a class="dropdown-item" href="{{ route('sale.create') }}">Cargar multimedia</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('sale.create') }}">Multimedia programada</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Pagos
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="#">Mis Pagos</a></li>
                                        <li><a class="dropdown-item" href="">Pagos pendientes</a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan --}}
                        </ul>
                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
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
                            <li class="nav-item">
                                <a class="nav-link" href="#"><span class="material-symbols-outlined">
                                        inbox
                                    </span></a>
                            </li>
                            @can('admin-list')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown2" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Mantenimientos
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{{ route('sale.index') }}">Videos</a></li>
                                        <li><a class="dropdown-item" href="{{ route('grilla') }}">Grilla</a></li>
                                        <li><a class="dropdown-item" href="{{ route('screen.index') }}">Pantallas</a></li>
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
                                    <li><a class="dropdown-item" href="#">Preguntas Frecuentes</a></li>
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

        <main class="py-4 container">
            <div class="row">
                <div class="col-12 col-sm-12">
                    @yield('content')
                </div>
            </div>

        </main>
    </div>
    @yield('js')
</body>

</html>
