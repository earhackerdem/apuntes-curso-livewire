@props(['disabled' => false])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
