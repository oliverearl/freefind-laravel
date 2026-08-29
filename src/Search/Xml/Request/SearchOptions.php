<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Request;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;
use Freefind\Freefind\Search\Xml\Query\DescriptionLength;
use Freefind\Freefind\Search\Xml\Query\SortOrder;
use Freefind\Freefind\Search\Xml\Query\Stemming;
use Illuminate\Support\Str;

final readonly class SearchOptions
{
    /**
     * @param  array<array-key, mixed>  $sections
     */
    public function __construct(
        public bool $accentSensitive = false,
        public bool $caseSensitive = false,
        public DescriptionLength $descriptionLength = DescriptionLength::Medium,
        public int $offset = 0,
        public int $resultsPerPage = 10,
        public array $sections = [],
        public SortOrder $sort = SortOrder::Relevance,
        public Stemming $stemming = Stemming::Auto,
    ) {
        if ($this->offset < 0) {
            throw new InvalidSearchRequestException('FreeFind search offsets must not be negative.');
        }

        if ($this->resultsPerPage < 1 || $this->resultsPerPage > 25) {
            throw new InvalidSearchRequestException('FreeFind results per page must be between 1 and 25.');
        }

        $seen = [];

        foreach ($this->sections as $section) {
            if (! is_string($section) || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$/', $section)) {
                throw new InvalidSearchRequestException('FreeFind XML sections must be single-word identifiers.');
            }

            if (Str::lower($section) === 'web') {
                throw new InvalidSearchRequestException('The FreeFind XML client does not support web search sections.');
            }

            if (in_array($section, $seen, true)) {
                throw new InvalidSearchRequestException('FreeFind XML sections must not be repeated.');
            }

            $seen[] = $section;
        }
    }

    /**
     * @return list<array{0: string, 1: string}>
     */
    public function pairs(): array
    {
        $pairs = [];

        if ($this->accentSensitive) {
            $pairs[] = ['asen', 'y'];
        }

        if ($this->caseSensitive) {
            $pairs[] = ['csen', 'y'];
        }

        if ($this->descriptionLength !== DescriptionLength::Medium) {
            $pairs[] = ['dl', $this->descriptionLength->value];
        }

        if ($this->offset > 0) {
            $pairs[] = ['fr', (string) $this->offset];
        }

        if ($this->resultsPerPage !== 10) {
            $pairs[] = ['rpp', (string) $this->resultsPerPage];
        }

        foreach ($this->sections as $section) {
            $pairs[] = ['s', $section];
        }

        if ($this->sort !== SortOrder::Relevance) {
            $pairs[] = ['srt', $this->sort->value];
        }

        if ($this->stemming !== Stemming::Auto) {
            $pairs[] = ['stm', $this->stemming->value];
        }

        return $pairs;
    }
}
