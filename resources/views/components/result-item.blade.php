<li>
    <article>
        <h3>
            <a
                href="{{ $result->url }}"
                @if ($result->target !== null) target="{{ $result->target }}" @endif
                @if ($result->target === '_blank') rel="noopener noreferrer" @endif
            >{{ $result->title }}</a>
        </h3>

        <p>{{ $result->description }}</p>
        <p>{{ $result->displayUrl }}</p>

        @if ($result->date !== null)
            <time datetime="{{ $result->date->format(DATE_ATOM) }}">{{ $result->date->format('Y-m-d') }}</time>
        @endif
    </article>
</li>
