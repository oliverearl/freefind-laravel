<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Results extends Component
{
    public readonly ?NavigationUrl $previousUrl;

    public readonly ?NavigationUrl $nextUrl;

    public readonly ?NavigationUrl $spellingUrl;

    public function __construct(
        public SearchResults $results,
        public string $heading = 'Search results',
        public string $emptyMessage = 'No results found.',
        public string $headingId = 'freefind-results-heading',
        string|NavigationUrl|null $previousUrl = null,
        string|NavigationUrl|null $nextUrl = null,
        string|NavigationUrl|null $spellingUrl = null,
    ) {
        $this->previousUrl = $previousUrl === null ? null : NavigationUrl::from($previousUrl);
        $this->nextUrl = $nextUrl === null ? null : NavigationUrl::from($nextUrl);
        $this->spellingUrl = $spellingUrl === null ? null : NavigationUrl::from($spellingUrl);

        foreach ([$this->heading, $this->emptyMessage] as $text) {
            if (
                $text === ''
                || preg_match('//u', $text) !== 1
                || preg_match('/[\x00-\x1F\x7F]/', $text) === 1
            ) {
                throw new InvalidMarkup('FreeFind result headings and empty-state messages must be non-empty valid text without control characters.');
            }
        }

        if (! preg_match('/^[A-Za-z][A-Za-z0-9_-]{0,63}$/', $this->headingId)) {
            throw new InvalidMarkup('FreeFind result heading IDs must be valid HTML identifiers.');
        }
    }

    public function render(): View
    {
        return view('freefind-laravel::components.results');
    }
}
