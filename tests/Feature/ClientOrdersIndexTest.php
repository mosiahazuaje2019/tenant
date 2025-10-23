<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientOrdersIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_forbids_listing_orders_of_another_tenant(): void
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();

        $userA = User::factory()->create(['client_id' => $clientA->id]);

        Sanctum::actingAs($userA);

        $this->getJson('/api/clients/'.$clientB->id.'/orders')
            ->assertStatus(403);
    }

    public function test_lists_orders_of_same_tenant_with_pagination(): void
    {
        $client = Client::factory()->create();
        $user   = User::factory()->create(['client_id' => $client->id]);

        Order::factory()->count(3)->create(['client_id' => $client->id]);

        Sanctum::actingAs($user);

        $this->getJson('/api/clients/'.$client->id.'/orders?per_page=2')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(2, 'data');
    }
}
