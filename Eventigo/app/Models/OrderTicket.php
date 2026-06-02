<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model
{
    public $fillable = ['order_item_id', 'event_id', 'ticket_code', 'status', 'valid_from', 'valid_until', 'user_at'];
}
