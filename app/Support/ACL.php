<?php

namespace App\Support;


use Illuminate\Support\Facades\Route;

class ACL
{
    const SUPER_ADMIN_ROLE = 'super admin';
    const GUARD = 'web';
    const IGNORE_NAMES = [
        'home',
        'login',
        'logout'
    ];

    const IGNORE_ROUTE_PATTERNS = [
        '/^horizon/',
        '/^_debugbar/',
        '/^_ignition/',
        '/^orders_/'
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allPermissions()
    {
        return collect(Route::getRoutes()->getRoutes())
            ->filter([$this, 'isNotIgnored']);
    }

    public function isIgnored(\Illuminate\Routing\Route $route): bool
    {

        if (in_array($route->getName(), self::IGNORE_NAMES)) {
            return true;
        }

        foreach (self::IGNORE_ROUTE_PATTERNS as $pattern) {
            if (preg_match($pattern, $route->uri())) {
                return true;
            }
        }

        return false;

    }

    public function isNotIgnored(\Illuminate\Routing\Route $route)
    {
        return !$this->isIgnored($route);
    }

    public function isEnable()
    {
        return config('permission.enable');
    }
}
