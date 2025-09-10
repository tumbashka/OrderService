<?php

namespace App\Rules;

use App\Services\UserService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistedUser implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userService = app(UserService::class);
        $userExist = $userService->checkUser($value);

        if (!$userExist) {
            $fail("User with id:{$value} not found.");
        }
    }
}
