<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Xml\Response\SearchWindow;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Pagination extends Component
{
    public readonly ?NavigationUrl $previousUrl;

    public readonly ?NavigationUrl $nextUrl;

    public function __construct(
        public SearchWindow $window,
        string|NavigationUrl|null $previousUrl = null,
        string|NavigationUrl|null $nextUrl = null,
        public string $label = 'Search results pages',
        public string $previousLabel = 'Previous',
        public string $nextLabel = 'Next',
    ) {
        $this->previousUrl = $previousUrl === null ? null : NavigationUrl::from($previousUrl);
        $this->nextUrl = $nextUrl === null ? null : NavigationUrl::from($nextUrl);

        foreach ([$this->label, $this->previousLabel, $this->nextLabel] as $text) {
            if (
                $text === ''
                || preg_match('//u', $text) !== 1
                || preg_match('/[\x00-\x1F\x7F]/', $text) === 1
            ) {
                throw new InvalidMarkup('FreeFind pagination labels must be non-empty valid text without control characters.');
            }
        }
    }

    public function render(): View
    {
        return view('freefind-laravel::components.pagination');
    }
}
