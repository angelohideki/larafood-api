<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getCategoriesByTenantUuid(string $uuid): Collection;
    public function getCategoriesByTenantId(int $id): Collection;
    public function getCategoryByUrl(string $url): ?Category;
    public function getCategoryByUuid(string $uuid): ?Category;
    public function searchByName(string $name, int $tenantId = null): Collection;
}