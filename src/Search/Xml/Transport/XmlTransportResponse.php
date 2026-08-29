<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Transport;

use Freefind\Freefind\Exceptions\SearchTransportException;

final readonly class XmlTransportResponse
{
    /**
     * @param  array<string, list<string>>  $headers
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
