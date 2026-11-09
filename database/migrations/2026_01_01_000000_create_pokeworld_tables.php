<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Schéma PokeWorld.
 *
 * Les noms de tables et de colonnes sont identiques à ceux générés par Doctrine
 * dans la version Symfony : une base existante reste donc compatible.
 * Chaque table n'est créée que si elle n'existe pas déjà.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('type')) {
            Schema::create('type', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
            });
        }

        if (! Schema::hasTable('type_relation')) {
            Schema::create('type_relation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('idTypeAttaquant')->constrained('type');
                $table->foreignId('idTypeDefenseur')->constrained('type');
                $table->double('efficacite');
            });
        }

        if (! Schema::hasTable('pokemon')) {
            Schema::create('pokemon', function (Blueprint $table) {
                $table->id('id_pokemon');
                $table->string('nom');
                $table->string('nom_japonais');
                $table->integer('numero_pokedex');
                $table->integer('generation');
                $table->foreignId('type1')->constrained('type');
                $table->foreignId('type2')->nullable()->constrained('type');
                $table->text('description');
                $table->string('image_principale');
                $table->boolean('mega_evolution_possible');
                $table->boolean('dynamax_possible');
                $table->unsignedBigInteger('idEvolutionPrecedente')->nullable();
                $table->string('cry_sound_url')->nullable();
                $table->integer('pv');
                $table->integer('attaque');
                $table->integer('defense');
                $table->integer('attaque_spe');
                $table->integer('defence_spe');
                $table->integer('vitesse');
                $table->integer('taille')->nullable();
                $table->integer('poids')->nullable();
                $table->string('couleur')->nullable();
                $table->string('habitat')->nullable();
                $table->integer('capture_rate')->nullable();
                $table->integer('base_experience')->nullable();
                $table->boolean('is_legendary');
                $table->boolean('is_mythical');
                $table->integer('gender_rate')->nullable();

                $table->foreign('idEvolutionPrecedente')->references('id_pokemon')->on('pokemon');
            });
        }

        if (! Schema::hasTable('organisation')) {
            Schema::create('organisation', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->text('but');
                $table->string('image')->nullable();
                $table->string('slug')->nullable()->unique();
            });
        }

        if (! Schema::hasTable('dresseur')) {
            Schema::create('dresseur', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->nullable()->unique();
                $table->string('nom')->nullable();
                $table->string('prenom');
                $table->string('ville_natale');
                $table->string('region');
                $table->text('ambition');
                $table->text('description');
                $table->string('image')->nullable();
                $table->boolean('est_mechant');
                $table->foreignId('organisation_id')->nullable()->constrained('organisation')->nullOnDelete();
                $table->string('grade')->nullable();
            });
        }

        if (! Schema::hasTable('arene')) {
            Schema::create('arene', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->nullable()->unique();
                $table->string('nom');
                $table->string('region');
                $table->string('lieu');
                $table->string('badge')->nullable();
                $table->string('image_badge')->nullable();
                $table->foreignId('idChampion')->nullable()->unique()->constrained('dresseur')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('team')) {
            Schema::create('team', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->foreignId('dresseur_id')->nullable()->constrained('dresseur')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('team_pokemon')) {
            Schema::create('team_pokemon', function (Blueprint $table) {
                $table->foreignId('team_id')->constrained('team')->cascadeOnDelete();
                $table->unsignedBigInteger('pokemon_id');
                $table->primary(['team_id', 'pokemon_id']);

                $table->foreign('pokemon_id')->references('id_pokemon')->on('pokemon')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('map')) {
            Schema::create('map', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('jeu');
                $table->string('image');
                $table->string('description');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('map');
        Schema::dropIfExists('team_pokemon');
        Schema::dropIfExists('team');
        Schema::dropIfExists('arene');
        Schema::dropIfExists('dresseur');
        Schema::dropIfExists('organisation');
        Schema::dropIfExists('pokemon');
        Schema::dropIfExists('type_relation');
        Schema::dropIfExists('type');
    }
};
