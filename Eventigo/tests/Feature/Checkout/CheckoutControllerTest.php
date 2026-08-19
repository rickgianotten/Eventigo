<?php

use App\Actions\Checkout\CreateCheckoutAction;
use App\Enums\Order\OrderStatus;
use App\Enums\toast\ToastStatus;
use App\Exceptions\Checkout\CheckoutException;
use App\Exceptions\Checkout\NotEnoughTicketsException;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Checkout;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->ticket = Ticket::factory()->for($this->event)->create(['quantity_available' => '100', 'quantity_sold' => '0', 'price' => '200']);
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id]);
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
    $this->order->update(['payment_status' => OrderStatus::Paid]);

    $this->ticket->update(['price' => null]);

    $this->mockedCreateCheckoutAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets){
        return $user->is($this->user) && $tickets  === makeRequest($this->ticket);
    })->andReturn($this->order);

    $response = $this->actingAs($this->user)->post(route('checkout.store'),['tickets' => makeRequest($this->ticket)]);

    expect(session('checkout_completed'))->toBeTrue();

    $response->assertRedirect(route('checkout.succes'))
    ->assertSessionHas('order_id', $this->order->id);

});

it('returns the checkout response when payment is required', function(){
    $mockedCheckout = Mockery::mock(Checkout::class);

    $mockedCheckout->shouldReceive('toResponse')->once()->andReturn(redirect('https://checkout.stripe.com/c/pay/cs_test_123'));

    $this->mockedCreateCheckoutAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets){
        return $user->is($this->user) && $tickets  === makeRequest($this->ticket);
    })->andReturn($mockedCheckout);

    $response = $this->actingAs($this->user)->post(route('checkout.store'),['tickets' => makeRequest($this->ticket)]);

    $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123');

});

it('returns back with toats when NotEnoughTicketsException is thrown',function(){
    $this->mockedCreateCheckoutAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets){
        return $user->is($this->user) && $tickets  === makeRequest($this->ticket);
    })->andThrow(new NotEnoughTicketsException('Not enough tickets available!'));

    $response = $this->actingAs($this->user)->post(route('checkout.store'), ['tickets' => makeRequest($this->ticket)]);

    $response->assertRedirectBack()->assertSessionHas('toast', [
        'status' => ToastStatus::Info,
        'title' => null,
        'message' => 'Not enough tickets available!'
    ]);
    
});

it('returns back with toats when CheckoutException is thrown',function(){
    $this->mockedCreateCheckoutAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets){
        return $user->is($this->user) && $tickets  === makeRequest($this->ticket);
    })->andThrow(new CheckoutException('Oops, something went wrong at checkout. Please try again.'));

    $response = $this->actingAs($this->user)->post(route('checkout.store'), ['tickets' => makeRequest($this->ticket)]);

    $response->assertRedirectBack()->assertSessionHas('toast', [
        'status' => ToastStatus::Error,
        'title' => 'Payment Failed',
        'message' => 'Oops, something went wrong at checkout. Please try again.'
    ]);    
});

it('returns the succes view and forget the order_id in session',function(){
    $response = $this->actingAs($this->user)->withSession(['order_id' => $this->order->id, 'checkout_completed' => true])->get(route('checkout.succes'));

    expect(session('order_id'))->toBeNull();

    $response->assertViewIs('checkout.succes')->assertViewHas('order', $this->order);

});

it('returns the cancel view and update the payment_status to cancelled and forget the order_id in session',function(){
    $response = $this->actingAs($this->user)->withSession(['order_id' => $this->order->id, 'checkout_completed' => true])->get(route('checkout.cancel'));

    expect(session('order_id'))->toBeNull();

    assertDatabaseHas('orders', [
        'id' => $this->order->id,
        'payment_status' => OrderStatus::Cancelled
    ]);

    $response->assertViewIs('checkout.cancel')->assertViewHas('order', $this->order);    
});

it('validates that tickets are required', function(){
    $response = $this->actingAs($this->user)->post(route('checkout.store'), ['tickets' => []]);

    $response->assertSessionHasErrors(['tickets']);

});