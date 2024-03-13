<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = "districts";

    protected $fillable = ['id', 'name', 'province_id', 'created_at', 'updated_at'];

    protected $primaryKey = "id";

    public $timestamps = true;

    public function province() {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function communes() {
        return $this->hasMany(Commune::class, 'district_id', 'id');
    }
}
