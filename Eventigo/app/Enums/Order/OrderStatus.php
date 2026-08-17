<?php

namespace App\Enums\Order;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Failed = 'failed';

    public static function random():self{
        return fake()->randomElement(self::cases());
    }
}
