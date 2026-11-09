{{-- Message "aucun résultat". Utilisation : <x-empty-state icon="fas fa-user-slash" title="Aucun dresseur trouvé.">Revenez plus tard !</x-empty-state> --}}
@props(['icon', 'title'])

<div class="col-12 text-center my-5">
    <div class="p-5 bg-white shadow-sm rounded-4 d-inline-block">
        <i class="{{ $icon }} text-muted mb-3 empty-state-icon"></i>
        <h4 class="text-muted fw-bold">{{ $title }}</h4>
        @if ($slot->isNotEmpty())
            <p class="text-muted mb-0">{{ $slot }}</p>
        @endif
    </div>
</div>
