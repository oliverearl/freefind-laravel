<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use Freefind\Freefind\Exceptions\InvalidSearchRequest;

final readonly class SearchResults
{
    /**
     * @param  list<string>  $sections
     * @param  list<SearchResult>  $items
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
            throw new InvalidSearchRequest('FreeFind result counts and offsets must be non-negative.');
        }
    }
}
