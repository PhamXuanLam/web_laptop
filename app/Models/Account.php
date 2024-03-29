<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Account extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = 
    [
        "id", "username", "password", 'role',
        'first_name', 'last_name', 'birth_day', 'email',
        'phone', 'avatar', 'created_at', 'updated_at',
    ];
    
    protected $table = "accounts";

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
