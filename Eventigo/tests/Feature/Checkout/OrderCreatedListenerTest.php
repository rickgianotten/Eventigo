<?php

use App\Actions\Checkout\CreateTicketsAction;
use App\Enums\Order\OrderStatus;
use App\Events\Checkout\OrderCreated;
use App\Listeners\Checkout\OrderCreatedListener;
use App\Mail\OrderConfirmationMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create();
    $this->order = Order::factory()->for($this->user)->create(['event_id' => $this->event->id, 'payment_status' => OrderStatus::Paid]);
    $this->mockedCreateTicketsAction = $this->mock(CreateTicketsAction::class);
});

it('calls CreateTicketsAction with the order from the event',function(){
    Mail::fake();

    $this->mockedCreateTicketsAction->shouldReceive('handle')->once()->withArgs(function($order){
        return $order->is($this->order);
    });

    app(OrderCreatedListener::class)->handle(new OrderCreated($this->order));

});

it('send the OrderConfirmationMail with the order from the event and the qr-codes',function(){
    Mail::fake();

    $fakeQrCodes = [
        [
            'ticket_code' => (string)Str::uuid(),
            'svg' => '<svg>fake-1</svg>'
        ],
        [
            'ticket_code' => Str::uuid(),
            'svg' => '<svg>fake-2</svg>'        
        ]
    ];

    $this->mockedCreateTicketsAction->shouldReceive('handle')->once()->andReturn($fakeQrCodes);

    app(OrderCreatedListener::class)->handle(new OrderCreated($this->order));

    Mail::assertQueued(OrderConfirmationMail::class, function(OrderConfirmationMail $mail) use($fakeQrCodes){
        return $mail->order->is($this->order) && $mail->qrCodes === $fakeQrCodes && $mail->hasTo($this->user->email);
    });
    

});