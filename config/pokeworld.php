<?php

return [
    // Nombre de Pokémon importés depuis la PokéAPI par PokemonSeeder (max 1025)
    'seed_limit' => (int) env('POKEMON_SEED_LIMIT', 1025),
];
