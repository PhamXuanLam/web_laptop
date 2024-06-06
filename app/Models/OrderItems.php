<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = "order_items";

    protected $fillable = ['id', 'order_id', 'product_id', 'quantity', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function order()
    {
        return $this->hasOne(Order::class, "id", "order_id");
    }

    public function product()
    {
        return $this->hasOne(Product::class, "id", "product_id");
    }
}
