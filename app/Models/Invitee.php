<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invitee extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'participant_id',
        'ndoc',
        'full_name',
        'email',
        'phone'
    ];

    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        $result = DB::select('SELECT gen_random_uuid() AS uuid');
        return $result[0]->uuid;
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
