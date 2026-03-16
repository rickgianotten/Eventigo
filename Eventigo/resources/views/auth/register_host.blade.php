@php
    $selectedPlan = old('plan', session('chosen_plan'));
@endphp

<x-layout>
    <section class="container mx-auto max-w-md px-4 py-10 ">
        <x-cards.card>
            <div class="py-6 space-y-6">
                <div class="text-center">
                    <h2 class="text-white text-xl">Become a host</h2>
                    <p class="text-light-grey">Register as a host to organise and <br> manage events.</p>
                </div>
                <x-form.form action="{{route('auth.host.store')}}" method="POST" class="space-y-6 grid px-4">
                    <x-form.form-group name="user[name]" label="Name" placeholder="Full name"/>
                    <x-form.form-group type="email" name="user[email]" label="Personal email" placeholder="name@example.com"/>

                    <x-form.form-group name="company[name]" label="Company" placeholder="Company name"/>
                    <x-form.form-group type="email" name="company[email]" label="Company email" placeholder="company@example.com"/>

                    <x-form.select label="Subscription plan" name="company[pricing_plan]">
                            @foreach($plans as $plan)
                                <option value="{{$plan->value}}" {{$selectedPlan == $plan->value ? 'selected' : ''}}>{{$plan->name}} ${{$plan->price}} {{$plan->billing_cycle}} </option>
                            @endforeach
                    </x-form.select>

                    <x-form.form-group type="password" name="user[password]" label="Password" placeholder="********"/>
                    <x-form.form-group type="password" name="user[password_confirmation]" label="Confirm password" placeholder="********"/>

                    <x-form.accept-terms/>
 
                    <x-form.button>Create host account</x-form.button>
                </x-form.form>
                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-light-grey/20"></div>
                    <span class="text-light-grey text-sm">or</span>
                    <div class="h-px flex-1 bg-light-grey/20"></div>
                </div>
                <div class="grid px-4 text-center">
                    <x-nav-button href="{{route('auth.login.create')}}">Login</x-nav-button>
                </div>
            </div>
        </x-cards.card>
    </section>
</x-layout>