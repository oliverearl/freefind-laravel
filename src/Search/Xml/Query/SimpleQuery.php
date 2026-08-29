<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

final readonly class SimpleQuery
{
    public function __construct(
        public string $query,
        public MatchMode $matchMode = MatchMode::All,
    ) {
        self::assertQuery($this->query);
    }

    /**
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
