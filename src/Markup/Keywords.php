<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Ordered keywords and their FreeFind relevance weight.
 */
final readonly class Keywords
{
    /**
     * @var list<string>
     */
    public array $words;

    public int $count;

    /**
     * Creates a validated keyword list and relevance weight.
     *
     * @param  array<array-key, mixed>  $words  Values are validated as an ordered list of safe, non-empty strings.
     *
     * @throws InvalidMarkupException When the list or weight is outside the supported range or contains unsafe text.
     */
    public function __construct(array $words, int $count)
    {
        if ($words === [] || count($words) > 100) {
            throw new InvalidMarkupException('FreeFind keywords must contain between one and 100 words.');
        }

        if ($count < 1 || $count > 100) {
            throw new InvalidMarkupException('FreeFind keyword weight must be between 1 and 100.');
        }

        if (! array_is_list($words)) {
            throw new InvalidMarkupException('FreeFind keywords must be provided as an ordered list.');
        }

        foreach ($words as $word) {
            if (! is_string($word) || $word === '') {
                throw new InvalidMarkupException('FreeFind keywords cannot contain empty words.');
            }

            HtmlCommentEscaper::assertSafe($word);
        }

        /** @var list<string> $words */
        $this->words = $words;
        $this->count = $count;
    }

    /**
     * Creates keywords, defaulting the weight to the number of supplied words.
     *
     * @param  array<array-key, mixed>  $words  Values are validated as an ordered list of safe, non-empty strings.
     *
     * @throws InvalidMarkupException When a word or the derived weight is invalid.
     */
    public static function from(array $words, ?int $count = null): self
    {
        return new self($words, $count ?? count($words));
    }
}
