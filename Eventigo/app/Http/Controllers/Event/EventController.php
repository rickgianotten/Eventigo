<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request){

        $filterDates = ['today', 'this week', 'this weekend', 'this month'];

        $validatedValues =  $request->validate([
            'search' => ['nullable'],
            'category' => ['nullable', Rule::exists('categories', 'id')],
            'location' => ['nullable',Rule::exists('events', 'location')],
            'date' => ['nullable', Rule::in($filterDates)],
        ]);

        $query = Event::query();

        if (!empty($validatedValues['category'])){
            $query->where('category_id', $validatedValues['category']);
        }

        if (!empty($validatedValues['location'])) {
            $query->where('location', 'like', '%' . $validatedValues['location'] . '%');
        }

        if (!empty($validatedValues['search'])) {
            $query->where('title', 'like', '%' . $validatedValues['search'] . '%')
            ->orWhere('city', 'like', '%' . $validatedValues['search'] . '%')
            ->orWhere('location', 'like', '%' . $validatedValues['search'] . '%');
        }        

        if (!empty($validatedValues['date'])) {
            match($validatedValues['date']) {
            'today'        => $query->whereDate('start_time', today()),
            'this week'    => $query->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()]),
            'this weekend' => $query->whereBetween('start_time', [now()->nextWeekendDay()->startOfDay(), now()->endOfWeek()]),
            'this month'   => $query->whereMonth('start_time', now()->month),
            };
        }

        $categories = Category::all();
        $locations = Event::select('location')->distinct()->pluck('location');
        $events = $query->with(['category', 'tickets'])->simplePaginate(9);

        return view('events.index', ['events' => $events, 
        'categories' => $categories, 
        'locations' =>$locations,
        'filterDates' => $filterDates
        ]);
    }

    public function show(Request $request, Event $event){
        return view('events.show', ['event' => $event]);
    }

    public function create(){
        $categories = Category::all();

        $eventImages = Cache::remember('event_default_images', 3600, function(){
            $eventFiles =  File::files('images/events/defaults');

            return collect($eventFiles)->map(function ($file) {
                return [
                    'eventFilePath' => 'images/events/defaults/' . $file->getFilename(),
                    'eventType' => pathinfo($file->getFilename(), PATHINFO_FILENAME). ' event'
                    ];
            });
        });

        return view('events.create', ['categories' => $categories, 'eventImages' => $eventImages]);
    }
}
