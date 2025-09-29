<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'participant_id',
        'person_type',
        'ndoc',
        'user_id',
        'action'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
