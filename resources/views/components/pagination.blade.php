@if (($window->hasPrevious() && $previousUrl !== null) || ($window->hasNext() && $nextUrl !== null))
    <nav aria-label="{{ $label }}">
        @if ($window->hasPrevious() && $previousUrl !== null)
            <a href="{{ $previousUrl }}">{{ $previousLabel }}</a>
        @endif

        @if ($window->hasNext() && $nextUrl !== null)
            <a href="{{ $nextUrl }}">{{ $nextLabel }}</a>
        @endif
    </nav>
@endif
