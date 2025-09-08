<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', description: 'Order ID', type: 'integer', example: 1),
        new OA\Property(property: 'start_date', description: 'Rent start date', type: 'string'),
        new OA\Property(property: 'end_date', description: 'Rent end date', type: 'string'),
        new OA\Property(
            property: 'status',
            description: 'Orders status',
            properties: [
                new OA\Property(property: 'id', description: 'Status ID', type: 'integer', example: 1),
                new OA\Property(property: 'name', description: 'Status name', type: 'string', example: 'NeedPayment'),
            ],
            type: 'object'
        ),
    ],
)]
class OrderResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date ' => $this->rent_start,
            'end_date ' => $this->rent_end,
            'status' => [
                'id' => $this->status->value,
                'name' => $this->status->name,
            ],
        ];
    }
}
