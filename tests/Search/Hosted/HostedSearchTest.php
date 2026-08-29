<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Search\Hosted\HostedSearch;

beforeEach(function (): void {
    $this->search = new HostedSearch(new Account('0012345'));
});

it('builds a repeated-key hosted search URL with documented options', function (): void {
    $url = $this->search->url('laravel middleware', ['manuals', 'release-notes'], 'en', true, true);

    expect($url->value)->toBe('https://search.freefind.com/find.html?si=0012345&query=laravel+middleware&s=manuals&s=release-notes&lang=en&nsb=&css=');
});

it('builds documented site map, whats-new, and index URLs', function (): void {
    expect($this->search->siteMapUrl()->value)
        ->toBe('https://search.freefind.com/find.html?si=0012345&m=0&p=0')
        ->and($this->search->whatsNewUrl()->value)
        ->toBe('https://search.freefind.com/find.html?si=0012345&w=0&p=0')
        ->and($this->search->indexUrl()->value)
        ->toBe('https://search.freefind.com/siteindex.html?id=0012345');
});

it('keeps form protocol fields owned by the builder', function (): void {
    expect($this->search->formPairs(null, [''], null, false, false))
        ->toBe([['si', '0012345'], ['s', '']]);
});
