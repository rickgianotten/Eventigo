@props(['event'])

<x-cards.card :hover="true">
    <a href="{{route('events.show', $event->slug)}}">
        <div style="background-image: url('https://content.jdmagicbox.com/comp/ernakulam/m4/0484px484.x484.140206113128.a9m4/catalogue/we-create-events-panampilly-nagar-ernakulam-event-management-companies-nsobpzm660.jpg');" class="bg-cover bg-center w-full h-90 rounded-t-lg relative">
            <div class="absolute left-2 top-1">
                <x-tag>{{$event->category->name}}</x-tag>
            </div>
        </div>
        <div class="px-6 py-4 flex flex-col gap-2">
            <h2 class="text-white text-xl font-bold group-hover:text-orange">{{$event->title}}</h2>
            <div class="flex gap-2">
                <x-icons.calender-icon class="text-orange"/>
                <p class="text-light-grey">{{$event->start_date->format('d F Y')}} • {{$event->start_time->format('H:i')}} </p>
            </div>
            <div class="flex gap-2">
                <x-icons.location-icon class="text-orange"/>
                <p class="text-light-grey">{{$event->location}}, {{$event->city}}</p>
            </div>
            <div class="flex justify-between border-t border-light-grey/20 pt-4">
                <p class="text-light-grey">From</p>
                <p class="text-white">${{number_format($event->cheapestTicketPrice(), 2, ',', '.')}}</p>
            </div>
        </div>
    </a>

</x-cards.card>