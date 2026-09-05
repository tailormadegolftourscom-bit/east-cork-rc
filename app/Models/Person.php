<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
use App\Models\Supporter;

class Person extends Model
{
    protected $table = 'people';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'public_name_mode',
        'preferred_contact_method',
        'phone_verified_at',
    ];

    public function getPublicDisplayNameAttribute(): string
    {
        if ($this->public_name_mode === 'anon_code') {
            return 'AD' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
        }

        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'parent_person_id');
    }

    public function supporter()
    {
        return $this->hasOne(Supporter::class, 'person_id');
    }
}
