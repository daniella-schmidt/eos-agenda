@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#008f91] text-start text-base font-semibold text-[#0d2b2b] bg-[#ccfeff] focus:outline-none focus:text-[#0d2b2b] focus:bg-[#ccfeff] focus:border-[#008f91] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-[#0d2b2b] hover:bg-[#e5ffff] hover:border-[#ff6bb3] focus:outline-none focus:text-[#0d2b2b] focus:bg-[#e5ffff] focus:border-[#008f91] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
