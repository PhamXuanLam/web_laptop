<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = "product_reviews";

    protected $fillable = ['id', 'customer_id', 'product_id', 'comment', 'rate', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function customer()
    {
        return $this->hasOne(Customer::class, "id", "customer_id");
    }
}
