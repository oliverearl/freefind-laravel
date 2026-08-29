<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * Encodes XML-search request pairs and builds validated HTTPS endpoint URLs.
 */
final class XmlRequestEncoder
{
    /**
     * Encodes all request pairs while preserving repeated section and query keys.
     *
     * @throws InvalidSearchRequestException When a request field contains invalid UTF-8 or controls.
     */
    public function encode(XmlSearchRequest $request): string
    {
        return implode('&', array_map(function (array $pair): string {
            [$key, $value] = $pair;
            $this->assertSafe($key);
            $this->assertSafe($value);

            return urlencode($key) . '=' . urlencode($value);
        }, $request->pairs()));
    }

    /**
     * Returns the validated URL for one XML search request.
     *
     * @throws InvalidSearchRequestException When a request field or generated URL is invalid.
     */
    public function url(XmlSearchRequest $request): XmlSearchUrl
    {
        return new XmlSearchUrl($request->account->xmlEndpoint . '?' . $this->encode($request));
    }

    /**
     * Validates one XML request field.
     *
     * @throws InvalidSearchRequestException When the value contains invalid UTF-8 or control characters.
     */
    private function assertSafe(string $value): void
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidSearchRequestException('FreeFind XML request fields cannot contain control characters or invalid UTF-8.');
        }
    }
}
