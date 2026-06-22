<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'MedFinder'))</title>
    <meta name="description" content="@yield('meta_description', 'Search Brazilian medications by name or active ingredient.')">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-teal-800">
                MedFinder
            </a>
            <p class="hidden text-sm text-slate-500 sm:block">Brazilian medication search</p>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6">
            <p class="text-center text-xs text-slate-500">
                For demonstration only — not medical advice. Prices are CMED PMC 18% (regulated maximum).
            </p>
        </div>
    </footer>
</body>

</html>
