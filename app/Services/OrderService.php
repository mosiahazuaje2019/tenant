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

    /**
     * Crea una orden para el cliente dado y dispara la generación
     * de factura de forma asíncrona.
     *
     * Estructura esperada en $payload:
     * [
     *   'items'    => [
     *      ['sku' => '...', 'name' => '...', 'quantity' => 1, 'unit_price' => 10.5],
     *      ...
     *   ],
     *   'tax_rate' => 0.19 // opcional
     * ]
     */
    public function create(int $clientId, array $payload): Order
    {
        $items   = $payload['items'] ?? [];
        $taxRate = $payload['tax_rate'] ?? null;

        // Persistir orden + ítems (transacción ocurre en el repositorio)
        $order = $this->orders->createWithItems($clientId, $items, $taxRate);

        // Disparar proceso asíncrono de factura
        GenerateInvoiceJob::dispatch($order->id);

        return $order->loadMissing('items');
    }

    /**
     * (Opcional) Cambia el estado de la orden con reglas simples.
     */
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
