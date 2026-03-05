<?php

namespace Database\Factories\Warranties;

use App\Enums\Warranties\WarrantyRequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warranties\WarrantyRequest>
 */
class WarrantyRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'factory_id' => null,
            'factory_sequence' => null,

            'shipping_city' => fake()->city(),
            'shipping_address' => fake()->address(),

            'damage_date' => fake()->date(),
            'purchase_date' => fake()->date(),

            'invoice_number' => fake()->numerify('INV###'),
            'internal_code' => fake()->bothify('INT-###??'),

            'model' => fake()->word(),
            'quantity' => fake()->numberBetween(1, 5),

            'status' => WarrantyRequestStatus::Pending,

            'failure_description' => fake()->sentence(),
        ];
    }
}
