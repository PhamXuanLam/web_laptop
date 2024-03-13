<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = "provinces";

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];

    protected $primaryKey = "id";

    public $timestamps = true;

    public function districts() {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
