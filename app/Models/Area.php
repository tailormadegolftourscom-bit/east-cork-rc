<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function committees()
    {
        return $this->hasMany(Committee::class);
    }

    /**
     * The pilot's single area. Used to default new schools/committees
     * until admin UI exists for choosing an area at creation time.
     */
    public static function defaultId(): ?int
    {
        return static::where('slug', 'east-cork')->value('id');
    }
}
