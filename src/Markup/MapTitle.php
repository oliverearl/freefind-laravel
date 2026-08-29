<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class MapTitle
{
    public function __construct(public string $title)
    {
        if ($this->title === '') {
            throw new InvalidMarkup('The FreeFind map title must not be empty.');
        }

        HtmlCommentEscaper::assertSafe($this->title);
    }

    public static function from(string $title): self
    {
        return new self($title);
    }
}
