<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $firstName = $this->faker->firstName();
        return [
            'user_id' => function() {
                $user = \App\Models\User::factory()->create();
                $user->role = '2';
                $user->save();
                return $user->id;
            },
            'application_id' => 0,
            'state_id' => $this->faker->randomElement([1, 2, 3]),
            'first_name' => $firstName,
            'last_name' => $this->faker->lastName(),
            'preferred_first_name' => $firstName,
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'suburb' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'lat' => $this->faker->latitude(),
            'lon' => $this->faker->longitude(),
            'abn' => Str::random(10),
            'wwcc_application_number' => Str::random(10),
            'availabilities' => ["mo-10:30 AM","tu-2:30 PM","we-2:30 PM","th-12:00 PM","th-3:50 PM","fr-3:50 PM","sa-2:30 PM"],
            'referral_key' => Str::random(6),
        ];
    }
}
