<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    public $fillable = ['type', 'price', 'description','quantity_available', 'quantity_sold'];

    public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }

    public function price(bool $inCents = false): int|string
    {
        $cents = $this->price;
        
        return $inCents ? $cents : number_format($this->price/ 100, 2, '.', ',');
    }

    public function available():int{
        return $this->quantity_available - $this->quantity_available;
    }

    protected $casts = [
        'price' => 'integer',
    ];

}
