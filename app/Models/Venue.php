<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A place activities happen — a hall, a centre, a stretch of greenway. */
class Venue extends Model
{
    protected $table = 'venues';

    protected $fillable = ['name', 'town', 'description', 'website_url', 'map_url', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getLabelAttribute(): string
    {
        return $this->town && ! str_contains($this->name, $this->town)
            ? $this->name.', '.$this->town
            : $this->name;
    }
}
