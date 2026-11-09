@extends('admin.layout')

@section('title', 'Pokémon')

@section('actions')
    <form action="{{ route('admin.pokemons.index') }}" method="GET" class="d-flex gap-2">
        <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Rechercher un Pokémon…">
        <button class="btn btn-outline-dark" type="submit"><i class="fas fa-search"></i></button>
    </form>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Types</th>
                        <th>Génération</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pokemons as $pokemon)
                        <tr>
                            <td class="text-muted">#{{ $pokemon->numero_pokedex }}</td>
                            <td><img src="{{ $pokemon->image_principale }}" alt="" class="admin-thumb" loading="lazy"></td>
                            <td class="fw-semibold">{{ $pokemon->nom }}</td>
                            <td>
                                <span class="badge text-bg-light border">{{ $pokemon->typePrincipal?->nom }}</span>
                                @if ($pokemon->typeSecondaire)
                                    <span class="badge text-bg-light border">{{ $pokemon->typeSecondaire->nom }}</span>
                                @endif
                            </td>
                            <td>{{ $pokemon->generation }}</td>
                            <td>
                                @include('admin.partials.row-actions', ['edit' => route('admin.pokemons.edit', $pokemon)])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Aucun Pokémon. Lancez <code>php artisan db:seed</code> pour les importer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $pokemons->links() }}</div>
@endsection
