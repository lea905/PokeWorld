@extends('admin.layout')

@section('title', 'Teams')

@section('actions')
    <a href="{{ route('admin.teams.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Créer une équipe</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Dresseur</th>
                        <th>Pokémon</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teams as $team)
                        <tr>
                            <td class="text-muted">{{ $team->id }}</td>
                            <td class="fw-semibold">{{ $team->nom }}</td>
                            <td>{{ $team->dresseur?->nom_complet ?? '—' }}</td>
                            <td>
                                @foreach ($team->pokemons as $pokemon)
                                    <span class="badge text-bg-light border">{{ $pokemon->nom }}</span>
                                @endforeach
                            </td>
                            <td>
                                @include('admin.partials.row-actions', [
                                    'edit' => route('admin.teams.edit', $team),
                                    'destroy' => route('admin.teams.destroy', $team),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Aucune équipe.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $teams->links() }}</div>
@endsection
