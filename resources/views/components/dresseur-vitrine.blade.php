{{--
    Vitrine d'un dresseur : artwork entier posé sur un fond coloré selon son camp
    (héros, champion, antagoniste), avec une Poké Ball en filigrane.
    Utilisation :
        <x-dresseur-vitrine :dresseur="$dresseur" class="dresseur-vitrine--carte" />
        <x-dresseur-vitrine :dresseur="$dresseur" class="dresseur-vitrine--fiche" eager />
--}}
@props(['dresseur', 'eager' => false])

<div {{ $attributes->class(['dresseur-vitrine', 'dresseur-vitrine--'.$dresseur->theme_visuel]) }}>
    @if ($dresseur->image)
        <img src="{{ asset('images/'.$dresseur->image) }}" class="dresseur-vitrine-img"
             alt="{{ $dresseur->nom_complet }}" @unless ($eager) loading="lazy" @endunless>
    @else
        <i class="fas fa-user dresseur-vitrine-vide" role="img" aria-label="Image inconnue"></i>
    @endif
    {{ $slot }}
</div>
