<?php

use App\Actions\Checkout\CreateCheckoutAction;
use App\Actions\Checkout\CreateOrderAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderPaid;
use App\Exceptions\Checkout\CheckoutException;
use App\Exceptions\Checkout\NotEnoughTicketsException;
use App\Exceptions\Checkout\TicketSoldOutException;
use App\Models\Event as ModelsEvent;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Checkout;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([CategorySeeder::class, PricingPlanSeeder::class]);
    $this->user = User::factory()->create();
    $this->event = ModelsEvent::factory()->create();
    $this->createOrderAction = Mockery::mock(CreateOrderAction::class);
    app()->instance(CreateOrderAction::class, $this->createOrderAction);
});

afterEach(function(){
    Mockery::close();
});

it('throws a NotEnoughTicketsException when there are not enough tickets available', function(){
    $fakeTicket = Ticket::factory()->for($this->event)->create(['quantity_available' => 10,'quantity_sold' => 0]);
    $tickets = [
        [
        'ticket_id' => $fakeTicket->id,
        'ticket_quantity' => 11,
        ]
    ];

    $this->createOrderAction->shouldNotReceive('handle');

    app(CreateCheckoutAction::class)->handle($this->user, $tickets);

})->throws(NotEnoughTicketsException::class, 'Not enough tickets available!');

it('throws a CheckoutException when checkout throws an exception', function(){
    $userMock = Mockery::mock($this->user);

    $fakeTickets = Ticket::factory(2)->for($this->event)->create(['quantity_available' => '100','quantity_sold' => '0', 'price' => '200']);

    $order = Order::factory()->for($this->event)->create(['payment_status' => OrderStatus::Pending]);

    $this->createOrderAction->shouldReceive('handle')->once()->andReturn($order);

    $tickets = $fakeTickets->map(function ($ticket) {
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '2'
        ];
    })->toArray();

    $userMock->shouldReceive('checkout')->andThrow(new Exception('Stripe is down!'));

    app(CreateCheckoutAction::class)->handle($userMock, $tickets);

    expect(session('checkout_completed'))->toBeNull()
    ->and(session('order_id'))->toBeNull();

    assertDatabaseHas('orders',[
        'id' => $order->id,
        'payment_status' => OrderStatus::Failed
    ]);

})->throws(CheckoutException::class, 'Oops, something went wrong at checkout. Please try again.');

it('throws a TicketSoldOutException when a ticket is sold out', function(){
    $fakeTicket = Ticket::factory()->for($this->event)->create(['quantity_available' => '10','quantity_sold' => '10']);
    $tickets = [
        [
        'ticket_id' => $fakeTicket->id,
        'ticket_quantity' => '2',
        ]
    ];

    $this->createOrderAction->shouldNotReceive('handle');

    expect(fn() => app(CreateCheckoutAction::class)->handle($this->user, $tickets))
    ->toThrow(TicketSoldOutException::class, "Unfortunately, the “{$fakeTicket->type}” ticket is sold out!");

});

it('filter out tickets with quantity 0',function(){
    $userMock = Mockery::mock($this->user);

    $fakeTicketToFilterOut = Ticket::factory()->for($this->event)->create(['quantity_available' => '100','quantity_sold' => '0', 'price' => null]);

    $fakeTicketToBuy = Ticket::factory()->for($this->event)->create(['quantity_available' => '100','quantity_sold' => '0', 'price' => null]);

    $order = Order::factory()->for($this->event)->create(['payment_status' => OrderStatus::Pending]);

    $tickets = [
        [
            'ticket_id' => $fakeTicketToBuy->id,
            'ticket_quantity' => '3'
        ],
        [
            'ticket_id' => $fakeTicketToFilterOut->id,
            'ticket_quantity' => '0'
        ]
    ];

    $this->createOrderAction->shouldReceive('handle')->once()->withArgs(function($user, $tickets, $lockedTickets) use($fakeTicketToFilterOut,$fakeTicketToBuy ){
        $ticketIds = collect($tickets)->pluck('ticket_id');
        return $ticketIds->contains($fakeTicketToBuy->id) && $ticketIds->doesntContain($fakeTicketToFilterOut->id);
    })->andReturn($order);

    app(CreateCheckoutAction::class)->handle($userMock, $tickets);

});

it('dispatches the OrderPaid event and returns the order if there are no paid tickets',function(){
    Event::fake(OrderPaid::class);

    $fakeTickets = Ticket::factory(2)->for($this->event)->create(['price' => null, 'quantity_available' => '100','quantity_sold' => '0']);

    $order = Order::factory()->for($this->event)->create(['payment_status' => OrderStatus::Pending]);

    $tickets = $fakeTickets->map(function ($ticket) {
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '2'
        ];
    })->toArray();

    $this->createOrderAction->shouldReceive('handle')->once()->andReturn($order);

    $result = app(CreateCheckoutAction::class)->handle($this->user, $tickets);

    expect($result)->toBeInstanceOf(Order::class);

    Event::assertDispatched(OrderPaid::class, fn ($event) => $event->order->is($result));


});

it('can create a checkout with the right line items', function(){
    $userMock = Mockery::mock($this->user);

    $fakeTickets = Ticket::factory(2)->for($this->event)->create(['quantity_available' => '100','quantity_sold' => '0', 'price' => '200']);

    $fakeCheckout = Mockery::mock(Checkout::class);
    $fakeCheckout->id = 'cs_test_123';

    $order = Order::factory()->for($this->event)->create(['payment_status' => OrderStatus::Pending]);

    $tickets = $fakeTickets->map(function ($ticket) {
        return [
            'ticket_id' => $ticket->id,
            'ticket_quantity' => '2'
        ];
    })->toArray();

    $ticketIds = collect($tickets)->pluck('ticket_id');
    $lockedTickets = Ticket::whereIn('id', $ticketIds)->get()->keyBy('id');

    $expectedLineItems = collect($tickets)
    ->map(fn($ticket) =>[
        'price_data' =>[
            'currency' => 'USD',
            'unit_amount' => $lockedTickets[$ticket['ticket_id']]->price,
            'product_data' =>[
                'name' => $lockedTickets[$ticket['ticket_id']]->type,
            ]
        ],
        'quantity' => $ticket['ticket_quantity'],
    ])->values()->toArray();

    $expectedOptions = [
        'success_url' => route('checkout.succes'),
        'cancel_url' => route('checkout.cancel'),
        'metadata' => [
            'order_id' => $order->id,
        ]
    ];

    $this->createOrderAction->shouldReceive('handle')->once()->andReturn($order);

    $userMock->shouldReceive('checkout')->once()->withArgs(function (array $actueLineItems, array $actualOptions) use($expectedLineItems, $expectedOptions){
        return $actueLineItems === $expectedLineItems && $actualOptions ===  $expectedOptions;
    })->andReturn($fakeCheckout);

    $result = app(CreateCheckoutAction::class)->handle($userMock, $tickets);

    expect($result)->toBeInstanceOf(Checkout::class)
    ->and($result->id)->toBe($fakeCheckout->id)
    ->and(session('checkout_completed'))->toBe(true)
    ->and(session('order_id'))->toBe($order->id);

    assertDatabaseHas('orders', [
        'id' => $order->id,
        'stripe_session_id' => $fakeCheckout->id
    ]);
});

