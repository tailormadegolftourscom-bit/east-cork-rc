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

    public static function levelLabel(string $classLevel): string
    {
        return self::levelLabels()[$classLevel] ?? str_replace('_', ' ', $classLevel);
    }

    public static function levelLabels(): array
    {
        return [
            'junior_infants' => 'Junior Infants',
            'senior_infants' => 'Senior Infants',
            '1st_class' => '1st Class',
            '2nd_class' => '2nd Class',
            '3rd_class' => '3rd Class',
            '4th_class' => '4th Class',
            '5th_class' => '5th Class',
            '6th_class' => '6th Class',
            '1st_year' => '1st Year',
            '2nd_year' => '2nd Year',
            '3rd_year' => '3rd Year',
            '4th_year' => '4th Year',
            '5th_year' => '5th Year',
            '6th_year' => '6th Year',
        ];
    }
}
