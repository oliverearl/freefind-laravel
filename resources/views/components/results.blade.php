<section aria-labelledby="{{ $headingId }}" {{ $attributes }}>
    <h2 id="{{ $headingId }}">{{ $heading }}</h2>

    @if ($results->spelling !== null)
        <x-freefind::spelling-suggestion :suggestion="$results->spelling" :url="$spellingUrl" />
    @endif

    @if ($results->items === [])
        <x-freefind::empty-state :message="$emptyMessage" />
    @else
        <ol>
            @foreach ($results->items as $result)
                <x-freefind::result-item :result="$result" />
            @endforeach
        </ol>
    @endif

    @if ($previousUrl !== null || $nextUrl !== null)
        <x-freefind::pagination
            :window="$results->window"
            :previous-url="$previousUrl"
            :next-url="$nextUrl"
        />
    @endif
</section>
