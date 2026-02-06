<?php

declare(strict_types=1);

use Freefind\Freefind\Freefind;

beforeEach(function (): void {
    app()->forgetInstance(Freefind::FREEFIND_REQUEST_INDICATOR_KEY);
    $this->freefind = resolve(Freefind::class);
});

it('can determine if the current request is a Freefind request', function (): void {
    // Initially, it should not be a Freefind request.
    expect($this->freefind->isFreeFindRequest())->toBeFalse();

    // Simulate a Freefind request by binding the indicator in the container.
    app()->instance(Freefind::FREEFIND_REQUEST_INDICATOR_KEY, true);

    // Now it should be a Freefind request.
    expect($this->freefind->isFreeFindRequest())->toBeTrue();
});

it('can retrieve the Freefind site ID from the configuration', function (): void {
    $value = fake()->randomNumber(7);
    config(['freefind-laravel.site_id' => $value]);

    expect($this->freefind->getSiteId())->toEqual($value);
});
