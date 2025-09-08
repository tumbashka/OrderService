<?php

namespace App\Enums;

enum PaymentType: int
{
    case Yookassa = 1;
    case Robokassa = 2;
}
