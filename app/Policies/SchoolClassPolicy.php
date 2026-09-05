<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    private function isAdmin(User $user): bool
    {
        return (int) $user->is_admin === 1 || $user->user_type === 'admin';
    }

    public function update(User $user, SchoolClass $schoolClass): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->user_type === 'school' && $user->school_id === $schoolClass->school_id;
    }

    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        // School users are never permitted to delete class rows, regardless of ownership.
        return $this->isAdmin($user);
    }
}
