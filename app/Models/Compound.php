<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    protected $fillable = [
        'vehicle_id',
        'officer_id',
        'offence_type_id',
        'status',
        'is_discounted',
        'issued_at',
        'paid_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function offenceType()
    {
        return $this->belongsTo(OffenceType::class);
    }

    public function appeal()
    {
        return $this->hasOne(Appeal::class);
    }
}