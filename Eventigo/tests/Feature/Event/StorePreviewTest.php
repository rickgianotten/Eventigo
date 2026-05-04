<?php

use App\Models\Company;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PricingPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

        'event_image' => 'images/events/defaults/art.jpg' //required to pass validation or image_upload
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

test('can store preview in the session of a free event', function(){
    $event = $this->startEventData;

    $event['free_event'] = 'on';
    $event['max_amount_of_visitors'] = '120';

    $this->actingAs($this->user)->post(route('events.create.storePreview'), $event)->assertRedirectToRoute('events.create.showPreview');

    expect(session('eventData.free_event'))->toBe('on');
    expect(session('eventData.max_amount_of_visitors'))->toBe('120');
             
});

test('can store preview in the session with default image', function(){
    $event = $this->startEventData;

    // making it free only for passing validation
    $event['free_event'] = 'on';
    $event['max_amount_of_visitors'] = '120';

    $this->actingAs($this->user)->post(route('events.create.storePreview'), $event)->assertRedirectToRoute('events.create.showPreview');

    expect(session('eventData'))
    ->toHaveKey('image_path', $this->startEventData['event_image'])
    ->toHaveKey('image_from_upload', false)
    ->not->toHaveKey('image_upload');
});

test('can store preview in the session with uploaded image', function(){
    $event = $this->startEventData;

    // making it free only for passing validation
    $event['free_event'] = 'on';
    $event['max_amount_of_visitors'] = '120';

    Storage::fake('events');

    $uploadedImage = UploadedFile::fake()->image('events_image.jpg');

    $event['image_upload'] = $uploadedImage;

    $this->actingAs($this->user)->post(route('events.create.storePreview'), $event)->assertRedirectToRoute('events.create.showPreview');

    expect(session('eventData'))
        ->toHaveKey('image_from_upload', true)
        ->toHaveKey('image_path', $uploadedImage->hashName())
        ->not->toHaveKey('image_upload');
    
    Storage::disk('events')->assertExists($uploadedImage->hashName());
});