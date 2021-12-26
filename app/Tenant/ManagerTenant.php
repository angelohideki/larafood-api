<?php

namespace App\Tenant;

use App\Models\Tenant;

class ManagerTenant
{
    public function getTenantIdentify()
    {
        //return auth()->check() ? auth()->user()->tenant_id : '';
        //dd(auth()->user()->tenant_id);
        return auth()->user()->tenant_id;
    }

    public function getTenant(): Tenant
    {
        //return auth()->check() ? auth()->user()->tenant : '';
        return auth()->user()->tenant;
    }

    public function isAdmin(): bool
    {
        return in_array(auth()->user()->email, config('tenant.admins'));
    }

}
