<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientOrdersController extends Controller
{
     use AuthorizesRequests;

    public function __construct(
        private OrderService $service,
        private \App\Repositories\Contracts\OrderRepositoryInterface $orders
    ) {}

    /**
     * GET /api/clients/{id}/orders
     * List all clients orders
     */
    public function index(Request $request, int $id)
    {
        // policy authorization
        $this->authorize('viewOrders', $id);

        // orquestation
        $perPage   = (int) $request->integer('per_page', 15);
        $paginator = $this->orders->paginateForClient($id, $perPage);

        // return data with resource collection
        return new OrderCollection($paginator);
    }
}
