<?php

use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Actions\Event\StoreEventConcept;
use Illuminate\Support\Carbon;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $this->company = Company::factory()->create(['pricing_plan_id' => '2']);
    $this->user = User::find($this->company->user_id);
    $this->category = Category::first();
    //only event data without tickets or participants
    $this->event =  [
        'title' => 'test event',
        'category' => $this->category->slug,
        'short_description' => 'short test description',
        'long_description' => 'long test description',

        'location' => 'The Netherlands',
        'city' => 'Zwolle',
        'street' => 'test street 72',
        'postal_code' => '8902HD',

        'start_date'        => now()->format('Y-m-d H:i:s'),
        'end_date'          => now()->addDays(7)->format('Y-m-d H:i:s'),
        'start_time'        => now()->format('H:i'),
        'end_time'          => now()->format('H:i'),

        'event_image' => 'images/events/defaults/art.jpg' //can be image_upload
    ];

    //expected event in the database
    $this->expectedEvent = array_merge($this->event, 
    [
        'category_id' => $this->category->id,
        'slug' => Str::slug($this->event['title']),
        'status' => 'concept',
        'start_time' => Carbon::parse($this->event['start_time'])->format('Y-m-d H:i:s'),
        'end_time' => Carbon::parse($this->event['end_time'])->format('Y-m-d H:i:s'),
        'image_path' => $this->event['event_image'],
    ]);

    //db expect category_id
    unset($this->expectedEvent['category']);
    //db expect only image_path
    unset($this->expectedEvent['event_image']);
    
    //event data as sent from the form
    $this->requestEventData = array_merge($this->event, [
        'action' => 'concept', 
        'participants' => [
            [
                "name" => null,
                "email" => null,
                "role" => "artist",
            ]
        ],
        'tickets' => [
            [
                "type" => "Regular",
                "price" => null,
                "quantity_available" => null,
                "description" => null,           
            ]
        ]
        ]);
});

//without tickets and participants
test('can store event as concept with complete information',function(){
    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);
    assertDatabaseHas('events',$this->expectedEvent);
});

test('can store event as concept without title',function(){
    $this->requestEventData['title'] = '';

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    assertDatabaseHas('events', array_merge($this->expectedEvent, ['title' => null, 'slug' => ''] ));

});

test('can store event concept without category', function(){
    unset($this->requestEventData['category']);
    $this->expectedEvent['category_id'] = null;

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    assertDatabaseHas('events', $this->expectedEvent);
});

test('can store event as concept without', function(string $missingfield){ 
    $this->expectedEvent[$missingfield] = null;
    $this->requestEventData[$missingfield] = '';

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    assertDatabaseHas('events',$this->expectedEvent);
})->with([
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
]);

test('can store tickets for an event concept',function(){
    $tickets = [
        [
            "type" => 'VIP',
            'price' => '75.00',
            'description' => 'Free food & drinks',
            'quantity_available' => '60'
        ],
        [
            "type" => 'Regular',
            'price' => '35.00',
            'description' => '',
            'quantity_available' => '120'
        ]
    ];
    $this->requestEventData['tickets'] = $tickets;

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    foreach($tickets as $ticket){
        assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'price' => (float)$ticket['price'],
            'description' => $ticket['description'] ?: null,
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
    $tickets[0][$missingfield] = null;
    
    $this->requestEventData['tickets'] = $tickets;

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    foreach($tickets as $ticket){
        assertDatabaseHas('tickets', [
            'event_id' => $event->id,
            'price' => $ticket['price'] !== null
                ? (float) $ticket['price']
                : null,
            'description' => $ticket['description'],
            'quantity_available' => $ticket['quantity_available']
        ]);
    }
    
})->with([
    'price',
    'description',
    'quantity_available'
]);

test('can store free event', function(){
    unset($this->requestEventData['tickets']);
    $this->requestEventData['free_event'] = 'on';
    $this->requestEventData['max_amount_of_visitors'] = '100';

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    assertDatabaseHas('tickets', [
        'event_id' => $event->id,
        'type' => 'Free',
        'quantity_available' => $this->requestEventData['max_amount_of_visitors']
    ]);

});

test('can store free event without max amount of visitors', function(){
    unset($this->requestEventData['tickets']);
    $this->requestEventData['free_event'] = 'on';
    $this->requestEventData['max_amount_of_visitors'] = '';

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();

    assertDatabaseHas('tickets', [
        'event_id' => $event->id,
        'type' => 'Free',
        'quantity_available' => null
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

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

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

    $participants[0][$missingfield] = null;

    $this->requestEventData['participants'] = $participants;

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    foreach($participants as $participant){
        assertDatabaseHas('participants',[
            'name' => $participant['name'],
            'email' => $participant['email'],
            'role' => $participant['role'],
        ]);
    }
})->with([
    'name',
    'email'
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

    $respone = $this->actingAs($this->user)->post(route('events.create.storePreview'), $this->requestEventData);
    $respone->assertJson(['message' => 'concept saved!']);

    $event = Event::where('slug', $this->expectedEvent['slug'])->firstOrFail();
    $participants = $event->participants;

    foreach($participants as $participant){
        assertDatabaseHas('event_participant', [
        'event_id' => $event->id,
        'participant_id' => $participant->id
    ]);
    }
});
