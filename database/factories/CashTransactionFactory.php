<?php

namespace Database\Factories;

use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashTransactionFactory extends Factory
{
    protected $model = CashTransaction::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['income', 'expense']);

        return [
            'category_id' => TransactionCategory::factory()->create(['type' => $type])->id,
            'type' => $type,
            'amount' => $this->faker->randomFloat(2, 10000, 5000000),
            'transaction_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'note' => $this->faker->sentence(3),
        ];
    }
}
