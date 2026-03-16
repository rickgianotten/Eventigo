@props(['hover'=> false])

@php
    if($hover){
        $class = "bg-card border border-light-grey/20 rounded-lg cursor-pointer transition  duration-300 ease-in-out hover:border-orange hover:-translate-y-1 group";
    }else{
         $class = "bg-card border border-light-grey/20 rounded-lg group";
    }
@endphp

<div {{$attributes(['class' => $class])}}>
    {{$slot}}
</div> 