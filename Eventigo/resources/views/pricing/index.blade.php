<x-layout>
    <section class="container mx-auto px-4">
        <div x-data="{premiumPlan : 'monthly'}">
            <div class="flex flex-col items-center text-center mb-10 pt-10">
                <x-section.section-heading>
                    <x-slot:heading>Choose your plan</x-slot:heading>
                    <x-slot:text>Start for free and upgrade when you're ready to grow. <br> hidden fees.</x-slot:text>
                </x-section.section-heading>


                <div class="w-fit bg-card border border-light-grey/20 rounded-4xl mt-5">
                    <div class="flex gap-2 p-2">
                        <button class="text-white cursor-pointer px-6 py-1.5 rounded-full transition duration-500 ease-in-out" :class="premiumPlan == 'monthly' ? 'bg-orange' : 'bg-transparent'" @click="premiumPlan = 'monthly'">Monthly</button>
                        <button class="text-white cursor-pointer px-6 py-1.5 rounded-full transition duration-500 ease-in-out flex items-center gap-2" :class="premiumPlan == 'yearly' ? 'bg-orange' : 'bg-transparent' "  @click="premiumPlan = 'yearly'">
                            Yearly <span class="bg-green-300/30 text-green-300 text-sm rounded-full py-0.5 px-1">-37,5%</span>
                        </button>
                    </div>
                </div>
            </div>



            <div class="mx-auto grid grid-cols-1 gap-5 max-w-4xl md:grid-cols-2">
                @foreach ($plans as $plan)
                    <div @if ($plan->value == 'premium_monthly')
                        x-show="premiumPlan == 'monthly'"
                    @endif
                    @if ($plan->value == 'premium_yearly')
                        x-show="premiumPlan == 'yearly'"
                    @endif>
                        <x-cards.pricing-plan :plan="$plan"/>
                    </div>   
                @endforeach
            </div>            
        </div>

    </section>


    
</x-layout>