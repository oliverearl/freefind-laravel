<?php

declare(strict_types=1);

use Freefind\Freefind\Markup\AnnotationCollector;
use Illuminate\Support\Facades\Blade;

it('renders crawler directives through the package renderer', function (): void {
    $html = Blade::render(<<<'BLADE'
@freefindKeywords(['laravel', 'search'], count: 5)
@freefindDocumentDate($date)
@freefindNoIndexPage
@freefindNoMap
@freefindMapTitle('Guide')
@freefindNotNew
@freefindLinks(['https://example.test/guide'])
@freefindLinkPolicy(queries: 'strip', scripts: 'ignore-page')
BLADE, ['date' => new DateTimeImmutable('2026-08-29 12:34:56 UTC')]);

    expect($html)->toContain('<!-- FreeFind Keywords Words="laravel search" Count="5" -->')
        ->toContain('<meta name="document-date" content="29 Aug 2026 12:34:56 GMT">')
        ->toContain('<!-- FreeFind No Index Page -->')
        ->toContain('<!-- FreeFind Links "https://example.test/guide" -->')
        ->toContain('<meta name="FreeFind" content="stripQueries">');
});

it('supports paired directives and the head collector hook', function (): void {
    $html = Blade::render(<<<'BLADE'
@freefindNoIndex
<nav>Repeated navigation</nav>
@endFreefindNoIndex
@freefindNoFollow
<a href="https://example.test/next">Next</a>
@endFreefindNoFollow
BLADE);

    expect($html)->toBe("<!-- FreeFind Begin No Index --><nav>Repeated navigation</nav>\n<!-- FreeFind End No Index --><!-- FreeFind nofollow --><a href=\"https://example.test/next\">Next</a>\n<!-- FreeFind end nofollow -->");

    app(AnnotationCollector::class)->add('<!-- FreeFind No Map -->');

    expect(Blade::render('@freefindHead'))->toBe('<!-- FreeFind No Map -->');
});

it('fails clearly when paired directives do not match', function (): void {
    expect(fn(): string => Blade::render('@endFreefindNoIndex'))
        ->toThrow(\Illuminate\View\ViewException::class);
});
