<?php

declare(strict_types=1);

namespace Freefind\Freefind\Testing;

use Closure;
use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Exceptions\InvalidSearchRequestException;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;
use RuntimeException;

/**
 * Fixture-backed XML transport that records the searches made by a test.
 */
final class SearchFake implements SearchTransport
{
    /**
     * @var array<string, SearchFixture>
     */
    private array $fixtures;

    /**
     * @var list<SentSearch>
     */
    private array $sent = [];

    /**
     * Creates a fake transport from unique, loaded search fixtures.
     *
     * @param list<mixed> $fixtures
     *
     * @throws InvalidSearchRequestException When an entry is not a loaded fixture or queries are duplicated.
     */
    public function __construct(array $fixtures)
    {
        $this->fixtures = [];

        foreach ($fixtures as $fixture) {
            if (! $fixture instanceof SearchFixture || $fixture->body === '') {
                throw new InvalidSearchRequestException('FreeFind fake fixtures must be loaded SearchFixture instances.');
            }

            if (array_key_exists($fixture->query, $this->fixtures)) {
                throw new InvalidSearchRequestException('FreeFind fake fixture queries must be unique.');
            }

            $this->fixtures[$fixture->query] = $fixture;
        }
    }

    /**
     * Records a request and returns the fixture matching its effective query.
     *
     * @throws RuntimeException When no fixture matches the request query.
     */
    public function send(XmlSearchRequest $request): XmlTransportResponse
    {
        $sent = SentSearch::from($request);
        $this->sent[] = $sent;
        $fixture = $this->fixtures[$sent->query] ?? null;

        if ($fixture === null) {
            throw new RuntimeException('No FreeFind fake fixture matched the search.');
        }

        return new XmlTransportResponse(200, $fixture->body, ['Content-Type' => ['application/xml']]);
    }

    /**
     * Asserts that at least one recorded search satisfies the supplied predicate.
     *
     * @param Closure(SentSearch): bool $predicate
     *
     * @throws RuntimeException When no recorded search satisfies the predicate.
     */
    public function assertSearched(Closure $predicate): void
    {
        foreach ($this->sent as $search) {
            if ($predicate($search)) {
                return;
            }
        }

        throw new RuntimeException('No FreeFind search matched the supplied assertion.');
    }
}
