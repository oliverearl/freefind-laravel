<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Search\Xml\FreefindXmlClient;

beforeEach(function (): void {
    $this->freefind = resolve(Freefind::class);
});

it('exposes the configured account and string site id', function (): void {
    config(['freefind-laravel.site_id' => '0012345']);
    app()->forgetInstance(Freefind::class);
    app()->forgetInstance(FreefindConfig::class);

    $freefind = resolve(Freefind::class);

    expect($freefind->siteId())->toBe('0012345')
        ->and($freefind->account()->siteId)->toBe('0012345')
        ->and($freefind->hostedSearch())->toBeInstanceOf(HostedSearch::class)
        ->and($freefind->xml())->toBeInstanceOf(FreefindXmlClient::class);
});

it('reports a request as a spider only through the request-local context', function (): void {
    expect($this->freefind->isSpiderRequest())->toBeFalse();
});
