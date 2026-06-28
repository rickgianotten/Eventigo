<?php

use App\Actions\Checkout\CreateCheckoutAction;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([CategorySeeder::class, PricingPlanSeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->ticket = Ticket::factory()->create(['event_id'=> $this->event->id]);
});

it('throws an exception if there are not enough tickets available', function(){
    $this->ticket->update(['quantity_available' => 10]);
    $this->ticket->update(['quantity_sold' => 0]);
    $tickets = [
        [
        'ticket_id' => $this->ticket->id,
        'ticket_quantity' => 11,
        ]
    ];

    app(CreateCheckoutAction::class)->handle($this->user, $tickets);

})->throws(Exception::class, 'Not enough tickets available!');