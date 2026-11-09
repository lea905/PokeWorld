{{-- Carte d'une organisation dans les résultats de recherche. Utilisation : <x-organisation-search-card :organisation="$o" /> --}}
@props(['organisation'])

<div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm border-0 hover-card">
        <div class="row g-0">
            <div class="col-4 p-2 text-center d-flex align-items-center justify-content-center">
                @if ($organisation->image)
                    <img src="{{ asset('images/'.$organisation->image) }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: contain;" alt="{{ $organisation->nom }}">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 80px;">
                        <i class="bi bi-incognito fs-1"></i>
                    </div>
                @endif
            </div>
            <div class="col-8">
                <div class="card-body">
                    <span class="badge bg-dark mb-1">Organisation</span>
                    <h5 class="card-title fw-bold">{{ $organisation->nom }}</h5>
                    <p class="card-text small text-muted">{{ $organisation->dresseurs_count }} membre(s) connu(s)</p>
                    <a href="{{ route('organisation.show', ['slug' => $organisation->slug]) }}" class="btn btn-sm btn-outline-dark mt-1">Détails</a>
                </div>
            </div>
        </div>
    </div>
</div>
