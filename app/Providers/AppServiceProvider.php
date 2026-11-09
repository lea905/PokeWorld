<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
    }
}
