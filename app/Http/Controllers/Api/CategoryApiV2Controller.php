<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\TenantFormRequest;
use App\Http\Resources\CategoryResource;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryApiV2Controller extends BaseApiController
{
    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->service = $categoryService;
    }

    /**
     * Get categories by tenant
     */
    public function categoryByTenant(TenantFormRequest $request): JsonResponse
    {
        try {
            $categories = $this->service->getCategoriesByUuid($request->token_company);

            return $this->resource(CategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get category by URL
     */
    public function showByUrl(TenantFormRequest $request, string $url): JsonResponse
    {
        try {
            $category = $this->service->getCategoryByUrl($url);

            if (!$category) {
                return $this->notFound('Category not found');
            }

            return $this->resource(new CategoryResource($category));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'unique:categories,name'],
            'description' => ['required', 'min:3', 'max:10000'],
        ]);

        try {
            $category = $this->service->create($request->validated());

            return $this->resource(
                new CategoryResource($category),
                'Category created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255', "unique:categories,name,{$id},id"],
            'description' => ['required', 'min:3', 'max:10000'],
        ]);

        try {
            $category = $this->service->update($id, $request->validated());

            return $this->resource(
                new CategoryResource($category),
                'Category updated successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return $this->success(null, 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Search categories
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['name', 'description']);
            $categories = $this->service->search($filters);

            return $this->resource(CategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get categories with products count
     */
    public function categoriesWithProductsCount(Request $request): JsonResponse
    {
        try {
            $categories = $this->service->getCategoriesWithProductsCount();

            return $this->resource(CategoryResource::collection($categories));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get the resource class for transformation
     */
    protected function getResourceClass(): string
    {
        return CategoryResource::class;
    }

    /**
     * Get searchable fields for filtering
     */
    protected function getSearchableFields(): array
    {
        return ['name', 'description', 'url'];
    }

    /**
     * Get success message for creation
     */
    protected function getCreatedMessage(): string
    {
        return 'Category created successfully';
    }

    /**
     * Get success message for update
     */
    protected function getUpdatedMessage(): string
    {
        return 'Category updated successfully';
    }

    /**
     * Get success message for deletion
     */
    protected function getDeletedMessage(): string
    {
        return 'Category deleted successfully';
    }
}