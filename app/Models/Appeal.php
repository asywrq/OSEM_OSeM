<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    protected $fillable = [
        'compound_id',
        'reviewed_by',
        'reason',
        'result',
        'submitted_at',
    ];

    public function compound()
    {
        return $this->belongsTo(Compound::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}