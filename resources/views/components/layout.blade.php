<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? __('messages.app_name') }}</title>
    <meta name="description" content="{{ $description ?? __('messages.app_description') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Vite-built assets -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- Alpine (lightweight interactions) - included as fallback if not bundled -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </head>
  <body class="bg-neutral-50 text-neutral-900 min-h-screen flex flex-col antialiased">
    <!-- Navbar (enhanced) -->
    <x-ui.navbar-enhanced :user="auth()->user()" />

    <!-- Global notification container (JS will render notifications here) -->
    <x-ui.notification />

    <script>
      // Prepare initial notifications from server-side flash messages.
      window.__initialNotifications = window.__initialNotifications || [];
      @if (session()->has('success'))
        window.__initialNotifications.push({ type: 'success', message: {!! json_encode(session('success')) !!} });
      @endif
      @if (session()->has('error'))
        window.__initialNotifications.push({ type: 'error', message: {!! json_encode(session('error')) !!} });
      @endif
      @if (session()->has('failure'))
        window.__initialNotifications.push({ type: 'error', message: {!! json_encode(session('failure')) !!} });
      @endif
      @if (session()->has('warning'))
        window.__initialNotifications.push({ type: 'warning', message: {!! json_encode(session('warning')) !!} });
      @endif
      @if (session()->has('info'))
        window.__initialNotifications.push({ type: 'info', message: {!! json_encode(session('info')) !!} });
      @endif
    </script>

    <main class="flex-grow">
      @isset($slot)
        {{ $slot }}
      @else
        @yield('content')
      @endisset
    </main>

    <!-- Footer -->
    <x-ui.footer />

  </body>
</html>
