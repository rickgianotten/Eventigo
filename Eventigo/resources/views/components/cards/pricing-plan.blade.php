@props(['plan'])

    <x-cards.card>
        <div class="p-8 grid gap-3 relative rounded-[inherit] {{$plan->name == 'premium' ? 'border-orange/80 border' : 'var(--light-grey)'}} " style="box-shadow: {{$plan->name == 'premium' ? 'var(--shadow-glow)' : 'var(--light-grey)'}}">
            @if ($plan->name == 'premium')
                <span class="absolute text-white top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 top-auto rounded-full py-0.5 px-3" style="background: var(--linear-gradient)">Most Popular</span>
            @endif
            
            <h3 class="text-white font-bold uppercase">{{$plan->name}}</h3>
            <div class="flex gap-1">
                <h4 class="text-white text-4xl">${{$plan->price}}</h4><span class="text-light-grey self-end">{{$plan->billing_cycle}}</span>
            </div>
            <form action="{{route('pricing.store')}}" method="post" class="grid">
                @csrf
                <input type="text" value="{{$plan->value}}" name="chosen_plan" hidden />
                <button class=" text-white rounded-xl py-2 cursor-pointer flex items-center justify-center gap-2 hover:opacity-75" style="background: {{ $plan->name == 'premium' ? 'var(--gradient-button)' : 'var(--light-grey)' }}">
                    {{$plan->cta}}<x-icons.arrow-right/>
                </button>
            </form>
            <div>
                <ul class="text-white space-y-2">
                    @foreach ($plan['features'] as $feature)
                        <li class="flex items-center gap-2"><x-icons.check-icon/>{{$feature}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-cards.card>
