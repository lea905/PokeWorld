@extends('admin.layout')

@section('title', $dresseur->exists ? 'Modifier '.$dresseur->nom_complet : 'Créer un dresseur')

@php
    // Équipes à afficher : valeurs soumises (après une erreur de validation) ou équipes en base
    $teams = old('teams', $dresseur->teams->map(fn ($team) => [
        'id' => $team->id,
        'nom' => $team->nom,
        'pokemons' => $team->pokemons->pluck('id_pokemon')->all(),
    ])->all());
@endphp

@section('content')
    <form action="{{ $dresseur->exists ? route('admin.dresseurs.update', $dresseur) : route('admin.dresseurs.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if ($dresseur->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">Informations</h2>
                        <div class="row">
                            <div class="col-md-6"><x-admin.input name="prenom" label="Prénom" :value="$dresseur->prenom" required /></div>
                            <div class="col-md-6"><x-admin.input name="nom" label="Nom" :value="$dresseur->nom" /></div>
                            <div class="col-md-6"><x-admin.input name="ville_natale" label="Ville natale" :value="$dresseur->ville_natale" required /></div>
                            <div class="col-md-6"><x-admin.input name="region" label="Région" :value="$dresseur->region" required /></div>
                        </div>
                        <x-admin.textarea name="ambition" label="Ambition" :value="$dresseur->ambition" required rows="3" />
                        <x-admin.textarea name="description" label="Description" :value="$dresseur->description" required />
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">Profil</h2>
                        <x-admin.image name="image" label="Image" :current="$dresseur->image" :url="$dresseur->image ? asset('images/'.$dresseur->image) : null" />

                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="est_mechant" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="est_mechant" name="est_mechant" value="1"
                                   @checked(old('est_mechant', $dresseur->est_mechant))>
                            <label class="form-check-label fw-semibold" for="est_mechant">Antagoniste ?</label>
                        </div>

                        <div class="mb-3">
                            <label for="organisation_id" class="form-label fw-semibold">Organisation</label>
                            <select id="organisation_id" name="organisation_id" class="form-select">
                                <option value="">— Aucune —</option>
                                @foreach ($organisations as $organisation)
                                    <option value="{{ $organisation->id }}" @selected(old('organisation_id', $dresseur->organisation_id) == $organisation->id)>{{ $organisation->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="grade" class="form-label fw-semibold">Grade dans l'organisation</label>
                            <select id="grade" name="grade" class="form-select @error('grade') is-invalid @enderror">
                                <option value="">— Aucun —</option>
                                @foreach (\App\Enums\Grade::ordered() as $grade)
                                    <option value="{{ $grade->value }}" @selected(old('grade', $dresseur->grade?->value) === $grade->value)>{{ $grade->value }}</option>
                                @endforeach
                            </select>
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 fw-bold mb-0">Teams</h2>
                            <button type="button" class="btn btn-sm btn-outline-success" id="add-team"><i class="fas fa-plus me-1"></i> Ajouter une équipe</button>
                        </div>

                        <div id="teams">
                            @foreach ($teams as $index => $team)
                                @include('admin.dresseurs._team', ['index' => $index, 'team' => $team])
                            @endforeach
                        </div>
                        <p class="text-muted small mb-0" id="no-team" @if (count($teams)) hidden @endif>Aucune équipe pour ce dresseur.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.dresseurs.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>

    <template id="team-template">
        @include('admin.dresseurs._team', ['index' => '__INDEX__', 'team' => ['id' => null, 'nom' => '', 'pokemons' => []]])
    </template>
@endsection

@push('scripts')
    <script>
        (function () {
            const container = document.getElementById('teams');
            const template = document.getElementById('team-template').innerHTML;
            const noTeam = document.getElementById('no-team');
            let nextIndex = {{ count($teams) ? max(array_keys($teams)) + 1 : 0 }};

            const refresh = () => { noTeam.hidden = container.children.length > 0; };

            document.getElementById('add-team').addEventListener('click', function () {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = template.replaceAll('__INDEX__', nextIndex++);
                const row = wrapper.firstElementChild;
                container.appendChild(row);
                window.initTomSelect(row);
                refresh();
            });

            container.addEventListener('click', function (event) {
                const button = event.target.closest('.js-remove-team');
                if (button) {
                    button.closest('.team-row').remove();
                    refresh();
                }
            });
        })();
    </script>
@endpush
