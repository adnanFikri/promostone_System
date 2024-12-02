<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = \App\Models\Client::class;

    public function definition()
    {
        return [
            'code_client' => $this->faker->unique()->regexify('D[0-9]{3}'),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'type' => $this->faker->randomElement(['Particulier', 'Fiche client', 'Anomalie']),
        ];
    }
}
