<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = "products";

    protected $fillable = ['id', 'name', 'price', 'quantity', 'slug','status','demand','avatar','evaluate','category_id', 'brand','size','color','created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
    // Product.php

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
