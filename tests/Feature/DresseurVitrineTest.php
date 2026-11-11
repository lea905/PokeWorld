<?php

namespace Tests\Feature;

use App\Models\Arene;
use App\Models\Dresseur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DresseurVitrineTest extends TestCase
{
    use RefreshDatabase;

    private function dresseur(string $prenom, array $attributes = []): Dresseur
    {
        return Dresseur::create($attributes + [
            'prenom' => $prenom, 'nom' => '', 'ville_natale' => 'Inconnue', 'region' => 'Kanto',
            'ambition' => 'Ambition', 'description' => 'Description', 'est_mechant' => false,
        ]);
    }

    public function test_theme_visuel_depends_on_camp(): void
    {
        $sacha = $this->dresseur('Sacha');
        $pierre = $this->dresseur('Pierre');
        $giovanni = $this->dresseur('Giovanni', ['est_mechant' => true]);
        Arene::create(['nom' => "Arène d'Argenta", 'region' => 'Kanto', 'lieu' => 'Argenta', 'idChampion' => $pierre->id]);
        Arene::create(['nom' => 'Arène de Jadielle', 'region' => 'Kanto', 'lieu' => 'Jadielle', 'idChampion' => $giovanni->id]);

        $this->assertSame('heros', $sacha->fresh()->theme_visuel);
        $this->assertSame('champion', $pierre->fresh()->theme_visuel);
        // L'antagoniste prime sur le statut de champion.
        $this->assertSame('mechant', $giovanni->fresh()->theme_visuel);
    }

    public function test_list_and_detail_render_the_vitrine(): void
    {
        $sacha = $this->dresseur('Sacha', ['image' => 'sacha.png']);
        $this->dresseur('Inconnu');

        $this->get('/dresseurs')
            ->assertOk()
            ->assertSee('dresseur-vitrine dresseur-vitrine--heros dresseur-vitrine--carte', false)
            ->assertSee('images/sacha.png', false)
            ->assertSee('aria-label="Image inconnue"', false);

        $this->get('/dresseur/'.$sacha->slug)
            ->assertOk()
            ->assertSee('dresseur-vitrine--fiche', false);
    }
}
