<?php

namespace Database\Seeders;

use App\Actions\Checkout\CreateOrderTicketsAction;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::inRandomOrder()->first();
        $tickets = $event->tickets;

        $order = Order::factory()->create(['event_id' => $event->id]);

        foreach ($tickets as $ticket){
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
                'unit_price' => $ticket->price
            ]);            
        }

        app(CreateOrderTicketsAction::class)->handle($order);


    }
}
