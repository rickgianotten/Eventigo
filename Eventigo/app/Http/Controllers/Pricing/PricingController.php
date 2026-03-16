<?php

namespace App\Http\Controllers\Pricing;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PricingController extends Controller
{
    public function index(){
        $plans = PricingPlan::all();
        return view('pricing.index', ['plans' => $plans]);
    }

    public function store(Request $request){
        
        $validatedValue = $request->validate([
            'chosen_plan' => ['required', Rule::in(['free','premium_monthly','premium_yearly'])]
        ]);
        
        session(['chosen_plan' => $validatedValue['chosen_plan']]);

        return redirect()->route('auth.host.create');
    }
}
