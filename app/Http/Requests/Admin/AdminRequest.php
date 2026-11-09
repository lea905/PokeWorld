<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base des requêtes du back-office.
 *
 * Défense en profondeur : en plus du middleware "can:access-admin" sur les routes,
 * chaque requête vérifie elle-même le droit administrateur avant toute validation.
 */
abstract class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('access-admin');
    }
}
