<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Tenant;

class TenantObserver
{
    /**
     * Handle the tenant "creating" event.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return void
     */
    public function creating(Tenant $tenant)
    {

        $tenant->uuid = Str::uuid();
        $tenant->url = Str::slug($tenant->name);
    }

    /**
     * Handle the tenant "updating" event.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return void
     */
    public function updating(Tenant $tenant)
    {
        $this->url = Str::slug($tenant->name);
    }

}