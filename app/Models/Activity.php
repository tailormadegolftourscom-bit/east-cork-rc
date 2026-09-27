<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Something for the kids to do together: planned (it is happening) or
 * suggested (an idea waiting for someone to take it on).
 */
class Activity extends Model
{
    public const STATUSES = [
        'planned' => 'Planned',
        'suggested' => 'Suggested',
    ];

    protected $table = 'activities';

    protected $fillable = [
        'title',
        'status',
        'venue_id',
        'starts_on',
        'schedule',
        'summary',
        'convenor_name',
        'link_url',
        'link_label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'is_active' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * What the public list shows: active, and either recurring or not yet
     * past. Planned before suggested, then soonest first, with recurring
     * sessions ahead of dated one-offs.
     */
    public function scopeListed($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_on')->orWhere('starts_on', '>=', today()))
            ->orderByRaw("CASE WHEN status = 'planned' THEN 0 ELSE 1 END")
            ->orderByRaw('CASE WHEN starts_on IS NULL THEN 0 ELSE 1 END')
            ->orderBy('starts_on')
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function isPast(): bool
    {
        return $this->starts_on !== null && $this->starts_on->lt(today());
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /** "Saturday 17 October · Time and meeting point to be confirmed" */
    public function whenLabel(): ?string
    {
        $parts = array_filter([
            $this->starts_on?->format('l j F'),
            $this->schedule,
        ]);

        return $parts ? implode(' · ', $parts) : null;
    }
}
