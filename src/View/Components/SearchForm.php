<?php

declare(strict_types=1);

namespace Freefind\Freefind\View\Components;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Freefind\Freefind\Markup\BrowsingContextName;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Search\Hosted\Language;
use Freefind\Freefind\Search\Hosted\Section;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Accessible, unstyled form that submits searches to FreeFind's hosted endpoint.
 */
final class SearchForm extends Component
{
    /**
     * Creates a hosted-search form with validated labels, sections, and browser target.
     *
     * @param array<array-key, mixed> $sections Map of FreeFind section IDs to display labels; entries are validated at runtime.
     *
     * @throws InvalidMarkupException When a form value is unsafe or invalid.
     */
    public function __construct(
        private readonly HostedSearch $search,
        public string $label = 'Search this site',
        public array $sections = [],
        public Language|string|null $language = null,
        public bool $hideResultsForm = false,
        public bool $extendedStyles = false,
        public ?string $query = null,
        public string $inputId = 'freefind-query',
        public string $submitLabel = 'Search',
        public string $method = 'get',
        public ?string $target = null,
    ) {
        if ($this->label === '' || $this->submitLabel === '') {
            throw new InvalidMarkupException('FreeFind search form labels must not be empty.');
        }

        foreach ([$this->label, $this->submitLabel] as $text) {
            if (preg_match('//u', $text) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $text) === 1) {
                throw new InvalidMarkupException('FreeFind search form labels must be valid text without control characters.');
            }
        }

        if ($this->method !== 'get') {
            throw new InvalidMarkupException('FreeFind hosted search forms only support the GET method.');
        }

        if (! preg_match('/^[A-Za-z][A-Za-z0-9_-]{0,63}$/', $this->inputId)) {
            throw new InvalidMarkupException('FreeFind search form input IDs must be valid HTML identifiers.');
        }

        if ($this->target !== null && ! BrowsingContextName::isValid($this->target)) {
            throw new InvalidMarkupException('FreeFind search form targets must be valid browsing-context names.');
        }

        foreach ($this->sections as $id => $label) {
            if (! is_string($id) || ! is_string($label)) {
                throw new InvalidMarkupException('FreeFind search form sections must be a string identifier-to-label map.');
            }

            Section::from($id, $label);
        }
    }

    /**
     * Returns the configured hosted-search form action URL.
     */
    public function action(): string
    {
        return $this->search->formAction()->value;
    }

    /**
     * Returns the explicit query or a validated query from the current request.
     *
     * @throws InvalidMarkupException When the request query contains invalid text.
     */
    public function queryValue(): string
    {
        if ($this->query !== null) {
            return $this->safeQuery($this->query);
        }

        $query = request()->query('query', '');

        return is_string($query) ? $this->safeQuery($query) : '';
    }

    /**
     * Returns the normalized language code for the form, if configured.
     *
     * @throws InvalidMarkupException When a configured language is not supported or syntactically valid.
     */
    public function languageValue(): ?string
    {
        return $this->language instanceof Language
            ? $this->language->code
            : ($this->language === null ? null : Language::fromCode($this->language)->code);
    }

    /**
     * Converts the configured section map into validated option values.
     *
     * @return list<Section>
     *
     * @throws InvalidMarkupException When a section ID or label is invalid.
     */
    public function sectionOptions(): array
    {
        $options = [];

        foreach ($this->sections as $id => $label) {
            if (! is_string($id) || ! is_string($label)) {
                throw new InvalidMarkupException('FreeFind search form sections must be a string identifier-to-label map.');
            }

            $options[] = Section::from($id, $label);
        }

        return $options;
    }

    /**
     * Validates query text before it is placed in an HTML input.
     *
     * @throws InvalidMarkupException When the query contains invalid UTF-8 or controls.
     */
    private function safeQuery(string $query): string
    {
        if (preg_match('//u', $query) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $query) === 1) {
            throw new InvalidMarkupException('FreeFind search queries must be valid text without control characters.');
        }

        return $query;
    }

    /**
     * Returns string-valued section selections from the current request.
     *
     * @return list<string>
     */
    public function selectedSections(): array
    {
        /** @var mixed $sections */
        $sections = request()->query('s', []);

        if (is_string($sections)) {
            return [$sections];
        }

        if (! is_array($sections)) {
            return [];
        }

        return array_values(array_filter($sections, 'is_string'));
    }

    /**
     * Returns the package's semantic hosted-search form view.
     */
    public function render(): View
    {
        return view()->make('freefind-laravel::components.search-form');
    }
}
