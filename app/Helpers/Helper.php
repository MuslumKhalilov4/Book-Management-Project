<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
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

    public static function logException(\Throwable $e): void
    {
        Log::error([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }


    public static function uploadImage(UploadedFile $image, string $folder): string
    {
        $extension = $image->extension();

        $image_name = Str::random(20) . '.' . $extension;

        $uploaded = $image->storeAs('public/uploads/' . $folder, $image_name);

        $image_path = Storage::url($uploaded);

        return $image_path;
    }

    public static function deleteFile($file_path): void
    {
        $storage_path = str_replace('/storage/', 'public/', $file_path);

        if (Storage::exists($storage_path)) {
            Storage::delete($storage_path);
        }
    }
}
