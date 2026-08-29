<?php

declare(strict_types=1);

use Freefind\Freefind\Markup\DocumentDate;
use Freefind\Freefind\Markup\ExplicitLinks;
use Freefind\Freefind\Markup\HtmlCommentEscaper;
use Freefind\Freefind\Markup\Keywords;
use Freefind\Freefind\Markup\LinkPolicy;
use Freefind\Freefind\Markup\MapTitle;
use Freefind\Freefind\Markup\MarkupState;
use Freefind\Freefind\Markup\Renderer;
use Freefind\Freefind\Markup\ResultImage;
use Freefind\Freefind\Markup\WhatsNewEntry;

beforeEach(function (): void {
    $this->renderer = new Renderer(new HtmlCommentEscaper(), new MarkupState());
});

it('renders exact FreeFind crawler comments and metadata', function (): void {
    $date = new DateTimeImmutable('2004-11-06 14:49:37 UTC');

    expect($this->renderer->keywords(Keywords::from(['laravel', 'search'], 5)))
    ->toBe('<!-- FreeFind Keywords Words="laravel search" Count="5" -->')
    ->and($this->renderer->documentDate(DocumentDate::from($date)))
    ->toBe('<meta name="document-date" content="06 Nov 2004 14:49:37 GMT">')
    ->and($this->renderer->noIndexPage())->toBe('<!-- FreeFind No Index Page -->')
    ->and($this->renderer->noMap())->toBe('<!-- FreeFind No Map -->')
    ->and($this->renderer->mapTitle(MapTitle::from('Guide')))
    ->toBe('<!-- FreeFind Map Title="Guide" -->')
    ->and($this->renderer->notNew())->toBe('<!-- FreeFind Not New -->');
});

it('renders exact links, whats-new, and result-image comments', function (): void {
    $date = new DateTimeImmutable('2026-08-29 12:34:56 UTC');

    expect($this->renderer->links(ExplicitLinks::from(['https://example.test/one', 'https://example.test/two'])))
        ->toBe('<!-- FreeFind Links "https://example.test/one" "https://example.test/two" -->')
        ->and($this->renderer->whatsNew(WhatsNewEntry::from($date, 'https://example.test/new.svg', 'Updated guide')))
        ->toBe('<!-- FreeFind New Date="29 Aug 2026 12:34:56 GMT" Icon="https://example.test/new.svg" Comment="Updated guide" -->')
        ->and($this->renderer->resultImage(ResultImage::from(
            src: 'https://example.test/image.jpg',
            alt: 'Example',
            width: 160,
            height: 90,
            href: 'https://example.test/page',
            target: '_blank',
        )))->toBe('<!-- FreeFind image src="https://example.test/image.jpg" alt="Example" height="90" width="160" href="https://example.test/page" target="_blank" -->');
});

it('renders exact link-policy meta tags', function (): void {
    $policy = LinkPolicy::from(queries: 'strip', scripts: 'never', robots: 'ignore');

    expect($this->renderer->globalLinkPolicy($policy))
        ->toBe(implode(PHP_EOL, [
            '<meta name="FreeFind" content="stripQueries">',
            '<meta name="FreeFind" content="neverFollowScript">',
            '<meta name="FreeFind" content="noRobotsTag">',
        ]));
});

it('renders and validates paired fragment markers', function (): void {
    expect($this->renderer->beginNoIndex())->toBe('<!-- FreeFind Begin No Index -->')
        ->and($this->renderer->beginNoFollow())->toBe('<!-- FreeFind nofollow -->')
        ->and($this->renderer->endNoFollow())->toBe('<!-- FreeFind end nofollow -->')
        ->and($this->renderer->endNoIndex())->toBe('<!-- FreeFind End No Index -->');
});
