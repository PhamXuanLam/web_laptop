<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = "products";

    protected $fillable = ['id', 'name', 'price', 'quantity', 'slug','status','description','avatar','evaluate','category_id', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
