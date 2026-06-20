@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex h-16 items-center px-1 pt-1 border-b-2 border-[#008f91] text-sm font-semibold leading-5 text-[#0d2b2b] focus:outline-none focus:border-[#008f91] transition duration-150 ease-in-out'
            : 'inline-flex h-16 items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-[#0d2b2b] hover:border-[#ff6bb3] focus:outline-none focus:text-[#0d2b2b] focus:border-[#008f91] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
