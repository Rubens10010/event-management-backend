<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function team_managers()
    {
        return $this->hasMany(TeamManager::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
