{{-- Ligne d'équipe dans le formulaire Dresseur. Paramètres : $index, $team (['id', 'nom', 'pokemons']), $pokemons. --}}
<div class="team-row border rounded-3 p-3 mb-3 bg-light">
    @if (! empty($team['id']))
        <input type="hidden" name="teams[{{ $index }}][id]" value="{{ $team['id'] }}">
    @endif
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Nom de l'équipe <span class="text-danger">*</span></label>
            <input type="text" name="teams[{{ $index }}][nom]" value="{{ $team['nom'] }}" class="form-control @error("teams.$index.nom") is-invalid @enderror" required>
        </div>
        <div class="col-md-7">
            <label class="form-label fw-semibold">Pokémon <small class="text-muted">(6 max recommandés)</small></label>
            <select name="teams[{{ $index }}][pokemons][]" multiple class="form-select js-autocomplete" placeholder="Rechercher un Pokémon…">
                @foreach ($pokemons as $pokemon)
                    <option value="{{ $pokemon->id_pokemon }}" @selected(in_array($pokemon->id_pokemon, $team['pokemons'] ?? []))>{{ $pokemon->nom }} (#{{ $pokemon->numero_pokedex }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-danger js-remove-team" title="Retirer l'équipe"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>
