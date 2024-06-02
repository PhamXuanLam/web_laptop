<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ['id', 'name', 'price', 'quantity', 'slug','status','demand','evaluate','category_id', 'brand','size','color','created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
    // Product.php
    const DIRECTORY_IMAGE = 'public/product/';

    public function category()
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, "product_id", "id");
    }

    public function images()
    {
        return $this->hasMany(Image::class, "product_id", "id");
    }

    public function description()
    {
        return $this->hasOne(Description::class, "product_id", "id");
    }
}
