<?php

declare(strict_types=1);

namespace Freefind\Freefind\Testing;

use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\RefinedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;

final readonly class SentSearch
{
    /**
     * @param  list<string>  $sections
     */
    public function __construct(
        public string $query,
        public array $sections,
        public int $offset,
        public int $resultsPerPage,
    ) {}

    public static function from(XmlSearchRequest $request): self
    {
        $query = match (true) {
            $request->query instanceof SimpleQuery => $request->query->query,
            $request->query instanceof RefinedQuery => $request->query->query->query,
            $request->query instanceof AdvancedQuery => self::advancedQuery($request->query),
        };

        return new self(
            query: $query,
            sections: array_values($request->options->sections),
            offset: $request->options->offset,
            resultsPerPage: $request->options->resultsPerPage,
        );
    }

    private static function advancedQuery(AdvancedQuery $query): string
    {
        return implode(' ', array_values(array_filter([
            $query->allWords,
            $query->exactPhrase,
            $query->anyWords,
            $query->withoutWords,
        ], static fn(?string $value): bool => $value !== null && trim($value) !== '')));
    }
}
