@props(['event', 'participant'])

@if($event->requiresShippingAddress() && ($participant?->needsShippingAddress() ?? false))
<div {{ $attributes->merge(['class' => 'border-l-4 border-primary-600 bg-primary-50 p-4 rounded-md flex items-start gap-4']) }}>
    <div class="flex-shrink-0">
        <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 9 4-18 3 9h4" />
        </svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-medium text-primary-700">Shipping address required</p>
        <p class="mt-1 text-sm text-primary-700/80">
            This event requires shipping addresses. Please provide your shipping address so you can be included in assignments.
        </p>
        <div class="mt-3">
            <a href="{{ route('gift-exchange.shipping-address', $event->id) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                Provide shipping address
            </a>
        </div>
    </div>
</div>
@endif