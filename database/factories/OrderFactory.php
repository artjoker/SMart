<?php

namespace Database\Factories;

use App\Entity\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => Arr::random([
                OrderStatus::CREATED,
                OrderStatus::APPROVED,
                OrderStatus::BILLED,
                OrderStatus::PAYED,
                OrderStatus::REJECTED,
            ]),
        ];
    }
}
