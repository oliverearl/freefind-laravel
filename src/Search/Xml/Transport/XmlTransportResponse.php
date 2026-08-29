<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Transport;

use Freefind\Freefind\Exceptions\SearchTransportException;

/**
 * Transport-neutral HTTP response data passed to the XML parser.
 */
final readonly class XmlTransportResponse
{
    /**
     * Creates a response value with normalized header lists.
     *
     * @param array<string, list<string>> $headers
     *
     * @throws SearchTransportException When the HTTP status is outside the valid range.
     */
    public function __construct(
        public int $status,
        public string $body,
        public array $headers = [],
    ) {
        if ($this->status < 100 || $this->status > 599) {
            throw new SearchTransportException('FreeFind returned an invalid HTTP status.');
        }
    }

    /**
     * Returns the first value for a header name using case-insensitive lookup.
     */
    public function header(string $name): ?string
    {
        foreach ($this->headers as $header => $values) {
            if (strcasecmp($header, $name) === 0) {
                return $values[0] ?? null;
            }
        }

        return null;
    }
}
