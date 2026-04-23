<x-layout>
    <section class="bg-cover bg-center h-[50vh] relative px-4 mb-10" style="background-image: url('{{ $event->getEventImage() }}')">
            {{-- overlay --}}
            <div class="absolute inset-0 bg-black/55"></div>

            {{-- hero text --}}
            <div class="relative z-10 pt-15 container mx-auto">
                <div class="mb-20">
                    <a href="{{ route('events.index') }}" class="text-white text-sm flex items-center gap-1 cursor-pointer hover:text-orange"><x-icons.arrow-left/>Back</a>
                </div>

                <div class="mt-2 pl-5">
                    <x-tag>{{$event->category->name}}</x-tag>

                    <h2 class="text-white text-5xl mb-4">
                        {{$event->title}}
                    </h2>
                    <p class="text-light-grey text-1xl">
                        {{$event->description}}
                    </p>
                </div>
            </div>
    </section>
    <x-section.section>
        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                {{-- info cards --}}
                <div class="grid gap-5 mb-6 h-max sm:grid-cols-3 ">
                    <x-cards.card>
                        <div class="p-3">
                            <x-icons.calender-icon class="text-orange"/>
                            <h4 class="text-light-grey text-sm">Date</h4>
                            <p class="text-white text-sm font-bold">{{$event->start_date->format('d F Y')}}</p>
                        </div>
                    </x-cards.card>

                    <x-cards.card>
                        <div class="p-3">
                            <x-icons.time-icon class="text-orange"/>
                            <h4 class="text-light-grey text-sm">Time</h4>
                            <p class="text-white text-sm font-bold">
                                {{$event->start_time->format('H:i')}} - {{$event->end_time->format('H:i')}}
                            </p>
                        </div>
                    </x-cards.card>

                    <x-cards.card>
                        <div class="p-3">
                            <x-icons.location-icon class="text-orange"/>
                            <h4 class="text-light-grey text-sm">Location</h4>
                            <p class="text-white text-sm font-bold">{{$event->location}}</p>
                        </div>
                    </x-cards.card>
                </div>

                {{-- info --}}
                <div x-data="{selected: 'info'}">
                    <div class="bg-(--light-blue) p-2 rounded-xl mb-5">
                        <button @click="selected = 'info' " class="py-1 px-3 rounded-xl text-sm" :class="selected == 'info' ? 'text-white bg-card' : 'text-light-grey/90' ">
                            Info
                        </button>

                        <button @click="selected = 'participants' " class="py-1 px-3 rounded-xl text-sm" :class="selected == 'participants' ? 'text-white bg-card' : 'text-light-grey/90' ">
                            Participants
                        </button>

                        <button @click="selected = 'location' " class="py-1 px-3 rounded-xl text-sm" :class="selected == 'location' ? 'text-white bg-card' : 'text-light-grey/90' ">
                            Location
                        </button>
                    </div>

                    <div x-show="selected == 'info'">
                        <h2 class="text-white text-xl font-bold mb-3">About this event</h2>
                        <x-cards.card>
                            <div class="p-3 space-y-2">
                                <p class="text-light-grey text-sm">organized by</p>

                                <div class="flex items-center">
                                    <x-icons.avatar name="{{$event->company->name}}"/>
                                    <div class="flex-grow ml-4">
                                        <h3 class="text-white font-bold text-lg">{{$event->company->name}}</h3>
                                        <p class="text-light-grey text-sm flex items-center gap-1"><x-icons.star-icon/> 4.8 - {{count($event->company->events)}} events</p>
                                    </div>
                                    <x-nav-button href="/organisations/{{$event->company->slug}}">About</x-nav-button>
                                </div >
                            </div>
                        </x-cards.card>
                    </div>

                    <div x-show="selected == 'participants'">
                        <h2 class="text-white text-xl font-bold mb-3">Participants</h2>
                        <div class="grid gap-4 sm:grid-cols-2 ">
                            @foreach ($event->participants as $participant)
                                <x-cards.card>
                                    <div class="flex items-center p-3">
                                        <x-icons.avatar name="{{$participant->name}}"/>
                                        <div class="flex-grow ml-4">
                                            <h3 class="text-white font-bold text-md">{{$participant->name}}</h3>
                                        </div>
                                    </div >
                                </x-cards.card>                   
                            @endforeach
                        </div>
                    </div>

                    <div x-show="selected == 'location'">
                        <h2 class="text-white text-xl font-bold mb-3">Location</h2>

                        <x-cards.card>
                            <div class="flex items-center p-3">
                                <x-icons.location-icon class="text-orange"/>
                                <div class="flex-grow ml-4">
                                    <h3 class="text-white font-bold text-lg">{{$event->location}}</h3>
                                    <p class="text-light-grey text-sm">{{$event->street}}, {{$event->postal_code}} {{$event->city}}</p>
                                </div>
                            </div>
                        </x-cards.card>

                    </div>
                </div>
            </div>
            
            {{-- tickets --}}
            <div>
                <x-form.form method="GET" action="{{route('checkout.create')}}" class="h-full">
                    <x-cards.card class="h-full">
                        <div class="p-5 flex flex-col h-full">
                            <h3 class="text-white font-bold flex items-center gap-2 text-xl mb-3"><x-icons.ticket-icon/> Tickets</h3>

                            <div class="grid auto-rows-max space-y-6 grow">
                                @foreach ($event->tickets as $ticket)
                                <div class="ticket">
                                    <x-cards.card>
                                        <div class="p-3 space-y-2">
                                            <div class="flex">
                                                <h3 class="text-white font-bold flex-grow">{{$ticket->type}}</h3>
                                                <p class="text-white font-bold">
                                                    @if($ticket->type == "Free")
                                                        <span class="ticket_price">Free</span>
                                                    @else
                                                        $ <span class="ticket_price">{{number_format($ticket->price, 2, ',', '.')}}</span>                                                       
                                                    @endif   
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-light-grey text-xs">{{$ticket->description}}</p>
                                            </div>
                                            <div class="flex items-center">
                                                @if($ticket->quantity_available - $ticket->quantity_sold >= 12 )
                                                    <p class="text-light-grey text-xs flex-grow"> <span class="max_quantity_of_tickets">{{$ticket->quantity_available -$ticket->quantity_sold}}</span> tickets available</p>
                                                @else
                                                    <p class="text-orange text-xs flex-grow">only <span class="max_quantity_of_tickets">{{$ticket->quantity_available -$ticket->quantity_sold}}</span> tickets available!</p>
                                                @endif
                                                <div class="flex items-center gap-1.5">
                                                    <button type="button" class="text-white bg-mid-blue h-5 w-5 rounded-full flex items-center justify-center p-3.5 cursor-pointer hover:opacity-90 btn_decrease">-</button>
                                                    <x-form.input type="number" name="ticket_quantity_{{$ticket->id}}" class="bg-transparent border-none no-spinner text-center ticket_quantity ticket_quantity" value="0" min="0" max="{{$ticket->quantity_available}}"/>
                                                    <button type="button" class="text-white bg-orange h-5 w-5 rounded-full flex items-center justify-center p-3.5 cursor-pointer hover:opacity-90 btn_increase">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </x-cards.card>  
                                </div>
            
                                @endforeach
                            </div>

                            <div class="grid mt-8 border-t border-t-light-grey/20 pt-5 space-y-2">
                                <div class="flex justify-between items-center">
                                    <p class="text-light-grey text-sm"><span id="total_tickets">0</span> ticket(s)</p>
                                    <p class="text-white font-bold text-xl">$<span id="total_price">0,00</span></p>
                                </div>
                                <x-form.button class="text-lg flex items-center gap-2"> <x-icons.ticket-icon class="text-white"/> order tickets</x-form.button>
                            </div>
                        </div>
                    </x-cards.card>
                </x-form.form>
            </div>
        </div>

    </x-section.section>
</x-layout>