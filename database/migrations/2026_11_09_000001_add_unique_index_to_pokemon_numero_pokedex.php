<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le numéro de Pokédex sert désormais d'identifiant dans les URL (/pokedex/pokemon/25) :
 * il doit être unique, et l'index accélère la recherche.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            $table->unique('numero_pokedex');
        });
    }

    public function down(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            $table->dropUnique(['numero_pokedex']);
        });
    }
};
