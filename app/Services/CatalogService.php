<?php

namespace App\Services;

use App\Data\AdditionalServiceData;
use App\Data\CarData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CatalogService
{
    private string $catalogServiceURL;

    public function __construct(?string $catalogServiceURL = null)
    {
        $this->catalogServiceURL = trim(
            $catalogServiceURL ?? config('microservices.CatalogServiceURL'),
            '/'
        );
    }

    public function getCar(int $carId): ?Collection
    {
        $res = Http::get($this->catalogServiceURL . "/api/catalog/" . $carId);
        if (!$res->successful()) {
            return null;
        }

        $carData = CarData::from($res->collect('data'));
        $carData->price = (int)($carData->price * 100);

        return collect($carData);
    }

    public function checkCar(int $carId): bool
    {
        $res = Http::get($this->catalogServiceURL . "/api/catalog/" . $carId . "/check");
        $data = $res->collect();

        return (bool)$data['result'];
    }

    public function getAdditionalServices(?array $additionalServiceIds = null): ?Collection
    {
        $url = $this->catalogServiceURL . "/api/additional-services";
        if (is_null($additionalServiceIds)) {
            $res = Http::get($url);
        } else {
            $res = Http::get($url, ['ids' => $additionalServiceIds]);
        }
        $additionalServices = $res->collect('data');

        if ($additionalServices->isEmpty()) {
            return null;
        }

        return $additionalServices->map(function ($additionalService) {
           $additionalService['price'] = (int)($additionalService['price'] * 100);
           return AdditionalServiceData::from($additionalService);
        });
    }

    public function getAdditionalService(int $additionalServiceId): ?Collection
    {
        $res = Http::get($this->catalogServiceURL . "/api/additional-services/" . $additionalServiceId);
        if (!$res->successful()) {
            return null;
        }
        $additionalServiceData = AdditionalServiceData::from($res->collect('data'));

        return collect($additionalServiceData);
    }
}