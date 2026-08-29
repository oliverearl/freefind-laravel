<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequest;

final readonly class RefinedQuery
{
    public function __construct(
        public SimpleQuery $query,
        public string $previousQuery,
    ) {
        if (trim($this->previousQuery) === '') {
            throw new InvalidSearchRequest('A refined FreeFind search must include the previous query.');
        }

        if (preg_match('//u', $this->previousQuery) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $this->previousQuery) === 1) {
            throw new InvalidSearchRequest('The previous FreeFind query must be valid text without control characters.');
        }
    }

    /**
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
