<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokedexTest extends TestCase
{
    use RefreshDatabase;

    /** Crée $count Pokémon numérotés de 1 à $count (génération 1 pour les 151 premiers, 2 ensuite). */
    private function createPokemons(int $count): void
    {
        $type = Type::create(['nom' => 'Normal']);

        for ($i = 1; $i <= $count; $i++) {
            Pokemon::create([
                'nom' => "Pokémon {$i}", 'nom_japonais' => 'x', 'numero_pokedex' => $i,
                'generation' => $i <= 151 ? 1 : 2, 'type1' => $type->id, 'description' => '',
                'image_principale' => 'x.png', 'mega_evolution_possible' => false, 'dynamax_possible' => false,
                'pv' => 1, 'attaque' => 1, 'defense' => 1, 'attaque_spe' => 1, 'defence_spe' => 1, 'vitesse' => 1,
                'is_legendary' => false, 'is_mythical' => false,
            ]);
        }
    }

    public function test_pokedex_is_paginated_by_48(): void
    {
        $this->createPokemons(100);

        $this->get('/pokedex')
            ->assertOk()
            ->assertSee('100 Pokémon — affichage de 1 à 48')
            ->assertSee('/pokedex/pokemon/48', false)
            ->assertDontSee('/pokedex/pokemon/49', false)
            ->assertSee('?page=2', false);

        $this->get('/pokedex?page=3')
            ->assertOk()
            ->assertSee('affichage de 97 à 100')
            ->assertSee('/pokedex/pokemon/100', false);
    }

    public function test_filters_are_kept_when_changing_page(): void
    {
        $this->createPokemons(160);

        // Génération 1 = 151 Pokémon : la page 2 du filtre doit garder ?type=Normal
        $this->get('/pokedex/1?type=Normal')
            ->assertOk()
            ->assertSee('151 Pokémon')
            ->assertSee('type=Normal&amp;page=2', false);
    }

    public function test_type_filter_only_lists_types_used_by_a_pokemon(): void
    {
        $this->createPokemons(3); // tous de type "Normal"
        Type::create(['nom' => '???']);
        Type::create(['nom' => 'Stellaire']);

        $this->get('/pokedex')
            ->assertOk()
            ->assertSee('<option value="Normal"', false)
            ->assertDontSee('<option value="???"', false)
            ->assertDontSee('<option value="Stellaire"', false);
    }

    public function test_pokemon_page_uses_its_pokedex_number(): void
    {
        $this->createPokemons(30);

        $this->get('/pokedex/pokemon/25')->assertOk()->assertSee('Pokémon 25');
        $this->get('/pokedex/pokemon/abc')->assertNotFound();
    }
}
