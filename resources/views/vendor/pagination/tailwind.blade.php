@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="flex items-center justify-between px-4 py-3 bg-white border border-[var(--color-border-light)] rounded-lg">
        {{-- Previous & Next Buttons (Left Side) --}}
        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                    Next
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>

        {{-- Showing X to Y of Z Results (Center) --}}
        <div class="flex-1 flex justify-center">
            <p class="text-sm text-gray-600">
                Showing
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        {{-- Page Numbers (Right Side) --}}
        <div class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-gray-500">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 py-2 text-sm font-semibold text-white bg-[var(--color-brand-green)] rounded">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </nav>
@endif
