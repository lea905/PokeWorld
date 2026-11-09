<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

// Crée (ou promeut) un compte administrateur pour accéder au back-office.
Artisan::command('app:create-admin {email : Email du compte}', function (string $email) {
    $validator = Validator::make(['email' => $email], ['email' => ['required', 'email']]);
    if ($validator->fails()) {
        $this->error('Email invalide.');

        return 1;
    }

    $user = User::firstOrNew(['email' => $email]);

    if (! $user->exists) {
        $user->name = $this->ask('Nom affiché', Str::before($email, '@'));
    }

    if (! $user->exists || $this->confirm('Ce compte existe déjà. Changer son mot de passe ?', false)) {
        $password = $this->secret('Mot de passe (12 caractères minimum)');

        if (mb_strlen((string) $password) < 12) {
            $this->error('Mot de passe trop court.');

            return 1;
        }

        if ($password !== $this->secret('Confirmez le mot de passe')) {
            $this->error('Les mots de passe ne correspondent pas.');

            return 1;
        }

        $user->password = $password; // haché automatiquement (cast "hashed")
    }

    $user->is_admin = true;
    $user->save();

    $this->info("Administrateur prêt : {$user->email}");

    return 0;
})->purpose('Crée un compte administrateur pour le back-office');
