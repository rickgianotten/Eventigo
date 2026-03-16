@php
    use App\Models\Event;
@endphp
<x-layout>
    <x-section.section>
        <h2 class="text-white text-3xl font-bold">Create event</h2>
        <p class="text-light-grey">Complete the steps below to publish your event</p>
        <div x-data="{step: 1, prevStep: 0}" class="space-y-6 mt-6">
            <div class="flex justify-around items-center">
                <button @click="step = 1" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 1 && 'bg-orange'">
                    <x-icons.info-icon :class="'step == 1 ? text-white : text-light-grey'"/>
                </button>

                <div class="h-px flex-1 bg-light-grey/30"></div>

                <button @click="step = 2" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 2 && 'bg-orange'">
                    <x-icons.location-icon :class="'step == 2 ? text-white : text-light-grey'"/>
                </button>

                <div class="h-px flex-1 bg-light-grey/30"></div>

                <button @click="step = 3" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 3 && 'bg-orange'">
                    <x-icons.ticket-icon :class="'step == 3 ? text-white : text-light-grey'"/>
                </button>

                <div class="h-px flex-1 bg-light-grey/30"></div>
                
                <button @click="step = 4" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 4 && 'bg-orange'">
                    <x-icons.upload-image-icon :class="'step == 4 ? text-white : text-light-grey'"/>
                </button>

                <div class="h-px flex-1 bg-light-grey/30"></div>

                <button @click="step = 5" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 5 && 'bg-orange'">
                    <x-icons.check-icon :class="'step == 5 ? text-white : text-light-grey'"/>
                </button>
            </div>

            <x-form.form>
                <div x-show="step == 1 ">
                    <x-cards.card>
                        <div class="p-4 space-y-6">
                            <div>
                                <h2 class="text-white text-xl font-bold">Basic information</h2>
                                <p class="text-light-grey text-sm">Enter the key details of your event.</p>
                            </div>

                            <x-form.form-group type="text" name="title" placeholder=" e.g. Amserdam music festival" label="Event title" />

                            <div>
                                <h3 class="text-white mb-2">Category</h3>
                                <div class="flex gap-4">
                                    @foreach ($categories as $category)
                                        <x-form.radio-button :value="$category->name" :id="$category->name" name="category">
                                            {{$category->name}}
                                        </x-form.radio-button>
                                    @endforeach
                                </div>
                            </div>

                            <x-form.form-group type="text" name="short_description" placeholder="Max. 120 characters - shown in overview" label="Short description" />

                            <x-form.textarea type="text" name="long_description" placeholder="Tell visitors more about your event" label="Full description"/>
                        </div>
                    </x-cards.card>
                </div>

                <div x-show="step == 2 ">
                    <x-cards.card>
                        <div class="p-4 space-y-6">
                            <div>
                                <h2 class="text-white text-xl font-bold">Location & time</h2>
                                <p class="text-light-grey text-sm">Where and when is the event taking place? </p>
                            </div>

                            <x-form.form-group type="text" name="location" placeholder=" e.g. Tomorrowland " label="Location" />

                            <x-form.form-group type="text" name="city" placeholder=" e.g. Alpe d'Huez " label="City" />

                            <x-form.form-group type="text" name="street" placeholder="street number, postal code " label="Street" />

                            <x-form.form-group type="date" name="start_date" placeholder="" label="Start date" />

                            <x-form.form-group type="time" name="start_time" placeholder="" label="Start time" />

                            <x-form.form-group type="date" name="end_date" placeholder="" label="End date" />

                            <x-form.form-group type="time" name="end_time" placeholder="" label="End time" />
                        </div>
                    </x-cards.card>
                </div>

                <div x-show="step == 3 ">
                    <x-cards.card>
                        <div class="p-4 space-y-6">
                            <div>
                                <h2 class="text-white text-xl font-bold">Tickets</h2>
                                <p class="text-light-grey text-sm">Set your ticket types and prices. </p>
                            </div>

                            <div x-data="{tickets: [1] }" class="space-y-5">
                                <template x-for="(ticket, index) in tickets">
                                    <div class="border border-light-grey/20 rounded-md p-4 space-y-5">
                                        <x-form.select name="'tickets['+index+'][type]'"  label="Ticket type">
                                            <option value="Regular">Regular</option>
                                            <option value="VIP">VIP</option>
                                        </x-form.select>

                                        <x-form.form-group type="number" name="'tickets['+index+'][price]'" label="Price ($)" placeholder="75,00"/>

                                        <x-form.form-group type="number"  name="'tickets['+index+'][quantity_available]'" label="Quantity available" placeholder="100"/>

                                        <x-form.form-group type="text" name="'tickets['+index+'][description]'" label="Description (optional)" placeholder=" e.g. Inc. food & drinks" :required="false"/>
                                        
                                    </div>
                                </template>
                                <button @click="tickets.push(tickets.length)" type="button" class="bg-dark-blue text-light-grey text-xs w-full py-2 rounded-lg border border-dashed border-light-grey/20 cursor-pointer hover:bg-orange hover:text-white">
                                    add ticket
                                </button>
                            </div>
                        </div>
                    </x-cards.card>
                </div>

                <div x-show="step == 4 ">
                    <x-cards.card>
                        <div class="p-4">
                            <div>
                                <h2 class="text-white text-xl font-bold">Media</h2>
                                <p class="text-light-grey text-sm">Upload a cover image. </p>
                            </div>
                            <div x-data="{imageChosen: 'defaultImage'}">
                                <div class="grid grid-cols-2 gap-4 mt-5 mb-5">
                                    <button @click="imageChosen = 'defaultImage'" :class="imageChosen == 'defaultImage' && 'border-orange text-orange bg-orange/10 hover:border-orange'" class="text-sm border border-light-grey/20 rounded-lg text-light-grey px-3 py-2 cursor-pointer hover:opacity-75 hover:border-light-grey">
                                        default image
                                    </button>
                                    <button @click="imageChosen = 'uploadImage'" :class="imageChosen == 'uploadImage' && 'border-orange text-orange bg-orange/10 hover:border-orange'" class="flex items-center justify-center gap-2 text-sm border border-light-grey/20 rounded-lg text-light-grey px-3 py-2 cursor-pointer hover:opacity-75 hover:border-light-grey">
                                        upload your own image
                                    </button>
                                </div>
                                <div x-show="imageChosen == 'defaultImage'">
                                    <p class="text-sm text-white mb-2">Select a cover image *</p>
                                    <div class="grid grid-cols-2 gap-5">
                                        @foreach ($eventImages as $image)
                                            <label for="{{$image['eventType']}}" class="cursor-pointer group/eventImage">
                                            <input type="radio" id="{{$image['eventType']}}" name="event_image" value="{{$image['eventFilePath']}}" class="hidden peer" :required="imageChosen == 'defaultImage'">
                                            <div class="rounded-lg border border-light-grey/20 w-full h-full peer-checked:border-orange hover:border-orange relative">
                                                <img src="{{asset($image['eventFilePath'])}}" alt="{{$image['eventType']}}" class="w-full h-full rounded-[inherit] ">
                                                <div class="absolute inset-0 bg-black/55 rounded-[inherit] hidden group-hover/eventImage:block"></div>
                                                <p class="text-sm text-white absolute bottom-2 left-1/2 -translate-x-1/2 hidden group-hover/eventImage:block">{{$image['eventType']}}</p>
                                            </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </x-cards.card>
                </div>

                <div x-show="step == 5 ">
                    <x-cards.card>

                    </x-cards.card>
                </div>
            </x-form.form>

            <div class="flex justify-between">
                <button @click="step -= 1" class="" :class="step <= 1  ? 'hidden' : 'flex items-center gap-2 rounded-md text-light-grey/50 text-sm cursor-pointer hover:opacity-75' ">
                    <x-icons.arrow-left/> previous
                </button>

                <div class="flex gap-4 ml-auto">
                    @can('saveAsConcept', Event::class)
                        <button class="border border-light-grey/20 rounded-md text-light-grey px-3 py-1 cursor-pointer hover:opacity-75 hover:border-orange">
                            save as concept
                        </button>
                    @endcan
                    <button @click="step += 1" style="background: var(--gradient-button)" :class="step >= 5  ? 'hidden' : 'flex items-center gap-2 rounded-md text-white px-3 py-1 cursor-pointer hover:opacity-75'">
                        next <x-icons.arrow-right/>
                    </button>
                </div>
            </div>
        </div>
    </x-section.section>
</x-layout>