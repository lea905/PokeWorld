{{--
    Bandeau coloré en haut d'une page de liste.
    Utilisation : <x-page-hero theme="dresseur" title="Les Dresseurs" subtitle="..." />
    Thèmes : dresseur, arene, organisation (classes .hero-* dans public/css/app.css).
--}}
@props(['theme', 'title', 'subtitle' => null, 'icon' => null])

<div {{ $attributes->class(['hero-pokemon page-hero', "hero-{$theme}"]) }}>
    <div class="container position-relative z-1 hero-content text-center">
        <h1 class="display-4 fw-bold text-white mb-2 page-hero-title">
            @if ($icon)<i class="{{ $icon }} me-2"></i>@endif{{ $title }}
        </h1>
        @if ($subtitle)
            <p class="lead text-white opacity-75">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
</div>
