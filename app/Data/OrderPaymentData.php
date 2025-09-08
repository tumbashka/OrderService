<?php

namespace App\Data;

use App\Models\Order;
use Spatie\LaravelData\Data;

class OrderPaymentData extends Data
{
    public function __construct(
        public ?Order $order,
        public ?int $amount,
        public ?int $paymentId = null,
    ) {}
}
