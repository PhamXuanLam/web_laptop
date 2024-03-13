<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = "address";

    protected $fillable = ['id', 'name', 'province_id', "district_id", 'commune_id', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
