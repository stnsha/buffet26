@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm text-grey-400 cursor-not-allowed">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-grey-700 hover:text-primary-600 transition-colors">
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 py-2 text-sm text-grey-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="min-w-[2rem] px-3 py-2 text-sm font-medium text-primary-600 border-b-2 border-primary-600">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="min-w-[2rem] px-3 py-2 text-sm text-grey-600 hover:text-primary-600 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-grey-700 hover:text-primary-600 transition-colors">
                Next
            </a>
        @else
            <span class="px-3 py-2 text-sm text-grey-400 cursor-not-allowed">
                Next
            </span>
        @endif
    </nav>
@endif
