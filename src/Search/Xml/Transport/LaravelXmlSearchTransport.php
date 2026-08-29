<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Transport;

use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Search\Xml\Request\XmlRequestEncoder;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

/**
 * Sends bounded, non-redirecting XML requests through Laravel's HTTP client.
 */
final class LaravelXmlSearchTransport implements SearchTransport
{
    /**
     * Creates a transport with bounded HTTP settings and XML request encoding.
     */
    public function __construct(
        private readonly Factory $http,
        private readonly HttpSettings $settings,
        private readonly XmlRequestEncoder $encoder,
    ) {}

    /**
     * Sends one user-initiated XML search and returns its bounded response body.
     *
     * @throws SearchTransportException When transport fails, redirects, returns a non-2xx status, or exceeds a body limit.
     */
    public function send(XmlSearchRequest $request): XmlTransportResponse
    {
        try {
            $response = $this->http
                ->accept('application/xml')
                ->withUserAgent('freefind-laravel')
                ->connectTimeout($this->settings->connectTimeout)
                ->timeout($this->settings->timeout)
                ->retry(2, 0, $this->shouldRetry(...), false)
                ->withOptions([
                    'allow_redirects' => false,
                    'stream' => true,
                    'on_headers' => $this->assertContentLength(...),
                ])
                ->get($this->encoder->url($request)->value);
        } catch (SearchTransportException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new SearchTransportException('The FreeFind XML request could not be completed.', previous: $exception);
        }

        if ($response->status() < 200 || $response->status() > 299) {
            throw new SearchTransportException("The FreeFind XML endpoint returned HTTP status {$response->status()}.");
        }

        return new XmlTransportResponse(
            status: $response->status(),
            body: $this->readBody($response->toPsrResponse()->getBody()),
            headers: $response->headers(),
        );
    }

    /**
     * Determines whether a transport exception represents a limited transient retry.
     */
    private function shouldRetry(Throwable $exception): bool
    {
        return $exception instanceof ConnectionException
            || ($exception instanceof RequestException && $exception->response->serverError());
    }

    /**
     * Rejects a response whose declared body length already exceeds the configured bound.
     *
     * @throws SearchTransportException When the declared content length is too large.
     */
    private function assertContentLength(ResponseInterface $response): void
    {
        $length = $response->getHeaderLine('Content-Length');

        if ($length !== '' && ctype_digit($length) && (int) $length > $this->settings->maxResponseBytes) {
            throw new SearchTransportException('The FreeFind XML response exceeded the configured size limit.');
        }
    }

    /**
     * Reads a response stream incrementally so the size limit also covers chunked bodies.
     *
     * @throws SearchTransportException When the body exceeds the configured size limit.
     */
    private function readBody(StreamInterface $body): string
    {
        $contents = '';

        while (! $body->eof()) {
            $chunk = $body->read(min(8192, $this->settings->maxResponseBytes + 1));
            $contents .= $chunk;

            if (strlen($contents) > $this->settings->maxResponseBytes) {
                throw new SearchTransportException('The FreeFind XML response exceeded the configured size limit.');
            }
        }

        return $contents;
    }
}
