@props(['category'])
@php
$colors = [
    'orange' => 'bg-orange-500',
    'green'  => 'bg-green-500 ',
    'yellow' => 'bg-yellow-500 ',
    'blue'   => 'bg-blue-500 ',
    'red'   => 'bg-red-500 ',
    'purple'   => 'bg-purple-500 ',
];
@endphp

<x-cards.card :hover="true">
    <a href="/categories/{{$category->name}}">
        <div class="px-2  py-4">
            <div class="flex flex-col items-center justify-center">
                <div class="{{$colors[$category->color]}} h-14 w-14 flex justify-center items-center rounded-xl mb-2">
                    <span class="material-symbols-outlined text-black">{{$category->icon}}</span>
                </div>

                <h4 class="text-white">{{$category->name}}</h4>
                <p class="text-light-grey">{{count($category->events)}} Events</p>
            </div>
        </div>
    </a>
</x-cards.card>