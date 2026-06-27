<?php

namespace App\Http\Controllers\Checkout;

use App\Actions\Checkout\CreateCheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function store(CreateCheckoutRequest $request, CreateCheckoutAction $action){
        $user = Auth::user();

        return $action->handle($user, $request->validated('tickets'));
    }

    public function succes(Request $request): View{
        return view('checkout.succes');
    }

    public function cancel(Request $request): View{
        return view('checkout.cancel');
    }
}
