<?php

namespace App\Models;

use App\Enums\Order\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;


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

    public function totalPrice(bool $inCents = false): int|string
    {
        $cents = $this->total_price;
        
        return $inCents ? $cents : number_format($this->total_price/ 100, 2, '.', ',');
    }

    public static function generateOrderNumber():string{
        return DB::transaction(function () {
            $year = now()->year;

            $lastOrder = static::where('order_number', 'like', "ORD-{$year}-%")
            ->lockForUpdate()
            ->orderByDesc('order_number')
            ->first();

            $nextNumber = $lastOrder ? ((int) substr($lastOrder->order_number, -5)) + 1 : 1;

            return sprintf('ORD-%d-%05d', $year, $nextNumber);            
        });
    }

    protected function casts():array
    {
        return [
            'payment_status' => OrderStatus::class,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($order){
            $order->order_number = static::generateOrderNumber();
        });
    }

}
