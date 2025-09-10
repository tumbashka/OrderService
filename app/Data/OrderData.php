<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public ?UserData $user,
        public ?CarData $car,
        public ?int $payment_id,
        #[DataCollectionOf(AdditionalServiceData::class)]
        public ?Collection $services,
        public ?Carbon $start_date,
        public ?Carbon $end_date,
    ) {
    }
}
