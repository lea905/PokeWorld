<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Équivalent de l'ancienne commande Symfony "app:load:all" :
// réinitialise la base puis charge tous les seeders (types, Pokémon, organisations, monde).
Artisan::command('app:load-all', function () {
    if (! $this->confirm('Toutes les tables vont être vidées puis rechargées. Continuer ?', true)) {
        return;
    }

    $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);
    $this->info('Tous les imports ont été exécutés avec succès.');
})->purpose('Réinitialise la base et charge toutes les données (PokéAPI + monde PokeWorld)');
