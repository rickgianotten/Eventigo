@props(['name', 'label'])

@php
    $oldName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="space-y-1">
    <x-form.label :name="$name">{{$label}}</x-form.label>
                        
    <select name="{{$name}}" id="{{$name}}" class="text-white bg-light-grey/10 py-1 px-2 w-full border border-transparent focus:ring-0 focus:outline-none">
        {{$slot}}
    </select>
    <x-form.error :name="$oldName"/>
</div>