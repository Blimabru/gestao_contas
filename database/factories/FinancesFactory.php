<?php

namespace Database\Factories;

use App\Models\Finances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancesFactory extends Factory
{
    protected $model = Finances::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(3),
            'value' => $this->faker->randomFloat(2, 50, 5000),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['Pendente', 'Pago', 'Atrasado']),
            'type' => $this->faker->randomElement(['Pagar', 'Receber']),
            'user_id' => User::factory(), // Associe um usuário da factory
        ];
    }
}
