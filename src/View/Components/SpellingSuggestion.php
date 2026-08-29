<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Search\Xml\Response\SpellingSuggestion as ResponseSpellingSuggestion;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SpellingSuggestion extends Component
{
    public readonly ?NavigationUrl $url;

    public function __construct(
        public ?ResponseSpellingSuggestion $suggestion,
        string|NavigationUrl|null $url = null,
    ) {
        $this->url = $url === null ? null : NavigationUrl::from($url);
    }

    public function render(): View
    {
        return view('freefind-laravel::components.spelling-suggestion');
    }
}
