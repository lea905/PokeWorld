<?php

namespace Tests\Feature;

use App\Enums\Grade;
use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\User;
use Database\Seeders\MechantsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MechantsTest extends TestCase
{
    use RefreshDatabase;

    private function dresseur(string $prenom, array $attributes = []): Dresseur
    {
        return Dresseur::create($attributes + [
            'prenom' => $prenom, 'nom' => '', 'ville_natale' => 'Inconnue', 'region' => 'Kanto',
            'ambition' => 'Ambition', 'description' => 'Description', 'est_mechant' => false,
        ]);
    }

    private function rocket(): Organisation
    {
        $rocket = Organisation::create(['nom' => 'Team Rocket', 'but' => 'Dominer le monde.']);

        $this->dresseur('Sbire', ['est_mechant' => true, 'grade' => Grade::Sbire, 'organisation_id' => $rocket->id]);
        $this->dresseur('Giovanni', ['est_mechant' => true, 'grade' => Grade::Boss, 'organisation_id' => $rocket->id]);
        $this->dresseur('Amos', ['est_mechant' => true, 'grade' => Grade::Commandant, 'organisation_id' => $rocket->id]);

        return $rocket;
    }

    public function test_organisation_members_are_sorted_by_grade(): void
    {
        $this->rocket();

        $this->get('/organisation/team-rocket')
            ->assertOk()
            ->assertSeeInOrder(['Giovanni', 'Amos', 'Sbire']);
    }

    public function test_search_finds_organisations_and_their_members(): void
    {
        $this->rocket();
        $this->dresseur('Sacha');

        $this->get('/recherche?q=Rocket')
            ->assertOk()
            ->assertSee('Organisations (1)')
            ->assertSee('Dresseurs (3)')
            ->assertSee('Giovanni')
            ->assertSee('Amos')
            ->assertDontSee('Sacha');
    }

    public function test_dresseurs_can_be_filtered_by_camp(): void
    {
        $this->rocket();
        $this->dresseur('Sacha');

        $this->get('/dresseurs?camp=antagonistes')->assertOk()->assertSee('Giovanni')->assertDontSee('Sacha');
        $this->get('/dresseurs?camp=heros')->assertOk()->assertSee('Sacha')->assertDontSee('Giovanni');
        $this->get('/dresseurs?camp=nimportequoi')->assertOk()->assertSee('Sacha')->assertSee('Giovanni');
    }

    public function test_admin_only_accepts_known_grades(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $amos = $this->dresseur('Amos', ['est_mechant' => true]);
        $donnees = ['prenom' => 'Amos', 'ville_natale' => 'Inconnue', 'region' => 'Johto', 'ambition' => 'A', 'description' => 'D'];

        $this->put("/admin/dresseurs/{$amos->id}", $donnees + ['grade' => 'Empereur'])
            ->assertSessionHasErrors(['grade' => 'La valeur sélectionnée pour grade est invalide.']);

        $this->put("/admin/dresseurs/{$amos->id}", $donnees + ['grade' => 'Commandant'])
            ->assertSessionHasNoErrors();

        $this->assertSame(Grade::Commandant, $amos->fresh()->grade);
    }

    public function test_free_text_grades_are_normalized_by_the_migration(): void
    {
        foreach (['Commandants', 'admin', 'Boss', 'Sbire', 'Grand manitou'] as $i => $grade) {
            DB::table('dresseur')->insert([
                'prenom' => "Membre {$i}", 'ville_natale' => 'X', 'region' => 'X', 'ambition' => 'X',
                'description' => 'X', 'est_mechant' => true, 'grade' => $grade, 'slug' => "membre-{$i}",
            ]);
        }

        $migration = require database_path('migrations/2026_11_09_000003_normalize_dresseur_grades.php');
        $migration->up();

        $this->assertSame(
            ['Commandant', 'Commandant', 'Boss', 'Sbire', null],
            DB::table('dresseur')->orderBy('id')->pluck('grade')->all()
        );
    }

    public function test_mechants_seeder_only_adds_what_is_missing(): void
    {
        $this->rocket();

        $this->seed(MechantsSeeder::class);
        $this->seed(MechantsSeeder::class); // relancé : aucun doublon

        $this->assertSame('Arène de Jadielle', Dresseur::where('prenom', 'Giovanni')->firstOrFail()->arene?->nom);
        $this->assertSame(1, Arene::where('nom', 'Arène de Jadielle')->count());
        $this->assertSame(['Team Aqua', 'Team Galaxie', 'Team Magma', 'Team Rocket'], Organisation::orderBy('nom')->pluck('nom')->all());

        $helio = Dresseur::where('prenom', 'Hélio')->firstOrFail();
        $this->assertSame(1, Dresseur::where('prenom', 'Hélio')->count());
        $this->assertSame(Grade::Boss, $helio->grade);
        $this->assertSame('Team Galaxie', $helio->organisation->nom);
        $this->assertSame('Rivamar', $helio->ville_natale); // vérifié sur Poképédia
    }
}
