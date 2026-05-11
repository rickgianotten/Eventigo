<?php

use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
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

    //only event data without tickets or participants
    $this->event =  [
        'title' => 'test event',
        'category' => Category::first()->slug,
        'short_description' => 'short test description',
        'long_description' => '',

        'location' => 'The Netherlands',
        'city' => 'Zwolle',
        'street' => 'test street 72',
        'postal_code' => '8902HD',

        'start_date'        => now()->format('Y-m-d H:i:s'),
        'end_date'          => now()->addDays(7)->format('Y-m-d H:i:s'),
        'start_time'        => now()->format('Y-m-d') . ' 10:00:00',
        'end_time'          => now()->format('Y-m-d') . ' 12:00:00',

        'image_path' => 'images/events/defaults/art.jpg' 
    ];

    //expected event in the database
    $this->expectedEvent = array_merge($this->event, 
    [
        'slug' => Str::slug($this->event['title']),
        'status' => 'concept'
    ]);

    //event data as sent from the form
    $this->requestEventData = array_merge($this->event, ['action' => 'concept']);
});

//without tickets and participants
test('can store event as concept with complete information',function(){

    $this->actingAs($this->user)->post(route('events.store',$this->requestEventData));

    assertDatabaseHas('events',$this->expectedEvent);
});

test('can store event as concept without title',function(){
    $this->requestEventData['title'] = '';

    $this->actingAs($this->user)->post(route('events.store', $this->requestEventData));

    assertDatabaseHas('events', array_merge(['title' => '', 'slug' => ''], $this->expectedEvent));

});

test('can store event as concept without', function(string $missingfield){ 
    $this->expectedEvent[$missingfield] = '';
    $this->requestEventData[$missingfield] = '';

    $this->actingAs($this->user)->post(route('events.store', $this->requestEventData));

    assertDatabaseHas('events',$this->expectedEvent);
})->with([
        'category',
        'short_description',
        'long_description',

        'location',
        'city',
        'street',
        'postal_code',

        'start_date',
        'end_date',
        'start_time',
        'end_time',

        'image_path'
]);

test('can store tickets for an event concept',function(){
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
    $this->requestEventData['tickets'] = $tickets;

    $this->actingAs($this->user)->post(route('events.store'), $this->requestEventData);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    foreach($tickets as $ticket){
        assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'price' => $ticket['price'],
            'description' => $ticket['description'],
            'quantity_available' => $ticket['quantity_available']
        ]);
    }
});

test('can store ticket without', function(string $missingfield){
    $tickets = [
        [
        "type" => 'VIP',
        'price' => '75,00',
        'description' => 'Free food & drinks',
        'quantity_available' => '60'
    ]
    ];
    $tickets[0][$missingfield] = '';
    
    $this->requestEventData['tickets'] = $tickets;

    $this->actingAs($this->user)->post(route('events.store'), $this->requestEventData);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    foreach($tickets as $ticket){
        assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'price' => $ticket['price'],
            'description' => $ticket['description'],
            'quantity_available' => $ticket['quantity_available']
        ]);
    }
    
})->with([
    'type',
    'price',
    'description',
    'quantity_available'
]);

test('can store free event', function(){
    $this->requestEventData['free_event'] = 'on';
    $this->requestEventData['max_amount_of_visitors'] = '100';

    $this->actingAs($this->user)->post(route('events.store'), $this->requestEventData);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    assertDatabaseHas('tickets', [
        'event_id' => $event->id,
        'type' => 'Free',
        'quantity_available' => $this->requestEventData['max_amount_of_visitors']
    ]);

});

test('can store particpants for an event concept',function(){
    $participants = [
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
    ];

    $this->requestEventData['participants'] = $participants;

    $this->actingAs($this->user)->post(route('events.store'),$this->requestEventData);

    foreach($participants as $participant){
        assertDatabaseHas('participants',[
            'name' => $participant['name'],
            'email' => $participant['email'],
            'role' => $participant['role'],
        ]);
    }
});

test('can store participants without', function(string $missingfield){
    $participants = [
        [
            'name' => 'test participant',
            'email' => 'test.participant@gmail.com',
            'role' => 'artist'
        ]
    ];

    $participants[0][$missingfield] = '';

    $this->requestEventData['participants'] = $participants;

    $this->actingAs($this->user)->post(route('events.store'),$this->requestEventData);

    foreach($participants as $participant){
        assertDatabaseHas('participants',[
            'name' => $participant['name'],
            'email' => $participant['email'],
            'role' => $participant['role'],
        ]);
    }
})->with([
    'name',
    'email',
    'role'
]);

test('can link event to participants', function(){

    $participants = [
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
    ];

    $this->requestEventData['participants'] = $participants;

    $this->actingAs($this->user)->post(route('events.store'),$this->requestEventData);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    $participants = $event->participants;

    foreach($participants as $participant){
        assertDatabaseHas('event_participant', [
        'event_id' => $event->id,
        'participant_id' => $participant->id
    ]);
    }
});

