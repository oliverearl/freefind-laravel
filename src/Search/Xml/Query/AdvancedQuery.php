<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

/**
 * The four optional field values supported by FreeFind's advanced search form.
 */
final readonly class AdvancedQuery
{
    /**
     * Creates an advanced query with at least one non-empty field.
     *
     * @throws InvalidSearchRequestException When every field is empty or a field contains unsafe text.
     */
    public function __construct(
        public ?string $allWords = null,
        public ?string $exactPhrase = null,
        public ?string $anyWords = null,
        public ?string $withoutWords = null,
    ) {
        $values = [$this->allWords, $this->exactPhrase, $this->anyWords, $this->withoutWords];

        if (! array_filter($values, static fn(?string $value): bool => $value !== null && trim($value) !== '')) {
            throw new InvalidSearchRequestException('An advanced FreeFind search must contain at least one query field.');
        }

        foreach ($values as $value) {
            if ($value !== null) {
                self::assertText($value);
            }
        }
    }

    /**
     * Returns the query fields as repeated FreeFind request pairs.
     *
     * @return list<array{0: string, 1: string}>
     */
    public function pairs(): array
    {
        $pairs = [];

        foreach ([
            'q1' => $this->allWords,
            'q2' => $this->exactPhrase,
            'q3' => $this->anyWords,
            'q4' => $this->withoutWords,
        ] as $name => $value) {
            if ($value !== null) {
                $pairs[] = [$name, $value];
            }
        }

        return $pairs;
    }

    /**
     * Validates one optional advanced-query field.
     *
     * @throws InvalidSearchRequestException When the value contains invalid UTF-8 or control characters.
     */
    private static function assertText(string $value): void
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidSearchRequestException('FreeFind advanced query fields must be valid text without control characters.');
        }
    }
}
