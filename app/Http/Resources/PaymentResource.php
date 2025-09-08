<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', description: 'Payment id', type: 'integer'),
        new OA\Property(property: 'order_id', description: 'Order id', type: 'integer'),
        new OA\Property(property: 'service_payment_id', description: 'Service payment id', type: 'string'),
        new OA\Property(
            property: 'type',
            description: 'Payment type',
            properties: [
                new OA\Property(property: 'value', description: 'Payment type id', type: 'integer'),
                new OA\Property(property: 'name', description: 'Payment type name', type: 'string'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: 'status',
            description: 'Payment status',
            properties: [
                new OA\Property(property: 'value', description: 'Payment status id', type: 'integer'),
                new OA\Property(property: 'name', description: 'Payment status name', type: 'string'),
            ],
            type: 'object'
        ),
        new OA\Property(property: 'amount', description: 'Amount', type: 'integer'),
        new OA\Property(property: 'url', description: 'Payment url', type: 'string'),
    ],
)]
class PaymentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Payment $this */
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'service_payment_id' => $this->service_payment_id,
            'type' => [
                'value' => $this->type->value,
                'name' => $this->type->name,
            ],
            'status' => [
                'value' => $this->status->value,
                'name' => $this->status->name,
            ],
            'amount' => $this->amount,
            'url' => $this->url,
        ];
    }
}
