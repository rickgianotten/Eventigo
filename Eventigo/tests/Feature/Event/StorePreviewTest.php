<?php

use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed([PricingPlanSeeder::class, CategorySeeder::class]);
    $company = Company::factory()->create(['pricing_plan_id' => '2']);
    $this->user = User::find($company->user_id);
    $this->startEventData = [
        'action' => 'preview',
        'title' => 'test event',
        'category' => 'art',
        'short_description' => 'short test description',

        'location' => 'The Netherlands',
        'city' => 'Zwolle',
        'street' => 'test street 72, 8902HD',

        'start_date' => now(),
        'end_date' => now()->addDays(7),

        'start_time' => '10:00',
        'end_time' => '12:00',

        'participants' => [
            [
            'name' => 'test participant',
            'email' => 'test.participant@gmail.com',
            'role' => 'artist'
            ]
        ],

        'event_image' => 'images/events/defaults/art.jpg'//required to pass validation or image_upload
    ];
});

test('can store preview in the session of an event with tickets', function(){
    $tickets =[
        [
        'type' => 'Regular',
        'price' => '60.00',
        'quantity_available' => '100'
        ],
        [
        'type' => 'VIP',
        'price' => '120.00',
        'quantity_available' => '30',
        'description' => "Free drinks"
        ]
    ];
    $event = array_merge(['tickets' => $tickets], $this->startEventData);
    $response = $this->actingAs($this->user)->post(route('events.create.storePreview'), $event);

    $response->assertRedirectToRoute('events.create.showPreview')->assertSessionHas('eventData.tickets', $tickets);
});
