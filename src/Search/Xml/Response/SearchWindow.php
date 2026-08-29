<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * Local pagination window calculated from the requested page and total result count.
 */
final readonly class SearchWindow
{
    /**
     * Creates a validated pagination window.
     *
     * @throws InvalidSearchRequestException When the offset or total is negative, or the page size is below one.
     */
    public function __construct(
        public int $offset,
        public int $resultsPerPage,
        public int $total,
    ) {
        if ($this->offset < 0 || $this->resultsPerPage < 1 || $this->total < 0) {
            throw new InvalidSearchRequestException('FreeFind pagination values must be non-negative and bounded.');
        }
    }

    /**
     * Determines whether an earlier result page exists.
     */
    public function hasPrevious(): bool
    {
        return $this->offset > 0;
    }

    /**
     * Returns the zero-based offset for the previous result page.
     */
    public function previousOffset(): int
    {
        return max(0, $this->offset - $this->resultsPerPage);
    }

    /**
     * Determines whether a later result page exists.
     */
    public function hasNext(): bool
    {
        return $this->offset + $this->resultsPerPage < $this->total;
    }

    /**
     * Returns the zero-based offset for the next result page.
     */
    public function nextOffset(): int
    {
        return $this->offset + $this->resultsPerPage;
    }
}
