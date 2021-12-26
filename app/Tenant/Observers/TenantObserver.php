<?php

namespace App\Tenant\Observers;

use App\Tenant\ManagerTenant;
use Illuminate\Database\Eloquent\Model;

class TenantObserver
{
    /**
     * Handle the plan "creating" event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function creating(Model $model)
    {
        $managerTenant = app(ManagerTenant::class);
        //dd($managerTenant);
        //$identify = $managerTenant->getTenantIdentify();

        //if ($identify)
          //  $model->tenant_id = $identify;
          $model->tenant_id = $managerTenant->getTenantIdentify();
          //var_dump($model);die;
    }

}
