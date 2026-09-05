<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolRegistrationRequest extends Model
{
    protected $table = 'school_registration_requests';

    protected $fillable = [
        'school_name',
        'school_type',
        'town',
        'website_url',
        'contact_name',
        'contact_email',
        'contact_phone',
        'notes',
        'request_status',
        'created_school_id',
    ];

    public function createdSchool()
    {
        return $this->belongsTo(School::class, 'created_school_id');
    }
}
