@extends('layouts.app')

@section('title', $query !== '' ? "Search: {$query} — MedFinder" : 'MedFinder — Brazilian Medication Search')

@section('content')
    <div class="mx-auto max-w-2xl text-center">
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
            Find Brazilian medications
        </h1>
        <p class="mt-3 text-slate-600">
            Search by product name or active ingredient. View manufacturer, presentation, and regulated maximum price.
        </p>
    </div>

    <form action="{{ route('home') }}" method="get" class="mx-auto mt-8 max-w-2xl">
        <label for="q" class="sr-only">Search medications</label>
        <div class="flex gap-2">
            <input id="q" type="search" name="q" value="{{ $query }}"
                placeholder="e.g. dipirona, paracetamol, VERZENIOS…" autocomplete="off"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-base shadow-sm placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            <button type="submit"
                class="shrink-0 rounded-lg bg-teal-700 px-5 py-3 text-sm font-medium text-white shadow-sm hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                Search
            </button>
        </div>
    </form>

    @if ($query === '')
        <p class="mx-auto mt-10 max-w-xl text-center text-sm text-slate-500">
            Over 25,000 medications from CMED open data. Enter a name or active ingredient to start.
        </p>
    @else
        <div class="mt-10">
            <p class="mb-4 text-sm text-slate-600">
                {{ $medications->total() }} result{{ $medications->total() === 1 ? '' : 's' }} for
                <span class="font-medium text-slate-900">&ldquo;{{ $query }}&rdquo;</span>
            </p>

            @if ($medications->isEmpty())
                <div class="rounded-lg border border-slate-200 bg-white px-6 py-10 text-center">
                    <p class="text-slate-600">No medications matched your search.</p>
                    <p class="mt-1 text-sm text-slate-500">Try a different name or active ingredient.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-200 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    @foreach ($medications as $medication)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-base font-semibold text-slate-900">
                                        {{ $medication->name }}
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $medication->active_ingredient }}
                                    </p>
                                    <p class="mt-2 text-sm text-slate-500">
                                        {{ $medication->manufacturer }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $medication->presentation }}
                                    </p>
                                </div>
                                <div class="shrink-0 sm:text-right">
                                    @if ($medication->price_max !== null)
                                        <p class="text-lg font-semibold text-teal-800">
                                            R$ {{ number_format((float) $medication->price_max, 2, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-slate-500">PMC max (18%)</p>
                                    @else
                                        <p class="text-sm text-slate-500">No consumer price</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    {{ $medications->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
