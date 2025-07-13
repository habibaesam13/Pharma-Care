<?php

namespace App\Traits;
use Symfony\Component\HttpFoundation\JsonResponse;
trait ApiResponse
{
    public static function success($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function created($data = null, $message = 'Created Successfully')
    {
        return self::success($data, $message, 201);
    }

    public static function deleted($message = 'Deleted Successfully')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], 200);
    }

    public static function error($message = 'An error occurred', $code = 400, $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    public static function validationError($errors, $message = 'Validation Error')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

}
