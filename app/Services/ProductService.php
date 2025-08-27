<?php

namespace App\Services;

use App\Exceptions\Product\ProductCreationException;
use App\Exceptions\Product\ProductDeletionException;
use App\Exceptions\Product\ProductNotFoundException;
use App\Exceptions\Product\ProductUpdateException;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\FileServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService implements ProductServiceInterface
{
    protected $productRepository;
    protected $tenantRepository;
    protected $fileService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TenantRepositoryInterface $tenantRepository,
        FileServiceInterface $fileService
    ) {
        $this->productRepository = $productRepository;
        $this->tenantRepository = $tenantRepository;
        $this->fileService = $fileService;
    }

    /**
     * Get all products with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage);
    }

    /**
     * Get product by ID
     */
    public function getById(int $id): Product
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            throw new ProductNotFoundException("Product with ID {$id} not found");
        }

        return $product;
    }

    /**
     * Create new product
     */
    public function create(array $data): Product
    {
        DB::beginTransaction();
        
        try {
            // Generate flag if not provided
            if (!isset($data['flag']) || empty($data['flag'])) {
                $data['flag'] = Str::slug($data['title']);
            }

            // Ensure unique flag
            $data['flag'] = $this->ensureUniqueFlag($data['flag']);

            // Handle image upload if provided
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $tenant = auth()->user()->tenant;
                if (!$tenant) {
                    throw new ProductCreationException('User has no associated tenant');
                }

                $imagePath = $this->fileService->uploadImage(
                    $data['image'],
                    $this->fileService->getTenantPath($tenant->uuid, 'products')
                );
                
                $data['image'] = $imagePath;
            }

            // Format price if it's a string
            if (isset($data['price']) && is_string($data['price'])) {
                $data['price'] = $this->formatPrice($data['price']);
            }

            $product = $this->productRepository->create($data);
            
            // Attach categories if provided
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->attach($data['categories']);
            }
            
            DB::commit();
            
            return $product->load('categories');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded image if product creation failed
            if (isset($data['image']) && is_string($data['image'])) {
                $this->fileService->delete($data['image']);
            }
            
            throw new ProductCreationException('Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Update product
     */
    public function update(int $id, array $data): Product
    {
        DB::beginTransaction();
        
        try {
            $product = $this->getById($id);
            $oldImagePath = $product->image;

            // Update flag if title changed
            if (isset($data['title']) && $data['title'] !== $product->title) {
                if (!isset($data['flag']) || empty($data['flag'])) {
                    $data['flag'] = Str::slug($data['title']);
                }
                $data['flag'] = $this->ensureUniqueFlag($data['flag'], $id);
            }

            // Handle image upload if provided
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $tenant = auth()->user()->tenant;
                if (!$tenant) {
                    throw new ProductUpdateException('User has no associated tenant');
                }

                $imagePath = $this->fileService->replace(
                    $data['image'],
                    $oldImagePath,
                    $this->fileService->getTenantPath($tenant->uuid, 'products')
                );
                
                $data['image'] = $imagePath;
            }

            // Format price if it's a string
            if (isset($data['price']) && is_string($data['price'])) {
                $data['price'] = $this->formatPrice($data['price']);
            }

            $this->productRepository->update($id, $data);
            
            // Update categories if provided
            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }
            
            // Refresh the model to get updated data
            $product = $this->getById($id);
            
            DB::commit();
            
            return $product->load('categories');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new ProductUpdateException('Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Delete product
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        
        try {
            $product = $this->getById($id);
            
            // Delete associated image
            if ($product->image) {
                $this->fileService->delete($product->image);
            }

            // Detach categories
            $product->categories()->detach();

            $deleted = $this->productRepository->delete($id);
            
            DB::commit();
            
            return $deleted;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new ProductDeletionException('Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Get products by tenant UUID with category filtering
     */
    public function getProductsByTenantUuid(string $uuid, array $categories = []): Collection
    {
        $tenant = $this->tenantRepository->getTenantByUuid($uuid);
        
        if (!$tenant) {
            throw new ProductNotFoundException('Tenant not found');
        }

        return $this->productRepository->getProductsByTenantId($tenant->id, $categories);
    }

    /**
     * Get product by flag
     */
    public function getProductByFlag(string $flag): ?Product
    {
        return $this->productRepository->getProductByFlag($flag);
    }

    /**
     * Get product by UUID
     */
    public function getProductByUuid(string $uuid): ?Product
    {
        return $this->productRepository->getProductByUuid($uuid);
    }

    /**
     * Search products
     */
    public function search(array $filters): Collection
    {
        if (isset($filters['title']) || isset($filters['description'])) {
            $term = $filters['title'] ?? $filters['description'];
            $tenantId = auth()->user()->tenant_id ?? null;
            return $this->productRepository->searchProducts($term, $tenantId);
        }

        if (isset($filters['price_min']) && isset($filters['price_max'])) {
            $tenantId = auth()->user()->tenant_id ?? null;
            return $this->productRepository->getProductsByPriceRange(
                (float) $filters['price_min'],
                (float) $filters['price_max'],
                $tenantId
            );
        }

        return $this->productRepository->search($filters);
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId): Collection
    {
        return $this->productRepository->getProductsByCategory($categoryId);
    }

    /**
     * Attach categories to product
     */
    public function attachCategories(int $productId, array $categoryIds): Product
    {
        $product = $this->getById($productId);
        $product->categories()->attach($categoryIds);
        
        return $product->load('categories');
    }

    /**
     * Detach categories from product
     */
    public function detachCategories(int $productId, array $categoryIds): Product
    {
        $product = $this->getById($productId);
        $product->categories()->detach($categoryIds);
        
        return $product->load('categories');
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts(int $tenantId = null): Collection
    {
        $tenantId = $tenantId ?? auth()->user()->tenant_id;
        return $this->productRepository->getFeaturedProducts($tenantId);
    }

    /**
     * Get latest products
     */
    public function getLatestProducts(int $limit = 10, int $tenantId = null): Collection
    {
        $tenantId = $tenantId ?? auth()->user()->tenant_id;
        return $this->productRepository->getLatestProducts($limit, $tenantId);
    }

    /**
     * Ensure flag is unique
     */
    private function ensureUniqueFlag(string $flag, int $excludeId = null): string
    {
        $originalFlag = $flag;
        $counter = 1;

        while (true) {
            $query = $this->productRepository->getModel()->where('flag', $flag);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $flag = $originalFlag . '-' . $counter;
            $counter++;
        }

        return $flag;
    }

    /**
     * Format price from Brazilian format to decimal
     */
    private function formatPrice(string $price): float
    {
        // Remove thousand separators (dots) and replace comma with dot
        $price = str_replace('.', '', $price);
        $price = str_replace(',', '.', $price);
        
        return (float) $price;
    }
}
