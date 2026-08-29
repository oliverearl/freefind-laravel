<?php

declare(strict_types=1);

use Freefind\Freefind\Freefind;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('executes the immutable convenience builder through the real client', function (): void {
    Http::fake([
        'https://search.freefind.com/*' => Http::response(
            file_get_contents(__DIR__ . '/../../Fixtures/xml/success.xml'),
            200,
        ),
    ]);

    $freefind = resolve(Freefind::class);
    $results = $freefind->search('blade directive')
        ->inSections(['manuals', 'releases'])
        ->sortBy(\Freefind\Freefind\Search\Xml\Query\SortOrder::Date)
        ->perPage(15)
        ->startingAt(10)
        ->get();

    expect($results->items)->toHaveCount(2);

    Http::assertSentCount(1);
    Http::assertSent(function (Request $request) use ($freefind): bool {
        return str_contains($request->url(), 'si=' . urlencode($freefind->siteId()))
            && str_contains($request->url(), 'query=blade+directive')
            && str_contains($request->url(), 'fr=10')
            && str_contains($request->url(), 'rpp=15')
            && str_contains($request->url(), 's=manuals')
            && str_contains($request->url(), 's=releases')
            && str_contains($request->url(), 'srt=d')
            && str_contains($request->url(), 'dtd=n');
    });
});
