<?php

namespace App\Http\Controllers\Checkout;

use App\Actions\Checkout\CreateCheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCheckoutRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function store(CreateCheckoutRequest $request, CreateCheckoutAction $action){
        $user = Auth::user();

        try {
            $result = $action->handle($user, $request->validated('tickets'));
            
            if($result instanceof Order){
                return redirect()->route('checkout.succes');
            }

            return $result;
            
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    public function succes(Request $request): View{
        return view('checkout.succes');
    }

    public function cancel(Request $request): View{
        return view('checkout.cancel');
    }
}
