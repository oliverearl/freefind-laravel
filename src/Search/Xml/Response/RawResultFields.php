<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

final readonly class RawResultFields
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $displayUrl,
    ) {}
}
