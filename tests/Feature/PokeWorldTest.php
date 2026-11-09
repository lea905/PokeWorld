<?php

namespace Tests\Feature;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokeWorldTest extends TestCase
{
    use RefreshDatabase;

    private function seedWorld(): array
    {
        $feu = Type::create(['nom' => 'Feu']);
        $vol = Type::create(['nom' => 'Vol']);

        $base = [
            'nom_japonais' => 'x', 'generation' => 1, 'description' => 'Un Pokémon de test.',
            'image_principale' => 'https://example.test/p.png', 'mega_evolution_possible' => false,
            'dynamax_possible' => false, 'pv' => 50, 'attaque' => 60, 'defense' => 70,
            'attaque_spe' => 80, 'defence_spe' => 90, 'vitesse' => 140,
            'is_legendary' => false, 'is_mythical' => false,
        ];

        $salameche = Pokemon::create($base + ['nom' => 'Salamèche', 'numero_pokedex' => 4, 'type1' => $feu->id]);
        $dracaufeu = Pokemon::create($base + [
            'nom' => 'Dracaufeu', 'numero_pokedex' => 6, 'type1' => $feu->id, 'type2' => $vol->id,
            'idEvolutionPrecedente' => $salameche->id_pokemon,
        ]);

        $rocket = Organisation::create(['nom' => 'Team Rocket', 'but' => '<p>Dominer le monde.</p>']);
        $pierre = Dresseur::create([
            'prenom' => 'Pierre', 'nom' => '', 'ville_natale' => 'Argenta', 'region' => 'Kanto',
            'ambition' => 'Éleveur', 'description' => 'Expert Roche', 'est_mechant' => false,
        ]);
        Dresseur::create([
            'prenom' => 'Giovanni', 'nom' => '', 'ville_natale' => 'Jadielle', 'region' => 'Kanto',
            'ambition' => 'Mewtwo', 'description' => 'Boss', 'est_mechant' => true,
            'organisation_id' => $rocket->id, 'grade' => 'Boss',
        ]);
        $arene = Arene::create(['nom' => "Arène d'Argenta", 'region' => 'Kanto', 'lieu' => 'Argenta', 'badge' => 'roche.webp', 'idChampion' => $pierre->id]);

        $team = Team::create(['nom' => 'Équipe de Pierre', 'dresseur_id' => $pierre->id]);
        $team->pokemons()->attach([$salameche->id_pokemon, $dracaufeu->id_pokemon]);

        return compact('salameche', 'dracaufeu', 'pierre', 'arene', 'rocket', 'team');
    }

    public function test_slugs_are_generated_like_symfony(): void
    {
        $world = $this->seedWorld();

        $this->assertSame('arene-d-argenta', $world['arene']->slug);
        $this->assertSame('pierre', $world['pierre']->slug);
        $this->assertSame('team-rocket', $world['rocket']->slug);
    }

    public function test_public_pages_render(): void
    {
        $world = $this->seedWorld();

        $this->get('/')->assertOk()->assertSee("Le Guide de l'Expert Dresseur", false);
        $this->get('/pokedex')->assertOk()->assertSee('Dracaufeu');
        $this->get('/pokedex/1?type=Vol')->assertOk()->assertSee('Dracaufeu')->assertDontSee('Salamèche');
        $this->get('/pokedex?evolution_stage=base')->assertOk()->assertSee('Salamèche')->assertDontSee('Dracaufeu');
        $this->get('/pokedex/show/Dracaufeu')->assertOk()->assertSee("Famille d'évolution", false)->assertSee('text-bg-vol');
        $this->get('/pokedex/show/Inconnu')->assertNotFound();
        $this->get('/dresseurs')->assertOk()->assertSee('Giovanni');
        $this->get('/dresseur/pierre')->assertOk()->assertSee('Équipe de Pierre');
        $this->get('/arenes')->assertOk()->assertSee('Badge Roche');
        $this->get('/arene/arene-d-argenta')->assertOk()->assertSee('Le Champion');
        $this->get('/organisations')->assertOk()->assertSee('1 membres connus');
        $this->get('/organisation/team-rocket')->assertOk()->assertSee('Boss');
        $this->get('/recherche?q=Argenta')->assertOk()->assertSee('Pierre')->assertSee("Arène d'Argenta");
    }

    public function test_admin_pages_render(): void
    {
        $world = $this->seedWorld();

        foreach (['/admin', '/admin/dresseurs', '/admin/teams', '/admin/arenes', '/admin/organisations', '/admin/pokemons',
            '/admin/dresseurs/create', '/admin/teams/create', '/admin/arenes/create', '/admin/organisations/create',
            "/admin/dresseurs/{$world['pierre']->id}/edit", "/admin/teams/{$world['team']->id}/edit",
            "/admin/arenes/{$world['arene']->id}/edit", "/admin/organisations/{$world['rocket']->id}/edit",
            "/admin/pokemons/{$world['dracaufeu']->id_pokemon}/edit"] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_updates_dresseur_and_syncs_teams(): void
    {
        $world = $this->seedWorld();
        $pierre = $world['pierre'];

        $this->put("/admin/dresseurs/{$pierre->id}", [
            'prenom' => 'Pierre', 'nom' => '', 'ville_natale' => 'Argenta', 'region' => 'Kanto',
            'ambition' => 'Éleveur', 'description' => 'Expert Roche', 'est_mechant' => '0',
            'teams' => [
                ['id' => $world['team']->id, 'nom' => 'Équipe A', 'pokemons' => [$world['salameche']->id_pokemon]],
                ['nom' => 'Équipe B', 'pokemons' => [$world['dracaufeu']->id_pokemon]],
            ],
        ])->assertRedirect('/admin/dresseurs');

        $teams = $pierre->teams()->with('pokemons')->orderBy('id')->get();
        $this->assertSame(['Équipe A', 'Équipe B'], $teams->pluck('nom')->all());
        $this->assertSame(['Salamèche'], $teams[0]->pokemons->pluck('nom')->all());

        // Retirer une équipe du formulaire la supprime (orphanRemoval)
        $this->put("/admin/dresseurs/{$pierre->id}", [
            'prenom' => 'Pierre', 'ville_natale' => 'Argenta', 'region' => 'Kanto',
            'ambition' => 'Éleveur', 'description' => 'Expert Roche',
        ])->assertRedirect('/admin/dresseurs');

        $this->assertSame(0, $pierre->teams()->count());
    }

    public function test_admin_validation_errors_are_in_french(): void
    {
        $this->post('/admin/organisations', [])->assertSessionHasErrors([
            'nom' => 'Le champ nom est obligatoire.',
        ]);
    }
}
