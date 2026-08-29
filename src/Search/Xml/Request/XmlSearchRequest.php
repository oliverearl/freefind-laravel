<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\RefinedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;

/**
 * Complete typed input for one FreeFind XML Page Search request.
 */
final readonly class XmlSearchRequest
{
    /**
     * Creates a typed request from an account, query variant, and options.
     */
    public function __construct(
        public Account $account,
        public SimpleQuery|AdvancedQuery|RefinedQuery $query,
        public SearchOptions $options = new SearchOptions(),
    ) {}

    /**
     * Returns account, query, options, and DTD-control fields in protocol order.
     *
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
