<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'service_payment_id',
        'type',
        'status',
        'amount',
        'url',
    ];

    protected $casts = [
        'type' => PaymentType::class,
        'status' => PaymentStatus::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => round($value / 100, 2),
            set: fn ($value) => $value * 100,
        );
    }
}
