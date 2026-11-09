{{-- Rendu récursif d'une famille d'évolution (remplace la macro Twig render_evolution). --}}
@php($isCurrent = $p->id_pokemon === $current->id_pokemon)

<div class="col-12 col-sm-6 col-md-3 mb-3">
    <div class="evolution-card card border-{{ $isCurrent ? 'primary' : 'light' }} {{ $isCurrent ? 'border-2 shadow' : 'border-1 shadow-sm' }} h-100">
        <div class="card-body">
            <h6 class="{{ $isCurrent ? 'text-primary fw-bold' : 'text-muted' }} text-uppercase small mb-3">
                {{ $isCurrent ? 'Actuel' : ($p->idEvolutionPrecedente ? 'Évolution' : 'Base') }}
            </h6>
            <a href="{{ route('pokedex.show', $p) }}" class="text-decoration-none text-dark d-block">
                <img src="{{ $p->image_principale }}"
                     alt="{{ $p->nom }}"
                     class="img-fluid mb-3"
                     style="max-height: {{ $isCurrent ? '130px' : '110px' }}; object-fit: contain;">
                <div class="fw-bold fs-5">{{ $p->nom }}</div>
                @if ($p->typePrincipal)
                    <div class="mt-2 text-center d-flex justify-content-center">
                        <span style="width:12px; height:12px; display:inline-block; border-radius:50%;" class="text-bg-{{ $p->typePrincipal->css }}"></span>
                        @if ($p->typeSecondaire)
                            <span style="width:12px; height:12px; display:inline-block; border-radius:50%; margin-left:4px;" class="text-bg-{{ $p->typeSecondaire->css }}"></span>
                        @endif
                    </div>
                @endif
            </a>
        </div>
    </div>
</div>

@if ($p->evolutionsSuivantes->isNotEmpty())
    <div class="col-1 d-none d-md-flex justify-content-center">
        <i class="bi bi-arrow-right fs-4 text-muted opacity-50"></i>
    </div>

    @if ($p->evolutionsSuivantes->count() > 1)
        <div class="col-md-auto">
            <div class="row flex-nowrap overflow-auto pb-2">
                @foreach ($p->evolutionsSuivantes as $next)
                    @include('pokedex._evolution', ['p' => $next, 'current' => $current])
                @endforeach
            </div>
        </div>
    @else
        @foreach ($p->evolutionsSuivantes as $next)
            @include('pokedex._evolution', ['p' => $next, 'current' => $current])
        @endforeach
    @endif
@endif
