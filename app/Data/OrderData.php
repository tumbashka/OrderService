<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public ?int $car_id,
        public ?int $payment_id,
        public ?array $services,
        public ?Carbon $start_date,
        public ?Carbon $end_date,
        public ?int $user_id,
    ) {}
}
