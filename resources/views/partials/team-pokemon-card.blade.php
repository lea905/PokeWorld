{{-- Carte d'un Pokémon dans une équipe. Paramètres : $pokemon, $col (classes de colonne). --}}
<div class="{{ $col ?? 'col-6 col-md-4' }}">
    <a href="{{ route('pokedex.show', ['nom' => $pokemon->nom]) }}" class="text-decoration-none">
        <div class="card border-1 border-light shadow-sm rounded-4 h-100 overflow-hidden bg-white"
             style="transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
            <div class="card-body text-center p-3 position-relative">
                <img src="{{ $pokemon->image_principale }}" alt="{{ $pokemon->nom }}" class="img-fluid mb-2" style="max-height: 100px; filter: drop-shadow(0 6px 8px rgba(0,0,0,0.15)); z-index: 2; position: relative;">
                <h6 class="fw-bold text-dark mb-1">{{ $pokemon->nom }}</h6>

                @if ($pokemon->typePrincipal)
                    <div class="mt-2 text-center d-flex justify-content-center">
                        <span style="width:12px; height:12px; display:inline-block; border-radius:50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" class="text-bg-{{ $pokemon->typePrincipal->css }}"></span>
                        @if ($pokemon->typeSecondaire)
                            <span style="width:12px; height:12px; display:inline-block; border-radius:50%; margin-left:4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" class="text-bg-{{ $pokemon->typeSecondaire->css }}"></span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>
