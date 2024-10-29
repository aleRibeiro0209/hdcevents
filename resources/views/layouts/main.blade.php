<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        {{-- Favicon --}}
        <link rel="shortcut icon" href="/image/hdcevents_logo.svg" type="image/x-icon">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        {{-- Bootstrap --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        {{-- CSS --}}
        <link rel="stylesheet" href="/css/styles.css">

    </head>
    <body class="main-layout">
        <header>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid px-3">
                    <a href="/" class="navbar-brand" id="logo-nav">
                        <img src="/image/hdcevents_logo.svg" alt="HDC Events">
                    </a>
                    <button class="navbar-toggler border border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-end" id="navbar" tabindex="-1">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                                <li class="nav-item">
                                    <a href="/" class="nav-link active" aria-current="page">Eventos</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/events/create" class="nav-link">Criar Eventos</a>
                                </li>
                                @auth
                                    <li class="nav-item">
                                        <a href="/dashboard" class="nav-link">Meus Eventos</a>
                                    </li>
                                @endauth
                                @guest
                                    <li class="nav-item">
                                        <a href="/login" class="nav-link">Entrar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/register" class="nav-link">Cadastrar</a>
                                    </li>
                                @endguest
                                <li class="nav-item dropdown" id="toggle-idiom">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <ion-icon name="language" class="fs-4"></ion-icon>
                                            <span class="d-block d-lg-none">&nbsp Alterar idioma</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center active" type="button">
                                                    <span>&nbsp PT-br</span>
                                                    <ion-icon name="checkmark" class="ms-auto fs-5"></ion-icon>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center" type="button">
                                                    <span>&nbsp En-us</span>
                                                    <ion-icon name="checkmark" class="d-none ms-auto fs-5"></ion-icon>
                                                </button>
                                            </li>
                                        </ul>
                                </li>
                                <li class="nav-item dropdown" id="toggle-theme">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <ion-icon id="icon-theme-mode" name="sunny" class="fs-4"></ion-icon>
                                        <span class="d-block d-lg-none">&nbsp Alterar tema</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item d-flex align-items-center" type="button" data-theme="light">
                                                <ion-icon name="sunny" class="fs-4"></ion-icon>
                                                <span>&nbsp Light</span>
                                                <ion-icon name="checkmark" class="d-none ms-auto fs-5"></ion-icon>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item d-flex align-items-center" type="button" data-theme="dark">
                                                <ion-icon name="moon" class="fs-4"></ion-icon>
                                                <span>&nbsp Dark</span>
                                                <ion-icon name="checkmark" class="d-none ms-auto fs-5"></ion-icon>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item d-flex align-items-center active" type="button" data-theme="auto">
                                                <ion-icon name="contrast" class="fs-4"></ion-icon>
                                                <span>&nbsp Auto</span>
                                                <ion-icon name="checkmark" class="ms-auto fs-5"></ion-icon>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                                @auth
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <ion-icon name="cog" class="gear-icon fs-4"></ion-icon>
                                            <span class="d-block d-lg-none">&nbsp Configurações</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" type="button">Perfil</button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="/logout" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item d-flex align-items-center" id="btn-logout">
                                                        <span>Sair</span>
                                                        <ion-icon name="exit" class="ms-auto fs-4 text-danger"></ion-icon>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div class="container-fluid">
                <div class="row">
                    @if (session('msg'))
                        <p class="msg">{{ session('msg') }}</p>
                    @elseif (session('msg-erro'))
                        <p class="msg-erro">{{ session('msg-erro') }}</p>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>
        <footer>
            <p>HDC Events &copy; 2024</p>
        </footer>
        {{-- JS --}}
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        {{-- Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        {{-- jQuery --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        {{-- JS General --}}
        <script src="{{ asset('js/scripts.js') }}"></script>

        {{-- JS Section --}}
        @yield('scripts')
    </body>
</html>
