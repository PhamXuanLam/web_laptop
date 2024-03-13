<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $table = "communes";

    protected $fillable = ['id', 'name', 'district_id'];

    protected $primaryKey = "id";

    public $timestamps = true;

    public function district() {
        return $this->hasOne(District::class, "id", "district_id");
    }
}
