{{--
    Carte d'un dresseur (liste des dresseurs, membres d'une organisation).
    Utilisation :
        <x-dresseur-card :dresseur="$dresseur" title-class="text-danger">
            <x-slot:badges>…badges en surimpression sur l'image…</x-slot:badges>
        </x-dresseur-card>
--}}
@props(['dresseur', 'titleClass' => null])

<div class="col-12 col-sm-6 col-lg-3">
    <a href="{{ route('dresseur.show', ['slug' => $dresseur->slug]) }}" class="text-decoration-none h-100 d-block">
        <div class="card card-lift h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="position-relative">
                <img src="{{ asset('images/'.($dresseur->image ?: 'placeholder.png')) }}" class="card-img-top object-fit-cover dresseur-card-img"
                     alt="{{ $dresseur->image ? $dresseur->nom_complet : 'Image inconnue' }}" loading="lazy">
                {{ $badges ?? '' }}
            </div>

            <div class="card-body text-center p-4">
                <h5 class="card-title fw-bold mb-1 {{ $titleClass ?? ($dresseur->est_mechant ? 'text-danger' : 'text-dark') }}">{{ $dresseur->nom }} {{ $dresseur->prenom }}</h5>
                <p class="text-danger small mb-3 fw-bold">
                    <i class="fas fa-map-marker-alt"></i> {{ $dresseur->ville_natale }}
                </p>
                <p class="card-text text-secondary small text-truncate">{{ strip_tags($dresseur->description) }}</p>
            </div>
        </div>
    </a>
</div>
