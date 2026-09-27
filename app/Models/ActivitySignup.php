<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A registered child signed up for an activity by one of their parents. */
class ActivitySignup extends Model
{
    protected $table = 'activity_signups';

    protected $fillable = ['activity_id', 'child_id', 'parent_id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
