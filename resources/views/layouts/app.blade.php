<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'MedFinder'))</title>
    <meta name="description" content="@yield('meta_description', 'Search Brazilian medications by name or active ingredient.')">
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    @yield('og')

    @fonts
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col text-stone-900 antialiased">
    <header>
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-5 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-accent text-white shadow-sm shadow-accent/25">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>
                <span class="text-lg font-semibold tracking-tight text-stone-900">MedFinder</span>
            </a>
            <p class="hidden text-sm text-stone-500 sm:block">Brazilian medication search</p>
        </div>
    </header>

    <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col px-4 pb-6 sm:px-8 sm:pb-8">
        <div class="card">
            <main class="flex flex-1 flex-col px-6 py-8 sm:px-10 sm:py-10">
                @yield('content')
            </main>
        </div>
    </div>

    <footer class="pb-6">
        <div class="mx-auto max-w-5xl px-4 sm:px-8">
            <p class="text-center text-xs text-stone-400">
                For demonstration only — not medical advice. Prices are CMED PMC 18% (regulated maximum).
            </p>
        </div>
    </footer>
</body>

</html>
