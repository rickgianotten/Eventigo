<x-layout>
    <section class="bg-cover bg-center h-[50vh] relative mb-20 md:mb-10 px-4" style="background-image: url('{{ asset('images/hero.jpeg') }}')">
            {{-- overlay --}}
            <div class="absolute inset-0 bg-black/55"></div>

            {{-- hero text --}}
            <div class="relative z-10 pt-10 container mx-auto">
                <div class="mb-10">
                    <h2 class="text-white text-5xl mb-4">
                        discover your <br>
                        <span class="text-orange"> next</span> <span style="background: var(--gradient-hero); background-clip: text; color: transparent;">experience</span>
                    </h2>
                    <p class="text-light-grey text-1xl">
                        From lively festivals to intimate comedy shows — <br>
                        find and book the best events in your area.
                    </p>
                </div>

                <div class="md:w-fit">
                    <x-cards.card>
                        <div class="px-2  py-4">
                            <form action="/search" class="grid gap-4 md:grid-cols-4 " method="GET">
                                <div class=" flex items-center gap-2">
                                    <label for="event" class="flex items-center"><x-icons.search-icon/></label>
                                    <input type="text" name="event" id="event" placeholder="search event" class="text-white">
                                </div>

                                <div class=" flex items-center gap-2">
                                    <label for="location" class="flex items-center"><x-icons.location-icon/></label>
                                    <input type="text" name="location" id="location" placeholder="location" class="text-white">
                                </div>

                                <div class=" flex items-center gap-2">
                                    <label for="date" class="flex items-center"><x-icons.calender-icon/></label>
                                    <input type="date" name="date" id="date" class="text-light-grey color-light-grey">
                                </div>
                                
                                <x-form.button>Search</x-form.button>
                            </form>
                        </div>

                    </x-cards.card>
                </div>

            </div>

    </section>

    <section class="mb-15 container mx-auto px-4">
            <div class="flex gap-5">
                <div>
                    <h3 class="text-white font-bold text-2xl">1K+</h3>
                    <p class="text-light-grey">Events</p>
                </div>

                <div>
                    <h3 class="text-white font-bold text-2xl">100K+</h3>
                    <p class="text-light-grey">Visitors</p>
                </div>

                <div>
                    <h3 class="text-white font-bold text-2xl">200+</h3>
                    <p class="text-light-grey">Organizations</p>
                </div>
            </div>
    </section>

    <section class="mb-15 container mx-auto px-4">
        <div class="mb-10">
            <x-section.section-heading>
                <x-slot:heading>Explore Categories</x-slot:heading>
                <x-slot:text>Discover events based on your interests</x-slot:text>
            </x-section.section-heading>
        </div>

            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-6">
                @foreach ($categories as $category)
                    <x-cards.category :category="$category"/>
                @endforeach
            </div>
    </section>

    <section class=" mb-15 container mx-auto px-4">
        <div class="mb-10 flex justify-between items-center">
            <x-section.section-heading>
                <x-slot:heading>Featured Events</x-slot:heading>
                <x-slot:text>The most popular events right now</x-slot:text>
            </x-section.section-heading>
            <a href="{{route('events.index')}}" class="text-orange flex items-center gap-1 hidden md:inline-flex hover:text-white">View all events<x-icons.arrow-right/></a>
        </div>
        <div class="mb-8">
            <x-cards.events-card-grid>
                @foreach($events as $event)
                    <x-cards.event :event="$event"/>
                @endforeach
            </x-cards.events-card-grid>
        </div>
        <x-nav-button class=" flex items-center gap-1 md:hidden" href="{{route('events.index')}}">View all events<x-icons.arrow-right/></x-nav-button>
    </section>

    <section class="container mx-auto px-4">
            <div class="flex flex-col items-center text-center gap-4 py-12 rounded-xl" style="background: var(--gradient-button)">
                <div class="bg-white/20 px-4 py-2 rounded-4xl flex gap-2 items-center">
                    <span class="material-symbols-outlined text-white">star_shine</span>
                    <p class="text-white text-sm">for organisations</p>
                </div>


                <h3 class="text-white text-5xl">
                    Organise your <br>
                    event with Eventigo
                </h3>

                <p class="text-white/70">
                    Reach thousands of visitors and manage your <br>
                    ticket sales effortlessly.
                </p>

                <x-nav-button href="{{route('pricing.index')}}" class="bg-black border-none flex items-center gap-2">
                    Start for free
                    <x-icons.arrow-right/>
                </x-nav-button>
            </div>
    </section>
</x-layout>
