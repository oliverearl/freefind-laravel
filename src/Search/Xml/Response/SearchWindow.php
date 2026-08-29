<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use Freefind\Freefind\Exceptions\InvalidSearchRequest;

final readonly class SearchWindow
{
    public function __construct(
        public int $offset,
        public int $resultsPerPage,
        public int $total,
    ) {
        if ($this->offset < 0 || $this->resultsPerPage < 1 || $this->total < 0) {
            throw new InvalidSearchRequest('FreeFind pagination values must be non-negative and bounded.');
        }
    }

    public function hasPrevious(): bool
    {
        return $this->offset > 0;
    }

    public function previousOffset(): int
    {
        return max(0, $this->offset - $this->resultsPerPage);
    }

    public function hasNext(): bool
    {
        return $this->offset + $this->resultsPerPage < $this->total;
    }

    public function nextOffset(): int
    {
        return $this->offset + $this->resultsPerPage;
    }
}
