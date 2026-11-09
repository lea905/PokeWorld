@extends('layouts.base')

@section('title', 'Pokedex')

@php($baseParams = $currentGeneration ? ['generation' => $currentGeneration] : [])

@section('body')
<!-- Section 1 : Hero Header -->
<div class="container-fluid px-0">
    <header class="hero-header d-flex flex-column justify-content-center align-items-center text-center mx-3 mt-3" style="padding: 70px 0;">
        <div class="hero-overlay"></div>
        <div class="hero-content w-100">
            <h1 class="display-4 fw-bold mb-3">Le Pokédex National</h1>
            <p class="lead mb-0">Recherchez et filtrez l'intégralité des espèces de Pokémon recensées.</p>
        </div>
    </header>

    <div class="container mt-5">
        <!-- Barre de Filtres Avancés -->
        <div class="card shadow-sm border-0 mb-5 text-bg-light" style="border-radius: 12px;">
            <div class="card-body">
                <form action="{{ route('pokedex.index', $baseParams) }}" method="GET" class="row g-3 align-items-end">

                    <!-- Filtre Type -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold mb-1"><i class="bi bi-tag-fill"></i> Type</label>
                        <select name="type" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">Tous les types</option>
                            @foreach ($allTypes as $t)
                                <option value="{{ $t->nom }}" @selected($currentType === $t->nom)>{{ $t->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Stade d'Évolution -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold mb-1"><i class="bi bi-diagram-2-fill"></i> Stade</label>
                        <select name="evolution_stage" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">Famille complète</option>
                            <option value="base" @selected($evolutionStage === 'base')>Pokémons de Base</option>
                            <option value="evolution" @selected($evolutionStage === 'evolution')>Évolutions</option>
                        </select>
                    </div>

                    <!-- Filtres Spéciaux (Légendaire / Fabuleux) -->
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="form-check form-switch fs-5 mb-0">
                            <input class="form-check-input shadow-sm" type="checkbox" role="switch" id="switchLegendary" name="is_legendary" value="1" onchange="this.form.submit()" @checked($isLegendary)>
                            <label class="form-check-label fs-6 fw-bold" for="switchLegendary">Légendaire 🌟</label>
                        </div>
                        <div class="form-check form-switch fs-5 mb-0">
                            <input class="form-check-input shadow-sm" type="checkbox" role="switch" id="switchMythical" name="is_mythical" value="1" onchange="this.form.submit()" @checked($isMythical)>
                            <label class="form-check-label fs-6 fw-bold" for="switchMythical">Fabuleux ✨</label>
                        </div>

                        <!-- Bouton Reset -->
                        <div class="ms-auto">
                            @if ($currentType || $evolutionStage || $isLegendary || $isMythical)
                                <a href="{{ route('pokedex.index', $baseParams) }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($pokemons->total() > 0)
            <p class="text-muted small mb-3">
                {{ $pokemons->total() }} Pokémon — affichage de {{ $pokemons->firstItem() }} à {{ $pokemons->lastItem() }}
            </p>
        @endif

        <div class="row">
            @forelse ($pokemons as $pokemon)
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <div class="card-body text-center">
                            <img src="{{ $pokemon->image_principale }}" class="img-fluid mb-3" style="max-height: 150px; object-fit: contain;" alt="{{ $pokemon->nom }}" loading="lazy">
                            <h5 class="card-title fw-bold">{{ $pokemon->nom }}</h5>
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge rounded-pill text-bg-{{ $pokemon->typePrincipal->css }}">{{ $pokemon->typePrincipal->nom }}</span>
                                @if ($pokemon->typeSecondaire)
                                    <span class="badge rounded-pill text-bg-{{ $pokemon->typeSecondaire->css }}">{{ $pokemon->typeSecondaire->nom }}</span>
                                @endif
                            </div>
                            <a href="{{ route('pokedex.show', $pokemon) }}" class="btn btn-primary w-100 stretched-link">Détails</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Aucun Pokémon trouvé.</div>
                </div>
            @endforelse
        </div>

        <nav class="d-flex justify-content-center my-4" aria-label="Pages du Pokédex">
            {{ $pokemons->onEachSide(1)->links() }}
        </nav>
    </div>
</div>
@endsection
