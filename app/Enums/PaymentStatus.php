<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Pending = 1;
    case WaitingForCapture = 2;
    case Succeeded = 3;
    case Canceled = 4;

    public static function fromYookassaStatus(string $status): ?self
    {
        return match ($status) {
            'pending' => self::Pending,
            'waiting_for_capture' => self::WaitingForCapture,
            'succeeded' => self::Succeeded,
            'canceled' => self::Canceled,
            default => null,
        };
    }
}
