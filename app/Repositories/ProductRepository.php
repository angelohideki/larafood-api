<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * Get products by tenant ID with optional category filtering
     */
    public function getProductsByTenantId(int $tenantId, array $categories = []): Collection
    {
        $query = $this->model->where('tenant_id', $tenantId);

        if (!empty($categories)) {
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('categories.url', $categories)
                  ->orWhereIn('categories.id', $categories);
            });
        }

        return $query->with('categories')->get();
    }

    /**
     * Get product by flag
     */
    public function getProductByFlag(string $flag): ?Product
    {
        return $this->model
            ->where('flag', $flag)
            ->first();
    }

    /**
     * Get product by UUID
     */
    public function getProductByUuid(string $uuid): ?Product
    {
        return $this->model
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId): Collection
    {
        return $this->model
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->with('categories')
            ->get();
    }

    /**
     * Search products by title or description
     */
    public function searchProducts(string $term, int $tenantId = null): Collection
    {
        $query = $this->model
            ->where('title', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%");

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->with('categories')->get();
    }

    /**
     * Get active products
     */
    public function getActiveProducts(int $tenantId = null): Collection
    {
        $query = $this->model->where('active', true);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->with('categories')->get();
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts(int $tenantId = null): Collection
    {
        $query = $this->model->where('featured', true);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->with('categories')->get();
    }

    /**
     * Get products with categories
     */
    public function getProductsWithCategories(int $tenantId = null): Collection
    {
        $query = $this->model->with('categories');

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }

    /**
     * Get products by price range
     */
    public function getProductsByPriceRange(float $minPrice, float $maxPrice, int $tenantId = null): Collection
    {
        $query = $this->model->whereBetween('price', [$minPrice, $maxPrice]);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->with('categories')->get();
    }

    /**
     * Get latest products
     */
    public function getLatestProducts(int $limit = 10, int $tenantId = null): Collection
    {
        $query = $this->model->latest();

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->with('categories')->limit($limit)->get();
    }
}
