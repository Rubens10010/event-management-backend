<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'max_participants',
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

    public function managers()
    {
        return $this->hasManyThrough(User::class, TeamManager::class, 'team_id', 'id', 'id', 'user_id');
    }
}
