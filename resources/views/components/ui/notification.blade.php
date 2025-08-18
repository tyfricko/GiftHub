@props([])

<!-- Notification container: JS will inject notification nodes here -->
<div id="global-notifications" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:items-start sm:justify-end z-50" aria-live="polite" aria-atomic="true">
  <div id="notifications" class="w-full flex flex-col items-end space-y-3 max-w-sm pointer-events-auto">
    {{-- JS-injected notifications appear here --}}
    <noscript>
      @if (session()->has('success'))
        <div class="rounded-md bg-primary-600 text-white p-4 mb-4 text-sm font-semibold" role="alert">
          {{ session('success') }}
        </div>
      @endif
      @if (session()->has('failure'))
        <div class="rounded-md bg-red-500 text-white p-4 mb-4 text-sm font-semibold" role="alert">
          {{ session('failure') }}
        </div>
      @endif
    </noscript>
  </div>
</div>