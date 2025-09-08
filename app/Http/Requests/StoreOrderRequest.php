<?php

namespace App\Http\Requests;

use App\Enums\PaymentType;
use App\Models\AdditionalService;
use App\Services\CatalogService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'payment_id', description: 'Payment type id', type: 'integer'),
        new OA\Property(
            property: 'services',
            description: 'Additional services array',
            type: 'array',
            items: new OA\Items(type: 'integer', example: 3),
            example: [1, 2, 3]
        ),
        new OA\Property(property: 'start_date', description: 'Start date', type: 'string'),
        new OA\Property(property: 'end_date', description: 'End date', type: 'string'),
    ]
)]
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(CatalogService $catalogService): array
    {
        $additionals = $catalogService->getAdditionalServices();
        dd($additionals->pluck('id'));
        return [
            'payment_id' => ['required', 'integer', Rule::enum(PaymentType::class)],
            'services' => ['required', 'array'],
            'services.*' => ['required', 'integer', Rule::exists(AdditionalService::class, 'id')],
            'start_date' => ['required', 'date', Rule::date()->afterOrEqual(now())],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }
}
