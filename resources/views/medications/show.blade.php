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
@endpush

@section('content')
    <nav class="mb-6">
        <a href="{{ $backUrl }}" class="text-sm font-medium text-teal-700 hover:text-teal-900">
            &larr; Back to search
        </a>
    </nav>

    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 px-6 py-6 sm:px-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                {{ $medication->name }}
            </h1>
            <p class="mt-2 text-base text-slate-600">
                {{ $medication->active_ingredient }}
            </p>
        </header>

        <dl class="divide-y divide-slate-200">
            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4 sm:px-8">
                <dt class="text-sm font-medium text-slate-500">Manufacturer</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $medication->manufacturer }}</dd>
            </div>
            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4 sm:px-8">
                <dt class="text-sm font-medium text-slate-500">Presentation</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">{{ $medication->presentation }}</dd>
            </div>
            <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4 sm:px-8">
                <dt class="text-sm font-medium text-slate-500">Regulated max price</dt>
                <dd class="text-sm text-slate-900 sm:col-span-2">
                    @if ($medication->price_max !== null)
                        <span class="text-lg font-semibold text-teal-800">
                            R$ {{ number_format((float) $medication->price_max, 2, ',', '.') }}
                        </span>
                        <span class="mt-1 block text-xs text-slate-500">CMED PMC 18% (consumer maximum)</span>
                    @else
                        <span class="text-slate-600">Not available — hospital-only or no PMC listing</span>
                    @endif
                </dd>
            </div>
            @if ($medication->registration)
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-slate-500">ANVISA registration</dt>
                    <dd class="text-sm text-slate-900 sm:col-span-2">{{ $medication->registration }}</dd>
                </div>
            @endif
        </dl>
    </article>
@endsection
