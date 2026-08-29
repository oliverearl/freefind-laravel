<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use DateTimeInterface;
use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class WhatsNewEntry
{
    public function __construct(
        public ?DateTimeInterface $date = null,
        public ?AbsoluteUrl $icon = null,
        public ?string $comment = null,
    ) {
        if ($this->date === null && $this->icon === null && $this->comment === null) {
            throw new InvalidMarkup('A FreeFind whats-new annotation must provide at least one value.');
        }

        if ($this->comment === '') {
            throw new InvalidMarkup('The FreeFind whats-new comment must not be empty.');
        }

        if ($this->comment !== null) {
            HtmlCommentEscaper::assertSafe($this->comment);
        }
    }

    public static function from(
        ?DateTimeInterface $date = null,
        string|AbsoluteUrl|null $icon = null,
        ?string $comment = null,
    ): self {
        return new self($date, $icon === null ? null : AbsoluteUrl::from($icon), $comment);
    }
}
