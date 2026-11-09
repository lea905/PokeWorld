{{--
    Bandeau d'une page de détail, avec bouton de retour vers la liste.
    Utilisation : <x-back-hero theme="arene" :href="route('arene.index')">Retour aux Arènes</x-back-hero>
--}}
@props(['theme', 'href'])

<div {{ $attributes->class(['hero-pokemon page-hero', "hero-{$theme}"]) }} style="min-height: 280px; padding: 2rem;">
    <div class="container position-relative z-1">
        <a href="{{ $href }}" class="btn btn-light rounded-pill px-4 mb-4 shadow-sm text-decoration-none fw-bold" style="opacity: 0.9;">
            &larr; {{ $slot }}
        </a>
    </div>
</div>
