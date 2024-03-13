<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = "accounts";

    protected $fillable = 
    [
        "id", "username", "password", "status", 'role',
        'first_name', 'last_name', 'birth_day', 'email',
        'phone', 'avatar', 'created_at', 'updated_at',
    ];

    protected $primaryKey = "id";

    public $timestamps = true;

    public function admin() {
        return $this->hasOne(Admin::class, 'account_id', 'id');
    }
    public function customer() {
        return $this->hasOne(Customer::class, 'account_id', 'id');
    }
    public function employee() {
        return $this->hasOne(Employee::class, 'account_id', 'id');
    }
}
