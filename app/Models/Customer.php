<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['id', 'account_id', 'address_id', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function account() {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function address() {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
