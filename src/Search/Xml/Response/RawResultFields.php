<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

/**
 * Original XML field contents, including any FreeFind highlight markup.
 *
 * These values are untrusted remote content and must be sanitized before HTML rendering.
 */
final readonly class RawResultFields
{
    /**
     * Stores the original field values for callers that deliberately handle remote markup.
     */
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $displayUrl,
    ) {}
}
