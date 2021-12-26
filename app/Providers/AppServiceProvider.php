<?php

namespace App\Providers;

use App\Models\{
    Category,
    Plan,
    Product,
    Table,
    Tenant,
};
use App\Observers\{
    ProductObserver,
    CategoryObserver,
    PlanObserver,
    TenantObserver,
    TableObserver,
};
use App\Repositories\Contracts\{
    TenantRepositoryInterface,
};
use App\Repositories\{
    TenantRepository,
};
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Plan::observe(PlanObserver::class);
        Tenant::observe(TenantObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Table::observe(TableObserver::class);
    }
}
