<?php

namespace Tests\Feature;

use App\Jobs\GenerateInvoiceJob;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_requires_auth_to_create_orders(): void
    {
        $this->postJson('/api/orders', [])->assertStatus(401);
    }

    public function test_validates_payload_when_creating_an_order(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/orders', ['items' => []])
            ->assertStatus(422);
    }

    public function test_creates_order_with_items_and_dispatches_invoice_job(): void
    {
        Bus::fake();

        $client = Client::factory()->create();
        $user = User::factory()->create([
            'client_id' => $client->id,
            'password'  => Hash::make('secret123'),
        ]);

        Sanctum::actingAs($user);

        $payload = [
            'items' => [
                ['sku' => 'BX-01', 'name' => 'Box',   'quantity' => 2, 'unit_price' => 10.50],
                ['sku' => 'LB-01', 'name' => 'Label', 'quantity' => 1, 'unit_price' => 2.00],
            ],
            'tax_rate' => 0.19,
        ];

        $res = $this->postJson('/api/orders', $payload)
            ->assertCreated()
            ->assertJsonPath('items.0.sku', 'BX-01');

        Bus::assertDispatched(GenerateInvoiceJob::class);

        $json = $res->json();
        $this->assertSame(23.00, (float) $json['subtotal']);
        $this->assertSame(4.37,  (float) $json['tax']);   // 23 * 0.19
        $this->assertSame(27.37, (float) $json['total']);
    }
}
