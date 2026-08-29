<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlRequestEncoder;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\LaravelXmlSearchTransport;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    $this->request = new XmlSearchRequest(new Account('0012345'), new SimpleQuery('blade directive'));
    $this->transport = new LaravelXmlSearchTransport(
        app(Factory::class),
        new HttpSettings(maxResponseBytes: 100),
        new XmlRequestEncoder(),
    );
});

it('uses bounded HTTPS XML requests with no application credentials', function (): void {
    Http::fake([
        'https://search.freefind.com/*' => Http::response('<ret><sts>0</sts></ret>', 200, ['Content-Type' => 'text/xml']),
    ]);

    $response = $this->transport->send($this->request);

    expect($response->status)->toBe(200)
        ->and($response->body)->toBe('<ret><sts>0</sts></ret>');

    Http::assertSent(function (Request $request): bool {
        return $request->method() === 'GET'
            && str_contains($request->url(), 'si=0012345&query=blade+directive&dtd=n')
            && $request->header('Accept') === ['application/xml']
            && $request->header('User-Agent') === ['freefind-laravel']
            && $request->header('Cookie') === []
            && $request->header('Authorization') === [];
    });
});

it('retries one transient server failure but not service or client errors', function (): void {
    Http::fakeSequence()
        ->push('<ret><sts>1</sts></ret>', 503)
        ->push('<ret><sts>0</sts></ret>', 200);

    expect($this->transport->send($this->request)->body)->toBe('<ret><sts>0</sts></ret>');
    Http::assertSentCount(2);
});

it('does not retry client errors', function (): void {
    Http::fake(['https://search.freefind.com/*' => Http::response('bad request', 400)]);

    expect(fn(): mixed => $this->transport->send($this->request))
        ->toThrow(SearchTransportException::class);
    Http::assertSentCount(1);
});

it('rejects redirects and responses larger than the configured byte limit', function (): void {
    Http::fake(['https://search.freefind.com/*' => Http::response('', 302, ['Location' => 'http://evil.test'])]);

    expect(fn(): mixed => $this->transport->send($this->request))
        ->toThrow(SearchTransportException::class);

    Http::fake(['https://search.freefind.com/*' => Http::response(str_repeat('x', 101), 200)]);

    expect(fn(): mixed => $this->transport->send($this->request))
        ->toThrow(SearchTransportException::class);
});
