<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    public function cars(): MorphToMany
    {
        return $this->morphedByMany(Car::class, 'product', 'order_product')
            ->withPivot(['price', 'name']);
    }

    public function additionalServices(): MorphToMany
    {
        return $this->morphedByMany(AdditionalService::class, 'product', 'order_product')
            ->withPivot(['price', 'name']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function paymentAmount(): Attribute
    {
        return new Attribute(
            get: function ($value, array $attributes) {
                $carsPrice = $this->cars()
                    ->sum('cars.price');

                $addServicesPrice = $this->additionalServices()
                    ->sum('additional_services.price');

                return $carsPrice + $addServicesPrice;
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
