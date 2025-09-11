<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'registration_id',
        'person_type',
        'ndoc',
        'gatekeeper_id',
        'action'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function gatekeeper()
    {
        return $this->belongsTo(EventGatekeeper::class);
    }
}
