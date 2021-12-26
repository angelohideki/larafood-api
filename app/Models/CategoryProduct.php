<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'categories_product';

    protected $fillable = ['name'];

    public function category()
    {
        $this->belongsTo(Product::class);
    }
}
