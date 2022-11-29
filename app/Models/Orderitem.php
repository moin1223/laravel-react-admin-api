<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Orderitem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_name',
        'product_price',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
