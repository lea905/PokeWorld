<?php

namespace App\Providers;

use App\Models\User;
use App\Support\SafeHtml;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Les vues utilisent Bootstrap 5 : la pagination suit le même style.
        Paginator::useBootstrapFive();

        // Accès au back-office : réservé aux utilisateurs administrateurs.
        Gate::define('access-admin', fn (User $user) => $user->is_admin);

        // @html($texte) : affiche du HTML saisi dans l'admin, nettoyé (protection XSS).
        Blade::directive('html', fn (string $expression) => "<?php echo \\".SafeHtml::class."::clean({$expression}); ?>");
    }
}
