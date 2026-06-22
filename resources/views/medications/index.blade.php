@extends('layouts.app')

@section('title', $query !== '' ? "Search: {$query} — MedFinder" : 'MedFinder — Brazilian Medication Search')

@section('content')
    <div class="mx-auto w-full max-w-2xl text-center">
        <p class="text-sm font-medium uppercase tracking-wider text-accent">CMED open data</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">
            Find Brazilian medications
        </h1>
        <p class="mt-3 text-stone-500">
            Search by product name or active ingredient. View manufacturer, presentation, and regulated maximum price.
        </p>
    </div>

    <form action="{{ route('home') }}" method="get" class="mx-auto mt-8 w-full max-w-2xl">
        <label for="q" class="sr-only">Search medications</label>
        <div class="flex flex-col gap-3 sm:flex-row">
            <input
                id="q"
                type="search"
                name="q"
                value="{{ $query }}"
                placeholder="e.g. dipirona, paracetamol, VERZENIOS…"
                autocomplete="off"
                class="input-field"
            >
            <button type="submit" class="btn-primary">
                Search
            </button>
        </div>
    </form>

    @if ($query === '')
        <p class="mx-auto mt-auto pt-10 text-center text-sm text-stone-400">
            Over 25,000 medications indexed. Enter a name or active ingredient to start.
        </p>
    @else
        <div class="mt-10 flex flex-1 flex-col">
            <p class="mb-5 text-sm text-stone-500">
                {{ $medications->total() }} result{{ $medications->total() === 1 ? '' : 's' }} for
                <span class="font-semibold text-stone-800">&ldquo;{{ $query }}&rdquo;</span>
            </p>

            @if ($medications->isEmpty())
                <div class="flex flex-1 items-center justify-center rounded-2xl border border-dashed border-stone-200 px-6 py-12 text-center">
                    <div>
                        <p class="text-stone-600">No medications matched your search.</p>
                        <p class="mt-1 text-sm text-stone-400">Try a different name or active ingredient.</p>
                    </div>
                </div>
            @else
                @php
                    $searchContext = array_filter([
                        'q' => $query,
                        'page' => $medications->currentPage() > 1 ? $medications->currentPage() : null,
                    ]);
                @endphp
                <ul class="-mx-6 divide-y divide-stone-100 sm:-mx-10">
                    @foreach ($medications as $medication)
                        <li class="px-6 py-5 sm:px-10">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-base font-semibold text-stone-900">
                                        <a href="{{ route('medications.show', ['medication' => $medication] + $searchContext) }}"
                                            class="link-accent hover:underline">
                                            {{ $medication->name }}
                                        </a>
                                    </h2>
                                    <p class="mt-1.5 text-sm text-stone-500">
                                        {{ $medication->active_ingredient }}
                                    </p>
                                    <p class="mt-3 text-sm text-stone-400">
                                        {{ $medication->manufacturer }}
                                    </p>
                                    <p class="mt-1 text-sm text-stone-600">
                                        {{ $medication->presentation }}
                                    </p>
                                </div>
                                <div class="shrink-0 rounded-2xl bg-accent-soft px-4 py-3 sm:text-right">
                                    @if ($medication->price_max !== null)
                                        <p class="price-tag">
                                            R$ {{ number_format((float) $medication->price_max, 2, ',', '.') }}
                                        </p>
                                        <p class="mt-0.5 text-xs font-medium text-stone-400">PMC max (18%)</p>
                                    @else
                                        <p class="text-sm text-stone-400">No consumer price</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-auto pt-8">
                    {{ $medications->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
