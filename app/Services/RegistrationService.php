<?php

namespace App\Services;

use App\Models\User;
use App\Models\Link;
use Illuminate\Support\Str;

class RegistrationService
{
    public function register(string $username, string $phoneNumber): Link
    {
        $user = User::firstOrCreate(
            ['username' => $username],
            ['phone_number' => $phoneNumber]
        );

        return Link::create([
            'user_id' => $user->id,
            'token' => Str::random(32),
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);
    }
}