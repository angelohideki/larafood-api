<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Table;

class TableObserver
{
    /**
     * Handle the table "creating" event.
     *
     * @param  \App\Models\Table $table
     * @return void
     */
    public function creating(Table $table)
    {
        $table->url = Str::slug($table->identify);
        $table->uuid = Str::uuid();

    }

    /**
     * Handle the table "updating" event.
     *
     * @param  \App\Models\Table  $table
     * @return void
     */
    public function updating(Table $table)
    {
        $table->url = Str::slug($table->identify);
    }
    
}
