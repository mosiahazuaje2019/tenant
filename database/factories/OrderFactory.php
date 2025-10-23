<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'number'    => Str::ulid(),
            'status'    => 'pending',
            'subtotal'  => 0,
            'tax'       => 0,
            'total'     => 0,
        ];
    }
}
