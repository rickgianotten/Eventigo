<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket_purchase extends Model
{
    /** @use HasFactory<\Database\Factories\TicketPurchasesFactory> */
    use HasFactory;
    public $fillable = ['quantity','total_price','status','qr_code'];

    public function ticket():BelongsTo{
        return $this->belongsTo(Ticket::class);
    }

}
