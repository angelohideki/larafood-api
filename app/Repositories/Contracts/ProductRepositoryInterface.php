<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductsByTenantId(int $tenantId, array $categories = []): Collection;
    public function getProductByFlag(string $flag): ?Product;
    public function getProductByUuid(string $uuid): ?Product;
    public function getProductsByCategory(int $categoryId): Collection;
    public function searchProducts(string $term, int $tenantId = null): Collection;
    public function getActiveProducts(int $tenantId = null): Collection;
    public function getFeaturedProducts(int $tenantId = null): Collection;
}