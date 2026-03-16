@props(['name', 'label', 'placeholder', 'required'=> true])


<div class="space-y-1 ">
    @if($label || isset($icon))
        <x-form.label :name="$name">
            @isset($label)
                {{$label}}            
            @endisset
            @isset($icon)
                {{$icon}}
            @endisset
        </x-form.label>
    @endif
    <textarea name="{{$name}}" id="{{$name}}" cols="30" rows="8"  
    placeholder="{{$placeholder}}" 
    class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none"
    @if ($required)
        required
    @endif
    >{{old($name)}}</textarea>
    <x-form.error :name="$name"/>
</div>