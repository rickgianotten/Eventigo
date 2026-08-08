<x-layout>
   <x-section.section>
        <div class="max-w-2xl mx-auto space-y-6 mt-7">

            <div class="flex items-center justify-center">
                <div style="background: var(--gradient-hero)" class="rounded-full rounded-full w-18 h-18 flex items-center justify-center">
                    <x-icons.check-icon class="text-white" style="font-size: 2.3rem"/>
                </div>
                <span class="text-orange text-xs bg-alpha-orange/15 px-2.5 py-0.5 rounded-xl flex items-center gap-1 self-end mb-2">
                    <span class="material-symbols-outlined" style="font-size: 0.75rem">star_shine</span> 
                    Order successful
                </span>
            </div>
            

            <div class="text-center">
                <x-section.section-heading heading="Your tickets are on their way!" text="Thank you for your purchase. We have received your order and sent a confirmation."/>          
            </div>

            <x-cards.card>  
                <div class="space-y-4 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-light-grey text-sm">Order number</p>
                            <p class="text-white font-bold">{{$order->order_number}}</p>
                        </div>
                        <div class="p-2 bg-orange/20 rounded-full flex items-center justify-center">
                            <x-icons.ticket-icon/>
                        </div>
                    </div>

                    <x-devider/>

                    <div class="space-y-2">
                        <p class="text-white font-bold text-xl">{{$order->event->title}}</p>
                        <div class="text-sm flex gap-3">
                            <x-icons.calender-icon class="text-orange"/>
                            <p class="text-light-grey">{{$order->event->start_date->format('d F Y')}} - {{$order->event->end_date->format('d F Y')}}</p>
                        </div>
                        <div class="text-sm flex gap-3">
                            <x-icons.location-icon class="text-orange"/>
                            <p class="text-light-grey">{{$order->event->location}}, {{$order->event->city}} </p>
                        </div>
                    </div>

                    <div class="bg-mid-blue rounded-xl flex items-center justify-between px-4 py-3">
                        <p class="text-light-grey text-sm">{{$order->orderItems->sum('quantity')}} ticket(s)</p>
                        <p class="text-white font-bold text-xl">${{$order->totalPrice()}}</p>
                    </div>

                    <div class="bg-mid-blue rounded-xl flex items-center gap-3 px-4 py-3">
                        <x-icons.mail-icon/>
                        <div class="text-sm">
                            <p class="text-white font-bold">Confirmation Sent</p>
                            <p class="text-light-grey">Check your inbox at <span class="text-white underline">{{$order->user->email}}</span> for your tickets.</p>
                        </div>     
                    </div>

                    <div class="grid gap-2">
                        <a href="{{route('orders.download', $order->id)}}" class="text-white text-sm flex items-center justify-center gap-1.5 rounded-md px-3 py-1.5 cursor-pointer hover:opacity-75" style="background: var(--gradient-button)">
                            <x-icons.download-icon class="text-white"/> Download tickets
                        </a>
                        <x-nav-button class="text-sm py-1.5 gap-1.5" href="{{route('events.show', $order->event->slug)}}">Event details <x-icons.arrow-right/> </x-nav-button>
                    </div>
                </div>             
            </x-cards.card>

            <div class="text-xs flex justify-center gap-1">
                <p class="text-light-grey">Questions about your order?</p> 
                <a href="mailto:{{env('MAIL_SUPPORT_ADDRESS')}}" class="text-orange">Contact us</a>.
            </div>

        </div>
   </x-section.section>
</x-layout>