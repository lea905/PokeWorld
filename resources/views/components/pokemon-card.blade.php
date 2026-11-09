{{-- Carte d'un Pokémon dans la grille du Pokédex. Utilisation : <x-pokemon-card :pokemon="$pokemon" /> --}}
@props(['pokemon'])

<div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm hover-shadow transition">
        <div class="card-body text-center">
            <img src="{{ $pokemon->image_principale }}" class="img-fluid mb-3" style="max-height: 150px; object-fit: contain;" alt="{{ $pokemon->nom }}" loading="lazy">
            <h5 class="card-title fw-bold">{{ $pokemon->nom }}</h5>
            <div class="d-flex justify-content-center gap-2 mb-3">
                <x-type-badge :type="$pokemon->typePrincipal" />
                <x-type-badge :type="$pokemon->typeSecondaire" />
            </div>
            <a href="{{ route('pokedex.show', $pokemon) }}" class="btn btn-primary w-100 stretched-link">Détails</a>
        </div>
    </div>
</div>
