@props(['name', 'label', 'placeholder', 'type' => "text", 'required'=> true])


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
    <x-form.input :type="$type" :name="$name" :placeholder="$placeholder" :required="$required"/>
    <x-form.error :name="$name"/>
</div>