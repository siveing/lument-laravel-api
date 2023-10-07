<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!$request->user()->hasRole($role)) {
            return response()->json([
                'meta' => [
                    'code' => 401,
                    'status' => 'Access Denied',
                    'message' => "Access Denied user ".$role,
                ],
                'results' => null,
            ], 401);
        }
        if ($permission !== null) {
            if(!$request->user()->can($permission)){
                return response()->json([
                    'meta' => [
                        'code' => 401,
                        'status' => 'Access Denied',
                        'message' => "Access Denied",
                    ],
                    'results' => null,
                ], 401);
            }
        }
        return $next($request);
    }
}
