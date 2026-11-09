<?php

namespace App\Http\Requests\Admin;

class OrganisationRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'but' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
