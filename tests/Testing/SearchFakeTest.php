<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Testing\SearchFake;
use Freefind\Freefind\Testing\SearchFixture;

it('returns fixture responses and records typed search intent', function (): void {
    $fake = new SearchFake([
        SearchFixture::for('blade directive')->fromFile(__DIR__ . '/../Fixtures/xml/success.xml'),
    ]);
    $request = new XmlSearchRequest(new Account('0012345'), new SimpleQuery('blade directive'));

    expect($fake->send($request)->body)->toContain('<sts>0</sts>');

    $fake->assertSearched(fn($search): bool => $search->query === 'blade directive');
});

it('rejects missing or duplicate fixture matches', function (): void {
    $fixture = SearchFixture::for('blade directive')->fromFile(__DIR__ . '/../Fixtures/xml/success.xml');

    expect(fn(): SearchFake => new SearchFake([$fixture, $fixture]))
        ->toThrow(InvalidSearchRequest::class);

    $fake = new SearchFake([$fixture]);

    expect(fn(): mixed => $fake->send(new XmlSearchRequest(new Account('0012345'), new SimpleQuery('missing'))))
        ->toThrow(\RuntimeException::class);
});
