<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Search\Xml\Query\DescriptionLength;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;

it('encodes non-default XML search options in stable order', function (): void {
    $options = new SearchOptions(
        accentSensitive: true,
        caseSensitive: true,
        descriptionLength: DescriptionLength::Long,
        offset: 25,
        resultsPerPage: 5,
        sections: ['manuals', 'releases'],
        sort: SortOrder::Date,
        stemming: Stemming::English,
    );

    expect($options->pairs())->toBe([
        ['asen', 'y'],
        ['csen', 'y'],
        ['dl', 'l'],
        ['fr', '25'],
        ['rpp', '5'],
        ['s', 'manuals'],
        ['s', 'releases'],
        ['srt', 'd'],
        ['stm', 'en'],
    ]);
});

it('omits defaults and rejects invalid XML option values', function (): void {
    expect((new SearchOptions())->pairs())->toBe([])
        ->and(fn(): SearchOptions => new SearchOptions(offset: -1))->toThrow(InvalidSearchRequest::class)
        ->and(fn(): SearchOptions => new SearchOptions(resultsPerPage: 26))->toThrow(InvalidSearchRequest::class)
        ->and(fn(): SearchOptions => new SearchOptions(sections: ['web']))->toThrow(InvalidSearchRequest::class)
        ->and(fn(): SearchOptions => new SearchOptions(sections: ['manuals', 'manuals']))->toThrow(InvalidSearchRequest::class);
});
