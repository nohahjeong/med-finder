<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'active_ingredient',
    'manufacturer',
    'presentation',
    'price_max',
    'registration',
    'slug',
])]
class Medication extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'price_max' => 'decimal:2',
        ];
    }
}
