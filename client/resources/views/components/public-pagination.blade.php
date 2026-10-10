@props(['paginator', 'label' => 'Pages'])

@if($paginator->hasPages())
    @php
        $visiblePages = collect([1, 2, $paginator->currentPage() - 1, $paginator->currentPage(), $paginator->currentPage() + 1, $paginator->lastPage() - 1, $paginator->lastPage()])
            ->filter(fn ($page) => $page >= 1 && $page <= $paginator->lastPage())
            ->unique()->sort()->values();
        $previousPage = null;
    @endphp
    <nav class="agency-pagination" aria-label="{{ $label }}">
        <div class="agency-pagination-list">
            @if($paginator->onFirstPage())<span class="agency-pagination-link is-disabled" aria-disabled="true">Previous</span>
            @else<a class="agency-pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>@endif
            @foreach($visiblePages as $page)
                @if($previousPage && $page > $previousPage + 1)<span class="agency-pagination-ellipsis" aria-hidden="true">…</span>@endif
                @if($page === $paginator->currentPage())<span class="agency-pagination-link is-current" aria-current="page">{{ $page }}</span>
                @else<a class="agency-pagination-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>@endif
                @php($previousPage = $page)
            @endforeach
            @if($paginator->hasMorePages())<a class="agency-pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            @else<span class="agency-pagination-link is-disabled" aria-disabled="true">Next</span>@endif
        </div>
    </nav>
@endif
