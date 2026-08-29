<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;
use Freefind\Freefind\Testing\SearchFixture;

it('loads a named XML fixture from a file', function (): void {
    $fixture = SearchFixture::for('blade directive')->fromFile(__DIR__ . '/../Fixtures/xml/success.xml');

    expect($fixture->query)->toBe('blade directive')
        ->and($fixture->body)->toContain('<ret>');
});

it('rejects invalid or unreadable fixture definitions', function (): void {
    expect(fn(): SearchFixture => SearchFixture::for(''))
        ->toThrow(InvalidSearchRequestException::class)
        ->and(fn(): SearchFixture => SearchFixture::for('query')->fromFile(__DIR__ . '/missing.xml'))
        ->toThrow(InvalidSearchRequestException::class);
});
