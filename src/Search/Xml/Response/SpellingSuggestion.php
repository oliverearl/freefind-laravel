<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use Freefind\Freefind\Exceptions\MalformedXmlResponseException;

final readonly class SpellingSuggestion
{
    public function __construct(
        public string $query,
        public ?string $encodedQuery = null,
    ) {
        if (trim($this->query) === '') {
            throw new MalformedXmlResponseException('The FreeFind spelling suggestion was empty.');
        }
    }
}
