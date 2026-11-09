<?php

// Messages de validation en français (sous-ensemble utilisé par l'administration).
return [
    'array' => 'Le champ :attribute doit être une liste.',
    'different' => 'Les champs :attribute et :other doivent être différents.',
    'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
    'image' => 'Le champ :attribute doit être une image.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'max' => [
        'file' => 'Le fichier :attribute ne doit pas dépasser :max kilo-octets.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        'numeric' => 'Le champ :attribute ne doit pas dépasser :max.',
        'array' => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
    ],
    'min' => [
        'file' => 'Le fichier :attribute doit faire au moins :min kilo-octets.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :min.',
        'array' => 'Le champ :attribute doit contenir au moins :min éléments.',
    ],
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique' => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded' => "Le fichier :attribute n'a pas pu être téléversé.",

    'attributes' => [
        'nom' => 'nom',
        'but' => 'but',
        'lieu' => 'lieu',
        'ambition' => 'ambition',
        'description' => 'description',
        'grade' => 'grade',
        'image' => 'image',
        'badge' => 'badge',
        'dresseur_id' => 'dresseur',
        'organisation_id' => 'organisation',
    ],
];
