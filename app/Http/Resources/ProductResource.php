<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'name', description: 'Product name', type: 'string'),
        new OA\Property(property: 'price', description: 'Product price', type: 'integer'),
    ],
)]
class ProductResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
