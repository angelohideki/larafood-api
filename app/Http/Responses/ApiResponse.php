<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    /**
     * Return success response
     */
    public static function success($data = null, string $message = null, int $status = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'timestamp' => now()->toISOString()
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Return error response
     */
    public static function error(string $message, int $status = 500, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString()
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Return validation error response
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Return not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return resource response with optional transformation
     */
    public static function resource($resource, string $message = null, int $status = 200): JsonResponse
    {
        if ($resource instanceof JsonResource || $resource instanceof ResourceCollection) {
            $data = $resource->toArray(request());
        } else {
            $data = $resource;
        }

        return self::success($data, $message, $status);
    }

    /**
     * Return paginated response
     */
    public static function paginated($paginator, $resourceClass = null, string $message = null): JsonResponse
    {
        $data = [
            'data' => $resourceClass ? $resourceClass::collection($paginator->items()) : $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_next_page' => $paginator->hasMorePages(),
                'has_previous_page' => $paginator->currentPage() > 1,
            ]
        ];

        return self::success($data, $message);
    }
}