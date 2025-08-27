@props(['event', 'participants'])

@php
    $total = $participants->count();
    $completed = $participants->filter(fn($p) => $p->hasShippingAddress())->count();
    $percent = $total > 0 ? intval(round($completed / $total * 100)) : 0;
@endphp

<div {{ $attributes->merge(['class' => 'p-4 bg-white border rounded-md']) }}>
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-700">Shipping addresses</div>
            <div class="text-xs text-gray-500">Collected for participants</div>
        </div>
        <div class="text-sm font-medium text-gray-700">{{ $completed }} / {{ $total }}</div>
    </div>

    <div class="mt-3">
        <div class="relative h-3 bg-gray-200 rounded overflow-hidden">
            <div class="absolute inset-0 bg-primary-600" style="width: {{ $percent }}%; transition: width 300ms ease;"></div>
        </div>
        <div class="mt-2 text-xs text-gray-600">{{ $percent }}% complete</div>
    </div>
</div>