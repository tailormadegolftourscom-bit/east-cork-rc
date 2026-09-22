<?php

namespace App\Actions\Fortify;

use App\Models\Parents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function reset(Parents $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
            // Choosing a password of their own is what finishes a
            // registration. Co-parent invites arrive already email-verified,
            // so this is the only reliable signal that someone actually
            // turned up — and it is what keeps the 3/6/9 sweep from
            // removing them.
            'registration_completed_at' => $user->registration_completed_at ?? now(),
        ])->save();
    }
}
