<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Freefind\Freefind\Markup\MarkupState;

it('tracks nested markup regions and detects mismatched ends', function (): void {
    $state = new MarkupState();
    $state->begin('no-index');
    $state->begin('no-follow');

    expect($state->isBalanced())->toBeFalse();

    expect(fn() => $state->end('no-index'))->toThrow(InvalidMarkupException::class);

    $state->end('no-follow');
    $state->end('no-index');

    expect($state->isBalanced())->toBeTrue();
});
