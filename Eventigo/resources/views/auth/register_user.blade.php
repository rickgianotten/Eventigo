<x-layout>
    <section class="container mx-auto max-w-md px-4 py-10 ">
        <x-cards.card>
            <div class="py-6 space-y-6">
                <div class="text-center">
                    <h2 class="text-white text-xl">Create an account </h2>
                    <p class="text-light-grey">Register to discover events and purchase tickets.</p>
                </div>
                <x-form.form action="{{route('auth.register.store')}}" method="POST" class="space-y-6 grid px-4">

                    <x-form.form-group type="text" name="name" label="Full name" placeholder="Your full name"/>

                    <x-form.form-group type="email" name="email" label="Email" placeholder="name@example.com"/>

                    <x-form.form-group type="password" name="password" label="Password" placeholder="********"/>
                    <x-form.form-group type="password" name="password_confirmation" label="Confirm password" placeholder="********"/>

                    <x-form.accept-terms/>

                    <x-form.button>register</x-form.button>

                </x-form.form>
                <div class="flex items-center gap-4">
                    <div class="h-px flex-1 bg-light-grey/20"></div>
                    <span class="text-light-grey text-sm">or</span>
                    <div class="h-px flex-1 bg-light-grey/20"></div>
                </div>
                <div class="grid px-4 text-center">
                    <x-nav-button href="{{route('auth.login.store')}}">login</x-nav-button>
                </div>
                
            </div>
        </x-cards.card>
    </section>
</x-layout>