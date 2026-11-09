<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Administration') - PokeWorld Admin</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-body">

@php
    $menu = [
        ['route' => 'admin.dresseurs.index', 'pattern' => 'admin.dresseurs.*', 'icon' => 'fa-user', 'label' => 'Dresseurs'],
        ['route' => 'admin.teams.index', 'pattern' => 'admin.teams.*', 'icon' => 'fa-users', 'label' => 'Teams'],
        ['route' => 'admin.arenes.index', 'pattern' => 'admin.arenes.*', 'icon' => 'fa-building', 'label' => 'Arènes'],
        ['route' => 'admin.organisations.index', 'pattern' => 'admin.organisations.*', 'icon' => 'fa-user-secret', 'label' => 'Organisations'],
        ['route' => 'admin.pokemons.index', 'pattern' => 'admin.pokemons.*', 'icon' => 'fa-dragon', 'label' => 'Pokémon'],
    ];
@endphp

<div class="d-flex min-vh-100">
    <aside class="admin-sidebar d-flex flex-column p-3">
        <a href="{{ route('home') }}" class="admin-logo text-decoration-none mb-4" title="Retour au site">PokeWorld</a>

        <nav class="nav flex-column gap-1">
            <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                <i class="fa fa-home fa-fw me-2"></i> Dashboard
            </a>

            <div class="admin-section mt-3 mb-1">Gestion</div>
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                    <i class="fas {{ $item['icon'] }} fa-fw me-2"></i> {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto">
            <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-arrow-left fa-fw me-2"></i> Voir le site</a>
            <div class="admin-user px-3 pt-3 mt-2">
                <div class="small text-truncate" title="{{ auth()->user()->email }}"><i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}</div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100"><i class="fas fa-sign-out-alt me-1"></i> Se déconnecter</button>
                </form>
            </div>
        </div>
    </aside>

    <main class="admin-main flex-grow-1 p-4 p-lg-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <h1 class="h3 fw-bold mb-0">@yield('title', 'Administration')</h1>
            @yield('actions')
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <i class="fas fa-exclamation-triangle me-2"></i> Le formulaire contient des erreurs :
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    // Listes déroulantes avec recherche (équivalent de l'autocomplete EasyAdmin)
    window.initTomSelect = function (root) {
        root.querySelectorAll('select.js-autocomplete:not(.tomselected)').forEach(function (el) {
            new TomSelect(el, { plugins: el.multiple ? ['remove_button'] : [], maxOptions: 2000 });
        });
    };
    document.addEventListener('DOMContentLoaded', function () { window.initTomSelect(document); });
    // Entrée dans une recherche Tom Select sélectionne l'option au lieu de soumettre le formulaire
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && event.target.closest('.ts-wrapper')) {
            event.preventDefault();
        }
    });
</script>
@stack('scripts')
</body>
</html>
