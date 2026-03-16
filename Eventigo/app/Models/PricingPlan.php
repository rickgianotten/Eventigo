<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = ['name', 
    'price', 
    'billing_cycle', 
    'event_limit', 
    'ticket_limit',
    'user_limit',
    'features'];

    protected function casts():array
    {
        return [
            'features' => 'array',
        ];
    }
}
