<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();
        $events = Event::with('category', 'tickets')->limit(6)->get();
        return view('landingpage.home', ['categories' => $categories, 'events' => $events]);
    }
}
