<?php

use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use function Pest\Laravel\assertDatabaseHas;
pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->company = Company::factory()->create(['pricing_plan_id' => '2']);
    $this->user = User::find($this->company->user_id);
    $this->startEventData = [
        'action' => 'store',
        'title' => 'test event',
        'category' => Category::first()->slug,
        'short_description' => 'short test description',

        'location' => 'The Netherlands',
        'city' => 'Zwolle',
        'street' => 'test street 72',
        'postal_code' => '8902HD',
        
        'start_date'        => now()->format('Y-m-d H:i:s'),
        'end_date'          => now()->addDays(7)->format('Y-m-d H:i:s'),
        'start_time'        => now()->format('Y-m-d') . ' 10:00:00',
        'end_time'          => now()->format('Y-m-d') . ' 12:00:00',

        'participants' => [
            [
            'name' => 'test participant',
            'email' => 'test.participant@gmail.com',
            'role' => 'artist'
            ],
            [
            'name' => 'test participant 1',
            'email' => 'test.participant1@gmail.com',
            'role' => 'speaker'
            ]
        ],
        
        'free_event' => 'on',
        'max_amount_of_visitors' => '100',

        'image_path' => 'images/events/defaults/art.jpg' 
    ];

    $this->eventSession = ['eventData' => $this->startEventData];
});

test('can store event in database',function(){

    $eventSlug = Str::slug($this->startEventData['title']);

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));
    assertDatabaseHas('events',[
        'status' => 'online',
        'title' => 'test event',
        'slug' => $eventSlug,
        'short_description' => 'short test description',

        'location' => 'The Netherlands',
        'city' => 'Zwolle',
        'street' => 'test street 72',
        'postal_code' => '8902HD',

        'start_date'        => now()->format('Y-m-d H:i:s'),
        'end_date'          => now()->addDays(7)->format('Y-m-d H:i:s'),
        'start_time'        => now()->format('Y-m-d') . ' 10:00:00',
        'end_time'          => now()->format('Y-m-d') . ' 12:00:00',

        
        'image_path' => 'images/events/defaults/art.jpg' 
    ]);
});

test('can store event tickets',function(){
    $eventSlug = Str::slug($this->startEventData['title']);

    $tickets = [
        [
            "type" => 'VIP',
            'price' => '75,00',
            'description' => 'Free food & drinks',
            'quantity_available' => '60'
        ],
        [
            "type" => 'Regular',
            'price' => '35,00',
            'description' => '',
            'quantity_available' => '120'
        ]
    ];

    $this->startEventData['tickets'] = $tickets;

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));

    $event = Event::where('slug', $eventSlug)->firstOrFail();

    foreach($tickets as $ticket){
        assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'price' => $ticket['price'],
            'description' => $ticket['description'],
            'quantity_available' => $ticket['quantity_available']
        ]);
    }

});

test('can store event thats free',function(){
    $eventSlug = Str::slug($this->startEventData['title']);

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));

    $event = Event::where('slug', $eventSlug)->firstOrFail();

    assertDatabaseHas('tickets', [
        'event_id' => $event->id,
        'type' => 'Free',
        'quantity_available' => $this->startEventData['max_amount_of_visitors']
    ]);
});

test('can store participants', function(){
    $eventSlug = Str::slug($this->startEventData['title']);

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));

    foreach($this->startEventData['participants'] as $participant){
        assertDatabaseHas('participants',[
            'name' => $participant['name'],
            'email' => $participant['email'],
            'role' => $participant['role'],
        ]);
    }
});

test('can link event to participants', function(){
    $eventSlug = Str::slug($this->startEventData['title']);

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));

    $event = Event::where('slug', $eventSlug)->firstOrFail();

    $participants = $event->participants;

    foreach($participants as $participant){
        assertDatabaseHas('event_participant', [
        'event_id' => $event->id,
        'participant_id' => $participant->id
    ]);
    }

});

test('can link event to company', function(){
    $eventSlug = Str::slug($this->startEventData['title']);

    $this->actingAs($this->user)->withSession(['eventData' => $this->startEventData])->post(route('events.store'))->assertRedirect(route('events.show',$eventSlug));

    $event = Event::where('slug', $eventSlug)->firstOrFail();

    expect($event->company_id)->toBe($this->company->id);
});

