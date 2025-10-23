<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'client_id' => $this->client_id,
            'number'    => $this->number,
            'status'    => $this->status,
            'subtotal'  => (float) $this->subtotal,
            'tax'       => (float) $this->tax,
            'total'     => (float) $this->total,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,

            // Solo si están cargados:
            'items'     => OrderItemResource::collection($this->whenLoaded('items')),
            'invoice'   => $this->whenLoaded('invoice', function () {
                return [
                    'id'      => $this->invoice->id,
                    'number'  => $this->invoice->number,
                    'payload' => $this->invoice->payload,
                ];
            }),
        ];
    }
}
