<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $service,
        private OrderRepository $orders
    ) {
    }

    /**
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $clientId = (int) $request->user()->client_id;
        $perPage  = (int) ($request->get('per_page', 10));

        $orders = $this->orders->forClient($clientId, $perPage);

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No orders found for this client.',
                'data' => [],
            ], 200);
        }

        return OrderResource::collection($orders);
    }

    /**
     * POST /api/orders
     */
    public function store(StoreOrderRequest $request)
    {
        $clientId = (int) $request->user()->client_id;
        $order = $this->service->create($clientId, $request->validated());

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/orders/{id}
     */
    public function show(Request $request, int $id)
    {
        $clientId = (int) $request->user()->client_id;

        try {
            $order = $this->orders->findForClientOrFail($clientId, $id);
            return new OrderResource($order);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order not found for this client.',
                'error' => 'ORDER_NOT_FOUND'
            ], 404);
        }
    }
}
