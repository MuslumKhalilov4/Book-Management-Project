<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public static function successResponse(string $message, $resource, $code, $token = null): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $resource
        ];

        if ($token) {
            $response['token'] = $token;
        }

        return response()->json($response, $code);
    }

    public static function failResponse(string $message, $code): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
