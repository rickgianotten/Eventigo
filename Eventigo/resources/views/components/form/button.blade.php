<button {{$attributes->merge(['class' => 'flex items-center justify-center text-white rounded-md text-white p-2 cursor-pointer hover:opacity-75'])}}  type="submit" style="background: var(--gradient-button)">
    {{$slot}}
</button>