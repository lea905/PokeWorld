@extends('layouts.base')

@section('title', 'Les Arènes - Pokédex')

@section('body')
    <x-page-hero theme="arene" icon="fas fa-landmark" title="La Ligue Pokémon" subtitle="Défiez les champions d'arène et collectez tous les badges !" />

    <div class="container my-5">
        <div class="row g-4 justify-content-center">
            @forelse ($arenes as $arene)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-lift h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative bg-white arene-card">

                        <!-- Background décoratif abstrait -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background: linear-gradient(to bottom right, #fef3c7, transparent); z-index: 0;"></div>

                        <div class="card-body position-relative z-1 text-center p-4 d-flex flex-column">

                            <!-- Badge -->
                            <div class="mb-4 mt-2 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                @include('partials.badge', [
                                    'arene' => $arene,
                                    'imgStyle' => 'width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.2));',
                                ])
                            </div>

                            <h3 class="card-title fw-bold text-dark mb-1">{{ $arene->nom }}</h3>
                            <p class="text-uppercase text-secondary small fw-bold tracking-wide mb-3" style="letter-spacing: 2px;">{{ $arene->region }}</p>

                            <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 shadow-sm"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $arene->lieu }}</span>
                                <span class="badge rounded-pill px-3 py-2 shadow-sm text-dark fw-bold" style="background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);"><i class="fas fa-award me-1"></i> {{ $arene->badge ? 'Badge '.$arene->badge_libelle : 'Arène' }}</span>
                            </div>

                            <!-- Section Champion -->
                            <div class="flex-grow-1 d-flex flex-column justify-content-center mb-4">
                                @if ($arene->champion)
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-pill p-2 shadow-sm mx-3 border" style="border-color: rgba(0,0,0,0.05) !important;">
                                        @if ($arene->champion->image)
                                            <img src="{{ asset('images/'.$arene->champion->image) }}" class="rounded-circle me-2 shadow-sm" alt="{{ $arene->champion->nom }}" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 45px; height: 45px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div class="text-start pe-3">
                                            <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Champion</small>
                                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $arene->champion->nom_complet }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted small fst-italic">
                                        Aucun champion assigné
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('arene.show', ['slug' => $arene->slug]) }}" class="btn rounded-pill fw-bold text-white shadow-sm mt-auto w-100" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); border: none;">Défier l'arène <i class="fas fa-arrow-right ms-2 fs-6"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state icon="fas fa-dungeon" title="Aucune arène n'a été construite pour le moment.">Revenez plus tard !</x-empty-state>
            @endforelse
        </div>
    </div>
@endsection
