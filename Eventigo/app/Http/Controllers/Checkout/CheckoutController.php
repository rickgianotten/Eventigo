<?php

namespace App\Http\Controllers\Checkout;

use App\Actions\Checkout\CreateCheckoutAction;
use App\Enums\OrderStatus;
use App\Exceptions\CheckoutException;
use App\Exceptions\NotEnoughTicketsException;
use App\Http\Controllers\Controller;
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
                session(['checkout_completed' => true]);
                return redirect()->route('checkout.succes')->with('order_id', $result->id);
            }

            return $result;
            
        } catch (NotEnoughTicketsException $e) {
            return back()->with('info', $e->getMessage());
        } catch(CheckoutException $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function succes(Request $request): View{

        $order = Order::findOrFail(session('order_id'));

        session()->forget('order_id');

        return view('checkout.succes', ['order' => $order]);
    }

    public function cancel(Request $request): View{
        $order = Order::findOrFail(session('order_id'));

        $order->update(['payment_status' => OrderStatus::Cancelled]);

        session()->forget('order_id');

        return view('checkout.cancel', ['order' => $order]);
    }
}
