<x-layout>
    <x-section.section>
        <div class="mb-6">
            <x-section.section-heading heading="Events" text="Discover {{count($events)}} events"/>
        </div>

        <div class="mb-8">
            <x-form.form class="space-y-2" action="{{route('events.index')}}">
                <div class="flex gap-2 items-center">
                    <div class="flex-grow">
                        <x-form.form-group :label="false" :required="false" name="search" placeholder="Search on location, name..."/>
                    </div>

                    <button type="submit" class="rounded-md bg-orange text-white px-6 py-1.5 cursor-pointer hover:opacity-75">
                        search
                    </button>
                </div>

                {{-- filter options --}}
                <div x-data="{open: false}" class="space-y-5">

                    <button type="button" @click="open = !open" class="flex items-center gap-1 border border-light-grey rounded-md text-white px-3 py-1 cursor-pointer hover:opacity-75 hover:border-orange">
                        <x-icons.filter-icon/> Filters
                    </button>

                    <div x-show="open"   
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2">
                        <x-cards.card>
                            <div class="grid gap-5 py-6 px-4  md:grid-cols-3 ">
                                <div>
                                    <h4 class="text-white flex items-center gap-1 mb-3"><x-icons.category-icon class="text-orange"/>Category</h4>
                                    <div class="flex gap-2 flex-wrap">
                                        <x-form.radio-button value="" id="all_categories" name="category" :selected="true">
                                            all
                                        </x-form.radio-button>
                                        @foreach ($categories as $category)
                                            <x-form.radio-button :value="$category->id" :id="$category->id" name="category">
                                                {{$category->name}}
                                            </x-form.radio-button>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-white flex items-center gap-1 mb-3"><x-icons.location-icon class="text-orange"/>City</h4>
                                    <div class="flex gap-2 flex-wrap">
                                        <x-form.radio-button value="" id="all_locations" name="location" :selected="true">
                                            all
                                        </x-form.radio-button>
                                        @foreach ($locations as $location)
                                            <x-form.radio-button :value="$location" :id="$location" name="location">
                                                {{$location}}
                                            </x-form.radio-button>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-white flex items-center gap-1 mb-3"><x-icons.calender-icon class="text-orange"/>Date</h4>
                                    <div class="flex gap-2 flex-wrap">
                                        <x-form.radio-button value="" id="all_dates" name="date" :selected="true">
                                            all
                                        </x-form.radio-button>
                                        @foreach ($filterDates as $date)
                                            <x-form.radio-button :value="$date" :id="$date"  name="date">
                                                {{$date}}
                                            </x-form.radio-button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </x-cards.card>
                    </div>
                </div>

            </x-form.form>
        </div>

        <x-cards.events-card-grid>
            @foreach ($events as $event)
                <x-cards.event :event="$event"/>
            @endforeach
        </x-cards.events-card-grid>


        <div class="mt-5">
            {{$events->links()}}
        </div>

    </x-section.section>
</x-layout>