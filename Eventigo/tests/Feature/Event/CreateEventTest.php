<?php

use App\Models\Event;
use App\Models\PricingPlan;
use App\Models\User;
use Database\Factories\CompanyFactory;
use Database\Seeders\PricingPlanSeeder;

use function Pest\Laravel\actingAs;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function(){
    $this->seed(PricingPlanSeeder::class);
});

test('user cannot create event',function(){
    $user = User::factory()->create();

    actingAs($user);

    expect($user->can('create', Event::class))->toBeFalse();

});

test('can create unlimited events',function(){})->todo();

test('company can save event as concept',function(){})->todo();
