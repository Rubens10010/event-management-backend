<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'logo'
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'organization_managers');
    }
}
