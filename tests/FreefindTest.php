<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Search\Xml\FreefindXmlClient;
use Freefind\Freefind\Search\Xml\XmlSearchQuery;
use Freefind\Freefind\Testing\SearchFixture;
use Freefind\Freefind\Testing\SentSearch;
use Freefind\Freefind\Facades\Freefind as FreefindFacade;

beforeEach(function (): void {
    $this->freefind = resolve(Freefind::class);
});

it('exposes the configured account and string site id', function (): void {
    config(['freefind-laravel.site_id' => '0012345']);
    app()->forgetInstance(Freefind::class);
    app()->forgetInstance(FreefindConfig::class);

    $freefind = resolve(Freefind::class);

    expect($freefind->siteId())->toBe('0012345')
        ->and($freefind->account()->siteId)->toBe('0012345')
        ->and($freefind->hostedSearch())->toBeInstanceOf(HostedSearch::class)
        ->and($freefind->xml())->toBeInstanceOf(FreefindXmlClient::class)
        ->and($freefind->search('query'))->toBeInstanceOf(XmlSearchQuery::class);
});

it('reports a request as a spider only through the request-local context', function (): void {
    expect($this->freefind->isSpiderRequest())->toBeFalse();
});

it('supports fixture-backed search assertions without using the network', function (): void {
    FreefindFacade::fake([
        SearchFixture::for('blade directive')->fromFile(__DIR__ . '/Fixtures/xml/success.xml'),
    ]);

    $results = FreefindFacade::search('blade directive')
        ->inSections(['manuals'])
        ->get();

    expect($results->items)->toHaveCount(2);

    FreefindFacade::assertSearched(fn(SentSearch $search): bool => $search->query === 'blade directive'
        && $search->sections === ['manuals']);
});
