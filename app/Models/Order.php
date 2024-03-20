<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $table = "orders";

    protected $fillable = ['id', 'customer_id', 'employee_id','total', 'tax','discount','pay','status', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
