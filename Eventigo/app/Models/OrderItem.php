<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;
    public $fillable = ['order_id', 'ticket_id', 'quantity', 'unit_price'];

    public function tickets(): HasMany{
        return $this->hasMany(OrderTicket::class);
    }
    public function ticket():BelongsTo{
        return $this->belongsTo(Ticket::class);
    }

    public function totalPrice(bool $inCents = false): float
    {
        $cents = $this->ticket->price * $this->quantity;
        
        return $inCents ? $cents : $cents / 100;
    }
}
