<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $fillable = ['user_id', 'event_id','stripe_session_id', 'total_price', 'payment_status', 'paid_at'];

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
