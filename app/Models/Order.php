<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'rent_start',
        'rent_end',
        'status',
    ];

    protected $casts = [
        'rent_start' => 'datetime',
        'rent_end' => 'datetime',
        'status' => OrderStatus::class,
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function paymentAmount(): Attribute
    {
        return new Attribute(
            get: function ($value, array $attributes) {
                return $this->products()
                    ->sum('price');
            }
        );
    }

    protected function paymentCount(): Attribute
    {
        return new Attribute(
            get: function ($value, array $attributes) {
                $carsCount = $this->cars()
                    ->count();

                $addServicesCount = $this->additionalServices()
                    ->count();

                return $carsCount + $addServicesCount;
            }
        );
    }
}
