<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class UserService
{
    private string $userServiceURL;

    public function __construct(?string $userServiceURL = null)
    {
        $this->userServiceURL = trim(
            $userServiceURL ?? config('microservices.UserServiceURL'),
            '/'
        );
    }

    public function getUser(int $userId): ?Collection
    {
        $res = Http::get($this->userServiceURL . "/api/users/" . $userId);
        $userData = $res->collect();

        if ($userData->isEmpty()) {
            return null;
        }

        return collect($userData['data']);
    }

    public function checkUser(int $userId): bool
    {
        $res = Http::get($this->userServiceURL . "/api/users/" . $userId . "/check");
        $data = $res->collect();

        return (bool)$data['result'];
    }
}