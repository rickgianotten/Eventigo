<?php

namespace App\Http\Middleware\Checkout;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCheckoutCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!session('checkout_completed')){
            return redirect()->route('home');
        }
        session()->forget('checkout_completed');
        
        return $next($request);
    }
}
