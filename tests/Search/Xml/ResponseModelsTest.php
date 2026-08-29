<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Exceptions\MalformedXmlResponse;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;
use Freefind\Freefind\Search\Xml\Response\RawResultFields;
use Freefind\Freefind\Search\Xml\Response\SearchResult;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Response\SearchWindow;
use Freefind\Freefind\Search\Xml\Response\SpellingSuggestion;

it('provides typed response models and local pagination helpers', function (): void {
    $window = new SearchWindow(10, 10, 25);
    $item = new SearchResult(
        number: 1,
        title: 'Title',
        description: 'Description',
        url: AbsoluteUrl::from('https://example.test/result'),
        target: '_blank',
        displayUrl: 'example.test/result',
        date: null,
        raw: new RawResultFields('Title <b>match</b>', 'Description', 'example.test/result'),
    );
    $results = new SearchResults(
        status: FreefindStatus::Success,
        query: 'query',
        total: 25,
        returned: 10,
        offset: 10,
        sections: ['manuals'],
        spelling: new SpellingSuggestion('suggestion', 'suggestion'),
        usedAutomaticAnyMode: false,
        items: [$item],
        window: $window,
    );

    expect($results->items)->toBe([$item])
        ->and($window->hasPrevious())->toBeTrue()
        ->and($window->previousOffset())->toBe(0)
        ->and($window->hasNext())->toBeTrue()
        ->and($window->nextOffset())->toBe(20)
        ->and($results->spelling?->query)->toBe('suggestion');
});

it('rejects invalid response model values', function (): void {
    expect(fn(): SearchWindow => new SearchWindow(-1, 10, 1))
        ->toThrow(InvalidSearchRequest::class)
        ->and(fn(): SpellingSuggestion => new SpellingSuggestion(''))
        ->toThrow(MalformedXmlResponse::class)
        ->and(fn(): SearchResult => new SearchResult(
            number: 1,
            title: '',
            description: '',
            url: AbsoluteUrl::from('https://example.test/result'),
            target: 'bad target',
            displayUrl: '',
            date: null,
            raw: new RawResultFields(null, null, null),
        ))->toThrow(MalformedXmlResponse::class);
});
