<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;
    protected $table = "description";

    protected $fillable = [
        'id', 'product_id', 'guarantee', 'mass', 'cpu',
        'screen', 'storage', 'graphics',
        'battery', 'operating_system', 'ram',
        'other', 'created_at', 'updated_at'
    ];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
