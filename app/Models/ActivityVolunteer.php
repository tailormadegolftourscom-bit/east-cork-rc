<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Someone offering to help run an activity — a parent or a supporter. */
class ActivityVolunteer extends Model
{
    protected $table = 'activity_volunteers';

    protected $fillable = ['activity_id', 'parent_id', 'name', 'email', 'phone', 'note'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
