<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

abstract class BaseApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $service;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $data = $this->service->getAll($perPage);

            return ApiResponse::paginated($data, $this->getResourceClass());
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->service->create($request->validated());
            $resource = $this->getResourceClass();

            return ApiResponse::resource(
                $resource ? new $resource($data) : $data,
                $this->getCreatedMessage(),
                201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->service->getById($id);
            $resource = $this->getResourceClass();

            return ApiResponse::resource(
                $resource ? new $resource($data) : $data
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $this->service->update($id, $request->validated());
            $resource = $this->getResourceClass();

            return ApiResponse::resource(
                $resource ? new $resource($data) : $data,
                $this->getUpdatedMessage()
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(null, $this->getDeletedMessage());
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Search resources
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $request->only($this->getSearchableFields());
            $data = $this->service->search($filters);

            return ApiResponse::success($data);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle exceptions consistently
     */
    protected function handleException(\Exception $e): JsonResponse
    {
        // Log the exception
        Log::error($e->getMessage(), [
            'exception' => $e,
            'request' => request()->all(),
            'user_id' => auth()->id()
        ]);

        // Return appropriate response based on exception type
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return ApiResponse::notFound('Resource not found');
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return ApiResponse::validationError($e->errors());
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            return ApiResponse::unauthorized($e->getMessage());
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            return ApiResponse::forbidden($e->getMessage());
        }

        // For other exceptions, return generic error
        $message = app()->environment('production') 
            ? 'An error occurred while processing your request' 
            : $e->getMessage();

        return ApiResponse::error($message, 500);
    }

    /**
     * Get the resource class for transformation
     */
    protected function getResourceClass(): ?string
    {
        return null;
    }

    /**
     * Get searchable fields for filtering
     */
    protected function getSearchableFields(): array
    {
        return [];
    }

    /**
     * Get success message for creation
     */
    protected function getCreatedMessage(): string
    {
        return 'Resource created successfully';
    }

    /**
     * Get success message for update
     */
    protected function getUpdatedMessage(): string
    {
        return 'Resource updated successfully';
    }

    /**
     * Get success message for deletion
     */
    protected function getDeletedMessage(): string
    {
        return 'Resource deleted successfully';
    }

    /**
     * Success response helper
     */
    protected function success($data = null, string $message = null, int $status = 200): JsonResponse
    {
        return ApiResponse::success($data, $message, $status);
    }

    /**
     * Error response helper
     */
    protected function error(string $message, int $status = 500, $errors = null): JsonResponse
    {
        return ApiResponse::error($message, $status, $errors);
    }

    /**
     * Resource response helper
     */
    protected function resource($resource, string $message = null, int $status = 200): JsonResponse
    {
        return ApiResponse::resource($resource, $message, $status);
    }
}