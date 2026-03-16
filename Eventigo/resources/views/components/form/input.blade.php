@props(['name', 'placeholder' => "", 'type', 'required' => true])

@php
    $oldName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

@php
    $defaults = [
        'name' => $name,
        'type' => $type,
        'id' => $name,
        'class' => "text-white bg-light-grey/10 rounded-lg py-1.5 px-2 w-full border border-light-grey/20 focus:border-orange focus:ring-0 focus:outline-none",
        'value' => old($oldName),
        'placeholder' => $placeholder,
        'autocomplete' => "off"
    ]
@endphp

<input {{$attributes($defaults)}} @if ($required)
    required
@endif>