<form method="{{ $method }}" action="{{ $action }}"@if ($target !== null) target="{{ $target }}"@endif {{ $attributes->except(['action', 'method', 'target']) }}>
    <label for="{{ $inputId }}">{{ $label }}</label>
    <input id="{{ $inputId }}" type="search" name="query" value="{{ $queryValue() }}">

    @if (isset($before) && $before->isNotEmpty())
        {{ $before }}
    @endif

    <button type="submit">{{ $submitLabel }}</button>

    @foreach ($sectionOptions() as $section)
        <label for="{{ $inputId }}-section-{{ $loop->index }}">
            <input
                id="{{ $inputId }}-section-{{ $loop->index }}"
                type="checkbox"
                name="s"
                value="{{ $section->id }}"
                @checked(in_array($section->id, $selectedSections(), true))
            >
            {{ $section->label }}
        </label>
    @endforeach

    <input type="hidden" name="si" value="{{ app(\Freefind\Freefind\Freefind::class)->siteId() }}">

    @if ($languageValue() !== null)
        <input type="hidden" name="lang" value="{{ $languageValue() }}">
    @endif

    @if ($hideResultsForm)
        <input type="hidden" name="nsb" value="">
    @endif

    @if ($extendedStyles)
        <input type="hidden" name="css" value="">
    @endif

    @if (isset($after) && $after->isNotEmpty())
        {{ $after }}
    @endif
</form>
