@extends('admin.layout')

@section('title', $arene->exists ? 'Modifier '.$arene->nom : 'Créer une arène')

@section('content')
    <form action="{{ $arene->exists ? route('admin.arenes.update', $arene) : route('admin.arenes.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if ($arene->exists)
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <x-admin.input name="nom" label="Nom" :value="$arene->nom" required />
                <div class="row">
                    <div class="col-md-6"><x-admin.input name="region" label="Région" :value="$arene->region" required /></div>
                    <div class="col-md-6"><x-admin.input name="lieu" label="Lieu" :value="$arene->lieu" required /></div>
                </div>

                <div class="mb-3">
                    <label for="idChampion" class="form-label fw-semibold">Champion</label>
                    <select id="idChampion" name="idChampion" class="form-select js-autocomplete @error('idChampion') is-invalid @enderror">
                        <option value="">— Aucun —</option>
                        @foreach ($dresseurs as $dresseur)
                            <option value="{{ $dresseur->id }}" @selected(old('idChampion', $arene->idChampion) == $dresseur->id)>{{ $dresseur->nom_complet }}</option>
                        @endforeach
                    </select>
                </div>

                <x-admin.image name="badge" label="Badge"
                               :current="$arene->badge_est_image ? $arene->badge : null"
                               :url="$arene->badge_est_image ? asset('images/Badges/'.$arene->badge) : null" />
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.arenes.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>
@endsection
