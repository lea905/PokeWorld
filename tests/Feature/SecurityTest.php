<?php

namespace Tests\Feature;

use App\Models\Arene;
use App\Models\Dresseur;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private function dresseur(array $attributes = []): Dresseur
    {
        return Dresseur::create($attributes + [
            'prenom' => 'Pierre', 'nom' => '', 'ville_natale' => 'Argenta', 'region' => 'Kanto',
            'ambition' => 'Éleveur', 'description' => 'Expert Roche', 'est_mechant' => false,
        ]);
    }

    /* ----------------------------------------------------------------
     | Accès au back-office
     * ---------------------------------------------------------------- */

    public function test_guest_is_redirected_to_login_on_every_admin_page(): void
    {
        $dresseur = $this->dresseur();

        foreach (['/admin', '/admin/dresseurs', '/admin/teams', '/admin/arenes', '/admin/organisations',
            '/admin/pokemons', '/admin/dresseurs/create', "/admin/dresseurs/{$dresseur->id}/edit"] as $url) {
            $this->get($url)->assertRedirect('/connexion');
        }
    }

    public function test_guest_cannot_modify_or_delete_data(): void
    {
        $dresseur = $this->dresseur();

        $this->post('/admin/organisations', ['nom' => 'Team Aqua', 'but' => 'Océans'])->assertRedirect('/connexion');
        $this->put("/admin/dresseurs/{$dresseur->id}", ['prenom' => 'Pirate'])->assertRedirect('/connexion');
        $this->delete("/admin/dresseurs/{$dresseur->id}")->assertRedirect('/connexion');

        $this->assertSame(0, Organisation::count());
        $this->assertSame('Pierre', $dresseur->fresh()->prenom);
    }

    public function test_logged_in_user_without_admin_right_gets_403(): void
    {
        $dresseur = $this->dresseur();
        $this->actingAs(User::factory()->create()); // utilisateur normal

        $this->get('/admin')->assertForbidden();
        $this->get('/admin/dresseurs')->assertForbidden();
        $this->delete("/admin/dresseurs/{$dresseur->id}")->assertForbidden();

        $this->assertNotNull($dresseur->fresh());
    }

    public function test_admin_can_access_back_office(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin')->assertOk()->assertSee('Se déconnecter');
    }

    /* ----------------------------------------------------------------
     | Connexion / déconnexion
     * ---------------------------------------------------------------- */

    /**
     * Crée un admin avec un mot de passe aléatoire, généré à chaque test
     * (aucun mot de passe écrit en dur dans le dépôt).
     *
     * @return array{0: User, 1: string} l'utilisateur et son mot de passe en clair
     */
    private function adminWithRandomPassword(): array
    {
        $password = Str::password(16);
        $user = User::factory()->admin()->create(['email' => 'prof@pokeworld.test', 'password' => $password]);

        return [$user, $password];
    }

    public function test_admin_can_log_in_and_is_sent_to_the_page_requested(): void
    {
        [$user, $password] = $this->adminWithRandomPassword();

        $this->get('/admin/arenes'); // mémorise la page demandée

        $this->post('/connexion', ['email' => $user->email, 'password' => $password])
            ->assertRedirect('/admin/arenes');

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_rejected(): void
    {
        [$user] = $this->adminWithRandomPassword();

        $this->post('/connexion', ['email' => $user->email, 'password' => Str::password(16)])
            ->assertSessionHasErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);

        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        [$user, $password] = $this->adminWithRandomPassword();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/connexion', ['email' => $user->email, 'password' => Str::password(16)]);
        }

        // Même avec le bon mot de passe, la 6e tentative est bloquée
        $this->post('/connexion', ['email' => $user->email, 'password' => $password])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_log_out(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->post('/deconnexion')->assertRedirect('/');
        $this->assertGuest();
    }

    /* ----------------------------------------------------------------
     | Protection XSS
     * ---------------------------------------------------------------- */

    public function test_malicious_html_is_removed_but_formatting_is_kept(): void
    {
        $organisation = Organisation::create([
            'nom' => 'Team Rocket',
            'but' => '<p><strong>Dominer</strong> le monde</p><script>alert(1)</script>'
                .'<img src=x onerror="alert(2)"><a href="javascript:alert(3)">piège</a>'
                .'<a href="https://pokeapi.co" onclick="alert(4)">source</a>',
        ]);

        $html = $this->get("/organisation/{$organisation->slug}")->assertOk()->getContent();

        $this->assertStringContainsString('<strong>Dominer</strong>', $html);
        $this->assertStringContainsString('href="https://pokeapi.co"', $html);
        $this->assertStringNotContainsString('<script>alert(1)', $html);
        $this->assertStringNotContainsString('onerror', $html);
        $this->assertStringNotContainsString('javascript:alert(3)', $html);
        $this->assertStringNotContainsString('alert(4)', $html);
    }

    public function test_dresseur_and_champion_descriptions_are_sanitized(): void
    {
        $pierre = $this->dresseur([
            'description' => '<p>Expert</p><a href="javascript:alert(1)" onmouseover="alert(2)">x</a>',
            'ambition' => '<em>Éleveur</em><script>alert(3)</script>',
        ]);
        Arene::create(['nom' => "Arène d'Argenta", 'region' => 'Kanto', 'lieu' => 'Argenta', 'idChampion' => $pierre->id]);

        foreach (["/dresseur/{$pierre->slug}", '/arene/arene-d-argenta'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString('<p>Expert</p>', $html);
            $this->assertStringNotContainsString('javascript:alert(1)', $html);
            $this->assertStringNotContainsString('alert(2)', $html);
            $this->assertStringNotContainsString('alert(3)', $html);
        }
    }
}
