<?php

namespace Database\Factories;

use App\Enum\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->name(),
            'email'        => $this->faker->unique()->safeEmail(),
            'password'     => static::$password ??= Hash::make('password'),
            'phone_number' => $this->faker->numerify('08##########'),
            'role'         => UserRole::TENANT->value,
            'is_verified'  => true,
        ];
    }

    /** State: Owner role */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::OWNER->value,
        ]);
    }

    /** State: Tenant role */
    public function tenant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::TENANT->value,
        ]);
    }
}
