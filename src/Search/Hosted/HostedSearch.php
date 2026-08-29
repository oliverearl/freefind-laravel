<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class HostedSearch
{
    public function __construct(
        private Account $account,
        private HostedQueryEncoder $encoder = new HostedQueryEncoder(),
    ) {}

    /**
     * @param  list<string>  $sections
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

    public function siteMapUrl(): HostedSearchUrl
    {
        return $this->urlWith([['si', $this->account->siteId], ['m', '0'], ['p', '0']]);
    }

    public function whatsNewUrl(): HostedSearchUrl
    {
        return $this->urlWith([['si', $this->account->siteId], ['w', '0'], ['p', '0']]);
    }

    public function indexUrl(): HostedSearchUrl
    {
        return $this->accountUrl($this->account->indexEndpoint, [['id', $this->account->siteId]]);
    }

    public function formAction(): HostedSearchUrl
    {
        return new HostedSearchUrl($this->account->htmlEndpoint);
    }

    /**
     * @param  list<string>  $sections
     * @return list<array{0: string, 1: string}>
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
     * @param  list<array{0: string, 1: string}>  $pairs
     */
    private function urlWith(array $pairs): HostedSearchUrl
    {
        return $this->accountUrl($this->account->htmlEndpoint, $pairs);
    }

    /**
     * @param  list<array{0: string, 1: string}>  $pairs
     */
    private function accountUrl(string $endpoint, array $pairs): HostedSearchUrl
    {
        return new HostedSearchUrl($endpoint . '?' . $this->encoder->encode($pairs));
    }

    private function sectionId(string $section): string
    {
        if ($section === '') {
            return '';
        }

        return Section::from($section, $section)->id;
    }

    private function safeValue(string $value, string $name): string
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidMarkup("Hosted FreeFind {$name} values cannot contain control characters or invalid UTF-8.");
        }

        return $value;
    }
}
