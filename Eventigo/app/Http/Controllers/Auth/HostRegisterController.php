<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\HostRegisterRequest;
use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HostRegisterController extends Controller
{
    public function create(){
        $plans = PricingPlan::all();

        return view('auth.register_host',['plans' => $plans]);
    }

    public function store(HostRegisterRequest $request){

        $validatedValues = $request->validated();

        $companyValues = $validatedValues['company'];

        $userValues = $validatedValues['user'];

        $user = DB::transaction(function() use ($companyValues, $userValues){
 
            $pricingPlan = PricingPlan::where('value', $companyValues['pricing_plan'])->firstOrFail();

            $companyValues['pricing_plan_id'] = $pricingPlan->id;

            $user = User::create($userValues);

            $user->ownedCompany()->create($companyValues);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('home');
    }
}
