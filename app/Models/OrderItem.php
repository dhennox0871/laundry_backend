<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'service_id', 'quantity', 'subtotal',
        'washing_status', 'ironing_status', 'washer_id', 'washer_wage_amount',
        'ironer_id', 'ironer_wage_amount'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
