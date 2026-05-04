<?php

namespace App\Actions\Event;

use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreEvent{
    public function handle(User $user, array $eventData){

        $company = $user->company ?? $user->ownedCompany;

        $event = Arr::except($eventData, ['tickets', 'participants', 'category']);

        $category = Category::where('slug', $eventData['category'])->firstOrFail();


        $event['category_id'] = $category->id;
        $event['slug'] = Str::slug($event['title']);

        [$street, $postalcode] = explode(',', $eventData['street']);
        $event['street'] = $street;
        $event['postal_code'] = $postalcode;

        $createdEvent = DB::transaction(function() use($event, $company,$eventData ) {
            $createdEvent = $company->events()->create($event);

            $participants = $eventData['participants'];

            $this->createTickets($eventData, $createdEvent);

            $this->createParticipants($participants, $createdEvent);

            return $createdEvent;
        });
        
        return $createdEvent;        
    }

    private function createTickets(array $eventData, Event $createdEvent){
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
    }

    private function createParticipants(array $participants, Event $createdEvent){
        foreach($participants as $participant){
            $participant = Participant::firstOrCreate(['email' => $participant['email']],$participant);
            $createdEvent->participants()->attach($participant);
        }
    }
}