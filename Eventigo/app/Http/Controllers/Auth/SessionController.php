<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create(){
        return view('auth.login');
    }

    public function store(Request $request){

        $validatedValues = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)]
        ]);

        $validatedValues['email'] = Str::lower($validatedValues['email']);

        if(!Auth::attempt($validatedValues)){
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.'
            ]);
        }
        
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function destroy(){
        Auth::logout();
        return redirect()->route('home');
    }
}
