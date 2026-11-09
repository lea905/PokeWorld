{{-- Pastilles des deux types d'un Pokémon. Utilisation : <x-pokemon-types :pokemon="$pokemon" /> --}}
@props(['pokemon'])

<div {{ $attributes->class(['mt-2 d-flex justify-content-center']) }}>
    <x-type-dot :type="$pokemon->typePrincipal" />
    <x-type-dot :type="$pokemon->typeSecondaire" />
</div>
