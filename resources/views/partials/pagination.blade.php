@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Navigasi halaman">
        <div class="pagination-info">
            Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> data
        </div>
        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="page-link disabled" aria-disabled="true"><x-icon name="arrow-left" :size="16" /></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="Halaman sebelumnya"><x-icon name="arrow-left" :size="16" /></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-link disabled">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next" aria-label="Halaman berikutnya"><x-icon name="arrow-right" :size="16" /></a>
            @else
                <span class="page-link disabled" aria-disabled="true"><x-icon name="arrow-right" :size="16" /></span>
            @endif
        </div>
    </nav>
@endif
