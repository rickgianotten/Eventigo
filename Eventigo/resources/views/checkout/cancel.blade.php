<x-layout>
   <x-section.section>
        <div class="max-w-2xl mx-auto space-y-6 mt-7">

            <div class="flex items-center justify-center">
                <div class="rounded-full rounded-full w-18 h-18 flex items-center justify-center bg-red-700/25">
                    <x-icons.cancel-icon class="text-red-500" style="font-size: 2.3rem"/>
                </div>
                <span class="text-red-500 text-xs bg-red-700/15 px-2.5 py-0.5 rounded-xl self-end mb-2">      
                    order cancelled
                </span>
            </div>
            

            <div class="text-center">
                <x-section.section-heading heading="Your order hasn't been completed" text="Don't worry—you haven't been charged. You can try again whenever you want."/>          
            </div>

            <x-cards.card>  
                <div class="p-4">

                    <h2 class="text-white font-bold mb-4">What now?</h2>

                    <div class="space-y-4 mx-1.5">
                        <div class="text-sm flex gap-4 bg-mid-blue rounded-xl p-2.5">
                            <div class="rounded-md bg-orange/10 h-9 w-9 flex items-center justify-center ">
                                <x-icons.icon class="text-orange">cached</x-icons.icon>
                            </div>
                        
                            <div>
                                <h4 class="text-white font-semibold">Try Again</h4>
                                <p class="text-light-grey">Your tickets are still available — please start the order again.</p>
                            </div>
                        </div>

                        <div class="text-sm flex gap-4 bg-mid-blue rounded-xl p-2.5">
                            <div class="rounded-md bg-orange/10 h-9 w-9 flex items-center justify-center ">
                                <x-icons.ticket-icon />
                            </div>
                        
                            <div>
                                <h4 class="text-white font-semibold">Other Tickets</h4>
                                <p class="text-light-grey">View alternative ticket types or other events.</p>
                            </div>
                        </div>

                        <div class="text-sm flex gap-4 bg-mid-blue rounded-xl p-2.5">
                                <div class="rounded-md bg-orange/10 h-9 w-9 flex items-center justify-center ">
                                    <x-icons.info-icon class="text-orange"/>
                                </div>
                            
                                <div>
                                    <h4 class="text-white font-semibold">Need help?</h4>
                                    <p class="text-light-grey">Our support team will be happy to assist you with your order.</p>
                                </div>
                        </div>
                    </div>

                    <div class="mt-6 mx-1.5 space-y-2">
                        <x-nav-button class="text-sm border-transparent py-2 gap-2 hover:border-transparent" style="background: var(--gradient-button)" href="{{route('events.show', $order->event->slug)}}">
                            <x-icons.icon class="text-white" style="font-size: 1.2rem">cached</x-icons.icon>
                            Try again
                        </x-nav-button>

                        <x-nav-button class="text-sm gap-2 border-zinc-700 py-2" href="{{route('events.index')}}">
                            <x-icons.arrow-left/>
                            Back to events
                        </x-nav-button>
                    </div>

                </div>             
            </x-cards.card>

            <div class="text-xs flex justify-center gap-1">
                <p class="text-light-grey">Was this a mistake?</p> 
                <a href="mailto:{{env('MAIL_SUPPORT_ADDRESS')}}" class="text-orange">Contact us</a>.
            </div>

        </div>
   </x-section.section>
</x-layout>