<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidSearchRequest;
use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\DescriptionLength;
use Freefind\Freefind\Search\Xml\Query\MatchMode;
use Freefind\Freefind\Search\Xml\Query\RefinedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;

it('keeps XML query enum values at the protocol boundary', function (): void {
    expect(MatchMode::Any->value)->toBe('any')
        ->and(DescriptionLength::Long->value)->toBe('l')
        ->and(SortOrder::Date->value)->toBe('d')
        ->and(Stemming::Auto->value)->toBe('');
});

it('encodes simple, advanced, and refined query pairs', function (): void {
    expect((new SimpleQuery('laravel middleware', MatchMode::Any))->pairs())
        ->toBe([['query', 'laravel middleware'], ['mode', 'any']])
        ->and((new AdvancedQuery(
            allWords: 'laravel package',
            exactPhrase: 'blade directive',
            withoutWords: 'wordpress',
        ))->pairs())
        ->toBe([
            ['q1', 'laravel package'],
            ['q2', 'blade directive'],
            ['q4', 'wordpress'],
        ])
        ->and((new RefinedQuery(new SimpleQuery('middleware'), 'laravel'))->pairs())
        ->toBe([
            ['query', 'middleware'],
            ['oq', 'laravel'],
            ['search', 'these'],
        ]);
});

it('rejects empty or unsafe query values', function (): void {
    expect(fn(): SimpleQuery => new SimpleQuery('   '))
        ->toThrow(InvalidSearchRequest::class)
        ->and(fn(): AdvancedQuery => new AdvancedQuery())
        ->toThrow(InvalidSearchRequest::class)
        ->and(fn(): RefinedQuery => new RefinedQuery(new SimpleQuery('new'), "old\x00query"))
        ->toThrow(InvalidSearchRequest::class);
});
