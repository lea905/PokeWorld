<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /** Nombre d'essais autorisés par minute pour un même email et une même IP. */
    private const MAX_ATTEMPTS = 5;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return ['password' => 'mot de passe'];
    }

    /**
     * Tente la connexion, avec limitation du nombre d'essais.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => 'Trop de tentatives. Réessayez dans '.RateLimiter::availableIn($this->throttleKey()).' secondes.',
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function throttleKey(): string
    {
        return Str::lower((string) $this->input('email')).'|'.$this->ip();
    }
}
