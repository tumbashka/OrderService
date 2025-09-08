<?php

namespace App\Enums;

enum OrderStatus: int
{
    case NeedPayment = 1;
    case WaitToReceive = 2;
    case Active = 3;
    case Canceled = 4;
    case Completed = 5;
}
