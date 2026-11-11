<?php

use App\Enums\Grade;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Le grade devient une liste fermée (App\Enums\Grade).
 * Les valeurs saisies librement auparavant sont ramenées à cette liste.
 */
return new class extends Migration
{
    /** Anciennes saisies (en minuscules) => grade normalisé. */
    private const CORRESPONDANCES = [
        'boss' => Grade::Boss,
        'chef' => Grade::Boss,
        'commandant' => Grade::Commandant,
        'commandants' => Grade::Commandant,
        'admin' => Grade::Commandant,
        'admins' => Grade::Commandant,
        'scientifique' => Grade::Scientifique,
        'sbire' => Grade::Sbire,
        'sbires' => Grade::Sbire,
    ];

    public function up(): void
    {
        foreach (DB::table('dresseur')->whereNotNull('grade')->get(['id', 'grade']) as $dresseur) {
            $grade = self::CORRESPONDANCES[mb_strtolower(trim($dresseur->grade))] ?? null;

            // Une valeur inconnue est retirée plutôt que de casser l'affichage
            DB::table('dresseur')->where('id', $dresseur->id)->update(['grade' => $grade?->value]);
        }
    }

    public function down(): void
    {
        // Normalisation non réversible : les anciennes saisies libres ne sont pas conservées.
    }
};
