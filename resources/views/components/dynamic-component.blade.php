@props(['component'])

@if (is_object($component) || is_callable($component))
    {{ $component($attributes->merge(['class' => ''])) }}
@elseif (is_string($component))
    @if (isset($attributes['href']) && $attributes['href'] !== '#')
        <a {{ $attributes->merge(['class' => '']) }}>{{ $slot }}</a>
    @else
        <{{ $component }} {{ $attributes->merge(['class' => '']) }}>{{ $slot }}</{{ $component }}>
    @endif
@endif
