<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;
    protected $table = "description";

    protected $fillable = ['id', 'product_id', 'Guarantee', 'mass', 'created_at', 'updated_at','CPU','screen','Storage','Graphics','The battery','RAM','Operating system','other'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
