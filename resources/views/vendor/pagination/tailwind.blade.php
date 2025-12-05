@if ($paginator->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Results info and per-page selector -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    results
                </p>
                <div class="flex items-center gap-2">
                    <label for="perPageInput" class="text-sm text-gray-600 whitespace-nowrap">Items per page:</label>
                    <input type="number" id="perPageInput" min="1" max="1000"
                        value="{{ request('per_page', 15) }}"
                        class="w-20 px-3 py-1.5 text-sm border border-gray-300 rounded-md shadow-sm focus:border-[var(--color-brand-green)] focus:ring focus:ring-[var(--color-brand-green)] focus:ring-opacity-50"
                        onkeypress="if(event.key === 'Enter') changePerPage(this.value)">
                    <button onclick="changePerPage(document.getElementById('perPageInput').value)"
                        class="px-3 py-1.5 text-sm font-medium text-white bg-[var(--color-brand-green)] rounded-md hover:opacity-90 transition-opacity">
                        Go
                    </button>
                </div>
            </div>

            <!-- Pagination controls -->
            <nav aria-label="Pagination" class="flex justify-center sm:justify-end">
                <div class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                    @if ($paginator->onFirstPage())
                        <span
                            class="relative inline-flex items-center rounded-l-md px-3 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                            <span class="sr-only">Previous</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5">
                                <path
                                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="relative inline-flex items-center rounded-l-md px-3 py-2 text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20">
                            <span class="sr-only">Previous</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5">
                                <path
                                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="relative z-10 inline-flex items-center bg-[var(--color-brand-green)] px-4 py-2 text-sm font-semibold text-white focus:z-20">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="relative inline-flex items-center rounded-r-md px-3 py-2 text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20">
                            <span class="sr-only">Next</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5">
                                <path
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span
                            class="relative inline-flex items-center rounded-r-md px-3 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 cursor-default">
                            <span class="sr-only">Next</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5 w-5">
                                <path
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <script>
        function changePerPage(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    </script>
@endif
