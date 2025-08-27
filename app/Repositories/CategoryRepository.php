<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * Get categories by tenant UUID
     */
    public function getCategoriesByTenantUuid(string $uuid): Collection
    {
        return $this->model
            ->whereHas('tenant', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })
            ->get();
    }

    /**
     * Get categories by tenant ID
     */
    public function getCategoriesByTenantId(int $tenantId): Collection
    {
        return $this->model
            ->where('tenant_id', $tenantId)
            ->get();
    }

    /**
     * Get category by URL
     */
    public function getCategoryByUrl(string $url): ?Category
    {
        return $this->model
            ->where('url', $url)
            ->first();
    }

    /**
     * Get category by UUID
     */
    public function getCategoryByUuid(string $uuid): ?Category
    {
        return $this->model
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Search categories by name
     */
    public function searchByName(string $name, int $tenantId = null): Collection
    {
        $query = $this->model
            ->where('name', 'LIKE', "%{$name}%")
            ->orWhere('description', 'LIKE', "%{$name}%");

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }

    /**
     * Get categories with products count
     */
    public function getCategoriesWithProductsCount(int $tenantId = null): Collection
    {
        $query = $this->model->withCount('products');

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }

    /**
     * Get categories with products
     */
    public function getCategoriesWithProducts(int $tenantId = null): Collection
    {
        $query = $this->model->with('products');

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }

    /**
     * Get active categories
     */
    public function getActiveCategories(int $tenantId = null): Collection
    {
        $query = $this->model->where('active', true);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get();
    }
}
