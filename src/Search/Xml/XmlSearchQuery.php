<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Contracts\SearchClient;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

final readonly class XmlSearchQuery
{
    public function __construct(
        private SearchClient $client,
        private Account $account,
        private SimpleQuery $query,
        private SearchOptions $options = new SearchOptions(),
    ) {}

    /**
     * @param  list<string>  $sections
     */
    public function inSections(array $sections): self
    {
        return $this->withOptions(sections: $sections);
    }

    public function sortBy(SortOrder $sort): self
    {
        return $this->withOptions(sort: $sort);
    }

    public function stemUsing(Stemming $stemming): self
    {
        return $this->withOptions(stemming: $stemming);
    }

    public function perPage(int $resultsPerPage): self
    {
        return $this->withOptions(resultsPerPage: $resultsPerPage);
    }

    public function startingAt(int $offset): self
    {
        return $this->withOptions(offset: $offset);
    }

    public function get(): SearchResults
    {
        return $this->client->execute(new XmlSearchRequest($this->account, $this->query, $this->options));
    }

    private function withOptions(
        ?bool $accentSensitive = null,
        ?bool $caseSensitive = null,
        ?\Freefind\Freefind\Search\Xml\Query\DescriptionLength $descriptionLength = null,
        ?int $offset = null,
        ?int $resultsPerPage = null,
        ?array $sections = null,
        ?SortOrder $sort = null,
        ?Stemming $stemming = null,
    ): self {
        return new self(
            client: $this->client,
            account: $this->account,
            query: $this->query,
            options: new SearchOptions(
                accentSensitive: $accentSensitive ?? $this->options->accentSensitive,
                caseSensitive: $caseSensitive ?? $this->options->caseSensitive,
                descriptionLength: $descriptionLength ?? $this->options->descriptionLength,
                offset: $offset ?? $this->options->offset,
                resultsPerPage: $resultsPerPage ?? $this->options->resultsPerPage,
                sections: $sections ?? $this->options->sections,
                sort: $sort ?? $this->options->sort,
                stemming: $stemming ?? $this->options->stemming,
            ),
        );
    }
}
