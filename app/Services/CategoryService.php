<?php

namespace App\Services;

use App\Exceptions\Category\CategoryCreationException;
use App\Exceptions\Category\CategoryDeletionException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Exceptions\Category\CategoryUpdateException;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\FileServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;
    protected $tenantRepository;
    protected $fileService;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        TenantRepositoryInterface $tenantRepository,
        FileServiceInterface $fileService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->tenantRepository = $tenantRepository;
        $this->fileService = $fileService;
    }

    /**
     * Get all categories with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($perPage);
    }

    /**
     * Get category by ID
     */
    public function getById(int $id): Category
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            throw new CategoryNotFoundException("Category with ID {$id} not found");
        }

        return $category;
    }

    /**
     * Create new category
     */
    public function create(array $data): Category
    {
        DB::beginTransaction();
        
        try {
            // Generate URL if not provided
            if (!isset($data['url']) || empty($data['url'])) {
                $data['url'] = Str::slug($data['name']);
            }

            // Ensure unique URL
            $data['url'] = $this->ensureUniqueUrl($data['url']);

            // Handle image upload if provided
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $tenant = auth()->user()->tenant;
                if (!$tenant) {
                    throw new CategoryCreationException('User has no associated tenant');
                }

                $imagePath = $this->fileService->uploadImage(
                    $data['image'],
                    $this->fileService->getTenantPath($tenant->uuid, 'categories')
                );
                
                $data['image'] = $imagePath;
            }

            $category = $this->categoryRepository->create($data);
            
            DB::commit();
            
            return $category;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded image if category creation failed
            if (isset($data['image']) && is_string($data['image'])) {
                $this->fileService->delete($data['image']);
            }
            
            throw new CategoryCreationException('Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * Update category
     */
    public function update(int $id, array $data): Category
    {
        DB::beginTransaction();
        
        try {
            $category = $this->getById($id);
            $oldImagePath = $category->image;

            // Update URL if name changed
            if (isset($data['name']) && $data['name'] !== $category->name) {
                if (!isset($data['url']) || empty($data['url'])) {
                    $data['url'] = Str::slug($data['name']);
                }
                $data['url'] = $this->ensureUniqueUrl($data['url'], $id);
            }

            // Handle image upload if provided
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $tenant = auth()->user()->tenant;
                if (!$tenant) {
                    throw new CategoryUpdateException('User has no associated tenant');
                }

                $imagePath = $this->fileService->replace(
                    $data['image'],
                    $oldImagePath,
                    $this->fileService->getTenantPath($tenant->uuid, 'categories')
                );
                
                $data['image'] = $imagePath;
            }

            $this->categoryRepository->update($id, $data);
            
            // Refresh the model to get updated data
            $category = $this->getById($id);
            
            DB::commit();
            
            return $category;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new CategoryUpdateException('Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Delete category
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        
        try {
            $category = $this->getById($id);
            
            // Check if category has products
            if ($category->products()->count() > 0) {
                throw new CategoryDeletionException('Cannot delete category that has products associated');
            }

            // Delete associated image
            if ($category->image) {
                $this->fileService->delete($category->image);
            }

            $deleted = $this->categoryRepository->delete($id);
            
            DB::commit();
            
            return $deleted;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($e instanceof CategoryDeletionException) {
                throw $e;
            }
            
            throw new CategoryDeletionException('Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * Get categories by tenant UUID
     */
    public function getCategoriesByUuid(string $uuid): Collection
    {
        $tenant = $this->tenantRepository->getTenantByUuid($uuid);
        
        if (!$tenant) {
            throw new CategoryNotFoundException('Tenant not found');
        }

        return $this->categoryRepository->getCategoriesByTenantId($tenant->id);
    }

    /**
     * Get category by URL
     */
    public function getCategoryByUrl(string $url): ?Category
    {
        return $this->categoryRepository->getCategoryByUrl($url);
    }

    /**
     * Get category by UUID
     */
    public function getCategoryByUuid(string $uuid): ?Category
    {
        return $this->categoryRepository->getCategoryByUuid($uuid);
    }

    /**
     * Search categories
     */
    public function search(array $filters): Collection
    {
        if (isset($filters['name'])) {
            $tenantId = auth()->user()->tenant_id ?? null;
            return $this->categoryRepository->searchByName($filters['name'], $tenantId);
        }

        return $this->categoryRepository->search($filters);
    }

    /**
     * Get categories with products count
     */
    public function getCategoriesWithProductsCount(int $tenantId = null): Collection
    {
        $tenantId = $tenantId ?? auth()->user()->tenant_id;
        return $this->categoryRepository->getCategoriesWithProductsCount($tenantId);
    }

    /**
     * Ensure URL is unique
     */
    private function ensureUniqueUrl(string $url, int $excludeId = null): string
    {
        $originalUrl = $url;
        $counter = 1;

        while (true) {
            $query = $this->categoryRepository->getModel()->where('url', $url);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $url = $originalUrl . '-' . $counter;
            $counter++;
        }

        return $url;
    }
}
