<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A kind of support someone offers — armchair, volunteer, teacher, celebrity.
 *
 * A table rather than an enum so new kinds can be added from the admin screen
 * without a migration. Not mutually exclusive: a teacher may also volunteer.
 */
class SupporterCategory extends Model
{
    protected $table = 'supporter_categories';

    protected $fillable = ['slug', 'name', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function supporters()
    {
        return $this->morphedByMany(Supporter::class, 'linkable', 'supporter_category_links', 'supporter_category_id')
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->morphedByMany(Parents::class, 'linkable', 'supporter_category_links', 'supporter_category_id')
            ->withTimestamps();
    }
}
