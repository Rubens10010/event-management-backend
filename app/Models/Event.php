<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'location',
        'capacity',
        'max_invitees',
        'starts_at',
        'finishes_at',
        'banner',
        'status'    // PENDING, REGISTERING, ACCESSING, CLOSED, FINISHED
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
