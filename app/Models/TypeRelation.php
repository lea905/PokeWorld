<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeRelation extends Model
{
    protected $table = 'type_relation';

    public $timestamps = false;

    protected $fillable = ['idTypeAttaquant', 'idTypeDefenseur', 'efficacite'];

    protected $casts = ['efficacite' => 'float'];

    public function typeAttaquant(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'idTypeAttaquant');
    }

    public function typeDefenseur(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'idTypeDefenseur');
    }
}
