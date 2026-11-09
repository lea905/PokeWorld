@extends('admin.layout')

@section('title', $organisation->exists ? 'Modifier '.$organisation->nom : 'Créer une organisation')

@section('content')
    <form action="{{ $organisation->exists ? route('admin.organisations.update', $organisation) : route('admin.organisations.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if ($organisation->exists)
            @method('PUT')
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <x-admin.input name="nom" label="Nom" :value="$organisation->nom" required />
                <x-admin.textarea name="but" label="But" :value="$organisation->but" required />
                <x-admin.image name="image" label="Image" :current="$organisation->image"
                               :url="$organisation->image ? asset('images/'.$organisation->image) : null" />
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.organisations.index') }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>
@endsection
