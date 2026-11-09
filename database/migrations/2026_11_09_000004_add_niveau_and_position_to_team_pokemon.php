<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Enrichit la composition des équipes :
 * - niveau de chaque Pokémon et position dans l'équipe ;
 * - une même espèce peut apparaître deux fois dans une équipe (ex. deux Rhinocorne),
 *   d'où une clé primaire propre (id) à la place de la clé (team_id, pokemon_id).
 *
 * La table est recréée puis les données existantes y sont recopiées.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('team_pokemon_nouvelle'); // reste d'une exécution interrompue

        Schema::create('team_pokemon_nouvelle', function (Blueprint $table) {
            $table->id();
            // Même type que les colonnes référencées : INT dans une base créée par Doctrine,
            // BIGINT UNSIGNED dans une base créée par Laravel (sinon MySQL refuse la clé étrangère)
            $this->colonneComme($table, 'team_id', 'team', 'id');
            $this->colonneComme($table, 'pokemon_id', 'pokemon', 'id_pokemon');
            $table->unsignedSmallInteger('niveau')->nullable();
            $table->unsignedTinyInteger('position')->nullable();

            $table->foreign('team_id')->references('id')->on('team')->cascadeOnDelete();
            $table->foreign('pokemon_id')->references('id_pokemon')->on('pokemon')->cascadeOnDelete();
            $table->index(['team_id', 'position']);
        });

        DB::table('team_pokemon')->orderBy('team_id')->orderBy('pokemon_id')->get()
            ->groupBy('team_id')
            ->each(function ($lignes, $teamId) {
                foreach ($lignes->values() as $index => $ligne) {
                    DB::table('team_pokemon_nouvelle')->insert([
                        'team_id' => $teamId,
                        'pokemon_id' => $ligne->pokemon_id,
                        'position' => $index + 1,
                    ]);
                }
            });

        Schema::drop('team_pokemon');
        Schema::rename('team_pokemon_nouvelle', 'team_pokemon');
    }

    /** Ajoute une colonne du même type entier (et même signe) que la colonne référencée. */
    private function colonneComme(Blueprint $table, string $nom, string $tableReferencee, string $colonneReferencee): void
    {
        $colonne = collect(Schema::getColumns($tableReferencee))->firstWhere('name', $colonneReferencee);
        $type = strtolower($colonne['type'] ?? 'bigint unsigned');
        $nonSigne = str_contains($type, 'unsigned');

        if (str_starts_with($colonne['type_name'] ?? 'bigint', 'int')) {
            $table->integer($nom, false, $nonSigne);
        } else {
            $table->bigInteger($nom, false, $nonSigne);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_pokemon_ancienne');

        Schema::create('team_pokemon_ancienne', function (Blueprint $table) {
            $this->colonneComme($table, 'team_id', 'team', 'id');
            $this->colonneComme($table, 'pokemon_id', 'pokemon', 'id_pokemon');
            $table->primary(['team_id', 'pokemon_id']);

            $table->foreign('team_id')->references('id')->on('team')->cascadeOnDelete();
            $table->foreign('pokemon_id')->references('id_pokemon')->on('pokemon')->cascadeOnDelete();
        });

        // Les doublons d'espèce dans une même équipe ne peuvent pas être conservés
        DB::table('team_pokemon')->select('team_id', 'pokemon_id')->distinct()->get()
            ->each(fn ($ligne) => DB::table('team_pokemon_ancienne')->insert((array) $ligne));

        Schema::drop('team_pokemon');
        Schema::rename('team_pokemon_ancienne', 'team_pokemon');
    }
};
