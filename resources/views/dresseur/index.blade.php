@extends('layouts.base')

@section('title', 'Dresseurs - Pokédex')

@section('body')
    <x-page-hero theme="dresseur" title="Les Dresseurs" subtitle="Découvrez les dresseurs, champions et maîtres de région !" />

    <div class="container my-5">
        <nav class="d-flex justify-content-center gap-2 mb-4" aria-label="Filtrer par camp">
            @foreach ([null => ['Tous', 'fas fa-users'], 'heros' => ['Héros', 'fas fa-star'], 'antagonistes' => ['Antagonistes', 'fas fa-skull']] as $valeur => [$libelle, $icone])
                <a href="{{ route('dresseur.index', array_filter(['camp' => $valeur])) }}"
                   @class(['btn rounded-pill px-4 fw-bold', 'btn-dark' => $camp === ($valeur ?: null), 'btn-outline-dark' => $camp !== ($valeur ?: null)])
                   @if ($camp === ($valeur ?: null)) aria-current="page" @endif>
                    <i class="{{ $icone }} me-1"></i> {{ $libelle }}
                </a>
            @endforeach
        </nav>

        <div class="row g-4 justify-content-center">
            @forelse ($dresseurs as $dresseur)
                <x-dresseur-card :dresseur="$dresseur">
                    <x-slot:badges>
                        <div class="position-absolute top-0 start-0 end-0 m-3 d-flex flex-wrap gap-2">
                            @if ($dresseur->est_mechant)
                                <span class="badge rounded-pill bg-danger text-white shadow-sm px-3 py-2 fw-bold badge-blur">
                                    <i class="fas fa-skull me-1"></i> Antagoniste
                                </span>
                            @endif
                            @if ($dresseur->arene)
                                <span class="badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2 fw-bold badge-blur ms-auto">
                                    <i class="fas fa-trophy me-1"></i> Champion
                                </span>
                            @endif
                        </div>
                    </x-slot:badges>
                </x-dresseur-card>
            @empty
                <x-empty-state icon="fas fa-user-slash" title="Aucun dresseur trouvé.">Revenez plus tard !</x-empty-state>
            @endforelse
        </div>
    </div>
@endsection
