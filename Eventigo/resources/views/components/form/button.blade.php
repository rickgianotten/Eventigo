<button {{$attributes->merge(['class' => 'flex items-center justify-center text-white rounded-md text-white p-2 cursor-pointer hover:opacity-75'])}} @disabled($disabled ?? false) type="submit" style="background: var(--gradient-button)">
    {{$slot}}
</button>