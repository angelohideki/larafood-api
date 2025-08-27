<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    CategoryRepositoryInterface,
    ProductRepositoryInterface,
    TableRepositoryInterface,
    TenantRepositoryInterface,
};
use App\Repositories\{
    CategoryRepository,
    ProductRepository,
    TableRepository,
    TenantRepository,
};
use App\Services\Contracts\FileServiceInterface;
use App\Services\FileService;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(//bind para que quando injetar a classe TenantRepositoryInterface vai criar um objeto TenantRepository
            TenantRepositoryInterface::class,
            TenantRepository::class,
            
        );

        $this->app->bind(//bind para que quando injetar a classe CategoryRepositoryInterface vai criar um objeto CategoryRepository
            CategoryRepositoryInterface::class,
            CategoryRepository::class,

        );

        $this->app->bind(//bind para que quando injetar a classe TableRepositoryInterface vai criar um objeto TableRepository
            TableRepositoryInterface::class,
            TableRepository::class,

        );

        $this->app->bind(//bind para que quando injetar a classe ProductRepositoryInterface vai criar um objeto ProductRepository
            ProductRepositoryInterface::class,
            ProductRepository::class,

        );

        $this->app->bind(//bind para que quando injetar a classe FileServiceInterface vai criar um objeto FileService
            FileServiceInterface::class,
            FileService::class,

        );

        $this->app->bind(//bind para que quando injetar a classe CategoryServiceInterface vai criar um objeto CategoryService
            CategoryServiceInterface::class,
            CategoryService::class,

        );

        $this->app->bind(//bind para que quando injetar a classe ProductServiceInterface vai criar um objeto ProductService
            ProductServiceInterface::class,
            ProductService::class,

        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
