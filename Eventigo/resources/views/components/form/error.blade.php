@props(['name'])

@php
    $oldName = str_replace(['[', ']'], ['.', ''], $name);
@endphp

@error($oldName)
   <p class="text-sm text-red-500">{{$message}}</p> 
@enderror
