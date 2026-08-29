<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmptyState extends Component
{
    public function __construct(public string $message = 'No results found.')
    {
        if (
            $this->message === ''
            || preg_match('//u', $this->message) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $this->message) === 1
        ) {
            throw new InvalidMarkup('FreeFind empty-state messages must be non-empty valid text without control characters.');
        }
    }

    public function render(): View
    {
        return view('freefind-laravel::components.empty-state');
    }
}
