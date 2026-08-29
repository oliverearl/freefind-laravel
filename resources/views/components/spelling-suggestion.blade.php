@if ($suggestion !== null)
    <p role="status">
        Did you mean
        @if ($url !== null)
            <a href="{{ $url }}">{{ $suggestion->query }}</a>
        @else
            <span>{{ $suggestion->query }}</span>
        @endif
       ?
    </p>
@endif
