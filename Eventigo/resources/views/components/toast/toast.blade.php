@php
    use App\Enums\toast\ToastStatus;
@endphp

@props([
    'status' => ToastStatus::Success,
    'title' => null,
    'message' => null
])

@php
    $styles = match($status){
        ToastStatus::Success => [
            'icon' => 'check',
            'border' => 'border-green-700',
            'bar' => 'bg-green-500',
            'iconBg' => 'bg-green-500/15',
            'iconColor' => 'text-green-400'

        ],
        ToastStatus::Error  => [
            'icon' => 'error',
            'border' => 'border-red-700',
            'bar'    => 'bg-red-500',
            'iconBg' => 'bg-red-500/15',
            'iconColor' => 'text-red-400'
        ],
        ToastStatus::Warning  => [
            'icon' => 'warning',
            'border' => 'border-yellow-700',
            'bar'    => 'bg-yellow-500',
            'iconBg' => 'bg-yellow-500/15',
            'iconColor' => 'text-yellow-400'           
        ],
        ToastStatus::Info  => [
            'icon' => 'info',
            'border' => 'border-sky-700',
            'bar'    => 'bg-sky-500',
            'iconBg' => 'bg-sky-500/15',
            'iconColor' => 'text-sky-400'          
        ],
    }
@endphp


<div x-data="{open: true}">
    <template x-if="open" >
        <div class="absolute left-1/2 top-6 -translate-x-1/2 z-50" >
            <div class="relative flex items-center overflow-hidden rounded-2xl border {{$styles['border']}} bg-dark-blue min-w-sm">

                <div class="w-1 self-stretch {{$styles['bar']}}"></div>

                <div class="flex flex-1 items-center gap-4 px-5 py-4">

                    <div class="flex p-1 items-center justify-center rounded-md {{$styles['iconBg']}}">
                        <x-icons.icon class="{{$styles['iconColor']}}">{{$styles['icon']}}</x-icons.icon>
                    </div>

                    <div class="flex-1">
                        @if ($title)
                            <h4 class="text-sm font-semibold text-white">
                                {{$title}}
                            </h4>                          
                        @endif

                        @if ($message)
                            <p class="text-sm text-light-grey">
                                {{$message}}
                            </p>                        
                        @endif

                    </div>

                    <button class="absolute right-4 top-4 transition hover:cursor-pointer" @click="open = false">
                        <x-icons.icon class="text-light-grey hover:text-white" style="font-size: 1.1rem">close</x-icons.icon>
                    </button>

                </div>
            </div>
        </div>    
    </template>
</div>

