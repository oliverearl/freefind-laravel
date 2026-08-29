<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Search\Xml\Response\SpellingSuggestion as ResponseSpellingSuggestion;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Semantic spelling-suggestion component for a parsed search response.
 */
final class SpellingSuggestion extends Component
{
    public readonly ?NavigationUrl $url;

    /**
     * Creates a spelling suggestion with an optional safe navigation URL.
     *
     * @throws \Freefind\Freefind\Exceptions\InvalidMarkupException When the navigation URL is invalid.
     */
    public function __construct(
        public ?ResponseSpellingSuggestion $suggestion,
        string|NavigationUrl|null $url = null,
    ) {
        $this->url = $url === null ? null : NavigationUrl::from($url);
    }

    /**
     * Returns the package's semantic spelling-suggestion view.
     */
    public function render(): View
    {
        return view()->make('freefind-laravel::components.spelling-suggestion');
    }
}
