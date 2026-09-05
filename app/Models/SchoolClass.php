<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'school_id',
        'class_level',
        'class_stream',
        'display_name',
        'sort_order',
        'total_pupils',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function childLinks()
    {
        return $this->hasMany(ChildSchoolLink::class, 'current_school_class_id');
    }
}
