<?php

namespace App\Http\Requests;

use App\Enums\PaymentType;
use App\Rules\ExistedAdditionalServices;
use App\Rules\ExistedCar;
use App\Rules\ExistedUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'car_id', description: 'Car id', type: 'integer'),
        new OA\Property(property: 'user_id', description: 'User id', type: 'integer'),
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
    protected $stopOnFirstFailure = true;
    public function rules(): array
    {
        return [
            'car_id' => ['required', 'integer', new ExistedCar()],
            'user_id' => ['required', 'integer', new ExistedUser()],
            'payment_id' => ['required', 'integer', Rule::enum(PaymentType::class)],
            'services' => ['required', 'array'],
            'services.*' => ['required', 'integer', new ExistedAdditionalServices()],
            'start_date' => ['required', 'date', Rule::date()->afterOrEqual(now())],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }
}
