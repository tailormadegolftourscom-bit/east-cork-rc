<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\Parents;

class ChildPolicy
{
    public function create(Parents $user): bool
    {
        return $user->user_type === 'parent';
    }

    public function view(Parents $user, Child $child): bool
    {
        return $this->hasAccess($user, $child);
    }

    public function update(Parents $user, Child $child): bool
    {
        return $this->hasAccess($user, $child);
    }

    public function delete(Parents $user, Child $child): bool
    {
        // Deleting is more permanent than editing — restricted to the
        // primary registering parent, not co-parent guardians, so one
        // parent can't unilaterally remove a child the other added.
        return (int) $user->id === (int) $child->parent_id;
    }

    private function hasAccess(Parents $user, Child $child): bool
    {
        if ((int) $user->id === (int) $child->parent_id) {
            return true;
        }

        return $child->guardians()->where('parents.id', $user->id)->exists();
    }
}
