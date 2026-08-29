<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;

it('matches the subscribed FreeFind XML contract for an operator-supplied query', function (): void {
    $siteId = getenv('FREEFIND_SITE_ID');
    $query = getenv('FREEFIND_LIVE_QUERY');

    if (! is_string($siteId) || ! is_string($query)) {
        $this->markTestSkipped('The live-contract environment was not configured.');
    }

    $request = new XmlSearchRequest(
        new Account($siteId),
        new SimpleQuery($query),
        new SearchOptions(resultsPerPage: 10),
    );
    $results = resolve(Freefind::class)->xml()->execute($request);

    expect($results->status)->toBe(FreefindStatus::Success)
        ->and($results->total)->toBeGreaterThanOrEqual(0)
        ->and($results->returned)->toBeGreaterThanOrEqual(0);
})->group('live-contract')->skip(fn(): bool => getenv('FREEFIND_LIVE_CONTRACT') !== '1'
    || ! is_string(getenv('FREEFIND_SITE_ID'))
    || trim((string) getenv('FREEFIND_SITE_ID')) === ''
    || ! is_string(getenv('FREEFIND_LIVE_QUERY'))
    || trim((string) getenv('FREEFIND_LIVE_QUERY')) === '');
