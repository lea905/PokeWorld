<?php

namespace Tests\Feature;

use App\Enums\Jeu;
use App\Models\Dresseur;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\PokepediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipesParJeuTest extends TestCase
{
    use RefreshDatabase;

    private function dresseur(): Dresseur
    {
        return Dresseur::create([
            'prenom' => 'Giovanni', 'nom' => '', 'ville_natale' => 'Jadielle', 'region' => 'Kanto',
            'ambition' => 'A', 'description' => 'D', 'est_mechant' => true,
        ]);
    }

    private function equipe(Dresseur $dresseur, string $nom, ?array $jeux): Team
    {
        return Team::create(['nom' => $nom, 'jeux' => $jeux, 'dresseur_id' => $dresseur->id]);
    }

    public function test_teams_are_grouped_by_game_in_release_order(): void
    {
        $giovanni = $this->dresseur();
        $this->equipe($giovanni, 'Arène de Jadielle — Pokémon : Let\'s Go', [Jeu::LetsGo]);
        $this->equipe($giovanni, 'Repaire Rocket — Pokémon Rouge, Bleu, Rouge Feu et Vert Feuille', [Jeu::RougeBleu, Jeu::RougeFeuVertFeuille]);
        $this->equipe($giovanni, 'Ma propre équipe', null);
        $this->equipe($giovanni, 'Arène de Jadielle — Pokémon Jaune', [Jeu::Jaune]);

        $groupes = Team::grouperParJeu($giovanni->teams);

        $this->assertSame(
            ['Rouge et Bleu', 'Jaune', 'Rouge Feu et Vert Feuille', "Let's Go, Pikachu et Let's Go, Évoli", 'Autres équipes'],
            $groupes->pluck('libelle')->all()
        );
        // Une équipe commune à plusieurs jeux apparaît dans chacun
        $this->assertSame(['Repaire Rocket'], $groupes[0]['teams']->map->titre->all());
        $this->assertSame(['Repaire Rocket'], $groupes[2]['teams']->map->titre->all());
        $this->assertSame(['Ma propre équipe'], $groupes[4]['teams']->map->titre->all());
    }

    public function test_title_drops_the_game_but_keeps_a_note_in_brackets(): void
    {
        $amos = $this->dresseur();

        $avecNote = $this->equipe($amos, "Entrepôt Rocket — Pokémon Rouge Feu et Vert Feuille (identité de l'Admin supposée)", [Jeu::RougeFeuVertFeuille]);
        $sansJeu = $this->equipe($amos, 'Équipe — sans jeu', null);

        $this->assertSame("Entrepôt Rocket (identité de l'Admin supposée)", $avecNote->titre);
        $this->assertSame('Équipe — sans jeu', $sansJeu->titre);
    }

    public function test_every_verified_team_name_maps_to_known_games(): void
    {
        foreach (require database_path('data/pokepedia.php') as $fiche) {
            foreach ($fiche['equipes'] as [$nom]) {
                PokepediaSeeder::jeuxDepuisNom($nom); // lève une exception si un libellé est inconnu
            }
        }

        $this->assertSame([Jeu::RougeBleu, Jeu::Jaune, Jeu::RougeFeuVertFeuille],
            PokepediaSeeder::jeuxDepuisNom("Arène d'Azuria — Pokémon Rouge, Bleu, Jaune, Rouge Feu et Vert Feuille"));
        $this->assertSame([Jeu::DessinAnime], PokepediaSeeder::jeuxDepuisNom('Ligue Indigo, Kanto (dessin animé)'));
        $this->assertNull(PokepediaSeeder::jeuxDepuisNom('Pokémon courants des Sbires de la Team Rocket'));
    }

    public function test_dresseur_page_shows_one_tab_per_game(): void
    {
        $giovanni = $this->dresseur();
        $this->equipe($giovanni, 'Repaire Rocket — Pokémon Rouge, Bleu, Rouge Feu et Vert Feuille', [Jeu::RougeBleu, Jeu::RougeFeuVertFeuille]);
        $this->equipe($giovanni, 'Arène de Jadielle — Pokémon Jaune', [Jeu::Jaune]);

        $this->get('/dresseur/giovanni')
            ->assertOk()
            ->assertSee('role="tablist"', false)
            ->assertSeeInOrder(['Rouge et Bleu', 'Jaune', 'Rouge Feu et Vert Feuille'])
            ->assertSee('Même équipe dans : Rouge Feu et Vert Feuille')
            ->assertSee('Arène de Jadielle');
    }

    public function test_no_tabs_when_all_teams_belong_to_one_group(): void
    {
        $giovanni = $this->dresseur();
        $this->equipe($giovanni, 'Équipe A', null);
        $this->equipe($giovanni, 'Équipe B', null);

        $this->get('/dresseur/giovanni')
            ->assertOk()
            ->assertDontSee('role="tablist"', false)
            ->assertSee('Équipe A')
            ->assertSee('Équipe B');
    }

    public function test_admin_chooses_the_games_of_a_team(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $giovanni = $this->dresseur();
        $team = $this->equipe($giovanni, 'Équipe', null);

        // Cochés dans le désordre : rangés dans l'ordre de sortie
        $this->put("/admin/teams/{$team->id}", ['nom' => 'Équipe', 'dresseur_id' => $giovanni->id, 'jeux' => ['lets-go', 'rouge-bleu']])
            ->assertSessionHasNoErrors();
        $this->assertSame([Jeu::RougeBleu, Jeu::LetsGo], $team->fresh()->jeux->all());

        // Aucun jeu coché : retour dans « Autres équipes »
        $this->put("/admin/teams/{$team->id}", ['nom' => 'Équipe', 'dresseur_id' => $giovanni->id])
            ->assertSessionHasNoErrors();
        $this->assertNull($team->fresh()->jeux);

        $this->put("/admin/teams/{$team->id}", ['nom' => 'Équipe', 'jeux' => ['pokemon-or-fictif']])
            ->assertSessionHasErrors('jeux.0');
    }
}
