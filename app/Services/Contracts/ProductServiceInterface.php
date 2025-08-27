<?php

namespace App\Services\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    /**
     * Get all products with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get product by ID
     */
    public function getById(int $id): Product;

    /**
     * Create new product
     */
    public function create(array $data): Product;

    /**
     * Update product
     */
    public function update(int $id, array $data): Product;

    /**
     * Delete product
     */
    public function delete(int $id): bool;

    /**
     * Get products by tenant UUID with category filtering
     */
    public function getProductsByTenantUuid(string $uuid, array $categories = []): Collection;

    /**
     * Get product by flag
     */
    public function getProductByFlag(string $flag): ?Product;

    /**
     * Get product by UUID
     */
    public function getProductByUuid(string $uuid): ?Product;

    /**
     * Search products
     */
    public function search(array $filters): Collection;

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId): Collection;

    /**
     * Attach categories to product
     */
    public function attachCategories(int $productId, array $categoryIds): Product;

    /**
     * Detach categories from product
     */
    public function detachCategories(int $productId, array $categoryIds): Product;
}