<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketsFactory> */
    use HasFactory;

    public $fillable = ['type', 'price', 'description','quantity_available', 'quantity_sold'];

    public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }

}
