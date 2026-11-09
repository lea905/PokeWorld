@extends('admin.layout')

@section('title', 'Dresseurs')

@section('actions')
    <a href="{{ route('admin.dresseurs.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Créer un dresseur</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Région</th>
                        <th>Antagoniste ?</th>
                        <th>Organisation</th>
                        <th>Teams</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dresseurs as $dresseur)
                        <tr>
                            <td class="text-muted">{{ $dresseur->id }}</td>
                            <td>
                                @if ($dresseur->image)
                                    <img src="{{ asset('images/'.$dresseur->image) }}" alt="" class="admin-thumb rounded-circle">
                                @endif
                            </td>
                            <td>{{ $dresseur->nom }}</td>
                            <td class="fw-semibold">{{ $dresseur->prenom }}</td>
                            <td>{{ $dresseur->region }}</td>
                            <td>
                                @if ($dresseur->est_mechant)
                                    <span class="badge text-bg-danger">Oui</span>
                                @else
                                    <span class="badge text-bg-secondary">Non</span>
                                @endif
                            </td>
                            <td>{{ $dresseur->organisation?->nom }}</td>
                            <td>{{ $dresseur->teams_count }}</td>
                            <td>
                                @include('admin.partials.row-actions', [
                                    'edit' => route('admin.dresseurs.edit', $dresseur),
                                    'destroy' => route('admin.dresseurs.destroy', $dresseur),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-5">Aucun dresseur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $dresseurs->links() }}</div>
@endsection
