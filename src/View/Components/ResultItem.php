<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Search\Xml\Response\SearchResult;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ResultItem extends Component
{
    public function __construct(public SearchResult $result) {}

    public function render(): View
    {
        return view('freefind-laravel::components.result-item');
    }
}
