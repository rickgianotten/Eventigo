<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eventigo</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.ts')
</head>
<body class="bg-dark-blue">
    <header class="px-2 py-3 border-b border-light-grey/20 fixed w-full top-0 left-0 z-50 bg-dark-blue">

        <div class="mx-auto container ">
            {{-- menu desktop --}}
            <div class=" hidden sm:flex items-center justify-between">

                <a href="{{route('home')}}"><x-icons.eventigo-logo/></a>
                
                <nav class=" flex items-center gap-10 ">
                    <a href="{{route('events.index')}}" class="text-light-grey hover:text-white">Events</a>
                    <a href="/categories" class="text-light-grey hover:text-white">Categories</a>
                    <a href="{{route('pricing.index')}}" class="text-light-grey hover:text-white">Pricing</a>
                </nav>

                @auth
                    <div class="flex items-center gap-4">
                        <x-nav-button href="">Profile</x-nav-button>                        
                        <form action={{route('auth.logout')}} method="POST">
                            @csrf
                            @method('DELETE')
                            <x-form.button class="py-1">Log out</x-form.button>
                        </form>
                    </div>
                @endauth

                @guest
                <div class="flex items-center gap-4">    
                        <x-nav-button href="{{route('auth.login.create')}}">Login</x-nav-button>
                        <x-nav-button href="{{route('auth.register.create')}}" class="border-none" style="background: var(--gradient-button)">Register</x-nav-button>                    
                </div>
                @endguest
            </div>
            
            {{-- menu mobile --}}
            <div x-data="{ open: false }">
                <div class=" flex justify-between sm:hidden">

                    <a href="{{route('home')}}"><x-icons.eventigo-logo/></a>
                    
                    <button class=" flex items-center cursor-pointer" @click="open = ! open">
                        <span x-show="!open" class="material-symbols-outlined text-white">menu</span>
                        <span x-show="open" class="material-symbols-outlined text-white">close</span>
                    </button>
                </div>

                <div x-show="open" @click.outside="open = false" class="text-white flex flex-col px-4 py-6 gap-4 sm:hidden">
                    <nav class="flex flex-col gap-4">
                        <a href="/events" class="text-light-grey hover:text-white">Events</a>
                        <a href="/categories" class="text-light-grey hover:text-white">Categories</a>
                        <a href="{{route('pricing.index')}}" class="text-light-grey hover:text-white">Pricing</a>
                    </nav>
                    
                    @auth
                        <div class="grid grid-cols-2 gap-2">
                            <x-nav-button href="">Profile</x-nav-button>      
                            <form action={{route('auth.logout')}} method="POST" class="grid">
                                @csrf
                                @method('DELETE')
                                <x-form.button>Log out</x-form.button>
                            </form>
                        </div>      
                    @endauth

                    @guest
                        <div class="grid grid-cols-2 gap-2">                           
                            <x-nav-button href="{{route('auth.login.create')}}" class="text-center">Login</x-nav-button>
                            <x-nav-button href="{{route('auth.register.create')}}" class="border-none text-center" style="background: var(--gradient-button)">Register</x-nav-button>                                               
                        </div>
                    @endguest
                </div>
            </div>
        </div>

    </header>
    <main class="py-15">
        {{$slot}}
    </main>

</body>
</html>