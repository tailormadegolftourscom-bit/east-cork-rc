<?php

namespace App\Actions\Fortify;

use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $person = Person::create([
                'first_name' => trim($input['first_name']),
                'last_name' => trim($input['last_name']),
                'email' => trim($input['email']),
                'phone' => null,
                'public_name_mode' => 'real_name',
            ]);

            return User::create([
                'person_id' => $person->id,
                'name' => trim($input['first_name'].' '.$input['last_name']),
                'email' => trim($input['email']),
                'password' => Hash::make($input['password']),
                'is_admin' => 0,
                'user_type' => 'parent',
                'school_id' => null,
            ]);
        });
    }
}
