<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    
    protected $table = "bills";

    protected $fillable = ['id', 'supplier_id', 'admin_id','product_id', 'quantity', 'discount','total','tax','pay', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
