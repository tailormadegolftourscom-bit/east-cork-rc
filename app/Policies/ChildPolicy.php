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
        return $user->person_id !== null && $user->person_id === $child->parent_person_id;
    }

    public function update(User $user, Child $child): bool
    {
        return $user->person_id !== null && $user->person_id === $child->parent_person_id;
    }
}
