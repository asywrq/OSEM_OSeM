<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffenceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
    ];

    public function compounds()
    {
        return $this->hasMany(Compound::class);
    }
}