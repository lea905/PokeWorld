{{--
    Équipes d'un dresseur, regroupées en onglets par jeu (ordre de sortie des jeux).
    Sans onglets s'il n'y a qu'un seul groupe.
    Utilisation : <x-equipes-par-jeu :teams="$dresseur->teams" id="equipes-dresseur" />
--}}
@props(['teams', 'id' => 'equipes', 'col' => 'col-6 col-md-4'])

@php($groupes = \App\Models\Team::grouperParJeu($teams))
@php($avecOnglets = $groupes->count() > 1)

@if ($avecOnglets)
    <ul class="nav nav-pills flex-wrap gap-2 mb-4" id="{{ $id }}-onglets" role="tablist">
        @foreach ($groupes as $groupe)
            <li class="nav-item" role="presentation">
                <button @class(['nav-link rounded-pill px-3 py-1 small fw-semibold border', 'active' => $loop->first])
                        id="{{ $id }}-onglet-{{ $groupe['cle'] }}" data-bs-toggle="pill"
                        data-bs-target="#{{ $id }}-{{ $groupe['cle'] }}" type="button" role="tab"
                        aria-controls="{{ $id }}-{{ $groupe['cle'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $groupe['libelle'] }}
                    <span class="badge rounded-pill text-bg-light ms-1">{{ $groupe['teams']->count() }}</span>
                </button>
            </li>
        @endforeach
    </ul>
@endif

<div @class(['tab-content' => $avecOnglets])>
    @foreach ($groupes as $groupe)
        <div @class(['tab-pane fade' => $avecOnglets, 'show active' => $avecOnglets && $loop->first])
             @if ($avecOnglets) id="{{ $id }}-{{ $groupe['cle'] }}" role="tabpanel" aria-labelledby="{{ $id }}-onglet-{{ $groupe['cle'] }}" tabindex="0" @endif>
            @foreach ($groupe['teams'] as $team)
                @php($autresJeux = $team->jeux?->reject(fn ($jeu) => $jeu->value === $groupe['cle']))
                <div class="mb-5">
                    <h4 class="h5 fw-bold mb-1 text-primary d-inline-block border-2 border-bottom border-primary pb-1">
                        <i class="fas fa-bookmark me-2"></i> {{ $avecOnglets ? $team->titre : $team->nom }}
                    </h4>
                    @if ($avecOnglets && $autresJeux?->isNotEmpty())
                        <p class="small text-muted mb-0">Même équipe dans : {{ $autresJeux->map->label()->join(', ', ' et ') }}</p>
                    @endif
                    <div class="row g-3 mt-2">
                        @forelse ($team->pokemons as $pokemon)
                            <x-pokemon-mini-card :pokemon="$pokemon" :class="$col" />
                        @empty
                            <div class="col-12">
                                <p class="text-muted fst-italic">Équipe vide.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
