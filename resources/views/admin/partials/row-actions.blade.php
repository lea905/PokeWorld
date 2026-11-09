{{-- Boutons Modifier / Supprimer d'une ligne. Paramètres : $edit (URL), $destroy (URL, optionnel). --}}
<div class="d-flex justify-content-end gap-2">
    <a href="{{ $edit }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Modifier</a>
    @isset($destroy)
        <form action="{{ $destroy }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet élément ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </form>
    @endisset
</div>
