<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(protected User $user)
    {
    }

    public function test(): void
    {
        Log::info($this->user);
    }
}
