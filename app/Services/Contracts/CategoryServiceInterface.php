<?php

namespace App\Services\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    /**
     * Get all categories with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get category by ID
     */
    public function getById(int $id): Category;

    /**
     * Create new category
     */
    public function create(array $data): Category;

    /**
     * Update category
     */
    public function update(int $id, array $data): Category;

    /**
     * Delete category
     */
    public function delete(int $id): bool;

    /**
     * Get categories by tenant UUID
     */
    public function getCategoriesByUuid(string $uuid): Collection;

    /**
     * Get category by URL
     */
    public function getCategoryByUrl(string $url): ?Category;

    /**
     * Get category by UUID
     */
    public function getCategoryByUuid(string $uuid): ?Category;

    /**
     * Search categories
     */
    public function search(array $filters): Collection;

    /**
     * Get categories with products count
     */
    public function getCategoriesWithProductsCount(int $tenantId = null): Collection;
}