<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGatekeeper extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
    ];
}
