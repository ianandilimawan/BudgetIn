<?php

namespace Database\Factories;

use App\Models\CashAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashAccountFactory extends Factory
{
    protected $model = CashAccount::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['cash', 'bank', 'ewallet', 'other']);

        return [
            'name' => $this->faker->words(2, true),
            'type' => $type,
            'account_number' => $this->faker->bankAccountNumber(),
            'icon' => null,
            'color' => $this->faker->randomElement(['emerald', 'blue', 'purple', 'amber']),
            'initial_balance' => $this->faker->randomFloat(2, 0, 1000000),
            'is_active' => true,
        ];
    }
}
