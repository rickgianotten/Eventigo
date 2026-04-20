<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
                    'eventFile' => $file->getFilename(),
                    'eventFilePath' => 'images/events/defaults/' . $file->getFilename(),
                    'eventType' => pathinfo($file->getFilename(), PATHINFO_FILENAME). ' event'
                    ];
            });
        });

        return view('events.create', ['categories' => $categories, 'eventImages' => $eventImages]);
    }

    public function store(Request $request){
        if($request->input('action') == "concept"){
            return dd('Save As Conept');
        }

        $user = Auth::user();

        $company = $user->company ?? $user->ownedCompany;

        // if($user->company){
        //     $company = $user->company;
        // }

        // if($user->ownedCompany){
        //     $company = $user->ownedCompany;
        // }

        $eventData = $request->session()->pull('eventData');

        $event = Arr::except($eventData, ['tickets', 'participants', 'category']);

        $category = Category::where('slug', $eventData['category'])->firstOrFail();


        $event['category_id'] = $category->id;
        $event['slug'] = Str::slug($event['title']);

        [$street, $postalcode] = explode(',', $eventData['street']);
        $event['street'] = $street;
        $event['postal_code'] = $postalcode;

        $createdEvent = DB::transaction(function() use($event, $company,$eventData ) {
            $createdEvent = $company->events()->create($event);

            if(array_key_exists('tickets', $eventData)){
                $tickets = $eventData['tickets'];
                $createdEvent->tickets()->createMany($tickets);
            }else{
                $ticket = [
                    'type' => 'Free',
                    'quantity_available' => $eventData['max_amount_of_visitors']
                ];
                $createdEvent->tickets()->create($ticket);       
            }

            foreach($eventData['participants'] as $participant){
                $participant = Participant::firstOrCreate(['email' => $participant['email']],$participant);
                $createdEvent->participants()->attach($participant);
            }

            return $createdEvent;
        });
                
        return redirect()->route('events.show',$createdEvent->slug);
    }

    public function storePreview(StoreEventRequest $request){
        $validatedValues = $request->validated();
        
        if($validatedValues['action'] == 'concept'){
            return dd($validatedValues);
        }

        if(array_key_exists('image_upload', $validatedValues)){
            $path = $request->file('image_upload')->store('', 'events');
            $validatedValues['image_path'] = $path;
            $validatedValues['image_from_upload'] = true;
            unset($validatedValues['image_upload']);
        }else{
            $validatedValues['image_path'] = $validatedValues['event_image'];
        };

        $request->session()->put('eventData', $validatedValues);
       return redirect()->route('events.create.showPreview');
    }
    
    public function showPreview(Request $request){
        $eventData = $request->session()->get('eventData');

        return view('events.preview', ['eventData' => $eventData ]);
    }
}
