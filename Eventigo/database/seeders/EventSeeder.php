<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::factory(10)->create()->each(function ($event){
            Ticket::factory(rand(1,2))->create([
                'event_id' => $event->id
            ]);
            $particapant = Participant::factory(8)->create();
            $event->participants()->attach($particapant);
        });
    }
}
