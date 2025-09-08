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
        $carData = $res->collect();

        if ($carData->isEmpty()) {
            return null;
        }

        return $carData;
    }

    public function getAdditionalServices(): ?Collection
    {
        $res = Http::get($this->catalogServiceURL . "/api/additional-services");

        return $res->collect();
    }
}