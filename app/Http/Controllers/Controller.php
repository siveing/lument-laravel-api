<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function ok($message, $data = null)
    {
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'Success',
                'message' => $message,
            ],
            'results' => $data,
        ], 200);
    }

    protected function created($message, $data)
    {
        return response()->json([
            'meta' => [
                'code' => 201,
                'status' => 'Created',
                'message' => $message,
            ],
            'results' => $data,
        ], 201);
    }

    protected function notFound($message, $data = null)
    {
        return response()->json([
            'meta' => [
                'code' => 404,
                'status' => 'Not Found',
                'message' => $message,
            ],
            'results' => $data,
        ], 404);
    }

    protected function badRequest($message, $data = null)
    {
        return response()->json([
            'meta' => [
                'code' => 400,
                'status' => 'Bad Request',
                'message' => $message,
            ],
            'results' => $data,
        ], 400);
    }

    protected function unauthorized($message = 'Access Denied')
    {
        return response()->json([
            'meta' => [
                'code' => 401,
                'status' => 'Access Denied',
                'message' => $message,
            ],
            'results' => null,
        ], 401);
    }

    protected function internalServerError($message, $data = null)
    {
        return response()->json([
            'meta' => [
                'code' => 500,
                'status' => 'Bad Request',
                'message' => $message,
            ],
            'results' => $data,
        ], 500);
    }
}
