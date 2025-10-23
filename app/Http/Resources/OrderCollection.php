<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }

    public function with($request): array
    {
        return $this->collection->isEmpty()
            ? ['message' => 'No orders found for this client.']
            : [];
    }
}
