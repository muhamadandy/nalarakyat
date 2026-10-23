@if ($paginator->hasPages())
<nav style="display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap">
    @php
        $base = 'display:flex;align-items:center;justify-content:center;min-width:42px;height:42px;padding:0 14px;border-radius:9999px;border:1px solid #EEF0F7;font-weight:600;font-size:14px;';
    @endphp

    @if ($paginator->onFirstPage())
        <span style="{{ $base }}color:#A3A6AE">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="{{ $base }}">‹</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="{{ $base }}border:none;color:#A3A6AE">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="{{ $base }}background:#b91c1c;border-color:#b91c1c;color:#fff">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="{{ $base }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="{{ $base }}">›</a>
    @else
        <span style="{{ $base }}color:#A3A6AE">›</span>
    @endif
</nav>
@endif
