@props(['value', 'name', 'selected' => false, 'id', 'required'=> true])

@php
    $current = old($name, request($name, $selected ? $value : null));
@endphp

<label for="{{$id}}" class="cursor-pointer">
    <input type="radio" id="{{$id}}" name="{{$name}}" value="{{$value}}" class="hidden peer" 
    @checked($current == $value)
    @if($required)
        required
    @endif    
    >
    <span class="text-white px-2.5 py-1 rounded-lg bg-mid-blue text-white peer-checked:bg-orange-600">
        {{$slot}} 
    </span>
</label>