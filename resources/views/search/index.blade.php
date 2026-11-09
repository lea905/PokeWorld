@extends('layouts.base')

@section('title', 'Résultats pour "'.$query.'" - PokéWorld')

@push('styles')
    <style>
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .nav-pills .nav-link.active {
            background-color: #EE1515;
        }
        .nav-pills .nav-link {
            color: #495057;
        }
    </style>
@endpush

@section('body')
    <div class="container py-5">

        <div class="row mb-5 align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-search text-danger"></i> Résultats de recherche
                </h1>
                @if ($query)
                    <p class="lead text-muted">Voici ce que nous avons trouvé pour "<strong>{{ $query }}</strong>"</p>
                @else
                    <p class="lead text-muted">Veuillez entrer un terme à rechercher.</p>
                @endif
            </div>
            <div class="col-md-4">
                <form action="{{ route('search') }}" method="GET" class="d-flex">
                    <input type="text" name="q" class="form-control me-2" value="{{ $query }}" placeholder="Nouvelle recherche...">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        @if ($query)
            @php($totalResults = $pokemons->count() + $dresseurs->count() + $arenes->count())

            @if ($totalResults === 0)
                <div class="alert alert-warning text-center p-5 rounded-4 shadow-sm border-0">
                    <i class="bi bi-emoji-frown display-4 d-block mb-3 text-warning"></i>
                    <h3 class="fw-bold">Aucun résultat trouvé</h3>
                    <p>Nous n'avons trouvé aucun Pokémon, Dresseur ou Arène correspondant à votre recherche.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Retour à l'accueil</a>
                </div>
            @else
                <ul class="nav nav-pills mb-4" id="search-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-selected="true">
                            Tout ({{ $totalResults }})
                        </button>
                    </li>
                    @if ($pokemons->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4" id="pokemon-tab" data-bs-toggle="pill" data-bs-target="#pokemon" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-record-circle-fill"></i> Pokémon ({{ $pokemons->count() }})
                            </button>
                        </li>
                    @endif
                    @if ($dresseurs->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4" id="dresseur-tab" data-bs-toggle="pill" data-bs-target="#dresseur" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-person-badge-fill"></i> Dresseurs ({{ $dresseurs->count() }})
                            </button>
                        </li>
                    @endif
                    @if ($arenes->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4" id="arene-tab" data-bs-toggle="pill" data-bs-target="#arene" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-shield-shaded"></i> Arènes ({{ $arenes->count() }})
                            </button>
                        </li>
                    @endif
                </ul>

                <div class="tab-content" id="search-tabs-content">

                    {{-- ONGLET TOUT --}}
                    <div class="tab-pane fade show active" id="all" role="tabpanel" tabindex="0">
                        <div class="row g-4">
                            @foreach ($pokemons as $p)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 hover-card">
                                        <div class="row g-0">
                                            <div class="col-4 p-2 text-center">
                                                <img src="{{ $p->image_principale }}" class="img-fluid rounded" style="max-height: 100px; object-fit: contain;" alt="{{ $p->nom }}">
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body">
                                                    <span class="badge bg-secondary mb-1">Pokémon N°{{ $p->numero_pokedex }}</span>
                                                    <h5 class="card-title fw-bold">{{ $p->nom }}</h5>
                                                    <a href="{{ route('pokedex.show', $p) }}" class="btn btn-sm btn-outline-danger mt-2">Détails</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($dresseurs as $d)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 hover-card">
                                        <div class="row g-0">
                                            <div class="col-4 p-2 text-center d-flex align-items-center justify-content-center">
                                                @if ($d->image)
                                                    <img src="{{ asset('images/'.$d->image) }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $d->nom_complet }}">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 80px;">
                                                        <i class="bi bi-person fs-1"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body">
                                                    <span class="badge bg-primary mb-1">Dresseur</span>
                                                    <h5 class="card-title fw-bold">{{ $d->prenom }} {{ $d->nom }}</h5>
                                                    <p class="card-text small text-muted text-truncate">{{ $d->ville_natale }}</p>
                                                    <a href="{{ route('dresseur.show', ['slug' => $d->slug]) }}" class="btn btn-sm btn-outline-primary mt-1">Détails</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($arenes as $a)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 hover-card">
                                        <div class="row g-0">
                                            <div class="col-4 p-2 text-center d-flex align-items-center justify-content-center">
                                                @if ($a->badge_est_image)
                                                    <img src="{{ asset('images/Badges/'.$a->badge) }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: contain;" alt="Badge">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 80px;">
                                                        <i class="bi bi-shield fs-1"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body">
                                                    <span class="badge bg-warning text-dark mb-1">Arène</span>
                                                    <h5 class="card-title fw-bold">{{ $a->nom }}</h5>
                                                    <p class="card-text small text-muted text-truncate">{{ $a->lieu }}</p>
                                                    <a href="{{ route('arene.show', ['slug' => $a->slug]) }}" class="btn btn-sm btn-outline-warning mt-1">Détails</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ONGLET POKEMON --}}
                    @if ($pokemons->isNotEmpty())
                        <div class="tab-pane fade" id="pokemon" role="tabpanel" tabindex="0">
                            <div class="row g-4">
                                @foreach ($pokemons as $p)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card h-100 shadow-sm border-0 text-center hover-card p-3">
                                            <img src="{{ $p->image_principale }}" class="img-fluid mx-auto mb-3" style="max-height: 120px; object-fit: contain;" alt="{{ $p->nom }}">
                                            <span class="text-muted small">N°{{ $p->numero_pokedex }}</span>
                                            <h5 class="fw-bold mb-3">{{ $p->nom }}</h5>
                                            <a href="{{ route('pokedex.show', $p) }}" class="btn btn-outline-danger w-100">Fiche Pokédex</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ONGLET DRESSEUR --}}
                    @if ($dresseurs->isNotEmpty())
                        <div class="tab-pane fade" id="dresseur" role="tabpanel" tabindex="0">
                            <div class="row g-4">
                                @foreach ($dresseurs as $d)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card h-100 shadow-sm border-0 text-center hover-card p-3">
                                            @if ($d->image)
                                                <img src="{{ asset('images/'.$d->image) }}" class="img-fluid rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $d->nom_complet }}">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted mx-auto mb-3" style="width: 120px; height: 120px;">
                                                    <i class="bi bi-person display-4"></i>
                                                </div>
                                            @endif
                                            <h5 class="fw-bold">{{ $d->prenom }} {{ $d->nom }}</h5>
                                            <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill"></i> {{ $d->ville_natale }}</p>
                                            <a href="{{ route('dresseur.show', ['slug' => $d->slug]) }}" class="btn btn-outline-primary w-100">Profil Dresseur</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ONGLET ARENE --}}
                    @if ($arenes->isNotEmpty())
                        <div class="tab-pane fade" id="arene" role="tabpanel" tabindex="0">
                            <div class="row g-4">
                                @foreach ($arenes as $a)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card h-100 shadow-sm border-0 text-center hover-card p-3">
                                            @if ($a->badge_est_image)
                                                <img src="{{ asset('images/Badges/'.$a->badge) }}" class="img-fluid mx-auto mb-3" style="width: 100px; height: 100px; object-fit: contain;" alt="Badge">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted mx-auto mb-3" style="width: 100px; height: 100px;">
                                                    <i class="bi bi-shield display-4"></i>
                                                </div>
                                            @endif
                                            <h5 class="fw-bold">{{ $a->nom }}</h5>
                                            <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill"></i> {{ $a->lieu }}</p>
                                            <a href="{{ route('arene.show', ['slug' => $a->slug]) }}" class="btn btn-outline-warning w-100">Défier l'Arène</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            @endif
        @endif
    </div>
@endsection
