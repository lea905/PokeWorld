<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesImageUpload
{
    /**
     * Gère le champ image d'un formulaire :
     * - nouveau fichier envoyé  => enregistré dans public/{$directory} sous un nom aléatoire ;
     * - case "supprimer" cochée => null ;
     * - sinon                   => valeur actuelle conservée.
     */
    protected function handleImage(Request $request, string $field, string $directory, ?string $current): ?string
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $name = Str::random(40).'.'.$file->extension();
            $file->move(public_path($directory), $name);

            return $name;
        }

        if ($request->boolean("{$field}_delete")) {
            return null;
        }

        return $current;
    }
}
