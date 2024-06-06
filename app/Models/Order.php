<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";

    protected $fillable = ['id', 'customer_id', 'employee_id', 'address_id','total', 'tax','discount','pay','status', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public $timestamps = true;

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_NOT_APPROVED = 0;
    const STATUS_PENDING_LABEL = "Pending";
    const STATUS_APPROVED_LABEL = "Approved";
    const STATUS_NOT_APPROVED_LABEL = "Not approved";

    public function getStatusLabel()
    {
        switch ($this->status) {
            case self::STATUS_NOT_APPROVED:
                return self::STATUS_NOT_APPROVED_LABEL;
                break;

            case self::STATUS_APPROVED:
                return self::STATUS_APPROVED_LABEL;
                break;

            default:
                self::STATUS_PENDING_LABEL;
                break;
        }
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, "order_id", "id");
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, "id", "customer_id");
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, "id", "employee_id");
    }
}
