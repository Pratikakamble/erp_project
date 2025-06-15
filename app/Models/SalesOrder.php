<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';
    protected $fillable = ['customer_name', 'total'];
    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

}
