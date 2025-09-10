<?php

namespace App\Rules;

use App\Services\CatalogService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistedCar implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $catalogService = app(CatalogService::class);
        $carExist = $catalogService->checkCar($value);

        if (!$carExist) {
            $fail("Car with id:{$value} not found.");
        }
    }
}
