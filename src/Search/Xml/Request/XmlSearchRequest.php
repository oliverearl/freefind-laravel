<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\RefinedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;

final readonly class XmlSearchRequest
{
    public function __construct(
        public Account $account,
        public SimpleQuery|AdvancedQuery|RefinedQuery $query,
        public SearchOptions $options = new SearchOptions(),
    ) {}

    /**
     * @return list<array{0: string, 1: string}>
     */
    public function pairs(): array
    {
        return [
            ['si', $this->account->siteId],
            ...$this->query->pairs(),
            ...$this->options->pairs(),
            ['dtd', 'n'],
        ];
    }
}
