@extends('admin.layout')

@section('title', 'Modifier '.$pokemon->nom)

@section('content')
    <form action="{{ route('admin.pokemons.update', $pokemon) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8"><x-admin.input name="nom" label="Nom" :value="$pokemon->nom" required /></div>
                    <div class="col-md-4"><x-admin.input name="numero_pokedex" type="number" label="Numéro de Pokédex" :value="$pokemon->numero_pokedex" required min="1" /></div>

                    @foreach (['type1' => 'Type 1', 'type2' => 'Type 2'] as $field => $label)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $field }}" class="form-label fw-semibold">{{ $label }} @if ($field === 'type1')<span class="text-danger">*</span>@endif</label>
                            <select id="{{ $field }}" name="{{ $field }}" class="form-select @error($field) is-invalid @enderror" @required($field === 'type1')>
                                @if ($field === 'type2')
                                    <option value="">— Aucun —</option>
                                @endif
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" @selected(old($field, $pokemon->$field) == $type->id)>{{ $type->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $pokemon->image_principale }}" alt="" class="admin-thumb">
                    <div class="flex-grow-1">
                        <x-admin.input name="image_principale" label="Image (URL ou chemin)" :value="$pokemon->image_principale" required />
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.pokemons.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>
@endsection
