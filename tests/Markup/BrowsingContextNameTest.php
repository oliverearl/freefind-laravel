<?php

declare(strict_types=1);

use Freefind\Freefind\Markup\BrowsingContextName;

it('accepts standard and named browsing contexts', function (): void {
    expect(BrowsingContextName::isValid('_blank'))->toBeTrue()
        ->and(BrowsingContextName::isValid('search-results'))->toBeTrue()
        ->and(BrowsingContextName::isValid('frame:results'))->toBeTrue();
});

it('rejects unsafe browsing contexts', function (): void {
    expect(BrowsingContextName::isValid(''))->toBeFalse()
        ->and(BrowsingContextName::isValid('1-results'))->toBeFalse()
        ->and(BrowsingContextName::isValid('results target'))->toBeFalse()
        ->and(BrowsingContextName::isValid('_parent<script>'))->toBeFalse();
});
