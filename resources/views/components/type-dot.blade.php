{{-- Pastille de couleur d'un type. Utilisation : <x-type-dot :type="$pokemon->typePrincipal" /> --}}
@props(['type'])

@if ($type)
    <span {{ $attributes->class(['type-dot', "text-bg-{$type->css}"]) }} title="{{ $type->nom }}"></span>
@endif
