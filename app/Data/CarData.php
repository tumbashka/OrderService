<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CarData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?int $year,
        public ?int $price,
        public ?string $slug,
        public ?string $imageURL,
        public ?array $model,
        public ?array $mark,
        public ?array $specs,
    ) {
    }
}
