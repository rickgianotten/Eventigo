<?php

use App\Models\Company;
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

test('user can create event',function(){
    $company = Company::factory()->create();
    $user = User::factory()->create([
        'company_id' => $company->id
    ]);


    actingAs($user);

    expect($user->can('create', Event::class))->toBeTrue();

});

test('company owner can create event',function(){
    $company = Company::factory()->create();
    $user = User::find($company->user_id);

    actingAs($user);

    expect($user->can('create', Event::class))->toBeTrue();

});

test('company with free pricing plan cannot save event as concept',function(){
    $pricingPlan = PricingPlan::where('value', 'free')->first();
    $company = Company::factory()->create([
        'pricing_plan_id' => $pricingPlan->id
    ]);
    $user = User::find($company->user_id);

    actingAs($user);

    expect($user->can('saveAsConcept', Event::class))->toBeFalse();

});

test('company with premium monthly pricing plan can save event as concept',function(){
    $pricingPlan = PricingPlan::where('value', 'premium_monthly')->first();
    $company = Company::factory()->create([
        'pricing_plan_id' => $pricingPlan->id
    ]);
    $user = User::find($company->user_id);

    actingAs($user);

    expect($user->can('saveAsConcept', Event::class))->toBeTrue();

});

test('company with premium yearly pricing plan can save event as concept',function(){
    $pricingPlan = PricingPlan::where('value', 'premium_yearly')->first();
    $company = Company::factory()->create([
        'pricing_plan_id' => $pricingPlan->id
    ]);
    $user = User::find($company->user_id);

    actingAs($user);

    expect($user->can('saveAsConcept', Event::class))->toBeTrue();

});
