<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

it('exposes case-insensitive response headers without exposing transport internals', function (): void {
    $response = new XmlTransportResponse(200, '<ret/>', ['Content-Type' => ['text/xml']]);

    expect($response->header('content-type'))->toBe('text/xml')
        ->and($response->header('X-Missing'))->toBeNull();
});

it('rejects impossible HTTP statuses', function (): void {
    expect(fn(): XmlTransportResponse => new XmlTransportResponse(700, ''))
        ->toThrow(SearchTransportException::class);
});
