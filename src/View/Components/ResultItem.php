<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Search\Xml\Response\SearchResult;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Semantic presentation component for one normalized search result.
 */
final class ResultItem extends Component
{
    /**
     * Creates a result-item component from one parsed search result.
     */
    public function __construct(public SearchResult $result) {}

    /**
     * Returns the package's semantic result-item view.
     */
    public function render(): View
    {
        return view('freefind-laravel::components.result-item');
    }
}
