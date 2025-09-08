<?php

namespace App\Interfaces;

use App\Data\OrderPaymentData;

interface PaymentInterface
{
    public function createPayment(OrderPaymentData $paymentDTO);
}
