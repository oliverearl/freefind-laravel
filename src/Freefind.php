<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Closure;
use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Exceptions\InvalidSearchRequestException;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Search\Xml\FreefindXmlClient;
use Freefind\Freefind\Search\Xml\XmlSearchQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Spider\SpiderContext;
use Freefind\Freefind\Testing\SearchFake;
use Freefind\Freefind\Testing\SearchFixture;

final class Freefind
{
    public function __construct(
        private readonly FreefindConfig $config,
        private readonly SpiderContext $spiderContext,
        private readonly HostedSearch $hostedSearch,
        private FreefindXmlClient $xmlClient,
        private ?SearchFake $searchFake = null,
    ) {}

    public function account(): Account
    {
        return $this->config->account;
    }

    public function siteId(): string
    {
        return $this->config->account->siteId;
    }

    public function isSpiderRequest(): bool
    {
        return $this->spiderContext->isSpider();
    }

    public function hostedSearch(): HostedSearch
    {
        return $this->hostedSearch;
    }

    public function xml(): FreefindXmlClient
    {
        return $this->xmlClient;
    }

    public function search(string $query): XmlSearchQuery
    {
        return new XmlSearchQuery(
            client: $this->xmlClient,
            account: $this->config->account,
            query: new SimpleQuery($query),
        );
    }

    /**
     * @param  list<SearchFixture>  $fixtures
     */
    public function fake(array $fixtures): void
    {
        $this->searchFake = new SearchFake($fixtures);
        $this->xmlClient = new FreefindXmlClient(
            $this->searchFake,
            app(XmlResponseParser::class),
        );
    }

    public function assertSearched(Closure $predicate): void
    {
        if ($this->searchFake === null) {
            throw new InvalidSearchRequestException('FreeFind search assertions require Freefind::fake() first.');
        }

        $this->searchFake->assertSearched($predicate);
    }
}
