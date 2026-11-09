<?php

namespace Tests\Feature;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\Pokemon;
use App\Models\Team;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    private function dresseur(string $prenom = 'Pierre'): Dresseur
    {
        return Dresseur::create([
            'prenom' => $prenom, 'nom' => '', 'ville_natale' => 'Argenta', 'region' => 'Kanto',
            'ambition' => 'Éleveur', 'description' => 'Expert', 'est_mechant' => false,
        ]);
    }

    private function pokemon(Type $type, string $nom, int $numero): Pokemon
    {
        return Pokemon::create([
            'nom' => $nom, 'nom_japonais' => 'x', 'numero_pokedex' => $numero, 'generation' => 1,
            'type1' => $type->id, 'description' => '', 'image_principale' => 'x.png',
            'mega_evolution_possible' => false, 'dynamax_possible' => false,
            'pv' => 1, 'attaque' => 1, 'defense' => 1, 'attaque_spe' => 1, 'defence_spe' => 1, 'vitesse' => 1,
            'is_legendary' => false, 'is_mythical' => false,
        ]);
    }

    public function test_dresseur_requires_its_mandatory_fields(): void
    {
        $this->post('/admin/dresseurs', [])->assertSessionHasErrors([
            'prenom' => 'Le champ prénom est obligatoire.',
            'ville_natale' => 'Le champ ville natale est obligatoire.',
            'region' => 'Le champ région est obligatoire.',
            'ambition', 'description',
        ]);

        $this->assertSame(0, Dresseur::count());
    }

    public function test_dresseur_is_created_with_teams_and_antagonist_flag(): void
    {
        $feu = Type::create(['nom' => 'Feu']);
        $salameche = $this->pokemon($feu, 'Salamèche', 4);

        $this->post('/admin/dresseurs', [
            'prenom' => 'Blue', 'ville_natale' => 'Bourg Palette', 'region' => 'Kanto',
            'ambition' => 'Battre Red', 'description' => 'Rival', 'est_mechant' => '1',
            'teams' => [['nom' => 'Équipe de Blue', 'pokemons' => [$salameche->id_pokemon]]],
        ])->assertRedirect('/admin/dresseurs')->assertSessionHasNoErrors();

        $blue = Dresseur::where('prenom', 'Blue')->firstOrFail();
        $this->assertTrue($blue->est_mechant);
        $this->assertSame('blue', $blue->slug);
        $this->assertSame(['Salamèche'], $blue->teams()->first()->pokemons->pluck('nom')->all());
    }

    public function test_team_rejects_unknown_pokemon(): void
    {
        $this->post('/admin/teams', ['nom' => 'Équipe', 'pokemons' => [999]])
            ->assertSessionHasErrors('pokemons.0');

        $this->assertSame(0, Team::count());
    }

    public function test_a_dresseur_can_only_be_champion_of_one_arena(): void
    {
        $pierre = $this->dresseur();
        $argenta = Arene::create(['nom' => "Arène d'Argenta", 'region' => 'Kanto', 'lieu' => 'Argenta', 'idChampion' => $pierre->id]);

        // Une autre arène ne peut pas prendre le même champion…
        $this->post('/admin/arenes', ['nom' => 'Arène bis', 'region' => 'Kanto', 'lieu' => 'Ailleurs', 'idChampion' => $pierre->id])
            ->assertSessionHasErrors(['idChampion' => "Ce dresseur est déjà champion d'une autre arène."]);

        // …mais l'arène actuelle peut être modifiée en gardant son champion.
        $this->put("/admin/arenes/{$argenta->id}", ['nom' => "Arène d'Argenta", 'region' => 'Kanto', 'lieu' => 'Argenta City', 'idChampion' => $pierre->id])
            ->assertSessionHasNoErrors();

        $this->assertSame('Argenta City', $argenta->fresh()->lieu);
    }

    public function test_organisation_rejects_a_non_image_upload(): void
    {
        $this->post('/admin/organisations', [
            'nom' => 'Team Magma', 'but' => 'Étendre les terres',
            'image' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors(['image' => 'Le champ image doit être une image.']);

        $this->assertSame(0, Organisation::count());
    }

    public function test_pokemon_types_must_be_different(): void
    {
        $feu = Type::create(['nom' => 'Feu']);
        $pokemon = $this->pokemon($feu, 'Salamèche', 4);

        $this->put("/admin/pokemons/{$pokemon->id_pokemon}", [
            'nom' => 'Salamèche', 'numero_pokedex' => 4, 'type1' => $feu->id, 'type2' => $feu->id, 'image_principale' => 'x.png',
        ])->assertSessionHasErrors(['type2' => 'Les champs type 2 et type 1 doivent être différents.']);
    }
}
