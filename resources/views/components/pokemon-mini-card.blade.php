{{--
    Petite carte de Pokémon (équipes d'un dresseur ou d'un champion).
    Utilisation : <x-pokemon-mini-card :pokemon="$pokemon" class="col-6 col-md-4" />
--}}
@props(['pokemon'])

<div {{ $attributes->merge(['class' => 'col-6 col-md-4']) }}>
    <a href="{{ route('pokedex.show', $pokemon) }}" class="text-decoration-none">
        <div class="card card-lift-sm border-1 border-light shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="card-body text-center p-3 position-relative">
                <img src="{{ $pokemon->image_principale }}" alt="{{ $pokemon->nom }}" class="img-fluid mb-2 position-relative"
                     style="max-height: 100px; filter: drop-shadow(0 6px 8px rgba(0,0,0,0.15)); z-index: 2;" loading="lazy">
                <h6 class="fw-bold text-dark mb-1">{{ $pokemon->nom }}</h6>
                <x-pokemon-types :pokemon="$pokemon" />
            </div>
        </div>
    </a>
</div>
