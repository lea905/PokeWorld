@extends('admin.layout')

@section('title', 'Arènes')

@section('actions')
    <a href="{{ route('admin.arenes.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Créer une arène</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Badge</th>
                        <th>Nom</th>
                        <th>Région</th>
                        <th>Lieu</th>
                        <th>Champion</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arenes as $arene)
                        <tr>
                            <td>
                                @if ($arene->badge_est_image)
                                    <img src="{{ asset('images/Badges/'.$arene->badge) }}" alt="" class="admin-thumb">
                                @else
                                    <span class="text-muted small">{{ $arene->badge ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $arene->nom }}</td>
                            <td>{{ $arene->region }}</td>
                            <td>{{ $arene->lieu }}</td>
                            <td>{{ $arene->champion?->nom_complet ?? '—' }}</td>
                            <td>
                                @include('admin.partials.row-actions', [
                                    'edit' => route('admin.arenes.edit', $arene),
                                    'destroy' => route('admin.arenes.destroy', $arene),
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Aucune arène.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $arenes->links() }}</div>
@endsection
