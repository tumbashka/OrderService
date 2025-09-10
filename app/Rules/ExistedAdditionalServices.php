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
        $additionalService = $catalogService->getAdditionalService((int)$value);

        if (!$additionalService) {
            $fail("Additional service with id:{$value} not found.");
        }
    }
}
