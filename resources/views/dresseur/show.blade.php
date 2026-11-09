@extends('layouts.base')

@section('title', $dresseur->nom_complet.' - Pokédex')

@php($balisesAutorisees = '<p><br><b><strong><i><em><u><ul><ol><li><a>')

@section('body')
    <!-- Hero Section -->
    <div class="hero-pokemon"
         style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); min-height: 280px; padding: 2rem; border-radius: 0 0 2rem 2rem; margin-top: -1.5rem;">
        <div class="container position-relative z-1">
            <a href="{{ route('dresseur.index') }}"
               class="btn btn-light rounded-pill px-4 mb-4 shadow-sm text-decoration-none fw-bold"
               style="opacity: 0.9;">
                &larr; Retour aux Dresseurs
            </a>
        </div>
    </div>

    <div class="container mb-5" style="margin-top: -140px; position: relative; z-index: 10;">
        <div class="row g-4">

            <div class="col-lg-4 align-items-start">
                <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0 text-center position-relative mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if ($dresseur->image)
                            <img src="{{ asset('images/'.$dresseur->image) }}"
                                 class="rounded-circle img-thumbnail shadow-lg" alt="{{ $dresseur->nom }}"
                                 style="width: 200px; height: 200px; object-fit: cover; border-width: 5px; border-color: white;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto shadow-lg"
                                 style="width: 200px; height: 200px; border: 5px solid white;">
                                <i class="fas fa-user text-muted opacity-50" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                    </div>

                    <h2 class="fw-bold mb-1 {{ $dresseur->est_mechant ? 'text-danger' : 'text-dark' }}">{{ $dresseur->nom }} {{ $dresseur->prenom }}</h2>
                    <p class="text-secondary fw-bold mb-4"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $dresseur->ville_natale }}, {{ $dresseur->region }}</p>

                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                        @if ($dresseur->arene)
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-building me-1"></i> Champion</span>
                        @endif
                        @if ($dresseur->est_mechant)
                            <span class="badge bg-danger text-white rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-skull me-1"></i> Antagoniste</span>
                        @endif
                        <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-paw me-1"></i> {{ $dresseur->teams->count() }} Équipes</span>
                    </div>

                    <div class="text-start mt-4">
                        <h5 class="fw-bold text-dark opacity-75 mb-3 border-bottom pb-2">À propos</h5>
                        <div class="text-secondary" style="line-height: 1.7;">{!! strip_tags($dresseur->description, $balisesAutorisees) !!}</div>
                    </div>

                    @if ($dresseur->ambition)
                        <div class="text-start mt-4">
                            <h5 class="fw-bold text-dark opacity-75 mb-3 border-bottom pb-2">Ambition</h5>
                            <blockquote class="blockquote fs-6 font-italic text-secondary border-start border-4 border-primary ps-3 bg-light p-3 rounded-end">
                                {!! strip_tags($dresseur->ambition, $balisesAutorisees) !!}
                            </blockquote>
                        </div>
                    @endif
                </div>

                @if ($arene = $dresseur->arene)
                    <div class="glass-panel p-4 bg-white shadow-sm border-0 mt-4">
                        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center"><i class="fas fa-building text-primary me-2 fs-4"></i> Son Arène</h5>
                        <div class="d-flex align-items-center bg-light p-3 rounded-4 mt-3" style="border: 1px solid rgba(0,0,0,0.05);">
                            @if ($arene->badge_est_image)
                                <img src="{{ asset('images/Badges/'.$arene->badge) }}" alt="Badge" class="me-3 drop-shadow" style="width: 65px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                            @elseif ($arene->image_badge)
                                <img src="{{ asset($arene->image_badge) }}" alt="Badge" class="me-3 drop-shadow" style="width: 65px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                            @endif
                            <div>
                                <h6 class="mb-1 text-dark fw-bold">{{ $arene->nom }}</h6>
                                <small class="text-muted d-block mb-2">{{ $arene->region }} - {{ $arene->lieu }}</small>
                                <a href="{{ route('arene.show', ['slug' => $arene->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">Détails <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-8">
                <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0 mt-4 mt-lg-0">
                    <h3 class="fw-bold mb-4 text-dark opacity-75 d-flex align-items-center border-bottom pb-3">
                        <i class="fas fa-th-large me-2 text-primary"></i> Ses Équipes Pokémon
                    </h3>

                    @forelse ($dresseur->teams as $team)
                        <div class="mb-5">
                            <h4 class="h5 fw-bold mb-4 text-primary d-inline-block border-2 border-bottom border-primary pb-1">
                                <i class="fas fa-bookmark me-2"></i> {{ $team->nom }}</h4>
                            <div class="row g-3">
                                @forelse ($team->pokemons as $pokemon)
                                    @include('partials.team-pokemon-card', ['pokemon' => $pokemon])
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted fst-italic">Équipe vide.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="alert bg-light border-0 shadow-sm rounded-4 py-5 text-center">
                            <i class="fas fa-ghost text-muted mb-3" style="font-size: 4rem; opacity: 0.2;"></i>
                            <h5 class="text-muted fw-bold">Aucune équipe</h5>
                            <p class="text-muted mb-0">Ce dresseur n'a pas encore composé d'équipe Pokémon.</p>
                        </div>
                    @endforelse

                    @if ($dresseur->organisation)
                        <div class="glass-panel p-4 bg-dark text-white shadow-sm border-0 mt-4 position-relative overflow-hidden" style="border-radius: 1rem;">
                            <i class="fas fa-user-secret position-absolute" style="font-size: 8rem; opacity: 0.05; top: -20px; right: -20px; transform: rotate(15deg);"></i>

                            <h5 class="fw-bold mb-3 d-flex align-items-center position-relative z-1">
                                <i class="fas fa-users text-danger me-2 fs-4"></i>
                                <a href="{{ route('organisation.show', ['slug' => $dresseur->organisation->slug]) }}" class="text-white text-decoration-none">{{ $dresseur->organisation->nom }}</a>
                            </h5>
                            <div class="p-3 rounded-4 position-relative z-1" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                <h6 class="text-danger small fw-bold text-uppercase mb-2">But de l'organisation</h6>
                                <p class="mb-0 text-light opacity-75 font-italic" style="line-height: 1.6;">"{{ strip_tags($dresseur->organisation->but) }}"</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
