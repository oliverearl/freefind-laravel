<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Contracts\SearchClient;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Response\SearchWindow;
use Freefind\Freefind\Search\Xml\XmlSearchQuery;

it('builds immutable options and executes only when get is called', function (): void {
    $results = new SearchResults(
        status: FreefindStatus::Success,
        query: 'blade directive',
        total: 25,
        returned: 15,
        offset: 10,
        sections: ['manuals', 'releases'],
        spelling: null,
        usedAutomaticAnyMode: false,
        items: [],
        window: new SearchWindow(10, 15, 25),
    );
    $client = new class ($results) implements SearchClient {
        public int $calls = 0;

        public ?XmlSearchRequest $request = null;

        public function __construct(private readonly SearchResults $results) {}

        public function execute(XmlSearchRequest $request): SearchResults
        {
            $this->calls++;
            $this->request = $request;

            return $this->results;
        }
    };
    $query = new XmlSearchQuery($client, new Account('0012345'), new SimpleQuery('blade directive'));
    $configured = $query
        ->inSections(['manuals', 'releases'])
        ->sortBy(SortOrder::Date)
        ->stemUsing(Stemming::English)
        ->perPage(15)
        ->startingAt(10);

    expect($configured)->not->toBe($query)
        ->and($client->calls)->toBe(0)
        ->and($configured->get())->toBe($results)
        ->and($client->calls)->toBe(1)
        ->and($client->request?->options->sections)->toBe(['manuals', 'releases'])
        ->and($client->request?->options->sort)->toBe(SortOrder::Date)
        ->and($client->request?->options->stemming)->toBe(Stemming::English)
        ->and($client->request?->options->resultsPerPage)->toBe(15)
        ->and($client->request?->options->offset)->toBe(10)
        ->and($query->get())->toBe($results)
        ->and($client->calls)->toBe(2);
});
