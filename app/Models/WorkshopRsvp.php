<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopRsvp extends Model
{
    protected $table = 'workshop_rsvps';

    protected $fillable = [
        'workshop',
        'name',
        'email',
        'phone',
        'adults',
        'children',
        'note',
    ];

    /** The workshop's details from config, or null if it has been retired. */
    public function details(): ?array
    {
        return config('notice.workshops.'.$this->workshop);
    }

    public function label(): string
    {
        return $this->details()['label'] ?? ucfirst($this->workshop);
    }

    public function getPartySizeAttribute(): int
    {
        return (int) $this->adults + (int) $this->children;
    }
}
