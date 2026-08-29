<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use DateTimeInterface;

final readonly class DocumentDate
{
    public function __construct(public DateTimeInterface $date) {}

    public static function from(DateTimeInterface $date): self
    {
        return new self($date);
    }
}
