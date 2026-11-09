<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-uppercase tracking-widest" href="{{ route('home') }}" style="letter-spacing: 0.15em; font-size: 1.1rem;">
            <img src="{{ asset('images/logo.png') }}" alt="PokeWorld" class="img-fluid" style="max-height: 80px; width: auto;">
        </a>

        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav gap-1">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('home') }}">Accueil</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pokédex
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom border-0 shadow-sm mt-2">
                        <li><a class="dropdown-item" href="{{ route('pokedex.index') }}">Tous les Pokémon</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach (range(1, 9) as $i)
                            <li><a class="dropdown-item" href="{{ route('pokedex.index', ['generation' => $i]) }}">Génération {{ $i }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('dresseur.index') }}">Dresseurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('arene.index') }}">Arènes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('organisation.index') }}">Organisations</a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0">
            <a href="{{ route('admin') }}" class="btn btn-sm btn-dark rounded-pill fw-bold px-3 shadow-sm" style="background-color: #1e293b; border-color: #1e293b;">
                <i class="fas fa-cog me-1"></i> Admin
            </a>
        </div>
    </div>
</nav>
