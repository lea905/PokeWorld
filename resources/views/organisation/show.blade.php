@extends('layouts.base')

@section('title', $organisation->nom.' - Pokédex')

@section('body')
    <div class="hero-pokemon page-hero hero-organisation-dark" style="min-height: 300px; padding: 4rem 0;">
        <div class="container position-relative z-1 hero-content text-center">
            @if ($organisation->image)
                <img src="{{ asset('images/'.$organisation->image) }}" alt="{{ $organisation->nom }}" class="mb-4 shadow-lg rounded bg-white p-2" style="height: 120px; object-fit: contain;">
            @endif
            <h1 class="display-3 fw-bold text-white mb-3" style="text-shadow: 0 4px 15px rgba(0,0,0,0.5);">{{ $organisation->nom }}</h1>
            <div class="lead text-white opacity-90 mx-auto" style="max-width: 800px; font-size: 1.25rem;">
                @html($organisation->but)
            </div>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="fw-bold mb-4 border-bottom pb-2 text-danger">
            <i class="fas fa-users me-2"></i> Membres connus ({{ $organisation->dresseurs->count() }})
        </h2>

        <div class="row g-4 justify-content-center">
            @forelse ($organisation->dresseurs as $dresseur)
                <x-dresseur-card :dresseur="$dresseur" title-class="text-danger">
                    <x-slot:badges>
                        @if ($dresseur->grade)
                            <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-dark text-white shadow-sm px-3 py-2 fw-bold badge-blur border border-danger">
                                <i class="fas fa-star text-danger me-1"></i> {{ $dresseur->grade }}
                            </span>
                        @endif
                    </x-slot:badges>
                </x-dresseur-card>
            @empty
                <div class="col-12 text-center my-4">
                    <p class="text-muted">Aucun membre n'a encore été identifié dans la base de données.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('organisation.index') }}" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Retour aux organisations
            </a>
        </div>
    </div>
@endsection
