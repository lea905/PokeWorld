<?php

namespace Tests\Feature;

use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use App\Models\Type;
use Database\Seeders\MechantsSeeder;
use Database\Seeders\PokepediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokepediaSeederTest extends TestCase
{
    use RefreshDatabase;

    /** Crée tous les Pokémon cités dans database/data/pokepedia.php. */
    private function creerPokemonsDesDonnees(): void
    {
        $type = Type::create(['nom' => 'Normal']);
        $base = [
            'nom_japonais' => 'x', 'generation' => 1, 'type1' => $type->id, 'description' => '',
            'image_principale' => 'x.png', 'mega_evolution_possible' => false, 'dynamax_possible' => false,
            'pv' => 1, 'attaque' => 1, 'defense' => 1, 'attaque_spe' => 1, 'defence_spe' => 1, 'vitesse' => 1,
            'is_legendary' => false, 'is_mythical' => false,
        ];

        $noms = [];
        $numeros = [];
        foreach (require database_path('data/pokepedia.php') as $fiche) {
            foreach ($fiche['equipes'] as [, $pokemons]) {
                foreach ($pokemons as $pokemon) {
                    is_array($pokemon) ? $noms[] = $pokemon[0] : $numeros[] = $pokemon;
                }
            }
        }

        // Pokémon cités par leur nom : numéros fictifs au-delà du Pokédex national
        foreach (array_values(array_unique($noms)) as $i => $nom) {
            Pokemon::create($base + ['nom' => $nom, 'numero_pokedex' => 2000 + $i]);
        }
        // Pokémon cités par leur numéro (équipes de Sacha)
        foreach (array_unique($numeros) as $numero) {
            Pokemon::create($base + ['nom' => "Pokémon {$numero}", 'numero_pokedex' => $numero]);
        }
    }

    private function dresseur(string $prenom): Dresseur
    {
        return Dresseur::create([
            'prenom' => $prenom, 'nom' => '', 'ville_natale' => 'X', 'region' => 'X',
            'ambition' => 'ancienne ambition', 'description' => 'ancienne description', 'est_mechant' => false,
        ]);
    }

    public function test_teams_and_profiles_come_from_the_verified_data(): void
    {
        $this->creerPokemonsDesDonnees();
        foreach (['Giovanni', 'Amos', 'Sbire', 'Pierre', 'Ondine', 'Sacha'] as $prenom) {
            $this->dresseur($prenom);
        }
        $giovanni = Dresseur::where('prenom', 'Giovanni')->firstOrFail();
        // Ancienne équipe non vérifiée : remplacée. Équipe créée dans l'admin : conservée.
        Team::create(['nom' => 'Équipe de Giovanni', 'dresseur_id' => $giovanni->id]);
        Team::create(['nom' => 'Ma propre équipe', 'dresseur_id' => $giovanni->id]);

        $this->seed(MechantsSeeder::class);
        $this->seed(PokepediaSeeder::class);
        $nombreEquipes = Team::count();
        $this->seed(PokepediaSeeder::class); // relancé : aucun doublon

        $this->assertSame($nombreEquipes, Team::count());

        // Giovanni : 12 combats vérifiés + son équipe personnelle
        $noms = $giovanni->teams()->pluck('nom');
        $this->assertCount(13, $noms);
        $this->assertContains('Ma propre équipe', $noms);
        $this->assertNotContains('Équipe de Giovanni', $noms);

        // Deux Rhinocorne dans la même équipe, dans l'ordre, avec leur niveau
        $jadielle = $giovanni->teams()->where('nom', 'like', 'Arène de Jadielle — Pokémon Rouge Feu%')->firstOrFail();
        $this->assertSame(
            ['Rhinocorne 45', 'Nidoking 45', 'Triopikeur 42', 'Rhinocorne 50', 'Nidoqueen 44'],
            $jadielle->pokemons->map(fn ($p) => "{$p->nom} {$p->pivot->niveau}")->all()
        );

        // Fiches mises à jour depuis Poképédia
        $this->assertSame('Rivamar', Dresseur::where('prenom', 'Hélio')->value('ville_natale'));
        $this->assertStringContainsString('Champion de l\'Arène de Jadielle', $giovanni->fresh()->description);
        $this->assertSame('Bourg Palette', Dresseur::where('prenom', 'Sacha')->value('ville_natale'));

        // Équipes du dessin animé : par numéro de Pokédex, sans niveau
        $sacha = Dresseur::where('prenom', 'Sacha')->firstOrFail();
        $this->assertSame(8, $sacha->teams()->count());
        $indigo = $sacha->teams()->where('nom', 'like', 'Ligue Indigo%')->firstOrFail();
        $this->assertSame([98, 99, 7, 25, 1, 89, 6], $indigo->pokemons->pluck('numero_pokedex')->all());
        $this->assertNull($indigo->pokemons->first()->pivot->niveau);

        // But des organisations vérifié
        $this->assertStringContainsString('Kyogre', Organisation::where('nom', 'Team Aqua')->value('but'));
    }

    public function test_a_team_is_skipped_when_a_pokemon_is_missing(): void
    {
        $this->dresseur('Pierre'); // aucun Pokémon en base (import PokéAPI partiel)

        $this->seed(PokepediaSeeder::class);

        $this->assertSame(0, Team::count());
        $this->assertStringContainsString('Arène d\'Argenta', Dresseur::where('prenom', 'Pierre')->value('description'));
    }

    public function test_levels_are_displayed_on_the_dresseur_page(): void
    {
        $this->creerPokemonsDesDonnees();
        $this->dresseur('Ondine');

        $this->seed(PokepediaSeeder::class);

        $this->get('/dresseur/ondine')
            ->assertOk()
            ->assertSee('Or HeartGold et Argent SoulSilver') // onglet du jeu
            ->assertSee("Arène d'Azuria")
            ->assertSeeInOrder(['Akwakwak', 'Niv. 49', 'Staross', 'Niv. 54']);
    }
}
