@extends('layouts.base')

@section('title', 'Dresseurs - Pokédex')

@section('body')
    <x-page-hero theme="dresseur" title="Les Dresseurs" subtitle="Découvrez les dresseurs, champions et maîtres de région !" />

    <div class="container my-5">
        <div class="row g-4 justify-content-center">
            @forelse ($dresseurs as $dresseur)
                <x-dresseur-card :dresseur="$dresseur">
                    <x-slot:badges>
                        @if ($dresseur->arene)
                            <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2 fw-bold badge-blur">
                                <i class="fas fa-trophy me-1"></i> Champion
                            </span>
                        @endif
                        @if ($dresseur->est_mechant)
                            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-danger text-white shadow-sm px-3 py-2 fw-bold badge-blur">
                                <i class="fas fa-skull me-1"></i> Antagoniste
                            </span>
                        @endif
                    </x-slot:badges>
                </x-dresseur-card>
            @empty
                <x-empty-state icon="fas fa-user-slash" title="Aucun dresseur trouvé.">Revenez plus tard !</x-empty-state>
            @endforelse
        </div>
    </div>
@endsection
