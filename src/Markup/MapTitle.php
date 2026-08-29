<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Safe title text for a FreeFind site-map annotation.
 */
final readonly class MapTitle
{
    /**
     * Creates a validated site-map title.
     *
     * @throws InvalidMarkupException When the title is empty or unsafe for an HTML comment.
     */
    public function __construct(public string $title)
    {
        if ($this->title === '') {
            throw new InvalidMarkupException('The FreeFind map title must not be empty.');
        }

        HtmlCommentEscaper::assertSafe($this->title);
    }

    /**
     * Creates a site-map title value object.
     *
     * @throws InvalidMarkupException When the title is empty or unsafe for an HTML comment.
     */
    public static function from(string $title): self
    {
        return new self($title);
    }
}
