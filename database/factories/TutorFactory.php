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
        $tutor_name = $this->faker->name();
        $firstName = explode(' ', $tutor_name)[0];
        return [
            'user_id' => function() {
                $user = \App\Models\User::factory()->create();
                $user->role = '2';
                $user->save();
                return $user->id;
            },
            'application_id' => 0,
            'tutor_state' => $this->faker->randomElement(['NSW', 'VIC', 'QLD']),
            'tutor_name' => $tutor_name,
            'preferred_first_name' => $firstName,
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'tutor_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'suburb' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'lat' => $this->faker->latitude(),
            'lon' => $this->faker->longitude(),
            'abn' => Str::random(10),
            'wwcc_application_number' => Str::random(10),
            // 'availabilities' => '',
            'referral_key' => Str::random(6),
        ];
    }
}
