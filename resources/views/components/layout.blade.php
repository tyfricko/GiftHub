<!DOCTYPE html>
<html lang="sl" x-data="{ open: false }" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>GiftHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
  </head>
  <body class="bg-neutral-light text-neutral-gray min-h-screen flex flex-col">
    <x-ui.navbar :user="auth()->user()" />

    <main class="flex-grow container mx-auto px-4 py-8">
      @if (session()->has('success'))
        <x-ui.alert type="success" dismissible>
          {{ session('success') }}
        </x-ui.alert>
      @endif

      @if (session()->has('failure'))
        <x-ui.alert type="error" dismissible>
          {{ session('failure') }}
        </x-ui.alert>
      @endif

      @yield('content')
    </main>

    <footer class="border-t text-center text-sm text-neutral-gray py-4 mt-auto">
      <p>Copyright &copy; {{ date('Y') }} <a href="/" class="text-neutral-gray hover:underline">GiftHub</a>. Vse pravice pridržane.</p>
    </footer>
  </body>
</html>
