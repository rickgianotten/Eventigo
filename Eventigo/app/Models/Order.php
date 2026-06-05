<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public $fillable = ['user_id', 'event_id','stripe_session_id', 'total_price', 'payment_status', 'paid_at'];

    public function orderItems():HasMany{
        return $this->hasMany(OrderItem::class);
    }

    public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }
}
