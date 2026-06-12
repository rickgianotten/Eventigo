<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    public $fillable = ['user_id', 'event_id','stripe_session_id', 'total_price', 'payment_status', 'paid_at'];

    public function orderItems():HasMany{
        return $this->hasMany(OrderItem::class);
    }

    public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    protected function casts():array
    {
        return [
            'payment_status' => OrderStatus::class
        ];
    }
}
