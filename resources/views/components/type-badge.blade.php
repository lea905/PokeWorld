{{-- Badge coloré d'un type. Utilisation : <x-type-badge :type="$pokemon->typePrincipal" class="fs-6" /> --}}
@props(['type'])

@if ($type)
    <span {{ $attributes->class(['badge rounded-pill', "text-bg-{$type->css}"]) }}>{{ $type->nom }}</span>
@endif
