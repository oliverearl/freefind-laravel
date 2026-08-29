<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * Immutable, typed representation of one successful FreeFind XML response.
 */
final readonly class SearchResults
{
    /**
     * Creates a response model with normalized sections, results, and pagination.
     *
     * @param list<string> $sections
     * @param list<SearchResult> $items
     *
     * @throws InvalidSearchRequestException When a result count or offset is negative.
     */
    public function __construct(
        public FreefindStatus $status,
        public string $query,
        public int $total,
        public int $returned,
        public int $offset,
        public array $sections,
        public ?SpellingSuggestion $spelling,
        public bool $usedAutomaticAnyMode,
        public array $items,
        public SearchWindow $window,
    ) {
        if ($this->total < 0 || $this->returned < 0 || $this->offset < 0) {
            throw new InvalidSearchRequestException('FreeFind result counts and offsets must be non-negative.');
        }
    }
}
