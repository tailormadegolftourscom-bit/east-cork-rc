<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopRsvp extends Model
{
    /**
     * Not a workshop but a request for one: someone who cannot make either
     * evening and wants a third arranged. Kept in the same table because it
     * is the same question — who wants to come, and when.
     */
    public const ALTERNATIVE = 'alternative';

    protected $table = 'workshop_rsvps';

    protected $fillable = [
        'workshop',
        'name',
        'email',
        'phone',
        'attendees',
        'note',
    ];

    /** The workshop's details from config, or null if it has been retired. */
    public function details(): ?array
    {
        return config('notice.workshops.'.$this->workshop);
    }

    public function label(): string
    {
        if ($this->isAlternative()) {
            return 'another evening';
        }

        return $this->details()['label'] ?? ucfirst($this->workshop);
    }

    public function isAlternative(): bool
    {
        return $this->workshop === self::ALTERNATIVE;
    }

    public function scopeAlternative($query)
    {
        return $query->where('workshop', self::ALTERNATIVE);
    }

    public function getPartySizeAttribute(): int
    {
        return (int) $this->attendees;
    }
}
