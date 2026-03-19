@props(['name', 'label', 'required' => true])

@php
    $oldName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="space-y-1">
    <x-form.label :name="$name">{{$label}}</x-form.label>
                        
    <select name="{{$name}}" id="{{$name}}" class="text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:ring-0 focus:outline-none" 
        @if($required)
        required
        @endif  
    >
        {{$slot}}
    </select>
    <x-form.error :name="$oldName"/>
</div>