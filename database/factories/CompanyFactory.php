<?php

namespace Database\Factories;
use App\Models\Company;
use App\Models\User;
use App\Models\Country;
use App\Models\Service;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Company::class;


    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'country_id' => Country::factory(),
            'service_id' => Service::factory(),
            'name' => $this->faker->unique()->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->unique()->e164PhoneNumber,
            'description' => $this->faker->text
        ];
    }
}
