@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <ul class="inline-flex items-center -space-x-px">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-1 rounded-l-md bg-gray-200 text-gray-500 cursor-not-allowed">
                        Précedent
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 rounded-l-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Précedent
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-1 bg-white border border-gray-300 text-gray-700">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-1 bg-primary-600 text-white border border-primary-600">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 rounded-r-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Suivant
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-1 rounded-r-md bg-gray-200 text-gray-500 cursor-not-allowed">
                        Suivant
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
