<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    protected $table = 'supporters';

    protected $fillable = [
        'person_id',
        'support_status',
        'is_active',
        'joined_at',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
