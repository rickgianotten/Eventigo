@php
    use App\Models\Event;
@endphp
<x-layout>
    <x-section.section>
        <div class="max-w-4xl mx-auto">
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
                        <x-icons.group-icon :class="'step == 3 ? text-white : text-light-grey'"/>
                    </button>

                    <div class="h-px flex-1 bg-light-grey/30"></div>

                    <button @click="step = 4" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 4 && 'bg-orange'">
                        <x-icons.ticket-icon :class="'step == 4 ? text-white : text-light-grey'"/>
                    </button>

                    <div class="h-px flex-1 bg-light-grey/30"></div>
                    
                    <button @click="step = 5" class="h-10 w-10 rounded-full flex items-center justify-center bg-mid-blue" :class="step == 5 && 'bg-orange'">
                        <x-icons.upload-image-icon :class="'step == 5 ? text-white : text-light-grey'"/>
                    </button>
                </div>

                <form id="createEventForm" method="POST" action="{{route('events.create.storePreview')}}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div x-show="step == 1 ">
                        <x-cards.card>
                            <div class="p-4 space-y-6">
                                <div>
                                    <h2 class="text-white text-xl font-bold">Basic information</h2>
                                    <p class="text-light-grey text-sm">Enter the key details of your event.</p>
                                </div>

                                <x-form.form-group type="text" name="title" placeholder=" e.g. Amserdam music festival" label="Event title" :required="false"/>

                                <div>
                                    <h3 class="text-white mb-2">Category</h3>
                                    <div class="flex flex-wrap gap-4 ">
                                        @foreach ($categories as $category)
                                            <x-form.radio-button :value="$category->slug" :id="$category->name" name="category" :required="false">
                                                {{$category->name}}
                                            </x-form.radio-button>
                                        @endforeach
                                    </div>
                                    <x-form.error name="category"/>
                                </div>

                                <x-form.form-group type="text" name="short_description" placeholder="Max. 120 characters - shown in overview" label="Short description" :required="false"/>

                                <x-form.textarea type="text" name="long_description" placeholder="Tell visitors more about your event" label="Full description" :required="false"/>
                            </div>
                        </x-cards.card>
                    </div>

                    <div x-show="step == 2 ">
                        <x-cards.card>
                            <div class="p-8">
                                <div class="mb-6">
                                    <h2 class="text-white text-xl font-bold">Location & time</h2>
                                    <p class="text-light-grey text-sm">Where and when is the event taking place? </p>
                                </div>
                                <div class="space-y-6">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <x-form.form-group type="text" name="location" placeholder=" e.g. Tomorrowland " label="Location" :required="false"/>
                                        <x-form.form-group type="text" name="city" placeholder=" e.g. Alpe d'Huez " label="City" :required="false"/>
                                    </div>


                                    <x-form.form-group type="text" name="street" placeholder="street number, postal code " label="Street" :required="false"/>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <x-form.form-group type="date" name="start_date" placeholder="" label="Start date" :required="false"/>
                                        <x-form.form-group type="date" name="end_date" placeholder="" label="End date" :required="false"/>                    
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">                                      
                                        <x-form.form-group type="time" name="start_time" placeholder="" label="Start time" :required="false"/>
                                        <x-form.form-group type="time" name="end_time" placeholder="" label="End time" :required="false"/>
                                    </div>

                                </div>

                            </div>
                        </x-cards.card>
                    </div>

                    <div x-show="step == 3">
                        <x-cards.card>
                            <div class="p-4 space-y-6">
                                <div class="mb-6">
                                    <h2 class="text-white text-xl font-bold">Participants</h2>
                                    <p class="text-light-grey text-sm">Add participants, speakers, or artists to your event. </p>
                                </div>
                                <div x-data="{participants: [1]}" class="space-y-5">
                                    <template x-for="(participant, index) in participants" :key="index">
                                        <div class="border border-light-grey/20 rounded-md p-4 space-y-5">
                                            <div class="flex justify-between">
                                                <h4 x-text="`participant ${index + 1}`" class="text-orange"></h4>
                                                <button type="button" @click="participants.splice(index, 1)" :class="index == 0 ? 'hidden' : 'text-red-500 cursor-pointer hover:text-red-600'">
                                                    delete
                                                </button>
                                            </div>
                                            <div class="grid gap-4 md:grid-cols-3">
                                                <div>
                                                    <label :for="'participan_name_' + index" class="flex items-center text-white font-medium">Name</label>
                                                    <input :id="'participan_name_' + index" type="text" :name="'participants['+index+'][name]'" placeholder="full name" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none">
                                                </div>
                                                                                                
                                                <div>
                                                    <label :for="'participan_email_' + index" class="flex items-center text-white font-medium">Contact email</label>
                                                    <input :id="'participan_email_' + index" type="email" :name="'participants['+index+'][email]'" placeholder="name@email.com" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label :for="'participan_role_' + index" class="flex items-center text-white font-medium">Role</label>
                                                    <select :name="'participants['+index+'][role]'" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:ring-0 focus:outline-none">
                                                        <option value="artist">Artist</option>
                                                        <option value="speaker">Speaker</option>
                                                        <option value="exhibitor">Exhibitor</option>
                                                        <option value="vendor">Vendor</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <button @click="participants.push(participants.length)" type="button" class="bg-dark-blue text-light-grey text-xs w-full py-2 rounded-lg border border-dashed border-light-grey/20 cursor-pointer hover:bg-orange hover:text-white">
                                        add participan
                                    </button>
                                </div>
                            </div>
                        </x-cards.card>
                    </div>

                    <div x-show="step == 4 ">
                        <x-cards.card>
                            <div class="p-4 space-y-6" x-data="{freeEvent: {{old('free_event') ? 'true' : 'false'}},tickets: [1]}">
                                <div class="flex justify-between">
                                    <div>
                                        <h2 class="text-white text-xl font-bold">Tickets</h2>
                                        <p class="text-light-grey text-sm">Set your ticket types and prices. </p>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="free_event" class="sr-only peer" @checked(old('free_event')) x-model="freeEvent">
                                        <span class="select-none ms-3 text-md font-medium text-heading text-light-grey/80 mr-4">Free event</span>
                                        <div class="relative w-9 h-5 bg-light-grey/10 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-light-grey/10 dark:peer-focus:ring-light-grey/10 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-buffer after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange"></div>
                                    </label>
                                </div>

                                <template x-if="!freeEvent" class="space-y-5">
                                    <template x-for="(ticket, index) in tickets" :key="index">
                                        <div class="border border-light-grey/20 rounded-md p-4 space-y-5">
                                            <div class="flex justify-between">
                                                <h4 x-text="`ticket ${index + 1}`" class="text-orange"></h4>
                                                <button type="button" @click="tickets.splice(index, 1)" :class="index == 0 ? 'hidden' : 'text-red-500 cursor-pointer hover:text-red-600'">
                                                    delete
                                                </button>
                                            </div>
                                            <div class="grid gap-4 md:grid-cols-3">
                                                <div>
                                                    <label :for="'ticket_type_' + index" class="flex items-center text-white font-medium">Ticket type</label>
                                                    <select :name="'tickets['+index+'][type]'" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:ring-0 focus:outline-none">
                                                        <option value="Regular">Regular</option>
                                                        <option value="VIP">VIP</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label :for="'ticket_price_' + index" class="flex items-center text-white font-medium">Price ($)</label>
                                                    <input :id="'ticket_price_' + index" type="number" :name="'tickets['+index+'][price]'" placeholder="75,00" step="0.01" min="1" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none">
                                                </div>
                                                                                                
                                                <div>
                                                    <label :for="'ticket_quantity_' + index" class="flex items-center text-white font-medium">Quantity available</label>
                                                    <input :id="'ticket_quantity_' + index" type="number" :name="'tickets['+index+'][quantity]'" placeholder="100" step="1" min="1" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none">
                                                </div>
                                            </div>

                                            <div>
                                                <label :for="'ticket_description_' + index" class="flex items-center text-white font-medium">Description (optional)</label>
                                                <input :id="'ticket_description_' + index" type="text" :name="'tickets['+index+'][description]'" placeholder=" e.g. Inc. food & drinks" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none">
                                            </div>  
                                            
                                        </div>
                                    </template>
                                </template>    
                                <button x-show="!freeEvent" @click="tickets.push(tickets.length)" type="button" class="bg-dark-blue text-light-grey text-xs w-full py-2 rounded-lg border border-dashed border-light-grey/20 cursor-pointer hover:bg-orange hover:text-white">
                                    add ticket
                                </button>
                                <div x-show="freeEvent" class="border border-light-grey/20 rounded-md p-4 space-y-5 text-center">
                                    <h3 class="text-green-500 text-3xl mb-0 font-bold">Free event</h3>
                                    <p class="text-light-grey text-sm">Visitors can register for free.</p>
                                    <div class="flex justify-center">
                                        <x-form.form-group label="Max. amount of visitors" type="number" name="max_amount_of_visitors" min="1" step="1" :required="false" placeholder="amount of visitors"/>
                                    </div>

                                </div>
                            </div>
                        </x-cards.card>
                    </div>

                    <div x-show="step == 5 ">
                        <x-cards.card>
                            <div class="p-4">
                                <div>
                                    <h2 class="text-white text-xl font-bold">Media</h2>
                                </div>
                                <div>
                                    <div class="mb-5">
                                        <p class="text-sm text-white mb-2">Upload your own cover image</p>
                                        <x-form.error name="image_upload"/>

                                        <div id="upload_image_container">
                                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-light-grey/50 px-6 py-15">
                                                <div class="text-center">
                                                    <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true" class="mx-auto size-12 text-gray-300">
                                                        <path d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                    <div class="mt-4 flex text-sm/6 text-gray-500">
                                                        <label for="image_upload" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-orange-600 focus-within:outline-2 focus-within:outline-offset-2 hover:text-orange-500">
                                                            <span>Upload a file</span>
                                                            <input id="image_upload" type="file" name="image_upload" class="sr-only"/>
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs/5 text-gray-500 uppercase">jpg, jpeg, png, bmp, gif or webp</p>
                                                </div>
                                            </div>                                           
                                        </div>

                                        <div id="preview_image_conainer" class="hidden">
                                            <label for="image_upload">
                                                <span class="font-semibold text-orange-600 hover:text-orange-500 cursor-pointer">Choose another file</span>
                                            </label>
                                            <img src="" alt="uploaded_image" id="preview_image" class="rounded-lg">
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm text-white mb-2">Or Select a cover image </p>
                                        <x-form.error name="event_image"/>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                                            @foreach ($eventImages as $image)
                                                <label for="{{$image['eventType']}}" class="cursor-pointer group/eventImage">
                                                <input type="radio" id="{{$image['eventType']}}" name="event_image" value="{{$image['eventFile']}}" @checked(old('event_image') == $image['eventFile'] ) class="hidden peer" :required="false">
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

                </form>

                <div class="flex justify-between">
                    <button @click="step -= 1" class="" :class="step <= 1  ? 'hidden' : 'flex items-center gap-2 rounded-md text-light-grey/80 text-sm cursor-pointer hover:opacity-75' ">
                        <x-icons.arrow-left/> previous
                    </button>

                    <div class="flex gap-4 ml-auto">
                        @can('saveAsConcept', Event::class)
                            <button type="submit" name="action" value="concept" form="createEventForm" class="border border-light-grey/20 rounded-md text-light-grey px-3 py-1 cursor-pointer hover:opacity-75 hover:border-orange">
                                save as concept
                            </button>
                        @endcan
                        <button @click="step += 1" style="background: var(--gradient-button)" :class="step == 5  ? 'hidden' : 'flex items-center gap-2 rounded-md text-white px-3 py-1 cursor-pointer hover:opacity-75'">
                            next <x-icons.arrow-right/>
                        </button>
                        <button type="submit" name="action" value="preview" form="createEventForm" style="background: var(--gradient-button)" :class="step == 5 ? 'flex items-center gap-2 rounded-md text-white px-3 py-1 cursor-pointer hover:opacity-75' : 'hidden'">
                            preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </x-section.section>
</x-layout>
{{dd($errors->all())}}