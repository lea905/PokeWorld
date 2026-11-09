@extends('admin.layout')

@section('title', $team->exists ? 'Modifier '.$team->nom : 'Créer une équipe')

@php($selected = old('pokemons', $team->pokemons->pluck('id_pokemon')->all()))

@section('content')
    <form action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}" method="POST">
        @csrf
        @if ($team->exists)
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <x-admin.input name="nom" label="Nom" :value="$team->nom" required />

                <div class="mb-3">
                    <label for="dresseur_id" class="form-label fw-semibold">Dresseur</label>
                    <select id="dresseur_id" name="dresseur_id" class="form-select js-autocomplete">
                        <option value="">— Aucun —</option>
                        @foreach ($dresseurs as $dresseur)
                            <option value="{{ $dresseur->id }}" @selected(old('dresseur_id', $team->dresseur_id) == $dresseur->id)>{{ $dresseur->nom_complet }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="pokemons" class="form-label fw-semibold">Pokémon <small class="text-muted">(6 max recommandés)</small></label>
                    <select id="pokemons" name="pokemons[]" multiple class="form-select js-autocomplete" placeholder="Rechercher un Pokémon…">
                        @foreach ($pokemons as $pokemon)
                            <option value="{{ $pokemon->id_pokemon }}" @selected(in_array($pokemon->id_pokemon, $selected))>{{ $pokemon->nom }} (#{{ $pokemon->numero_pokedex }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.teams.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>
@endsection
