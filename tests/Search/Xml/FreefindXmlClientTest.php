<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Search\Xml\FreefindXmlClient;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Response\SearchWindow;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

it('executes exactly one transport call and parses its response with the original request', function (): void {
    $request = new XmlSearchRequest(new Account('0012345'), new SimpleQuery('blade directive'));
    $response = new XmlTransportResponse(200, '<ret><sts>0</sts></ret>');
    $results = new SearchResults(
        status: FreefindStatus::Success,
        query: 'blade directive',
        total: 0,
        returned: 0,
        offset: 0,
        sections: [],
        spelling: null,
        usedAutomaticAnyMode: false,
        items: [],
        window: new SearchWindow(0, 10, 0),
    );

    $transport = new class ($response) implements SearchTransport {
        public int $calls = 0;

        public ?XmlSearchRequest $request = null;

        public function __construct(private readonly XmlTransportResponse $response) {}

        public function send(XmlSearchRequest $request): XmlTransportResponse
        {
            $this->calls++;
            $this->request = $request;

            return $this->response;
        }
    };
    $parser = new class ($results) implements XmlResponseParser {
        public int $calls = 0;

        public ?XmlSearchRequest $request = null;

        public ?XmlTransportResponse $response = null;

        public function __construct(private readonly SearchResults $results) {}

        public function parse(XmlTransportResponse $response, XmlSearchRequest $request): SearchResults
        {
            $this->calls++;
            $this->response = $response;
            $this->request = $request;

            return $this->results;
        }
    };

    $client = new FreefindXmlClient($transport, $parser);

    expect($client->execute($request))->toBe($results)
        ->and($transport->calls)->toBe(1)
        ->and($transport->request)->toBe($request)
        ->and($parser->calls)->toBe(1)
        ->and($parser->request)->toBe($request)
        ->and($parser->response)->toBe($response);
});

it('does not send a request before the explicit terminal call', function (): void {
    $request = new XmlSearchRequest(new Account('0012345'), new SimpleQuery('blade directive'));
    $transport = new class implements SearchTransport {
        public int $calls = 0;

        public function send(XmlSearchRequest $request): XmlTransportResponse
        {
            $this->calls++;

            return new XmlTransportResponse(200, '<ret><sts>0</sts></ret>');
        }
    };
    $parser = new class implements XmlResponseParser {
        public function parse(XmlTransportResponse $response, XmlSearchRequest $request): SearchResults
        {
            return new SearchResults(
                status: FreefindStatus::Success,
                query: 'blade directive',
                total: 0,
                returned: 0,
                offset: 0,
                sections: [],
                spelling: null,
                usedAutomaticAnyMode: false,
                items: [],
                window: new SearchWindow(0, 10, 0),
            );
        }
    };

    new FreefindXmlClient($transport, $parser);

    expect($transport->calls)->toBe(0);
});
