<?php

namespace App\Http\Middleware;

use App\Repository\PermissionRepositoryInterface;
use App\Support\ACL;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckAcl
{

    protected $acl;

    public function __construct(ACL $acl)
    {
        $this->acl = $acl;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permissions = app(PermissionRepositoryInterface::class)->all()->pluck('name')->toArray();
        
        $route = $request->route()->getName();

        /** @var User $user */
        $user = Auth::user();

        if (in_array($route, $permissions) && !$user->can($route)) {
            return abort(JsonResponse::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
