<?php

use App\Actions\Checkout\CreateCheckoutAction;
use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;


pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->ticket = Ticket::factory()->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0', 'price' => '200']);
    $this->mockedCreateCheckoutAction = $this->mock(CreateCheckoutAction::class);
});

afterEach(function(){
    Mockery::close();
});

function makeRequest(Ticket $ticket): array{
    return [
        [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '2',
        ]
    ];
}

it('redirects to succes with order_id and set checkout_completed in session when order is free', function(){
    $order = Order::factory()->for($this->user)->create(['payment_status' => OrderStatus::Paid, 'event_id' => $this->event->id]);

    $this->ticket->update(['price' => null]);

    $this->mockedCreateCheckoutAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets){
        return $user->is($this->user) && $tickets  === makeRequest($this->ticket);
    })->andReturn($order);

    $response = $this->actingAs($this->user)->post(route('checkout.store'),['tickets' => makeRequest($this->ticket)]);

    expect(session('checkout_completed'))->toBeTrue();

    $response->assertRedirect(route('checkout.succes'))
    ->assertSessionHas('order_id', $order->id);

});

it('returns the checkout response when payment is required', function(){})->todo();

it('returns back with toats when NotEnoughTicketsException is thrown',function(){})->todo();

it('returns back with toats when CheckoutException is thrown',function(){})->todo();

it('returns the succes view and forget the order_id in session',function(){})->todo();

it('returns the cancel view and update the payment_status to cancelled and forget the order_id in session',function(){})->todo();

it('validates that tickets are required', function(){})->todo();