<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'free',
                'event_limit' => 5,
                'ticket_limit' => 100,
                'cta' => 'Start for free',
                'user_limit' => 1,
                'features' => [
                    'Up to 5 events',
                    'Max 100 tickets',
                    'Max 1 team member',
                    'QR-code generated ticket',
                    'E-mailnotifications',
                ]
            ],
            [
                'name' => 'premium',
                'price' => 19.99,
                'billing_cycle' => 'monthly',
                'cta' => 'Upgrade to premium',
                'value' => 'premium_monthly',
                'features' => [
                    'Unlimited events',
                    'Unlimited tickets',
                    'Unlimited team members',
                    'Save your event as a concept',
                    'QR-code generated ticket',
                    'E-mailnotifications',
                    'Advanced analytics',
                    'multiple team members'
                ]
            ],
            [
                'name' => 'premium',
                'price' => 149.99,
                'billing_cycle' => 'yearly',
                'cta' => 'Upgrade to premium',
                'value' => 'premium_yearly',
                'features' => [
                    'Unlimited events',
                    'Unlimited tickets',
                    'Unlimited team members',
                    'Save your event as a concept',
                    'QR-code generated ticket',
                    'E-mailnotifications',
                    'Advanced analytics',
                    'multiple team members'
                ]
            ]
        ];

        foreach($plans as $plan){
            PricingPlan::create($plan);
        }
    }
}
