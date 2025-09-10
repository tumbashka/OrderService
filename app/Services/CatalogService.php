<?php

namespace App\Services;

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
        $carData = $res->collect('data');

        if (!$res->successful() && $carData->isEmpty()) {
            return null;
        }
        $carData['price'] = (int)($carData['price'] * 100);

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
        if (is_null($additionalServiceIds)) {
            $res = Http::get($this->catalogServiceURL . "/api/additional-services");
            $additionalServices = $res->collect('data');
        } else {
            $additionalServices = collect();

            foreach ($additionalServiceIds as $additionalServiceId) {
                $additionalService = $this->getAdditionalService($additionalServiceId);
                $additionalServices->push($additionalService);
            }
        }

        if ($additionalServices->isEmpty()) {
            return null;
        }

        $additionalServices =  $additionalServices->map(function ($additionalService) {
           $additionalService['price'] = (int)($additionalService['price'] * 100);
           return $additionalService;
        });

        return ($additionalServices);
    }

    public function getAdditionalService(int $additionalServiceId): ?Collection
    {
        $res = Http::get($this->catalogServiceURL . "/api/additional-services/" . $additionalServiceId);

        $additionalServiceData = $res->collect('data');

        if (!$res->successful() && $additionalServiceData->isEmpty()) {
            return null;
        }

        return collect($additionalServiceData);
    }
}