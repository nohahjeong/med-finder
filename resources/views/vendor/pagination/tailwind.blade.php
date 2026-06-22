@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $onEachSide = 3;

        $rangeStart = max(1, $current - $onEachSide);
        $rangeEnd = min($last, $current + $onEachSide);

        $pageItems = [];

        if ($rangeStart > 1) {
            $pageItems[] = ['type' => 'page', 'page' => 1];
            if ($rangeStart > 2) {
                $pageItems[] = ['type' => 'dots'];
            }
        }

        for ($page = $rangeStart; $page <= $rangeEnd; $page++) {
            $pageItems[] = ['type' => 'page', 'page' => $page];
        }

        if ($rangeEnd < $last) {
            if ($rangeEnd < $last - 1) {
                $pageItems[] = ['type' => 'dots'];
            }
            $pageItems[] = ['type' => 'page', 'page' => $last];
        }
    @endphp

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex items-center justify-between gap-4">
            <p class="hidden text-sm text-stone-500 sm:block">
                @if ($paginator->firstItem())
                    {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
                @else
                    {{ $paginator->count() }} results
                @endif
            </p>

            <div class="flex flex-1 items-center justify-center gap-1 sm:justify-end">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-stone-100 bg-stone-50 text-stone-300" aria-disabled="true">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-stone-200 bg-white text-stone-600 transition hover:border-accent/30 hover:bg-accent-soft hover:text-accent"
                        aria-label="{{ __('pagination.previous') }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                @foreach ($pageItems as $item)
                    @if ($item['type'] === 'dots')
                        <span class="inline-flex h-10 min-w-10 items-center justify-center px-2 text-sm text-stone-400">&hellip;</span>
                    @elseif ($item['page'] == $current)
                        <span aria-current="page"
                            class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border-2 border-accent bg-accent px-3 text-sm font-semibold text-white">
                            {{ $item['page'] }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($item['page']) }}"
                            class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-stone-200 bg-white px-3 text-sm font-medium text-stone-600 transition hover:border-accent/30 hover:bg-accent-soft hover:text-accent"
                            aria-label="{{ __('Go to page :page', ['page' => $item['page']]) }}">
                            {{ $item['page'] }}
                        </a>
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-stone-200 bg-white text-stone-600 transition hover:border-accent/30 hover:bg-accent-soft hover:text-accent"
                        aria-label="{{ __('pagination.next') }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-stone-100 bg-stone-50 text-stone-300" aria-disabled="true">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
