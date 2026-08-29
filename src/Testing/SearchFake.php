<?php

declare(strict_types=1);

namespace Freefind\Freefind\Testing;

use Closure;
use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;
use RuntimeException;

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
     * @param  list<mixed>  $fixtures
     */
    public function __construct(array $fixtures)
    {
        $this->fixtures = [];

        foreach ($fixtures as $fixture) {
            if (! $fixture instanceof SearchFixture || $fixture->body === '') {
                throw new InvalidSearchRequest('FreeFind fake fixtures must be loaded SearchFixture instances.');
            }

            if (array_key_exists($fixture->query, $this->fixtures)) {
                throw new InvalidSearchRequest('FreeFind fake fixture queries must be unique.');
            }

            $this->fixtures[$fixture->query] = $fixture;
        }
    }

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
