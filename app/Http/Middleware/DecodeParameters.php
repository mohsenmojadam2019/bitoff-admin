<?php

namespace App\Http\Middleware;

use App\Support\Hash\HashId;
use Closure;
use Exception;

class DecodeParameters
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param array $params
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        foreach ($request->all() as $key => $value) {

            if (!in_array($key, $params)) {
                continue;
            }

            try {
                $decoded = HashId::decode($value)[0];
            } catch (Exception $e) {
                continue;
            }
            $request->merge([$key => $decoded, $this->buildName($key) => $value]);

        }

        return $next($request);
    }

    protected function buildName($key)
    {
        return '__origin__' . $key;
    }
}
