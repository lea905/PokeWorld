@extends('admin.layout')

@section('title', 'Organisations')

@section('actions')
    <a href="{{ route('admin.organisations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Créer une organisation</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>But</th>
                        <th>Membres</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organisations as $organisation)
                        <tr>
                            <td>
                                @if ($organisation->image)
                                    <img src="{{ asset('images/'.$organisation->image) }}" alt="" class="admin-thumb">
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $organisation->nom }}</td>
                            <td class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($organisation->but), 80) }}</td>
                            <td>{{ $organisation->dresseurs_count }}</td>
                            <td>
                                @include('admin.partials.row-actions', [
                                    'edit' => route('admin.organisations.edit', $organisation),
                                    'destroy' => route('admin.organisations.destroy', $organisation),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Aucune organisation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $organisations->links() }}</div>
@endsection
