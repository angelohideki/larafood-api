<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\StoreUpdateProduct;
use App\Http\Requests\Api\TenantFormRequest;
use App\Http\Resources\ProductResource;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductApiV2Controller extends BaseApiController
{
    public function __construct(ProductServiceInterface $productService)
    {
        $this->service = $productService;
    }

    /**
     * Get products by tenant with optional category filtering
     */
    public function productsByTenant(TenantFormRequest $request): JsonResponse
    {
        try {
            $categories = $request->get('categories', []);
            if (is_string($categories)) {
                $categories = explode(',', $categories);
            }

            $products = $this->service->getProductsByTenantUuid(
                $request->token_company,
                $categories
            );

            return $this->resource(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get product by flag
     */
    public function showByFlag(TenantFormRequest $request, string $flag): JsonResponse
    {
        try {
            $product = $this->service->getProductByFlag($flag);

            if (!$product) {
                return $this->notFound('Product not found');
            }

            return $this->resource(new ProductResource($product));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request using StoreUpdateProduct rules
            $validator = Validator::make($request->all(), (new \App\Http\Requests\StoreUpdateProduct)->rules());
            
            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }
            
            $product = $this->service->create($validator->validated());

            return $this->resource(
                new ProductResource($product),
                'Product created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Validate the request using StoreUpdateProduct rules
            $validator = Validator::make($request->all(), (new \App\Http\Requests\StoreUpdateProduct)->rules());
            
            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }
            
            $product = $this->service->update($id, $validator->validated());

            return $this->resource(
                new ProductResource($product),
                'Product updated successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return $this->success(null, 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'title', 'description', 'price_min', 'price_max', 'category_id'
            ]);

            $products = $this->service->search($filters);

            return $this->resource(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get products by category
     */
    public function productsByCategory($categoryId): JsonResponse
    {
        try {
            $products = $this->service->getProductsByCategory($categoryId);

            return $this->resource(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get featured products
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $products = $this->service->getFeaturedProducts();

            return $this->resource(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get latest products
     */
    public function latest(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $products = $this->service->getLatestProducts($limit);

            return $this->resource(ProductResource::collection($products));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Attach categories to product
     */
    public function attachCategories(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'category_ids' => 'required|array',
                'category_ids.*' => 'exists:categories,id'
            ]);

            $product = $this->service->attachCategories($id, $request->category_ids);

            return $this->resource(
                new ProductResource($product),
                'Categories attached successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Detach categories from product
     */
    public function detachCategories(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'category_ids' => 'required|array',
                'category_ids.*' => 'exists:categories,id'
            ]);

            $product = $this->service->detachCategories($id, $request->category_ids);

            return $this->resource(
                new ProductResource($product),
                'Categories detached successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get the resource class for transformation
     */
    protected function getResourceClass(): string
    {
        return ProductResource::class;
    }

    /**
     * Get searchable fields for filtering
     */
    protected function getSearchableFields(): array
    {
        return ['title', 'description', 'price_min', 'price_max'];
    }

    /**
     * Get success message for creation
     */
    protected function getCreatedMessage(): string
    {
        return 'Product created successfully';
    }

    /**
     * Get success message for update
     */
    protected function getUpdatedMessage(): string
    {
        return 'Product updated successfully';
    }

    /**
     * Get success message for deletion
     */
    protected function getDeletedMessage(): string
    {
        return 'Product deleted successfully';
    }
}