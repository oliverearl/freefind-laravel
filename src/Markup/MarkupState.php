<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Tracks nested FreeFind region markers for one request.
 */
final class MarkupState
{
    /**
     * Request attribute used to store the current markup state.
     */
    public const string REQUEST_ATTRIBUTE = 'freefind.markup_state';

    /**
     * Names of regions currently open in nesting order.
     *
     * @var list<string>
     */
    private array $regions = [];

    /**
     * Opens a named region and records it for balanced closing.
     */
    public function begin(string $region): void
    {
        $this->regions[] = $region;
    }

    /**
     * Closes the most recently opened region.
     *
     * @throws InvalidMarkupException When the region does not match the open marker.
     */
    public function end(string $region): void
    {
        if ($this->regions === [] || end($this->regions) !== $region) {
            throw new InvalidMarkupException("The FreeFind {$region} region ended without a matching start marker.");
        }

        array_pop($this->regions);
    }

    /**
     * Determines whether all opened regions have been closed.
     */
    public function isBalanced(): bool
    {
        return $this->regions === [];
    }
}
