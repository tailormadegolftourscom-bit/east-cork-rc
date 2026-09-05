<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    public function create(User $user): bool
    {
        return $user->user_type === 'parent' && $user->person_id !== null;
    }

    public function view(User $user, Child $child): bool
    {
        return $this->hasAccess($user, $child);
    }

    public function update(User $user, Child $child): bool
    {
        return $this->hasAccess($user, $child);
    }

    private function hasAccess(User $user, Child $child): bool
    {
        if ($user->person_id === null) {
            return false;
        }

        if ($user->person_id === $child->parent_person_id) {
            return true;
        }

        return $child->guardians()->where('people.id', $user->person_id)->exists();
    }
}
