<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use DateTimeInterface;

/**
 * Document publication or modification date rendered as a FreeFind head annotation.
 */
final readonly class DocumentDate
{
    /**
     * Creates a document-date value from any supported date implementation.
     */
    public function __construct(public DateTimeInterface $date) {}

    /**
     * Creates a document-date value from any supported date implementation.
     */
    public static function from(DateTimeInterface $date): self
    {
        return new self($date);
    }
}
