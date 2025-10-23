<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order;


interface OrderRepositoryInterface
{
    public function createWithItems(int $clientId, array $items, ?float $taxRate = null): Order;

    public function forClient(int $clientId, int $perPage = 10): LengthAwarePaginator;

    public function paginateForClient(int $clientId, int $perPage = 15): LengthAwarePaginator;

    public function findForClientOrFail(int $clientId, int $orderId): Order;
}
