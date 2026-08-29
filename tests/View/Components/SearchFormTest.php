<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Exceptions\InvalidMarkup;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\View\Components\SearchForm;

beforeEach(function (): void {
    config(['freefind-laravel.site_id' => '0012345']);
    app()->forgetInstance(FreefindConfig::class);
});

it('renders an accessible semantic hosted search form', function (): void {
    $html = Blade::render(
        '<x-freefind::search-form label="Search docs" query="<script>alert(1)</script>" :sections="$sections" language="es" hide-results-form extended-styles class="search-box" />',
        ['sections' => ['' => 'Everything', 'manuals' => 'Manuals & Guides']],
    );

    expect($html)->toContain('<form method="get" action="https://search.freefind.com/find.html" class="search-box">')
        ->toContain('<label for="freefind-query">Search docs</label>')
        ->toContain('type="search" name="query" value="&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->toContain('<button type="submit">Search</button>')
        ->toContain('name="si" value="0012345"')
        ->toContain('name="lang" value="es"')
        ->toContain('name="nsb" value=""')
        ->toContain('name="css" value=""')
        ->toContain('name="s"')
        ->toContain('value="manuals"')
        ->toContain('Manuals &amp; Guides')
        ->not->toContain('s[]');
});

it('does not allow the attribute bag to replace protected form fields', function (): void {
    $html = Blade::render('<x-freefind::search-form action="https://evil.test" data-testid="search-form" query="typed" />');

    expect($html)->toContain('method="get" action="https://search.freefind.com/find.html"')
        ->not->toContain('evil.test')
        ->toContain('data-testid="search-form"');
});

it('preserves a query from the current GET request when no explicit value is provided', function (): void {
    $this->get('/search?query=from-request');

    expect(Blade::render('<x-freefind::search-form />'))
        ->toContain('name="query" value="from-request"');
});

it('renders named content slots inside the owned form', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-freefind::search-form>
            <x-slot:before><p data-filter="before">Filter by keyword</p></x-slot:before>
            <x-slot:after><small data-filter="after">Searches the public index</small></x-slot:after>
        </x-freefind::search-form>
        BLADE);

    expect($html)->toContain('<p data-filter="before">Filter by keyword</p>')
        ->toContain('<small data-filter="after">Searches the public index</small>')
        ->toContain('name="si" value="0012345"');
});

it('rejects non-GET methods and unsafe text values', function (): void {
    $search = resolve(HostedSearch::class);

    expect(fn(): SearchForm => new SearchForm($search, method: 'post'))
        ->toThrow(InvalidMarkup::class)
        ->and(fn(): SearchForm => new SearchForm($search, label: "bad\x00label"))
        ->toThrow(InvalidMarkup::class);
});
