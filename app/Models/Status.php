<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $table = "status";

    protected $fillable = ['status', 'account_id', 'id', 'time_in', 'time_out'];

    protected $primaryKey = 'id';

    public $timestamps = true;
}
