<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use DateTimeInterface;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Exceptions\MalformedXmlResponse;

final readonly class SearchResult
{
    public function __construct(
        public ?int $number,
        public string $title,
        public string $description,
        public AbsoluteUrl $url,
        public ?string $target,
        public string $displayUrl,
        public ?DateTimeInterface $date,
        public RawResultFields $raw,
    ) {
        if ($this->number !== null && $this->number < 0) {
            throw new MalformedXmlResponse('A FreeFind result number was negative.');
        }

        if ($this->target !== null && ! preg_match('/^(?:_(?:blank|self|parent|top)|[A-Za-z][A-Za-z0-9:_-]{0,63})$/', $this->target)) {
            throw new MalformedXmlResponse('A FreeFind result contained an unsafe link target.');
        }
    }
}
