<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final class MarkupState
{
    /**
     * @var list<string>
     */
    private array $regions = [];

    public function begin(string $region): void
    {
        $this->regions[] = $region;
    }

    public function end(string $region): void
    {
        if ($this->regions === [] || end($this->regions) !== $region) {
            throw new InvalidMarkup("The FreeFind {$region} region ended without a matching start marker.");
        }

        array_pop($this->regions);
    }

    public function isBalanced(): bool
    {
        return $this->regions === [];
    }
}
