<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{
    public function create(){
        return view('auth.register_user');
    }

    public function store(UserRegisterRequest $request){

        $validatedValues = $request->validated();

        $user = User::create($validatedValues);

        Auth::login($user);

        return redirect()->route('home');
    }
}
