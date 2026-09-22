<?php

namespace App\Actions\Fortify;

use App\Models\Parents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): Parents
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:parents,email'],
            'password' => $this->passwordRules(),
        ])->validate();

        $first = trim($input['first_name']);
        $last = trim($input['last_name']);

        // Self-registration picks the password here, so the account is
        // complete the moment it exists — unlike a co-parent invite, which
        // only completes when the recipient sets one of their own.
        return Parents::create([
            'first_name' => $first,
            'last_name' => $last,
            'name' => $first.' '.$last,
            'email' => trim($input['email']),
            'phone' => null,
            'public_name_mode' => 'real_name',
            'password' => Hash::make($input['password']),
            'is_admin' => 0,
            'user_type' => 'parent',
            'school_id' => null,
            'registration_completed_at' => now(),
        ]);
    }
}
