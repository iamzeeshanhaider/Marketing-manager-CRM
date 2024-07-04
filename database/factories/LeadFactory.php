<?php

namespace Database\Factories;

use App\Enums\EmailStatus;
use App\Enums\GeneralStatus;
use App\Enums\LeadSource;
use App\Enums\LeadType;
use App\Models\Company;
use App\Models\LeadStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'tel' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'email_status' => fake()->randomElement(EmailStatus::getInstances()),

            'address' => fake()->address(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'postcode' => fake()->postcode(),
            'country' => fake()->country(),
            'lead_type' => fake()->randomElement(LeadType::getInstances()),

            'data_array' => json_encode([
                'address' => fake()->address(),
                'state' => fake()->state(),
                'city' => fake()->city(),
                'postcode' => fake()->postcode(),
                'country' => fake()->country(),

                'sentence' => fake()->sentence(),
                'paragraph' => fake()->paragraph(),
            ]),

            'company_id' => Company::inRandomOrder()->first()->id,
            'source' => fake()->randomElement(LeadSource::getInstances()),
            'status' => LeadStatus::inRandomOrder()->first()->id,
        ];
    }
}
