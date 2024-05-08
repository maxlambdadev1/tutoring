<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => function() {
                $user = \App\Models\User::factory()->create();
                $user->role = '1';
                $user->save();
                return $user->id;
            },
            'admin_name' => $this->faker->name(),
            'admin_role_id' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
