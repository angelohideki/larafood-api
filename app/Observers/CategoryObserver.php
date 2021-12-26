<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the category "creating" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function creating(Category $category)
    {

        $category->url = Str::slug($category->name);
        $category->uuid = Str::uuid();

    }

    /**
     * Handle the category "updating" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updating(Category $category)
    {
        $category->url = Str::slug($category->name);
    }
    
}
