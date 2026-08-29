<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use DateTimeInterface;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Markup\BrowsingContextName;
use Freefind\Freefind\Exceptions\MalformedXmlResponseException;

/**
 * One safely normalized result returned by FreeFind's XML Page Search feed.
 */
final readonly class SearchResult
{
    /**
     * Creates a result after validating its number and link target.
     *
     * @throws MalformedXmlResponseException When the result number or target is invalid.
     */
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
            throw new MalformedXmlResponseException('A FreeFind result number was negative.');
        }

        if ($this->target !== null && ! BrowsingContextName::isValid($this->target)) {
            throw new MalformedXmlResponseException('A FreeFind result contained an unsafe link target.');
        }
    }
}
