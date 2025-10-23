<?php

namespace Tests\Feature;

use App\Jobs\GenerateInvoiceJob;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateInvoiceJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_creates_invoice_and_updates_order_status(): void
    {
        $client = Client::factory()->create();
        $order  = Order::factory()->create([
            'client_id' => $client->id,
            'status'    => 'pending',
            'subtotal'  => 20,
            'tax'       => 3.8,
            'total'     => 23.8,
        ]);

        OrderItem::factory()->create([
            'order_id'   => $order->id,
            'sku'        => 'BX-01',
            'name'       => 'Box',
            'quantity'   => 2,
            'unit_price' => 10,
            'line_total' => 20,
        ]);

        // Ejecuta el job directamente
        (new GenerateInvoiceJob($order->id))->handle();

        $order->refresh();

        $this->assertSame('processing', $order->status);
        $this->assertTrue(Invoice::where('order_id', $order->id)->exists());
    }
}
