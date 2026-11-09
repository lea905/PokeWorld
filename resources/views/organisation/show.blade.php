@extends('layouts.base')

@section('title', $organisation->nom.' - Pokédex')

@section('body')
    <!-- Hero Header -->
    <div class="hero-pokemon" style="background: linear-gradient(135deg, #111827 0%, #991b1b 100%); min-height: 300px; padding: 4rem 0; border-radius: 0 0 2rem 2rem; margin-top: -1.5rem;">
        <div class="container position-relative z-1 hero-content text-center">
            @if ($organisation->image)
                <img src="{{ asset('images/'.$organisation->image) }}" alt="{{ $organisation->nom }}" class="mb-4 shadow-lg rounded bg-white p-2" style="height: 120px; object-fit: contain;">
            @endif
            <h1 class="display-3 fw-bold text-white mb-3" style="text-shadow: 0 4px 15px rgba(0,0,0,0.5);">{{ $organisation->nom }}</h1>
            <div class="lead text-white opacity-90 mx-auto" style="max-width: 800px; font-size: 1.25rem;">
                {!! $organisation->but !!}
            </div>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="fw-bold mb-4 border-bottom pb-2 text-danger">
            <i class="fas fa-users me-2"></i> Membres connus ({{ $organisation->dresseurs->count() }})
        </h2>

        <div class="row g-4 justify-content-center">
            @forelse ($organisation->dresseurs as $dresseur)
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="{{ route('dresseur.show', ['slug' => $dresseur->slug]) }}" class="text-decoration-none h-100 d-block">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white"
                             style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
                            <div class="position-relative">
                                <img src="{{ asset('images/'.($dresseur->image ?: 'placeholder.png')) }}" class="card-img-top object-fit-cover" alt="{{ $dresseur->image ? $dresseur->nom : 'Image inconnue' }}" style="height: 220px; border-bottom: 1px solid rgba(0,0,0,0.05);">

                                @if ($dresseur->grade)
                                    <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-dark text-white shadow-sm px-3 py-2 fw-bold" style="backdrop-filter: blur(5px); background: rgba(33, 37, 41, 0.9) !important; border: 1px solid #dc3545;">
                                        <i class="fas fa-star text-danger me-1"></i> {{ $dresseur->grade }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body text-center p-4">
                                <h5 class="card-title fw-bold text-danger mb-1">{{ $dresseur->nom }} {{ $dresseur->prenom }}</h5>
                                <p class="text-danger small mb-3 fw-bold">
                                    <i class="fas fa-map-marker-alt"></i> {{ $dresseur->ville_natale }}
                                </p>
                                <p class="card-text text-secondary small text-truncate">{{ strip_tags($dresseur->description) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
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
