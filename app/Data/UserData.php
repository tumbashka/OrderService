<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public ?string $phone,
        public ?RoleData $role,
    ) {
    }
}
