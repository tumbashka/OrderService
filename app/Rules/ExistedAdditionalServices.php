<?php

namespace App\Rules;

use App\Services\CatalogService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistedAdditionalServices implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $catalogService = app(CatalogService::class);
        $additionalServices = $catalogService->getAdditionalServices();
        $servicesIds = $additionalServices->pluck('id');

        if (!$servicesIds->contains($value)) {
            $fail("Additional service with id:{$value} not found.");
        }
    }
}
