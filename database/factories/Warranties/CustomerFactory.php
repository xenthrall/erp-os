<?php

namespace Database\Factories\Warranties;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warranties\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'     => $this->faker->firstName(),
            'last_name'      => $this->faker->lastName(),
            'document_type'  => 'CC',
            'document_number' => $this->faker->unique()->numerify('###########'), // 11 digits
            'phone'          => $this->faker->optional(0.9)->numerify('3#########'), // celular Colombia-ish
            'address'        => $this->faker->optional()->address(),
            'is_active'      => $this->faker->boolean(95), // la mayoría activos
        ];
    }
}
