<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class Keywords
{
    /**
     * @var list<string>
     */
    public array $words;

    public int $count;

    /**
     * @param  array<mixed>  $words
     */
    public function __construct(array $words, int $count)
    {
        if ($words === [] || count($words) > 100) {
            throw new InvalidMarkup('FreeFind keywords must contain between one and 100 words.');
        }

        if ($count < 1 || $count > 100) {
            throw new InvalidMarkup('FreeFind keyword weight must be between 1 and 100.');
        }

        if (! array_is_list($words)) {
            throw new InvalidMarkup('FreeFind keywords must be provided as an ordered list.');
        }

        foreach ($words as $word) {
            if (! is_string($word) || $word === '') {
                throw new InvalidMarkup('FreeFind keywords cannot contain empty words.');
            }

            HtmlCommentEscaper::assertSafe($word);
        }

        /** @var list<string> $words */
        $this->words = $words;
        $this->count = $count;
    }

    /**
     * @param  array<mixed>  $words
     */
    public static function from(array $words, ?int $count = null): self
    {
        return new self($words, $count ?? count($words));
    }
}
