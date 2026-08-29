<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

use Freefind\Freefind\Exceptions\InvalidSearchRequestException;

final readonly class AdvancedQuery
{
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

    private static function assertText(string $value): void
    {
        if (preg_match('//u', $value) !== 1 || preg_match('/[\x00-\x1F\x7F]/', $value) === 1) {
            throw new InvalidSearchRequestException('FreeFind advanced query fields must be valid text without control characters.');
        }
    }
}
