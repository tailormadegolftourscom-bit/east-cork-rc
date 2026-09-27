<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** An idea for an activity; shown publicly only once an admin approves it. */
class ActivitySuggestion extends Model
{
    protected $table = 'activity_suggestions';

    protected $fillable = ['activity_id', 'parent_id', 'body', 'status', 'approved_at'];

    protected $casts = ['approved_at' => 'datetime'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
