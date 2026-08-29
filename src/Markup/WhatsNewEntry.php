<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use DateTimeInterface;
use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Optional date, icon, and comment metadata for a FreeFind what's-new annotation.
 */
final readonly class WhatsNewEntry
{
    /**
     * Creates a what's-new entry with at least one safe value.
     *
     * @throws InvalidMarkupException When no value is supplied or the comment is empty or unsafe.
     */
    public function __construct(
        public ?DateTimeInterface $date = null,
        public ?AbsoluteUrl $icon = null,
        public ?string $comment = null,
    ) {
        if ($this->date === null && $this->icon === null && $this->comment === null) {
            throw new InvalidMarkupException('A FreeFind whats-new annotation must provide at least one value.');
        }

        if ($this->comment === '') {
            throw new InvalidMarkupException('The FreeFind whats-new comment must not be empty.');
        }

        if ($this->comment !== null) {
            HtmlCommentEscaper::assertSafe($this->comment);
        }
    }

    /**
     * Creates a what's-new entry from an icon string or existing absolute URL.
     *
     * @throws InvalidMarkupException When no value is supplied or an icon/comment is invalid.
     */
    public static function from(
        ?DateTimeInterface $date = null,
        string|AbsoluteUrl|null $icon = null,
        ?string $comment = null,
    ): self {
        return new self($date, $icon === null ? null : AbsoluteUrl::from($icon), $comment);
    }
}
