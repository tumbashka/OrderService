<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AdditionalServiceData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?int $price,
    ) {
    }
}
