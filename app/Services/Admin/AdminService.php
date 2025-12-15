<?php

namespace App\Services\Admin;

use App\Services\Service;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class AdminService
 * @package App\Services
 */
class AdminService extends Service
{
    protected function guard(): Guard|StatefulGuard
    {
        return Auth::guard('admin');
    }

    protected function user(): ?Authenticatable
    {
        return $this->guard()->user();
    }
}
