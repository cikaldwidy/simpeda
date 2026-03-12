@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

    {{-- MOBILE --}}
    <div class="flex items-center justify-between sm:hidden">
        @if ($paginator->onFirstPage())
        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-300 cursor-not-allowed">
            {!! __('pagination.previous') !!}
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-800 rounded-lg hover:text-orange-500 hover:bg-orange-50 transition-colors duration-200">
            {!! __('pagination.previous') !!}
        </a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-800 rounded-lg hover:text-orange-500 hover:bg-orange-50 transition-colors duration-200">
            {!! __('pagination.next') !!}
        </a>
        @else
        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-300 cursor-not-allowed">
            {!! __('pagination.next') !!}
        </span>
        @endif
    </div>

    {{-- DESKTOP --}}
    <div class="hidden sm:flex sm:items-center sm:justify-between sm:gap-4">

        {{-- Info --}}
        <p class="text-sm text-gray-500">
            Menampilkan
            @if ($paginator->firstItem())
            <span class="font-semibold text-gray-800">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-semibold text-gray-800">{{ $paginator->lastItem() }}</span>
            @else
            {{ $paginator->count() }}
            @endif
            dari <span class="font-semibold text-gray-800">{{ $paginator->total() }}</span> data
        </p>

        {{-- Page buttons --}}
        <div class="flex items-center gap-1">

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-700 hover:text-orange-500 hover:bg-orange-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
            @if (is_string($element))
            <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400">
                {{ $element }}
            </span>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <span aria-current="page"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-bold text-white bg-orange-500">
                {{ $page }}
            </span>
            @else
            <a href="{{ $url }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium text-gray-700 hover:text-orange-500 hover:bg-orange-50 transition-colors duration-200">
                {{ $page }}
            </a>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-700 hover:text-orange-500 hover:bg-orange-50 transition-colors duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @endif

        </div>
    </div>

</nav>
@endif