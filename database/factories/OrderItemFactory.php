<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $qty  = $this->faker->numberBetween(1, 5);
        $unit = $this->faker->randomFloat(2, 1, 100);
        return [
            'order_id'   => Order::factory(),
            'sku'        => strtoupper($this->faker->bothify('SKU-###')),
            'name'       => $this->faker->words(2, true),
            'quantity'   => $qty,
            'unit_price' => $unit,
            'line_total' => $qty * $unit,
        ];
    }
}
