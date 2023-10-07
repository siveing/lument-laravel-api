<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access_token = $request->bearerToken();
        if (!$access_token) {
            return response()->json([
                'meta' => [
                    'code' => 400,
                    'status' => 'Unauthorized',
                    'message' => 'Access token not provided.',
                ],
                'results' => null,
            ], 401);
        }
        try {
            $credential = JWT::decode($access_token, new Key(env('JWT_SECRET'), 'HS256'));
            $user = User::find($credential->sub);
            // set credentials
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return $next($request);
        } catch (ExpiredException $e) {
            return response()->json([
                'meta' => [
                    'code' => 400,
                    'status' => 'Bad Request',
                    'message' => 'access_token expired.',
                ],
                'results' => null,
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'status' => 'Internal Server Error',
                    'message' => 'An error while decoding access_token.',
                ],
                'results' => null,
            ], 500);
        }
    }
}
