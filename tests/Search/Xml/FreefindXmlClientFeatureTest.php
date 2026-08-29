<?php

declare(strict_types=1);

use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('executes a user-initiated search through the configured Laravel transport and parser', function (): void {
    Http::fake([
        'https://search.freefind.com/*' => Http::response(
            file_get_contents(__DIR__ . '/../../Fixtures/xml/success.xml'),
            200,
            ['Content-Type' => 'application/xml'],
        ),
    ]);

    $freefind = resolve(Freefind::class);
    $request = new XmlSearchRequest($freefind->account(), new SimpleQuery('blade directive'));

    Http::assertNothingSent();

    $results = $freefind->xml()->execute($request);

    expect($results->query)->toBe('blade directive')
        ->and($results->items)->toHaveCount(2);

    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($freefind): bool {
        return $request->method() === 'GET'
            && str_contains($request->url(), 'si=' . urlencode($freefind->siteId()))
            && str_contains($request->url(), 'query=blade+directive')
            && str_contains($request->url(), 'dtd=n');
    });
});
