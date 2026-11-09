@extends('layouts.base')

@section('title', 'Organisations - Pokédex')

@section('body')
    <x-page-hero theme="organisation" title="Les Organisations" subtitle="Découvrez les syndicats et équipes maléfiques du monde Pokémon !" />

    <div class="container my-5">
        <div class="row g-4 justify-content-center">
            @forelse ($organisations as $org)
                <div class="col-12 col-md-8 col-lg-6">
                    <a href="{{ route('organisation.show', ['slug' => $org->slug]) }}" class="text-decoration-none h-100 d-block">
                        <div class="card card-lift h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">

                            <div class="row g-0 h-100">
                                <div class="col-md-4 d-flex align-items-center justify-content-center p-3" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
                                    @if ($org->image)
                                        <img src="{{ asset('images/'.$org->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $org->nom }}" style="max-height: 120px; max-width: 200px; object-fit: contain;">
                                    @else
                                        <i class="fas fa-users text-secondary opacity-25" style="font-size: 5rem;"></i>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body p-4 h-100 d-flex flex-column justify-content-center">
                                        <h3 class="card-title fw-bold text-danger mb-2">{{ $org->nom }}</h3>
                                        <p class="card-text text-secondary mb-3">{{ strip_tags($org->but) }}</p>
                                        <div class="mt-auto">
                                            <span class="badge bg-dark px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-users me-1"></i> {{ $org->dresseurs_count }} membres connus
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <x-empty-state icon="fas fa-user-secret" title="Aucune organisation trouvée.">La paix règne sur le monde Pokémon (pour l'instant).</x-empty-state>
            @endforelse
        </div>
    </div>
@endsection
