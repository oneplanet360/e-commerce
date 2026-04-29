@if ($paginator->hasPages())
    <nav class="d-flex align-items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled border-0 text-muted" style="background: var(--color-4); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; cursor: not-allowed; opacity: 0.5;">
                <i class="bi bi-chevron-left" style="font-size: 0.8rem;"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link border-0 text-dark" style="background: var(--color-4); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; text-decoration: none;">
                <i class="bi bi-chevron-left" style="font-size: 0.8rem;"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="text-muted mx-1" style="font-size: 0.8rem;">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active border-0 text-white shadow-sm" style="background: var(--brand-color); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="page-link border-0 text-muted" style="background: white; border: 1px solid var(--color-3) !important; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 0.75rem; font-weight: 500; transition: all 0.2s; text-decoration: none;">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link border-0 text-dark" style="background: var(--color-4); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s; text-decoration: none;">
                <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
            </a>
        @else
            <span class="page-link disabled border-0 text-muted" style="background: var(--color-4); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 10px; cursor: not-allowed; opacity: 0.5;">
                <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
            </span>
        @endif
    </nav>
@endif
