@props(['name'])

@php
    $initials = collect(explode(' ', $name))
        ->take(2)
        ->map(fn($word) => strtoupper($word[0]))
        ->implode('');
@endphp

<div {{ $attributes->merge(['class' => 'w-12 h-12 rounded-full bg-orange/20 flex items-center justify-center ']) }}>
    <span class="text-orange font-bold text-sm">{{ $initials }}</span>
</div>