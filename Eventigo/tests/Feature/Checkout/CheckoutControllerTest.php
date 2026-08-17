<?php

use App\Actions\Checkout\CreateCheckoutAction;
use App\Models\Event;
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
    $this->makeRequest = fn () => [
        [
            'ticket_id' => $this->ticket->id,
            'ticket_quantity' => '2',
        ]
    ];
});

afterEach(function(){
    Mockery::close();
});

it('redirects to succes with order_id and set checkout_completed in session when order is free', function(){})->todo();

it('redirects to succes when checkout succeeds', function(){})->todo();

it('redirects to cancel when checkout has been canceled', function(){})->todo();

it('returns the checkout response when payment is required', function(){})->todo();

it('returns back with toats when NotEnoughTicketsException is thrown',function(){})->todo();

it('returns back with toats when CheckoutException is thrown',function(){})->todo();

it('returns the succes view and forget the order_id in session',function(){})->todo();

it('returns the cancel view and update the payment_status to cancelled and forget the order_id in session',function(){})->todo();

it('validates that tickets are required', function(){})->todo();