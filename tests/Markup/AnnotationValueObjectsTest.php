<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Markup\DocumentDate;
use Freefind\Freefind\Markup\ExplicitLinks;
use Freefind\Freefind\Markup\Keywords;
use Freefind\Freefind\Markup\LinkPolicy;
use Freefind\Freefind\Markup\MapTitle;
use Freefind\Freefind\Markup\ResultImage;
use Freefind\Freefind\Markup\WhatsNewEntry;

it('validates and defaults weighted keywords', function (): void {
    $keywords = Keywords::from(['laravel', 'freefind']);

    expect($keywords->words)->toBe(['laravel', 'freefind'])
        ->and($keywords->count)->toBe(2);
});

it('rejects invalid keyword lists and weights', function (array $words, int $count): void {
    expect(fn(): Keywords => new Keywords($words, $count))
        ->toThrow(InvalidMarkup::class);
})->with([
    [[], 1],
    [[''], 1],
    [['one'], 0],
    [['one'], 101],
    [['one', 123], 1],
]);

it('wraps a public date without changing its public date type', function (): void {
    $date = new DateTimeImmutable('2026-08-29 12:34:56 UTC');
    $annotation = DocumentDate::from($date);

    expect($annotation->date)->toBe($date);
});

it('validates explicit links', function (): void {
    $links = ExplicitLinks::from(['https://example.test/one', 'http://example.test/two']);

    expect($links->urls)->toHaveCount(2)
        ->and($links->urls[0])->toBeInstanceOf(AbsoluteUrl::class);
});

it('rejects invalid explicit links', function (): void {
    expect(fn(): ExplicitLinks => ExplicitLinks::from(['javascript:alert(1)']))
        ->toThrow(InvalidMarkup::class);
});

it('validates map titles and whats-new entries', function (): void {
    $date = new DateTimeImmutable('2026-08-29 12:34:56 UTC');
    $entry = WhatsNewEntry::from($date, 'https://example.test/new.svg', 'Updated guide');

    expect(MapTitle::from('Guide')->title)->toBe('Guide')
        ->and($entry->icon?->value)->toBe('https://example.test/new.svg');
});

it('rejects empty map and whats-new values', function (): void {
    expect(fn(): MapTitle => MapTitle::from(''))->toThrow(InvalidMarkup::class)
        ->and(fn(): WhatsNewEntry => WhatsNewEntry::from())->toThrow(InvalidMarkup::class)
        ->and(fn(): WhatsNewEntry => WhatsNewEntry::from(comment: ''))->toThrow(InvalidMarkup::class);
});

it('validates result image dimensions, attributes, and targets', function (): void {
    $image = ResultImage::from(
        src: 'https://example.test/image.jpg',
        alt: 'Example',
        width: 160,
        height: 90,
        href: 'https://example.test/page',
        target: '_blank',
        attributes: ['class' => 'thumbnail'],
        linkAttributes: ['rel' => 'nofollow'],
    );

    expect($image->src->value)->toBe('https://example.test/image.jpg')
        ->and($image->href?->value)->toBe('https://example.test/page');
});

it('rejects unsafe result image options', function (callable $factory): void {
    expect($factory)->toThrow(InvalidMarkup::class);
})->with([
    fn(): ResultImage => ResultImage::from('https://example.test/image.jpg', width: 0),
    fn(): ResultImage => ResultImage::from('https://example.test/image.jpg', attributes: ['onerror' => 'alert(1)']),
    fn(): ResultImage => ResultImage::from('https://example.test/image.jpg', target: '" onload="alert(1)'),
]);

it('validates link policy options', function (): void {
    $policy = LinkPolicy::from(queries: 'strip', scripts: 'never', robots: 'ignore');

    expect($policy->queries)->toBe('strip')
        ->and($policy->scripts)->toBe('never')
        ->and($policy->robots)->toBe('ignore');
});

it('rejects empty or unsupported link policies', function (?string $queries, ?string $scripts, ?string $robots): void {
    expect(fn(): LinkPolicy => new LinkPolicy($queries, $scripts, $robots))
        ->toThrow(InvalidMarkup::class);
})->with([
    [null, null, null],
    ['invalid', null, null],
    [null, 'invalid', null],
    [null, null, 'invalid'],
]);
