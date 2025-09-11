<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitee extends Model
{
    protected $fillable = [
        'participant_id',
        'ndoc',
        'full_name',
        'email',
        'phone'
    ];
}
