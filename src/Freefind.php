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

/**
 * Main package entry point for account access, hosted URLs, XML searches, and testing fakes.
 */
final class Freefind
{
    /**
     * Creates the package entry point from the configured services and request context.
     */
    public function __construct(
        private readonly FreefindConfig $config,
        private readonly SpiderContext $spiderContext,
        private readonly HostedSearch $hostedSearch,
        private FreefindXmlClient $xmlClient,
        private ?SearchFake $searchFake = null,
    ) {}

    /**
     * Returns the configured Page Search account.
     */
    public function account(): Account
    {
        return $this->config->account;
    }

    /**
     * Returns the configured public FreeFind site identifier.
     */
    public function siteId(): string
    {
        return $this->config->account->siteId;
    }

    /**
     * Determines whether the current request matched a configured spider signature.
     */
    public function isSpiderRequest(): bool
    {
        return $this->spiderContext->isSpider();
    }

    /**
     * Returns the hosted-search URL builder for the configured account.
     */
    public function hostedSearch(): HostedSearch
    {
        return $this->hostedSearch;
    }

    /**
     * Returns the low-level XML search client for the configured account.
     */
    public function xml(): FreefindXmlClient
    {
        return $this->xmlClient;
    }

    /**
     * Starts an immutable high-level XML search builder for a user-entered query.
     *
     * @throws InvalidSearchRequestException When the query is empty or contains unsafe text.
     */
    public function search(string $query): XmlSearchQuery
    {
        return new XmlSearchQuery(
            client: $this->xmlClient,
            account: $this->config->account,
            query: new SimpleQuery($query),
        );
    }

    /**
     * Replaces the XML transport with fixture-backed responses for package tests.
     *
     * @param  list<SearchFixture>  $fixtures
     *
     * @throws InvalidSearchRequestException When fixtures are invalid, empty, or duplicated by query.
     */
    public function fake(array $fixtures): void
    {
        $this->searchFake = new SearchFake($fixtures);
        $this->xmlClient = new FreefindXmlClient(
            $this->searchFake,
            app(XmlResponseParser::class),
        );
    }

    /**
     * Asserts that a recorded fake search satisfies a caller-provided predicate.
     *
     * @param  Closure(\Freefind\Freefind\Testing\SentSearch): bool  $predicate
     *
     * @throws InvalidSearchRequestException When no fake transport has been configured.
     * @throws \RuntimeException When no recorded search satisfies the predicate.
     */
    public function assertSearched(Closure $predicate): void
    {
        if ($this->searchFake === null) {
            throw new InvalidSearchRequestException('FreeFind search assertions require Freefind::fake() first.');
        }

        $this->searchFake->assertSearched($predicate);
    }
}
