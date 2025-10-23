<?php

namespace App\Services;

use App\Jobs\GenerateInvoiceJob;
use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        private OrderRepository $orders
    ) {}

    public function create(int $clientId, array $payload): Order
    {
        $items   = $payload['items'] ?? [];
        $taxRate = $payload['tax_rate'] ?? null;

        $order = $this->orders->createWithItems($clientId, $items, $taxRate);

        GenerateInvoiceJob::dispatch($order->id);

        return $order->loadMissing('items');
    }

    public function setStatus(Order $order, string $status): Order
    {
        $allowed = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $order->update(['status' => $status]);
        return $order;
    }
}
