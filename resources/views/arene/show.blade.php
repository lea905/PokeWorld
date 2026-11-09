@extends('layouts.base')

@section('title', $arene->nom.' - Pokédex')

@section('body')
    <x-back-hero theme="arene" :href="route('arene.index')">Retour aux Arènes</x-back-hero>

    <div class="container mb-5" style="margin-top: -140px; position: relative; z-index: 10;">

        <!-- Carte Principale Arène -->
        <div class="glass-panel p-0 bg-white shadow-lg border-0 mb-5 overflow-hidden">
            <div class="row g-0 align-items-center">

                <!-- Colonne Gauche (Badge) -->
                <div class="col-md-5 col-lg-4 text-center p-5 position-relative" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                    <div class="position-relative z-1">
                        @include('partials.badge', [
                            'arene' => $arene,
                            'size' => 'lg',
                            'imgClass' => 'drop-shadow mb-3',
                            'imgStyle' => 'max-height: 180px; filter: drop-shadow(0 15px 15px rgba(0,0,0,0.15));',
                        ])
                        <h2 class="fw-bold mt-3 text-dark mb-0">@if ($arene->badge) Badge {{ $arene->badge_libelle }} @endif</h2>
                    </div>
                </div>

                <!-- Colonne Droite (Infos & Champion) -->
                <div class="col-md-7 col-lg-8 p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold tracking-wide mb-1" style="letter-spacing: 2px;"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $arene->region }} - {{ $arene->lieu }}</p>
                            <h1 class="display-5 fw-bold text-dark mb-0">{{ $arene->nom }}</h1>
                        </div>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

                    <h4 class="fw-bold mb-4 text-dark opacity-75 d-flex align-items-center">
                        <i class="fas fa-crown text-warning me-2 fs-3"></i> Le Champion d'Arène
                    </h4>

                    @if ($champion = $arene->champion)
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center bg-light p-4 rounded-4 shadow-sm border" style="border-color: rgba(0,0,0,0.05) !important;">
                            <div class="position-relative mb-3 mb-sm-0 me-sm-4 text-center">
                                @if ($champion->image)
                                    <img src="{{ asset('images/'.$champion->image) }}" class="rounded-circle shadow-md" alt="{{ $champion->nom }}" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white shadow-md mx-auto" style="width: 100px; height: 100px; border: 4px solid white;">
                                        <i class="fas fa-user-ninja fa-2x"></i>
                                    </div>
                                @endif
                                <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark border border-2 border-white"><i class="fas fa-star"></i></span>
                            </div>

                            <div class="flex-grow-1 text-center text-sm-start">
                                <h3 class="mb-2 text-dark fw-bold">{{ $champion->nom }} {{ $champion->prenom }}</h3>
                                <div class="text-secondary small mb-3" style="line-height: 1.6; max-height: 4.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    @html($champion->description)
                                </div>
                                <a href="{{ route('dresseur.show', ['slug' => $champion->slug]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">Défier le champion <i class="fas fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light bg-light border-0 shadow-sm rounded-4 p-4 d-flex align-items-center text-muted">
                            <i class="fas fa-info-circle fa-2x me-3 opacity-50"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Arène vacante</h6>
                                <p class="mb-0 small">Cette arène n'a pas encore de champion officialisé par la Ligue.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Équipes du Champion -->
        @if ($arene->champion && $arene->champion->teams->isNotEmpty())
            <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0 mb-5">
                <h3 class="fw-bold mb-4 text-dark opacity-75 d-flex align-items-center border-bottom pb-3">
                    <i class="fas fa-th-large me-2 text-primary"></i> Les Équipes du Champion
                </h3>

                @foreach ($arene->champion->teams as $team)
                    <div class="mb-5">
                        <h4 class="h5 fw-bold mb-4 text-primary d-inline-block border-2 border-bottom border-primary pb-1"><i class="fas fa-bookmark me-2"></i> {{ $team->nom }}</h4>
                        <div class="row g-3">
                            @forelse ($team->pokemons as $pokemon)
                                <x-pokemon-mini-card :pokemon="$pokemon" class="col-6 col-md-4 col-lg-3" />
                            @empty
                                <div class="col-12">
                                    <p class="text-muted fst-italic">Cette équipe est vide pour le moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
