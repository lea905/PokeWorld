<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La colonne image_badge n'est plus utilisée : le badge est l'image stockée
 * dans public/images/Badges (colonne "badge").
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('arene', 'image_badge')) {
            Schema::table('arene', function (Blueprint $table) {
                $table->dropColumn('image_badge');
            });
        }
    }

    public function down(): void
    {
        Schema::table('arene', function (Blueprint $table) {
            $table->string('image_badge')->nullable()->after('badge');
        });
    }
};
