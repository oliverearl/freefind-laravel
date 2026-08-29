<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Semantic empty-state message for a result list with no matches.
 */
final class EmptyState extends Component
{
    /**
     * Creates an empty-state component with safe display text.
     *
     * @throws InvalidMarkupException When the message is empty or contains unsafe text.
     */
    public function __construct(public string $message = 'No results found.')
    {
        if (
            $this->message === ''
            || preg_match('//u', $this->message) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $this->message) === 1
        ) {
            throw new InvalidMarkupException('FreeFind empty-state messages must be non-empty valid text without control characters.');
        }
    }

    /**
     * Returns the package's semantic empty-state view.
     */
    public function render(): View
    {
        return view('freefind-laravel::components.empty-state');
    }
}
