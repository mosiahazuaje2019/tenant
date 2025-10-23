<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_order_for_same_tenant(): void
    {
        $client = Client::factory()->create();
        $user   = User::factory()->create(['client_id' => $client->id]);
        $order  = Order::factory()->create(['client_id' => $client->id]);

        Sanctum::actingAs($user);

        $this->getJson('/api/orders/'.$order->id)
            ->assertOk()
            ->assertJsonPath('id', $order->id);
    }

    public function test_returns_404_for_other_tenant_order(): void
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();

        $userA = User::factory()->create(['client_id' => $clientA->id]);
        $orderB = Order::factory()->create(['client_id' => $clientB->id]);

        Sanctum::actingAs($userA);

        $this->getJson('/api/orders/'.$orderB->id)
            ->assertStatus(404);
    }
}
