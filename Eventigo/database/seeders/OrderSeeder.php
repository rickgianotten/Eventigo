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
        Event::inRandomOrder()->take(5)->get()->each(function ($event){
            $tickets = $event->tickets;

            Order::factory(3)->create(['event_id' => $event->id])->each(function ($order) use ($tickets) {
                foreach ($tickets as $ticket){
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'ticket_id' => $ticket->id,
                        'unit_price' => $ticket->price
                    ]);            
                }
                app(CreateOrderTicketsAction::class)->handle($order);
            });
        });
    }
}
