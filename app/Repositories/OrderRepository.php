<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function createWithItems(int $clientId, array $items, ?float $taxRate = null): Order
    {
        return DB::transaction(function () use ($clientId, $items, $taxRate) {
            $subtotal = collect($items)->sum(fn ($i) => (float) $i['unit_price'] * (int) $i['quantity']);
            $tax      = $taxRate ? round($subtotal * $taxRate, 2) : 0.0;
            $total    = round($subtotal + $tax, 2);

            $order = Order::create([
                'client_id' => $clientId,
                'number'    => Str::ulid(),
                'status'    => 'pending',
                'subtotal'  => $subtotal,
                'tax'       => $tax,
                'total'     => $total,
            ]);

            $rows = collect($items)->map(fn ($i) => [
                'order_id'   => $order->id,
                'sku'        => $i['sku'],
                'name'       => $i['name'],
                'quantity'   => (int) $i['quantity'],
                'unit_price' => (float) $i['unit_price'],
                'line_total' => (int) $i['quantity'] * (float) $i['unit_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ])->all();

            OrderItem::insert($rows);

            return $order->load('items');
        });
    }

    public function forClient(int $clientId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::with('items')
            ->where('client_id', $clientId)
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForClient(int $clientId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::with('items')
            ->where('client_id', $clientId)
            ->latest('id')
            ->paginate($perPage);
    }

    public function findForClientOrFail(int $clientId, int $orderId): Order
    {
        return Order::with('items')
            ->where('client_id', $clientId)
            ->findOrFail($orderId);
    }
}
