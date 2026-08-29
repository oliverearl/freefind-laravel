<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Contracts\SearchClient;
use Freefind\Freefind\Exceptions\InvalidSearchRequestException;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

/**
 * Immutable fluent builder for one user-initiated XML Page Search request.
 */
final readonly class XmlSearchQuery
{
    /**
     * Creates an immutable builder for one account and validated query.
     */
    public function __construct(
        private SearchClient $client,
        private Account $account,
        private SimpleQuery $query,
        private SearchOptions $options = new SearchOptions(),
    ) {}

    /**
     * Returns a copy restricted to the supplied FreeFind sections.
     *
     * @param list<string> $sections
     *
     * @throws InvalidSearchRequestException When a section identifier is invalid or repeated.
     */
    public function inSections(array $sections): self
    {
        return $this->withOptions(sections: $sections);
    }

    /**
     * Returns a copy ordered by the supplied FreeFind sort mode.
     */
    public function sortBy(SortOrder $sort): self
    {
        return $this->withOptions(sort: $sort);
    }

    /**
     * Returns a copy using the supplied FreeFind stemming mode.
     */
    public function stemUsing(Stemming $stemming): self
    {
        return $this->withOptions(stemming: $stemming);
    }

    /**
     * Returns a copy requesting the supplied number of results per page.
     *
     * @throws InvalidSearchRequestException When the page size is outside FreeFind's supported range.
     */
    public function perPage(int $resultsPerPage): self
    {
        return $this->withOptions(resultsPerPage: $resultsPerPage);
    }

    /**
     * Returns a copy beginning at the supplied zero-based result offset.
     *
     * @throws InvalidSearchRequestException When the offset is negative.
     */
    public function startingAt(int $offset): self
    {
        return $this->withOptions(offset: $offset);
    }

    /**
     * Executes the configured user-initiated search and parses its response.
     *
     * @throws SearchTransportException When the XML request cannot be completed or is rejected at HTTP level.
     * @throws \Freefind\Freefind\Exceptions\MalformedXmlResponseException When FreeFind returns invalid XML.
     * @throws \Freefind\Freefind\Exceptions\FreefindServiceException When FreeFind reports a service error.
     * @throws \Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException When the account is not authorized for XML search.
     * @throws \Freefind\Freefind\Exceptions\InvalidOrClosedAccountException When the account is invalid or closed.
     * @throws \Freefind\Freefind\Exceptions\RejectedSearchParametersException When FreeFind rejects the request parameters.
     */
    public function get(): SearchResults
    {
        return $this->client->execute(new XmlSearchRequest($this->account, $this->query, $this->options));
    }

    /**
     * Returns a copy with the supplied non-null options replacing the current values.
     *
     * @param list<string>|null $sections
     *
     * @throws InvalidSearchRequestException When the resulting options are invalid.
     */
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
