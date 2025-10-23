<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(): void
    {
        $order = Order::with('items', 'client')->findOrFail($this->orderId);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'number'   => 'INV-' . Str::upper(Str::ulid()),
            'payload'  => [
                'message'      => 'Invoice created successfully',
                'order_number' => $order->number,
                'client'       => $order->client->only(['id', 'name', 'email']),
                'items'        => $order->items->map(fn($i) => $i->only(['sku', 'name', 'quantity', 'unit_price', 'line_total'])),
                'totals'       => $order->only(['subtotal', 'tax', 'total']),
            ],
        ]);

        $order->update(['status' => 'processing']);

        Log::info('Invoice generated successfully', [
            'order_id'       => $order->id,
            'order_number'   => $order->number,
            'invoice_id'     => $invoice->id,
            'invoice_number' => $invoice->number,
        ]);
    }
}
