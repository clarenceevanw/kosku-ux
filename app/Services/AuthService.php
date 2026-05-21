<?php

namespace App\Services;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialiteUser;

/**
 * AuthService — all authentication business logic lives here.
 *
 * Controllers inject this service and delegate ALL logic to it.
 * Zero Auth/DB calls are allowed in the Controller itself.
 */
class AuthService
{
    /**
     * Register a new user from validated form data.
     * The password cast in the User model handles hashing automatically,
     * but we hash explicitly here for clarity and safety.
     *
     * @param  array{name: string, email: string, phone_number: string, password: string, role: string}  $data
     */
    public function registerUser(array $data): User
    {
        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'password'     => Hash::make($data['password']),
            'role'         => $data['role'],
            'is_verified'  => false,
        ]);

        Auth::login($user);

        return $user;
    }

    /**
     * Attempt to authenticate a user with email + password.
     * Returns true on success, false on failure.
     *
     * @param  array{email: string, password: string}  $credentials
     */
    public function loginUser(array $credentials): bool
    {
        return Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $credentials['remember'] ?? false);
    }

    /**
     * Handle the OAuth callback from Google.
     *
     * Flow:
     *   1. Look up user by email.
     *   2. If found  → log them in directly.
     *   3. If not found → throw exception (do not auto-register).
     *
     * Google users are marked as verified (email is verified by Google).
     */
    public function handleGoogleCallback(SocialiteUser $googleUser): User
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            throw new \Exception('Akun dengan email ini belum terdaftar. Silakan daftar terlebih dahulu.');
        }

        // If the user hasn't verified their email but logs in via Google, mark them as verified.
        if (! $user->is_verified || is_null($user->email_verified_at)) {
            $user->update([
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);

        return $user;
    }
}
