@extends('layouts.app')

@section('title', $seoTitle . ' — MedFinder')

@section('meta_description', $seoDescription)

@section('canonical', $canonicalUrl)

@section('og')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <nav aria-label="Breadcrumb" class="mb-6">
        <ol class="flex flex-wrap items-center gap-2 text-sm text-stone-400">
            @foreach ($breadcrumbs as $crumb)
                @if (! $loop->last)
                    <li><a href="{{ $crumb['url'] }}" class="hover:text-accent">{{ $crumb['name'] }}</a></li>
                    <li aria-hidden="true" class="text-stone-300">›</li>
                @else
                    <li class="font-medium text-stone-600" aria-current="page">{{ $crumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>

    <nav class="mb-8">
        <a href="{{ $backUrl }}" class="btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to search
        </a>
    </nav>

    <article class="flex flex-1 flex-col">
        <header class="border-b border-stone-100 pb-8">
            <p class="text-xs font-semibold uppercase tracking-wider text-accent">Medication</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-stone-900 sm:text-3xl">
                {{ $medication->name }}
            </h1>
            <p class="mt-2 text-base text-stone-500">
                {{ $medication->active_ingredient }}
            </p>
        </header>

        <dl class="divide-y divide-stone-100">
            <div class="grid gap-1 py-5 sm:grid-cols-3 sm:gap-6 sm:py-6">
                <dt class="text-sm font-medium text-stone-400">Manufacturer</dt>
                <dd class="text-sm text-stone-800 sm:col-span-2">{{ $medication->manufacturer }}</dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-3 sm:gap-6 sm:py-6">
                <dt class="text-sm font-medium text-stone-400">Presentation</dt>
                <dd class="text-sm text-stone-800 sm:col-span-2">{{ $medication->presentation }}</dd>
            </div>
            <div class="grid gap-1 py-5 sm:grid-cols-3 sm:gap-6 sm:py-6">
                <dt class="text-sm font-medium text-stone-400">Regulated max price</dt>
                <dd class="sm:col-span-2">
                    @if ($medication->price_max !== null)
                        <span class="price-tag text-xl">
                            R$ {{ number_format((float) $medication->price_max, 2, ',', '.') }}
                        </span>
                        <span class="mt-1 block text-xs text-stone-400">CMED PMC 18% (consumer maximum)</span>
                    @else
                        <span class="text-sm text-stone-500">Not available — hospital-only or no PMC listing</span>
                    @endif
                </dd>
            </div>
            @if ($medication->registration)
                <div class="grid gap-1 py-5 sm:grid-cols-3 sm:gap-6 sm:py-6">
                    <dt class="text-sm font-medium text-stone-400">ANVISA registration</dt>
                    <dd class="text-sm font-mono text-stone-800 sm:col-span-2">{{ $medication->registration }}</dd>
                </div>
            @endif
        </dl>

        <details class="mt-8 rounded-lg border border-stone-200 bg-stone-50">
            <summary class="cursor-pointer px-4 py-3 text-sm font-medium text-stone-600">
                🔎 Structured data for AI &amp; search (schema.org/Drug)
            </summary>
            <p class="px-4 pt-1 text-xs text-stone-400">
                This page exposes the data below as JSON-LD in its &lt;head&gt; so search engines and AI assistants can read and cite it.
            </p>
            <pre class="mt-2 overflow-x-auto border-t border-stone-200 px-4 py-3 text-xs text-stone-700"><code>{{ json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</code></pre>
        </details>
    </article>
@endsection
