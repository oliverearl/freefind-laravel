<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

final class XmlRequestEncoder
{
    public function encode(XmlSearchRequest $request): string
    {
        return implode('&', array_map(function (array $pair): string {
            [$key, $value] = $pair;
            $this->assertSafe($key);
            $this->assertSafe($value);

            return urlencode($key) . '=' . urlencode($value);
        }, $request->pairs()));
    }

    public function url(XmlSearchRequest $request): XmlSearchUrl
    {
        return new XmlSearchUrl($request->account->xmlEndpoint . '?' . $this->encode($request));
    }

    private function assertSafe(string $value): void
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidSearchRequestException('FreeFind XML request fields cannot contain control characters or invalid UTF-8.');
        }
    }
}
