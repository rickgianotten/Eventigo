@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Event;
@endphp

<x-layout>
    <x-section.section>
        <div class=" max-w-4xl mx-auto mt-10">
            <x-section.section-heading>
                <x-slot:heading>Event Overview</x-slot:heading>
                <x-slot:text>Review your event before publishing it.</x-slot:text>
            </x-section.section-heading>
            <x-cards.card class="my-5">
                <div>
                    @if($eventData['image_from_upload'])
                        <img src="{{Storage::disk('events')->url($eventData['image_path'])}}" alt="{{$eventData['title']}}" class="rounded-lg object-cover aspect-[21/9]">
                    @else
                        <img src="{{asset($eventData['event_image'])}}" alt="{{$eventData['title']}}" class="rounded-lg object-cover aspect-[21/9]" >
                    @endif
                </div>
            </x-cards.card>

            <x-cards.card>
                <div class="p-4">
                    <h3 class="text-white font-bold text-xl">Details</h3>
                    <div class="space-y-6 mt-5">

                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.info-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">Title</h4>
                                <p class="text-white">{{$eventData['title']}}</p>
                            </div>
                        </x-cards.card>
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.category-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">Category</h4>
                                <p class="text-white">{{$eventData['category']}}</p>
                                
                            </div>
                        </x-cards.card>
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.location-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">Location</h4>
                                <p class="text-white"> {{$eventData['location']}}, {{$eventData['city']}}</p>
                                <p class="text-white">{{$eventData['street']}} {{$eventData['postal_code']}}</p>
                            </div>
                        </x-cards.card>
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.calender-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">Date</h4>
                                <p class="text-white">{{$eventData['start_date']}} {{$eventData['start_time']}} - {{$eventData['end_date']}} {{$eventData['end_time']}}</p>
                            </div>
                        </x-cards.card>
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.info-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">Short Description</h4>
                                <p class="text-white">{{$eventData['short_description']}}</p>
                            </div>
                        </x-cards.card>
                        
                        @if($eventData['long_description'])
                            <x-cards.card class="flex items-center py-1.5 px-2.5">
                                <x-icons.info-icon class="text-orange"/>
                                <div class="flex-grow pl-3">
                                    <h4 class="text-light-grey text-sm">Long Description</h4>
                                    <p class="text-white">{{$eventData['long_description']}}</p>
                                </div>
                            </x-cards.card>                
                        @endif
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.ticket-icon/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">tickets</h4>
                                @isset($eventData['tickets'])
                                    <div class="space-y-1">
                                        @foreach ($eventData['tickets'] as $ticket)
                                        <div class="flex justify-between">
                                            <p class="text-white">{{$ticket['type']}}</p>
                                            <p class="text-light-grey text-sm">${{$ticket['price']}} - {{$ticket['quantity_available']}} available</p>
                                        </div>
                                        @endforeach                                    
                                    </div>

                                @else
                                    <p class="text-white">free event - max {{$eventData['max_amount_of_visitors']}} visitors</p>
                                @endisset
                            </div>
                        </x-cards.card>
                        
                        <x-cards.card class="flex items-center py-1.5 px-2.5">
                            <x-icons.group-icon class="text-orange"/>
                            <div class="flex-grow pl-3">
                                <h4 class="text-light-grey text-sm">participants</h4>
                                <div class="space-y-1">
                                    @foreach ($eventData['participants'] as $participant)
                                    <div class="flex justify-between">
                                        <p class="text-white">{{$participant['name']}}</p>
                                        <p class="text-light-grey text-sm">{{$participant['role']}} - {{$participant['email']}}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </x-cards.card>       
                    </div>                     
                </div>
            </x-cards.card>

            <div class="flex justify-between items-center mt-5">
                <a href="{{route('events.create')}}" class="text-light-grey flex items-center gap-2 cursor-pointer hover:text-orange"><x-icons.arrow-left/>Edit Event</a>

                <x-form.form class="flex gap-3 " method="POST" action="{{route('events.store')}}">
                    @can('saveAsConcept', Event::class)
                        <button type="submit" name="action" value="concept" class="border border-light-grey/20 rounded-md text-light-grey p-2 cursor-pointer hover:opacity-75 hover:border-orange">
                            save as concept
                        </button>
                    @endcan
                    <x-form.button name="action" value="store" class="gap-1">
                        <span class="material-symbols-outlined text-white">star_shine</span>Publish your event
                    </x-form.button>
                </x-form.form> 

            </div>   
        </div>
    </x-section.section>
</x-layout>