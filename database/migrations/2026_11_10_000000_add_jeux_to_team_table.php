<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Jeux dans lesquels apparaît une équipe (liste de valeurs App\Enums\Jeu, en JSON).
 * Une même équipe peut couvrir plusieurs jeux (ex. Rouge et Bleu + Rouge Feu et Vert Feuille).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team', function (Blueprint $table) {
            $table->json('jeux')->nullable()->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('team', function (Blueprint $table) {
            $table->dropColumn('jeux');
        });
    }
};
