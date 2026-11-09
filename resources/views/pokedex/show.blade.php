@extends('layouts.base')

@section('title', $pokemon->nom.' - Pokédex')

@php
    $root = $pokemon->evolutionRoot();
    $stats = [
        'PV' => $pokemon->pv,
        'Attaque' => $pokemon->attaque,
        'Défense' => $pokemon->defense,
        'Atq Spé' => $pokemon->attaque_spe,
        'Déf Spé' => $pokemon->defence_spe,
        'Vitesse' => $pokemon->vitesse,
    ];
@endphp

@section('body')
    <!-- Hero Section with Type Background -->
    <div class="hero-pokemon bg-type-{{ $pokemon->typePrincipal->css }}">
        <div class="container position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-5 text-center text-lg-start mb-5 mb-lg-0">
                    <a href="{{ route('pokedex.index') }}" class="btn btn-light rounded-pill px-4 mb-4 shadow-sm text-decoration-none fw-bold" style="opacity: 0.9;">
                        &larr; Retour au Pokédex
                    </a>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-2">
                        <span class="fs-4 text-dark opacity-50 fw-bold me-3">#{{ $pokemon->numero_pokedex }}</span>
                    </div>
                    <h1 class="display-3 fw-bold text-dark text-uppercase mb-1" style="letter-spacing: -1px;">{{ $pokemon->nom }}</h1>
                    <h4 class="text-dark opacity-50 mb-4 ms-1">{{ $pokemon->nom_japonais }}</h4>

                    <div class="d-flex justify-content-center justify-content-lg-start gap-2">
                        <x-type-badge :type="$pokemon->typePrincipal" class="fs-6 px-4 py-2 glass-badge" />
                        <x-type-badge :type="$pokemon->typeSecondaire" class="fs-6 px-4 py-2 glass-badge" />
                    </div>
                </div>
                <div class="col-lg-7 text-center">
                    <img src="{{ $pokemon->image_principale }}" class="hero-pokemon-img img-fluid animate__animated animate__fadeIn" alt="{{ $pokemon->nom }}">
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row g-4">

            <!-- Description -->
            <div class="col-12">
                <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0">
                    <h3 class="h4 fw-bold mb-4 opacity-75">Description</h3>
                    <p class="lead fs-5 text-secondary" style="line-height: 1.8;">{{ $pokemon->description }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="col-12">
                <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0">
                    <h3 class="h4 fw-bold mb-4 opacity-75">Statistiques de base</h3>
                    <div class="row g-4">
                        @foreach ($stats as $label => $value)
                            @php
                                // Pourcentage relatif à la stat maximale possible (255)
                                $percentage = round($value / 255 * 100);
                                $barClass = match (true) {
                                    $value >= 130 => 'stat-bar-ultra',
                                    $value >= 90 => 'stat-bar-high',
                                    $value >= 65 => 'stat-bar-mid',
                                    default => 'stat-bar-low',
                                };
                            @endphp

                            <div class="col-md-6">
                                <div class="stat-row">
                                    <div class="stat-label">{{ $label }}</div>
                                    <div class="stat-value">{{ $value }}</div>
                                    <div class="stat-bar-container">
                                        <div class="stat-bar {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Evolutions -->
            @if ($root->evolutionsSuivantes->isNotEmpty())
                <div class="col-12 mt-4">
                    <div class="glass-panel p-4 p-md-5 bg-white shadow-sm border-0">
                        <h3 class="h4 fw-bold mb-4 opacity-75">Famille d'évolution</h3>
                        <div class="row justify-content-center text-center align-items-center">
                            @include('pokedex._evolution', ['p' => $root, 'current' => $pokemon])
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
