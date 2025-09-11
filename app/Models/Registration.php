<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'participant_id',
        'approved_by',
        'qr_code'
    ];
}
