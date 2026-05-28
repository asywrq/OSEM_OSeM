<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffenceType extends Model
{
    protected $fillable = [
        'name',
        'amount',
    ];

    public function compounds()
    {
        return $this->hasMany(Compound::class);
    }
}