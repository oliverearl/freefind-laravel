<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Builds HTTPS URLs for FreeFind's hosted Page Search experience.
 */
final readonly class HostedSearch
{
    /**
     * Creates a hosted-search builder for one configured account.
     */
    public function __construct(
        private Account $account,
        private HostedQueryEncoder $encoder = new HostedQueryEncoder(),
    ) {}

    /**
     * Builds a hosted search URL for a query and optional sections or display flags.
     *
     * @param  list<string>  $sections
     *
     * @throws InvalidMarkupException When a section, language, query, or generated URL is invalid.
     */
    public function url(
        ?string $query = null,
        array $sections = [],
        Language|string|null $language = null,
        bool $hideResultsForm = false,
        bool $extendedStyles = false,
    ): HostedSearchUrl {
        return new HostedSearchUrl($this->account->htmlEndpoint . '?' . $this->encoder->encode(
            $this->formPairs($query, $sections, $language, $hideResultsForm, $extendedStyles),
        ));
    }

    /**
     * Returns the hosted URL for the account's site map.
     */
    public function siteMapUrl(): HostedSearchUrl
    {
        return $this->urlWith([['si', $this->account->siteId], ['m', '0'], ['p', '0']]);
    }

    /**
     * Returns the hosted URL for the account's what's-new listing.
     */
    public function whatsNewUrl(): HostedSearchUrl
    {
        return $this->urlWith([['si', $this->account->siteId], ['w', '0'], ['p', '0']]);
    }

    /**
     * Returns the account's FreeFind site-index URL.
     */
    public function indexUrl(): HostedSearchUrl
    {
        return $this->accountUrl($this->account->indexEndpoint, [['id', $this->account->siteId]]);
    }

    /**
     * Returns the endpoint used as the action of a hosted search form.
     */
    public function formAction(): HostedSearchUrl
    {
        return new HostedSearchUrl($this->account->htmlEndpoint);
    }

    /**
     * Produces the ordered fields used by the hosted search form or URL.
     *
     * @param  list<string>  $sections
     * @return list<array{0: string, 1: string}>
     *
     * @throws InvalidMarkupException When a section, language, or query is invalid.
     */
    public function formPairs(
        ?string $query,
        array $sections,
        Language|string|null $language,
        bool $hideResultsForm,
        bool $extendedStyles,
    ): array {
        $pairs = [['si', $this->account->siteId]];

        if ($query !== null) {
            $pairs[] = ['query', $this->safeValue($query, 'query')];
        }

        foreach ($sections as $section) {
            $pairs[] = ['s', $this->sectionId($section)];
        }

        if ($language !== null) {
            $pairs[] = ['lang', $language instanceof Language ? $language->code : Language::fromCode($language)->code];
        }

        if ($hideResultsForm) {
            $pairs[] = ['nsb', ''];
        }

        if ($extendedStyles) {
            $pairs[] = ['css', ''];
        }

        return $pairs;
    }

    /**
     * Appends ordered fields to the configured hosted-search endpoint.
     *
     * @param  list<array{0: string, 1: string}>  $pairs
     *
     * @throws InvalidMarkupException When the generated URL is invalid.
     */
    private function urlWith(array $pairs): HostedSearchUrl
    {
        return $this->accountUrl($this->account->htmlEndpoint, $pairs);
    }

    /**
     * Appends ordered fields to a configured account endpoint.
     *
     * @param  list<array{0: string, 1: string}>  $pairs
     *
     * @throws InvalidMarkupException When the generated URL is invalid.
     */
    private function accountUrl(string $endpoint, array $pairs): HostedSearchUrl
    {
        return new HostedSearchUrl($endpoint . '?' . $this->encoder->encode($pairs));
    }

    /**
     * Validates and returns one hosted section identifier.
     *
     * @throws InvalidMarkupException When the section identifier is invalid.
     */
    private function sectionId(string $section): string
    {
        if ($section === '') {
            return '';
        }

        return Section::from($section, $section)->id;
    }

    /**
     * Validates arbitrary user-controlled hosted-search text.
     *
     * @throws InvalidMarkupException When the value contains invalid UTF-8 or control characters.
     */
    private function safeValue(string $value, string $name): string
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidMarkupException("Hosted FreeFind {$name} values cannot contain control characters or invalid UTF-8.");
        }

        return $value;
    }
}
