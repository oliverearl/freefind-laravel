<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Search\Xml\Query\MatchMode;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlRequestEncoder;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Request\XmlSearchUrl;

beforeEach(function (): void {
    $this->encoder = new XmlRequestEncoder();
    $this->account = new Account('0012345');
});

it('encodes a subscription request with repeated sections and dtd disabled', function (): void {
    $request = new XmlSearchRequest(
        $this->account,
        new SimpleQuery('laravel middleware', MatchMode::Any),
        new SearchOptions(resultsPerPage: 3, sections: ['manuals', 'releases']),
    );

    expect($this->encoder->url($request)->value)
        ->toBe(trim(file_get_contents(__DIR__ . '/../../Fixtures/xml/simple-request.txt')));
});

it('represents secure XML URLs and rejects unsafe standalone values', function (): void {
    $url = new XmlSearchUrl('https://search.freefind.com/find.xml?si=0012345&dtd=n');

    expect((string) $url)->toBe($url->value)
        ->and(fn(): XmlSearchUrl => new XmlSearchUrl('http://search.freefind.com/find.xml'))
        ->toThrow(InvalidSearchRequest::class)
        ->and(fn(): XmlSearchUrl => new XmlSearchUrl('https://user:pass@search.freefind.com/find.xml'))
        ->toThrow(InvalidSearchRequest::class);
});
