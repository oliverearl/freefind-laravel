<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * A simple query refined using the query submitted immediately before it.
 */
final readonly class RefinedQuery
{
    /**
     * Creates a refined query with a non-empty, validated previous query.
     *
     * @throws InvalidSearchRequestException When the previous query is empty or unsafe.
     */
    public function __construct(
        public SimpleQuery $query,
        public string $previousQuery,
    ) {
        if (trim($this->previousQuery) === '') {
            throw new InvalidSearchRequestException('A refined FreeFind search must include the previous query.');
        }

        if (preg_match('//u', $this->previousQuery) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $this->previousQuery) === 1) {
            throw new InvalidSearchRequestException('The previous FreeFind query must be valid text without control characters.');
        }
    }

    /**
     * Returns the refined query and previous-query fields as request pairs.
     *
     * @return list<array{0: string, 1: string}>
     */
    public function pairs(): array
    {
        return [
            ...$this->query->pairs(),
            ['oq', $this->previousQuery],
            ['search', 'these'],
        ];
    }
}
