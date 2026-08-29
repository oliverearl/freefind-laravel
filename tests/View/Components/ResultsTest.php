<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;
use Freefind\Freefind\Search\Xml\Response\RawResultFields;
use Freefind\Freefind\Search\Xml\Response\SearchResult;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Response\SearchWindow;
use Freefind\Freefind\Search\Xml\Response\SpellingSuggestion as ResponseSpellingSuggestion;
use Freefind\Freefind\View\Components\EmptyState;
use Freefind\Freefind\View\Components\NavigationUrl;
use Freefind\Freefind\View\Components\Pagination;
use Freefind\Freefind\View\Components\ResultItem;
use Freefind\Freefind\View\Components\Results;
use Freefind\Freefind\View\Components\SpellingSuggestion;
use Illuminate\Support\Facades\Blade;

beforeEach(function (): void {
    $this->result = new SearchResult(
        number: 1,
        title: '<script>alert(1)</script>',
        description: 'A description & details.',
        url: AbsoluteUrl::from('https://example.test/results/1'),
        target: '_blank',
        displayUrl: 'example.test/results/1?match=1&safe=2',
        date: new \DateTimeImmutable('2024-01-02T03:04:05+00:00'),
        raw: new RawResultFields(
            '<script><b>Highlighted</b></script>',
            'A <b>highlight</b>',
            'example.test/results/1',
        ),
    );
    $this->results = new SearchResults(
        status: FreefindStatus::Success,
        query: 'blade directive',
        total: 25,
        returned: 1,
        offset: 10,
        sections: ['manuals'],
        spelling: new ResponseSpellingSuggestion('<script>suggestion</script>', 'suggestion'),
        usedAutomaticAnyMode: false,
        items: [$this->result],
        window: new SearchWindow(10, 10, 25),
    );
});

it('renders safe result fields with semantic markup and safe new-window links', function (): void {
    $html = Blade::render(
        '<x-freefind::results
            :results="$results"
            heading="Search & results"
            heading-id="search-results"
            :previous-url="$previousUrl"
            :next-url="$nextUrl"
            :spelling-url="$spellingUrl"
            class="result-list"
        />',
        [
            'results' => $this->results,
            'previousUrl' => '/search?q=blade&offset=0',
            'nextUrl' => '/search?q=blade&offset=20',
            'spellingUrl' => '/search?q=suggestion',
        ],
    );

    expect($html)->toContain('<section aria-labelledby="search-results" class="result-list">')
        ->toContain('<h2 id="search-results">Search &amp; results</h2>')
        ->toContain('<ol>')
        ->toContain('<li>')
        ->toContain('<article>')
        ->toContain('href="https://example.test/results/1"')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"')
        ->toContain('&lt;script&gt;alert(1)&lt;/script&gt;')
        ->toContain('A description &amp; details.')
        ->toContain('example.test/results/1?match=1&amp;safe=2')
        ->toContain('datetime="2024-01-02T03:04:05+00:00"')
        ->toContain('Did you mean')
        ->toContain('&lt;script&gt;suggestion&lt;/script&gt;')
        ->toContain('<nav aria-label="Search results pages">')
        ->toContain('href="/search?q=blade&amp;offset=0"')
        ->toContain('href="/search?q=blade&amp;offset=20"')
        ->not->toContain('<b>Highlighted</b>')
        ->not->toContain('<b>highlight</b>')
        ->not->toContain('<script>alert(1)</script>');
});

it('renders an empty state without fabricating pagination links', function (): void {
    $results = new SearchResults(
        status: FreefindStatus::Success,
        query: 'missing',
        total: 0,
        returned: 0,
        offset: 0,
        sections: [],
        spelling: null,
        usedAutomaticAnyMode: false,
        items: [],
        window: new SearchWindow(0, 10, 0),
    );

    $html = Blade::render('<x-freefind::results :results="$results" empty-message="Nothing matched." />', compact('results'));

    expect($html)->toContain('<p role="status">Nothing matched.</p>')
        ->not->toContain('<ol>')
        ->not->toContain('<nav')
        ->not->toContain('javascript:');
});

it('renders the child components with their direct contracts', function (): void {
    $pagination = Blade::render(
        '<x-freefind::pagination :window="$window" previous-url="/previous" next-url="/next" />',
        ['window' => new SearchWindow(10, 10, 25)],
    );
    $item = Blade::render('<x-freefind::result-item :result="$result" />', ['result' => $this->result]);
    $suggestion = Blade::render(
        '<x-freefind::spelling-suggestion :suggestion="$suggestion" />',
        ['suggestion' => new ResponseSpellingSuggestion('corrected')],
    );
    $empty = Blade::render('<x-freefind::empty-state message="No matches." />');

    expect($pagination)->toContain('href="/previous"')->toContain('href="/next"')
        ->and($item)->toContain('<article>')->toContain('rel="noopener noreferrer"')
        ->and($suggestion)->toContain('<span>corrected</span>')
        ->and($empty)->toContain('<p role="status">No matches.</p>')
        ->and(new Results($this->results))->toBeInstanceOf(Results::class)
        ->and(new Pagination(new SearchWindow(0, 10, 10)))->toBeInstanceOf(Pagination::class)
        ->and(new SpellingSuggestion(null))->toBeInstanceOf(SpellingSuggestion::class)
        ->and(new EmptyState())->toBeInstanceOf(EmptyState::class)
        ->and(new ResultItem($this->result))->toBeInstanceOf(ResultItem::class);
});

it('validates navigation URLs and component text boundaries', function (): void {
    expect(NavigationUrl::from('/search?q=blade')->value)->toBe('/search?q=blade')
        ->and(NavigationUrl::from('https://example.test/search')->value)->toBe('https://example.test/search')
        ->and(fn(): NavigationUrl => NavigationUrl::from('javascript:alert(1)'))
        ->toThrow(InvalidMarkup::class)
        ->and(fn(): Results => new Results($this->results, headingId: 'bad id'))
        ->toThrow(InvalidMarkup::class)
        ->and(fn(): Pagination => new Pagination(new SearchWindow(0, 10, 10), previousLabel: ''))
        ->toThrow(InvalidMarkup::class);
});
