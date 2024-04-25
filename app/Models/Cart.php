<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart";

    protected $fillable = ["id", "customer_id", "product_id", "product_name", "quantity", "price", "total", "status", "created_at", "updates_at"];

    protected $primaryKey = "id";

    public $timestamps = true;
}
