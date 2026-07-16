<?php

namespace App\Http\Controllers\Checkout;

use App\Actions\Checkout\CreateCheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CheckoutSuccesRequest;
use App\Http\Requests\Checkout\CreateCheckoutRequest;
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
                return redirect()->route('checkout.succes')->with('order_id', $result->id);
            }

            return $result;
            
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    public function succes(CheckoutSuccesRequest $request): View{

         $order = $request->validated('session_id')
            ? Order::where('stripe_session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->firstOrFail()
            : Order::where('id', session('order_id'))
                ->where('user_id', Auth::id())
                ->firstOrFail();

        return view('checkout.succes', ['order' => $order]);
    }

    public function cancel(Request $request): View{
        return view('checkout.cancel');
    }
}
