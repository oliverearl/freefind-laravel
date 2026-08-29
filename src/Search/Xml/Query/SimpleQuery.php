<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * A validated user-entered FreeFind search query.
 */
final readonly class SimpleQuery
{
    /**
     * Creates a simple query with the requested match mode.
     *
     * @throws InvalidSearchRequestException When the query is empty or contains unsafe text.
     */
    public function __construct(
        public string $query,
        public MatchMode $matchMode = MatchMode::All,
    ) {
        self::assertQuery($this->query);
    }

    /**
     * Returns the simple query fields as request pairs.
     *
     * @return list<array{0: string, 1: string}>
     */
    public function pairs(): array
    {
        $pairs = [['query', $this->query]];

        if ($this->matchMode !== MatchMode::All) {
            $pairs[] = ['mode', $this->matchMode->value];
        }

        return $pairs;
    }

    /**
     * Validates the query text accepted by a simple search.
     *
     * @throws InvalidSearchRequestException When the query is empty or contains unsafe text.
     */
    private static function assertQuery(string $query): void
    {
        if (
            trim($query) === ''
            || preg_match('//u', $query) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $query) === 1
        ) {
            throw new InvalidSearchRequestException('FreeFind search queries must be non-empty valid text without control characters.');
        }
    }
}
