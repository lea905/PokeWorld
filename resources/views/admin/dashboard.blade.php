@extends('admin.layout')

@section('title', 'PokeWorld Admin')

@php
    $cards = [
        ['route' => 'admin.dresseurs.index', 'icon' => 'fa-user text-primary', 'title' => 'Dresseurs', 'count' => $counts['dresseurs'], 'text' => 'Gérez les dresseurs, leurs infos et ajoutez des Pokémon à leurs équipes.', 'cta' => 'Gérer les Dresseurs'],
        ['route' => 'admin.teams.index', 'icon' => 'fa-users text-success', 'title' => 'Équipes', 'count' => $counts['teams'], 'text' => "Vue d'ensemble de toutes les équipes Pokémon.", 'cta' => 'Gérer les Équipes'],
        ['route' => 'admin.pokemons.index', 'icon' => 'fa-dragon text-danger', 'title' => 'Pokémon', 'count' => $counts['pokemons'], 'text' => 'Liste de tous les Pokémon importés.', 'cta' => 'Voir les Pokémon'],
        ['route' => 'admin.arenes.index', 'icon' => 'fa-building text-warning', 'title' => 'Arènes', 'count' => $counts['arenes'], 'text' => 'Gestion des arènes, champions et badges.', 'cta' => 'Gérer les Arènes'],
        ['route' => 'admin.organisations.index', 'icon' => 'fa-user-secret text-secondary', 'title' => 'Organisations', 'count' => $counts['organisations'], 'text' => 'Gestion des organisations et de leurs membres.', 'cta' => 'Gérer les Organisations'],
    ];
@endphp

@section('content')
    <div class="alert alert-info shadow-sm border-0">
        <i class="fas fa-info-circle me-2"></i> Bienvenue dans l'interface d'administration de <strong>PokeWorld</strong> !
    </div>

    <div class="row g-4 mt-1">
        @foreach ($cards as $card)
            <div class="col-md-6 col-xl-4">
                <div class="card admin-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title fw-bold"><i class="fas {{ $card['icon'] }} me-2"></i> {{ $card['title'] }}</h5>
                            <span class="badge rounded-pill text-bg-light fs-6">{{ $card['count'] }}</span>
                        </div>
                        <p class="card-text text-muted">{{ $card['text'] }}</p>
                        <a href="{{ route($card['route']) }}" class="btn btn-outline-dark btn-sm">{{ $card['cta'] }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
